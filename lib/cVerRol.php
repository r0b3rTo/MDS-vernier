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

    if (isset($all)){
        $LISTA_ORG=array();

        $modo_depuracion=FALSE;

        $sql ="SELECT * ";
        $sql.="FROM ROL ";
        $sql.="ORDER BY id";
        
        if ($modo_depuracion) echo "$sql<br>";
        else{
            $resultado=ejecutarConsulta($sql, $conexion);
            $i=0;
            while ($fila=obtenerResultados($resultado)){
                $_SESSION['Rol']['id'][$i]=$fila['id'];
                $_SESSION['Rol']['org'][$i]=$fila['id_org'];
                $_SESSION['Rol']['fam'][$i]=$fila['id_fam'];
                $_SESSION['Rol']['cod'][$i]=$fila['codigo'];                
                $_SESSION['Rol']['name'][$i]=$fila['nombre'];
                $_SESSION['Rol']['desc'][$i]=$fila['descripcion'];
                $_SESSION['Rol']['obs'][$i]=$fila['funciones'];   
                $i++;   
                            
            }
            $_SESSION['max_res']=$i;
                    
        }
    }else{
        $sql ="SELECT * ";
        $sql.="FROM ROL ";
        $sql.="WHERE id='".$_GET['id']."'";

        $resultado=ejecutarConsulta($sql, $conexion);
        $i=0;
        while ($fila=obtenerResultados($resultado)){
            $_SESSION['RolOne']['id'][$i]=$fila['id'];
            $_SESSION['RolOne']['org'][$i]=$fila['id_org'];
            $_SESSION['RolOne']['fam'][$i]=$fila['id_fam'];
            $_SESSION['RolOne']['cod'][$i]=$fila['codigo'];                
            $_SESSION['RolOne']['name'][$i]=$fila['nombre'];
            $_SESSION['RolOne']['desc'][$i]=$fila['descripcion'];
            $_SESSION['RolOne']['obs'][$i]=$fila['funciones'];                    
        }
    }

    cerrarConexion($conexion);
//    include_once("cEnviarCorreo.php");
    
?>
