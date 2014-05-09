<?php
 

	include_once("php/db.php");
	session_start(); 

	$title = "Профиль";
	$current_menu2 = "current-menu-item current_page_item";
	include_once("templ/block_header.php"); 
	
	#передан ли номер пользователя
	if (isset($_GET["z"]))
	{
		$id = safe_var($_GET["z"]);
		#получаем информацию о пользователе
		$query = mysql_query("
		SELECT u.intUserId, u.txtName, u.txtSecondName, u.txtSurname, u.txtCourse, u.intStatusId, c.txtChairName, d.txtDirectionName
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
			SELECT u.intUserId, u.txtName, u.txtSecondName, u.txtSurname, u.txtCourse, u.intStatusId, c.txtChairName, d.txtDirectionName
			FROM tblUsers u
			LEFT JOIN tblChair c ON c.intChairId = u.intChairId
			LEFT JOIN tblDirection d ON d.intDirectionId = u.intDirectionId			
			WHERE intUserId='".$_SESSION["userId"]."'
			");
			$info = mysql_fetch_array($query);
			$self = true;
		} 
		else #выводим профиль id
		{
		#получаем информацию о пользователе с id
		$info = mysql_fetch_array($query);
		}
	} 
	else #id пользователя не передан 
	{
		#вывод информации о себе
		$query = mysql_query("
		SELECT u.intUserId, u.txtName, u.txtSecondName, u.txtSurname, u.txtCourse, u.intStatusId, c.txtChairName, d.txtDirectionName
		FROM tblUsers u
		LEFT JOIN tblChair c ON c.intChairId = u.intChairId
		LEFT JOIN tblDirection d ON d.intDirectionId = u.intDirectionId
		WHERE intUserId='".$_SESSION["userId"]."'
		");
		$info = mysql_fetch_assoc($query);		
		$self = true;
	}
	
	if ($info["intStatusId"] == 0) $group = "Студент";
	elseif ($info["intStatusId"] == 1) $group = "Преподаватель";
	elseif ($info["intStatusId"] == 2) $group = "Методист кафедр";
	elseif ($info["intStatusId"] == 3) $group = "Руководство факультета и кафедры";

?>	
	
	<div id="edge">		
		<p>Группа: <i><?php echo $group; ?></i></p>
		<p>Фамилия: <i><?php echo $info["txtSurname"]; ?></i></p>
		<p>Имя: <i><?php echo $info["txtName"]; ?></i></p>
		<p>Отчество: <i><?php echo $info["txtSecondName"]; ?></i></p>	
		<?php if ($info["intStatusId"] == 0 && !empty($info["txtCourse"]))  echo '<p>Курс: <i>'.$info["txtCourse"].'</i></p>'; ?>
		<?php if ($info["intStatusId"] == 0 && !empty($info["txtDirectionName"])) echo '<p>Направление: <i>'.$info["txtDirectionName"].'</i></p>'; ?>
		<?php if ($info["intStatusId"] != 0) echo '<p>Кафедра: <i>'.$info["txtChairName"].'</i></p>'; ?>
	</div>	

<?php	
		 
	
	if (!$self) echo '<input id="submit" type="button" name="new_message" onclick="location.href=\'new_message?u='.$info["txtLogin"].'\'" value="Написать сообщение" />';
	include_once("templ/block_footer.php"); 
?>	


