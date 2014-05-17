<?php
include_once("db.php");
session_start();  

	$recipient = safe_var($_POST['name']);
	$theme = safe_var($_POST['theme']);
	$message = safe_var($_POST['message']);
	$now_time = date("Y-m-d H:i:s",mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y')));
	
	# проверяем есть ли получатель сообщения
	$query = mysql_query("SELECT intUserId FROM tblUsers WHERE BINARY txtLogin='".$recipient."' LIMIT 1");
    $data = mysql_fetch_array($query);
	
	# проверяем есть ли запись
	if (empty($data) || mysql_num_rows ($query) == 0)
	{
		$_SESSION["um"] = 'e15';
		header("Location: ./../new_message"); exit();
	} 
	
	#проверяем не отправляем ли мы сообщение самому себе
	if ($data["intUserId"] == $_SESSION["userId"])
	{
		$_SESSION["um"] = 'e14';
		header("Location: ./../new_message"); exit();
	}
	
		#сохраняем сообщение для получателя
		mysql_query("INSERT INTO `tblMessage` (`intMessageId`, `intSenderId`, `intRecipientId`, `txtTopic`, `txtMessage`, `boolCheck`, `boolType`, `datDate`) VALUES (NULL, '".$_SESSION["userId"]."', '".$data["intUserId"]."', '".$theme."', '".$message."', '0', '0','".$now_time."')");
	
		#сохраняем сообщение для отправителя
		mysql_query("INSERT INTO `tblMessage` (`intMessageId`, `intSenderId`, `intRecipientId`, `txtTopic`, `txtMessage`, `boolCheck`, `boolType`, `datDate`) VALUES (NULL, '".$_SESSION["userId"]."', '".$data["intUserId"]."', '".$theme."', '".$message."', '1', '1','".$now_time."')");

	#вызываем функцию для проверки и отправки оповещений на электронный адрес пользователя
	notification_by_mail($data["intUserId"], 'mess','<hr /><i>'.strftime('%A,%H:%M',strtotime($now_time)).'</i><p>От кого: <b>'.getFullName($_SESSION["userId"]).'</b><br />Тема: <b>'.$theme.'</b><br />Сообщение: '.$message.'</p><hr /><p>НЕ ОТВЕЧАЙТЕ НА ЭТО ПИСЬМО!</p> <p>Вы можете просмотреть сообщение и ответить по указанной ниже ссылке: <br /> <a href="ias-petrsu.ru/message">ias-petrsu.ru/message</a></p>');	
		
	$_SESSION["um"] = 'i7';
	header("Location: ./../message?&c=1"); 
	
?>