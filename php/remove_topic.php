<?php
include_once("db.php");
session_start();  
	
	#доступ только у преподавателя
	if ($_SESSION["statusId"] != 1){ header("Location: ./home.php"); exit(); }
	
	
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
	
	#возврат на главную страницу
	header("Location: ./../home?remove_topic=ok");
	
?>