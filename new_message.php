<?php
	include_once("php/db.php");
	session_start(); 

	$title = "Новое сообщение";
	$current_menu3 = "current-menu-item current_page_item";
	include_once("templ/block_header.php");
	
	#сообщения для пользователя
	um();
	 
?>
<div id="edge" class="button">
			<form action="php/send_message.php" method="post">
			<label>Получатель: </label><input type="text" maxlength="30" name="name" required="" value="<?php if (isset($_GET["u"])) echo $_GET["u"]; ?>" placeholder="Получатель"/><br />
			<label>Тема: </label><input type="text" maxlength="30" name="theme" value="<?php if (isset($_GET["t"])) echo $_GET["t"]; ?>" placeholder="Тема" /><br />
			<label>Сообщение: </label><br />
			<textarea name="message" required="" maxlength="1000" placeholder="Сообщение"></textarea><br />
			<input id="submit" type="submit" name="send" value="Отправить" />
			</form>			
</div>
<?php include_once("templ/block_footer.php"); ?>	