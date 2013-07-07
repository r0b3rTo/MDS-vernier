<?
define ("MANEJADOR_BD", "postgres");
define ("NOMBRE_BD", "vernier"); //cambiar si cambia nombre Base de Datos
define ("USER" , "vernier");
define ("PASS" , "Dev!!");

require_once "cFunciones.php";

// Conectar con la base de datos
if (MANEJADOR_BD == "mysql") {
	$conexion = crearConexion("localhost", USER, PASS); //Cambiar si cambia contrasena Base de Datos

}else if(MANEJADOR_BD == "postgres"){
	$conexion = crearConexion("localhost", NOMBRE_BD , USER, PASS); //Cambiar si cambia contrasena Base de Datos
}

/*
 * Evitamos SQL Injection en datos entrantes!
 */
foreach($_POST as $key => $value)
	$_POST[$key] = escaparString($value);

foreach($_GET  as $key => $value)
	$_GET[$key] = escaparString($value);

?>
