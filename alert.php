<?php
 

	include_once("php/db.php");
	session_start(); 
	
	$title = "Оповещения (".getAlertCount($_SESSION['userId']).")";
	$js = '<script src="http://code.jquery.com/jquery-latest.js"></script>
    <script type="text/javascript" src="js/jQuery_load.js"></script>';
	$current_menu3 = "current-menu-item current_page_item";
	include_once("templ/block_header.php"); 
		
	#сообщения для пользователя
	um();	
		
?> 
<div id="edge">
	
	<?php
	if ($_SESSION["statusId"] == 0)
	{
		echo '<div id="right">';
		echo '<select id="filter_al" onchange="filter_func();">';
		echo '<option value="1">Оповещения</option>';
		echo '<option value="2">Отправленные заявки</option>'; 
		echo '</select>';  
		echo '</div>';
	}
	?>

	<div id="alert">
		<?php include_once("php/work_filter/alert.php"); ?>
	</div>

<?php include_once("templ/block_footer.php"); ?>	