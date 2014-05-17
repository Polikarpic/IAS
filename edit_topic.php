<?php
 

	include_once("php/db.php");
	session_start(); 
	
	#доступ
	if ($_SESSION["statusId"] == 0){ $_SESSION["um"] = 'e0'; header("Location: home"); exit();}

	
	$query = mysql_query("SELECT * FROM tblWork WHERE intWorkId='".safe_var($_GET["z"])."' LIMIT 1");
	$topic = mysql_fetch_array($query);
	
	if (mysql_num_rows($query) == 0 && ($_SESSION["statusId"] != 2 && $_SESSION["chairId"] == $topic["intChairId"])){ header("Location: ./home?edit_topic=fail"); exit();}
			
	#получаем информацию о подробном описании работы
	$query = mysql_query("SELECT txtName, txtLink FROM tblReview WHERE intReviewId='".$topic["intAdditionalComment"]."' and boolType='0' LIMIT 1");
	$material =	mysql_fetch_array($query);	
	
	$title = "Редактировать тему";
	$current_menu1 = "current-menu-item current_page_item";
	$js = '<script type="text/javascript" src="js/maxchars.js"></script>
	<script type="text/javascript" src="js/onlyDig.js"></script>';
	include_once("templ/block_header.php"); 

	#получаем список направлений
	$direction = mysql_query("SELECT * FROM tblDirection");
	$direction2 = mysql_query("SELECT * FROM tblDirection");
	
?>
<div id="edge" class="button">
				<form enctype="multipart/form-data" action="php/edit_topic.php" method="post">
			<label>Тип работы: </label>
				<select name="workType">					
					<option value="0" <?php if ($topic["boolType"] == 0) echo 'selected="selected"'; ?>>Курсовая работа</option>
					<option value="1" <?php if ($topic["boolType"] == 1) echo 'selected="selected"'; ?>>ВКР</option>
				</select><br />
			<label>Тема: </label><input type="text" required="" name="theme" maxlength="256" placeholder="Тема" onkeyup="this.value=this.value.replace(/^\s*/,'');" value="<?php echo $topic["txtTopic"]; ?>"/><br />
			<label>Описание: </label><div style="float:right; margin-right: 33px;">Осталось символов:<span id='count'><?php if (isset($topic["txtComment"])) echo (1024 - mb_strlen($topic["txtComment"],'UTF-8')); else '1024'; ?></span></div><br />
			<textarea maxlength="1024" name="comment" onkeypress="counter(this);" onkeyup="counter(this);" onchange="counter(this);" placeholder="Описание"><?php echo $topic["txtComment"]; ?></textarea><br />
			<label>Подробное описание: </label> <?php if (isset($material["txtName"])) echo '<a href="'.$material["txtLink"].'">'.$material["txtName"].'</a>'; ?> <input type="file" name="additional_comment"><br />			
			<label>Курс:</label>
			<select name="course">
				<option value="1" <?php if ($topic["txtCourse"] == 1) echo 'selected="selected"'; ?>>1</option>
				<option value="2" <?php if ($topic["txtCourse"] == 2) echo 'selected="selected"'; ?>>2</option>
				<option value="3" <?php if ($topic["txtCourse"] == 3) echo 'selected="selected"'; ?>>3</option>
				<option value="4" <?php if ($topic["txtCourse"] == 4) echo 'selected="selected"'; ?>>4</option>
				<option value="5" <?php if ($topic["txtCourse"] == 5) echo 'selected="selected"'; ?>>5</option>
				<option value="6" <?php if ($topic["txtCourse"] == 6) echo 'selected="selected"'; ?>>6</option>
			</select><br />
			<label>Направление: </label> 
			<select name="direction">
			<?php
			$i = 1;
			while($data = mysql_fetch_array($direction))
			{
				if ($topic["intDirectionId"] == $i) $selected = 'selected="selected"';
				else $selected = '';
				echo '<option value="'.$data["intDirectionId"].'" '.$selected.'>'.$data["txtDirectionName"].'</option>';			
				$i++;
			}
			?>
			</select><br />  
			<label>Дополнительное направление: </label> 
			<select name="direction2">
				<option value="0">Нет</option>
			<?php
			$i = 1;
			while($data = mysql_fetch_array($direction2))
			{
				if ($topic["intDirectionId2"] == $i) $selected = 'selected="selected"';
				else $selected = '';
				echo '<option value="'.$data["intDirectionId"].'" '.$selected.'>'.$data["txtDirectionName"].'</option>';			
				$i++;
			}
			?>
			</select><br />  
			<label>Количество студентов: </label>
			<select name="nop">
				<option value="1" <?php if ($topic["txtNumber_of_persons"] == 1) echo 'selected="selected"'; ?>>1</option>
				<option value="2" <?php if ($topic["txtNumber_of_persons"] == 2) echo 'selected="selected"'; ?>>2</option>
				<option value="3" <?php if ($topic["txtNumber_of_persons"] == 3) echo 'selected="selected"'; ?>>3</option>
				<option value="4" <?php if ($topic["txtNumber_of_persons"] == 4) echo 'selected="selected"'; ?>>4</option>
				<option value="5" <?php if ($topic["txtNumber_of_persons"] == 5) echo 'selected="selected"'; ?>>5</option>
				<option value="6" <?php if ($topic["txtNumber_of_persons"] == 6) echo 'selected="selected"'; ?>>6</option>
				<option value="7" <?php if ($topic["txtNumber_of_persons"] == 7) echo 'selected="selected"'; ?>>7</option>
				<option value="8" <?php if ($topic["txtNumber_of_persons"] == 8) echo 'selected="selected"'; ?>>8</option>
				<option value="9" <?php if ($topic["txtNumber_of_persons"] == 9) echo 'selected="selected"'; ?>>9</option>
				<option value="10" <?php if ($topic["txtNumber_of_persons"] == 10) echo 'selected="selected"'; ?>>10</option>
			</select><br />
			<input type="hidden" name="intWorkId" value="<?php echo safe_var($_GET["z"]) ?>">
			<input id="submit" type="submit" name="send" value="Редактировать тему" />
			</form>
</div>
<?php include_once("templ/block_footer.php"); ?>	