<?php


include_once("db.php");
session_start();  

	#доступ только у преподавателя
	if ($_SESSION["statusId"] != 1) header("Location: ./home");
	
	#получаем id номер для новой работы
	$wmax = mysql_query("SELECT MAX(intWorkId) as max FROM tblWork");
	$wmax = mysql_fetch_array($wmax);
	$wmax["max"]++;   	
	
	#подробное описание, документ
	if (!$_FILES['additional_comment']['error']){
	
	/* проверка размера файла */
	if($_FILES["additional_comment"]["size"] > 1024*10*1024)
	{
		//echo "Размер файла превышает десять мегабайт";
		header("Location: ./../add_topic?add_topic=fail&reason=size");
		exit();
    }
	$ext = substr(strrchr($_FILES['additional_comment']['name'], '.'), 1);
		
	$uploaddir = "./../doc/";

	#получаем id номер для нового документа
	$max = mysql_query("SELECT MAX(intReviewId) as max FROM tblReview");
	$max = mysql_fetch_array($max);
	$max["max"]++;   		

	$temp = md5(uniqid(rand(),1).$max["max"]).".".$ext;
	$uploadfile = $uploaddir.$temp;
	$now_time = date("Y-m-d H:i:s",mktime(date('H') + 4,date('i'),date('s'),date('m'),date('d'),date('Y'))); 
	
	move_uploaded_file($_FILES['additional_comment']['tmp_name'], $uploadfile);
	$slink = "doc/".$temp;
	
	#добавляем информацию о документе в базу
	mysql_query("INSERT INTO `tblReview` (`intReviewId`, `txtName`, `txtLink`, `intWorkId`, `boolType`,`datDate`) VALUES ('".$max["max"]."', '".safe_var($_FILES["additional_comment"]["name"])."', '".safe_var($slink)."', '".$wmax["max"]."', '0','".$now_time."')"); 

} 
	$theme = safe_var($_POST['theme']);
	$work_type = safe_var($_POST['workType']);
	$comment = safe_var($_POST['comment']);
	$course = safe_var($_POST['course']);
	$direction = safe_var($_POST['direction']);
	$nop = safe_var($_POST['nop']);
	
	#если есть подробное описание
	if (!$_FILES['additional_comment']['error']) 
		mysql_query("INSERT INTO `tblWork` (`intWorkId`, `txtStudentId`, `intTeacherId`, `boolType`, `txtTopic`, `txtComment`, `intAdditionalComment`, `txtNumber_of_persons`, `intDirectionId`, `txtCourse`, `intChairId`) VALUES ('".$wmax["max"]."', NULL, '".$_SESSION["userId"]."', '".$work_type."', '".$theme."', '".$comment."', '".$max["max"]."', '".$nop."', '".$direction."', '".$course."','".$_SESSION["chairId"]."')");

	#если нет подробного описания
	else 
		mysql_query("INSERT INTO `tblWork` (`intWorkId`, `txtStudentId`, `intTeacherId`, `boolType`, `txtTopic`, `txtComment`, `intAdditionalComment`, `txtNumber_of_persons`, `intDirectionId`, `txtCourse`, `intChairId`) VALUES ('".$wmax["max"]."', NULL, '".$_SESSION["userId"]."', '".$work_type."', '".$theme."', '".$comment."', NULL, '".$nop."', '".$direction."', '".$course."','".$_SESSION["chairId"]."')");
		
	
	header("Location: ./../home?add_topic=ok&k=".$work_type.""); exit();	
	
?>