<?php 
include_once("./../db.php");
session_start();	

	$row_limit = 10; //число сообщений на страницу
	
	# получаем номер страницы 
	if (intval($_GET['page']) > 0) $pages = safe_var(intval($_GET['page']));
	else $pages = 1;
		
	# получаем максимальное число записей на страницу
	$count = mysql_fetch_array(mysql_query("select COUNT(*) as max from tblNotification WHERE intRecipientId='".$_SESSION["userId"]."'"));
	$max_pages = ceil($count["max"]/$row_limit);
	
	# если число страниц меньше нуля
	if ($pages < 1) $pages = 1;
	if ($pages > $max_pages) $pages = $max_pages;
	$_GET["page"] = $pages;		
	
	#номер записи с которой начинаем вывод
	$row_start = ($pages - 1) * $row_limit;	
	
	#получаем оповещения
	$query = mysql_query("SELECT * FROM tblNotification WHERE intRecipientId='".$_SESSION["userId"]."' ORDER BY intNotificationId DESC LIMIT ".$row_start.", ".$row_limit."");	 
?>

	<table class="tftable">
		<tr>
			<th></th>
			<th >Дата</th>				
			<th >Событие</th>					
		</tr>		
		
<?php 
	while($data = mysql_fetch_assoc($query))
	{
		echo '<tr class="even" data-href="application.php">';

		if ($data["boolCheck"] == 0) $button_check = '<image src="./images/read.png" title="Отменить как прочитанное" style="cursor: pointer;" onclick="location.href=\'php/NotificationRead.php?z='.$data["intNotificationId"].'\'">';
		else $button_check = '';
		
		if (is_null($data["intWorkId"])) $remove_button = ' <image src="./images/remove.png" title="Удалить оповещение" style="cursor: pointer;" onclick="location.href=\'php/remove_notification.php?z='.$data["intNotificationId"].'\'" />';	
		else $remove_button = '';
		
		echo '<td>'.$button_check.$remove_button.'</td>';
		
		if ($data["boolCheck"] == 0) #сообщение не прочитано
		{
			echo '<td><b>'.strftime('%d %B %G года',strtotime($data["datDate"])).'</b></td>';
			echo '<td><b>'.htmlspecialchars_decode($data["txtMessage"]).'</b></td>';		
			echo '</tr>';			
		} 
		else 
		{
			echo '<td>'.$data["datDate"].'</td>';
			echo '<td>'.htmlspecialchars_decode($data["txtMessage"]).'</td>';			
			echo '</tr>';		
		}		
	}
	if (mysql_num_rows($query) == 0) 
	{
					echo '<tr>';
					echo '<td>Оповещений нет</td>';
					echo '<td></td>';  
					echo '</tr>';				
	}
?>
	</table>	
<?php
#вывод списка страниц
if ($max_pages > 1)
{
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