<?php
    require "cAutorizacion.php";
    extract($_GET);
    extract($_POST);
    $_ERRORES = array();
    $_WARNING = array();
    $_SUCCESS = array();
    date_default_timezone_set('America/Caracas');
    
    require_once 'XML/RPC2/Client.php';

    // Obtención de datos del usuario
    $atts = array("id");
    $sql ="SELECT id ";
    $sql.="FROM PERSONA ";
    if (isset($_SESSION['cedula'])) {
	$cedula= $_SESSION['cedula'];
        $sql.="WHERE cedula='".$cedula."'";
    }
    
    $PERSONA = obtenerDatos($sql, $conexion, $atts, "Per");
    $id_usuario=$PERSONA['Per']['id'][0];

    //Evaluaciones actuales
    ///////////////////////
      // Obtención del identificador, tipo, estado, periodo y token de Limesurvey de las encuestas del usuario
      $sql ="SELECT id_encuesta_ls, id_evaluado, token_ls, tipo, estado, periodo ";
      $sql.="FROM PERSONA_ENCUESTA ";
      $sql.="WHERE id_encuestado='".$id_usuario."' AND actual='t'";
	  
      $atts = array("id_encuesta_ls", "id_evaluado", "token_ls", "tipo", "estado", "periodo", "nombre", "apellido");
      $LISTA_EVALUACION_ACTUAL= obtenerDatos($sql, $conexion, $atts, "Enc");

      //Obtención de los nombres de los evaluados
      for ($i=0; $i<$LISTA_EVALUACION_ACTUAL[max_res]; $i++){
	$sql ="SELECT nombre, apellido ";
	$sql.="FROM PERSONA ";
	$sql.="WHERE ";
	$sql.= "id='".$LISTA_EVALUACION_ACTUAL["Enc"]["id_evaluado"][$i]."'";
	$atts = array("nombre", "apellido");
	$NOMBRE= obtenerDatos($sql, $conexion, $atts, "Nom");
	$LISTA_EVALUACION_ACTUAL["Enc"]["nombre"][$i]=$NOMBRE["Nom"]["nombre"][0];
	$LISTA_EVALUACION_ACTUAL["Enc"]["apellido"][$i]=$NOMBRE["Nom"]["apellido"][0];
      }
      
    //Evaluaciones pasadas
    //////////////////////
      // Obtención del identificador, tipo, estado y token de Limesurvey de las encuestas del usuario
      $sql ="SELECT id_encuesta_ls, id_evaluado, token_ls, tipo, estado, periodo ";
      $sql.="FROM PERSONA_ENCUESTA ";
      $sql.="WHERE id_encuestado='".$id_usuario."' AND actual='f'";
	  
      $atts = array("id_encuesta_ls", "id_evaluado", "token_ls", "tipo", "estado", "periodo", "nombre", "apellido");
      $LISTA_EVALUACION_PASADA= obtenerDatos($sql, $conexion, $atts, "Enc");
      
      //Obtención de los nombres de los evaluados
      for ($i=0; $i<$LISTA_EVALUACION_PASADA[max_res]; $i++){
	$sql ="SELECT nombre, apellido ";
	$sql.="FROM PERSONA ";
	$sql.="WHERE ";
	$sql.= "id='".$LISTA_EVALUACION_PASADA["Enc"]["id_evaluado"][$i]."'";
	$atts = array("nombre", "apellido");
	$NOMBRE= obtenerDatos($sql, $conexion, $atts, "Nom");
	$LISTA_EVALUACION_PASADA["Enc"]["nombre"][$i]=$NOMBRE["Nom"]["nombre"][0];
	$LISTA_EVALUACION_PASADA["Enc"]["apellido"][$i]=$NOMBRE["Nom"]["apellido"][0];
      }
      
    if (isset($_GET['token_ls']) && isset($_GET['id_encuesta_ls'])) {
	//Determinar estado de la encuesta
	  $client_ls = XML_RPC2_Client::create('http://localhost/limesurvey/index.php/admin/remotecontrol'); //Crear un cliente para comunicarse con Limesurvey
	  $session_key = $client_ls->get_session_key('admin', 'Segundo!!');//Pedir llave de acceso a Limesurvey
	  $id_encuesta_ls=intval($_GET['id_encuesta_ls']);
	  $token_ls=$_GET['token_ls'];
	  //Hallar token ID y get_particpant_info
	  $completed= $client_ls->get_summary($session_key, $id_encuesta_ls, 'token_completed');//Determinar si completó la encuesta
	  $resultado=$client_ls->release_session_key($session_key);//Devolver llave de acceso a Limesurvey
	  $ip=$_SERVER['REMOTE_ADDR'];
	  $fecha_intento=date("d/m/Y.H:i");
	  
	  if ($completed==1){
	    $sql ="UPDATE PERSONA_ENCUESTA SET estado='Finalizada', ip='".$ip."', fecha='".$fecha_intento."' ";
	    $sql.="WHERE ";
	    $sql.= "token_ls='".$token_ls."'";
	    $resultado=ejecutarConsulta($sql, $conexion);
	    $_SESSION['MSJ'] = "Sus respuestas han sido procesadas. Gracias por realizar la encuesta";
            header("Location: ./vListarEvaluaciones.php?success"); 
	  } else {
	    $sql ="UPDATE PERSONA_ENCUESTA SET estado='En proceso', ip='".$ip."', fecha='".$fecha_intento."' ";
	    $sql.="WHERE ";
	    $sql.= "token_ls='".$token_ls."'";
	    $resultado=ejecutarConsulta($sql, $conexion);
	    $_SESSION['MSJ'] = "Sus respuestas han sido procesadas. Recuerde completar la encuesta antes de finalizar el periodo de evaluación";
            header("Location: ./vListarEvaluaciones.php?warning"); 
	 }
    }
    
    cerrarConexion($conexion);
    
?>
