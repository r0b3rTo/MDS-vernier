<?
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
        <meta charset="utf-8">
        <title>Sistema de Solicitud - DACE</title>
        <link rel="shortcut icon" href="img/favicon.ico"> 
        <link rel="stylesheet" href="css/bootstrap.min.css">
    </head>
    <body>
    <script src="js/jquery-1.8.2.min.js"></script>
    <script src="js/bootstrap.min.js"></script>

    <div class="container">
         
        <div class="hero-unit">
            <h1>Awesome responsive layout</h1>
            <p>Hello guys i am a ".hero-unit" and you can use me if you wanna say something important.</p>
            <p><a class="btn btn-primary btn-large">Super important &raquo;</a></p>
        </div><!-- .hero-unit -->
        
        <div class="row">
            <div class="span4">
                <h2>Box Number 1</h2>
                <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
                <p><a class="btn" href="#">Click meeee &raquo;</a></p>
            </div><!-- .span4 -->
   
            <div class="span4">
                <h2>Box Number 2</h2>
                <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
                <p><a class="btn" href="#">Click meeee &raquo;</a></p>
            </div><!-- .span4 -->
   
            <div class="span4">
                <h2>Box Number 3</h2>
                <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
                <p><a class="btn" href="#">Click meeee &raquo;</a></p>
            </div><!-- .span4 -->
   
        </div><!-- .row -->
    </div><!-- .container -->
           
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
