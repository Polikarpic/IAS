<?php
include_once("db.php");
session_start();  
	
	
	mysql_query("DELETE FROM tblMessage WHERE intMessageId='".safe_var($_GET["z"])."' and 
	((intRecipientId='".$_SESSION["userId"]."' and boolType='0') or
	(intSenderId='".$_SESSION["userId"]."' and boolType='1')) LIMIT 1");
  	
	$_SESSION["um"] = 'i22';
	header("Location: ./../message");
	
?>