<?
    session_start();
    require "cAutorizacion.php";
    use  SimpleExcel\SimpleExcel;
    session_start();
    require "cAutorizacion.php";
    require "SimpleCSV/src/SimpleExcel/SimpleExcel.php";
    extract($_GET);
    extract($_POST);

    $csv_mimetypes = array(
        'text/csv',
        'text/plain',
        'application/csv',
        'text/comma-separated-values',
        'application/excel',
        'application/vnd.ms-excel',
        'application/vnd.msexcel',
        'text/anytext',
        'application/octet-stream',
        'application/txt',
        'text/tsv'
    );

    if (in_array($_FILES['file']['type'], $csv_mimetypes)) {

            if (file_exists("../files/" . $_FILES["file"]["name"])){
              echo $_FILES["file"]["name"] . " already exists. ";
            }
            else{
              move_uploaded_file($_FILES["file"]["tmp_name"],
              "../files/" . $_FILES["file"]["name"]);
              //echo "<br>Stored in: " . "../files/" . $_FILES["file"]["name"];
            }

              $file = "../files/" . $_FILES["file"]["name"];

              $excel = new SimpleExcel('CSV');
              $excel->parser->loadFile($file);  // Load CSV file

        switch ($_POST['BD']) {
          case 'Per':
            $BD = "PERSONA";

            if ($tam = sizeof($excel->parser->getRow(1)) > 1){
              $i = 2;
              $columna = sizeof($excel->parser->getColumn(1));
              while ($i <= $columna){
                $fila = $excel->parser->getRow($i);        

                $direccion = $fila[43]." , ".$fila[44]." , ".$fila[45]." , ".$fila[46];
                $unidad = $fila[51];

                $sql="INSERT INTO PERSONA (nombre, apellido, cedula, sexo, fecha_nac, unidad, direccion, telefono, email) VALUES(".
                "'$fila[9]', ".  //id organizacion              
                "'$fila[8]', ".  //id familia de cargo
                "'$fila[7]', ".  //codigo cargo
                "'$fila[10]', ". //nombre cargo
                "'$fila[19]', ". //nombre cargo
                "'$unidad', ". //nombre cargo
                "'$direccion', ". //nombre cargo
                "'', ". //clave para la organizacion                
                "'' ". //descripcion
                ")";

                $i++;
                $resultado=ejecutarConsulta($sql, $conexion);
              }
            }

            //echo $sql;
            break;
          case 'Org':
            $BD = "ORGANIZACION";         
            if ($tam = sizeof($excel->parser->getRow(1)) > 1){
              $i = 2;
              $columna = sizeof($excel->parser->getColumn(1));
              while ($i <= $columna){
                $fila = $excel->parser->getRow($i);

                $codigo = $fila[0].$fila[1].$fila[2];

                $sql="INSERT INTO ORGANIZACION (idsup, nombre, codigo, descripcion, observacion) VALUES(".
                "'0', ".  //id organizacion              
                "'$fila[3]', ".  //nombre de la organizacion
                "'$codigo', ".  //codigo de la organizacion
                "'', ".  //descripcion de la organizacion
                "'' ".  //observacion de la organizacion
                ")";

                $i++;
                $resultado=ejecutarConsulta($sql, $conexion);
              }

            }

            break;
          case 'Car':
            $BD = "CARGO";              
            if ($tam = sizeof($excel->parser->getRow(1)) > 1){
              $i = 2;
              $columna = sizeof($excel->parser->getColumn(1));
              while ($i <= $columna){
                $fila = $excel->parser->getRow($i);

                if ($fila[4] == "x") {
                  $familia = "1";
                }else if ($fila[5] == "x") {
                  $familia = "2";
                }else if ($fila[6] == "x") {
                  $familia = "3";
                }else if ($fila[7] == "x") {
                  $familia = "4";
                }else if ($fila[8] == "x") {
                  $familia = "5";
                }else {
                  $familia = "0";
                }

                $sql="INSERT INTO CARGO (id_org, id_fam, codigo, codtno, codgra, nombre, clave, descripcion, funciones) VALUES(".
                "'0', ".  //id organizacion              
                "$familia, ".  //id familia de cargo
                "'$fila[1]', ".  //codigo cargo
                "'$fila[3]', ". //nombre cargo
                "'$fila[0]', ". //nombre cargo
                "'$fila[2]', ". //nombre cargo
                "'f', ". //clave para la organizacion                
                "'', ". //descripcion
                "'' ".   //funciones
                ")";

                $i++;
                $resultado=ejecutarConsulta($sql, $conexion);
              }

            }

            break;
          case 'Rol':
            $BD = "ROL";
            break;
          default:
            header("Location: ../SubirArchivo.php?error"); 
            break;
        }
        cerrarConexion($conexion);
        unlink($file);
        echo "<br>BASE DE DATOS ".$BD."<br>";

    }else{
      $_SESSION['MSJ'] = "Tipo de archivo incorrecto";
      header("Location: ../SubirArchivo.php?error"); 
    }

    $_SESSION['MSJ'] = "Los datos fueron registrados";
    header("Location: ../SubirArchivo.php?success"); 

?>