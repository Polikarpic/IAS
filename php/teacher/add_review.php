<?php



include_once("./../db.php");
session_start();  




	#подробное описание, документ
	if (!$_FILES['f']['error']){
	
	/* проверка размера файла */
	if($_FILES["f"]["size"] > 1024*10*1024)
	{
		header("Location: ./../../current_topic?z=".$_GET["z"]."&add_re=fail&reason=size");
		exit();
    }
	
	
	$ext = substr(strrchr($_FILES['f']['name'], '.'), 1);
	
	/* проверка расширения */
	if ($ext != 'pdf' && $ext != 'doc' && $ext != 'docx' && $ext != 'docm')
	{
		header("Location: ./../../current_topic?z=".$_GET["z"]."&add_re=fail&reason=extension");
		exit();
	}
	
	$uploaddir = "./../../doc/";
	
	#получаем id номер для нового документа
	$max = mysql_query("SELECT MAX(intReviewId) as max FROM tblReview");
	$max = mysql_fetch_array($max);
	$max["max"]++;   		
	
	$temp = md5(uniqid(rand(),1).$max["max"]).".".$ext;
	$uploadfile = $uploaddir.$temp;
	$now_time = date("Y-m-d H:i:s",mktime(date('H') + 4,date('i'),date('s'),date('m'),date('d'),date('Y'))); 
	
	move_uploaded_file($_FILES['f']['tmp_name'], $uploadfile);
	$slink = "doc/".$temp;
	
	#добавляем информацию о документе в базу
	mysql_query("INSERT INTO `tblReview` (`intReviewId`, `txtName`, `txtLink`, `intWorkId`, `boolType`,`datDate`) VALUES ('".$max["max"]."', '".safe_var($_FILES["f"]["name"])."', '".safe_var($slink)."', '".safe_var($_GET["z"])."', '2','".$now_time."')") or die(mysql_error()); 
	
	
	$query = mysql_query("SELECT txtTopic, intStudentId, intTeacherId FROM tblWork WHERE intWorkId='".safe_var($_GET["z"])."' LIMIT 1");
	$topic = mysql_fetch_array($query);
	
	$teacher = getFullName($topic["intTeacherId"]);	
	
	$alert = mysql_real_escape_string("Преподаватель <a href='profile.php?z=".$topic["intTeacherId"]."'>".$teacher."</a> загрузил новую версию отзыва к вашей работе <b><a href='current_topic.php?z=".safe_var($_GET["z"])."'>".$topic["txtTopic"]."</a></b>.");
	
	$students = split_studentId($topic['txtStudentId']);
	$max = sizeof($students);
	for ($i = 1; $i <= $max; $i++)
	{	
		#оповещение для студента о том, что преподаватель загрузил новую версию отзыва
		mysql_query("INSERT INTO `tblNotification` (`intNotificationId`, `intRecipientId`, `txtMessage`, `boolCheck`, `datDate`) VALUES (NULL, '".$students[$i]."', '".$alert."', '0', '".$now_time."')");
	}
	
	header("Location: ./../../current_topic?z=".$_GET["z"]."&add_re=ok");
	
	}	
	else
	{
		
		header("Location: ./../../current_topic?z=".$_GET["z"]."&add_re=fail&reason=not_file");
		exit();
	}

	
?>