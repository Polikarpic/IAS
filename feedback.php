<?php

 
	$title = "Обратная связь с администрацией проекта";
	$current_menu1 = "current-menu-item current_page_item";
	include_once("templ/block_header.php"); 
	 
?>
<div id="edge" class="button">
			<form action="" method="post">
			<label>Ваше имя: </label><input type="text" name="name" required="" placeholder="Ваше имя"/><br />
			<label>Обратный e-mail: </label><input type="text" name="theme" placeholder="Обратный e-mail"/><br />
			<label>Сообщение: </label><br />
			<textarea name="message" required="" placeholder="Сообщение"></textarea><br />
			<input id="submit" type="submit" name="send" value="Отправить" />
</div>

<?php include_once("templ/block_footer.php"); ?>	