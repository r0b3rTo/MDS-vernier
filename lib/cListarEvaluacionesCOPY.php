<?php
   //require "cAutorizacion.php";
   //temporal
   require "cConstantes.php";
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
   $id_usuario=$PERSONA['Per']['id'][0];
   
   //Obtención de los evaluados del usuario
   $sql ="SELECT id_per ";
   $sql.="FROM PERSONA_EVALUADOR ";
   $sql.="WHERE id_eva='".$id_usuario."' ";
   $sql.="AND actual='t'";
   
   $atts = array("id_per");
   $LISTA_EVALUADOS= obtenerDatos($sql, $conexion, $atts, "Per_Eva");
   
   //Inclusión de las autoevaluaciones (si existen)
   $LISTA_EVALUADOS["Per_Eva"]["id_per"][$LISTA_EVALUADOS['max_res']] = $id_usuario;
   $LISTA_EVALUADOS['max_res']++;

   //Evaluaciones actuales de la lista de evaluados
   ///////////////////////
   // Obtención del identificador, tipo, estado, periodo y token de Limesurvey de las encuestas del usuario
   
   for($i=0; $i<$LISTA_EVALUADOS['max_res']; $i++){
      $sql ="SELECT id_encuesta_ls, id_evaluado, token_ls, tipo, estado, periodo ";
      $sql.="FROM PERSONA_ENCUESTA ";
      $sql.="WHERE id_encuestado='".$LISTA_EVALUADOS["Per_Eva"]["id_per"][$i]."' ";
      $sql.="AND actual='t'";

      $atts = array("id_encuesta_ls", "id_evaluado", "token_ls", "tipo", "estado", "periodo", "nombre", "apellido");
      $LISTA_EVALUACION_ACTUAL= obtenerDatos($sql, $conexion, $atts, "Enc");
   }

   //Obtención de los nombres de los evaluados
   for ($i=0; $i<$LISTA_EVALUACION_ACTUAL[max_res]; $i++){
      $sql ="SELECT nombre, apellido ";
      $sql.="FROM PERSONA ";
      $sql.="WHERE ";
      $sql.= "id='".$LISTA_EVALUACION_ACTUAL["Enc"]["id_evaluado"][$i]."'";
      $atts = array("nombre", "apellido");
      $NOMBRES= obtenerDatos($sql, $conexion, $atts, "Nom");
      $LISTA_EVALUACION_ACTUAL["Enc"]["nombre"][$i]=$NOMBRES["Nom"]["nombre"][0];
      $LISTA_EVALUACION_ACTUAL["Enc"]["apellido"][$i]=$NOMBRES["Nom"]["apellido"][0];
   }
      
   //Evaluaciones pasadas de la lista de evaluados
   //////////////////////
   // Obtención del identificador, tipo, estado y token de Limesurvey de las encuestas del usuario
   
   for($i=0; $i<$LISTA_EVALUADOS['max_res']; $i++){
      $sql ="SELECT id_encuesta_ls, id_evaluado, token_ls, tipo, estado, periodo ";
      $sql.="FROM PERSONA_ENCUESTA ";
      $sql.="WHERE id_encuestado='".$LISTA_EVALUADOS["Per_Eva"]["id_per"][$i]."' ";
      $sql.="AND actual='f'";

      $atts = array("id_encuesta_ls", "id_evaluado", "token_ls", "tipo", "estado", "periodo", "nombre", "apellido");
      $LISTA_EVALUACION_PASADA= obtenerDatos($sql, $conexion, $atts, "Enc");
   }
      
   //Obtención de los nombres de los evaluados
   for ($i=0; $i<$LISTA_EVALUACION_PASADA['max_res']; $i++){
      $sql ="SELECT nombre, apellido ";
      $sql.="FROM PERSONA ";
      $sql.="WHERE ";
      $sql.= "id='".$LISTA_EVALUACION_PASADA["Enc"]["id_evaluado"][$i]."'";
      $atts = array("nombre", "apellido");
      $NOMBRES= obtenerDatos($sql, $conexion, $atts, "Nom");
      $LISTA_EVALUACION_PASADA["Enc"]["nombre"][$i]=$NOMBRES["Nom"]["nombre"][0];
      $LISTA_EVALUACION_PASADA["Enc"]["apellido"][$i]=$NOMBRES["Nom"]["apellido"][0];
   }
   
   if (isset($_GET['action']) ){
   
      switch ($_GET['action']) {
            case 'viewSupervisor':
               //Obtención de los supervisados del usuario
               $sql ="SELECT id_per ";
               $sql.="FROM PERSONA_SUPERVISOR ";
               $sql.="WHERE id_sup='".$id_usuario."' ";
               $sql.="AND actual='t'";
   
               $atts = array("id_per");
               $LISTA_SUPERVISADOS= obtenerDatos($sql, $conexion, $atts, "Per_Sup");
   
               //Evaluaciones actuales de la lista de supervisados
               ///////////////////////
               // Obtención del identificador, tipo, estado, periodo y token de Limesurvey de las encuestas del usuario
   
               for($i=0; $i<$LISTA_SUPERVISADOS['max_res']; $i++){
                  $sql ="SELECT id_encuesta_ls, id_evaluado, token_ls, tipo, estado, periodo ";
                  $sql.="FROM PERSONA_ENCUESTA ";
                  $sql.="WHERE id_encuestado='".$LISTA_SUPERVISADOS["Per_Sup"]["id_per"][$i]."' ";
                  $sql.="AND actual='t'";

                  $atts = array("id_encuesta_ls", "id_evaluado", "token_ls", "tipo", "estado", "periodo", "nombre", "apellido");
                  $LISTA_SUPERVISION_ACTUAL= obtenerDatos($sql, $conexion, $atts, "Enc");
               }
   
               //Obtención de los nombres de los supervisados
               for ($i=0; $i<$LISTA_SUPERVISION_ACTUAL[max_res]; $i++){
                  $sql ="SELECT nombre, apellido ";
                  $sql.="FROM PERSONA ";
                  $sql.="WHERE ";
                  $sql.= "id='".$LISTA_SUPERVISION_ACTUAL["Enc"]["id_evaluado"][$i]."'";
                  $atts = array("nombre", "apellido");
                  $NOMBRES= obtenerDatos($sql, $conexion, $atts, "Nom");
                  $LISTA_SUPERVISION_ACTUAL["Enc"]["nombre"][$i]=$NOMBRES["Nom"]["nombre"][0];
                  $LISTA_SUPERVISION_ACTUAL["Enc"]["apellido"][$i]=$NOMBRES["Nom"]["apellido"][0];
               }
   
               //Evaluaciones pasadas de los supervisados
               //////////////////////
               // Obtención del identificador, tipo, estado y token de Limesurvey de las encuestas del usuario
   
               for($i=0; $i<$LISTA_SUPERVISADOS['max_res']; $i++){
                  $sql ="SELECT id_encuesta_ls, id_evaluado, token_ls, tipo, estado, periodo ";
                  $sql.="FROM PERSONA_ENCUESTA ";
                  $sql.="WHERE id_encuestado='".$LISTA_SUPERVISADOS["Per_Sup"]["id_per"][$i]."' ";
                  $sql.="AND actual='f'";

                  $atts = array("id_encuesta_ls", "id_evaluado", "token_ls", "tipo", "estado", "periodo", "nombre", "apellido");
                  $LISTA_SUPERVISION_PASADA= obtenerDatos($sql, $conexion, $atts, "Enc");
               }
      
               //Obtención de los nombres de los evaluados
               for ($i=0; $i<$LISTA_SUPERVISION_PASADA['max_res']; $i++){
                  $sql ="SELECT nombre, apellido ";
                  $sql.="FROM PERSONA ";
                  $sql.="WHERE ";
                  $sql.= "id='".$LISTA_SUPERVISION_PASADA["Enc"]["id_evaluado"][$i]."'";
                  $atts = array("nombre", "apellido");
                  $NOMBRES= obtenerDatos($sql, $conexion, $atts, "Nom");
                  $LISTA_SUPERVISION_PASADA["Enc"]["nombre"][$i]=$NOMBRES["Nom"]["nombre"][0];
                  $LISTA_SUPERVISION_PASADA["Enc"]["apellido"][$i]=$NOMBRES["Nom"]["apellido"][0];
               }
               break;
               
            default:
               # code...
               break;
      }
      
   }
      
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
      $resultado=$client_ls->release_session_key($session_key);//Devolver llave de acceso a Limesurvey

      $ip=$_SERVER['REMOTE_ADDR'];
      $fecha_intento=date("d/m/Y.H:i");
      
      if ($completed!='N'){
         $sql ="UPDATE PERSONA_ENCUESTA SET estado='Finalizada', ip='".$ip."', fecha='".$fecha_intento."' ";
         $sql.="WHERE ";
         $sql.= "token_ls='".$token_ls."'";
         $resultado=ejecutarConsulta($sql, $conexion);
         $_SESSION['MSJ'] = "Sus respuestas han sido procesadas. Gracias por realizar la encuesta";
         header("Location: ./vListarEvaluaciones.php?success"); 
      } else {
         $sql ="UPDATE PERSONA_ENCUESTA SET estado='En proceso', ip='".$ip."', fecha='".$fecha_intento."' ";
         $sql.="WHERE ";
         $sql.= "token_ls='".$token_ls."'";
         $resultado=ejecutarConsulta($sql, $conexion);
         $_SESSION['MSJ'] = "Sus respuestas han sido procesadas. Recuerde completar la encuesta antes de finalizar el periodo de evaluación";
         header("Location: ./vListarEvaluaciones.php?warning"); 
      }
   }
   
   cerrarConexion($conexion);
   
?>