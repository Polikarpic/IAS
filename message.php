<?php
	include_once("php/db.php");
	session_start(); 
	
	$title = "Сообщения (".getMessCount($_SESSION['userId']).")";
	$current_menu3 = "current-menu-item current_page_item";
	$js = '<script src="http://code.jquery.com/jquery-latest.js"></script>
	<script type="text/javascript" src="js/jQuery_load.js"></script>';
	include_once("templ/block_header.php"); 
		
	#сообщения для пользователя
	um();	
?>
     

<div id="edge">
	<div id="right">
		<select id="filter_msg" onchange="filter_func();">
			<option value="1">Входящие сообщения</option>
			<option <?php if ($_GET["c"] == 1) echo 'selected="selected"'; ?> value="2">Отправленные сообщения</option>
		</select>  
	</div>
	
	<div id="messages">
		<?php 
			if ($_GET["c"] == 1) include_once("php/work_filter/outgoing_messages.php");
			else include_once("php/work_filter/incoming_messages.php");
		?>
	</div>
	

  <input id="submit" type="button" name="new_message" onclick="location.href='new_message'" value="Новое сообщение" />
  
  
<?php include_once("templ/block_footer.php"); ?>	