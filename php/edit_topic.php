<?php



include_once("db.php");
session_start();  

	#доступ
	if ($_SESSION["statusId"] == 0){ $_SESSION["um"] = 'e0'; header("Location: ./home"); exit(); }
	
	
	$workId = safe_var($_POST['intWorkId']);
	
	#подробное описание, документ
	if (!$_FILES['additional_comment']['error']){
	
	
	#получаем подробное описание и удаляем его
	$query = mysql_query("DELETE FROM tblReview WHERE intWorkId='".$workId."' and boolType='0' LIMIT 1");
  	$data = mysql_fetch_array($query);
	unlink("./../".$data["txtLink"]);
	
	/* проверка размера файла */
	if($_FILES["additional_comment"]["size"] > 1024*10*1024)
	{
		$_SESSION["um"] = 'e4';
		header("Location: ./../add_topic");
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
	
	
	$now_time = date("Y-m-d H:i:s",mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y')));
	
	move_uploaded_file($_FILES['additional_comment']['tmp_name'], $uploadfile);
	$slink = "doc/".$temp;
	
	#добавляем информацию о документе в базу
	mysql_query("INSERT INTO `tblReview` (`intReviewId`, `txtName`, `txtLink`, `intWorkId`, `boolType`,`datDate`) VALUES ('".$max["max"]."', '".safe_var($_FILES["additional_comment"]["name"])."', '".safe_var($slink)."', '".$workId."', '0','".$now_time."')"); 

} 	
	
	$theme = safe_var($_POST['theme']);
	$work_type = safe_var($_POST['workType']);
	$comment = safe_var($_POST['comment']);
	$course = safe_var($_POST['course']);
	$direction = safe_var($_POST['direction']);

	$direction2 = safe_var($_POST['direction2']);
	if ($direction2 != '0') $direction2 = "'".$direction2."'";
	else $direction2 = 'NULL';
	
	$nop = safe_var($_POST['nop']);
	
	
	#меняем тему
	mysql_query("UPDATE tblWork SET txtTopic = '".$theme."', boolType='".$work_type."' ,txtComment = '".$comment."', txtNumber_of_persons = '".$nop."', intDirectionId = '".$direction."', intDirectionId2 = $direction2, txtCourse = '".$course."', intAdditionalComment = '".$max["max"]."', intChairId = '".$_SESSION["chairId"]."'  WHERE intWorkId='".$workId."'");
			
	$_SESSION["um"] = 'i3';
    header("Location: ./../home"); exit();	
	
?>