<?php 
include_once("./../db.php");
session_start();	

	$row_limit = 10; //число сообщений на страницу
	
	# получаем номер страницы 
	if (intval($_GET['page']) > 0) $pages = safe_var(intval($_GET['page']));
	else $pages = 1;
	
	# получаем максимальное число записей на страницу
	$count = mysql_fetch_array(mysql_query("select COUNT(*) as max from tblMessage WHERE intSenderId='".$_SESSION["userId"]."' and boolType='1'"));
	$max_pages = ceil($count["max"]/$row_limit);
		
	# если число страниц меньше нуля
	if ($pages < 0) $pages = 1;
	if ($pages > $max_pages) $pages = $max_pages;
	$_GET["page"] = $pages;	
	
	#получаем номер сообщения для вывода
	$row_start = ($pages - 1) * $row_limit;	
	
	#получаем список сообщений
	$query = mysql_query("SELECT intMessageId, intRecipientId, datDate, txtTopic, boolCheck FROM tblMessage WHERE intSenderId='".$_SESSION["userId"]."' and boolType='1' ORDER BY intMessageId DESC LIMIT ".$row_start.", ".$row_limit."");    

?>
<h1>Отправленные сообщения</h1>
<table class="tftable" id="trlink">
		<tr>
			<th></th>
			<th>Дата</th>				
			<th>Тема</th>
			<th>Получатель</th>					
		</tr>	
	
		<?php
		while($data = mysql_fetch_array($query))
		{
			
			echo '<tr onMouseOver="this.style.backgroundColor=\'\#ccc\';" onMouseOut="this.style.backgroundColor=\'\#FFFAFA\'">';
			$remove_button = ' <image src="./images/remove.png" title="Удалить сообщение" style="cursor: pointer;" onclick="location.href=\'php/remove_message.php?z='.$data["intMessageId"].'\'" />';		
			echo '<td>'.$remove_button.'</td>';
			echo '<td onclick="location.href=\'current_message?z='.$data["intMessageId"].'\';">'.strftime('%d %B %G года',strtotime($data["datDate"])).'</td>';
			echo '<td onclick="location.href=\'current_message?z='.$data["intMessageId"].'\';">'.$data["txtTopic"].'</td>';		
			echo '<td onclick="location.href=\'current_message?z='.$data["intMessageId"].'\';"><a href="profile?z='.$data["intRecipientId"].'">'.getFullName($data["intRecipientId"]).'</a></td>';	
			echo '</tr>';		
			
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
			echo '<a href="'.$_SERVER["PHP_SELF"].'?page='.$p.'&page1='.$_GET["page1"].'&c=1">'.$p.'</a> ';
		}
	}
}
?>		
</div>	