<?php



include_once("db.php");

// Начинаем сессию  
session_start();  

# если не заданы переменные сессии (пользователь не авторизован)
if (!isset($_COOKIE["hash"]) && !isset($_SESSION['login']))
{
	header("Location: ./../?log=fail"); exit();
}

#если есть куки, но не сохраны данные в сессии
if ($_SESSION["login"] == '' && $_COOKIE["hash"] != '')
{
	$query = mysql_query("SELECT * FROM tblUsers WHERE txtSessionId='".safe_var($_COOKIE["hash"])."' LIMIT 1");
	
	if (mysql_num_rows($query) == 0)
	{
		header("Location: ./../");
		setcookie ("hash", "", time() - 3600);
		exit();
	}
	
	$data = mysql_fetch_assoc($query);	
	
	$_SESSION['login'] = $data['txtLogin'];
	$_SESSION['password'] = $data['txtPass'];
	$_SESSION['userId'] = $data['intUserId'];
	$_SESSION['statusId'] = $data['intStatusId'];
	$_SESSION['chairId'] = $data['intChairId'];
	$_SESSION['directionId'] = $data['intDirectionId'];
	$_SESSION['courseId'] = $data['txtCourse'];	
	return;
}


#куки не установлены, но задан логин в сессии	
#Вытаскиваем из БД запись о пароле пользователя
$query = mysql_query("SELECT txtPass FROM tblUsers WHERE txtLogin='".safe_var($_SESSION['login'])."' LIMIT 1");
$data = mysql_fetch_assoc($query);

# Сравниваем пароли
if($data['txtPass'] != $_SESSION['password'] || empty($data['txtPass']) || empty($_SESSION['password']))
{
	header("Location: ./../?log=fail"); exit();
}
	
	unset($data);
	

?>