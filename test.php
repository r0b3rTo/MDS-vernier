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
?>