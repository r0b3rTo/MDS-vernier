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
    
    cerrarConexion($conexion);
    
//    include_once("cEnviarCorreo.php");
    
?>
