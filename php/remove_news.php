<?php
include_once("db.php");
session_start();  
	
	#доступ у всех кроме студента
	if ($_SESSION["statusId"] == 0 || !isset($_GET["z"])){ header("Location: ./../news.php?remowe_news=fail"); exit(); }
	
	# проверяем есть ли такая новость
	mysql_query("DELETE FROM tblNews WHERE intNewsId='".safe_var($_GET["z"])."' LIMIT 1");
  	header("Location: ./../news?remowe_news=ok");
	
?>