<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Sistema Vernier</title>
        <link rel="shortcut icon" href="img/favicon.ico"> 
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="css/bootstrap-select.min.css">
        <link href="css/datepicker.css" rel="stylesheet"> 
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
    <script src="js/bootbox.min.js" type="text/javascript"></script>
    <script src="js/bootstrap-select.min.js" type="text/javascript"></script>
    <script src="js/bootstrap-datepicker.js" type="text/javascript"></script>
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
        $('.datepicker').datepicker()
   });
 
   </script>
    
    <div class="container">  
        <header class="page-header">
            <div class="span12">
                <img src="img/header.png" class="img-rounded">
            </div>
            <div class="span12">
                <h1 class="text-center">Sistema Vernier</h1>
            </div>
            <div class = "span12">
                <a href="vInicio.php">Ir a Inicio</a>
            </div>
        </header>
