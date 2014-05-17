<?php
 

	session_start();  
	
	if ($_SESSION["statusId"] == 0){ $_SESSION["um"] = 'e0'; header("Location: home"); exit();}

	$title = "Редактировать пользователя";
	$current_menu1 = "current-menu-item current_page_item";
	$js = '<script src="http://code.jquery.com/jquery-latest.js"></script>
	<script type="text/javascript" src="js/passGenerator.js"></script>
	<script type="text/javascript" src="js/onlyDig.js"></script>	
	<script type="text/javascript" src="js/checkbox.js"></script>
	<script type="text/javascript" src="js/jQuery_load.js"></script>
	<script type="text/javascript" src="js/tooltip.js"></script>
	<link rel="stylesheet" type="text/css" href="css/tooltip.css" />';
	include_once("templ/block_header.php"); 	 
	
	#получаем список направлений
	$users = mysql_query("SELECT * FROM tblUsers ORDER BY intStatusId, intUserId");	
	
	#подсказка для изменения пароля
	$hint_pass = "Если Вы <b>не хотите</b> изменять пароль пользователя, оставьте это поле пустым";

	#сообщения для пользователя
	um();
	
?>


<label>Пользователь: </label> 
	<select name="users" id="filter_user" onchange="filter_func();">
		<?php
		while($data = mysql_fetch_array($users))
		{
			if ($data["intStatusId"] == 0) $group = "Студент";
			elseif ($data["intStatusId"] == 1) $group = "Преподаватель";
			elseif ($data["intStatusId"] == 2) $group = "Методист кафедр";
			elseif ($data["intStatusId"] == 3) $group = "Руководство факультета и кафедры";
			echo '<option value="'.$data["intUserId"].'">['.$group.'] '.$data["txtSurname"].' '.$data["txtName"].' '.$data["txtSecondName"].'</option>';			
		}
		?>
	</select><br /><br />

	
	<div id="edit_user">
		<?php
			$_GET["z"] = 1;
			include_once("php/work_filter/edit_user.php"); 
		?>
	</div>
	
<?php include_once("templ/block_footer.php"); ?>	