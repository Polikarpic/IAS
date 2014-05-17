<?php


include_once("db.php");
session_start();  

	#доступ 
	if ($_SESSION["statusId"] != 2 && $_SESSION["statusId"] != 3){ $_SESSION["um"] = 'e0'; header("Location: ./../home"); exit(); }
	
	$direction = safe_var($_POST["direction"]);
	
	#добавляем новость
	mysql_query("INSERT INTO tblDirection (`intDirectionId`, `txtDirectionName`) VALUES (NULL, '$direction')");
		
	$_SESSION["um"] = 'i10';
	header("Location: ./../add_direction");
	
?>