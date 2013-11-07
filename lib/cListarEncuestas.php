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
    $atts = array("id","tipo","nombre","apellido","sexo");
    $sql ="SELECT id, tipo, nombre, apellido, sexo ";
    $sql.="FROM PERSONA ";
    if (isset($_SESSION['cedula'])) {
	$cedula= $_SESSION['cedula'];
        $sql.="WHERE cedula='".$cedula."'";
    }  
    $ID_PER = obtenerDatos($sql, $conexion, $atts, "Per");
    $id_usuario=$ID_PER['Per']['id'][0];
    $NOM_USUARIO=$ID_PER['Per']['nombre'][0]." ".$ID_PER['Per']['apellido'][0];
    
    // Obtención del cargo del usuario
    $sql ="SELECT id_car ";
    $sql.="FROM PERSONA_CARGO ";
    $sql.="WHERE id_per='".$id_usuario."'";   
    $atts = array("id_car");
    $ID_CAR = obtenerDatos($sql, $conexion, $atts, "Car");
    $id_cargo= $ID_CAR["Car"]["id_car"][0];
    
    // Obtención del enlace de la encuesta para el cargo del usuario
    $sql ="SELECT enlace ";
    $sql.="FROM ENCUESTA ";
    $sql.="WHERE id_car='".$id_cargo."'";
        
    $atts = array("enlace");
    $ID_ENCUESTA= obtenerDatos($sql, $conexion, $atts, "Enc");
    $id_enc= $ID_ENCUESTA["Enc"]["enlace"][0];

    //Obtención de ID's supervisados
    $sql_1 ="SELECT id_per ";
    $sql_1.="FROM PERSONA_SUPERVISOR ";
    $sql_1.="WHERE id_sup='".$id_usuario."' AND actual='TRUE' ORDER BY id_per";
    
    //Obtención de los nombres de los supervisados
    $sql_2 ="SELECT nombre, apellido ";
    $sql_2.="FROM PERSONA ";
    $sql_2.="WHERE id IN (";
    $sql_2.="$sql_1) ORDER BY id";
    $atts = array("nombre", "apellido");
    $NOMBRE_SUP = obtenerDatos($sql_2,$conexion,$atts,"Nom_Sup");

    //Obtención de los cargos de los supervisados
    $sql_2 ="SELECT id_car ";
    $sql_2.="FROM PERSONA_CARGO ";
    $sql_2.="WHERE id_per IN (";
    $sql_2.="$sql_1)";
    $atts = array("id_car");
    
    //Obtención de los enlaces a la encuestas correspondientes a los cargos
    $sql_3 ="SELECT enlace ";
    $sql_3.="FROM ENCUESTA ";
    $sql_3.="WHERE id_car IN (";
    $sql_3.="$sql_2)";
    $atts = array("enlace");
    $ENCUESTA_SUP = obtenerDatos($sql_3,$conexion,$atts,"Enc_Sup");

    //Obtención de ID's evaluados
    $sql_1 ="SELECT id_per ";
    $sql_1.="FROM PERSONA_EVALUADOR ";
    $sql_1.="WHERE id_eva='".$id_usuario."' AND actual='TRUE' ORDER BY id_per";
    
    //Obtención de los nombres de los evaluados
    $sql_2 ="SELECT nombre, apellido ";
    $sql_2.="FROM PERSONA ";
    $sql_2.="WHERE id IN (";
    $sql_2.="$sql_1) ORDER BY id";
    $atts = array("nombre", "apellido");
    $NOMBRE_EVA = obtenerDatos($sql_2,$conexion,$atts,"Nom_Eva");

    //Obtención de los cargos de los evaluados
    $sql_2 ="SELECT id_car ";
    $sql_2.="FROM PERSONA_CARGO ";
    $sql_2.="WHERE id_per IN (";
    $sql_2.="$sql_1)";
    $atts = array("id_car");
    
    //Obtención de los enlaces a la encuestas correspondientes a los cargos
    $sql_3 ="SELECT enlace ";
    $sql_3.="FROM ENCUESTA ";
    $sql_3.="WHERE id_car IN (";
    $sql_3.="$sql_2)";
    $atts = array("enlace");
    $ENCUESTA_EVA = obtenerDatos($sql_3,$conexion,$atts,"Enc_Eva");    
    
    cerrarConexion($conexion);
    
//    include_once("cEnviarCorreo.php");
    
?>
