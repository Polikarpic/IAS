<?php



include_once("db.php");

	#получаем крайние сроки сдачи отчёта и работы
	$query_deadlines = mysql_query("SELECT * FROM tblDeadlines ORDER BY intDeadlinesId DESC LIMIT 1");
	$deadlines = mysql_fetch_array($query_deadlines);

	#получаем информацию о курсовых работах(документы)
	$query = mysql_query("SELECT * FROM tblReview WHERE intWorkId='".$topic["intWorkId"]."' and (boolType='1' or boolType='4') ORDER BY intReviewId DESC");	
		
	
		   
?>

	<div id="edge">
	<h1>Отчёт и курсовая работа</h1>
	<div id="edge" class="deadlines">Крайний срок сдачи отчёта: <b><?php if (isset($deadlines["dateRentingReport"])) echo $deadlines["dateRentingReport"]; ?></b><br />
	Крайний срок сдачи работы: <b><?php if (isset($deadlines["dateRentingWork"])) echo $deadlines["dateRentingWork"]; ?></b></div>

	<table class="tftable">
		<tr>
			<th >Дата</th>	
			<th >Тип документа</th>
			<th >Загрузил</th>
			<th >Название документа</th>	
		</tr>		
				<?php
				while($data = mysql_fetch_array($query))
				{
					if ($data["boolType"] == 1) $type_file = 'Работа';
					else $type_file = 'Отчёт';
					
					echo '<tr>';
					echo '<td>'.$data["datDate"].'</td>';
					echo '<td>'.$type_file.'</td>'; 
					echo '<td><a href="profile?z='.$data["intSenderId"].'">'.getFullName($data["intSenderId"]).'</a></td>';
					echo '<td><a href="'.$data["txtLink"].'">'.$data["txtName"].'</td>';       
					echo '</tr>';
				}
				if (mysql_num_rows($query) == 0) 
				{
					echo '<tr>';
					echo '<td>Документы ещё не загружены</td>';
					echo '<td></td>';  
					echo '<td></td>';
					echo '<td></td>'; 
					echo '</tr>';				
				}
				?>
			</table>	
<?php 
if (mysql_num_rows($query_student) != 0)
{
	
	echo '<div id="edge" class="button">';
	echo '<h1>Загрузить документ</h1>';
	echo '<form enctype="multipart/form-data" method="post" action="./php/student/add_courseWork.php?z='.$topic["intWorkId"].'">';
	echo '<label>Тип документа: </label>';
	echo '<select name="type_file">';
	echo '<option value="4">Отчёт</option>';
	echo '<option value="1">Курсовая работа</option>';
	echo '</select>';	
	echo '<input type="file" name="f">';
	echo '<input id="submit" type="submit" value="Добавить Документ" />';
	echo '</form>'; 
	echo '</div>';
}

			
			 

?>
		
	</div>	