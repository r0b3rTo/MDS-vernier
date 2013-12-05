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
      $sql="SELECT id_pregunta, titulo FROM PREGUNTA WHERE id_encuesta_ls='".$id_encuesta_ls."' AND seccion='competencia'";
      $atts = array("id_pregunta", "titulo", "resultado");
      $LISTA_COMPETENCIAS= obtenerDatos($sql, $conexion, $atts, "Preg"); //Lista de preguntas de la sección de competencias
      $sql="SELECT id_pregunta, titulo FROM PREGUNTA WHERE id_encuesta_ls='".$id_encuesta_ls."' AND seccion='factor'";
      $LISTA_FACTORES= obtenerDatos($sql, $conexion, $atts, "Preg"); //Lista de preguntas de la sección de competencias

      for($i=0; $i<$LISTA_COMPETENCIAS[max_res] ;$i++){
	$id_pregunta_i=$LISTA_COMPETENCIAS['Preg']['id_pregunta'][$i];
	$sql="SELECT respuesta FROM RESPUESTA WHERE id_pregunta='".$id_pregunta_i."'";
	$atts = array("respuesta");
	$aux= obtenerDatos($sql, $conexion, $atts, "Res");
	$LISTA_COMPETENCIAS['Preg']['resultado'][$i]=$aux['Res']['respuesta'][0];
      }
      
      for($i=0; $i<$LISTA_FACTORES[max_res] ;$i++){
	$id_pregunta_i=$LISTA_FACTORES['Preg']['id_pregunta'][$i];
	$sql="SELECT respuesta FROM RESPUESTA WHERE id_pregunta='".$id_pregunta_i."'";
	$atts = array("respuesta");
	$aux= obtenerDatos($sql, $conexion, $atts, "Res");
	$LISTA_FACTORES['Preg']['resultado'][$i]=$aux['Res']['respuesta'][0];
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


