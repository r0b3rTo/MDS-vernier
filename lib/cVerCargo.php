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
        $sql.="FROM Cargo ";
        $sql.="ORDER BY id";
        
        if ($modo_depuracion) echo "$sql<br>";
        else{
            $resultado=ejecutarConsulta($sql, $conexion);
            $i=0;
            while ($fila=obtenerResultados($resultado)){
                $_SESSION['Cargo']['id'][$i]=$fila['id'];
                $_SESSION['Cargo']['org'][$i]=$fila['id_org'];
                $_SESSION['Cargo']['fam'][$i]=$fila['id_fam'];
                $_SESSION['Cargo']['cod'][$i]=$fila['codigo'];                
                $_SESSION['Cargo']['name'][$i]=$fila['nombre'];
                $_SESSION['Cargo']['desc'][$i]=$fila['descripcion'];
                $_SESSION['Cargo']['obs'][$i]=$fila['funciones'];   
                $i++;   
                            
            }
            $_SESSION['max_res']=$i;
                    
        }
    }else{
        $sql ="SELECT * ";
        $sql.="FROM Cargo ";
        $sql.="WHERE id='".$_GET['id']."'";

        $resultado=ejecutarConsulta($sql, $conexion);
        $i=0;
        while ($fila=obtenerResultados($resultado)){
            $_SESSION['CargoOne']['id'][$i]=$fila['id'];
            $_SESSION['CargoOne']['org'][$i]=$fila['id_org'];
            $_SESSION['CargoOne']['fam'][$i]=$fila['id_fam'];
            $_SESSION['CargoOne']['cod'][$i]=$fila['codigo'];                
            $_SESSION['CargoOne']['name'][$i]=$fila['nombre'];
            $_SESSION['CargoOne']['desc'][$i]=$fila['descripcion'];
            $_SESSION['CargoOne']['obs'][$i]=$fila['funciones'];                    
        }
    }

    cerrarConexion($conexion);
//    include_once("cEnviarCorreo.php");
    
?>
