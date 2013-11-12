<?php

require_once '../jsonrpcphp/includes/jsonRPCClient.php';

$myExample = new jsonRPCClient('http://localhost/limesurvey/index.php/admin/remotecontrol');

 //receive session key

	$sessionKey= $myExample->get_session_key( 'admin', 'Segundo!!');
	echo 'Se inicio la sesion<br />'."\n";
	echo $sessionKey;
	
//receive session key
	$lista= $myExample->list_surveys( $sessionKey, 'admin');
	echo 'Estos son los surveys obtenidos<br />'."\n";
	print_r($lista, null );


// release the session key

	$sessionKey= $myExample->release_session_key( $sessionKey);
	echo 'Se cerro la sesion con exito<br />'."\n";
	echo $sessionKey;

// return

  //header("Location: ../vLimeSurveyRPC.php?success"); 

?>
                