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
      
	//------------------------------------------------
	//------------------------------------------------
	//MANEJO DE LA BÚSQUEDA DE RESULTADOS POR PERSONA
	//------------------------------------------------
	//------------------------------------------------
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

	break;
	//------------------------------------------------------- 
	//-------------------------------------------------------
	//FIN DEL MANEJO DE LA BÚSQUEDA DE RESULTADOS POR PERSONA  
	//-------------------------------------------------------
	//-------------------------------------------------------
	
 
	//----------------------------------------------
	//----------------------------------------------
	//MANEJO DE LA BÚSQUEDA DE RESULTADOS POR UNIDAD
	//----------------------------------------------
	//----------------------------------------------
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
	  
	break;
	//----------------------------------------
	//----------------------------------------
	//FIN DEL MANEJO DE LA BÚSQUEDA POR UNIDAD
	//----------------------------------------
	//----------------------------------------
	
	//----------------------------------------
	//----------------------------------------
	//HISTORICO DE RESULTADOS PARA UNA PERSONA
	//----------------------------------------
	//----------------------------------------
	case 'hist_per':
	  
	  #...code
	  
	break;
	//-----------------------------------------------
	//-----------------------------------------------
	//FIN DE HISTORICO DE RESULTADOS PARA UNA PERSONA
	//-----------------------------------------------
	//-----------------------------------------------
	
	//----------------------------------------
	//----------------------------------------
	//MUESTRA DE RESULTADOS PARA UNA UNIDAD
	//----------------------------------------
	//----------------------------------------
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
	  	  
	  //Obtener nombres de los evaluados
	  for($i=0; $i<$LISTA_EVALUADOS['max_res']; $i++){
	    $sql="SELECT nombre, apellido FROM PERSONA WHERE id='".$LISTA_EVALUADOS['Eva']['id_evaluado'][$i]."'";
	    $atts= array("nombre", "apellido");
	    $aux=obtenerDatos($sql, $conexion, $atts, "Nom");
	    $LISTA_EVALUADOS['Eva']['nombre'][$i]=$aux['Nom']['nombre'][0].' '.$aux['Nom']['apellido'][0];
	  }
	  
	  
	  //Obtener los resultados para cada evaluado
	  for($i=0; $i<$LISTA_EVALUADOS['max_res']; $i++){
	  
	      //--------------------------------------------------------------------
	      //CALCULO DE RESULTADOS PARA LA AUTOEVALUACIÓN DEL TRABAJADOR EVALUADO
	      //--------------------------------------------------------------------
	      
	      //-----------------------------------------------------
	      //CALCULO DE RESULTADOS PARA LA SECCIÓN DE COMPETENCIAS
	      //-----------------------------------------------------
	      $sql="SELECT id_pregunta FROM PREGUNTA WHERE id_encuesta='".$LISTA_EVALUADOS['Eva']['id_encuesta'][$i]."' AND seccion='competencia' AND id_pregunta_root_ls IS NOT NULL";
	      $atts= array("id_pregunta", "respuesta");
	      $LISTA_COMPETENCIAS=obtenerDatos($sql, $conexion, $atts, "Comp");
	      
	      $LISTA_EVALUADOS['Eva']['autoevaluacion']['competencias']['maximo'][$i]=$LISTA_COMPETENCIAS['max_res']*3;//puntaje máximo en la sección de competencias
	      
	      $puntaje=0; //Inicialización
	      if($LISTA_EVALUADOS['Eva']['estado'][$i]!='Pendiente' && $LISTA_EVALUADOS['Eva']['estado'][$i]!='En proceso') {
		
		for($j=0; $j<$LISTA_COMPETENCIAS['max_res']; $j++){

		  $sql="SELECT respuesta FROM RESPUESTA WHERE id_pregunta='".$LISTA_COMPETENCIAS['Comp']['id_pregunta'][$j]."' AND token_ls='".$LISTA_EVALUADOS['Eva']['token_ls'][$i]."'";
		  $atts= array("respuesta");
		  $aux=obtenerDatos($sql, $conexion, $atts, "Aux");
		  $LISTA_COMPETENCIAS['Comp']['respuesta'][$j]=$aux['Aux']['respuesta'][0];
		  
		  
		   switch($LISTA_COMPETENCIAS['Comp']['respuesta'][$j]){
		    case 'Siempre':
		      $puntaje+=3;
		    break;
		    case 'Casi siempre':
		      $puntaje+=2;
		    break;
		    case 'Pocas veces':
		      $puntaje+=1;
		    break;
		  } //Fin del switch
		  
		}//Cierre del ciclo sobre las preguntas de competencias
	      }//Fin del condicional (evaluación completada)
	      
	      $LISTA_EVALUADOS['Eva']['autoevaluacion']['competencias']['puntaje'][$i]=$puntaje;
	      //---------------------------
	      //FIN RESULTADOS COMPETENCIAS
	      //---------------------------
	      
	      //-------------------------------------------------
	      //CALCULO DE RESULTADOS PARA LA SECCIÓN DE FACTORES
	      //-------------------------------------------------
	      
	      //Respuestas para la sección de factores
	      $sql="SELECT id_pregunta FROM PREGUNTA WHERE id_encuesta='".$LISTA_EVALUADOS['Eva']['id_encuesta'][$i]."' AND seccion='factor' AND id_pregunta_root_ls IS NOT NULL";
	      $atts= array("id_pregunta", "respuesta");
	      $LISTA_FACTORES=obtenerDatos($sql, $conexion, $atts, "Fac");
	
	      $LISTA_EVALUADOS['Eva']['autoevaluacion']['factores']['maximo'][$i]=$LISTA_FACTORES['max_res']*3;//puntaje máximo en la sección de factores

	      
	      if($LISTA_EVALUADOS['Eva']['estado'][$i]!='Pendiente' && $LISTA_EVALUADOS['Eva']['estado'][$i]!='En proceso') {
		
		$puntaje=0; //Inicialización
		for($j=0; $j<$LISTA_FACTORES['max_res']; $j++){
		
		  $sql="SELECT respuesta FROM RESPUESTA WHERE id_pregunta='".$LISTA_FACTORES['Fac']['id_pregunta'][$j]."' AND token_ls='".$LISTA_EVALUADOS['Eva']['token_ls'][$i]."'";
		  $atts= array("respuesta");
		  $aux=obtenerDatos($sql, $conexion, $atts, "Aux");
		  $LISTA_FACTORES['Fac']['respuesta'][$j]=$aux['Aux']['respuesta'][0];
		  
		   switch($LISTA_FACTORES['Fac']['respuesta'][$j]){
		    case 'Excelente':
		      $puntaje+=3;
		    break;
		    case 'Sobre lo esperado':
		      $puntaje+=2;
		    break;
		    case 'En lo esperado':
		      $puntaje+=1;
		    break;
		  } //Fin del switch
		  
		}//Cierre del ciclo sobre las preguntas de competencias
		
	      }//Fin del condicional (evaluación completada)
	      $LISTA_EVALUADOS['Eva']['autoevaluacion']['factores']['puntaje'][$i]=$puntaje;
	      //-----------------------
	      //FIN RESULTADOS FACTORES
	      //-----------------------
	      
	    //Obtener datos de las evaluaciones de los supervisores inmediatos del trabajador evaluado
	    $sql="SELECT id_encuesta, id_encuestado, token_ls, estado FROM PERSONA_ENCUESTA WHERE id_unidad='".$_GET['id'];
	    $sql.="' AND periodo='".$_GET['proc']."' AND tipo='evaluador' AND id_evaluado='".$LISTA_EVALUADOS['Eva']['id_evaluado'][$i]."'";
	    $atts= array("id_encuesta", "id_encuestado", "token_ls", "estado");
	    $LISTA_EVALUADORES=obtenerDatos($sql, $conexion, $atts, "Eva");
	    
	    $numero_evaluadores=0;
	    $competencias_total=0;
	    $factores_total=0;
	    
	    for($j=0; $j<$LISTA_EVALUADORES['max_res']; $j++){
	      
	      if($LISTA_EVALUADORES['Eva']['estado'][$j]!='Pendiente' && $LISTA_EVALUADORES['Eva']['estado'][$j]!='En proceso'){
		
		//CALCULO DE RESULTADOS PARA LA SECCIÓN DE COMPETENCIAS
		$sql="SELECT id_pregunta FROM PREGUNTA WHERE id_encuesta='".$LISTA_EVALUADORES['Eva']['id_encuesta'][$j]."' AND seccion='competencia' AND id_pregunta_root_ls IS NOT NULL";
		$atts= array("id_pregunta");
		$LISTA_COMPETENCIAS=obtenerDatos($sql, $conexion, $atts, "Comp");
		
		$puntaje=0; //Inicialización
		for($k=0; $k<$LISTA_COMPETENCIAS['max_res']; $k++){
		  $sql="SELECT respuesta FROM RESPUESTA WHERE id_pregunta='".$LISTA_COMPETENCIAS['Comp']['id_pregunta'][$k]."' AND token_ls='".$LISTA_EVALUADORES['Eva']['token_ls'][$j]."'";
		  $atts= array("respuesta");
		  $aux=obtenerDatos($sql, $conexion, $atts, "Aux");
		  
		  switch($aux['Aux']['respuesta'][0]){
		    case 'Siempre':
		      $puntaje+=3;
		    break;
		    case 'Casi siempre':
		      $puntaje+=2;
		    break;
		    case 'Pocas veces':
		      $puntaje+=1;
		    break;
		  } //Fin del switch 
		}//Cierre del ciclo sobre las preguntas de competencias
		
		$competencias_total+=$puntaje;
		//FIN RESULTADOS COMPETENCIAS
		
		//CALCULO DE RESULTADOS PARA LA SECCIÓN DE FACTORES
		$sql="SELECT id_pregunta FROM PREGUNTA WHERE id_encuesta='".$LISTA_EVALUADORES['Eva']['id_encuesta'][$j]."' AND seccion='factor' AND id_pregunta_root_ls IS NOT NULL";
		$atts= array("id_pregunta");
		$LISTA_FACTORES=obtenerDatos($sql, $conexion, $atts, "Fac");
		
		$puntaje=0; //Inicialización
		for($k=0; $k<$LISTA_FACTORES['max_res']; $k++){
		  $sql="SELECT respuesta FROM RESPUESTA WHERE id_pregunta='".$LISTA_FACTORES['Fac']['id_pregunta'][$k]."' AND token_ls='".$LISTA_EVALUADORES['Eva']['token_ls'][$j]."'";
		  $atts= array("respuesta");
		  $aux=obtenerDatos($sql, $conexion, $atts, "Aux");
		  switch($aux['Aux']['respuesta'][0]){
		    case 'Excelente':
		      $puntaje+=3;
		    break;
		    case 'Sobre lo esperado':
		      $puntaje+=2;
		    break;
		    case 'En lo esperado':
		      $puntaje+=1;
		    break;
		  } //Fin del switch 
		}//Cierre del ciclo sobre las preguntas de competencias
		
		$factores_total+=$puntaje;
		//FIN RESULTADOS FACTORES
		
	      $numero_evaluadores++;
	      } //Fin del condicional (evaluación finalizada)
	      
	    }//Fin del ciclo sobre supervisores inmediatos
	 
	    $competencias_promedio=$competencias_total/$numero_evaluadores;
	    $factores_promedio=$factores_total/$numero_evaluadores;
	    
	    $LISTA_EVALUADOS['Eva']['evaluacion']['competencias']['puntaje'][$i]=$competencias_promedio;
	    $LISTA_EVALUADOS['Eva']['evaluacion']['factores']['puntaje'][$i]=$factores_promedio;
	  }//Fin del ciclo sobre los evaluados 
	  
	  $PROMEDIO_COMPETENCIAS=0;
	  $PROMEDIO_FACTORES=0;
	  for($i=0; $i<$LISTA_EVALUADOS['max_res']; $i++){
	    if($LISTA_EVALUADOS['Eva']['evaluacion']['competencias']['puntaje'][$i]){
	      $PROMEDIO_COMPETENCIAS+=$LISTA_EVALUADOS['Eva']['evaluacion']['competencias']['puntaje'][$i]/$LISTA_EVALUADOS['Eva']['autoevaluacion']['competencias']['maximo'][$i];
	      $PROMEDIO_FACTORES+=$LISTA_EVALUADOS['Eva']['evaluacion']['factores']['puntaje'][$i]/$LISTA_EVALUADOS['Eva']['autoevaluacion']['factores']['maximo'][$i];
	      $trabajadores_evaluados++;
	    }
	  }
	  $PROMEDIO_COMPETENCIAS=$PROMEDIO_COMPETENCIAS/$trabajadores_evaluados;
	  $PROMEDIO_FACTORES=$PROMEDIO_FACTORES/$trabajadores_evaluados;
	  
	break;
	//-----------------------------------------------
	//-----------------------------------------------
	//FIN DE LA MUESTRA DE RESULTADOS PARA UNA UNIDAD
	//-----------------------------------------------
	//-----------------------------------------------
	
	//---------------------------------------
	//---------------------------------------
	//HISTORICO DE RESULTADOS PARA UNA UNIDAD
	//---------------------------------------
	//---------------------------------------
	case 'hist_uni':
	
	  #...code
	
	break;
	//----------------------------------------------
	//----------------------------------------------
	//FIN DE HISTORICO DE RESULTADOS PARA UNA UNIDAD
	//----------------------------------------------
	//----------------------------------------------
	
      } //cierre del switch 
    } //cierre del condicional

    
   

    //Cierre conexión a la BD
    cerrarConexion($conexion);

?> 


