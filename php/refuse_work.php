<?php
 
	include_once("db.php");
	session_start(); 
	
	#доступ
	if ($_SESSION["statusId"] != 0){ $_SESSION["um"] = 'e0'; header("Location: ./../home"); exit(); }
	
	$now_time = date("Y-m-d H:i:s",mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y')));
	
	#получаем информацию о работе
	$query = mysql_query("SELECT * FROM tblWork WHERE intWorkId='".safe_var($_GET["z"])."' LIMIT 1");
	$topic = mysql_fetch_array($query);
	
	$students = split_studentId($topic['txtStudentId']);
	
	print_r($students);
	
	#удаляем студента из массива
	$key = array_search($_SESSION["userId"], $students);
	if ($key !== false)
	{
		unset($students[$key]);
	}
	
	if (empty($students)) #если студентов в работе больше нет - работа закрыта, удаляем все документы
	{
		mysql_query("UPDATE tblWork SET txtStudentId=NULL, intStudentsPerformingWork = (intStudentsPerformingWork - 1) WHERE intWorkId='".safe_var($_GET["z"])."' LIMIT 1");
		#получаем список документов для удаления
		$query = mysql_query("SELECT * FROM tblReview WHERE intWorkId='".safe_var($_GET["z"])."'");  	
		#удаляем документы с сервера
		while($data = mysql_fetch_array($query))
		{
			unlink("./../".$data["txtLink"]);
		}
		#удаляем документы
		mysql_query("DELETE FROM tblReview WHERE intWorkId='".safe_var($_GET["z"])."'");
	}
	else
	{
		$max = sizeof($students);
		$list_s = ' ';
		for ($i = 1; $i <= $max; $i++)	$list_s .= $students[$i];
		$list_s .= ' ';	
		mysql_query("UPDATE tblWork SET txtStudentId='".$list_s."', intStudentsPerformingWork = (intStudentsPerformingWork - 1) WHERE intWorkId='".safe_var($_GET["z"])."' LIMIT 1");
	}
	 
	$student = getFullName($_SESSION["userId"]);
	 
	#оповещаем преподавателя
	$alert = mysql_real_escape_string("Студент <a href='profile?z=".$_SESSION["userId"]."'>".$student."</a> отказался от выполнения Вашей работы <b><a href='current_topic?z=".safe_var($_GET["z"])."'>".$topic["txtTopic"]."</a></b>.");
	mysql_query("INSERT INTO `tblNotification` (`intNotificationId`, `intRecipientId`, `txtMessage`, `boolCheck`, `datDate`) VALUES (NULL, '".$topic["intTeacherId"]."', '".$alert."', '0', '".$now_time."')");
		
	
	
	$_SESSION["um"] = 'i26';
	header("Location: ./../home");
	
?>
