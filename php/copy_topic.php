<?php


include_once("db.php");
session_start();  

	#доступ только у преподавателя
	if ($_SESSION["statusId"] == 0) { $_SESSION["um"] = 'e0'; header("Location: ./home"); exit(); }
	
	$now_time = date("Y-m-d H:i:s",mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y')));
		
	#получаем информацию о работе
	$query = mysql_query("SELECT * FROM tblWork WHERE intWorkId='".safe_var($_GET["z"])."' LIMIT 1");
	$topic = mysql_fetch_array($query);
	
	#получаем крайние сроки сдачи отчёта и работы
	$query_deadlines = mysql_query("SELECT * FROM tblDeadlines ORDER BY intDeadlinesId DESC LIMIT 1");
	$deadlines = mysql_fetch_array($query_deadlines);
	
	#копируем тему
	mysql_query("INSERT INTO `tblWork` (`intWorkId`, `txtStudentId`, `intTeacherId`, `boolType`, `txtTopic`, `txtComment`, `intAdditionalComment`, `txtNumber_of_persons`, `intDirectionId`, `txtCourse`, `intChairId`,`intDeadlinesId`,`datDate`) VALUES (NULL, NULL, '".$topic["intTeacherId"]."', '".$topic["boolType"]."', '".$topic["txtTopic"]."', '".$topic["txtTopic"]."', NULL, '".$topic["txtNumber_of_persons"]."', '".$topic["intDirectionId"]."', '".$topic["txtCourse"]."','".$_SESSION["chairId"]."','".$deadlines["intDeadlinesId"]."','".$now_time."')");

	$_SESSION["um"] = 'i19';
	header("Location: ./../home"); exit();	
	
?>