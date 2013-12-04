<?php
    session_start();
    require "cAutorizacion.php";
    extract($_GET);
    extract($_POST);
    date_default_timezone_set('America/Caracas');
    $_ERRORES = array();
    $_WARNING = array();
    $_SUCCESS = array();
    require_once 'XML/RPC2/Client.php';
    
    // Obtención de las encuestas definidas
    $sql ="SELECT * ";
    $sql.="FROM ENCUESTA ORDER BY id_car";        
    $atts = array("id_car", "id_encuesta_ls", "fecha_ini", "fecha_fin", "estado");
    $LISTA_ENCUESTA= obtenerDatos($sql, $conexion, $atts, "Enc");
    
    if ($LISTA_ENCUESTA['max_res']>0) {
    // Obtención de los cargos
    $sql ="SELECT nombre ";
    $sql.="FROM CARGO ";
    $sql.="WHERE id='";        
    for ($i=0;$i<$LISTA_ENCUESTA['max_res'];$i++){    
      $sql.=$LISTA_ENCUESTA['Enc']['id_car'][$i];
      if($i == $LISTA_ENCUESTA['max_res']-1) {
	  $sql.="'";
      } else {
	  $sql.="' OR id='";
      }
    }
    $atts = array("nombre"); 
    $LISTA_CARGOS = obtenerDatos($sql, $conexion, $atts, "Car");
    }
    
    //Obtener las preguntas de la sección de factores de la encuesta
    if (isset($_GET['id_encuesta_ls'])){
      $sql ="SELECT id_encuesta_ls, id_pregunta_ls, id_pregunta_root_ls, titulo, peso, seccion, id_pregunta ";
      $sql.="FROM PREGUNTA WHERE id_encuesta_ls='".$_GET['id_encuesta_ls']."' AND seccion='factor' ORDER BY id_pregunta";        
      $atts = array("id_encuesta_ls","id_pregunta_ls", "id_pregunta_root_ls", "titulo", "peso", "seccion", "id_pregunta");
      $LISTA_PREGUNTA= obtenerDatos($sql, $conexion, $atts, "Preg"); //Lista de preguntas
    }
    
    if (isset($_GET['action'])){
      switch ($_GET['action']) {
      
            case 'set':
            
		//Modificar los pesos de los factores
		for ($i=0; $i<$LISTA_PREGUNTA[max_res]; $i++){
		  $id_pregunta=$LISTA_PREGUNTA['Preg']['id_pregunta'][$i];
		  $tag='peso_'.$id_pregunta;
		  if($_POST[$tag]!='-'){
		    $sql="UPDATE PREGUNTA SET peso='".$_POST[$tag]."' WHERE id_pregunta='".$id_pregunta."'";
		    echo $sql;
		    $resultado_sql=ejecutarConsulta($sql,$conexion);
		  } else {
		    $sql="UPDATE PREGUNTA SET peso=NULL WHERE id_pregunta='".$id_pregunta."'";
		    echo $sql;
		    $resultado_sql=ejecutarConsulta($sql,$conexion);
		  }
		} //cierre iteración sobre las preguntas

	      break;
	
	    default:
	      # code...
	      break;
	    }
        
    }
    
    if (isset($_GET['action'])){
        switch ($_GET['action']) {
        
            case 'set':
                $_SESSION['MSJ'] = "Los pesos asociados a los factores han sido modificados";
                header("Location: ../vEncuestas.php?success"); 
                break;
                
            default:
                # code...
                break;            
        }

    }
  
?> 


