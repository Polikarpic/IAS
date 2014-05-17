<?php 
include_once("./../db.php");
session_start();	

	#получаем оповещения
	$query = mysql_query("SELECT * 
	FROM tblList_of_applications ap 
	LEFT JOIN tblWork w ON w.intWorkId = ap.intWorkId
	WHERE ap.intStudentId='".$_SESSION["userId"]."' and boolStatus='0' ORDER BY ap.intApplicationsId DESC");	
 
?>
<h1>Поданные заявки на выполнение работ</h1>
	<table class="tftable">
		<tr>
			<th></th>
			<th >Тема работы</th>
			<th >Преподаватель</th>
		</tr>		
		
<?php 
	while($data = mysql_fetch_assoc($query))
	{
			
			echo '<td><image src="./images/cancel.png" title="Отозвать заявку" style="cursor: pointer;" onclick="if (confirm(\'Вы действительно хотите отозвать поданную заявку на выполнение этой работы?\')) location.href=\'php/recall_application?z='.$data["intApplicationsId"].'\'" /></td>';
			echo '<td><a href="topic?z='.$data["intWorkId"].'">'.$data["txtTopic"].'</a></td>';	
			echo '<td><a href="profile?z='.$data["intTeacherId"].'">'.getFullName($data["intTeacherId"]).'</a></td>';			
			echo '</tr>';			
	}
	if (mysql_num_rows($query) == 0) 
	{
					echo '<tr>';
					echo '<td>Вы ещё не подавали заявок ни к одной работе или они (заявки) уже рассмотрены</td>';
					echo '<td></td>';  
					echo '<td></td>';   
					echo '</tr>';				
	}
?>
	</table>	
</div>	