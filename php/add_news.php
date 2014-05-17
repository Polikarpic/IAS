<?php


include_once("db.php");
session_start();  

	#доступ
	if ($_SESSION["statusId"] == 0){ $_SESSION["um"] = 'e0'; header("Location: ./../home"); exit(); }
	
	$news = safe_var($_POST['news']);
	$group = safe_var($_POST['group']);
		if ($group != 'NULL') $group = "'".$group."'";
		
	$chair = safe_var($_POST['chair']);
		if ($chair != 'NULL') $chair = "'".$chair."'";
		
	$direction = safe_var($_POST['direction']);
		if ($direction != 'NULL') $direction = "'".$direction."'";
		
	$course = safe_var($_POST['course']);
		if ($course != 'NULL') $course = "'".$course."'";
	
	
	$now_time = date("Y-m-d H:i:s",mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y')));
	
	#добавляем новость
	mysql_query("INSERT INTO `tblNews` (`intNewsId`, `txtText`, `intSenderId`, `datDate`, `intGroup_of_readers`, `intDirectionId`, `intCourse`,`intChairId`) VALUES (NULL, '".$news."', '".$_SESSION["userId"]."', '".$now_time."', $group, $direction,$course, $chair)");
	
	$_SESSION["um"] = 'i11';
	header("Location: ./../news");
	
?>