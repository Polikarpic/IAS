<?php
include_once("db.php");
session_start();  
	
	# проверяем есть ли получатель сообщения
	mysql_query("DELETE FROM tblMessage WHERE intMessageId='".safe_var($_GET["z"])."' and intRecipientId='".$_SESSION["userId"]."' LIMIT 1");
  	header("Location: ./../message?delete=ok");
	
?>