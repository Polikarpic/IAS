<?php


include_once("db.php");
session_start();  

	#доступ
	if ($_SESSION["statusId"] == 0){ $_SESSION["um"] = 'e0'; header("Location: ./../home"); exit(); }
	
	$login = safe_var($_POST['login']);
	
	$pass = safe_var($_POST['pass']);	
	if (empty($pass)) $pass_no_change = false;
	else $pass_no_change = true;	
	$pass = md5(md5('c2tsE}DkAJK!]W%'.safe_var($pass).'QdmrA}SM(Z4n?\/'));	
	
	$status = safe_var($_POST['status']);	
	$surname = safe_var($_POST['surname']);
	$name = safe_var($_POST['name']);
	$secondname = safe_var($_POST['secondname']);
	$course = safe_var($_POST['course']);
	$group = safe_var($_POST['group']);
	$direction = safe_var($_POST['direction']);
	$chair = safe_var($_POST['chair']);
	$intUserId = safe_var($_POST['intUserId']);
	
	#проверка на занятость логина
	$query = mysql_query("select intUserId from `tblUsers` WHERE txtLogin='".$login."'");
	$user = mysql_fetch_array($query);
	
	if(!mysql_fetch_array($query) || $user["intUserId"] == $intUserId) #логин не занят
	{	
		if ($status != 0) # не студент
		{
			if ($pass_no_change) #если нужно поменять и пароль
			{
				mysql_query("UPDATE tblUsers SET txtSurname='".$surname."', txtName='".$name."', txtSecondName='".$secondname."', txtLogin='".$login."', txtPass='".$pass."', intStatusId='".$status."', intChairId='".$chair."', txtCourse = NULL, intDirectionId = NULL WHERE intUserId='".$intUserId."'");
			}
			else #не меняем пароль
			{
				mysql_query("UPDATE tblUsers SET txtSurname='".$surname."', txtName='".$name."', txtSecondName='".$secondname."', txtLogin='".$login."', intStatusId='".$status."', intChairId='".$chair."', txtCourse = NULL, intDirectionId = NULL WHERE intUserId='".$intUserId."'");
			}
		}
		else #студент
		{
			if ($pass_no_change) #если нужно поменять и пароль
			{
				mysql_query("UPDATE tblUsers SET txtSurname='".$surname."', txtName='".$name."', txtSecondName='".$secondname."', txtLogin='".$login."', txtPass='".$pass."', intStatusId='".$status."', intChairId=NULL, txtCourse = '".$course."', intDirectionId = '".$direction."', txtGroup='".$group."' WHERE intUserId='".$intUserId."'");
			}
			else #не меняем пароль
			{
				mysql_query("UPDATE tblUsers SET txtSurname='".$surname."', txtName='".$name."', txtSecondName='".$secondname."', txtLogin='".$login."', intStatusId='".$status."', intChairId= NULL, txtCourse = '".$course."', intDirectionId = '".$direction."', txtGroup='".$group."' WHERE intUserId='".$intUserId."'");
			}
		}
		
		$_SESSION["um"] = 'i30';
		header("Location: ./../edit_user?z=".$intUserId."");
	}
	else #пользователь с таким логином уже существует
	{
		$_SESSION["um"] = 'e6';
		header("Location: ./../edit_user");	
	}
?>