<?php


include_once("./db.php");
session_start();  
	
	#заявки может отправлять только студент
	if ($_SESSION["statusId"] != 0){ $_SESSION["um"] = 'e0'; header("Location: ./../home"); exit(); }
	
	$student = getFullName($_SESSION["userId"]);
	$now_time = date("Y-m-d H:i:s",mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y')));
	
	#получаем данные о работе на которую подаётся заявка
	$query = mysql_query("SELECT txtTopic, intTeacherId, txtStudentId FROM tblWork WHERE intWorkId='".safe_var($_GET["z"])."' LIMIT 1");
	$topic = mysql_fetch_array($query);	
	
	#проверяем не выполняет ли студент уже какую-то работу
	$query_already = mysql_query("SELECT * FROM tblWork WHERE txtStudentId LIKE '% ".$_SESSION["userId"]." %' and intWorkStatus='0' LIMIT 1");
		
	if (mysql_num_rows($query_already) != 0) { $_SESSION["um"] = 'e2'; header("Location: ./../home"); exit();}
	
	#проверяем не подавал ли студент уже заявку на эту работу
	$check = mysql_query("SELECT * FROM tblList_of_applications WHERE intStudentId='".$_SESSION["userId"]."' and intWorkId='".safe_var($_GET["z"])."' and boolStatus='0' LIMIT 1");
	
	if (mysql_num_rows($check) != 0){ $_SESSION["um"] = 'e1'; header("location: ./../home"); exit(); }  
		
		
	#проверяем не подавал ли студент уже заявки на 5 других работ
	$check = mysql_query("SELECT * FROM tblList_of_applications WHERE intStudentId='".$_SESSION["userId"]."' and boolStatus='0' LIMIT 5");	
	if (mysql_num_rows($check) >= 5){ header("location: ./../home?apply=fail&reason=max_work"); exit(); }  
	
	
	#заявка на выполнение работы
	mysql_query("INSERT INTO `tblList_of_applications` (`intApplicationsId`, `intStudentId`, `intTeacherId`, `intWorkId`, `datDate`, `txtTopic`) VALUES (NULL, '".$_SESSION["userId"]."', '".$topic["intTeacherId"]."', '".safe_var($_GET["z"])."', '".$now_time."', '".$topic["txtTopic"]."')");
	
	#номер последней заявки
	$max = mysql_query("SELECT MAX(intApplicationsId) as max FROM tblList_of_applications");
	$max = mysql_fetch_array($max);    
    	
	#номер нового оповещения
	$nmax = mysql_query("SELECT MAX(intNotificationId) as nmax FROM tblNotification");
	$nmax = mysql_fetch_array($nmax); 
    $nmax["nmax"]++;	

	#текст оповещения
	$alert = mysql_real_escape_string("Заявка от студента <a href='profile.php?z=".$_SESSION["userId"]."'>".$student."</a> на выполнение работы <b><a href='topic.php?z=".safe_var($_GET["z"])."'>".$topic["txtTopic"]."</a></b> <input id='submit' type='button' onclick=\"location.href='application.php?z=".$max["max"]."&n=".$nmax["nmax"]."'\" value='Подробнее' />");

	#оповещение для преподавателя
	mysql_query("INSERT INTO `tblNotification` (`intNotificationId`, `intRecipientId`, `txtMessage`, `boolCheck`, `datDate`,`intWorkId`, `intApplicationsId`) VALUES ('".$nmax["nmax"]."', '".$topic["intTeacherId"]."', '".$alert."', '0', '".$now_time."','".safe_var($_GET["z"])."', '".$max["max"]."')");
	
	#оповещение на почту для пользователя
	notification_by_mail($topic["intTeacherId"], 'alert');
	
	$_SESSION["um"] = 'i2';
	header("Location: ./../home"); exit();
	
?>