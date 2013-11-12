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
            case 'activar':
		
		$fecha= date("d/m/Y");
		
		//Activación de la encuesta
		$sql = "UPDATE ENCUESTA SET estado= 't', fecha_ini='$fecha', fecha_fin=NULL WHERE id_encuesta_ls='$_GET[id]' ";
		$resultado=ejecutarConsulta($sql, $conexion);
		$sql= "SELECT * FROM ENCUESTA ORDER BY id_car";
		$resultado=ejecutarConsulta($sql, $conexion);
		
		  //Activación de la encuesta en Limesurvey
		  $client_ls = XML_RPC2_Client::create('http://localhost/limesurvey/index.php/admin/remotecontrol'); //Crear un cliente para comunicarse con Limesurvey
		  $session_key = $client_ls->get_session_key('admin', 'Segundo!!');//Pedir llave de acceso a Limesurvey
		  $id_encuesta_ls=intval($_GET[id]);
		  $resultado= $client_ls->activate_survey($session_key, $id_encuesta_ls);//Activar la encuesta
		  //Actualizar la fecha de inicio en Limesurvey
		  $properties=array("startdate"=> date("Y-m-d"));
		  $resultado= $client_ls->set_survey_properties($session_key, $id_encuesta_ls, $properties);
		  $resultado= $client_ls->activate_tokens($session_key, $id_encuesta_ls);
		  $resultado=$client_ls->release_session_key($session_key);//Devolver llave de acceso a Limesurvey
		
		//Buscar personas con el cargo de la encuesta
		$sql_1 = "SELECT id_car FROM ENCUESTA ";
		$sql_1.= "WHERE id_encuesta_ls='$_GET[id]'";
		
		$sql= "SELECT id_per, id_car, fecha_ini FROM PERSONA_CARGO ";
		$sql.= "WHERE actual=TRUE AND id_car IN (";
		$sql.= $sql_1.")";
		$atts= array("id_per","id_car","fecha_ini");
		$LISTA_PERSONA= obtenerDatos($sql, $conexion, $atts, "Per");
		
		//Agregar las encuestas correspondientes a cada usuario
		for($i=0; $i<$LISTA_PERSONA[max_res]; $i++){
		
		  //Días de la fecha actual
		  $aux=explode("/", $fecha);
		  $fecha_actual_dias=$aux[0]+($aux[1]*30)+($aux[2]*365);
		  //Días de la fecha de inicio del cargo
		  $fecha_ini=$LISTA_PERSONA["Per"]["fecha_ini"][$i];
		  $aux=explode("-", $fecha_ini);
		  $fecha_ini_dias=$aux[0]+($aux[1]*30)+($aux[2]*365);
		  //Identificador de la persona
		  $id_per=$LISTA_PERSONA["Per"]["id_per"][$i];
		  //Identificador del cargo
		  $id_car=$LISTA_PERSONA["Per"]["id_car"][$i];
		  
		  //Buscar datos de la persona
		  $sql= "SELECT id, nombre, apellido, email FROM PERSONA ";
		  $sql.= "WHERE id='";
		  $sql.= $id_per."'";
		  $atts= array("id","nombre","apellido","email");
		  $DATOS= obtenerDatos($sql, $conexion, $atts, "Dat");
		  $email=$DATOS["Dat"]["email"][0];
		  $nombre=$DATOS["Dat"]["nombre"][0];
		  $apellido=$DATOS["Dat"]["apellido"][0];
		  
		    //Verificar que las personas tengan al menos 150 días (5 meses) en el cargo		  
		    if($fecha_actual_dias-$fecha_ini_dias>150){
		    
		      //Se agrega el usuario a la encuesta en Limesurvey
		      $session_key = $client_ls->get_session_key('admin', 'Segundo!!'); //Pedir llave de acceso a Limesurvey
		      $usuario=array("usuario"=> array("email"=>$email,"firstname"=>$nombre,"lastname"=>$apellido));
		      print_r($usuario);
		      $resultado= $client_ls->add_participants($session_key, $id_encuesta_ls, $usuario);//Agregar participante
		      $token_ls=$resultado["usuario"]["token"];//Obtener token asignado al usuario por limesurvey
		      $resultado=$client_ls->release_session_key($session_key);//Devolver llave de acceso a Limesurvey
		    
		      //Se agrega encuesta de autoevaluación
		      $sql="INSERT INTO PERSONA_ENCUESTA (id_encuestado, id_evaluado, id_car, tipo, token_ls, estado, id_encuesta_ls) VALUES(";
		      $sql.="'$id_per', ";  //id persona encuestada    
		      $sql.="'$id_per', ";  //id persona evaluada             
		      $sql.="'$id_car', ";  //id cargo actual          
		      $sql.="'autoevaluacion', ";  //tipo de encuesta              
		      $sql.="'$token_ls', ";  //token asignado al encuestado por limesurvey
		      $sql.="'Por hacer', ";
		      $sql.="'$_GET[id]')";
		      $resultado=ejecutarConsulta($sql, $conexion); 
		      
		      //Se buscan los supervisores del usuario
		      $sql= "SELECT id_sup, fecha_ini FROM PERSONA_SUPERVISOR ";
		      $sql.= "WHERE actual=TRUE AND id_per=$id_per";
		      $atts= array("id_sup","fecha_ini");
		      $LISTA_SUPERVISOR=obtenerDatos($sql, $conexion, $atts, "Sup");
		      echo print_r($LISTA_SUPERVISOR);
		      
		      //Agregar las encuestas de supervisor correspondientes a cada usuario
		      for($j=0; $j<$LISTA_SUPERVISOR[max_res]; $j++){ 
		      
			//Días de la fecha de inicio como supervisor
			$fecha_ini=$LISTA_SUPERVISOR["Sup"]["fecha_ini"][$j];
			$aux=explode("-", $fecha_ini);
			$fecha_ini_dias=$aux[0]+($aux[1]*30)+($aux[2]*365);
			
			$id_sup=$LISTA_SUPERVISOR["Sup"]["id_sup"][$j];
			//Buscar datos del supervisor
			$sql= "SELECT id, nombre, apellido, email FROM PERSONA ";
			$sql.= "WHERE id='";
			$sql.= $id_sup."'";
			$atts= array("id","nombre","apellido","email");
			$DATOS= obtenerDatos($sql, $conexion, $atts, "Dat");
			$email=$DATOS["Dat"]["email"][0];
			$nombre=$DATOS["Dat"]["nombre"][0];
			$apellido=$DATOS["Dat"]["apellido"][0];
			
			//Verificar que tenga al menos 150 días (5 meses) como supervisor		  
			if($fecha_actual_dias-$fecha_ini_dias>150) {
			
			  //Se agrega el usuario a la encuesta en Limesurvey
			  $session_key = $client_ls->get_session_key('admin', 'Segundo!!'); //Pedir llave de acceso a Limesurvey
			  $usuario=array("usuario"=> array("email"=>$email,"firstname"=>$nombre,"lastname"=>$apellido));
			  print_r($usuario);
			  $resultado= $client_ls->add_participants($session_key, $id_encuesta_ls, $usuario);//Agregar participante
			  $token_ls=$resultado["usuario"]["token"];//Obtener token asignado al usuario por limesurvey
			  $resultado=$client_ls->release_session_key($session_key);//Devolver llave de acceso a Limesurvey
			
			  //Se agrega encuesta de supervisor
			  $sql="INSERT INTO PERSONA_ENCUESTA (id_encuestado, id_evaluado, id_car, tipo, token_ls, estado, id_encuesta_ls) VALUES(";
			  $sql.="'$id_sup', ";  //id persona encuestada    
			  $sql.="'$id_per', ";  //id persona evaluada             
			  $sql.="'$id_car', ";  //id cargo actual          
			  $sql.="'supervisor', ";  //tipo de encuesta              
			  $sql.="'$token_ls', ";  //token asignado al encuestado por limesurvey
			  $sql.="'Por hacer', ";
			  $sql.="'$_GET[id]')";
			  $resultado=ejecutarConsulta($sql, $conexion); 
			}
		     }
		     
		      //Se buscan los evaluadores del usuario
		      $sql= "SELECT id_eva, fecha_ini FROM PERSONA_EVALUADOR ";
		      $sql.= "WHERE actual=TRUE AND id_per=$id_per";
		      $atts= array("id_eva","fecha_ini");
		      $LISTA_EVALUADOR=obtenerDatos($sql, $conexion, $atts, "Eva");
		      
		      //Agregar las encuestas de supervisor correspondientes a cada usuario
		      for($j=0; $j<$LISTA_EVALUADOR[max_res]; $j++){ 
		      
			//Días de la fecha de inicio como supervisor
			$fecha_ini=$LISTA_EVALUADOR["Eva"]["fecha_ini"][$j];
			$aux=explode("-", $fecha_ini);
			$fecha_ini_dias=$aux[0]+($aux[1]*30)+($aux[2]*365);
			
			$id_eva=$LISTA_EVALUADOR["Eva"]["id_eva"][$j];
			//Buscar datos del evaluador
			$sql= "SELECT id, nombre, apellido, email FROM PERSONA ";
			$sql.= "WHERE id='";
			$sql.= $id_eva."'";
			$atts= array("id","nombre","apellido","email");
			$DATOS= obtenerDatos($sql, $conexion, $atts, "Dat");
			$email=$DATOS["Dat"]["email"][0];
			$nombre=$DATOS["Dat"]["nombre"][0];
			$apellido=$DATOS["Dat"]["apellido"][0];
			
			//Verificar que tenga al menos 150 días (5 meses) como evaluador		  
			if($fecha_actual_dias-$fecha_ini_dias>150) {
			
			  //Se agrega el usuario a la encuesta en Limesurvey
			  $session_key = $client_ls->get_session_key('admin', 'Segundo!!'); //Pedir llave de acceso a Limesurvey
			  $usuario=array("usuario"=> array("email"=>$email,"firstname"=>$nombre,"lastname"=>$apellido));
			  print_r($usuario);
			  $resultado= $client_ls->add_participants($session_key, $id_encuesta_ls, $usuario);//Agregar participante
			  $token_ls=$resultado["usuario"]["token"];//Obtener token asignado al usuario por limesurvey
			  $resultado=$client_ls->release_session_key($session_key);//Devolver llave de acceso a Limesurvey
			  
			  //Se agrega encuesta de supervisor
			  $sql="INSERT INTO PERSONA_ENCUESTA (id_encuestado, id_evaluado, id_car, tipo, token_ls, estado, id_encuesta_ls) VALUES(";
			  $sql.="'$id_eva', ";  //id persona encuestada    
			  $sql.="'$id_per', ";  //id persona evaluada             
			  $sql.="'$id_car', ";  //id cargo actual          
			  $sql.="'evaluador', ";  //tipo de encuesta              
			  $sql.="'$token_ls', ";  //token asignado al encuestado por limesurvey
			  $sql.="'Por hacer', ";
			  $sql.="'$_GET[id]')";
			  $resultado=ejecutarConsulta($sql, $conexion);
			}
		     }

		    }
		    
		    }
	
                break;  
                
            case 'desactivar':
		$fecha= date("d/m/Y");
		//Desactivación de la encuesta
		$sql = "UPDATE ENCUESTA SET estado= 'f', fecha_fin='$fecha' WHERE id_encuesta_ls='$_GET[id]' ";
		$resultado=ejecutarConsulta($sql, $conexion);
		//Queda pendiente desactivación de la encuesta en Limesurvey
                break; 
            default:
                # code...
                break;
        }
        
    }
    
    if (isset($_GET['action'])){
        switch ($_GET['action']) {
        
            case 'activar':
                $_SESSION['MSJ'] = "La encuesta ha sido activada";
                header("Location: ../vEncuestas.php?success"); 
                break;
                

            case 'desactivar':
                $_SESSION['MSJ'] = "La encuesta ha sido desactivada";
                header("Location: ../vEncuestas.php?success");
                break;
                
            default:
                # code...
                break;            
        }

    }
  
?> 


