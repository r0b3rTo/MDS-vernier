<?php
    //require "cAutorizacion.php";
    extract($_GET);
    extract($_POST);
    //include "cMail.php";
//  del post
//  asun, fecha , tipo, descrip
    $_ERRORES = array();
    $_WARNING = array();
    $_SUCCESS = array();

    $ORG_ID = obtenerIds($conexion, "ORGANIZACION");
    $FAM_ID = obtenerIds($conexion, "FAMILIA_ROL");

    $sql ="SELECT * ";
    $sql.="FROM ROL ";

    if (isset($_GET['id'])) {
        $sql.="WHERE id='".$_GET['id']."'";
    }else{
        $sql.="ORDER BY id ";
    }

    $LISTA_ROL = obtenerRol($sql, $conexion);

    cerrarConexion($conexion);
//    include_once("cEnviarCorreo.php");
    
?>
