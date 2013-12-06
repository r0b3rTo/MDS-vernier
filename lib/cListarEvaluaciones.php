<?php
    require "cAutorizacion.php";
    extract($_GET);
    extract($_POST);
    $_ERRORES = array();
    $_WARNING = array();
    $_SUCCESS = array();
    date_default_timezone_set('America/Caracas');
    
    require_once 'XML/RPC2/Client.php';

    // Obtención de datos del usuario
    $atts = array("id");
    $sql ="SELECT id ";
    $sql.="FROM PERSONA ";
    if (isset($_SESSION['cedula'])) {
	$cedula= $_SESSION['cedula'];
        $sql.="WHERE cedula='".$cedula."'";
    }
    
    $PERSONA = obtenerDatos($sql, $conexion, $atts, "Per");
    
    //Inicialización de variables
    $LISTA_EVALUACION_ACTUAL[max_res]=0;
    $LISTA_EVALUACION_PASADA[max_res]=0;
    if(isset($PERSONA['Per']['id'][0])){
      
      $id_usuario=$PERSONA['Per']['id'][0];

      //Evaluaciones actuales
      ///////////////////////
      
	// Obtención del identificador, tipo, estado, periodo y token de Limesurvey de las encuestas del usuario
	$sql ="SELECT id_encuesta_ls, id_evaluado, token_ls, tipo, estado, periodo ";
	$sql.="FROM PERSONA_ENCUESTA ";
	$sql.="WHERE id_encuestado='".$id_usuario."' AND actual='t'";
	    
	$atts = array("id_encuesta_ls", "id_evaluado", "token_ls", "tipo", "estado", "periodo", "nombre", "apellido");
	$LISTA_EVALUACION_ACTUAL= obtenerDatos($sql, $conexion, $atts, "Enc");

	//Obtención de los nombres de los evaluados
	for ($i=0; $i<$LISTA_EVALUACION_ACTUAL[max_res]; $i++){
	  $sql ="SELECT nombre, apellido ";
	  $sql.="FROM PERSONA ";
	  $sql.="WHERE ";
	  $sql.= "id='".$LISTA_EVALUACION_ACTUAL["Enc"]["id_evaluado"][$i]."'";
	  $atts = array("nombre", "apellido");
	  $NOMBRE= obtenerDatos($sql, $conexion, $atts, "Nom");
	  $LISTA_EVALUACION_ACTUAL["Enc"]["nombre"][$i]=$NOMBRE["Nom"]["nombre"][0];
	  $LISTA_EVALUACION_ACTUAL["Enc"]["apellido"][$i]=$NOMBRE["Nom"]["apellido"][0];
	}
	
      //Evaluaciones pasadas
      //////////////////////
      
	// Obtención del identificador, tipo, estado y token de Limesurvey de las encuestas del usuario
	$sql ="SELECT id_encuesta_ls, id_evaluado, token_ls, tipo, estado, periodo ";
	$sql.="FROM PERSONA_ENCUESTA ";
	$sql.="WHERE id_encuestado='".$id_usuario."' AND actual='f'";
	    
	$atts = array("id_encuesta_ls", "id_evaluado", "token_ls", "tipo", "estado", "periodo", "nombre", "apellido");
	$LISTA_EVALUACION_PASADA= obtenerDatos($sql, $conexion, $atts, "Enc");
	
	//Obtención de los nombres de los evaluados
	for ($i=0; $i<$LISTA_EVALUACION_PASADA[max_res]; $i++){
	  $sql ="SELECT nombre, apellido ";
	  $sql.="FROM PERSONA ";
	  $sql.="WHERE ";
	  $sql.= "id='".$LISTA_EVALUACION_PASADA["Enc"]["id_evaluado"][$i]."'";
	  $atts = array("nombre", "apellido");
	  $NOMBRE= obtenerDatos($sql, $conexion, $atts, "Nom");
	  $LISTA_EVALUACION_PASADA["Enc"]["nombre"][$i]=$NOMBRE["Nom"]["nombre"][0];
	  $LISTA_EVALUACION_PASADA["Enc"]["apellido"][$i]=$NOMBRE["Nom"]["apellido"][0];
	}
      
      /*----------------------------------------------------------
      ----------------- Regreso desde Limesurvey -----------------
      ------------------------------------------------------------*/
      if (isset($_GET['token_ls']) && isset($_GET['id_encuesta_ls'])) {
      
	    //Determinar estado de la encuesta
	    $client_ls = XML_RPC2_Client::create('http://localhost/limesurvey/index.php/admin/remotecontrol'); //Crear un cliente para comunicarse con Limesurvey
	    $session_key = $client_ls->get_session_key('admin', 'Segundo!!');//Pedir llave de acceso a Limesurvey
	    $id_encuesta_ls=intval($_GET['id_encuesta_ls']);
	    $token_ls=$_GET['token_ls'];
	    //Hallar token ID
	    $sql ="SELECT tid_ls FROM PERSONA_ENCUESTA WHERE token_ls='".$token_ls."'";
	    $atts = array("tid_ls");
	    $resultado= obtenerDatos($sql, $conexion, $atts, "Tok");
	    $tid_ls=intval($resultado['Tok']['tid_ls'][0]);
	    $properties=array("completed");
	    //Solicitar estado de la encuesta a Limesurvey
	    $properties= $client_ls->get_participant_properties($session_key, $id_encuesta_ls, $tid_ls, $properties);//Determinar si completó la encuesta
	    $completed= $properties["completed"][0];
	    
	    $ip=$_SERVER['REMOTE_ADDR']; //Dirección IP del usuario registrado
	    $fecha_intento=date("d/m/Y.H:i"); //Fecha y hora de último intento de realizar la encuesta
	    
	    if ($completed!='N'){
	    
	      //Encuesta finalizada
	      $sql ="UPDATE PERSONA_ENCUESTA SET estado='Finalizada', ip='".$ip."', fecha='".$fecha_intento."' ";
	      $sql.="WHERE ";
	      $sql.= "token_ls='".$token_ls."'";
	      $resultado=ejecutarConsulta($sql, $conexion);
	      
	      //Obtención de los resultados
	      ////////////////////////////
	      
	      $sql ="SELECT id_pregunta_ls, id_pregunta_root_ls, id_pregunta FROM PREGUNTA WHERE id_encuesta_ls='".$_GET['id_encuesta_ls']."' ORDER BY id_pregunta";        
	      $atts = array("id_pregunta_ls", "id_pregunta_root_ls","id_pregunta");
	      $LISTA_PREGUNTA= obtenerDatos($sql, $conexion, $atts, "Preg"); //Lista de preguntas
	      $n=0; //indice del numero de preguntas con resultados
	      
	      //Obtener la lista de preguntas con resultados/respuestas
	      for ($i=0; $i<$LISTA_PREGUNTA[max_res]; $i++){
	      
		//Si no es una subpregunta
		if ($LISTA_PREGUNTA['Preg']['id_pregunta_root_ls'][$i]==NULL){
		  $aux=intval($LISTA_PREGUNTA['Preg']['id_pregunta_ls'][$i]);
		  
		  //Pedir tipos de respuesta a Limesurvey para la pregunta
		  $properties=array("answeroptions");
		  $id_pregunta_ls=intval($LISTA_PREGUNTA['Preg']['id_pregunta_ls'][$i]);
		  $tipo_respuesta= $client_ls->get_question_properties($session_key, $aux, $properties);
		  
		  //Verificar si tiene o no subpreguntas
		  if (!(in_array($aux,$LISTA_PREGUNTA['Preg']['id_pregunta_root_ls']))) {
		    //La pregunta no tiene subpreguntas, necesitamos su resultado
		    $PREGUNTA_CON_RESPUESTA['id_pregunta_ls'][$n]=$LISTA_PREGUNTA['Preg']['id_pregunta_ls'][$i];
		    $PREGUNTA_CON_RESPUESTA['id_pregunta'][$n]=$LISTA_PREGUNTA['Preg']['id_pregunta'][$i];
		    $PREGUNTA_CON_RESPUESTA['tipo_respuesta'][$n]=$tipo_respuesta;
		    $n++;
		  }
		} else { //Es una subpregunta, necesitamos su resultado
		
		  //Pedir tipos de respuesta a Limesurvey para la pregunta padre
		  $properties=array("answeroptions");
		  $id_pregunta_ls=intval($LISTA_PREGUNTA['Preg']['id_pregunta_root_ls'][$i]);
		  $tipo_respuesta= $client_ls->get_question_properties($session_key, $aux, $properties);
		  
		  $PREGUNTA_CON_RESPUESTA['id_pregunta_ls'][$n]=$LISTA_PREGUNTA['Preg']['id_pregunta_ls'][$i];
		  $PREGUNTA_CON_RESPUESTA['id_pregunta'][$n]=$LISTA_PREGUNTA['Preg']['id_pregunta'][$i];
		  $PREGUNTA_CON_RESPUESTA['tipo_respuesta'][$n]=$tipo_respuesta;
		  $n++;
		}
	      } //cierra el for
	      
	      //Pedir resultados a Limesurvey	    
	      $resultado= $client_ls->export_responses_by_token($session_key, $id_encuesta_ls, 'csv',$token_ls);//Obtener respuestas para la encuesta
	      $resultado=base64_decode($resultado); //decode    
	      $aux= explode(",",$resultado); //colocar en arreglo
	      $RESPUESTAS = array_slice($aux, -$n); //tomar las respuestas (al final del arreglo)
	      
	      for ($i=0; $i<count($RESPUESTAS); $i++){
	      
		$id_pregunta_i=$PREGUNTA_CON_RESPUESTA['id_pregunta'][$i];
		$tipo_respuesta_i= trim($RESPUESTAS[$i], '"');     
		$respuesta_i=$PREGUNTA_CON_RESPUESTA['tipo_respuesta'][$i]['answeroptions'][$tipo_respuesta_i]['answer'];

		//Guardar las respuestas
		$sql="INSERT INTO RESPUESTA (token_ls, id_pregunta, respuesta) VALUES (";
		$sql.="'$token_ls', '$id_pregunta_i', '$respuesta_i')";
		$resultado_sql=ejecutarConsulta($sql, $conexion);
	      }
	      
	      $_SESSION['MSJ'] = "Sus respuestas han sido procesadas. Gracias por realizar la encuesta";
	      header("Location: ./vListarEvaluaciones.php?success"); 
	    } else {
	      //No finalizó la encuesta
	      $sql ="UPDATE PERSONA_ENCUESTA SET estado='En proceso', ip='".$ip."', fecha='".$fecha_intento."' ";
	      $sql.="WHERE ";
	      $sql.= "token_ls='".$token_ls."'";
	      $resultado=ejecutarConsulta($sql, $conexion);
	      $_SESSION['MSJ'] = "Sus respuestas han sido procesadas. Recuerde completar la encuesta antes de finalizar el periodo de evaluación";
	      header("Location: ./vListarEvaluaciones.php?warning"); 
	  }
	  $resultado=$client_ls->release_session_key($session_key);//Devolver llave de acceso a Limesurvey
      }
    }
    cerrarConexion($conexion);
    
?>
