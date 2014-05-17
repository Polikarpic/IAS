<?php
 
	include_once("php/db.php");
	session_start(); 
	if ($_SESSION["statusId"] == 0){ $_SESSION["um"] = 'e0'; header("Location: home"); exit();}
	
	$title = "Добавить тему";
	$current_menu1 = "current-menu-item current_page_item";
	$js = '<script type="text/javascript" src="js/maxchars.js"></script>	
	<script type="text/javascript" src="js/onlyDig.js"></script>';
	include_once("templ/block_header.php"); 	

	#получаем список направлений
	$direction = mysql_query("SELECT * FROM tblDirection");
	$direction2 = mysql_query("SELECT * FROM tblDirection");


	
	
?>
<div id="edge" class="button">
			<form enctype="multipart/form-data" action="php/add_topic.php" method="post">
			<label>Тип работы: </label>
				<select name="workType">
					<option value="0">Курсовая работа</option>
					<option value="1">ВКР</option>
				</select><br />
			<label>Тема: </label><input type="text" required="" name="theme" maxlength="256" placeholder="Тема" onkeyup="this.value=this.value.replace(/^\s*/,'');"/><br />
			<label>Описание: </label><div style="float:right; margin-right: 33px;">Осталось символов:<span id='count'>1024</span></div><br />
			<textarea maxlength="1024" name="comment" onkeypress="counter(this);" onkeyup="counter(this);" onchange="counter(this);" placeholder="Описание"></textarea><br />
			<label>Подробное описание: </label>  <input type="file" name="additional_comment"><br />			
			<label>Курс:</label>
			<select name="course">
				<option value="1">1</option>
				<option value="2">2</option>
				<option value="3">3</option>
				<option value="4">4</option>
				<option value="5">5</option>
				<option value="6">6</option>
			</select><br />
			<label>Направление: </label> 
			<select name="direction">
			<?php
			while($data = mysql_fetch_array($direction))
			{
				echo '<option value="'.$data["intDirectionId"].'">'.$data["txtDirectionName"].'</option>';			
			}
			?>
			</select><br />  
			<label>Дополнительное направление: </label> 
			<select name="direction2">
				<option value="0">Нет</option>
			<?php
			while($data = mysql_fetch_array($direction2))
			{
				echo '<option value="'.$data["intDirectionId"].'">'.$data["txtDirectionName"].'</option>';			
			}
			?>
			</select><br />  			
			<label>Количество студентов: </label>
			<select name="nop">
				<option value="1">1</option>
				<option value="2">2</option>
				<option value="3">3</option>
				<option value="4">4</option>
				<option value="5">5</option>
				<option value="6">6</option>
				<option value="7">7</option>
				<option value="8">8</option>
				<option value="9">9</option>
				<option value="10">10</option>
			</select><br />			
			<input id="submit" type="submit" name="send" value="Добавить тему" />
			</form>
</div>
<?php include_once("templ/block_footer.php"); ?>	