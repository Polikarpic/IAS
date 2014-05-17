<?php
 

	include_once("db.php");
	session_start();
	
	#доступ
	if ($_SESSION["statusId"] == 0){ $_SESSION["um"] = 'e0'; header("Location: home"); exit();}

	
	$title = "Добавить новость";
	$current_menu1 = "current-menu-item current_page_item";
	$js = '<script src="http://code.jquery.com/jquery-latest.js"></script>
	<script type="text/javascript" src="js/passGenerator.js"></script>
	<script type="text/javascript" src="js/checkbox.js"></script>
	<script type="text/javascript" src="js/maxchars.js"></script>';

	include_once("templ/block_header.php"); 
	
	#сообщения для пользователя
	um();
	
	#получаем список направлений
	$direction = mysql_query("SELECT * FROM tblDirection");
	
	#получаем список направлений
	$chair = mysql_query("SELECT * FROM tblChair");
	 
?>
<div id="edge" class="button">
			<form action="php/add_news.php" method="post">
			<label>Группа читателей: </label>
			<select name="group" id="sid" onchange="SelectedN(this);">
				<option value="NULL">Все группы</option>
				<option value="0">Студенты</option>
				<option value="1">Преподаватели</option>
				<option value="2">Методисты кафедр</option>
				<option value="3">Руководство факультета и кафедры</option>
			</select><br />
			<div id="teacher" style="display: block;">
			<label>Кафедра: </label> 
			<select name="chair">
			<option value="NULL">Все кафедры</option>
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
			<option value="NULL">Все направления</option>
			<?php
			while($data = mysql_fetch_array($direction))
			{
				echo '<option value="'.$data["intDirectionId"].'">'.$data["txtDirectionName"].'</option>';			
			}
			?>
			</select><br />
			<label>Курс: </label>
			<select name="course">
				<option value="NULL">Все курсы</option>
				<option value="1">1</option>
				<option value="2">2</option>
				<option value="3">3</option>
				<option value="4">4</option>
				<option value="5">5</option>
				<option value="6">6</option>
			</select><br />
			</div>
			<label>Текст новости: </label><div style="float:right; margin-right: 33px;">Осталось символов:<span id='count'>1024</span></div><br />
			<textarea required="" onkeypress="counter(this);" onkeyup="counter(this);" onchange="counter(this);" maxlength="1024" name="news" placeholder="Новость"></textarea><br />
			<input id="submit" type="submit" name="send" value="Добавить" />
			</form>			
</div>
<?php include_once("templ/block_footer.php"); ?>	