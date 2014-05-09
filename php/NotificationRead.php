<?php
include_once("./db.php");
session_start();  

	if (isset($_GET["z"]))
	{
		#делаем оповещение прочитанным
		mysql_query("UPDATE tblNotification SET boolCheck='1' WHERE intNotificationId='".safe_var($_GET["z"])."' and intRecipientId='".$_SESSION["userId"]."'");
		header("location: ./../alert"); exit();
	}
	
?>