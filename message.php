<?php
 

	include_once("php/db.php");
	include_once("php/info.php");
	session_start(); 
	
	$title = "Сообщения (".getMessCount($_SESSION['userId']).")";
	$current_menu3 = "current-menu-item current_page_item";
	$js = '<script type="text/javascript" src="js/trLink.js"></script>';

	include_once("templ/block_header.php"); 
	
	
	$row_limit = 10; //число сообщений на страницу
	
	# получаем номер страницы 
	if (intval($_GET['page']) > 0) $pages = safe_var(intval($_GET['page']));
	else $pages = 1;
	
	# получаем максимальное число записей на страницу
	$count = mysql_fetch_array(mysql_query("select COUNT(*) as max from tblMessage WHERE intRecipientId='".$_SESSION["userId"]."'"));
	$max_pages = ceil($count["max"]/$row_limit);
	
	
	# если число страниц меньше нуля
	if ($pages < 0) $pages = 1;
	if ($pages > $max_pages) $pages = $max_pages;
	$_GET["page"] = $pages;	
	
	#получаем номер сообщения для вывода
	$row_start = ($pages - 1) * $row_limit;	
	
	#получаем список сообщений
	$query = mysql_query("SELECT intMessageId, intSenderId, datDate, txtTopic, boolCheck FROM tblMessage WHERE intRecipientId='".$_SESSION["userId"]."' ORDER BY intMessageId DESC LIMIT ".$row_start.", ".$row_limit."");    
	 
	#информация
	if ($_GET["info"] == "sent")
	{
		$info[] = "Сообщение было успешно отправлено";
	} 
	
	info_msg($info);
	
	
	 	 
?>
     

<div id="edge">
	<table class="tftable" id="trlink">
		<tr>
			<th></th>
			<th>Дата</th>				
			<th>Тема</th>
			<th>Отправитель</th>					
		</tr>	
	
		<?php
		while($data = mysql_fetch_array($query))
		{
			$sender = getFullName($data["intSenderId"]);
			echo '<tr data-href="current_message?z='.$data["intMessageId"].'" onMouseOver="this.style.backgroundColor=\'\#ccc\';" onMouseOut="this.style.backgroundColor=\'\#FFFAFA\'">';
			
			if ($data["boolCheck"] == 0) $button_check = '<image src="./images/read.png" title="Отменить как прочитанное" style="cursor: pointer;" onclick="location.href=\'php/MessageRead.php?z='.$data["intMessageId"].'\'">';
			else $button_check = '';
			$remove_button = ' <image src="./images/remove.png" title="Удалить сообщение" style="cursor: pointer;" onclick="location.href=\'php/remove_message.php?z='.$data["intMessageId"].'\'" />';		
			echo '<td>'.$button_check.$remove_button.'</td>';
			
			if ($data["boolCheck"] == 0) #сообщение не прочитано
			{
				echo '<td><b>'.$data["datDate"].'</b></td>';
				echo '<td><b>'.$data["txtTopic"].'</b></td>';		
				echo '<td><b>'.$sender.'</b></td>';	
				echo '</tr>';			
			} 
			else 
			{
				echo '<td>'.$data["datDate"].'</td>';
				echo '<td>'.$data["txtTopic"].'</td>';		
				echo '<td>'.$sender.'</td>';	
				echo '</tr>';		
			}
		}	
		if (mysql_num_rows($query) == 0) 
		{
					echo '<tr>';
					echo '<td>Сообщений нет</td>';
					echo '<td></td>';  
					echo '<td></td>';
					echo '</tr>';				
		}
		?>		
	</table>	

<?php
#вывод списка страниц
if ($max_pages > 1){
echo 'Страница: ';
	for ($p = 1; $p <= $max_pages; $p++)
	{		
		if ($p == $pages)
		{
			echo '['.$p.'] ';
		}
		else
		{
			echo '<a href="'.$_SERVER["PHP_SELF"].'?page='.$p.'">'.$p.'</a> ';
		}
	}
}
?>		
</div>	


  <input id="submit" type="button" name="new_message" onclick="location.href='new_message'" value="Новое сообщение" />

<?php include_once("templ/block_footer.php"); ?>	