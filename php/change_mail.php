<?php



include_once("db.php");
session_start();  

	$email = safe_var($_POST['mail']);
	$set4 = safe_var($_POST['set4']);
	if (!empty($set4)) $set4 = 1;
	else $set4 = 0;
	
	
	#проверка корректности
	if(!preg_match("|^[-0-9a-z_\.]+@[-0-9a-z_^\.]+\.[a-z]{2,6}$|i", $email) && $email != '') 
    { 
		$_SESSION["um"] = 'e10';
        header("Location: ./../settings"); exit();
    } 	
	
	set_user_settings(4, $set4);
	
	#изменяем почтовый ящик пользователя
	mysql_query("UPDATE tblUsers SET txtMail='".$email."' WHERE intUserId='".$_SESSION["userId"]."'");
	
	$_SESSION["um"] = 'i14';
	header("Location: ./../settings"); exit();

?>