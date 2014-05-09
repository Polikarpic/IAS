<?php 
 
include_once("php/db.php");
// Начинаем сессию  
session_start();  

#очистка сессии
if (!empty($_GET["logout"])){
  $query = mysql_query("UPDATE tblUsers SET txtSessionId=NULL WHERE intUserId='".safe_var($_SESSION["userId"])."'");
  unset($_SESSION);
  unset($_COOKIE["hash"]);
  setcookie ("hash", "", time() - 3600);
  session_unset(); 
  session_destroy();
  header("Location: ."); exit();  
}

#если пользователей уже авторизован, переадресовываем на домашнюю страницу
if ((isset($_SESSION["userId"]) && $_SESSION["userId"] != '') || (isset($_COOKIE["hash"]) && $_COOKIE["hash"] != ''))
{
	header("Location: home"); exit();
}





 ?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />  
	<link rel="icon" href="/favicon.ico" type="image/x-icon">
	<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
<head>
	<title>Информационно аналитическая система</title>
	<link rel="stylesheet" type="text/css" href="css/login.css" />
	<link rel="icon" href="images/favicon.ico" type="image/x-icon">
	<link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
</head>

<body>
<form id="login" action="php/auth.php" method="POST">
    <h1><img src="images/logo.png"/></h1>
    <fieldset id="inputs">
        <input id="username" name="login" maxlength="30" placeholder="Логин" autofocus="" required="" value="<?php if (!empty($_SESSION["login"])) echo $_SESSION["login"]; ?>" type="text">   
        <input id="password" name="password" maxlength="30" placeholder="Пароль" required="" type="password">
	</fieldset>
    <fieldset id="actions">
		<input type="checkbox" class="checkbox" name="rememberMe"><label>Запомнить меня</label>
		<input id="submit" type="submit" value="Войти" >        
    </fieldset>
</form>
 </body> 
 
 </html> 