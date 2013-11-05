<?
    session_start();

    if (isset($_SESSION['USBID'])){
        include("lib/cAutorizacion.php");
    }

    $Legend = "Inicio";
    include_once("vHeader.php");
?>
<br><br><br>
<div class="well text-center">
    <?

    if (!isset($_SESSION['USBID'])){
        echo "    
    <p>
        <h4>Mediante este sistema se puede hacer seguimiento de la evaluación de personal de la Universidad Simón Bolívar.</h4>
    </p><br><br>

    <p>
        Si eres parte de la Dirección de Gestión de Capital Humano (DGCH) inicia sesión para administrar 
        los datos y encuestas.
    </p><br><br>

    <p>
        Si eres empleado o evaluador inicia sesión para ver las diferentes encuestas que debes realizar.
    </p><br><br><br>";
    echo "<a href='lib/scriptcas-estudiante.php' class='btn btn-info'>Iniciar sesión</a><br><br>";

    } else {

            echo "    
        <p>
            <h4>Mediante este sistema se puede hacer seguimiento de la evaluación de personal de la Universidad Simón Bolívar.</h4>
        </p><br><br>

        <p>
            Como administrador del sistema puedes habilitar las diferentes encuestas que completar&aacute;n los empleados y evaluadores
            que laboran en la Universidad Simón Bolívar.
        </p><br><br>

        <p>
            También puedes agregar otros usuarios como administradores del sistema.
        </p><br><br><br>";

    }
    ?>
</div>

<?
    include_once("vFooter.php");
?>
