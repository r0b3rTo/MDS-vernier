<?php
    if (isset($_REQUEST['dace-info'])) {
        include_once("lib/scriptcas-daceinfo.php");

        header("Location: vInicio.php");  
    }
    if (isset($_REQUEST['dace-horarios'])) {
        include_once("lib/scriptcas-dacehorario.php");

        header("Location: vInicio.php");  
    }
    if (isset($_REQUEST['usuario'])) {
        include_once("lib/scriptcas-otro.php");

        header("Location: vInicio.php");  
    }
    if (isset($_REQUEST['usuario2'])) {
        include_once("lib/scriptcas-otro2.php");

        header("Location: vInicio.php");  
    }
?>

<!DOCTYPE html>
<html>
    <head>
		<meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"> 
        <title>Sistema de Solicitud - DACE</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
        <link rel="shortcut icon" href="images/favicon.ico"> 
        <link rel="stylesheet" type="text/css" href="css/style.css" />
        <link rel="stylesheet" type="text/css" href="css/tab.css" />
		<script type="text/javascript" src="js/modernizr.custom.04022.js"></script>
		<link href='http://fonts.googleapis.com/css?family=Open+Sans+Condensed:700,300,300italic' rel='stylesheet' type='text/css'>
		<!--[if lt IE 9]>
			<style>
				.content{
					height: auto;
					margin: 0;
				}
				.content div {
					position: relative;
				}
			</style>
		<![endif]-->
    <style type="text/css">
	A:link, A:visited {color:#2F6EB1; text-decoration: none }
    </style>
    </head>
    <body>
        <div class="container">
            
			<header>
                

                <table class="header" width="950" border="0" align="center" cellpadding="0" cellspacing="0">
		            <tr>
			        <td height="184" valign="bottom" style ="background-image:url(images/header.png); background-repeat:no-repeat;">
				        <div align="right">
					    <span class="parrafo"></span>
				        </div>
			        </td>
		            </tr>
	            </table>

				<h2>Bienvenido al Sistema de Solicitudes de la Direccion de Admisi&oacute;n y Contro de Estudios</h2>
			</header>

<br><br><div align="center">
<table cellpadding="20" cellspacing="20" border="0" class="display" id="example">
    <tr>
    <p> Ingresar al sistema seg&uacute;n el tipo de usuario</h4>
    </tr>
    <tr>
    <td>
    <li><a href="?dace-info=">dace informacion</a><br>
    </td>
    </tr>
    <tr>
    <td>
    <li><a href="?dace-horarios=">dace horarios</a><br>
    </td>
    </tr>
    <tr>
    <td>
    <li><a href="?usuario=">usuario</a>
    </td>
    </tr>
        <tr>
    <td>
    <li><a href="?usuario2=">usuario2</a>
    </td>
    </tr>
</table>
</div><br><br><br><br><br>

<?php
include "vFooter.php";
?>
