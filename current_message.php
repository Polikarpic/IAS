<?php
 


	include_once("php/db.php");
	session_start(); 
	
	#проверяем передан ли номер сообщения
	if (isset($_GET["z"]))
	{	
		#получаем сообщение
		$query = mysql_query("SELECT * FROM tblMessage WHERE intMessageId='".safe_var($_GET["z"])."' and 
		((intRecipientId='".$_SESSION["userId"]."' and boolType='0') or
		(intSenderId='".$_SESSION["userId"]."' and boolType='1')) LIMIT 1");
		$mess = mysql_fetch_array($query);
		
		#у пользователя нет прав на просмотр сообщения или сообщение не существует
		if (mysql_num_rows($query) == 0){ $_SESSION["um"]='e0'; header("location: ./message.php"); exit(); } 		
		
		
		if ($mess["boolType"] == 0)
		{ 
			#отмечаем сообщение как прочитанное
			mysql_query("UPDATE tblMessage SET boolCheck='1' WHERE intMessageId='".safe_var($_GET["z"])."' and (intRecipientId='".$_SESSION["userId"]."' and boolType='0')");
		
			#получаем логин отправителя для ответного сообщения
			$query = mysql_query("SELECT txtLogin FROM tblUsers WHERE intUserId='".$mess["intSenderId"]."' LIMIT 1");
			$name = mysql_fetch_array($query);
		}
		
	}
	
	$title = "Сообщение от ".getFullName($mess["intSenderId"])."";
	$current_menu4 = "current-menu-item current_page_item";
	include_once("templ/block_header.php");
		
?>
<div id="edge">
<p>Дата: <b><?php echo $mess["datDate"]; ?></b></p>
<p>Тема: <b><?php echo $mess["txtTopic"]; ?></b> </p>
<?php if ($mess["boolType"] == 0) echo '<p>Отправитель: <b><a href="profile?z='.$mess["intSenderId"].'">'.getFullName($mess["intSenderId"]).'</a></b> </p>'; 
else echo '<p>Получатель: <b><a href="profile?z='.$mess["intRecipientId"].'">'.getFullName($mess["intRecipientId"]).'</a></b> </p>';
?>

<p><b>Сообщение: <b></p>
<div id="edge">
<p><?php echo $mess["txtMessage"]; ?></p>
</div>
</div>
 <?php if ($mess["boolType"] == 0) echo '<input id="submit" type="button" name="new_message" onclick="location.href=\'new_message?u='.$name["txtLogin"].'&t='.$mess["txtTopic"].'\'" value="Ответить" />'; ?>

<?php include_once("templ/block_footer.php"); ?>	