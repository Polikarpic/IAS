<?php



include_once("db.php");
session_start();  

	$old_password = safe_var($_POST['old_password']);
	$new_password = safe_var($_POST['new_password']);
	$confirm_password = safe_var($_POST['confirm_password']);

	#получаем информацию о пароле пользователя
	$query = mysql_query("SELECT txtPass FROM tblUsers WHERE intUserId='".$_SESSION["userId"]."' LIMIT 1");
    $pass = mysql_fetch_array($query);
		
	#проверяем совпадают ли пароли
	if ($pass["txtPass"] === md5(md5('c2tsE}DkAJK!]W%'.$old_password.'QdmrA}SM(Z4n?\/'))){
		if ($new_password === $confirm_password){ #совпадает ли пароль подтверждения
			#сохраняем пароль пользователя
			mysql_query("UPDATE tblUsers SET txtPass='".md5(md5('c2tsE}DkAJK!]W%'.$new_password.'QdmrA}SM(Z4n?\/'))."' WHERE intUserId='".$_SESSION["userId"]."'");
			header("Location: ./../settings.php?change_pass=ok");
		} 
		else 
		{
			header("Location: ./../settings?change_pass=fail&reason=n_confirm"); exit();
		}	
	}
	else 
	{
		header("Location: ./../settings?change_pass=fail&reason=n_pass"); exit();
	}
?>