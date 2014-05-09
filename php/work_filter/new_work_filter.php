<?php 
include_once("./../db.php");
session_start();

	#студент
	if ($_SESSION["statusId"] == 0)
	{
		#получаем список последних 5 добавленных работ
		$query = mysql_query("SELECT *
		FROM tblWork w
		LEFT JOIN tblDirection d ON d.intDirectionId = w.intDirectionId
		WHERE w.intDirectionId='".$_SESSION["directionId"]."' and w.txtCourse='".$_SESSION["courseId"]."' and (w.txtStudentId is null or w.intStudentsPerformingWork < w.txtNumber_of_persons) ORDER BY w.intWorkId DESC LIMIT 5");	
	}
	else
	{
		#получаем список последних 5 добавленных работ
		$query = mysql_query("SELECT *
		FROM tblWork w
		LEFT JOIN tblDirection d ON d.intDirectionId = w.intDirectionId
		WHERE w.intChairId='".$_SESSION["chairId"]."' and (w.txtStudentId is null or w.intStudentsPerformingWork < w.txtNumber_of_persons) ORDER BY w.intWorkId DESC LIMIT 5");	
	}
?>

  <table class="tftable">
				<tr>
					<th >Тема</th>				
					<th >Курс</th>
					<th >Направление</th>
					<th >Количество человек</th>
					<th >Руководитель</th>
				</tr>	
				<?php
				while($data = mysql_fetch_array($query))
				{
					$teacher = getFullName($data['intTeacherId']); //возвращает ФИО ученика в формате (Фамилия И. О.)
														
					echo '<tr>';
					echo '<td><a href="./../topic?z='.$data["intWorkId"].'">'.$data["txtTopic"].'</a></td>';
					echo '<td>'.$data["txtCourse"].'</td>';  
					echo '<td>'.$data["txtDirectionName"].'</td>';  
					echo '<td>'.$data["txtNumber_of_persons"].'</td>';    
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
					echo '</tr>';				
				}
				?>
			</table>	