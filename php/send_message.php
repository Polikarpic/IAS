<?php



include_once("db.php");
session_start();  

	$recipient = safe_var($_POST['name']);
	$theme = safe_var($_POST['theme']);
	$message = safe_var($_POST['message']);
	$now_time = date("Y-m-d H:i:s",mktime(date('H') + 4,date('i'),date('s'),date('m'),date('d'),date('Y'))); 
	
	# проверяем есть ли получатель сообщения
	$query = mysql_query("SELECT intUserId FROM tblUsers WHERE BINARY txtLogin='".$recipient."' LIMIT 1");
    $data = mysql_fetch_array($query);
	
	# проверяем есть ли запись
	if (empty($data) || mysql_num_rows ($query) == 0)
	{
		header("Location: ./../new_message.php?send=fail&reason=not_user"); exit();
	} 
	
	#проверяем не отправляем ли мы сообщение самому себе
	if ($data["intUserId"] == $_SESSION["userId"])
	{
		header("Location: ./../new_message?send=fail&reason=oneself"); exit();
	}
	
		#сохраняем сообщение
		mysql_query("INSERT INTO `tblMessage` (`intMessageId`, `intSenderId`, `intRecipientId`, `txtTopic`, `txtMessage`, `boolCheck`, `datDate`) VALUES (NULL, '".$_SESSION["userId"]."', '".$data["intUserId"]."', '".$theme."', '".$message."', '0', '".$now_time."')");
		header("Location: ./../message?info=sent"); 
	
?>