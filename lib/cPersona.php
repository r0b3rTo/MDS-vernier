<?php
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
//      $_ERRORES[] = 'Todos los campos son obligatorios';
//    }

    if (isset($_GET['action'])){

        switch ($_GET['action']) {
            case 'add':
                $sql="INSERT INTO PERSONA (nombre, cedula, sexo, fecha_nac, direccion, telefono, email) VALUES(".
                "'$_POST[name]', ".  //id organizacion              
                "'$_POST[ced]', ".  //id familia de cargo
                "'$_POST[sex]', ".  //codigo cargo
                "'$_POST[fech_nac]', ". //nombre cargo
                "'$_POST[dir]', ". //clave para la organizacion                
                "'$_POST[tel]', ". //descripcion
                "'$_POST[email]' ".   //funciones
                ")";
                echo $sql;
                break;

            case 'delete':
                $sql="DELETE FROM PERSONA WHERE id='".$_GET['id']."'";
                break;

            case 'edit':
                $sql = "UPDATE PERSONA SET nombre='$_POST[name]', cedula='$_POST[ced]', sexo='$_POST[sex]', fecha_nac='$_POST[fech_nac]', 
                        direccion='$_POST[dir]',telefono='$_POST[tel]', email='$_POST[email]' WHERE id='$_GET[id]'";
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
                header("Location: ../vListarPersona.php?success"); 
                break;
            
            default:
                header("Location: ../vPersona.php?success"); 
                break;
        }

    }
?>
