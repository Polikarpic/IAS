<?php


include_once("db.php");
session_start();  

	#������ ������ � �������������
	if ($_SESSION["statusId"] == 0){ header("Location: ./../home"); exit(); }
	
	$news = safe_var($_POST['news']);
	$now_time = date("Y-m-d H:i:s",mktime(date('H') + 4,date('i'),date('s'),date('m'),date('d'),date('Y'))); 
	
	#��������� �������
	mysql_query("INSERT INTO `tblNews` (`intNewsId`, `txtText`, `intSenderId`, `datDate`) VALUES (NULL, '".$news."', '".$_SESSION["userId"]."', '".$now_time."')");
	header("Location: ./../news?add_news=ok");
	
?>