<?php


include_once("db.php");
session_start(); 
 
	#доступ 
	if ($_SESSION["statusId"] != 2 && $_SESSION["statusId"] != 3){ $_SESSION["um"] = 'e0'; header("Location: ./../home"); exit(); }
	
	$chair = safe_var($_POST["chair"]);
	$abbreviation = safe_var($_POST["abbreviation"]);
	
	#добавляем новость
	mysql_query("INSERT INTO tblChair (`intChairId`, `txtChairName`, `txtAbbreviation`) VALUES (NULL, '$chair', '$abbreviation')");
		
	$_SESSION["um"] = 'i8';
	header("Location: ./../add_chair");
	
?>