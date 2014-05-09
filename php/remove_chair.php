<?php
include_once("db.php");
session_start();  
	
	#доступ у всех кроме студента
	if ($_SESSION["statusId"] != 3 || !isset($_GET["z"])){ header("Location: ./../add_chair?remowe_chair=fail"); exit(); }
	
	# проверяем есть ли такая новость
	mysql_query("DELETE FROM tblChair WHERE intChairId='".safe_var($_GET["z"])."' LIMIT 1");
  	header("Location: ./../add_chair?remove_chair=ok");
	
?>