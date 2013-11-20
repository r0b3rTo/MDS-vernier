<?php
    require "./lib/cConstantes.php";
    date_default_timezone_set('America/Caracas');
    extract($_GET);

    if (isset($_GET['action'])) {
        switch ($_GET['action']) {
            case 'activar':
		$fecha=date("d-m-Y.h:i:s");
                $sql="INSERT INTO PRUEBA (fecha, registro) VALUES ('".$fecha."','Activacion_tipo2')";
                $resultado=ejecutarConsulta($sql,$conexion);
                break;
            case 'desactivar':
                $fecha=date("d-m-Y.h:i:s");
                $sql="INSERT INTO PRUEBA (fecha, registro) VALUES ('".$fecha."','Desactivacion_tipo2')";
                $resultado=ejecutarConsulta($sql,$conexion);
                break;
            default:
                break;
        }
    }
    cerrarConexion($conexion);
?>
