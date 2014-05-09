<?php
 
	include_once("php/db.php");
	session_start(); 
	if ($_SESSION["statusId"] != 0) header("Location: ./../home");
	
	$title = "Отказаться от выполнения работы";
	$current_menu1 = "current-menu-item current_page_item";
	include_once("templ/block_header.php"); 		 
	 
	
	
?>
<div id="edge" class="button">
			<form action="" method="post">
			<label>Причина отказа от работы: </label><br />
			<textarea maxlength="500" name="reason" placeholder="Причина отказа"></textarea><br />
			<input id="submit" type="submit" name="send" value="Отказаться от выполнения работы" onclick="return confirm('Вы действительно хотите отказаться от выполнения работы?')"/>
			</form>
</div>
<?php include_once("templ/block_footer.php"); ?>	