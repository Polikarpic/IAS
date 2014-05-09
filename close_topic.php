<?php
 
	include_once("php/db.php");
	session_start(); 
	if ($_SESSION["statusId"] != 1) header("Location: ./../home");
	
	$title = "Закрыть работу";
	$current_menu1 = "current-menu-item current_page_item";
	include_once("templ/block_header.php"); 	
	
	 
	 
	
	
?>
<div id="edge" class="button">
			<p><b>Внимание! К закрытой работе нельзя добавлять документы, назначать рецензента и выставлять оценки студентам. </b></p>
			<form action="" method="post">
			<label>Оставить тему работы: </label><input type="checkbox" name="""/><br />
			<input id="submit" type="submit" name="send" value="Закрыть работу" />
			</form>
</div>
<?php include_once("templ/block_footer.php"); ?>	