<?php



include_once("./db.php");
session_start();  

	#доступ только у преподавателя
	if ($_SESSION["statusId"] != 1){ header("Location: ./../home"); exit(); }
	
	$now_time = date("Y-m-d H:i:s",mktime(date('H') + 4,date('i'),date('s'),date('m'),date('d'),date('Y'))); 
	
	# получаем информацию о заявке
	$query = mysql_query("SELECT * FROM tblList_of_applications WHERE intApplicationsId='".safe_var($_GET["z"])."' LIMIT 1");
	$appl = mysql_fetch_array($query);
	
	# проверяем выполяет ли работу уже какой-то студент (групповая работа)
	$query = mysql_query("SELECT txtStudentId, intStudentsPerformingWork, txtNumber_of_persons FROM tblWork WHERE intWorkId='".$appl["intWorkId"]."' LIMIT 1");
	$work = mysql_fetch_array($query);
	
    #если в работу ещё не записан студент
	if (is_null($work["txtStudentId"])){	
		#добавляем студента в работу и повышаем число участников работы
		mysql_query("UPDATE tblWork SET txtStudentId=' ".$appl["intStudentId"]." ', intStudentsPerformingWork = intStudentsPerformingWork + 1 WHERE intWorkId='".$appl["intWorkId"]."' LIMIT 1");	
	}
	else 
	{
	    #добавляем студента в работу и повышаем число участников в работе
		mysql_query("UPDATE tblWork SET txtStudentId=CONCAT(txtStudentId,'".$appl["intStudentId"]."',' '), intStudentsPerformingWork = intStudentsPerformingWork + 1 WHERE intWorkId='".$appl["intWorkId"]."' LIMIT 1");	
	}
		
	$alert = mysql_real_escape_string("Преподаватель <a href='profile?z=".$appl["intTeacherId"]."'>".$teacher."</a> одобрил Вашу заявку на выполнение работы <b><a href='current_topic?z=".$appl["intWorkId"]."'>".$appl["txtTopic"]."</a></b>");
			
    #оповещение для студента
	mysql_query("INSERT INTO `tblNotification` (`intNotificationId`, `intRecipientId`, `txtMessage`, `boolCheck`, `datDate`) VALUES (NULL, '".$appl["intStudentId"]."', '".$alert."', '0', '".$now_time."')");
		
	#удаляем оповещение о заявке на работу для преподавателя
	mysql_query("DELETE FROM tblNotification WHERE intNotificationId='".safe_var($_GET["n"])."' LIMIT 1");
	
	###отказываем всем остальным заявкам на выполнение этой работы, если работа не групповая		
	if ($work["txtNumber_of_persons"] < $work["intStudentsPerformingWork"])
	{
	
		$query = mysql_query("SELECT l.intStudentId as student
		FROM tblNotification n
		LEFT JOIN tblList_of_applications l ON l.intWorkId = n.intWorkId and l.intApplicationsId <> '".safe_var($_GET["z"])."'
		WHERE n.intWorkId='".$appl["intWorkId"]."'");
		
		while($data = mysql_fetch_array($query))
		{
			$alert = mysql_real_escape_string("Преподаватель <a href='profile?z=".$appl["intTeacherId"]."'>".$teacher."</a> отклонил Вашу заявку на выполнение работы <b><a href='current_topic?z=".$appl["intWorkId"]."'>".$appl["txtTopic"]."</a></b>");
			
			#оповещение для студента
			mysql_query("INSERT INTO `tblNotification` (`intNotificationId`, `intRecipientId`, `txtMessage`, `boolCheck`, `datDate`) VALUES (NULL, '".$data["student"]."', '".$alert."', '0', '".$now_time."')");
				
			#удаляем оповещение о заявке на работу для преподавателя
			mysql_query("DELETE FROM tblNotification WHERE intRecipientId='".$appl["intTeacherId"]."' and intWorkId='".$appl["intWorkId"]."'");
		}
			
		#меняем статус заявок на работу
		mysql_query("UPDATE tblList_of_applications SET boolStatus = '1' WHERE intWorkId='".$appl["intWorkId"]."' and boolStatus='0'");
		
	}	
		
	header("Location: ./../home?take=ok"); exit();
	
?>