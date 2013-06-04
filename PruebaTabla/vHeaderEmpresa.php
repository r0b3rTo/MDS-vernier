<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es" lang="es">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf8" />
        <title><?php echo (isset($TITLE) ? $TITLE : 'CCTDS - Registro de Empresas'); ?></title>

        <link href="imagenes/estilo.css" rel="stylesheet" type="text/css" />
        <link href="imagenes/menu_ccc.css" rel="stylesheet" type="text/css" />
        <link href="imagenes/info.css" rel="stylesheet" type="text/css" />

        <!--script language="javascript" src="zapatec/utils/zapatec.js" type="text/javascript"></script>
        <script language="javascript" src="zapatec/utils/zpdate.js" type="text/javascript"></script>
        <script language="javascript" src="zapatec/zpcal/src/calendar.js" type="text/javascript"></script>
        <script type="text/javascript" src="zapatec/zpcal/lang/calendar-sp.js"></script-->        
    </head>

    <body>
        <div class="layout">
            <table class="header" width="950" border="0" align="center" cellpadding="0" cellspacing="0">
                <tr>
                    <td height="184" valign="bottom" style ="background-image:url(imagenes/topCCT_3.jpg); background-repeat:no-repeat;">
                        <div align="right">
                            <span class="parrafo"></span>
                        </div>
                    </td>
                </tr>
            </table>

          <ul class="nav">
	          <li>
		          <a href="vInicio.php">Inicio</a>
	          </li>
	          <li>
		          <a>Empresas<span class="flecha">&#9660;</span></a>
		          <ul>
			          <li><a href="vCrearEmpresa.php">Crear</a></li>
			          <li><a href="vListarEmpresas.php">Listar</a></li>
		          </ul>
	          </li>
	          <li>
		          <a>Roles<span class="flecha">&#9660;</span></a>
		          <ul>
			          <li><a href="vCrearUsuario.php">Crear</a></li>
			          <li><a href="vListarUsuarios.php">Listar</a></li>
		          </ul>
	          </li>
                  <li>
                          <a href="salir.php">Cerrar Sesi&oacute;n</a>
                  </li>
          </ul>

        </div>
        <br><br><br>


        <!-- END Header -->
