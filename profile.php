<?php
 

	include_once("php/db.php");
	session_start(); 

	$title = "Профиль";
	$current_menu2 = "current-menu-item current_page_item";
	include_once("templ/block_header.php"); 
	
	#сообщения для пользователя
	um();
	
	#передан ли номер пользователя
	if (isset($_GET["z"]))
	{
		$id = safe_var($_GET["z"]);
		#получаем информацию о пользователе
		$query = mysql_query("
		SELECT u.intUserId, u.txtName, u.txtSecondName, u.txtSurname, u.txtCourse, u.intStatusId, c.txtChairName, d.txtDirectionName, u.txtMail, u.txtPhone, u.txtLogin
		FROM tblUsers u
		LEFT JOIN tblChair c ON c.intChairId = u.intChairId
		LEFT JOIN tblDirection d ON d.intDirectionId = u.intDirectionId
        WHERE intUserId='".$id."'
		");
		
		#если запрос ничего не вернул, выводим свой профиль
		if (mysql_num_rows($query) == 0)
		{
			#получаем информацию о себе
			$query = mysql_query("
			SELECT u.intUserId, u.txtName, u.txtSecondName, u.txtSurname, u.txtCourse, u.intStatusId, c.txtChairName, d.txtDirectionName, u.txtMail, u.txtPhone, u.txtLogin
			FROM tblUsers u
			LEFT JOIN tblChair c ON c.intChairId = u.intChairId
			LEFT JOIN tblDirection d ON d.intDirectionId = u.intDirectionId			
			WHERE intUserId='".$_SESSION["userId"]."'
			");
			$info = mysql_fetch_array($query);
			$self = true;
			
			#получаем настройки пользователя(отображать email и phone или нет)
			$uSettings = get_user_settings('NULL',$_SESSION["userId"]);
		} 
		else #выводим профиль id
		{
			#получаем информацию о пользователе с id
			$info = mysql_fetch_array($query);
			
			#получаем настройки пользователя(отображать email и phone или нет)
			$uSettings = get_user_settings('NULL',safe_var($_GET["z"]));
		}
	} 
	else #id пользователя не передан 
	{
		#вывод информации о себе
		$query = mysql_query("
		SELECT u.intUserId, u.txtName, u.txtSecondName, u.txtSurname, u.txtCourse, u.intStatusId, c.txtChairName, d.txtDirectionName, u.txtMail, u.txtPhone, u.txtLogin
		FROM tblUsers u
		LEFT JOIN tblChair c ON c.intChairId = u.intChairId
		LEFT JOIN tblDirection d ON d.intDirectionId = u.intDirectionId
		WHERE intUserId='".$_SESSION["userId"]."'
		");
		$info = mysql_fetch_assoc($query);		
		$self = true;
		
		#получаем настройки пользователя(отображать email и phone или нет)
		$uSettings = get_user_settings('NULL',$_SESSION["userId"]);
	}
	
	if ($info["intStatusId"] == 0) $group = "Студент";
	elseif ($info["intStatusId"] == 1) $group = "Преподаватель";
	elseif ($info["intStatusId"] == 2) $group = "Методист кафедр";
	elseif ($info["intStatusId"] == 3) $group = "Руководство факультета и кафедры";
	
	
	

?>	
	
	<div id="edge">	
		<h1>Информация о пользователе</h1>
		<p>Группа: <b><?php echo $group; ?></b></p>
		<p>Фамилия: <b><?php echo $info["txtSurname"]; ?></b></p>
		<p>Имя: <b><?php echo $info["txtName"]; ?></b></p>
		<p>Отчество: <b><?php echo $info["txtSecondName"]; ?></b></p>	
		<?php if ($info["intStatusId"] == 0 && !empty($info["txtCourse"]))  echo '<p>Курс: <b>'.$info["txtCourse"].'</b></p>'; ?>
		<?php if ($info["intStatusId"] == 0 && !empty($info["txtDirectionName"])) echo '<p>Направление: <b>'.$info["txtDirectionName"].'</b></p>'; ?>
		<?php if ($info["intStatusId"] != 0) echo '<p>Кафедра: <b>'.$info["txtChairName"].'</b></p>'; ?>
	</div>	
	
	<?php 
	if (($uSettings[3] != 0 && !empty($info["txtMail"])) || ($uSettings[4] != 0 && !empty($info["txtPhone"])))
	{
		echo '<div id="edge">';
		echo '<h1>Контактная информация</h1>';
		if ($uSettings[3] != 0 && !empty($info["txtMail"])) echo '<p>Адрес электронной почты: <b>'.$info["txtMail"].'</b></p>';
		if ($uSettings[4] != 0 && !empty($info["txtPhone"])) echo '<p>Номер телефона: <b>'.$info["txtPhone"].'</b></p>';
		echo '</div>';
	}
	?>

<?php	
		 
	
	if (!$self) echo '<input id="submit" type="button" name="new_message" onclick="location.href=\'new_message?u='.$info["txtLogin"].'\'" value="Написать сообщение" />';
	include_once("templ/block_footer.php"); 
?>	


