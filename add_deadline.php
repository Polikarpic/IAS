<?php
 
	include_once("php/db.php");
	session_start(); 
	if ($_SESSION["statusId"] != 3) header("Location: ./../home");
	
	$title = "Установить сроки сдачи работ";
	$current_menu1 = "current-menu-item current_page_item";	
	$js = '<link rel="stylesheet" type="text/css" href="css/latest.css" />
<script type="text/javascript" src="js/jquery.1.4.2.js"></script>
<script type="text/javascript" src="js/latest.js"></script>';
	include_once("templ/block_header.php"); 	
	
	#получаем крайние сроки сдачи отчёта и работы
	$query_deadlines = mysql_query("SELECT * FROM tblDeadlines ORDER BY intDeadlinesId DESC LIMIT 1");
	$deadlines = mysql_fetch_array($query_deadlines);
	
?>
<div id="edge" class="button">
			<form action="php/add_deadline.php" method="post">
			<p><b>Внимание! Сроки сдачи работ необходимо устанавливать ТОЛЬКО в начале нового учебного года.
			Все выполняемые работы, используют эту информацию.</b></p>
			<p>Текущий крайний срок сдачи отчёта: <b><?php echo $deadlines["dateRentingReport"]; ?></b></p>
			<p>Текущий крайний срок сдачи работы: <b><?php echo $deadlines["dateRentingWork"]; ?></b></p>
			<label>Крайний срок сдачи отчёта: </label><input type="text" class="datepickerTimeField" required="" name="deadline1" maxlength="10" placeholder=""/><br />
			<label>Крайний срок сдачи работы: </label><input type="text" class="datepickerTimeField" required="" name="deadline2" maxlength="10" placeholder=""/><br />
			<input id="submit" type="submit" name="send" value="Установить" />
			</form>
</div>
<?php include_once("templ/block_footer.php"); ?>	