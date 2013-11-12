<?

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



    //////////////////////////////////////////////////// 
    //Convierte fecha de mysql a normal 
    //////////////////////////////////////////////////// 
    function cambiaf_a_normal($fecha) {
        ereg("([0-9]{2,4})-([0-9]{1,2})-([0-9]{1,2})", $fecha, $mifecha);
        $lafecha = $mifecha[3] . "/" . $mifecha[2] . "/" . $mifecha[1];
        return $lafecha;
    }

    //////////////////////////////////////////////////// 
    //Convierte fecha de normal a mysql 
    //////////////////////////////////////////////////// 

    function cambiaf_a_mysql($fecha) {
        ereg("([0-9]{1,2})/([0-9]{1,2})/([0-9]{2,4})", $fecha, $mifecha);
        $lafecha = $mifecha[3] . "-" . $mifecha[2] . "-" . $mifecha[1];
        return $lafecha;
    }

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

//Funciones Gestion de la BD

function insertarUsuario($usbid,$email,$tipo,$conexion) {
    $sql = "INSERT INTO Usuario (usbid,email,tipo) VALUES ( '";
    $sql .= $usbid ."','".$email."','".$tipo."')";
/*    
    if($nombre == null || $apellido == null || $ci == null) {
        $sql = "INSERT INTO usuario (usbid,tipo) VALUES ( '";
        $sql .= $usbid ."','".$tipo . "')";
    }
*/        
   
    $resultado = ejecutarConsulta($sql, $conexion);
}

function insertarSolicitud($usbid,$fecha,$fechaT,$tipo,$asunto,$descrip,$archivo,$estado,$conexion){
    date_default_timezone_set('America/Caracas');
    $sql = "INSERT INTO Solicitud (solicitante,fsolicitud,fsolicitada,fsolicitadaT,tipo,asunto,descripcion,archivo,estado) VALUES ( '";
    $sql .= $usbid."','".date("y/m/d")."','".$fecha."','".$fechaT."','".$tipo."','".$asunto."','".$descrip."','".$archivo."','".$estado."')";
//    print "Inserta Solicitud: $sql <br/>";
    $resultado = ejecutarConsulta($sql, $conexion);
}

    function escaparString($string) {
        return pg_escape_string($string);
    }

function ActualizarEstadistica($tipoE, $tipo, $conexion){
        $sql_temp="SELECT ".$tipo." FROM Estadistica WHERE tipo='".$tipoE."' AND actual = '1'";
        $resultado=ejecutarConsulta($sql_temp, $conexion);
        $fila=obtenerResultados($resultado);
        $n=$fila[$tipo];

        $n++;

	    $sql_temp="UPDATE Estadistica Set ".$tipo."='".$n."' WHERE tipo='".$tipoE."' AND actual = '1'";
        $resultado=ejecutarConsulta($sql_temp, $conexion);
}

function break_line($linea)
{
   $reg = "\'";
   $rep = '\'';
   $linea1 = str_replace($reg, $rep, $linea);
   $reg1 = '\"';
   $rep1 = "\"";
   $linea2 = str_replace($reg1, $rep1, $linea1);
   $reg2 = '\r\n';
   $rep2 = "\n";
       return str_replace($reg2, $rep2, $linea2);

}

function isAdmin() {
    if ($_SESSION['USBID'] == "dace-info" or $_SESSION['USBID'] == "dace-horarios")
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

function fechaDentroTrimestre($title,$body,$start,$end,$fecha, $conexion){
        $sql1 ="SELECT viernes ";
	    $sql1.="FROM Trimestre ";
	    $sql1.="WHERE semana = '13'";  
        $resultado=ejecutarConsulta($sql1, $conexion);
        $i=0;
		while ($fila=obtenerResultados($resultado)){
            $viernes=$fila['viernes'];
			$i++;	                 
        }
        $sql1 ="SELECT lunes ";
	    $sql1.="FROM Trimestre ";
	    $sql1.="WHERE semana = '0'";  
        $resultado=ejecutarConsulta($sql1, $conexion);
        $i=0;
		while ($fila=obtenerResultados($resultado)){
            $lunes=$fila['lunes'];
			$i++;	                 
        }
    if($fecha<$lunes or $fecha>$viernes) 
        return "INSERT INTO Calendar(titulo,descrip,inicio,fin,trim) VALUES ('".$title."','".$body."','".$start."','".$end."','0')";
    else {
$fech = verDiadFecha($fecha, $conexion);
        return "INSERT INTO Calendar(titulo,descrip,inicio,fin,trim,ftrim) VALUES ('".$title."','".$body."','".$start."','".$end."','1','".$fech."')";
}

}

function verDiadFecha($f, $conexion){
    $sql1 ="SELECT * " ;
	$sql1.=" FROM Trimestre ";
	$sql1.="WHERE  lunes = '".$f."' OR martes = '".$f."' OR miercoles = '".$f."' OR jueves = '".$f."' OR viernes = '".$f."'"; 

    $resultado=ejecutarConsulta($sql1, $conexion);
    $i=0;
    while ($fila=obtenerResultados($resultado)){

        if($fila['lunes']==$f)
            $fecha = 'Lunes Semana '.$fila['semana'];
        else if($fila['martes']==$f)
            $fecha = 'Martes Semana '.$fila['semana'];
        else if($fila['miercoles']==$f)
            $fecha = 'Miercoles Semana '.$fila['semana'];
        else if($fila['jueves']==$f)
            $fecha = 'Jueves Semana '.$fila['semana'];
        else
            $fecha = 'Viernes Semana '.$fila['semana'];

	    $i++;	                 
    }

    return $fecha;

}

function verFechadDia($d, $s, $conexion){
    $sql1 ="SELECT ".$d ;
	$sql1.=" FROM Trimestre ";
	$sql1.="WHERE semana = '".$s."'"; 

    $resultado=ejecutarConsulta($sql1, $conexion);
    $i=0;
    while ($fila=obtenerResultados($resultado)){
        $fecha=$fila[$d];
	    $i++;	                 
    }

    return $fecha;
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

	?>
	<strong style="font-size:12px"><?php echo "$_SESSION[USBID]"; if (isAdmin()) echo " |<i> Administrador</i>"; ?></strong>
    <i> <?
    	if ($_SESSION['tipo']=="pregrado" or $_SESSION['tipo']=="postgrado") echo " | Estudiante ";
		if ($_SESSION['tipo']=="profesores") echo " | Miembro USB - Profesor";
		if ($_SESSION['tipo']=="empleados") echo " | Miembro USB - Empleado";
		if ($_SESSION['tipo']=="administrativos") echo " | Miembro USB - Instituci&oacute;n";
		if ($_SESSION['tipo']=="organizaciones") echo " | Organizaci&oacute;n Estudiantil"; ?> </i>

	  <?
	}
}

/*
    obtenerFilas. funcion consulta datos sobre la BD
    $conexion   - conexion a la BD
    $sql        - consulta sobre la BD
    $tabla      - nombre de la tabla
    $atts       - atributos a obtener
*/
function obtenerDatos($sql, $conexion, $atts, $tabla){
    
    $LISTA=array();

    $modo_depuracion=FALSE;
        
    if ($modo_depuracion) 
        echo "$sql<br>";
    else{
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
    }
    
    return $LISTA;
}

function obtenerIds($conexion, $tabla, $persona){

    $sql ="SELECT * ";
    $sql.="FROM ".$tabla;
    
    $FAM_ID=array();

    $modo_depuracion=FALSE;
        

    if ($modo_depuracion) 
        echo "$sql<br>";
    else{
        $resultado=ejecutarConsulta($sql, $conexion);
        $i=0;
        
        while ($fila=obtenerResultados($resultado)){
        
                if ($persona) {
                    $FAM_ID[$fila['id']] = $fila['nombre'].' '.$fila['apellido'];
                }else
                    $FAM_ID[$fila['id']]  = $fila['nombre'];
        }
        $i++;   
    }
    return $FAM_ID;
}

//Otras funciones
function MostrarLegenda($text){

    echo "<legend>".$text."</legend>";

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

