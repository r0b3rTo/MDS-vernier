<?
	
	$ORG_ID = array();

	$a = 0;
	$b = "hola";

	for ($i=0; $i < 5; $i++) { 
		$ORG_ID[$i] = $b;
	}
                            
    while (list($key, $val) = each($ORG_ID))
	{
	  	echo $key."=>".($val)."++";
	}

	echo $ORG_ID[0];

?>

<?php

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
  	echo "Upload: " . $_FILES["file"]["name"] . "<br>";
  	echo "Type: " . $_FILES["file"]["type"] . "<br>";
  	echo "Size: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
  	echo "Stored in: " . $_FILES["file"]["tmp_name"];
}else
  {
  echo "<br> Invalid file, Error: " . $_FILES["file"]["error"] . "<br>";
  }
?>