<?
    session_start();
    require "cAutorizacion.php";
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

        switch ($_POST['BD']) {
          case 'Per':
            $BD = "PERSONA";
            break;
          case 'Org':
            $BD = "ORGANIZACION";
            break;
          case 'Car':
            $BD = "CARGO";
            break;
          case 'Rol':
            $BD = "ROL";
            break;
          default:
            header("Location: ../SubirArchivo.php?error"); 
            break;
        }

        echo "BASE DE DATOS ".$BD."<br>";

      	echo "Upload: " . $_FILES["file"]["name"] . "<br>";
      	echo "Type: " . $_FILES["file"]["type"] . "<br>";
      	echo "Size: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
      	echo "Stored in: " . $_FILES["file"]["tmp_name"];
    }else{
      //header("Location: ../SubirArchivo.php?error"); 
    }
?>