<?php

include_once("db.php");
session_start();  

	$to      = 'iaspetrsu@gmail.com';
	$subject = getFullName($_SESSION["userId"]);
	$message = $_POST["message"];
	$headers = 'From: mail.ias-petrsu.ru' . "\r\n" .
    'Reply-To: '.$_POST["email"].'' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();

	mail($to, $subject, $message, $headers);
	
	$_SESSION["um"] = 'i7';
	header("Location: ./../home"); 
	
?>