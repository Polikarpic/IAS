<?php
 
include_once("php/db.php");
session_start(); 

	#доступ только у руководства кафедр
	if ($_SESSION["statusId"] != 3){ $_SESSION["um"] = 'e0'; header("Location: home"); exit();}

	$title = "Отчёты";
	$current_menu1 = "current-menu-item current_page_item";
	$js = '<script type="text/javascript" src="js/onlyDig.js"></script>';
	include_once("templ/block_header.php"); 
	
	#сообщения для пользователя
	um();
	
	#получаем список направлений
	$direction = mysql_query("SELECT * FROM tblDirection");

?>

<div id="edge" class="button">
	<form action="php/add_report_g.php" method="post">
		<label>Тип отчёта: </label>
		<select name="typeReport">
				<option value="0">Список студентов, которые получили неудовлетворительную оценку</option>
				<option value="1">Список студентов, которые не выбрали работу</option>
		</select><br />
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
		<label>Формат отчёта: </label>
		<select name="type_doc">
			<option value="0">PDF</option>
			<option value="1">DOC</option>
		</select><br />
		<input id="submit" type="submit" value="Создать" />	
   </form>
</div>					
						
					
<?php include_once("templ/block_footer.php"); ?>	