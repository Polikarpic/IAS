<?php
 
	include_once("php/db.php");
	session_start(); 
	if ($_SESSION["statusId"] != 1) header("Location: ./../home.php");
	
	$title = "Добавить тему";
	$current_menu1 = "current-menu-item current_page_item";
	$js = '<script type="text/javascript" src="js/maxchars.js"></script>	
	<script type="text/javascript" src="js/onlyDig.js"></script>';
	include_once("templ/block_header.php"); 	

	#получаем список направлений
	$direction = mysql_query("SELECT * FROM tblDirection");
	
	
?>
<div id="edge" class="button">
			<form enctype="multipart/form-data" action="php/add_topic.php" method="post">
			<label>Тип работы: </label>
				<select name="workType">
					<option value="0">Курсовая работа</option>
					<option value="1">ВКР</option>
				</select><br />
			<label>Тема: </label><input type="text" required="" name="theme" maxlength="256" placeholder="Тема"/><br />
			<label>Описание: </label><div style="float:right; margin-right: 33px;">Осталось символов:<span id='count'>1024</span></div><br />
			<textarea maxlength="1024" name="comment" onkeypress="counter(this);" onkeyup="counter(this);" onchange="counter(this);" placeholder="Описание"></textarea><br />
			<label>Подробное описание: </label>  <input type="file" name="additional_comment"><br />			
			<label>Курс: </label><input type="text" name="course" maxlength="1" placeholder="Курс" value="1" onkeyup="return digC(this);" onchange="return digC(this);" /><br />
			<label>Направление: </label> 
			<select name="direction">
			<?php
			while($data = mysql_fetch_array($direction))
			{
				echo '<option value="'.$data["intDirectionId"].'">'.$data["txtDirectionName"].'</option>';			
			}
			?>
			</select>  
			<label>Количество студентов: </label><input type="text" maxlength="1" name="nop" value="1" onkeyup="return dig(this);" onchange="return dig(this);" placeholder="Количество студентов"/><br />
			<input id="submit" type="submit" name="send" value="Добавить тему" />
			</form>
</div>
<?php include_once("templ/block_footer.php"); ?>	