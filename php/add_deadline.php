<?php


include_once("db.php");
session_start();  

	#������ ������ � ����������� ������
	if ($_SESSION["statusId"] != 3){ header("Location: ./../home"); exit(); }
	
	$deadline1 = safe_var($_POST["deadline1"]);
	$deadline2 = safe_var($_POST["deadline2"]);
	
	#��������� �������
	mysql_query("INSERT INTO tblDeadlines (`intDeadlinesId`, `dateRentingReport`, `dateRentingWork`) VALUES (NULL, '$deadline1', '$deadline2')");
		
	header("Location: ./../add_deadline?add_deadline=ok");
	
?>