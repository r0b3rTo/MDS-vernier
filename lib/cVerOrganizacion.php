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

    $ORG_ID = obtenerORGs($conexion);

    $sql ="SELECT * ";
    $sql.="FROM ORGANIZACION ";


    if (isset($_GET['id'])) {
        $sql.="WHERE id='".$_GET['id']."'";
    }else{
        $sql.="ORDER BY id ";
    }

    $LISTA_ORG = obtenerOrganizacion($sql, $conexion);

    cerrarConexion($conexion);
//    include_once("cEnviarCorreo.php");
    
?>
