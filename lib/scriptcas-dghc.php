<?php
	session_start();
	$_SESSION[usuario_validado]=1;
	$_SESSION[USBID]="dghc";
	$_SESSION[nombres]="Dirección";
	$_SESSION[apellidos]="de Gestión de Capital Humano"; 
	$_SESSION[cedula]="18445082";
	$_SESSION[tipo]="administrativos";

	$_SESSION[cct]=1;
	if ($_SESSION[usuario_validado]==1){
	  header("Location: cVerificarUsuario.php");
	}
?>