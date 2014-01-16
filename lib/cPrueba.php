<?php
    session_start();
    require "cAutorizacion.php";
    extract($_GET);
    extract($_POST);
    date_default_timezone_set('America/Caracas');
    $_ERRORES = array();
    $_WARNING = array();
    $_SUCCESS = array();

    //Lista de personas
    $PERSONA_ID = obtenerIds($conexion, "PERSONA", true);
    
    //Lista de unidades
    $UNIDAD_ID = obtenerIds($conexion, "ORGANIZACION", false);
    
 
    if (isset($_GET['action'])){
      switch ($_GET['action']) {
      
	//MANEJO DE LA BÚSQUEDA DE RESULTADOS RESULTADOS POR PERSONA
	case 'stats_persona':
	   //Manejo de errores en la entrada
	   if (isset($_GET['input'])){
	    switch ($_GET['input']) {
	      case '1':
		if ($_POST['per']==0) {
		
		  $_SESSION['MSJ']="Por favor seleccione una persona o unidad valida";
		  header("Location: ../vPrueba.php?error");
		  
		} else {
		
		  //Determinar cargos de la persona
		  $sql="SELECT id_car, fecha_ini, fecha_fin FROM PERSONA_CARGO WHERE id_per='".$_POST['per']."'";
		  $atts= array("id_car", "fecha_ini", "fecha_fin", "nombre");
		  $LISTA_CARGO=obtenerDatos($sql, $conexion, $atts, "Car");
		  
		  //La persona no tiene ningún cargo registrado
		  if(!count($LISTA_CARGO['Car']['id_car'])){
		    $_SESSION['MSJ']="La persona seleccionada no tiene un histórico de cargos registrado en el sistema";
		    header("Location: ../vPrueba.php?error");
		  } else {
		    header("Location: ../vPrueba.php?action=stats_persona&step=1&id=".$_POST['per']);
		  }
		  
		}//Fin del condicional (persona válida)
	      //Fin case input=0
	      break;
	      
	      case '2':
	      
		//Determinar los procesos en los que ha participado la persona
		$sql="SELECT DISTINCT periodo FROM PERSONA_ENCUESTA WHERE id_evaluado='".$_GET['id']."' AND id_car='".$_POST['car']."' AND estado!='Pendiente' AND estado!='En proceso'";
		$atts= array("periodo", "nombre");
		$LISTA_ENCUESTA=obtenerDatos($sql, $conexion, $atts, "Enc");
	    
		if(count($LISTA_ENCUESTA['Enc']['periodo'])){
		  header("Location: ../vPrueba.php?action=stats_persona&step=2&id=".$_GET['id'].'&car='.$_POST['car']);
		} else {
		  $_SESSION['MSJ']="No se han registrado en el sistema resultados para la evaluación del cargo seleccionado";
		  header("Location: ../vPrueba.php?error");
		}
	      //Fin case input=1
	      break;
	      
	      case '3':

		if($_POST['proc']){
		  //Escogió un proceso de evaluación en particular
		  
		  //Determinar token_ls para ir a la vista de resultados particulares
		  $sql="SELECT token_ls FROM PERSONA_ENCUESTA WHERE id_evaluado='".$_GET['id']."' AND id_encuestado='".$_GET['id']."'AND id_car='".$_GET['car']."' AND periodo='".$_POST['proc']."'";
		  $atts= array("token_ls");
		  $aux=obtenerDatos($sql, $conexion, $atts, "Aux");
		  $token_ls=$aux['Aux']['token_ls'][0];
		  header("Location: ../vResultados.php?token_ls=".$token_ls);
		  
		} else {
		
		  //Escogió la opción de histórico de resultados
		  //Mostrar vista de histórico de resultados
		  header("Location: ../vPrueba.php?action=hist_per&id=".$_GET['id']."&car=".$_GET['car']."&proc=".$_POST['proc']);
		  
		}
		
		
		
		
	      break;
	    }//cierre del case (entradas del formulario)
	   }//cierre del if
	   //Fin del manejo de errores en la entrada
	   
	   if(isset($_GET['step']) && isset($_GET['id'])){
    
	      //Determinar nombre de la persona seleccionada
	      $sql="SELECT nombre, apellido, cedula FROM PERSONA WHERE id='".$_GET['id']."'";
	      $atts= array("nombre", "apellido", "cedula");
	      $aux=obtenerDatos($sql, $conexion, $atts, "Nom");
	      $NOMBRE=$aux['Nom']['nombre'][0].' '.$aux['Nom']['apellido'][0];
	      $CEDULA=$aux['Nom']['cedula'][0];
	      
	      //Datos
	      if ($_GET['step']==1){
		//Determinar cargos de la persona seleccionada
		$sql="SELECT id_car, fecha_ini, fecha_fin FROM PERSONA_CARGO WHERE id_per='".$_GET['id']."'";
		$atts= array("id_car", "fecha_ini", "fecha_fin", "nombre");
		$LISTA_CARGO=obtenerDatos($sql, $conexion, $atts, "Car");
		
		for($i=0; $i<count($LISTA_CARGO['Car']['id_car']); $i++){
		  $sql="SELECT nombre FROM CARGO WHERE id='".$LISTA_CARGO['Car']['id_car'][$i]."'";
		  $atts= array("nombre");
		  $aux=obtenerDatos($sql, $conexion, $atts, "Car");
		  $LISTA_CARGO['Car']['nombre'][$i]=$aux['Car']['nombre'][0];
		}
	      }//PASO 1
	      
	      if($_GET['step']==2){
		//Determinar nombre del cargo seleccionado
		$sql="SELECT nombre FROM CARGO WHERE id='".$_GET['car']."'";
		$atts= array("nombre");
		$aux=obtenerDatos($sql, $conexion, $atts, "Car");
		$CARGO=$aux['Car']['nombre'][0];
		
		//Determinar los procesos de evaluación en los que ha participado
		$sql="SELECT DISTINCT periodo FROM PERSONA_ENCUESTA WHERE id_evaluado='".$_GET['id']."' AND id_car='".$_GET['car']."' AND estado!='Pendiente' AND estado!='En proceso'";
		$atts= array("periodo", "nombre");
		$LISTA_ENCUESTA=obtenerDatos($sql, $conexion, $atts, "Enc");
		
		for($i=0; $i<count($LISTA_ENCUESTA['Enc']['periodo']); $i++){
		  $sql="SELECT periodo FROM EVALUACION WHERE id='".$LISTA_ENCUESTA['Enc']['periodo'][$i]."'";
		  $atts= array("periodo");
		  $aux=obtenerDatos($sql, $conexion, $atts, "Eva");
		  $LISTA_ENCUESTA['Enc']['nombre'][$i]=$aux['Eva']['periodo'][0];
		}
		
		//Agregar la opción de histórico
		array_push($LISTA_ENCUESTA['Enc']['periodo'], 0);
		array_push($LISTA_ENCUESTA['Enc']['nombre'], 'Histórico');

	      }//PASO 2
	      
	    }
	   
	//FIN DEL MANEJO DE LA BÚSQUEDA DE RESULTADOS POR PERSONA  
	break;
	
	//MANEJO DE LA BÚSQUEDA DE RESULTADOS POR UNIDAD
	case 'stats_unidad':
	  if (isset($_GET['input'])){
	    switch ($_GET['input']) {
	    
	      case '1':
	      
		if ($_POST['uni']==0) {
		  $_SESSION['MSJ']="Por favor seleccione una persona o unidad valida";
		  header("Location: ../vPrueba.php?error");
		} else {
		  //Determinar si la unidad ha sido evaluada
		  $sql="SELECT DISTINCT periodo FROM PERSONA_ENCUESTA WHERE id_unidad='".$_POST['uni']."' AND estado!='Pendiente' AND estado!='En proceso'";
		  $atts= array("periodo");
		  $aux=obtenerDatos($sql, $conexion, $atts, "Aux");
		
		  if(count($aux['Aux']['periodo'])){
		    header("Location: ../vPrueba.php?action=stats_unidad&step=1&id=".$_POST['uni']);
		  } else {
		    $_SESSION['MSJ']="No se han registrado en el sistema resultados para la evaluación de la unidad seleccionada";
		    header("Location: ../vPrueba.php?error");
		  }
		}
	      //Fin case input=1
	      break;
	      
	      case '2';
	      
		if($_POST['proc']){
		  //Escogió un proceso de evaluación en particular
		  header("Location: ../vPrueba.php?action=view_uni&id=".$_GET['id']."&proc=".$_POST['proc']);
		} else {
		  //Mostrar vista de resultados
		  header("Location: ../vPrueba.php?action=hist_uni&id=".$_GET['id']."&proc=".$_POST['proc']);
		}
		
		
	      break;
	      
	    }// cierre del case (entradas del formulario)
	  }//cierre if
	  
	  if(isset($_GET['step']) && isset($_GET['id'])){
    
	      //Determinar nombre de la unidad seleccionada
	      $sql="SELECT nombre FROM ORGANIZACION WHERE id='".$_GET['id']."'";
	      $atts= array("nombre");
	      $aux=obtenerDatos($sql, $conexion, $atts, "Uni");
	      $NOMBRE_UNIDAD=$aux['Uni']['nombre'][0];
	      
	      //Determinar los procesos de evaluación en los que ha participado
	      $sql="SELECT DISTINCT periodo FROM PERSONA_ENCUESTA WHERE id_unidad='".$_GET['id']."' AND estado!='Pendiente' AND estado!='En proceso'";
	      $atts= array("periodo", "nombre");
	      $LISTA_ENCUESTA=obtenerDatos($sql, $conexion, $atts, "Enc");
	      
	      for($i=0; $i<count($LISTA_ENCUESTA['Enc']['periodo']); $i++){
		$sql="SELECT periodo FROM EVALUACION WHERE id='".$LISTA_ENCUESTA['Enc']['periodo'][$i]."'";
		$atts= array("periodo");
		$aux=obtenerDatos($sql, $conexion, $atts, "Eva");
		$LISTA_ENCUESTA['Enc']['nombre'][$i]=$aux['Eva']['periodo'][0];
	      }
	      //Agregar la opción de histórico
	      array_push($LISTA_ENCUESTA['Enc']['periodo'], 0);
	      array_push($LISTA_ENCUESTA['Enc']['nombre'], 'Histórico'); 
	    }
	  
	//FIN DEL MANEJO DE LA BÚSQUEDA POR UNIDAD
	break;
	
	//HISTORICO DE RESULTADOS PARA UNA PERSONA
	case 'hist_per':
	  
	  #...code
	  
	//FIN DE HISTORICO DE RESULTADOS PARA UNA PERSONA
	break;
	
	//MUESTRA DE RESULTADOS PARA UNA UNIDAD
	case 'view_uni':
	  
	  //Determinar datos de la unidad seleccionada
	  $sql="SELECT nombre FROM ORGANIZACION WHERE id='".$_GET['id']."'";
	  $atts= array("nombre");
	  $UNIDAD=obtenerDatos($sql, $conexion, $atts, "Uni");
	  
	  //Determinar datos del proceso de evaluación seleccionado
	  $sql="SELECT periodo FROM EVALUACION WHERE id='".$_GET['proc']."'";
	  $atts= array("periodo");
	  $PROCESO=obtenerDatos($sql, $conexion, $atts, "Proc");
	  
	  
	  //Obtener datos de las evaluaciones de la unidad para el proceso seleccionado
	  $sql="SELECT id_encuesta, id_evaluado, token_ls, estado FROM PERSONA_ENCUESTA WHERE id_unidad='".$_GET['id']."' AND periodo='".$_GET['proc']."' AND tipo='autoevaluacion'";
	  $atts= array("id_encuesta", "id_evaluado", "token_ls", "estado", "nombre");
	  $LISTA_EVALUADOS=obtenerDatos($sql, $conexion, $atts, "Eva");
	  
	  
	  for($i=0; $i<$LISTA_EVALUADOS['max_res']; $i++){
	    $sql="SELECT nombre, apellido FROM PERSONA WHERE id='".$LISTA_EVALUADOS['Eva']['id_evaluado'][$i]."'";
	    $atts= array("nombre", "apellido");
	    $aux=obtenerDatos($sql, $conexion, $atts, "Nom");
	    $LISTA_EVALUADOS['Eva']['nombre'][$i]=$aux['Nom']['nombre'][0].' '.$aux['Nom']['apellido'][0];
	  }
	  
	  $LISTA_COMPETENCIAS=array();
	  $LISTA_FACTORES=array();
	  
	  //Obtener los resultados para cada evaluado
	  for($i=0; $i<$LISTA_EVALUADOS['max_res']; $i++){
	    //Respuestas para la sección de competencias
	    $sql="SELECT id_pregunta, titulo FROM PREGUNTA WHERE id_encuesta='".$LISTA_EVALUADOS['Eva']['id_encuesta'][$i]."' AND seccion='competencia' AND id_pregunta_root_ls IS NOT NULL";
	    $atts= array("id_pregunta", "titulo", "respuesta");
	    $aux=obtenerDatos($sql, $conexion, $atts, "Aux");
	    array_push($LISTA_COMPETENCIAS, $aux['Aux']);
	    for($j=0; $j<count($LISTA_COMPETENCIAS[$i]['id_pregunta']); $j++){
	      $sql="SELECT respuesta FROM RESPUESTA WHERE id_pregunta='".$LISTA_COMPETENCIAS[$i]['id_pregunta'][$j]."' AND token_ls='".$LISTA_EVALUADOS['Eva']['token_ls'][$i]."'";
	      $atts= array("respuesta");
	      $aux=obtenerDatos($sql, $conexion, $atts, "Aux");
	      $LISTA_COMPETENCIAS[$i]['respuesta'][$j]=$aux['Aux']['respuesta'][0];
	    }
	    
	    //Respuestas para la sección de factores
	    $sql="SELECT id_pregunta, titulo FROM PREGUNTA WHERE id_encuesta='".$LISTA_EVALUADOS['Eva']['id_encuesta'][$i]."' AND seccion='factor' AND id_pregunta_root_ls IS NOT NULL";
	    $atts= array("id_pregunta", "titulo", "respuesta");
	    $aux=obtenerDatos($sql, $conexion, $atts, "Aux");
	    array_push($LISTA_FACTORES, $aux['Aux']);
	    for($j=0; $j<count($LISTA_FACTORES[$i]['id_pregunta']); $j++){
	      $sql="SELECT respuesta FROM RESPUESTA WHERE id_pregunta='".$LISTA_FACTORES[$i]['id_pregunta'][$j]."' AND token_ls='".$LISTA_EVALUADOS['Eva']['token_ls'][$i]."'";
	      $atts= array("respuesta");
	      $aux=obtenerDatos($sql, $conexion, $atts, "Aux");
	      $LISTA_FACTORES[$i]['respuesta'][$j]=$aux['Aux']['respuesta'][0];
	    }
	  }	  
	  
	
	//FIN DE LA MUESTRA DE RESULTADOS PARA UNA UNIDAD
	break;
	
	//HISTORICO DE RESULTADOS PARA UNA UNIDAD
	case 'hist_uni':
	
	  #...code
	
	//FIN DE HISTORICO DE RESULTADOS PARA UNA UNIDAD
	break;

      } //cierre del switch 
    } //cierre del condicional

    
   

    //Cierre conexión a la BD
    cerrarConexion($conexion);

  /*
  //Buscar los procesos de evaluación en los que ha participado
	    $sql="SELECT token_ls, id_encuesta, estado, tipo FROM PERSONA_ENCUESTA WHERE id_evaluado='".$_POST['per']."' AND periodo='".$_POST['proc']."'";
	    $atts= array("token_ls", "id_encuesta", "estado", "tipo");
	    $LISTA_ENCUESTA=obtenerDatos($sql, $conexion, $atts, "Enc");
	  
	    if ($_POST['proc']==0){
	      //Si seleccionó visualizar el histórico de resultados
	      echo "seleccionada la op. historic!!! ";
	    } else {
	      //Si seleccionó un proceso de evaluación en particular
	      
	      //Lista de evaluaciones sobre la persona seleccionada
	      $sql="SELECT token_ls, id_encuesta, estado, tipo FROM PERSONA_ENCUESTA WHERE id_evaluado='".$_POST['per']."' AND periodo='".$_POST['proc']."'";
	      $atts= array("token_ls", "id_encuesta", "estado", "tipo");
	      $LISTA_ENCUESTA=obtenerDatos($sql, $conexion, $atts, "Enc");
	      
	      if (!$LISTA_ENCUESTA['max_res']){
		//No hay encuestas para la persona
		$_SESSION['MSJ']="La persona seleccionada no fue evaluada en este proceso de evaluación";
		header("Location: ../vPrueba.php?error"); 
	      } else {
		$l=0; //Índice de encuestas realizadas
		for ($i=0; $i<$LISTA_ENCUESTA['max_res']; $i++){
		  if($LISTA_ENCUESTA['Enc']['estado'][$i]=='En proceso' || $LISTA_ENCUESTA['Enc']['estado'][$i]=='Pendiente'){
		    $l++;
		  }
		}
		if ($l==$LISTA_ENCUESTA['max_res']){
		  //La evaluación no fue realizada por ninguna de las partes
		  $_SESSION['MSJ']="La evaluación de la persona seleccionada no fue completada por ninguna de las partes involucradas en el proceso de evaluación";
		  header("Location: ../vPrueba.php?error");
		} else {
		  //Al menos una evaluación fue realizada
		  $EVALUACION=array(); //Inicialización
		  //Preguntas de la encuesta
		  $sql="SELECT id_pregunta, titulo FROM PREGUNTA WHERE id_encuesta='".$LISTA_ENCUESTA['Enc']['id_encuesta'][0]."' AND id_pregunta_root_ls IS NOT NULL AND seccion='competencia'";
		  $atts= array("id_pregunta", "titulo", "respuesta");
		  $competencias=obtenerDatos($sql, $conexion, $atts, "Preg"); //Preguntas de la sección de competencias
		  $sql="SELECT id_pregunta, titulo, peso FROM PREGUNTA WHERE id_encuesta='".$LISTA_ENCUESTA['Enc']['id_encuesta'][0]."' AND id_pregunta_root_ls IS NOT NULL AND seccion='factor'";
		  $atts= array("id_pregunta", "titulo", "peso", "respuesta");
		  $factores=obtenerDatos($sql, $conexion, $atts, "Preg"); //Preguntas de la sección de factores de desempeño
		  
		  //Iteración sobre las evaluaciones registradas
		  for ($i=0; $i<$LISTA_ENCUESTA['max_res']; $i++){
		    //Si fue realizada
		    if($LISTA_ENCUESTA['Enc']['estado'][$i]!='En proceso' || $LISTA_ENCUESTA['Enc']['estado'][$i]!='Pendiente'){
		      
			//Iteración sobre las preguntas de la seccion de competencias
			for ($j=0; $j<count($competencias['Preg']['id_pregunta']); $j++){
			  //Obtener respuesta
			  $sql="SELECT respuesta FROM RESPUESTA WHERE id_pregunta='".$competencias['Preg']['id_pregunta'][$j]."' AND token_ls='".$LISTA_ENCUESTA['Enc']['token_ls'][$i]."'";
			  $atts= array("respuesta");
			  $aux=obtenerDatos($sql, $conexion, $atts, "Aux");
			  //Guardar en arreglo
			  $competencias['Preg']['respuesta'][$j]=$aux['Aux']['respuesta'][0];
			}//cierre de la iteración
			
			//Iteración sobre las preguntas de la seccion de competencias
			for ($j=0; $j<count($factores['Preg']['id_pregunta']); $j++){
			  //Obtener respuesta
			  $sql="SELECT respuesta FROM RESPUESTA WHERE id_pregunta='".$factores['Preg']['id_pregunta'][$j]."' AND token_ls='".$LISTA_ENCUESTA['Enc']['token_ls'][$i]."'";
			  $atts= array("respuesta");
			  $aux=obtenerDatos($sql, $conexion, $atts, "Aux");
			  //Guardar en arreglo
			  $factores['Preg']['respuesta'][$j]=$aux['Aux']['respuesta'][0];  
			}//cierre de la iteración
			  
			if($LISTA_ENCUESTA['Enc']['tipo'][$i]=='autoevaluacion'){
			  $AUTOEVALUACION=array($competencias['Preg'], $factores['Preg']);
			} else {
			  array_push($EVALUACION,array($competencias['Preg'], $factores['Preg']));
			}
		    } //Fin del condicional (evaluacion realizada)
		 } //Fin de la iteración sobre las evaluaciones
		}//Fin del condicional (al menos una evaluacion realizada)
	      }//Fin del condicional (hay encuestas)
	    }//Fin del condicional (proceso seleccionado)
  */
?> 


