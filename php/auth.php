<?php
//

include_once("db.php");

// Начинаем сессию  
session_start();  

	# Сохраняем сессию
    $_SESSION['login'] = safe_var($_POST['login']); 
	$pass = md5(md5('c2tsE}DkAJK!]W%'.$_POST['password'].'QdmrA}SM(Z4n?\/'));  	
	
	# Вытаскиваем из БД запись, у которой логин равняеться введенному
	$query = mysql_query("SELECT * FROM tblUsers WHERE BINARY txtLogin='".$_POST['login']."' LIMIT 1");
    $data = mysql_fetch_assoc($query);	
	
	
	# Сравниваем пароли
    if($data['txtPass'] === $pass)
    {
		
		# Создаём хэш сессии
        $hash = md5('{JJFm3=cG1l'.(session_id()+$data['intUserId']).'vhJa0O_{JJFm3=cG1ly\15zc?iUmTf');
		
	
		$_SESSION['password'] = $pass;
		$_SESSION['userId'] = $data['intUserId'];
		$_SESSION['statusId'] = $data['intStatusId'];
		$_SESSION['chairId'] = $data['intChairId'];
		$_SESSION['directionId'] = $data['intDirectionId'];
		$_SESSION['courseId'] = $data['txtCourse'];
		
		
		#устанавливаем куки
		if ($_POST["rememberMe"] == "on")
		{
			setcookie("hash", $hash, time()+60*60*24*30,"/");
		
			#сохраняем хэш в БД
			mysql_query("UPDATE tblUsers SET txtSessionId='".$hash."' WHERE intUserId='".$data['intUserId']."'");
		}
		else
		{
			mysql_query("UPDATE tblUsers SET txtSessionId=NULL WHERE intUserId='".$data['intUserId']."'");
		}
		
		 
        # Переадресовываем браузер на страницу home
        header("Location: ./../home");  exit();
	
	#неверный пароль, возвращаем на стартовую страницу
	} else {
		 header("Location: ./../?l=e"); exit();
	}
?>