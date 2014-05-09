<?php
 
include_once("php/db.php");
session_start(); 

	#доступ только у руководства кафедр
	if ($_SESSION["statusId"] != 3){ header("Location: home"); exit(); }

	$title = "Отчёты";
	$current_menu1 = "current-menu-item current_page_item";
	$js = '<script type="text/javascript" src="js/onlyDig.js"></script>';
	include_once("templ/block_header.php"); 

?>

<div id="edge" class="button">
	<form action="php/add_report_g.php" method="post">
		<label>Тип отчёта: </label>
		<select name="typeReport">
				<option value="0">Список студентов, которые получили неудовлетворительную оценку</option>
				<option value="1">Список студентов, которые не выбрали работу</option>
		</select><br />
		<label>Курс: </label><input type="text" name="course" maxlength="1" placeholder="Курс" value="1" onkeyup="return digC(this);" onchange="return digC(this);" /><br />
		<label>Формат отчёта: </label>
		<select name="type_doc">
			<option value="0">PDF</option>
			<option value="1">DOC</option>
		</select><br />
		<input id="submit" type="submit" value="Создать" />	
   </form>
</div>					
						
					
<?php include_once("templ/block_footer.php"); ?>	