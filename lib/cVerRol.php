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

    $ORG_ID = obtenerIds($conexion, "ORGANIZACION", false);
    $FAM_ID = obtenerIds($conexion, "FAMILIA_ROL", false);

    $atts = array("id", "id_org", "id_fam", "codigo", "nombre", "clave" ,"descripcion", "funciones" );

    $sql ="SELECT * ";
    $sql.="FROM ROL ";

    if (isset($_GET['id'])) {
        $sql.="WHERE id='".$_GET['id']."'";
    }else{
        $sql.="WHERE id!='0'";
        $sql.="ORDER BY id ";
    }

    $LISTA_ROL = obtenerDatos($sql, $conexion, $atts, "Rol");

    cerrarConexion($conexion);
//    include_once("cEnviarCorreo.php");
    
?>
