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
    
    if (isset($_GET['action'])){
      switch ($_GET['action']) {
	case 'activar':

	  if (isset($_POST[periodo]) & isset($_POST[ini]) & isset($_POST[fin])){
	  
	    //Agregar nuevo periodo de evaluación
	    $sql="INSERT INTO EVALUACION (periodo, fecha_ini, fecha_fin, actual) VALUES(";
	    $sql.="'$_POST[periodo]', ";  //periodo de evaluacion    
	    $sql.="'$_POST[ini]', ";  //fecha_ini            
	    $sql.="'$_POST[fin]', ";  //fecha_fin         
	    $sql.="'t')";  //periodo actual            
	    $resultado=ejecutarConsulta($sql, $conexion);
	    
	    //Agregar cronjob para el día de expiración de la encuesta
	    $aux= explode("-",$_POST[fin]);
	    $output=file_put_contents('../tmp/vernier_jobs.txt', '00 00 '.$aux[0].' '.$aux[1].' * wget -O -q -t 1 http://localhost/vernier/lib/cEvaluaciones.php?action=desactivar'.PHP_EOL);
	    $output=shell_exec('crontab ../tmp/vernier_jobs.txt');
	    
	    // Obtención de los procesos de evaluacion del sistema
	    $sql ="SELECT * ";
	    $sql.="FROM EVALUACION ORDER BY actual";        
	    $atts = array("periodo", "fecha_ini", "fecha_fin");
	    $LISTA_EVALUACION= obtenerDatos($sql, $conexion, $atts, "Proc");
	    
	    //Activación de las encuestas
	    $sql = "UPDATE ENCUESTA SET estado= 't'";
	    $resultado=ejecutarConsulta($sql, $conexion);
	    //Reorganizar encuestas
	    $sql= "SELECT * FROM ENCUESTA ORDER BY id_car";
	    $resultado=ejecutarConsulta($sql, $conexion);
	    
	    // Obtención de las encuestas definidas
	    $sql ="SELECT id_car, id_encuesta_ls ";
	    $sql.="FROM ENCUESTA ORDER BY id_car";        
	    $atts = array("id_car", "id_encuesta_ls");
	    $LISTA_ENCUESTA= obtenerDatos($sql, $conexion, $atts, "Enc");
	    
	    //Crear un cliente para comunicarse con Limesurvey
	    $client_ls = XML_RPC2_Client::create('http://localhost/limesurvey/index.php/admin/remotecontrol'); 
	    //Pedir llave de acceso a Limesurvey
	    $session_key = $client_ls->get_session_key('admin', 'Segundo!!');
	    
	    for($i=0; $i<$LISTA_ENCUESTA[max_res]; $i++){
	    
	      $id_encuesta_ls=intval($LISTA_ENCUESTA["Enc"]["id_encuesta_ls"][$i]);//Encuesta de i-ésima iteración
	      $id_car=$LISTA_ENCUESTA["Enc"]["id_car"][$i];//Cargo para la encuesta de la i-ésima iteración
	      $fecha_ini=date("Y-m-d", strtotime($_POST[ini])); //Fecha de inicio
	      //echo"Esta es la fecha de inicio transformada: $fecha_ini<br>";
	      $fecha_fin=date("Y-m-d", strtotime($_POST[fin])); //Fecha de finalización
	      $periodo=$_POST[periodo];
	      
	      //Activación de la encuesta en Limesurvey
	      $resultado= $client_ls->activate_survey($session_key, $id_encuesta_ls);//Activar la encuesta
	      
	      //Actualizar la fecha de inicio y la fecha de finalizacion en Limesurvey
	      $properties=array("startdate"=> $fecha_ini, "expires"=>$fecha_fin);
	      $resultado= $client_ls->set_survey_properties($session_key, $id_encuesta_ls, $properties);
	      $resultado= $client_ls->activate_tokens($session_key, $id_encuesta_ls);//Se abre la encuesta solo para usuarios con token
	      //$resultado=$client_ls->release_session_key($session_key);//Devolver llave de acceso a Limesurvey
	      
	      //Buscar personas con el cargo de la encuesta de la i-ésima iteración
	      $sql= "SELECT id_per, id_car, fecha_ini FROM PERSONA_CARGO ";
	      $sql.= "WHERE actual=TRUE AND id_car='";
	      $sql.=$id_car;
	      $sql.="'";
	      $atts= array("id_per","id_car","fecha_ini");
	      $LISTA_PERSONA= obtenerDatos($sql, $conexion, $atts, "Per");
	      echo "Esta es la consulta $sql<br>";
	      echo "Este es el resultado de la consulta";
	      
	      //Agregar las encuestas correspondientes a cada usuario
	      for($j=0; $j<$LISTA_PERSONA[max_res]; $j++){
		  
		  //Identificador de la persona
		  $id_per=$LISTA_PERSONA["Per"]["id_per"][$j];
		  //Identificador del cargo
		  $id_car=$LISTA_PERSONA["Per"]["id_car"][$j];
		  
		  //Buscar datos de la persona
		  $sql= "SELECT id, nombre, apellido, email FROM PERSONA WHERE id='";
		  $sql.= $id_per."'";
		  $atts= array("id","nombre","apellido","email");
		  $DATOS= obtenerDatos($sql, $conexion, $atts, "Dat");
		  $email=$DATOS["Dat"]["email"][0];
		  $nombre=$DATOS["Dat"]["nombre"][0];
		  $apellido=$DATOS["Dat"]["apellido"][0];

		  
		    //Verificar que las personas tengan al menos 150 días (5 meses) en el cargo
		    //QUEDA PENDIENTE VERIFICAR CONDICION ESPECIAL
		    if(obtenerDiferenciaDias($_POST[ini], $LISTA_PERSONA["Per"]["fecha_ini"][$j])>150){
		      //Se agrega el usuario a la encuesta en Limesurvey
		      //$session_key = $client_ls->get_session_key('admin', 'Segundo!!'); //Pedir llave de acceso a Limesurvey
		      $usuario=array("usuario"=> array("email"=>$email,"firstname"=>$nombre,"lastname"=>$apellido));
		      $resultado= $client_ls->add_participants($session_key, $id_encuesta_ls, $usuario);//Agregar participante
		      $token_ls=$resultado["usuario"]["token"];//Obtener token asignado al usuario por limesurvey
		      //$resultado=$client_ls->release_session_key($session_key);//Devolver llave de acceso a Limesurvey
		    
		      //Se agrega encuesta de autoevaluación
		      $sql="INSERT INTO PERSONA_ENCUESTA (id_encuestado, id_evaluado, id_car, tipo, token_ls, estado, id_encuesta_ls, actual, periodo) VALUES(";
		      $sql.="'$id_per', ";  //id persona encuestada    
		      $sql.="'$id_per', ";  //id persona evaluada             
		      $sql.="'$id_car', ";  //id cargo actual          
		      $sql.="'autoevaluacion', ";  //tipo de encuesta              
		      $sql.="'$token_ls', ";  //token asignado al encuestado por limesurvey
		      $sql.="'Pendiente', "; //estado de la encuesta
		      $sql.="'$id_encuesta_ls', "; //id de la encuesta
		      $sql.="'t', "; //proceso de evaluación actual
		      $sql.="'$periodo')"; //proceso de evaluación correspondiente
		      $resultado=ejecutarConsulta($sql, $conexion); 
		      
		      //Se buscan los evaluadores del usuario
		      $sql= "SELECT id_eva, fecha_ini FROM PERSONA_EVALUADOR ";
		      $sql.= "WHERE actual=TRUE AND id_per=$id_per";
		      $atts= array("id_eva","fecha_ini");
		      $LISTA_EVALUADOR=obtenerDatos($sql, $conexion, $atts, "Eva");
		      
		      //Agregar las encuestas de evaluador correspondientes a cada usuario
		      for($k=0; $k<$LISTA_EVALUADOR[max_res]; $k++){ 
		      
			$id_eva=$LISTA_EVALUADOR["Eva"]["id_eva"][$k];
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
			//QUEDA PENDIENTE VERIFICAR CONDICION ESPECIAL
			if(obtenerDiferenciaDias($_POST[ini], $LISTA_EVALUADOR["Eva"]["fecha_ini"][$k])>150) {
			
			  //Se agrega el usuario a la encuesta en Limesurvey
			  //$session_key = $client_ls->get_session_key('admin', 'Segundo!!'); //Pedir llave de acceso a Limesurvey
			  $usuario=array("usuario"=> array("email"=>$email,"firstname"=>$nombre,"lastname"=>$apellido));
			  $resultado= $client_ls->add_participants($session_key, $id_encuesta_ls, $usuario);//Agregar participante
			  $token_ls=$resultado["usuario"]["token"];//Obtener token asignado al usuario por limesurvey
			  //$resultado=$client_ls->release_session_key($session_key);//Devolver llave de acceso a Limesurvey
			  
			  //Se agrega encuesta de evaluador
			  $sql="INSERT INTO PERSONA_ENCUESTA (id_encuestado, id_evaluado, id_car, tipo, token_ls, estado, id_encuesta_ls, actual, periodo) VALUES(";
			  $sql.="'$id_eva', ";  //id persona encuestada    
			  $sql.="'$id_per', ";  //id persona evaluada             
			  $sql.="'$id_car', ";  //id cargo actual          
			  $sql.="'evaluador', ";  //tipo de encuesta              
			  $sql.="'$token_ls', ";  //token asignado al encuestado por limesurvey
			  $sql.="'Pendiente', "; //estado de la encuesta
			  $sql.="'$id_encuesta_ls', "; //id de la encuesta
			  $sql.="'t', "; //proceso de evaluación actual
			  $sql.="'$periodo')"; //proceso de evaluación correspondiente
			  $resultado=ejecutarConsulta($sql, $conexion);
			  
			} //cierre if (tiempo como evaluador)
		     } //cierre iteración sobre los evaluadores
		     
		    } //cierre if (tiempo en el cargo)     
	      }//cierre iteración sobre las personas	      
	    } //cierre iteración sobre las encuestas
	    //Devolver llave de acceso a Limesurvey
	    $resultado=$client_ls->release_session_key($session_key);
	  } //cierre if
	  break;
	case 'desactivar':
	  $sql= "UPDATE EVALUACION SET actual='f'";
	  $resultado= ejecutarConsulta($sql, $conexion);
	  $sql= "UPDATE PERSONA_ENCUESTA SET actual='f'";
	  $resultado= ejecutarConsulta($sql, $conexion);
	  #code
	  break;
	case 'default':    
	  #code
	  break;
      } //cierre switch
    } //cierre if
    
    // Obtención de los procesos de evaluacion del sistema
    $sql ="SELECT * ";
    $sql.="FROM EVALUACION ORDER BY actual DESC";        
    $atts = array("periodo", "fecha_ini", "fecha_fin", "actual", "total","pendiente", "en_proceso", "finalizada", "supervisada");
    $LISTA_EVALUACION= obtenerDatos($sql, $conexion, $atts, "Proc");
    
    for($i=0; $i<$LISTA_EVALUACION[max_res]; $i++){ 
      $periodo= $LISTA_EVALUACION["Proc"]["periodo"][$i];//periodo de evaluación de la i-ésima iteración
      //Obtención del número total de evaluaciones
      $sql="SELECT estado FROM PERSONA_ENCUESTA WHERE periodo='";
      $sql.=$periodo;
      $sql.="'";
      $atts= array("estado");
      $aux=obtenerDatos($sql, $conexion, $atts, "Aux");
      $LISTA_EVALUACION["Proc"]["total"][$i]=$aux[max_res];
      //Obtención del número de evaluaciones pendientes
      $sql="SELECT estado FROM PERSONA_ENCUESTA WHERE periodo='";
      $sql.=$periodo."' AND estado='Pendiente'";
      $atts= array("estado");
      $aux=obtenerDatos($sql, $conexion, $atts, "Aux");
      $LISTA_EVALUACION["Proc"]["pendiente"][$i]=$aux[max_res];
      //Obtención del número de evaluaciones en proceso
      $sql="SELECT estado FROM PERSONA_ENCUESTA WHERE periodo='";
      $sql.=$periodo."' AND estado='En Proceso'";
      $atts= array("estado");
      $aux=obtenerDatos($sql, $conexion, $atts, "Aux");
      $LISTA_EVALUACION["Proc"]["en_proceso"][$i]=$aux[max_res];
      //Obtención del número de evaluaciones finalizadas
      $sql="SELECT estado FROM PERSONA_ENCUESTA WHERE periodo='";
      $sql.=$periodo."' AND estado='Finalizada'";
      $atts= array("estado");
      $aux=obtenerDatos($sql, $conexion, $atts, "Aux");
      $LISTA_EVALUACION["Proc"]["finalizada"][$i]=$aux[max_res];
      //Obtención del número de evaluaciones supervisadas CAMBIAR!!!
      $LISTA_EVALUACION["Proc"]["supervisada"][$i]=0;
    } //cierre iteración sobre los procesos de evaluación
    
    //Cierre conexión a la BD
    cerrarConexion($conexion);

    
    if (isset($_GET['action'])){
        switch ($_GET['action']) {
        
            case 'activar':
                $_SESSION['MSJ'] = "Se ha iniciado un nuevo proceso de evaluación";
                header("Location: ../vEvaluaciones.php?success"); 
                break;
                
            default:
                # code...
                break;            
        }
    }
  
?> 


