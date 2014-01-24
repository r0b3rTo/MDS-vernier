<?php
    session_start();
    require "cAutorizacion.php";
    extract($_GET);
    extract($_POST);
    date_default_timezone_set('America/Caracas');
    $_ERRORES = array();
    $_WARNING = array();
    $_SUCCESS = array();
    
      //Buscar las notificaciones no leídas por el administrador
      $sql="SELECT id, tipo, nombre_per, token_ls_per, mensaje FROM NOTIFICACION WHERE revisado=FALSE";
      $atts=array("id", "tipo", "nombre_per", "token_ls_per", "mensaje", "notificacion");
      $LISTA_NOTIFICACIONES=obtenerDatos($sql, $conexion, $atts, "Not");
      
      for($i=0; $i<$LISTA_NOTIFICACIONES['max_res']; $i++){
	switch($LISTA_NOTIFICACIONES['Not']['tipo'][$i]){
	  case '1':
	    $LISTA_NOTIFICACIONES['Not']['notificacion'][$i]="El trabajador ".$LISTA_NOTIFICACIONES['Not']['nombre_per'][$i]." registró su disconformidad con los resultados de su evaluación";
	    break;
	  case '0':
	    //Determinar datos de los involucrados en la evaluación rechazada
	    $sql="SELECT id_evaluado, id_encuestado FROM PERSONA_ENCUESTA WHERE token_ls='".$LISTA_NOTIFICACIONES['Not']['token_ls_per'][$i]."'";
	    $atts=array("id_evaluado", "id_encuestado");
	    $aux=obtenerDatos($sql, $conexion, $atts, "Aux");
	    $sql_1="SELECT nombre, apellido FROM PERSONA WHERE id='".$aux['Aux']['id_evaluado'][0]."'";
	    $sql_2="SELECT nombre, apellido FROM PERSONA WHERE id='".$aux['Aux']['id_encuestado'][0]."'";
	    $atts=array("nombre", "apellido");
	    $aux_1=obtenerDatos($sql_1, $conexion, $atts, "Aux");
	    $aux_2=obtenerDatos($sql_2, $conexion, $atts, "Aux");
	    $nombre_evaluado=$aux_1['Aux']['nombre'][0].' '.$aux_1['Aux']['apellido'][0];
	    $nombre_encuestado=$aux_2['Aux']['nombre'][0].' '.$aux_2['Aux']['apellido'][0];
	    
	    $LISTA_NOTIFICACIONES['Not']['notificacion'][$i]="El supervisor jerárquico <i>".$LISTA_NOTIFICACIONES['Not']['nombre_per'][$i]."</i> rechazó la evaluación del trabajador <i>".$nombre_evaluado."</i> realizada por <i>".$nombre_encuestado."</i>";
	    $LISTA_NOTIFICACIONES['Not']['mensaje'][$i]="No aplica";
	    break;
	  case '2':
	    //Determinar datos de los involucrados en la evaluación aprobada
	    $sql="SELECT id_evaluado, id_encuestado FROM PERSONA_ENCUESTA WHERE token_ls='".$LISTA_NOTIFICACIONES['Not']['token_ls_per'][$i]."'";
	    $atts=array("id_evaluado", "id_encuestado");
	    $aux=obtenerDatos($sql, $conexion, $atts, "Aux");
	    $sql_1="SELECT nombre, apellido FROM PERSONA WHERE id='".$aux['Aux']['id_evaluado'][0]."'";
	    $sql_2="SELECT nombre, apellido FROM PERSONA WHERE id='".$aux['Aux']['id_encuestado'][0]."'";
	    $atts=array("nombre", "apellido");
	    $aux_1=obtenerDatos($sql_1, $conexion, $atts, "Aux");
	    $aux_2=obtenerDatos($sql_2, $conexion, $atts, "Aux");
	    $nombre_evaluado=$aux_1['Aux']['nombre'][0].' '.$aux_1['Aux']['apellido'][0];
	    $nombre_encuestado=$aux_2['Aux']['nombre'][0].' '.$aux_2['Aux']['apellido'][0];
	    
	    $LISTA_NOTIFICACIONES['Not']['notificacion'][$i]="El supervisor jerárquico <i>".$LISTA_NOTIFICACIONES['Not']['nombre_per'][$i]."</i> aprobó la evaluación previamente rechazada del trabajador <i>".$nombre_evaluado."</i> realizada por <i>".$nombre_encuestado."</i>";
	    $LISTA_NOTIFICACIONES['Not']['mensaje'][$i]="No aplica";
	    break;	  
	}
      }
      
      //Buscar las notificaciones leídas por el administrador
      $sql="SELECT id, tipo, nombre_per, token_ls_per, mensaje FROM NOTIFICACION WHERE revisado=TRUE ORDER BY id DESC";
      $atts=array("id", "tipo", "nombre_per", "token_ls_per", "mensaje", "notificacion");
      $HISTORIAL_NOTIFICACIONES=obtenerDatos($sql, $conexion, $atts, "Not");
      
      for($i=0; $i<$HISTORIAL_NOTIFICACIONES['max_res']; $i++){
	switch($HISTORIAL_NOTIFICACIONES['Not']['tipo'][$i]){
	  case '1':
	    $HISTORIAL_NOTIFICACIONES['Not']['notificacion'][$i]="El trabajador ".$HISTORIAL_NOTIFICACIONES['Not']['nombre_per'][$i]." registró su disconformidad con los resultados de su evaluación";
	    break;
	  case '0':
	    //Determinar datos de los involucrados en la evaluación rechazada
	    $sql="SELECT id_evaluado, id_encuestado FROM PERSONA_ENCUESTA WHERE token_ls='".$HISTORIAL_NOTIFICACIONES['Not']['token_ls_per'][$i]."'";
	    $atts=array("id_evaluado", "id_encuestado");
	    $aux=obtenerDatos($sql, $conexion, $atts, "Aux");
	    $sql_1="SELECT nombre, apellido FROM PERSONA WHERE id='".$aux['Aux']['id_evaluado'][0]."'";
	    $sql_2="SELECT nombre, apellido FROM PERSONA WHERE id='".$aux['Aux']['id_encuestado'][0]."'";
	    $atts=array("nombre", "apellido");
	    $aux_1=obtenerDatos($sql_1, $conexion, $atts, "Aux");
	    $aux_2=obtenerDatos($sql_2, $conexion, $atts, "Aux");
	    $nombre_evaluado=$aux_1['Aux']['nombre'][0].' '.$aux_1['Aux']['apellido'][0];
	    $nombre_encuestado=$aux_2['Aux']['nombre'][0].' '.$aux_2['Aux']['apellido'][0];
	    
	    $HISTORIAL_NOTIFICACIONES['Not']['notificacion'][$i]="El supervisor jerárquico <i>".$HISTORIAL_NOTIFICACIONES['Not']['nombre_per'][$i]."</i> rechazó la evaluación del trabajador <i>".$nombre_evaluado."</i> realizada por <i>".$nombre_encuestado."</i>";
	    $HISTORIAL_NOTIFICACIONES['Not']['mensaje'][$i]="No aplica";
	    break;
	  case '2':
	    //Determinar datos de los involucrados en la evaluación aprobada
	    $sql="SELECT id_evaluado, id_encuestado FROM PERSONA_ENCUESTA WHERE token_ls='".$HISTORIAL_NOTIFICACIONES['Not']['token_ls_per'][$i]."'";
	    $atts=array("id_evaluado", "id_encuestado");
	    $aux=obtenerDatos($sql, $conexion, $atts, "Aux");
	    $sql_1="SELECT nombre, apellido FROM PERSONA WHERE id='".$aux['Aux']['id_evaluado'][0]."'";
	    $sql_2="SELECT nombre, apellido FROM PERSONA WHERE id='".$aux['Aux']['id_encuestado'][0]."'";
	    $atts=array("nombre", "apellido");
	    $aux_1=obtenerDatos($sql_1, $conexion, $atts, "Aux");
	    $aux_2=obtenerDatos($sql_2, $conexion, $atts, "Aux");
	    $nombre_evaluado=$aux_1['Aux']['nombre'][0].' '.$aux_1['Aux']['apellido'][0];
	    $nombre_encuestado=$aux_2['Aux']['nombre'][0].' '.$aux_2['Aux']['apellido'][0];
	    
	    $HISTORIAL_NOTIFICACIONES['Not']['notificacion'][$i]="El supervisor jerárquico <i>".$HISTORIAL_NOTIFICACIONES['Not']['nombre_per'][$i]."</i> aprobó la evaluación previamente rechazada del trabajador <i>".$nombre_evaluado."</i> realizada por <i>".$nombre_encuestado."</i>";
	    $HISTORIAL_NOTIFICACIONES['Not']['mensaje'][$i]="No aplica";
	    break;	  
	}
      }
   
    
    if (isset($_GET['action']) && $_GET['action']=='check'){

      $sql="UPDATE NOTIFICACION SET revisado=TRUE WHERE id='".$_GET['id']."'";       
      $resultado=ejecutarConsulta($sql, $conexion);
      header("Location: ../vNotificaciones.php");

    }
    
    //Cierre conexión a la BD
    cerrarConexion($conexion);
  
?> 