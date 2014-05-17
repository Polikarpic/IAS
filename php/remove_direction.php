<?php
include_once("db.php");
session_start();  
	
	#доступ 
	if ($_SESSION["statusId"] != 2 && $_SESSION["statusId"] != 3){ $_SESSION["um"] = 'e0'; header("Location: ./../home"); exit(); }
	
	#проверяем есть ли пользователи такой кафедры
	$query = mysql_query("SELECT intUserId FROM tblUsers WHERE intDirectionId='".safe_var($_GET["z"])."' LIMIT 1");
	if (mysql_num_rows($query) != 0){ $_SESSION["um"] = 'e12'; header("Location: ./../add_direction"); exit(); }	
	
	# проверяем есть ли такая новость
	mysql_query("DELETE FROM tblDirection WHERE intDirectionId='".safe_var($_GET["z"])."' LIMIT 1");
 
	$_SESSION["um"] = 'i21';
	header("Location: ./../add_direction");
	
?>