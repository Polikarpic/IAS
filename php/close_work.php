<?php
 
	include_once("db.php");
	session_start(); 
	
	#доступ
	if ($_SESSION["statusId"] == 0){ $_SESSION["um"] = 'e0'; header("Location: ./../home"); exit(); }
	
	#получаем информацию о работе
	$query = mysql_query("UPDATE tblWork SET intWorkStatus='1' WHERE intWorkId='".safe_var($_GET["z"])."' LIMIT 1");
	$topic = mysql_fetch_array($query);	
	
	$_SESSION["um"] = 'i33';
	header("Location: ./../current_topic?z=".$_GET["z"]."");
	
?>
