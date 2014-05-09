<?php
 

	include_once("php/info.php");
	include_once("php/error.php");
	$current_menu1 = "current-menu-item current_page_item";
	include_once("templ/block_header.php");
	
	#информация
	if ($_GET["reason"] == "already_submitted")
	{
		$error[] = "Вы уже подавали заявку на выполнение этой работы";
	} 
	if ($_GET["reason"] == "already_running")
	{
		$error[] = "Вы уже выполняете другую работу";
	} 
	if ($_GET["reason"] == "max_work")
	{
		$error[] = "Вы уже подали заявки на 5 работ. Пожалуйста, подождите пока преподаватели рассмотрят их";
	} 
	if ($_GET["take"] == "ok")
	{
		$info[] = "Вы успешно одобрили заявку студента на выполнение работы";
	} 	
	if ($_GET["reject"] == "ok")
	{
		$info[] = "Вы успешно отклонили заявку студента на выполнение работы";
	} 	
	if ($_GET["apply"] == "ok")
	{
		$info[] = "Заявка на выполнение работы была успешно подана";
	} 
	if ($_GET["edit_topic"] == "ok")
	{
		$info[] = "Тема была успешно отредактирована";
	} 
	if ($_GET["remove_topic"] == "ok")
	{
		$info[] = "Тема была успешно удалена";
	} 
	
	unset($_GET);
	info_msg($info);
	error_msg($error);
	
	
	#блок "О проекте"
	include_once("php/about.php"); 


	#блок "Моя работа"
	if ($_SESSION['statusId'] == 0) include_once("php/student/mywork.php"); 
	elseif ($_SESSION['statusId'] == 1) include_once("php/teacher/mywork.php"); 
	elseif ($_SESSION['statusId'] == 2)	include_once("php/methodist/mywork.php"); 
	elseif ($_SESSION['statusId'] == 3)	include_once("php/guide/mywork.php");
	
	#список новых тем работ	
 	if ($_SESSION["statusId"] != 3) include_once("php/list_of_new_works.php");
	
	include_once("templ/block_footer.php");
?>	


