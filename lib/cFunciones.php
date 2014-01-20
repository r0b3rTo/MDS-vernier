<?

/*----------------------------------------------------------
------------ Funciones para la gestión de la BD ------------ 
------------------------------------------------------------*/

/*--------- MySQL ---------*/
if (MANEJADOR_BD == "mysql") {

    function crearConexion($servidor, $usuario, $contrasena) {
        $conexion = mysql_connect($servidor, $usuario, $contrasena) or die("No se pudo conectar al servidor" . mysql_error());
        return $conexion;
    }

    function cerrarConexion($conexion) {
        mysql_close($conexion);
    }

    function numResultados($resultado) {
        return mysql_num_rows($resultado);
    }

    function ejecutarConsulta($consulta, $conexion) {
        mysql_select_db(NOMBRE_BD) or die("No se pudo seleccionar la BD " . mysql_error());
        $resultado = mysql_query($consulta) or die("No se pudo ejecutar la consulta $consulta <br>" . mysql_error());
        return $resultado;
    }
   
    function obtenerResultados($resultado) {
        return mysql_fetch_array($resultado, MYSQL_ASSOC);
    }



    /*---------------------------------------------------------------
    ------ Convierte fecha del formato mysql al formato normal ------
    -----------------------------------------------------------------*/
    function cambiaf_a_normal($fecha) {
        ereg("([0-9]{2,4})-([0-9]{1,2})-([0-9]{1,2})", $fecha, $mifecha);
        $lafecha = $mifecha[3] . "/" . $mifecha[2] . "/" . $mifecha[1];
        return $lafecha;
    }

    /*-----------------------------------------------------------------
    ------- Convierte fecha del formato normal al formato mysql -------
    -------------------------------------------------------------------*/

    function cambiaf_a_mysql($fecha) {
        ereg("([0-9]{1,2})/([0-9]{1,2})/([0-9]{2,4})", $fecha, $mifecha);
        $lafecha = $mifecha[3] . "-" . $mifecha[2] . "-" . $mifecha[1];
        return $lafecha;
    }
    
/*--------- PostgreSQL ---------*/
}else if (MANEJADOR_BD == "postgres"){
    function crearConexion($servidor, $bd, $user, $contrasena) {
        $conexion = pg_connect("host=".$servidor." dbname=".$bd." user=".$user." password=".$contrasena) or die("No se pudo conectar al servidor" . pg_last_error());
        return $conexion;
    }

    function cerrarConexion($conexion) {
        pg_close($conexion);
    }

    function numResultados($resultado) {
        return pg_num_rows($resultado);
    }

    function ejecutarConsulta($consulta, $conexion) {
        $resultado = pg_query($consulta) or die("No se pudo ejecutar la consulta $consulta <br>" . pg_last_error());
        return $resultado;
    }
   
    function obtenerResultados($resultado) {
        return pg_fetch_array($resultado);
    }

    function liberarResultado($resultado){
        return pg_free_result($result);
    }
}


function escaparString($string) {
    return pg_escape_string($string);
}

/*
    obtenerDatos. funcion para la consulta de datos sobre la BD
    $conexion   - conexion a la BD
    $sql        - consulta sobre la BD
    $tabla      - nombre de la tabla a consultar
    $atts       - atributos (columnas) a obtener
*/
function obtenerDatos($sql, $conexion, $atts, $tabla){
    
    $LISTA=array();
    $resultado=ejecutarConsulta($sql, $conexion);
    $i=0;
    
    while ($fila=obtenerResultados($resultado)){
	$n = count($atts);
	$j = 0;
	while ($j < $n){
	    $LISTA[$tabla][$atts[$j]][$i]=$fila[$atts[$j]];  
	    $j++;
	}
	$i++;
    }
    
    $LISTA['max_res']=$i;
    return $LISTA;
}

/*
    obtenerIds. funcion para la obtención de los identificadores
		de los elementos de una tabla de la BD
    $conexion   - conexion a la BD
    $tabla      - nombre de la tabla a consultar
    $persona    - boolean
*/
function obtenerIds($conexion, $tabla, $persona){

    $sql ="SELECT * ";
    $sql.="FROM ".$tabla;
    $FAM_ID=array();
    $resultado=ejecutarConsulta($sql, $conexion);
    $i=0;
    
    while ($fila=obtenerResultados($resultado)){
    
      if ($persona) {
	  $FAM_ID[$fila['id']] = $fila['nombre'].' '.$fila['apellido'];
      } else
	  $FAM_ID[$fila['id']]  = $fila['nombre'];
    }
    $i++;   
    
    return $FAM_ID;
}

/*---------------------------------------------------
------------ Fin del bloque de funciones ------------ 
-----------------------------------------------------*/

/*-----------------------------------------------------------------------
------------ Funciones para la gestión de usuario registrado ------------ 
-------------------------------------------------------------------------*/

function isAdmin() {
    if ($_SESSION['USBID'] == "dgch" or $_SESSION['USBID'] == "evaluaciones")
        return true;
    else
        return false;
}

function isEmpleado() {
    if ($_SESSION[tipo] == "empleados")
        return true;
    else
        return false;
}

function isAsistente() {
    if ($_SESSION[ROL] == "asistente")
        return true;
    else
        return false;
}

function isSecretariaAtenEstudiante() {
    if ($_SESSION[ROL] == "secretaria_atencion_estudiante")
        return true;
    else
        return false;
}

function isSecretariaAtenProfesor() {
    if ($_SESSION[ROL] == "secretaria_atencion_profesor")
        return true;
    else
        return false;
}

function isEstudiante() {
    if ($_SESSION['tipo'] == "pregrado" or $_SESSION['tipo'] == "postgrado")
        return true;
    else
        return false;
}

function isProfesor() {
    if ($_SESSION['tipo'] == "profesores")
        return true;
    else
        return false;
}

function mostrarDatosUsuario(){
	if (isset($_SESSION['USBID'])){
        include_once('CAS.php');
	/*
        phpCAS::setDebug();
        // inicializa sesion phpCAS
        phpCAS::client(CAS_VERSION_2_0,'secure.dst.usb.ve',443,'');
        phpCAS::setNoCasServerValidation();
        // Forza la autenticacion CAS
        phpCAS::forceAuthentication();
	*/
        // Para cerrar la cesion
        if (isset($_REQUEST['logout'])) {
            $_SESSION=array();
	    session_unset();
	    session_destroy();

	    $parametros_cookies = session_get_cookie_params();
	    setcookie(session_name(),0,1,$parametros_cookies["path"]);
            phpCAS::logout();
        }
	?><strong style="font-size:12px"><?php echo "$_SESSION[USBID]"; if (isAdmin()) echo " |<i> Administrador</i>"; ?></strong>
	  <strong style="font-size: 12px"> <?
	  if ($_SESSION['tipo']=="pregrado" or $_SESSION['tipo']=="postgrado") echo " |<i> Estudiante</i> ";
	    if ($_SESSION['tipo']=="profesores") echo " |<i> Miembro USB - Profesor</i>";
	    if ($_SESSION['tipo']=="empleados") echo " |<i> Miembro USB - Empleado</i>";
	    if ($_SESSION['tipo']=="administrativos") echo " |<i> Miembro USB - Instituci&oacute;n</i>";
	    if ($_SESSION['tipo']=="organizaciones") echo " |<i> Organizaci&oacute;n Estudiantil</i>"; ?> 
	</strong><?
	}
}

/*---------------------------------------------------
------------ Fin del bloque de funciones ------------ 
----------------------------------------------------*/

/*---------------------------------------
------------ Otras funciones ------------ 
---------------------------------------*/

function MostrarLegenda($text){
    echo "<legend>".$text."</legend>";
}

/*
    obtenerDiferenciaDias. Determina la diferencia en días entre dos fechas
    -----------------------------------------------------------------------
    $fecha_1 - fecha inicial (dd-mm-yyyy)
    $fecha_2 - fecha final (dd-mm-yyyy)
*/
function obtenerDiferenciaDias ($fecha_1, $fecha_2){
    //Días de la primera fecha
    $aux=explode("-", $fecha_1);
    $fecha_1_dias=$aux[0]+($aux[1]*30)+($aux[2]*365);
    //Días de la segunda fecha
    $aux=explode("-", $fecha_2);
    $fecha_2_dias=$aux[0]+($aux[1]*30)+($aux[2]*365);
    return ($fecha_1_dias-$fecha_2_dias);
}

/*
    isSupervisor. Determina si el identificador suministrado pertenece o no a un
    a un usuario que es supervisor jerárquico en el sistema 
    ---------------------------------------------------------------------------
    $conexion - conexión a la base de datos
*/
function isSupervisor ($conexion){
  if(!isAdmin()){
    $sql= "SELECT id FROM PERSONA WHERE cedula='".$_SESSION['cedula']."'";
    $resultado=ejecutarConsulta($sql, $conexion);
    $resultado=obtenerResultados($resultado);
    $id_usuario=$resultado[0];
    
    $sql= "SELECT * FROM PERSONA_SUPERVISOR WHERE id_sup='".$id_usuario."'";
    $resultado=ejecutarConsulta($sql, $conexion);
    $resultado=obtenerResultados($resultado);
    if (is_array($resultado)){
      return true;
    } else {
      return false;
    }
  }
  else {
      return false;
  }
}

/*
    countNotifications. Determina el número de notificaciones al administrador
    del sistema que aún no se han revisado
    ---------------------------------------------------------------------------
    $conexion - conexión a la base de datos
*/
function countNotifications ($conexion){
    $sql= "SELECT id FROM NOTIFICACION WHERE revisado=FALSE";
    $atts= array("id");
    $resultado=obtenerDatos($sql, $conexion, $atts, 'Res');
    return $resultado['max_res'];
}

/*
    isEvaluador. Determina si el identificador suministrado pertenece o no a un
    a un usuario que es evaluador (supervisor inmediato) en el sistema
    ---------------------------------------------------------------------------
    $conexion - conexión a la base de datos

function isEvaluador ($ci_usuario, $conexion){

    $sql= "SELECT id FROM PERSONA WHERE cedula='".$_SESSION['cedula']."'";
    $atts=array('id');
    $resultado=obtenerDatos($sql, $conexion, $atts, 'Res');
    $id_usuario=$resultado['Res']['id'][0];
 
    $sql= "SELECT * FROM PERSONA_EVALUADOR WHERE id_eva='".$id_usuario."'";
    $resultado=ejecutarConsulta($sql, $conexion);
    $resultado=obtenerResultados($resultado);
    if (is_array($resultado)){
      return true;
    } else {
      return false;
    }
}
*/

/*
    determinarPeriodo. Transforma una fecha de la forma mm/yyyy a su forma textual
    ------------------------------------------------------------------------------
    $month - mes del año "mm"
    $year - año "yyyy"
*/
function determinarPeriodo ($month, $year){
  switch ($month){
    case '01':
      $periodo="Enero ".$year;
      break;
    case '02':
      $periodo="Febrero ".$year;
      break;
    case '03':
      $periodo="Marzo ".$year;
      break;
    case '04':
      $periodo="Abril ".$year;
      break;
    case '05':
      $periodo="Mayo ".$year;
      break;
    case '06':
      $periodo="Junio ".$year;
      break;
    case '07':
      $periodo="Julio ".$year;
      break;
    case '08':
      $periodo="Agosto ".$year;
      break;
    case '09':
      $periodo="Septiembre ".$year;
      break;
    case '10':
      $periodo="Octubre ".$year;
      break;
    case '11':
      $periodo="Noviembre ".$year;
      break;
    case '12':
      $periodo="Diciembre ".$year;
      break;
  }
  return $periodo;
}


/*
function handleError($errno, $errstr, $errfile, $errline, array $errcontext)
{
    // error was suppressed with the @-operator
    if (0 === error_reporting()) {
        return false;
    }

    throw new ErrorException($errstr, $errno, 0, $errfile, $errline);
}

set_error_handler('handleError');
*/

?> 

