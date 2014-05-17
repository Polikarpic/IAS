<?php
#проверка авторизации пользователя
include_once("php/check.php"); 
include_once("php/db.php");
session_start();	
?>

<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?php if(!empty($title)) echo $title; else echo 'Информационно-аналитическая система'; ?></title>	
	<link rel="stylesheet" type="text/css" media="screen" href="css/style.css" />
	<link rel="stylesheet" type="text/css" media="screen" href="css/table.css" />
	<?php if (!empty($js)) echo $js;  ?>
	<link rel="icon" href="images/favicon.ico" type="image/x-icon">
	<link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
</head>
<body>

<div class="container">

	<div id="page">
	<div id="edge_head"><p><b><?php if(!empty($title)) echo $title; else echo 'Информационно-аналитическая система'; ?></b></p></div>
	<div id="edge" class="head_l"><?php if (!empty($title))include("templ/return_ago.php");  ?></div>
	<div id="edge" class="head_r"><a href="message?page=1">сообщения(<?php echo getMessCountNR($_SESSION['userId']); ?>)</a> <a href="alert?page=1">оповещения(<?php echo getAlertCountNR($_SESSION['userId']); ?>)</a> <a href="profile"><?php echo $_SESSION["login"]; ?>  <sup><a href="index?logout=true" onclick="return confirm('Bы действительно хотите выйти?');"><img id="close" src="images/close.png" title="Выход" /></a></sup></div>
		
	<div id="main-menu" class="menu-main-menu-container">
	<ul id="menu-main-menu" class="menu">
		<li class="menu-item <?php if (!empty($current_menu1)) echo $current_menu1; ?> page-home-php"><em></em><a href="home">Главная</a></li>
		<li class="menu-item <?php if (!empty($current_menu2)) echo $current_menu2; ?> page-profile-php"><em></em><a href="profile">Профиль</a></li>
		<li class="menu-item <?php if (!empty($current_menu3)) echo $current_menu3; ?> page-message-php"><em></em><a href="message">Сообщения</a></li>
		<li class="menu-item <?php if (!empty($current_menu4)) echo $current_menu4; ?> page-list-php"><em></em><a href="list">Список работ</a></li>
		<li class="menu-item <?php if (!empty($current_menu5)) echo $current_menu5; ?> page-document-php"><em></em><a href="documents">Документы</a></li>
		<li class="menu-item <?php if (!empty($current_menu6)) echo $current_menu6; ?> page-settings-php"><em></em><a href="settings">Настройки</a></li>
	</ul>
</div>	
<div id="stiker">
	<div id="stiker_wrap"><?php echo lastNews(); ?>
	<div id="h_wrap"><a href="news">Список новостей</a></div>
	</div>
</div> 

		<div id="page-decoration"><a href="home"><img src="images/logo_beta.png" /></a></div>
		
		<div id="site-info">
			<div class="text"></div>
			<div class="clear"></div>
		</div>
		
	<div id="content"> 		
		<div class="entry-content">
		
	
	

	