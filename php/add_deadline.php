<?php


include_once("db.php");
session_start();  

	#доступ 
	if ($_SESSION["statusId"] != 2 && $_SESSION["statusId"] != 3){ $_SESSION["um"] = 'e0'; header("Location: ./../home"); exit(); }
	
	$deadline1 = safe_var($_POST["deadline1"]);
	$deadline2 = safe_var($_POST["deadline2"]);
	$deadline3 = safe_var($_POST["deadline3"]);
	$deadline4 = safe_var($_POST["deadline4"]);
	
	#получаем крайние сроки сдачи отчёта и работы
	$query_deadlines = mysql_query("SELECT * FROM tblDeadlines ORDER BY intDeadlinesId DESC LIMIT 1");
	$deadlines = mysql_fetch_array($query_deadlines);
		
	#закрываем текущие работы(перемещаем в архив)
	mysql_query("UPDATE tblWork SET intWorkStatus='1'  WHERE intWorkStatus='0' and txtStudentId is not null");
		
	#добавляем новые крайние сроки сдачи
	mysql_query("INSERT INTO tblDeadlines (`intDeadlinesId`, `dateRentingReport`, `dateRentingWork`,`dateRentingReport1`, `dateRentingWork1`) VALUES (NULL, '$deadline1', '$deadline2', '$deadline3', '$deadline4')");
	
	#получаем крайние сроки сдачи отчёта и работы
	$query_deadlines = mysql_query("SELECT * FROM tblDeadlines ORDER BY intDeadlinesId DESC LIMIT 1");
	$deadlines = mysql_fetch_array($query_deadlines);
	
	#меняем дедлайн у тем работ
	mysql_query("UPDATE tblWork SET intDeadlinesId='".$deadlines["intDeadlinesId"]."' WHERE intWorkStatus='0' and txtStudentId is null");
	
	#меняем статус всех заявок
	mysql_query("UPDATE tblList_of_applications SET boolStatus ='1' WHERE boolStatus='0'");
	
	#удаляем оповещения о заявках у преподавателей
	mysql_query("DELETE FROM tblNotification WHERE intWorkId is not null");

		
	$_SESSION["um"] = 'i9';
	header("Location: ./../add_deadline");
	
?>