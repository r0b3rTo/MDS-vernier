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
                $sql="INSERT INTO PERSONA (nombre, apellido, cedula, sexo, fecha_nac, unidad, email) VALUES(".
                "'$_POST[name]', ".  //id organizacion              
                "'$_POST[lname]', ".  //id organizacion              
                "'$_POST[ced]', ".  //id familia de cargo
                "'$_POST[sex]', ".  //codigo cargo
                "'$_POST[fnac]', ". //nombre cargo
                "'$_POST[org]', ". //clave para la organizacion                
                "'$_POST[email]' ".   //funciones
                ")";
                break;

            case 'add_car':
                $atts = array("id_per");

                $sql1 ="SELECT * ";
                $sql1.="FROM PERSONA_CARGO ";
                $sql1.="WHERE id_per='".$_POST['id']."'";

                $LISTA_PER_CAR = obtenerDatos($sql1, $conexion, $atts, "Per_Car");        

                if ($LISTA_PER_CAR['max_res']!==0) {
                    $sql = "UPDATE PERSONA_CARGO SET actual= 'f', fecha_fin='$_POST[fin]' WHERE id_per='$_POST[id]' AND actual='t'";
                    $resultado=ejecutarConsulta($sql, $conexion);
                }

                    $sql="INSERT INTO PERSONA_CARGO (id_per, id_car, actual, fecha_ini, observacion) VALUES(".
                    "'$_POST[id]', ".  //id organizacion              
                    "'$_POST[car]', ".  //id organizacion              
                    "'t', ".  //id organizacion              
                    "'$_POST[fech]', ".  //id familia de cargo
                    "'$_POST[obs]' ".  //observacion
                    //observacion
                    ")";
                break;    

            case 'add_sup':

                $atts = array("id_per");

                $sql1 ="SELECT * ";
                $sql1.="FROM SUPERVISOR ";
                $sql1.="WHERE id_per='".$_POST['id']."'";
                $sql1.="ORDER BY id_per ";

                $LISTA_PER_SUP = obtenerDatos($sql1, $conexion, $atts, "Per_Sup"); 

                if ($LISTA_PER_SUP['max_res']==0) {
                    $sql="INSERT INTO SUPERVISOR (id_per, id_sup, observacion) VALUES(".
                    "'$_POST[id]', ".  //id organizacion              
                    "'$_POST[sup]', ".  //id organizacion              
                    "'$_POST[observacion]' ".  //id organizacion              
                    //observacion
                    ")";
                }else{
                    $sql = "UPDATE SUPERVISOR SET id_sup='$_POST[sup]', observacion='$_POST[obs]' WHERE id_per='$_POST[id]';";
                }
                break;

            case 'add_eval':

                $atts = array("id_per");

                $sql1 ="SELECT * ";
                $sql1.="FROM EVALUADOR ";
                $sql1.="WHERE id_per='".$_POST['id']."'";
                $sql1.="ORDER BY id_per ";

                $LISTA_PER_EVA = obtenerDatos($sql1, $conexion, $atts, "Per_Eva"); 

                if ($LISTA_PER_EVA['max_res']==0) {
                    $sql="INSERT INTO EVALUADOR (id_per, id_eva, observacion) VALUES(".
                    "'$_POST[id]', ".  //id organizacion              
                    "'$_POST[eval]', ".  //id organizacion              
                    "'$_POST[observacion]' ".  //id organizacion              
                    //observacion
                    ")";
                }else{
                    $sql = "UPDATE EVALUADOR SET id_eva='$_POST[eval]', observacion='$_POST[obs]' WHERE id_per='$_POST[id]';";
                }
                break;

            case 'delete':
                $sql="DELETE FROM PERSONA WHERE id='".$_GET['id']."'";
                break;

            case 'edit':
                $sql = "UPDATE PERSONA SET nombre='$_POST[name]', apellido='$_POST[lname]' , cedula='$_POST[ced]', sexo='$_POST[sex]', fecha_nac='$_POST[fnac]', 
                        unidad='$_POST[org]', email='$_POST[email]' WHERE id='$_GET[id]'";
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
                header("Location: ../vListarPersonas.php?success"); 
                break;

            case 'add':
                $_SESSION['MSJ'] = "Los datos fueron registrados";
                header("Location: ../vListarPersonas.php?success");
                break;
            
            default:
                $_SESSION['MSJ'] = "Los cambios fueron guardados";
                $Location = "Location: ../vPersona.php?success&action=edit&id=".$_POST['id'];
                header($Location); 
                break;
        }

    } 
?>
