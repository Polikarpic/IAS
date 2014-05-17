<?php
include_once("db.php");
session_start();  
	
	#доступ у всех кроме студента
	if ($_SESSION["statusId"] == 0){ $_SESSION["um"] = 'e0'; header("Location: ./../news"); exit(); }
	
	# проверяем есть ли такая новость
	mysql_query("DELETE FROM tblNews WHERE intNewsId='".safe_var($_GET["z"])."' LIMIT 1");
  	
	$_SESSION["um"] = 'i23';
	header("Location: ./../news");
	
?>