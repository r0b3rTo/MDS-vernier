<?php
try{
    session_start();
    require "cAutorizacion.php";
    extract($_GET);
    extract($_POST);
    //include "cMail.php";
//  del post
//  asun, fecha , tipo, descrip

    $_ERRORES = array();
    $_WARNING = array();
    $_SUCCESS = array();

//    if( !$_POST['tipo'] OR !$_POST['asun'] OR !$_POST['descrip'] ){
//	    $_ERRORES[] = 'Todos los campos son obligatorios';
//    }

    if (isset($_GET['action'])){

        switch ($_GET['action']) {
            case 'add':
                $sql="INSERT INTO ROL (id_fam, codigo, nombre, clave, descripcion, funciones) VALUES(".
                "'$_POST[fam]', ".  //id familia de cargo
                "'$_POST[cod]', ".  //codigo cargo
                "'$_POST[name]', ". //nombre cargo
                "'$_POST[clav]', ". //clave para la organizacion                
                "'$_POST[desc]', ". //descripcion
                "'$_POST[obs]' ".   //funciones
                ")";
                echo $sql;
                break;

            case 'delete':
                $sql="DELETE FROM ROL WHERE id='".$_GET['id']."'";
                break;

            case 'edit':
                $sql = "UPDATE ROL SET id_org='$_POST[org]', id_fam='$_POST[fam]', codigo='$_POST[cod]', nombre='$_POST[name]', 
                        clave='$_POST[clav]', descripcion='$_POST[desc]', observacion='$_POST[obs]' WHERE id='$_GET[id]'";
                break;
            
            default:
                # code...
                break;
        }

    }

    $resultado=ejecutarConsulta($sql, $conexion);

    cerrarConexion($conexion);

    if (isset($_GET['action'])){

        switch ($_GET['action']) {
            case 'delete':
                $_SESSION['MSJ'] = "Los datos fueron eliminados";
                header("Location: ../vListarRoles.php?success"); 
                break;
            
            default:
                $_SESSION['MSJ'] = "Los datos fueron registrados";
                header("Location: ../vListarRoles.php?success"); 
                break;
        }

    }


}catch (ErrorException $e) {
    $error = $e->getMessage();
    $resultado=ejecutarConsulta($sql, $conexion);

    cerrarConexion($conexion);

    if (isset($_GET['action'])){

        switch ($_GET['action']) {
            case 'delete':
                $_SESSION['MSJ'] = "No se pudo eliminar el rol";
                header("Location: ../vListarRoles.php?error"); 
                break;
            
            default:
                $_SESSION['MSJ'] = "No se pudo registrar el rol";
                header("Location: ../vRol.php?error"); 
                break;
        }

    }
}
?>
