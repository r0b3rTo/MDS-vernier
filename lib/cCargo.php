<?php
    session_start();
    require_once "cAutorizacion.php";
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
                $sql="INSERT INTO CARGO (id_org, id_fam, codigo, nombre, clave, descripcion, funciones) VALUES(".
                "'$_POST[org]', ".  //id organizacion              
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
                $sql="DELETE FROM CARGO WHERE id='".$_GET['id']."'";
                break;

            case 'edit':
                $sql = "UPDATE CARGO SET id_org='$_POST[org]', id_fam='$_POST[fam]', codigo='$_POST[cod]', nombre='$_POST[name]', 
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
                header("Location: ../vListarCargo.php?success"); 
                break;
            
            default:
                header("Location: ../vCargo.php?success"); 
                break;
        }

    }
?>
