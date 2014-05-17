<?php 
include_once("./../db.php");
session_start();	

	$row_limit = 10; //число сообщений на страницу
	
	# получаем номер страницы 
	if (intval($_GET['page1']) > 0) $pages = safe_var(intval($_GET['page1']));
	else $pages = 1;
	
	# получаем максимальное число записей на страницу
	$count = mysql_fetch_array(mysql_query("select COUNT(*) as max from tblMessage WHERE intRecipientId='".$_SESSION["userId"]."' and boolType='0'"));
	$max_pages = ceil($count["max"]/$row_limit);
		
	# если число страниц меньше нуля
	if ($pages < 0) $pages = 1;
	if ($pages > $max_pages) $pages = $max_pages;
	$_GET["page1"] = $pages;	
	
	#получаем номер сообщения для вывода
	$row_start = ($pages - 1) * $row_limit;	
	
	#получаем список сообщений
	$query = mysql_query("SELECT intMessageId, intSenderId, datDate, txtTopic, boolCheck FROM tblMessage WHERE intRecipientId='".$_SESSION["userId"]."' and boolType='0' ORDER BY intMessageId DESC LIMIT ".$row_start.", ".$row_limit."");    

?>
<h1>Входящие сообщения</h1>
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
			echo '<tr onMouseOver="this.style.backgroundColor=\'\#ccc\';" onMouseOut="this.style.backgroundColor=\'\#FFFAFA\'">';
			
			if ($data["boolCheck"] == 0) $button_check = '<image src="./images/read.png" title="Отменить как прочитанное" style="cursor: pointer;" onclick="location.href=\'php/MessageRead.php?z='.$data["intMessageId"].'\'">';
			else $button_check = '';
			$remove_button = ' <image src="./images/remove.png" title="Удалить сообщение" style="cursor: pointer;" onclick="location.href=\'php/remove_message.php?z='.$data["intMessageId"].'\'" />';		
			echo '<td>'.$button_check.$remove_button.'</td>';
			
			if ($data["boolCheck"] == 0) #сообщение не прочитано
			{
				echo '<td onclick="location.href=\'current_message?z='.$data["intMessageId"].'\';"><b>'.strftime('%d %B %G года',strtotime($data["datDate"])).'</b></td>';
				echo '<td onclick="location.href=\'current_message?z='.$data["intMessageId"].'\';"><b>'.$data["txtTopic"].'</b></td>';		
				echo '<td onclick="location.href=\'current_message?z='.$data["intMessageId"].'\';"><b><a href="profile?z='.$data["intSenderId"].'">'.getFullName($data["intSenderId"]).'</a></b></td>';	
				echo '</tr>';			
			} 
			else #сообщение прочитано
			{
				echo '<td onclick="location.href=\'current_message?z='.$data["intMessageId"].'\';">'.strftime('%d %B %G года',strtotime($data["datDate"])).'</td>';
				echo '<td onclick="location.href=\'current_message?z='.$data["intMessageId"].'\';">'.$data["txtTopic"].'</td>';		
				echo '<td onclick="location.href=\'current_message?z='.$data["intMessageId"].'\';"><a href="profile?z='.$data["intSenderId"].'">'.getFullName($data["intSenderId"]).'</a></td>';	
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
			echo '<a href="'.$_SERVER["PHP_SELF"].'?page='.$_GET["page"].'&page1='.$p.'&c=0">'.$p.'</a> ';
		}
	}
}
?>		
</div>	