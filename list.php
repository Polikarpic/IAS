<?php
 

	$title = "Список работ";
	$current_menu4 = "current-menu-item current_page_item";
	
	include_once("templ/block_header.php"); 

	#сообщения для пользователя
	um();
	
	include_once("php/list_of_works.php");
	include_once("php/list_of_BusyWorks.php");
	echo '<hr />';
	include_once("php/list_of_graduate_works.php");
	include_once("php/list_of_Busy_graduate_Works.php");

	include_once("templ/block_footer.php"); 
?>	