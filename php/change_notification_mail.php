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
	
	set_user_settings(1, $set1);
	set_user_settings(2, $set2);
	set_user_settings(3, $set3);
	
	
	$_SESSION["um"] = 'i27';
	header("Location: ./../settings"); exit();

	
	
?>