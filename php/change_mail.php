<?php



include_once("db.php");
session_start();  

	$email = safe_var($_POST['mail']);
	
	#проверка корректности
	if(!preg_match("|^[-0-9a-z_\.]+@[-0-9a-z_^\.]+\.[a-z]{2,6}$|i", $email)) 
    { 
        header("Location: ./../settings?change_mail=fail&reason=incorrect"); exit();
    } 	
	
	#изменяем почтовый ящик пользователя
	mysql_query("UPDATE tblUsers SET txtMail='".$email."' WHERE intUserId='".$_SESSION["userId"]."'");
	header("Location: ./../settings?change_mail=ok"); exit();

?>