<?php
    session_start();
    require "cAutorizacion.php";
    extract($_GET);
    extract($_POST);
    date_default_timezone_set('America/Caracas');
    $_ERRORES = array();
    $_WARNING = array();
    $_SUCCESS = array();
    
    // Obtención de las encuestas definidas
    $sql ="SELECT * ";
    $sql.="FROM ENCUESTA WHERE actual='t' ORDER BY id_fam";        
    $atts = array("id", "id_encuesta_ls", "id_fam", "id_unidad", "estado");
    $LISTA_ENCUESTA= obtenerDatos($sql, $conexion, $atts, "Enc");
    
    // Obtención de las familias de cargos
    // Obtención de las unidades evaluadas
    for ($i=0;$i<$LISTA_ENCUESTA['max_res'];$i++){    
      $sql ="SELECT nombre FROM FAMILIA_CARGO WHERE id='".$LISTA_ENCUESTA['Enc']['id_fam'][$i]."'";   
      $sql_1 ="SELECT nombre FROM ORGANIZACION WHERE id='".$LISTA_ENCUESTA['Enc']['id_unidad'][$i]."'"; 
      $atts = array("nombre"); 
      $aux= obtenerDatos($sql, $conexion, $atts, "Car");
      $LISTA_CARGOS[$i]=$aux['Car']['nombre'][0];
      $aux= obtenerDatos($sql_1, $conexion, $atts, "Uni");
      $LISTA_UNIDADES[$i]=$aux['Uni']['nombre'][0];
    }

    
    
    //Obtener las preguntas de la sección de factores de la encuesta
    if (isset($_GET['id_encuesta'])){
      $sql ="SELECT id_encuesta, id_encuesta_ls, id_pregunta_ls, id_pregunta_root_ls, titulo, peso, seccion, id_pregunta ";
      $sql.="FROM PREGUNTA WHERE id_encuesta='".$_GET['id_encuesta']."' AND seccion='factor' ORDER BY id_pregunta";        
      $atts = array("id_encuesta", "id_encuesta_ls","id_pregunta_ls", "id_pregunta_root_ls", "titulo", "peso", "seccion", "id_pregunta");
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
		    $peso=$_POST[$tag]/100;
		    $sql="UPDATE PREGUNTA SET peso='".$peso."' WHERE id_pregunta='".$id_pregunta."'";
		    $resultado_sql=ejecutarConsulta($sql,$conexion);
		  } else {
		    $sql="UPDATE PREGUNTA SET peso=NULL WHERE id_pregunta='".$id_pregunta."'";
		    $resultado_sql=ejecutarConsulta($sql,$conexion);
		  }
		} //cierre iteración sobre las preguntas

	      break;
	
	    case 'delete':
	    
	      //Actualizar vigencia de la encuesta (antigua)
	      $sql="UPDATE ENCUESTA SET actual='f' WHERE id='".$_GET['id_encuesta']."'";
	      $resultado_sql=ejecutarConsulta($sql,$conexion);
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
                
            case 'delete':
                $_SESSION['MSJ'] = "La encuesta fue eliminada";
                header("Location: ../vEncuestas.php?success"); 
                break;
                
            default:
                # code...
                break;            
        }

    }
  
?> 


