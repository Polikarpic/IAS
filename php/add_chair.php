<?php


include_once("db.php");
session_start();  

	#доступ только у руководства кафедр
	if ($_SESSION["statusId"] != 3){ header("Location: ./../home"); exit(); }
	
	$chair = safe_var($_POST["chair"]);
	$abbreviation = safe_var($_POST["abbreviation"]);
	
	#добавляем новость
	mysql_query("INSERT INTO tblChair (`intChairId`, `txtChairName`, `txtAbbreviation`) VALUES (NULL, '$chair', '$abbreviation')");
		
	header("Location: ./../add_chair?add_chair=ok");
	
?>