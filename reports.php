<?php
 

	$title = "Отчёты";
	$current_menu1 = "current-menu-item current_page_item";
	include_once("templ/block_header.php"); 
?>

	<select id="type" onchange="changeCheck();">
		<option value="1">Список студентов, которые не выбрали работу</option>
		<option value="2">Список студентов, получивших неудовлетворительную оценку</option>	
	</select>  
   <input id="submit" type="button" onclick="location.href='#'" value="Получить" />	
					
						
					
<?php include_once("templ/block_footer.php"); ?>	