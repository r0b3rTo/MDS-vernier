<?php
    session_start();
    require "cAutorizacion.php";
    extract($_GET);
    extract($_POST);
    $_ERRORES = array();
    $_WARNING = array();
    $_SUCCESS = array();
    require_once 'XML/RPC2/Client.php';
    
    
    //Lista de cargos
    $CAR_ID = obtenerIds($conexion, "CARGO", false);
    
    if (isset($_GET['id_encuesta_ls'])){
      //Lista de preguntas
      $sql ="SELECT * ";
      $sql.="FROM PREGUNTA WHERE id_encuesta_ls='".$_GET['id_encuesta_ls']."' AND seccion='factor'";        
      $atts = array("id_encuesta_ls","id_pregunta", "id_pregunta_root", "titulo", "peso", "seccion");
      $LISTA_PREGUNTA= obtenerDatos($sql, $conexion, $atts, "Preg"); 
    }
    
if (isset($_GET['action'])) {
  switch($_GET['action']){
  
    case 'import': 
	  
	  //Verificar si la encuesta ya existe o si ya existe una encuesta para ese cargo
	  $sql ="SELECT id_encuesta_ls FROM ENCUESTA WHERE id_encuesta_ls='".$_POST[encuesta]."'";        
	  $atts = array("id_encuesta_ls");
	  $ENCUESTA_IMPORTADA= obtenerDatos($sql, $conexion, $atts, "Enc");
	  $sql ="SELECT id_car FROM ENCUESTA WHERE id_car='".$_POST[car]."'";        
	  $atts = array("id_car");
	  $CARGO_EVALUADO= obtenerDatos($sql, $conexion, $atts, "Car");
	  
	  if ($ENCUESTA_IMPORTADA[max_res]!=0){
	    $_SESSION['MSJ']="La encuesta indicada ya ha sido importada al sistema";
	    header("Location: ../vImportarEncuesta.php?error"); 
	  } else if ($CARGO_EVALUADO[max_res]!=0){
	    $_SESSION['MSJ']="El cargo indicado ya tiene una encuesta asociada";
	    header("Location: ../vImportarEncuesta.php?error");
	  } else {
	  
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
	      
	      //Pedir preguntas de cada sección
	      for($i=0; $i<count($resultado); $i++){
	      
		if ($i==0){
		  $seccion="competencia";
		} else {
		  $seccion="factor";
		}
	   
		$group_id=intval($resultado[$i]['id']['gid']); //ID de la sección de preguntas
	    
		$preguntas= $client_ls->list_questions($session_key, $id_encuesta_ls, $group_id);
		
		//Pedir la información de cada pregunta
		for($j=0; $j<count($preguntas); $j++){
				  
		  $question_id=intval($preguntas[$j]['id']['qid']);
		  $question= $preguntas[$j]['question'];
		  $properties=array("subquestions");
		  $subpreguntas= $client_ls->get_question_properties($session_key, $question_id, $properties);
		  
		  if(is_array($subpreguntas['subquestions'])){
		  
		    //INSERT DE LA PREGUNTA
		    $sql="INSERT INTO PREGUNTA (id_encuesta_ls, id_pregunta, titulo, seccion) VALUES (";
		    $sql.="'$id_encuesta_ls', '$question_id', '$question', '$seccion')";
		    $resultado_sql=ejecutarConsulta($sql, $conexion);
		    
		    //Pedir la información de las subpreguntas de cada pregunta
		    while (current($subpreguntas['subquestions'])) {
		      $id_subquestion=key($subpreguntas['subquestions']);
		      $subquestion= $subpreguntas['subquestions'][$id_subquestion]['question'];
		      
		      //INSERT DE LA SUBPREGUNTA
		      $sql="INSERT INTO PREGUNTA (id_encuesta_ls, id_pregunta, id_pregunta_root, titulo, seccion) VALUES (";
		      $sql.="'$id_encuesta_ls', '$id_subquestion', '$question_id', '$subquestion', '$seccion')";
		      $resultado_sql=ejecutarConsulta($sql, $conexion);
		      
		      next($subpreguntas['subquestions']);
		      
		    } // cierre ciclo sobre subpreguntas
		  } else {
		  
		    $sql="INSERT INTO PREGUNTA (id_encuesta_ls, id_pregunta, titulo, seccion) VALUES (";
		    $sql.="'$id_encuesta_ls', '$question_id', '$question', '$seccion')";
		    $resultado_sql=ejecutarConsulta($sql, $conexion);
		  }
		  
		} //cierre ciclo sobre preguntas
	      } //cierre ciclo sobre secciones de preguntas
	      
	    $_SESSION['MSJ']="La encuesta ha sido importada";
	    header("Location: ../vImportarEncuesta.php?action=pesos&id_encuesta_ls=$id_encuesta_ls"); 
	    
	    } //cierre else (ID encuesta válido)
	  } //cierre else (verificación de datos no repetidos)
	   
	  break;
	  
    case 'set':
    
	  print_r($_POST);
	  
	  for ($i=0; $i<$LISTA_PREGUNTA[max_res]; $i++){
	    $id_pregunta=$LISTA_PREGUNTA['Preg']['id_pregunta'][$i];
	    $tag='peso_'.$id_pregunta;
	    if($_POST[$tag]!='-'){
	      $sql="UPDATE PREGUNTA SET peso='".$_POST[$tag]."' WHERE id_pregunta='".$id_pregunta."'";
	      echo $sql;
	      $resultado_sql=ejecutarConsulta($sql,$conexion);
	    } else {
	      $sql="UPDATE PREGUNTA SET peso=NULL WHERE id_pregunta='".$id_pregunta."'";
	      echo $sql;
	      $resultado_sql=ejecutarConsulta($sql,$conexion);
	    }
	  }
	  
	  $_SESSION['MSJ']="Los pesos suministrados fueron registrados";
	  header("Location: ../vImportarEncuesta.php?success"); 
    
	  break;
                
  } //cierre switch
} //cierre if
  

  cerrarConexion($conexion);

?>
