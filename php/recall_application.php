<?php
include_once("db.php");
session_start();  
	
	#меняем статус заявки на "рассмотренную"
	mysql_query("UPDATE tblList_of_applications SET boolStatus='1' WHERE intApplicationsId='".safe_var($_GET["z"])."' LIMIT 1");
 	
	#удаляем оповещение для преподавателя
	mysql_query("DELETE FROM tblNotification WHERE intApplicationsId='".safe_var($_GET["z"])."' LIMIT 1");
 	
	
	$_SESSION["um"] = 'i25';
	header("Location: ./../alert");
	
?>