<?php
    session_start();
    require "cAutorizacion.php";
    extract($_GET);
    extract($_POST);
    $_ERRORES = array();
    $_WARNING = array();
    $_SUCCESS = array();
    require_once 'XML/RPC2/Client.php';
    
if (isset($_GET['action'])) {
  switch($_GET['action']){
  
    case 'import': 

	  $client_ls = XML_RPC2_Client::create('http://localhost/limesurvey/index.php/admin/remotecontrol'); //Crear un cliente para comunicarse con Limesurvey
	  $session_key = $client_ls->get_session_key('admin', 'Segundo!!');//Pedir llave de acceso a Limesurvey
	  $id_encuesta_ls=intval($_POST['encuesta']);
	  
	  //Solicitar las secciones de preguntas de la encuesta
	  $resultado= $client_ls->list_groups($session_key, $id_encuesta_ls);
	  
	  if (isset($resultado['status'])){
	  
	   $_SESSION['MSJ']="El código de encuesta suministrado no existe. Por favor, intente de nuevo";
	   header("Location: ../vImportarEncuesta.php?error"); 
	   
	  } else {
	  
	    $sql="INSERT INTO ENCUESTA (id_car, estado, id_encuesta_ls) VALUES (";
            $sql.="'$_POST[car]', ";  //id de cargo            
            $sql.="'f', "; //estado actual de la encuesta
            $sql.="'$_POST[encuesta]')";  //id de la encuesta en limesurvey 
            $resultado_sql=ejecutarConsulta($sql, $conexion);
            echo "<br>INSERT: $sql<br>";
	    
	    //Pedir preguntas de cada sección
	    for($i=0; $i<count($resultado); $i++){
	    
	      if ($i==0){
		$seccion="factor";
	      } else {
		$seccion="competencia";
	      }
	    
	      echo "<br>ID DEL GRUPO $i: <br>";
	      echo $resultado[$i]['id']['gid'];
	      
	      $group_id=intval($resultado[$i]['id']['gid']); //ID de la sección de preguntas
	  
	      $preguntas= $client_ls->list_questions($session_key, $id_encuesta_ls, $group_id);
	      
	      //Pedir la información de cada pregunta
	      for($j=0; $j<count($preguntas); $j++){
	      
		 echo "<br>ID DE LA PREGUNTA $j: <br>";
		 echo $preguntas[$j]['id']['qid'];
		 echo "<br>QUÉ DICE LA PREGUNTA $j: <br>";
		 echo $preguntas[$j]['question'];
		 
		 $question_id=intval($preguntas[$j]['id']['qid']);
		 $question= $preguntas[$j]['question'];
		 $properties=array("subquestions");
		 $subpreguntas= $client_ls->get_question_properties($session_key, $question_id, $properties);
		 
		 if(is_array($subpreguntas['subquestions'])){
		 
		  //INSERT DE LA PREGUNTA
		  $sql="INSERT INTO PREGUNTA (id_encuesta_ls, id_pregunta, titulo, seccion) VALUES (";
		  $sql.="'$id_encuesta_ls', '$question_id', '$question', '$seccion')";
		  $resultado_sql=ejecutarConsulta($sql, $conexion);
		  echo "<br>INSERT: $sql<br>";
		  
		 
		  echo "<br>SÍ HAY SUBPREGUNTAS<br>";
		  //Pedir la información de las subpreguntas de cada pregunta
		  while (current($subpreguntas['subquestions'])) {
		     echo "<br>ID DE LA SUBPREGUNTA<br>";
		     $id_subquestion=key($subpreguntas['subquestions']);
		     echo $id_subquestion;
		     echo "<br>QUÉ DICE LA SUBPREGUNTA<br>";
		     echo $subpreguntas['subquestions'][$id_subquestion]['question'];
		     $subquestion= $subpreguntas['subquestions'][$id_subquestion]['question'];
		     
		     //INSERT DE LA SUBPREGUNTA
		     $sql="INSERT INTO PREGUNTA (id_encuesta_ls, id_pregunta, id_pregunta_root, titulo, seccion) VALUES (";
		     $sql.="'$id_encuesta_ls', '$id_subquestion', '$question_id', '$subquestion', '$seccion')";
		     $resultado_sql=ejecutarConsulta($sql, $conexion);
		     echo "<br>INSERT: $sql<br>";
		     
		     next($subpreguntas['subquestions']);
		     
		  } // cierre ciclo sobre subpreguntas
		 } else {
		 
		  $sql="INSERT INTO PREGUNTA (id_encuesta_ls, id_pregunta, titulo, seccion) VALUES (";
		  $sql.="'$id_encuesta_ls', '$question_id', '$question', '$seccion')";
		  $resultado_sql=ejecutarConsulta($sql, $conexion);
		  echo "<br>INSERT: $sql<br>";
		  echo "<br>NO HAY SUBPREGUNTAS<br>";
		 }
		 
	      } //cierre ciclo sobre preguntas
	      
	    } //cierre ciclo sobre secciones de preguntas
	    
	    
	  $_SESSION['MSJ']="La encuesta ha sido importada";
	  header("Location: ../vImportarEncuesta.php?action=pesos&id_encuesta_ls=$id_encuesta_ls"); 
	   
	  } //cierre else (ID encuesta válido)
	  
	  break;
	  
    case 'pesos':
    
	  print_r($_POST);	  
	  //$_SESSION['MSJ']="Los pesos suministrados fueron registrados";
	  //header("Location: ../vImportarEncuesta.php?success"); 
    
	  break;
                
  } //cierre switch
} //cierre if

  //Lista de cargos
  $CAR_ID = obtenerIds($conexion, "CARGO", false);
    
  if (isset($_GET['id_encuesta_ls'])){
    //Lista de preguntas
    $sql ="SELECT * ";
    $sql.="FROM PREGUNTA WHERE id_encuesta_ls='".$_GET['id_encuesta_ls']."'";        
    $atts = array("id_encuesta_ls","id_pregunta", "id_pregunta_root", "titulo", "peso", "seccion");
    $LISTA_PREGUNTA= obtenerDatos($sql, $conexion, $atts, "Preg"); 
  }
  

  cerrarConexion($conexion);

?>
