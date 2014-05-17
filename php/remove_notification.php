<?php
include_once("db.php");
session_start();  
	
	# проверяем есть ли получатель сообщения
	mysql_query("DELETE FROM tblNotification WHERE intNotificationId='".safe_var($_GET["z"])."' and intRecipientId='".$_SESSION["userId"]."' LIMIT 1");
  	
	$_SESSION["um"] = 'i24';
	header("Location: ./../alert");
	
?>