<?php
include_once("./db.php");
session_start();  

	#доступ только у преподавателя
	if ($_SESSION["statusId"] != 1){ header("Location: ./../home"); exit(); }
	
		$now_time = date("Y-m-d H:i:s",mktime(date('H') + 4,date('i'),date('s'),date('m'),date('d'),date('Y'))); 
		
		# получаем информацию о заявке
		$query = mysql_query("SELECT * FROM tblList_of_applications WHERE intApplicationsId='".safe_var($_GET["z"])."' LIMIT 1");
		$appl = mysql_fetch_array($query);		
		
		if (empty($appl) || mysql_num_rows($query) == 0){ header("location: ./../alert?reject=fail"); exit(); }
		
		$teacher = getFullName($appl["intTeacherId"]);
		$alert = mysql_real_escape_string("Преподаватель <a href='profile.php?z=".$appl["intTeacherId"]."'>".$teacher."</a> отклонил Вашу заявку на выполнение работы <b><a href='topic.php?z=".$appl["intWorkId"]."'>".$appl["txtTopic"]."</a></b>");

		#меняем статус заявки
		mysql_query("UPDATE tblList_of_applications SET boolStatus = '1' WHERE intWorkId='".$appl["intWorkId"]."' and intStudentId='".$appl["intStudentId"]."' and intTeacherId='".$appl["intTeacherId"]."' and boolStatus='0'");
			
		#оповещение для студента
		mysql_query("INSERT INTO `tblNotification` (`intNotificationId`, `intRecipientId`, `txtMessage`, `boolCheck`, `datDate`) VALUES (NULL, '".$appl["intStudentId"]."', '".$alert."', '0', '".$now_time."')");
				
		#удаляем оповещение о заявке на работу для преподавателя
		mysql_query("DELETE FROM `tblNotification` WHERE intNotificationId='".safe_var($_GET["n"])."'");
		
		header("Location: ./../home?reject=ok"); exit();
	
?>