<?php
include_once("./db.php");
session_start();  

	
	if (isset($_GET["z"]))
	{
		#делаем сообщение прочитанным
		mysql_query("UPDATE tblMessage SET boolCheck='1' WHERE intMessageId='".safe_var($_GET["z"])."' and intRecipientId='".$_SESSION["userId"]."'");
		header("location: ./../message"); exit();
	}
	
?>