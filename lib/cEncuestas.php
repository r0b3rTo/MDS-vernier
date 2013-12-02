<?php
    session_start();
    require "cAutorizacion.php";
    extract($_GET);
    extract($_POST);
    date_default_timezone_set('America/Caracas');
    $_ERRORES = array();
    $_WARNING = array();
    $_SUCCESS = array();
    require_once 'XML/RPC2/Client.php';
    
    // Obtención de las encuestas definidas
    $sql ="SELECT * ";
    $sql.="FROM ENCUESTA ORDER BY id_car";        
    $atts = array("id_car", "id_encuesta_ls", "fecha_ini", "fecha_fin", "estado");
    $LISTA_ENCUESTA= obtenerDatos($sql, $conexion, $atts, "Enc");
    
    // Obtención de los cargos
    $sql ="SELECT nombre ";
    $sql.="FROM CARGO ";
    $sql.="WHERE id='";        
    for ($i=0;$i<$LISTA_ENCUESTA['max_res'];$i++){    
      $sql.=$LISTA_ENCUESTA['Enc']['id_car'][$i];
      if($i == $LISTA_ENCUESTA['max_res']-1) {
	  $sql.="'";
      } else {
	  $sql.="' OR id='";
      }
    }
    $atts = array("nombre"); 
    $LISTA_CARGOS = obtenerDatos($sql, $conexion, $atts, "Car");
    
    if (isset($_GET['action'])){
      switch ($_GET['action']) {
            case 'modificar':
		# code...
		break;

            default:
                # code...
                break;
        }
        
    }
    
    if (isset($_GET['action'])){
        switch ($_GET['action']) {
        
            case 'modifcar':
                $_SESSION['MSJ'] = "La encuesta ha sido modificada";
                header("Location: ../vEncuestas.php?success"); 
                break;
                
            default:
                # code...
                break;            
        }

    }
  
?> 


