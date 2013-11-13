<?php
    require "cAutorizacion.php";
    extract($_GET);
    extract($_POST);
    //include "cMail.php";
//  del post
//  asun, fecha , tipo, descrip
    $_ERRORES = array();
    $_WARNING = array();
    $_SUCCESS = array();
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

    // Obtención del identificador, tipo, estado y token de Limesurvey de las encuestas del usuario
    $sql ="SELECT id_encuesta_ls, id_evaluado, token_ls, tipo, estado ";
    $sql.="FROM PERSONA_ENCUESTA ";
    $sql.="WHERE id_encuestado='".$id_usuario."'";
        
    $atts = array("id_encuesta_ls", "id_evaluado", "token_ls", "tipo", "estado", "nombre", "apellido");
    $LISTA_ENCUESTA= obtenerDatos($sql, $conexion, $atts, "Enc");
    
    //Obtención de los nombres de los evaluados
    for ($i=0; $i<$LISTA_ENCUESTA[max_res]; $i++){
      $sql ="SELECT nombre, apellido ";
      $sql.="FROM PERSONA ";
      $sql.="WHERE ";
      $sql.= "id='".$LISTA_ENCUESTA["Enc"]["id_evaluado"][$i]."'";
      $atts = array("nombre", "apellido");
      $NOMBRE= obtenerDatos($sql, $conexion, $atts, "Nom");
      $LISTA_ENCUESTA["Enc"]["nombre"][$i]=$NOMBRE["Nom"]["nombre"][0];
      $LISTA_ENCUESTA["Enc"]["apellido"][$i]=$NOMBRE["Nom"]["apellido"][0];
    }
    
    if (isset($_GET['token_ls']) && isset($_GET['id_encuesta_ls'])) {
	//Determinar estado de la encuesta
	  $client_ls = XML_RPC2_Client::create('http://localhost/limesurvey/index.php/admin/remotecontrol'); //Crear un cliente para comunicarse con Limesurvey
	  $session_key = $client_ls->get_session_key('admin', 'Segundo!!');//Pedir llave de acceso a Limesurvey
	  $id_encuesta_ls=intval($_GET['id_encuesta_ls']);
	  $token_ls=$_GET['token_ls'];
	  $completed= $client_ls->get_summary($session_key, $id_encuesta_ls, 'token_completed');//Determinar si completó la encuesta
	  $resultado=$client_ls->release_session_key($session_key);//Devolver llave de acceso a Limesurvey
	  echo"ESTE FUE EL RESULTADO!!!: $completed";
	  if ($completed==1){
	  echo "lo completó!!!";
	    $sql ="UPDATE PERSONA_ENCUESTA SET estado='Completada'";
	    $sql.="WHERE ";
	    $sql.= "token_ls='".$token_ls."'";
	    $resultado=ejecutarConsulta($sql, $conexion);
	    $_SESSION['MSJ'] = "Sus respuestas han sido procesadas. Gracias por completar la encuesta";
            header("Location: ./vListarEncuestas.php?success"); 
	  } else {
	  echo "no está completa!!!";
	    $sql ="UPDATE PERSONA_ENCUESTA SET estado='Por completar'";
	    $sql.="WHERE ";
	    $sql.= "token_ls='".$token_ls."'";
	    $resultado=ejecutarConsulta($sql, $conexion);
	    $_SESSION['MSJ'] = "Sus respuestas han sido procesadas. Recuerde completar la encuesta antes de finalizar el periodo de evaluación";
            header("Location: ./vListarEncuestas.php?warning"); 
	 }
	
	//Si estuviese completada, cambiar el enlace!
	//Agregar success! con mensajito al header :D
	
    }
    
    cerrarConexion($conexion);
    
//    include_once("cEnviarCorreo.php");
    
?>
