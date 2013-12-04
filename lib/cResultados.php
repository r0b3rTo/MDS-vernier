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
    
    if(isset($_GET['token_ls'])){
    
      $sql="SELECT id_encuesta_ls FROM PERSONA_ENCUESTA WHERE token_ls='".$_GET['token_ls']."'";
      $atts = array("id_encuesta_ls");
      $resultado= obtenerDatos($sql, $conexion, $atts, "Enc"); //ID de la encuesta para el token del usuario
      
      $id_encuesta_ls=$resultado['Enc']['id_encuesta_ls'][0];
      $sql="SELECT id_pregunta FROM PREGUNTA WHERE id_encuesta_ls='".$id_encuesta_ls."'";
      $atts = array("id_pregunta", "resultado");
      $resultado= obtenerDatos($sql, $conexion, $atts, "Preg"); //Lista de preguntas

      for($i=0; $i<$resultado[max_res] ;$i++){
	$id_pregunta_i=$resultado['Preg']['id_pregunta'][$i];
	$sql="SELECT respuesta FROM RESPUESTA WHERE id_pregunta='".$id_pregunta_i."'";
	$atts = array("respuesta");
	$aux= obtenerDatos($sql, $conexion, $atts, "Res");
	$resultado['Preg']['resultado'][$i]=$aux['Res']['respuesta'][0];
      }
      
    }
    
    //Cierre conexión a la BD
    cerrarConexion($conexion);

    if (isset($_GET['action'])){
        switch ($_GET['action']) {
        
            case 'add':
                $_SESSION['MSJ'] = "Se ha agregado un nuevo proceso de evaluación";
                header("Location: ../vEvaluaciones.php?success"); 
                break;
		
            default:
                # code...
                break;            
        }
    }
  
?> 


