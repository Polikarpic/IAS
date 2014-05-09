<?php
 


	include_once("php/db.php");
	session_start(); 
	
	#проверяем передан ли номер сообщения
	if (isset($_GET["z"]))
	{	
		#получаем сообщение
		$query = mysql_query("SELECT intMessageId, intSenderId, datDate, txtTopic, txtMessage FROM tblMessage WHERE intMessageId='".safe_var($_GET["z"])."' and intRecipientId='".$_SESSION["userId"]."' LIMIT 1");
		$mess = mysql_fetch_array($query);
		
		if (empty($mess) || mysql_num_rows($query) == 0){ header("location: ./message.php"); exit(); } 
		
		#отмечаем сообщение как прочитанное
		mysql_query("UPDATE tblMessage SET boolCheck='1' WHERE intMessageId='".safe_var($_GET["z"])."' and intRecipientId='".$_SESSION["userId"]."'");
		
		#получаем логин отправителя для ответного сообщения
		$query = mysql_query("SELECT txtLogin FROM tblUsers WHERE intUserId='".$mess["intSenderId"]."' LIMIT 1");
		$name = mysql_fetch_array($query);
		
	}
	
	$title = "Сообщение от ".getFullName($mess["intSenderId"])."";
	$current_menu4 = "current-menu-item current_page_item";
	include_once("templ/block_header.php");
		
?>
<div id="edge">
<p>Дата: <b><?php echo $mess["datDate"]; ?></b></p>
<p>Тема: <b><?php echo $mess["txtTopic"]; ?></b> </p>
<p>Отправитель: <b><?php echo getFullName($mess["intSenderId"]); ?></b> </p>
<div id="edge">
<p><?php echo $mess["txtMessage"]; ?></p>
</div>
</div>
 <input id="submit" type="button" name="new_message" onclick="location.href='new_message?u=<?php echo $name["txtLogin"]; ?>&t=<?php echo $mess["txtTopic"]; ?>'" value="Ответить" />

<?php include_once("templ/block_footer.php"); ?>	