<?php


include_once("db.php");
session_start();  

	#������ ������ � ����������� ������
	if ($_SESSION["statusId"] != 3){ header("Location: ./../home"); exit(); }
	
	$direction = safe_var($_POST["direction"]);
	
	#��������� �������
	mysql_query("INSERT INTO tblDirection (`intDirectionId`, `txtDirectionName`) VALUES (NULL, '$direction')");
		
	header("Location: ./../add_direction?add_direction=ok");
	
?>