<?php
 

	include_once("php/db.php");
	session_start(); 

	$title = "Настройки";
	$current_menu6 = "current-menu-item current_page_item";
	#ajax, всплывающие подсказки
	$js = '<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js"></script>
	<script type="text/javascript" src="js/tooltip.js"></script>
	<link rel="stylesheet" type="text/css" href="css/tooltip.css" />';
	include_once("templ/block_header.php"); 
	
	#получаем информацию о текущем пользователе
	$query = mysql_query("SELECT txtMail,txtPhone FROM tblUsers WHERE intUserId='".$_SESSION["userId"]."' LIMIT 1");
    $ep = mysql_fetch_array($query);
	
	#настройки
	$settings = get_user_settings();
	
	#подсказка для изменения пароля
	$hint_pass = "* Убедитесь, что <b>не включена</b> кнопка CAPS-lock<br/>* Пароль должен быть <b>не менее 6 символов</b> в длину<br/>* <b>'qwerty'</b> и <b>'QWErTY'</b> - разные пароли";

	#сообщения для пользователя
	um();
	
?>
<div id="edge" class="button">
	<h1>Изменить пароль</h1>
    <form action="php/change_password.php" method="post">
		<label>Старый пароль: </label><span class="jQtooltip" title="<?php echo $hint_pass; ?>"><input type="password" name="old_password" maxlength="30" required="" placeholder="Старый пароль"/></span><br />
		<label>Новый пароль: </label><span class="jQtooltip" title="<?php echo $hint_pass; ?>"><input type="password" name="new_password" maxlength="30" required="" placeholder="Новый пароль"/></span><br />
		<label>Подтвердите пароль: </label><span class="jQtooltip" title="<?php echo $hint_pass; ?>"><input type="password" name="confirm_password" maxlength="30" required="" placeholder="Подтвердите пароль"/></span><br />
		<input id="submit" type="submit" name="send" value="Изменить пароль" />
	</form>		
</div>	


<div id="edge" class="button">
	<h1>Адрес Вашей электронной почты</h1>
    <form action="php/change_mail.php" method="post">
		<label>E-mail: </label><input type="text" name="mail" placeholder="E-mail" maxlength="30" value="<?php if (isset($ep)) echo $ep["txtMail"]; ?>" /><br />
		<input type="checkbox" <?php if ($settings[3] == 1) echo "checked='checked'"; ?> name="set4" /><label>Отображать в профиле </label>
		<input id="submit" type="submit" name="send" value="Сохранить" />
	</form>		
</div>	


<div id="edge" class="button">
	<h1>Номер Вашего телефона</h1>
    <form action="php/change_phone.php" method="post">
		<label>Телефон: </label><input type="text" name="phone" maxlength="30" value="<?php if (isset($ep)) echo $ep["txtPhone"]; ?>" placeholder="Телефон"/><br />
		<input type="checkbox" <?php if ($settings[4] == 1) echo "checked='checked'"; ?> name="set5" /><label>Отображать в профиле </label>
		<input id="submit" type="submit" name="send" value="Сохранить" />
	</form>		
</div>


<div id="edge" class="button">
	<h1>Рассылка оповещений на электронную почту</h1>
	<form action="php/change_notification_mail.php" method="post">
		<input type="checkbox" name="set1" <?php if ($settings[0] == 1) echo "checked='checked'"; ?>> <label>Новое объявление или новость</label><br />
		<input type="checkbox" name="set2" <?php if ($settings[1] == 1) echo "checked='checked'"; ?>> <label>Новое сообщение</label><br />
		<input type="checkbox" name="set3" <?php if ($settings[2] == 1) echo "checked='checked'"; ?>> <label>Новое оповещение на сайте</label><br />
		<input id="submit" type="submit" name="send" value="Сохранить" />
	</form>	

</div>  


  
<?php include_once("templ/block_footer.php"); ?>	