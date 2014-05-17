<?php
include_once("db.php");
session_start();  
	
	#доступ у всех кроме студента
	if ($_SESSION["statusId"] == 0){ $_SESSION["um"] = 'e0'; header("Location: ./../home"); exit(); }
	
	mysql_query("DELETE FROM tblUsers WHERE intUserId='".safe_var($_GET["z"])."' LIMIT 1");
  	
	$_SESSION["um"] = 'i31';
	header("Location: ./../edit_user");
	
?>