<?php
 

	include_once("php/db.php");
	session_start(); 
	
	if ($_SESSION["statusId"] != 1) { header("Location: ./home.php"); exit();}
	
	#проверяем есть ли у преподавателя право на редактирование темы
	$query = mysql_query("SELECT * FROM tblWork WHERE intWorkId='".safe_var($_GET["z"])."' and intTeacherId='".$_SESSION["userId"]."' LIMIT 1");
	
	if (mysql_num_rows($query) == 0){ header("Location: ./home.php?edit_topic=fail"); exit();}
	
	$topic = mysql_fetch_array($query);	
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
	
?>
<div id="edge" class="button">
				<form enctype="multipart/form-data" action="php/edit_topic.php" method="post">
			<label>Тип работы: </label>
				<select name="workType">					
					<option value="0" <?php if ($topic["boolType"] == 0) echo 'selected="selected"'; ?>>Курсовая работа</option>
					<option value="1" <?php if ($topic["boolType"] == 1) echo 'selected="selected"'; ?>>ВКР</option>
				</select><br />
			<label>Тема: </label><input type="text" required="" name="theme" maxlength="256" placeholder="Тема" value="<?php echo $topic["txtTopic"]; ?>"/><br />
			<label>Описание: </label><div style="float:right; margin-right: 33px;">Осталось символов:<span id='count'><?php if (isset($topic["txtComment"])) echo (1024 - mb_strlen($topic["txtComment"],'UTF-8')); else '1024'; ?></span></div><br />
			<textarea maxlength="1024" name="comment" onkeypress="counter(this);" onkeyup="counter(this);" onchange="counter(this);" placeholder="Описание"><?php echo $topic["txtComment"]; ?></textarea><br />
			<label>Подробное описание: </label>  <input type="file" name="additional_comment"><br />			
			<label>Курс: </label><input type="text" name="course" maxlength="1" placeholder="Курс" value="<?php echo $topic["txtCourse"]; ?>" onkeyup="return digC(this);" onchange="return digC(this);" /><br />
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
			</select>  
			<label>Количество студентов: </label><input type="text" maxlength="1" name="nop" value="<?php echo $topic["txtNumber_of_persons"] ?>" onkeyup="return dig(this);" onchange="return dig(this);" placeholder="Количество студентов"/><br />
			<input type="hidden" name="intWorkId" value="<?php echo safe_var($_GET["z"]) ?>">
			<input id="submit" type="submit" name="send" value="Редактировать тему" />
			</form>
</div>
<?php include_once("templ/block_footer.php"); ?>	