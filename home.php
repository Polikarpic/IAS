<?php
 
	$current_menu1 = "current-menu-item current_page_item";
	include_once("templ/block_header.php");
	
	#сообщения для пользователя
	um();
	
	#блок "О проекте"
	include_once("templ/about.php"); 

	#блок "Моя работа"
	if ($_SESSION['statusId'] == 0) include_once("php/student/mywork.php"); 
	elseif ($_SESSION['statusId'] == 1) include_once("php/teacher/mywork.php"); 
	elseif ($_SESSION['statusId'] == 2)	include_once("php/methodist/mywork.php"); 
	elseif ($_SESSION['statusId'] == 3)	include_once("php/guide/mywork.php");
	
	#список новых тем работ	
 	if ($_SESSION["statusId"] != 3) include_once("php/list_of_new_works.php");
	
	include_once("templ/block_footer.php");
?>	


