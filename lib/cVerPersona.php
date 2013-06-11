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

    $PER_ID = obtenerIds($conexion, "PERSONA", true);
    $EVAL_ID = obtenerIds($conexion, "PERSONA", true);
    $CAR_ID = obtenerIds($conexion, "CARGO", false);

    if (isset($_GET['id'])) {
        $atts = array("id", "nombre", "apellido", "cedula", "sexo", "fecha_nac", "direccion", "telefono", "email" );

        $sql ="SELECT * ";
        $sql.="FROM PERSONA ";

        $sql.="WHERE id='".$_GET['id']."'";
        $sql.="ORDER BY id ";

        $LISTA_PER = obtenerDatos($sql, $conexion, $atts, "Per");

        $atts = array("id_per", "id_car", "fecha");

        $sql ="SELECT * ";
        $sql.="FROM PERSONA_CARGO ";
        $sql.="WHERE id_per='".$_GET['id']."'";
        $sql.="ORDER BY id_per ";

        $LISTA_PER_CAR = obtenerDatos($sql, $conexion, $atts, "Per_Car");        

        $atts = array("id_per", "id_sup");

        $sql ="SELECT * ";
        $sql.="FROM SUPERVISOR ";
        $sql.="WHERE id_per='".$_GET['id']."'";
        $sql.="ORDER BY id_per ";

        $LISTA_PER_SUP = obtenerDatos($sql, $conexion, $atts, "Per_Sup"); 

                $atts = array("id_per", "id_eva");

        $sql ="SELECT * ";
        $sql.="FROM EVALUADOR ";
        $sql.="WHERE id_per='".$_GET['id']."'";
        $sql.="ORDER BY id_per ";

        $LISTA_PER_EVA = obtenerDatos($sql, $conexion, $atts, "Per_Eva"); 

    }else if (isset($all)){
        $atts = array("id", "nombre", "apellido", "cedula", "sexo", "fecha_nac", "direccion", "telefono", "email" );

        $sql ="SELECT * ";
        $sql.="FROM PERSONA ";
        $sql.="WHERE id!='0'";
        $sql.="ORDER BY id ";

        $LISTA_PER = obtenerDatos($sql, $conexion, $atts, "Per");
    }

    cerrarConexion($conexion);
//    include_once("cEnviarCorreo.php");
    
?>
