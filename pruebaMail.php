<?php
$to = "jerilyngoncalves@gmail.com";
$subject = "[Sistema Vernier] Bienvenido";
$message = "Hello! Bienvenido a nuestro sistema.";
$from = "jerilyngoncalves@gmail.com";
$headers = "From:" . $from;
mail($to,$subject,$message,$headers);
echo "Mail Sent.";
?> 