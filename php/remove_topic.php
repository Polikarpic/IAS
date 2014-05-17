<?php
include_once("db.php");
session_start();  
	
	#доступ
	if ($_SESSION["statusId"] == 0){ $_SESSION["um"] = 'e0'; header("Location: ./home"); exit(); }
		
	#удаляем тему
	mysql_query("DELETE FROM tblWork WHERE intWorkId='".safe_var($_GET["z"])."' LIMIT 1");
  	
	#удаляем оценки
	mysql_query("DELETE FROM tblMark WHERE intWorkId='".safe_var($_GET["z"])."'");
  	
	#получаем список документов для удаления
	$query = mysql_query("SELECT * FROM tblReview WHERE intWorkId='".safe_var($_GET["z"])."'");
  	
	#удаляем документы с сервера
	while($data = mysql_fetch_array($query))
	{
	    unlink("./../".$data["txtLink"]);
	}
	
	#удаляем документы
	mysql_query("DELETE FROM tblReview WHERE intWorkId='".safe_var($_GET["z"])."'");
	
	$_SESSION["um"] = 'i4';
	header("Location: ./../home");
	
?>