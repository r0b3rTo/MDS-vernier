<?php
    session_start();
    require "cAutorizacion.php";
    extract($_GET);
    extract($_POST);
    date_default_timezone_set('America/Caracas');
    $_ERRORES = array();
    $_WARNING = array();
    $_SUCCESS = array();
    
    if (isset($_GET['periodo'])){
    
      // Obtención del proceso de evaluacion
      $sql ="SELECT periodo ";
      $sql.="FROM EVALUACION WHERE id='".$_GET['periodo']."'";        
      $atts = array("periodo");
      $aux= obtenerDatos($sql, $conexion, $atts, "Proc");
      $nombre_periodo= $aux["Proc"]["periodo"][0];//nombre del periodo de evaluación
      
      //Obtención del número total de evaluaciones
      $sql="SELECT tipo, token_ls, id_encuesta_ls, id_encuestado, id_evaluado, estado, ip, fecha FROM PERSONA_ENCUESTA WHERE periodo='";
      $sql.=$nombre_periodo;
      $sql.="'";
      $atts= array("tipo", "token_ls", "id_encuesta_ls", "id_encuestado", "id_evaluado", "estado", "ip", "fecha");
      $LISTA_EVALUACION=obtenerDatos($sql, $conexion, $atts, "Aux");
      
      //Sorting de las evaluaciones según su estado
      /////////////////////////////////////////////
      $j=0; $k=0; $l=0;
      
      
      for ($i=0; $i<$LISTA_EVALUACION[max_res]; $i++){
      
	//Obtención del nombre del evaluado
	$sql ="SELECT nombre, apellido ";
	$sql.="FROM PERSONA ";
	$sql.="WHERE ";
	$sql.= "id='".$LISTA_EVALUACION["Aux"]["id_evaluado"][$i]."'";
	$atts = array("nombre", "apellido");
	$aux= obtenerDatos($sql, $conexion, $atts, "Nom");
	$nombre_evaluado=$aux["Nom"]["nombre"][0]." ".$aux["Nom"]["apellido"][0];
	
	//Obtención del nombre del evaluador
	$sql ="SELECT nombre, apellido ";
	$sql.="FROM PERSONA ";
	$sql.="WHERE ";
	$sql.= "id='".$LISTA_EVALUACION["Aux"]["id_encuestado"][$i]."'";
	$atts = array("nombre", "apellido");
	$aux= obtenerDatos($sql, $conexion, $atts, "Nom");
	$nombre_evaluador=$aux["Nom"]["nombre"][0]." ".$aux["Nom"]["apellido"][0];
   
	//Pedir la fecha y el IP a LimeSurvey para el usuario token_ls de la encuesta id_encuesta_ls
	//PENDIENTE!!!!

	//Agregar a la lista correspondiente
	  if($LISTA_EVALUACION["Aux"]["estado"][$i]=="Pendiente"){
	    $LISTA_PENDIENTE["tipo"][$j]= $LISTA_EVALUACION["Aux"]["tipo"][$i];
	    $LISTA_PENDIENTE["nombre_evaluado"][$j]= $nombre_evaluado;
	    $LISTA_PENDIENTE["nombre_evaluador"][$j]= $nombre_evaluador;
	    $j++;
	  }
	  if($LISTA_EVALUACION["Aux"]["estado"][$i]=="En proceso"){
	    $LISTA_EN_PROCESO["tipo"][$k]= $LISTA_EVALUACION["Aux"]["tipo"][$i];
	    $LISTA_EN_PROCESO["nombre_evaluado"][$k]= $nombre_evaluado;
	    $LISTA_EN_PROCESO["nombre_evaluador"][$k]= $nombre_evaluador;
	    $LISTA_EN_PROCESO["fecha"][$k]= $LISTA_EVALUACION["Aux"]["fecha"][$i];
	    $LISTA_EN_PROCESO["ip"][$k]= $LISTA_EVALUACION["Aux"]["ip"][$i];
	    $k++;	  
	  }
	  if($LISTA_EVALUACION["Aux"]["estado"][$i]=="Finalizada"){
	    $LISTA_FINALIZADA["tipo"][$l]= $LISTA_EVALUACION["Aux"]["tipo"][$i];
	    $LISTA_FINALIZADA["nombre_evaluado"][$l]= $nombre_evaluado;
	    $LISTA_FINALIZADA["nombre_evaluador"][$l]= $nombre_evaluador;
	    $LISTA_FINALIZADA["fecha"][$l]= $LISTA_EVALUACION["Aux"]["fecha"][$i];
	    $LISTA_FINALIZADA["ip"][$l]= $LISTA_EVALUACION["Aux"]["ip"][$i];
	    $l++;	  
	  }
      }
    
    }
    
    //Cierre conexión a la BD
    cerrarConexion($conexion);

  
?> 


