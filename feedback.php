<?php
	$title = "Обратная связь с администрацией проекта";
	$current_menu1 = "current-menu-item current_page_item";
	$js = '<script type="text/javascript" src="js/maxchars.js"></script>';
	include_once("templ/block_header.php"); 	 
?>
<div id="edge" class="button">
			<form action="php/feedback.php" method="post">			
			<label>Обратный e-mail: </label><input type="text" maxlength="256" name="email" placeholder="Обратный e-mail"/><br />
			<label>Тема: </label><input type="text" name="theme" required="" placeholder="Тема" maxlength="256"/><br />
			<label>Сообщение: </label><div style="float:right; margin-right: 33px;">Осталось символов:<span id='count'>1024</span></div><br />
			<textarea name="message" required="" onkeypress="counter(this);" onkeyup="counter(this);" onchange="counter(this);" maxlength="1024" placeholder="Сообщение"></textarea><br />
			<input id="submit" type="submit" name="send" value="Отправить" />
</div>

<?php include_once("templ/block_footer.php"); ?>	