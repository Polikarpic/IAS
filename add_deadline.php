<?php
 
	include_once("php/db.php");
	session_start(); 
	
	#доступ 
	if ($_SESSION["statusId"] != 2 && $_SESSION["statusId"] != 3){ $_SESSION["um"] = 'e0'; header("Location: ./../home"); exit(); }
	
	$title = "Установить сроки сдачи работ";
	$current_menu1 = "current-menu-item current_page_item";	
	$js = '<link rel="stylesheet" type="text/css" href="css/latest.css" />
<script type="text/javascript" src="js/jquery.1.4.2.js"></script>
<script type="text/javascript" src="js/latest.js"></script>';
	include_once("templ/block_header.php"); 	
	
	#сообщения для пользователя
	um();
	
	#получаем крайние сроки сдачи отчёта и работы
	$query_deadlines = mysql_query("SELECT * FROM tblDeadlines ORDER BY intDeadlinesId DESC LIMIT 1");
	$deadlines = mysql_fetch_array($query_deadlines);
	
	#получаем крайние сроки сдачи отчёта и работы для всех работ и тем
	$query_works = mysql_query("SELECT intWorkId, txtTopic, boolType FROM tblWork WHERE intWorkStatus='0' and txtStudentId is not null ORDER BY intWorkId");
	
?>
<div id="edge" class="button">	
<p><b>Внимание! Сроки сдачи работ необходимо устанавливать ТОЛЬКО в начале нового учебного года.<br /></p>
<p>После установки новых сроков сдачи, все текущие выполняемые работы будут закрыты и перемещены в архив.</b></p>
<h1>Сроки сдачи КР</h1>
			<form action="php/add_deadline.php" method="post">
			<p>Текущий крайний срок сдачи отчёта: <b><?php echo $deadlines["dateRentingReport"]; ?></b></p>
			<p>Текущий крайний срок сдачи работы: <b><?php echo $deadlines["dateRentingWork"]; ?></b></p>
			<label>Крайний срок сдачи отчёта: </label><input type="text" class="datepickerTimeField" required="" name="deadline1" maxlength="10"/><br />
			<label>Крайний срок сдачи работы: </label><input type="text" class="datepickerTimeField" required="" name="deadline2" maxlength="10"/><br />

<h1>Сроки сдачи ВКР</h1>
			<p>Текущий крайний срок сдачи отчёта: <b><?php echo $deadlines["dateRentingReport"]; ?></b></p>
			<p>Текущий крайний срок сдачи работы: <b><?php echo $deadlines["dateRentingWork"]; ?></b></p>
			<label>Крайний срок сдачи отчёта: </label><input type="text" class="datepickerTimeField" required="" name="deadline3" maxlength="10"/><br />
			<label>Крайний срок сдачи работы: </label><input type="text" class="datepickerTimeField" required="" name="deadline4" maxlength="10"/><br />
			
			<input id="submit" type="submit" name="send" value="Установить" />
			</form>
</div>

<div id="edge">
<h1>Сроки сдачи для конкретной работы</h1>
<label>Выберите работу: </label> 
	<select name="users" id="filter_deadline" onchange="filter_func();">
		<option value="0">Выберите работу</option>
		<?php
		while($data = mysql_fetch_array($query_works))
		{
			if ($data["boolType"] == 0) $type_work = "КР";
			else $type_work = "ВКР";

			echo '<option value="'.$data["intWorkId"].'">['.$type_work.'] '.$data["txtTopic"].'</option>';			
		}
		?>
	</select>
</div>

<?php include_once("templ/block_footer.php"); ?>	