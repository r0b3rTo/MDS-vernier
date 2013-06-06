<?
  ini_set('display_errors', 1);
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Sistema Vernier</title>
        <link rel="shortcut icon" href="img/favicon.ico"> 
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="css/bootstrap-select.min.css">
        <link href="js/datepicker/css/datepicker.css" rel="stylesheet"> 
        <link href="js/BootstrapHelpers/css/bootstrap-formhelpers.css" rel="stylesheet">
        <link rel="stylesheet" href="js/select2/select2.css">
        <link href="assets/css/bootstrap-formhelpers.css" rel="stylesheet">
        <!-- Le HTML5 shim, for IE6-8 support of HTML elements -->
        <!--[if lt IE 9]>
          <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]--> 
        <script>window["_GOOG_TRANS_EXT_VER"] = "1";</script>      
    </head>
    <body>
    <script src="js/jquery-1.8.2.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.validate.js"></script>
    <script src="js/bootstrap-confirm.js" type="text/javascript"></script>
    <script src="js/bootstrap.file-input.js" type="text/javascript"></script>
    <script src="js/bootbox.min.js" type="text/javascript"></script>
    <script src="js/bootstrap-select.min.js" type="text/javascript"></script>
    <script src="js/datepicker/js/bootstrap-datepicker.js" type="text/javascript"></script>
    <script src="js/datepicker/js/locales/bootstrap-datepicker.es.js" type="text/javascript"></script>
    <script src="js/BootstrapHelpers/js/bootstrap-formhelpers-selectbox.js" type="text/javascript"></script>
    <script src="js/select2/select2.js"></script>
    <script src="js/select2/select2_locale_es.js"></script>
    <script src="js/BootstrapHelpers/js/bootstrap-formhelpers-phone.format.js"></script>
    <script src="js/BootstrapHelpers/js/bootstrap-formhelpers-phone.js"></script>
    <script>
        jQuery(function ($) {
            $("a").tooltip()
        });                  
    </script>
    <script>
    $(document).ready(function() {
        $('a[href=#confirm]').click(function(e) {
          //var data = $(this).data('data');
          //var data = $(this).data('data');
          //alert(url+" "+data);
 
          //Link untuk menghapus
          var url = $(this).data('url');
          bootbox.dialog('Esta Seguro de continuar?', [{
                         'label':'No',
                         'class':'btn'
                        },
                        {
                         'label':'Si',
                         'class':'btn',
                         'callback':function() {
                                return location.href = url;
                         }
                        }]);
        });
        $('.selectpicker').selectpicker();
        $('.select2').select2();
        $('.datepicker').datepicker()
    });
 
    </script>
    
    <div class="navbar navbar-fixed-top ">
    <div class="navbar-inner">
      <div class="container">
        <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </a>
       <a class="brand" href="vInicio.php">"Vernier"</a>
       <div class="nav-collapse collapse" id="main-menu">
        <ul class="nav" id="main-menu-left">
          <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">Persona <b class="caret"></b></a>
            <ul class="dropdown-menu" id="swatch-menu">
              <li><a href="vPersona.php">Crear Persona</a></li>
              <li><a href="vListarPersona.php">Listar Persona</a></li>
            </ul>
          </li>
          <li class="dropdown" id="preview-menu">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">Organizaci&oacute;n <b class="caret"></b></a>
            <ul class="dropdown-menu">
              <li><a href="vOrganizacion.php">Crear Organizaci&oacute;n</a></li>
              <li><a href="vListarOrganizacion.php">Listar Organizaci&oacute;n</a></li>
            </ul>
          </li>
          <li class="dropdown" id="preview-menu">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">Cargo/Rol<b class="caret"></b></a>
            <ul class="dropdown-menu">
              <li><a href="vCargo.php">Crear Cargo</a></li>
              <li><a href="vListarCargo.php">Listar Cargo</a></li>
              <li class="divider"></li>
              <li><a href="vRol.php">Crear Rol</a></li>
              <li><a href="vListarRol.php">Listar Rol</a></li>
            </ul>
          </li>
          <li>
            <a href="SubirArchivo.php">Cargar CSV</a>
          </li>
        </ul>
        <ul class="nav pull-right" id="main-menu-right">
          <li><a rel="tooltip" target="_blank">Arturo Murillo</a></li>
          <li><a rel="tooltip" target="_blank" href="#" title="Showcase of Bootstrap sites &amp; apps" onclick="_gaq.push(['_trackEvent', 'click', 'outbound', 'builtwithbootstrap']);">LiveSurveys <i class="icon-share-alt"></i></a></li>
          <li><a rel="tooltip" target="_blank" href="#" title="Marketplace for premium Bootstrap templates" onclick="_gaq.push(['_trackEvent', 'click', 'outbound', 'wrapbootstrap']);">Salir <i class="icon-off"></i></a></li>
        </ul>
       </div>
     </div>
   </div>
 </div>


    <div class="container">  
        <header class="page-header">
            <div class="text-center">
                <img src="img/header.png" class="img-rounded">
            </div>
            <div >
                <h1 class="text-center">Sistema Vernier</h1>
            </div>
        </header>
