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
    include_once("vHeader.php");
?>

            <p class="text-center"> Ingresar al sistema seg&uacute;n el tipo de usuario</p>
            <div class="row">
                <div class="span4"></div>
                <div class="span4">
                    <ul>
                        <li><a href="?dace-info=">dace informacion</a>
                        <li><a href="?dace-horarios=">dace horarios</a>
                        <li><a href="?usuario=">usuario</a>
                        <li><a href="?usuario2=">usuario2</a>
                    </ul>
                </div>
                <div class="span4"></div>
            </div> <!-- row -->
<?
    include_once("vFooter.php");
?>