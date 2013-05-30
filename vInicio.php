<?
    session_start();
    $_SESSION['USBID'] = "08-10790";
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

            <p class="text-center"> Selecciona una de las opciones</p>

            <div class="accordion" id="accordion2">
                <div class="accordion-group">
                    <div class="accordion-heading">
                        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseOne">
                            <p class="text-center">Gestionar Organizaci&oacute;n</p>
                        </a>
                    </div>
                    <div id="collapseOne" class="accordion-body collapse">
                        <div class="accordion-inner">
                            <ul class="text-center" >
                                <li><a href="vOrganizacion.php">Nuevo</a>
                                <li><a href="vListarOrganizacion.php">Listar</a>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="accordion-group">
                    <div class="accordion-heading">
                        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseTwo">
                            <p class="text-center">Gestionar Cargo</p>
                        </a>
                    </div>
                    <div id="collapseTwo" class="accordion-body collapse">
                        <div class="accordion-inner">
                            <ul class="text-center" >
                                <li><a href="vCargo.php">Nuevo</a>
                                <li><a href="vListarCargo.php">Listar</a>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="accordion-group">
                    <div class="accordion-heading">
                        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseThree">
                            <p class="text-center">Gestionar Rol</p>
                        </a>
                    </div>
                    <div id="collapseThree" class="accordion-body collapse">
                        <div class="accordion-inner">
                            <ul class="text-center" >
                                <li><a href="vRol.php">Nuevo</a>
                                <li><a href="vListarRol.php">Listar</a>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="accordion-group">
                    <div class="accordion-heading">
                        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseFour">
                            <p class="text-center">Personas Gestionar</p>
                        </a>
                    </div>
                    <div id="collapseFour" class="accordion-body collapse">
                        <div class="accordion-inner">
                            <ul class="text-center" >
                                <li><a href="vPersona.php">Nuevo</a>
                                <li><a href="vListarPersona.php">Listar</a>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
<?
    include_once("vFooter.php");
?>