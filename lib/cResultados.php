<?php
    session_start();
    //require "cAutorizacion.php";
    //POR AHORA!!!!! CAMBIAR!!!!!
    require "cConstantes.php";
    extract($_GET);
    extract($_POST);
    date_default_timezone_set('America/Caracas');
    $_ERRORES = array();
    $_WARNING = array();
    $_SUCCESS = array();
    require_once 'XML/RPC2/Client.php';
    
    if (isset($_GET['action'])){
      switch ($_GET['action']) {
      
	case 'add':

	  //code
	  break;
	  
	case 'activar':
	
	  //code
	  break;
	  
	case 'default':    
	  #code
	  break;
	  
      } //cierre switch
    } //cierre if
    
    // Obtención de resultados para una encuesta
	  $client_ls = XML_RPC2_Client::create('http://localhost/limesurvey/index.php/admin/remotecontrol'); //Crear un cliente para comunicarse con Limesurvey
	  $session_key = $client_ls->get_session_key('admin', 'Segundo!!');//Pedir llave de acceso a Limesurvey
	  $id_encuesta_ls=intval($_GET['id_encuesta_ls']);
	  $token_ls=$_GET['token_ls'];
	  //Hallar token ID
	  //$sql ="SELECT tid_ls FROM PERSONA_ENCUESTA WHERE token_ls='".$token_ls."'";
	  //$atts = array("tid_ls");
	  //$resultado= obtenerDatos($sql, $conexion, $atts, "Tok");
	  //$tid_ls=intval($resultado['Tok']['tid_ls'][0]);
	  //Solicitar estado de la encuesta a Limesurvey
	
	  $resultado= $client_ls->list_questions($session_key, $id_encuesta_ls);
	  echo "Este es el resultado de listar preguntas para nuestra encuesta:<br><br>";
	  print_r($resultado);
	  
	  $resultado= $client_ls->list_groups($session_key, $id_encuesta_ls);
	  echo "<br><br>Este es el resultado de listar los grupos para nuestra encuesta:<br><br>";
	  print_r($resultado);
	  
	  $group_id=10;
	  $resultado= $client_ls->list_questions($session_key, $id_encuesta_ls, $group_id);
	  echo "<br><br>Este es el resultado de listar preguntas para el primer grupo de nuestra encuesta:<br><br>";
	  print_r($resultado);
	  
	  $properties=array("subquestions");
	  $question_id=88;
	  $resultado= $client_ls->get_question_properties($session_key, $question_id, $properties);
	  echo "<br><br>Este es el resultado de listar subpreguntas cuando SI HAY:<br><br>";
	  print_r($resultado);
	  
	  echo "<br> PRUEBA <br>";
	  while ($nombre_fruta = current($resultado['subquestions'])) {
	      
		  echo key($resultado['subquestions']).'<br />';
	     
	      next($resultado['subquestions']);
	  }
	  
	  $question_id=89;
	  $resultado= $client_ls->get_question_properties($session_key, $question_id, $properties);
	  echo "<br><br>Este es el resultado de listar subpreguntas cuando NO HAY:<br><br>";
	  print_r($resultado);
	  
	  
	  $resultado= $client_ls->export_responses_by_token($session_key, $id_encuesta_ls, 'csv',$token_ls);//Obtener respuestas para la encuesta
	  echo "<br><br>Este es el resultado de exportar respuestas para nuestra encuesta:<br><br>";
	  print_r($resultado);
	  
	  $resultado=base64_decode($resultado);
	  echo "<br><br>Este es el resultado DECODED:<br><br>";
	  print_r($resultado);
	  
	  $aux= explode(",",$resultado);
	  echo "<br><br>Este es nuestro arreglo: <br><br>";
	  print_r($aux);
	  
	  $resultado=$client_ls->release_session_key($session_key);//Devolver llave de acceso a Limesurvey
    
    
    
    //Cierre conexión a la BD
    cerrarConexion($conexion);

    if (isset($_GET['action'])){
        switch ($_GET['action']) {
        
            case 'add':
                $_SESSION['MSJ'] = "Se ha agregado un nuevo proceso de evaluación";
                header("Location: ../vEvaluaciones.php?success"); 
                break;
		
            default:
                # code...
                break;            
        }
    }
  
?> 


