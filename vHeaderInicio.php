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
        <link href="css/user.css" rel="stylesheet">
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
    
    
    <?
  if (isset($_SESSION['USBID'])){
    echo "
    <div class='navbar navbar-fixed-top '>
    <div class='navbar-inner'>
      <div class='container'>
        <a class='btn btn-navbar' data-toggle='collapse' data-target='.nav-collapse'>
          <span class='icon-bar'></span>
          <span class='icon-bar'></span>
          <span class='icon-bar'></span>
        </a>
       <a class='brand' href='index.php'>Sistema de Evaluación</a>
       <div class='nav-collapse collapse' id='main-menu'>
        <ul class='nav pull-right' id='main-menu-right'>
          <li><a class='dropdown-toggle' data-toggle='dropdown' href='#'>";mostrarDatosUsuario(); echo "<b class='caret'></b></a>
            <ul class='dropdown-menu'>
              <li><a rel='tooltip' target='_blank' href='#' title='Ir a LiveSurveys' onclick='_gaq.push(['_trackEvent', 'click', 'outbound', 'builtwithbootstrap']);'>LiveSurveys <i class='icon-share-alt'></i></a></li>
              <li class='divider'></li>
              <li><a rel='tooltip' href='salir.php' title='Cerrar Sesi&oacute;n'>Salir <i class='icon-off'></i></a></li>
            </ul>
          </li>
        </ul>
       </div>
     </div>
   </div>
 </div>
 ";
}
?>

    <div class="container">  
      <div class="text-center">
        <p>
        <br><br><img src="img/header.png" width="800">
        </p>
        <h1>Sistema de Evaluación</h1>
      </div>
    </div>
  