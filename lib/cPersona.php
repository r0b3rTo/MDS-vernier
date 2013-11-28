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
                $sql="INSERT INTO PERSONA (tipo, nombre, apellido, cedula, sexo, fecha_nac, unidad, email) VALUES(".
                "'$_POST[tipo]', ".  //id organizacion              
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
  
            case 'add_eval':

                $atts = array("id_per");

                $sql1 ="SELECT * ";
                $sql1.="FROM PERSONA_EVALUADOR ";
                $sql1.="WHERE id_per='".$_POST['id']."'";

                $LISTA_PER_EVA = obtenerDatos($sql1, $conexion, $atts, "Per_Eva"); 

//                 if ($LISTA_PER_EVA['max_res']!==0) {
//                     $sql = "UPDATE PERSONA_EVALUADOR SET actual= 'f', fecha_fin='$_POST[fin]' WHERE id_per='$_POST[id]' AND actual='t'";
//                     $resultado=ejecutarConsulta($sql, $conexion);
//                 }

                    $sql="INSERT INTO PERSONA_EVALUADOR (id_per, id_eva, actual, fecha_ini, observacion) VALUES(".
                    "'$_POST[id]', ".  //id persona              
                    "'$_POST[eval]', ".  //id evaluador            
                    "'t', ".  // evaluador actual              
                    "'$_POST[fech]', ".  //fecha
                    "'$_POST[obs]' ".  //observacion
                    //observacion
                    ")";
                break;  
                
            case 'delete_eval':

                $atts = array("id_per", "id_eva");

                $sql1 ="SELECT * ";
                $sql1.="FROM PERSONA_EVALUADOR ";
                $sql1.="WHERE id_per='".$_GET['id']."' ";
                $sql1.="AND id_eva='".$_GET['id_eva']."'";

                $LISTA_PER_EVA = obtenerDatos($sql1, $conexion, $atts, "Per_Eva"); 

                if ($LISTA_PER_EVA['max_res']!==0) {
                    $sql = "UPDATE PERSONA_EVALUADOR SET actual= 'f', fecha_fin='$_GET[fin]' WHERE id_per='$_GET[id]' AND id_eva='$_GET[id_eva]' AND actual='t'";
//                     $resultado=ejecutarConsulta($sql, $conexion);
                }
                
                break;  
            
            case 'add_sup':

                $atts = array("id_sup");

                $sql1 ="SELECT * ";
                $sql1.="FROM PERSONA_SUPERVISOR ";
                $sql1.="WHERE id_per='".$_POST['id']."'";

                $LISTA_PER_SUP = obtenerDatos($sql1, $conexion, $atts, "Per_Sup");
                
                if ($LISTA_PER_SUP['max_res']!==0) {
                    $sql = "UPDATE PERSONA_SUPERVISOR SET actual= 'f', fecha_fin='$_POST[fin]' WHERE id_per='$_POST[id]' AND actual='t'";
                    $resultado=ejecutarConsulta($sql, $conexion);
                }

                    $sql="INSERT INTO PERSONA_SUPERVISOR (id_per, id_sup, actual, fecha_ini, observacion) VALUES(".
                    "'$_POST[id]', ".  //id persona              
                    "'$_POST[sup]', ".  //id supervisor           
                    "'t', ".  // evaluador actual              
                    "'$_POST[fecha_sup]', ".  //fecha
                    "'$_POST[obs_sup]' ".  //observacion
                    ")";
                break;
                

            case 'delete':
                $sql="DELETE FROM PERSONA WHERE id='".$_GET['id']."'";
                break;

            case 'edit':
                $sql = "UPDATE PERSONA SET tipo='$_POST[tipo]', nombre='$_POST[name]', apellido='$_POST[lname]' , cedula='$_POST[ced]', sexo='$_POST[sex]', fecha_nac='$_POST[fnac]', 
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
            case 'add':
                $_SESSION['MSJ'] = "Los datos fueron registrados";
                header("Location: ../vListarPersonas.php?success");
                break;
      
            case 'delete':
                $_SESSION['MSJ'] = "Los datos fueron eliminados";
                header("Location: ../vListarPersonas.php?success"); 
                break;
                
            case 'delete_eval':
                $_SESSION['MSJ'] = "Los datos del evaluador fueron eliminados";
                $Location = "Location: ../vPersona.php?success&action=edit&id=".$_GET['id'];
                header($Location); 
                break;
                
            default:
                $_SESSION['MSJ'] = "Los cambios fueron guardados";
                $Location = "Location: ../vPersona.php?success&action=edit&id=".$_POST['id'];
                header($Location); 
                break;
        }

    } 
?>
