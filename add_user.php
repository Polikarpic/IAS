<?php
 

	session_start();  
	
	if ($_SESSION["statusId"] == 0) header("Location: ./home");

	$title = "Добавить пользователя";
	$current_menu1 = "current-menu-item current_page_item";
	
	$js = '<script src="http://code.jquery.com/jquery-latest.js"></script>
	<script type="text/javascript" src="js/passGenerator.js"></script>
	<script type="text/javascript" src="js/onlyDig.js"></script>	
	<script type="text/javascript" src="js/checkbox.js"></script>';
	include_once("templ/block_header.php"); 	 
	
	#получаем список направлений
	$direction = mysql_query("SELECT * FROM tblDirection");
	
	#получаем список направлений
	$chair = mysql_query("SELECT * FROM tblChair");
	 
	
?>
<div id="edge" class="button">
			<form action="php/add_user.php" method="post">
				<label>Логин: </label><input type="text" name="login" maxlength="30" required="" placeholder="Логин"/><br />
				<label>Пароль: </label><input type="text" id="pass" maxlength="30" name="pass" required="" placeholder="Пароль"/><img src="images/generate.png" title="Генерировать пароль" onclick="document.getElementById('pass').value = generatePassword();" style="cursor: pointer; margin-left: 5px;"><br />
				<label>Группа: </label>
				<select name="status" id="sid" onchange="Selected(this);">
					<option value="0">Студент</option>
					<option value="1">Преподаватель</option>
					<option value="2">Методист кафедр</option>
					<option value="3">Руководство факультета и кафедры</option>
				</select><br />
				
				<label>Фамилия: </label><input name="surname" name="name" maxlength="30" type="text" required="" placeholder="Фамилия"/><br />
				<label>Имя: </label><input name="name"  type="text" maxlength="30" required="" placeholder="Имя"/><br />
				<label>Отчество: </label><input name="secondname" maxlength="30" type="text" required="" placeholder="Отчество"/><br />			
			
			<div id="teacher" style="display: none;">
				<label>Кафедра: </label> 
				<select name="chair">
				<?php
				while($data = mysql_fetch_array($chair))
				{
					echo '<option value="'.$data["intChairId"].'">'.$data["txtChairName"].'</option>';			
				}
				?>
				</select><br />
			</div>
				
			<div id="student" style="display: block;">
				<label>Направление: </label> 
				<select name="direction">
				<?php
				while($data = mysql_fetch_array($direction))
				{
					echo '<option value="'.$data["intDirectionId"].'">'.$data["txtDirectionName"].'</option>';			
				}
				?>
				</select><br />				
				<label>Курс: </label><input type="text" name="course" maxlength="1" placeholder="Курс" value="1" onkeyup="return digC(this);" onchange="return digC(this);" /><br />
				<label>Группа: </label><input type="text" name="group" maxlength="6" placeholder="Группа" value="000001" onkeyup="return digC(this);" onchange="return digC(this);" /><br />			
			</div>
			
				<input id="submit" type="submit" value="Добавить" />
				</form>
</div>
<?php include_once("templ/block_footer.php"); ?>	