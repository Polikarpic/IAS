<?php



include_once("./../db.php");
session_start();  

	if ($_SESSION["statusId"] != 0){ $_SESSION["um"] = 'e0'; header("Location: ./../../home"); exit();}

	#подробное описание, документ
	if (!$_FILES['f']['error']){
	
	/* проверка размера файла */
	if($_FILES["f"]["size"] > 1024*10*1024)
	{
		$_SESSION["um"] = 'e4';
		header("Location: ./../../current_topic?z=".$_GET["z"]);
		exit();
    }
	/* проверка расширения */
	$ext = substr(strrchr($_FILES['f']['name'], '.'), 1);
	
	/* проверка расширения */
	if ($ext != 'pdf' && $ext != 'doc' && $ext != 'docx' && $ext != 'docm')
	{
		$_SESSION["um"] = 'e17';
		header("Location: ./../../current_topic?z=".$_GET["z"]);
		exit();
	}
	
	#тип файла(отчёт/курсовая работа)
	$type_file = $_POST["type_file"];	
	
	$uploaddir = "./../../doc/";
	
	#получаем id номер для нового документа
	$max = mysql_query("SELECT MAX(intReviewId) as max FROM tblReview");
	$max = mysql_fetch_array($max);
	$max["max"]++;   		
	
	$temp = md5(uniqid(rand(),1).$max["max"]).".".$ext;
	$uploadfile = $uploaddir.$temp;
	$now_time = date("Y-m-d H:i:s",mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y')));
	
	move_uploaded_file($_FILES['f']['tmp_name'], $uploadfile);
	$slink = "doc/".$temp;
	
	#добавляем информацию о документе в базу
	mysql_query("INSERT INTO `tblReview` (`intReviewId`, `txtName`, `txtLink`, `intSenderId`,`intWorkId`, `boolType`,`datDate`) VALUES ('".$max["max"]."', '".safe_var($_FILES["f"]["name"])."', '".safe_var($slink)."', '".$_SESSION["userId"]."','".safe_var($_GET["z"])."', '".$type_file."','".$now_time."')") or die(mysql_error()); 
		
	
	$query = mysql_query("SELECT txtTopic, txtStudentId, intTeacherId FROM tblWork WHERE intWorkId='".safe_var($_GET["z"])."' LIMIT 1");
	$topic = mysql_fetch_array($query);
	
	$student = getFullName($_SESSION["userId"]);	
	
	$alert = mysql_real_escape_string("Студент <a href='profile.php?z=".$_SESSION["userId"]."'>".$student."</a>, выполняющий вашу работу <b><a href='current_topic.php?z=".safe_var($_GET["z"])."'>".$topic["txtTopic"]."</a></b>, загрузил новую версию курсовой работы.");
	
	#оповещение для преподавателя о том, что студент загрузил новую версию курсовой работы
	mysql_query("INSERT INTO `tblNotification` (`intNotificationId`, `intRecipientId`, `txtMessage`, `boolCheck`, `datDate`) VALUES (NULL, '".$topic["intTeacherId"]."', '".$alert."', '0', '".$now_time."')");
		
	#оповещение на почту для пользователя
	notification_by_mail($topic["intTeacherId"], 'alert');	
	
	$_SESSION["um"] = 'i12';
	header("Location: ./../../current_topic?z=".$_GET["z"]);	
	
	} 
	else
	{
		$_SESSION["um"] = 'e5';
		header("Location: ./../../current_topic?z=".$_GET["z"]);
		exit();
	}
	
?>