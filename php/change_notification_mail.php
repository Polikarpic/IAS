<?php



include_once("db.php");
session_start();  

	$set1 = safe_var($_POST['set1']);
	$set2 = safe_var($_POST['set2']);
	$set3 = safe_var($_POST['set3']);
	if (!empty($set1)) $set1 = 1;
	else $set1 = 0;
	
	if (!empty($set2)) $set2 = 1;
	else $set2 = 0;
	
	if (!empty($set3)) $set3 = 1;
	else $set3 = 0;
	
	
	$set = $set1." ".$set2." ".$set3;

	
	$query = mysql_query("SELECT * FROM tblSettings WHERE intUserId='".$_SESSION["userId"]."'");
	if (mysql_num_rows($query) != 0) 
	{
		mysql_query("UPDATE tblSettings SET txtSettings='".$set."' WHERE intUserId='".$_SESSION["userId"]."'");
	}
	else
	{
		mysql_query("INSERT INTO `tblSettings` (`intUserId`,`txtSettings`) VALUES ('".$_SESSION["userId"]."','".$set."')");	
	}
	
	header("Location: ./../settings?change_notification_mail=ok"); exit();

	
	
?>