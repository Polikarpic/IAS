<?php 
include_once("./../db.php");
session_start();	

#получаем список направлений
$query = mysql_query("SELECT * FROM tblUsers WHERE intUserId='".safe_var($_GET["z"])."' LIMIT 1");	
$data = mysql_fetch_array($query);

#получаем список направлений
$chair = mysql_query("SELECT * FROM tblChair");
	
#получаем список направлений
$direction = mysql_query("SELECT * FROM tblDirection");

?>


<div id="edge" class="button">
			<form action="php/edit_user.php" method="post">
				<label>Логин: </label><input type="text" name="login" maxlength="30" required="" value="<?php echo $data["txtLogin"]; ?>" placeholder="Логин"/><br />
				<label>Новый пароль: </label><span class="jQtooltip" title="<?php echo $hint_pass; ?>"><input type="text" id="pass" maxlength="30" name="pass" placeholder="Пароль"/><img src="images/generate.png" title="Генерировать пароль" onclick="document.getElementById('pass').value = generatePassword();" style="cursor: pointer; margin-left: 5px;"></span><br />
				<label>Группа: </label>
				<select name="status" id="sid" onchange="Selected(this);">
					<option <?php if ($data["intStatusId"] == 0) echo 'selected="selected"'; ?> value="0">Студент</option>
					<option <?php if ($data["intStatusId"] == 1) echo 'selected="selected"'; ?>  value="1">Преподаватель</option>
					<option <?php if ($data["intStatusId"] == 2) echo 'selected="selected"'; ?>  value="2">Методист кафедр</option>
					<option <?php if ($data["intStatusId"] == 3) echo 'selected="selected"'; ?>  value="3">Руководство факультета и кафедры</option>
				</select><br />
				
				<label>Фамилия: </label><input name="surname" name="name" value="<?php echo $data["txtSurname"]; ?>" maxlength="30" type="text" required="" placeholder="Фамилия"/><br />
				<label>Имя: </label><input name="name"  type="text" maxlength="30" value="<?php echo $data["txtName"]; ?>" required="" placeholder="Имя"/><br />
				<label>Отчество: </label><input name="secondname" maxlength="30" type="text" required="" value="<?php echo $data["txtSecondName"]; ?>" placeholder="Отчество"/><br />			
			
			<div id="teacher" style="<?php if ($data["intStatusId"] == 0) echo 'display: none;'; else echo 'display: block;'; ?>">
				<label>Кафедра: </label> 
				<select name="chair">
				<?php
				while($data_c = mysql_fetch_array($chair))
				{
					if ($data["intChairId"] == $data_c["intChairId"]) $selected = 'selected="selected"';
					else $selected = '';
					echo '<option '.$selected.' value="'.$data_c["intChairId"].'">'.$data_c["txtChairName"].'</option>';			
				}
				?>
				</select><br />
			</div>
				
			<div id="student" style="<?php if ($data["intStatusId"] != 0) echo 'display: none;'; else echo 'display: block;'; ?>">
				<label>Направление: </label> 
				<select name="direction">
				<?php
				while($data_d = mysql_fetch_array($direction))
				{
					if ($data["intDirectionId"] == $data_d["intDirectionId"]) $selected = 'selected="selected"';
					else $selected = '';
					echo '<option '.$selected.' value="'.$data_d["intDirectionId"].'">'.$data_d["txtDirectionName"].'</option>';			
				}
				?>
				</select><br />				
				<label>Курс:</label>
				<select name="course">
					<option <?php if ($data["txtCourse"] == 1) echo 'selected="selected"'; ?> value="1">1</option>
					<option <?php if ($data["txtCourse"] == 2) echo 'selected="selected"'; ?> value="2">2</option>
					<option <?php if ($data["txtCourse"] == 3) echo 'selected="selected"'; ?> value="3">3</option>
					<option <?php if ($data["txtCourse"] == 4) echo 'selected="selected"'; ?> value="4">4</option>
					<option <?php if ($data["txtCourse"] == 5) echo 'selected="selected"'; ?> value="5">5</option>
					<option <?php if ($data["txtCourse"] == 6) echo 'selected="selected"'; ?> value="6">6</option>
				</select><br />
				<label>Группа: </label><input type="text" name="group" value="<?php echo $data["txtGroup"]; ?>" maxlength="6" placeholder="Группа" /><br />			
			</div>
				<input type="hidden" name="intUserId" value="<?php echo safe_var($_GET["z"]) ?>">
				<input id="submit" type="submit" value="Сохранить" />
				</form>
</div>

<div id="edge" class="button"><input id="submit" type="button" onclick="if (confirm('Вы действительно хотите удалить пользователя? \nЭТОТ ПРОЦЕСС НЕВОЗМОЖНО ОБРАТИТЬ.')) location.href='php/remove_user.php?z=<?php echo safe_var($_GET["z"]); ?>'" value="Удалить пользователя" />	</div>
