<?php 
include_once("./../db.php");
session_start();

$query = mysql_query("SELECT *, dd.txtDirectionName as txtDirectionName2, d.txtDirectionName as txtDirectionName1
FROM tblWork w
LEFT JOIN tblDirection d ON (d.intDirectionId = w.intDirectionId)
LEFT JOIN tblDirection dd ON (dd.intDirectionId = w.intDirectionId2)
LEFT JOIN tblChair ch ON ch.intChairId = w.intChairId
WHERE (w.txtStudentId is null or w.intStudentsPerformingWork < w.txtNumber_of_persons) ORDER BY w.intWorkId DESC LIMIT 5");		
?>

  <table class="tftable">
				<tr>
					<th >Тема</th>				
					<th >Курс</th>
					<th >Направление</th>
					<th >Количество человек</th>
					<th >Кафедра преподавателя</th>
					<th >Руководитель</th>
				</tr>	
				<?php
				while($data = mysql_fetch_array($query))
				{
					$teacher = getFullName($data['intTeacherId']); //возвращает ФИО ученика в формате (Фамилия И. О.)
					echo '<tr>';
					echo '<td><a href="./../topic?z='.$data["intWorkId"].'">'.$data["txtTopic"].'</a></td>';
					echo '<td>'.$data["txtCourse"].'</td>';  
					
					if ($data["txtDirectionName2"] != '' && $data["txtDirectionName1"] != $data["txtDirectionName2"]) echo '<td>'.$data["txtDirectionName1"].', '.$data["txtDirectionName2"].'</td>';  
					else echo '<td>'.$data["txtDirectionName1"].'</td>';  
					
					echo '<td>'.$data["txtNumber_of_persons"].'</td>';    
					echo '<td>'.$data["txtAbbreviation"].'</td>';
					echo '<td><a href="profile?z='.$data['intTeacherId'].'">'.$teacher.'</a></td>';    
					echo '</tr>';
				}
				if (mysql_num_rows($query) == 0) //если нет новых тем, то выводим сообщение об этом
				{
					echo '<tr>';
					echo '<td>Нет новых тем</td>';
					echo '<td></td>';  
					echo '<td></td>';  
					echo '<td></td>';    
					echo '<td></td>';  
					echo '<td></td>';  
					echo '</tr>';				
				}
				?>
			</table>	