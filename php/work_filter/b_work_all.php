<?php 
include_once("./../db.php");
session_start();

	$row_limit = 10; //число работ на страницу
	
	# получаем номер страницы 
	if (intval($_GET['page3']) > 0) $pages = safe_var(intval($_GET['page3']));
	else $pages = 1;
	
	# получаем максимальное число записей на страницу
	$count = mysql_fetch_array(mysql_query("select COUNT(*) as max from tblWork WHERE boolType='0' and intWorkStatus='0' and txtStudentId is not null"));
	$max_pages = ceil($count["max"]/$row_limit);
		
	# если число страниц меньше нуля
	if ($pages < 0) $pages = 1;
	if ($pages > $max_pages) $pages = $max_pages;
	$_GET["page3"] = $pages;	
	
	#получаем номер сообщения для вывода
	$row_start = ($pages - 1) * $row_limit;	

	#получаем информацию о работе
	$query = mysql_query("SELECT *, dd.txtDirectionName as txtDirectionName2, d.txtDirectionName as txtDirectionName1
	FROM tblWork w
	LEFT JOIN tblDirection d ON (d.intDirectionId = w.intDirectionId)
	LEFT JOIN tblDirection dd ON (dd.intDirectionId = w.intDirectionId2)
	LEFT JOIN tblChair ch ON ch.intChairId = w.intChairId
	WHERE w.boolType='0' and w.intWorkStatus='0' and w.txtStudentId is not null ORDER BY w.intWorkId DESC LIMIT ".$row_start.", ".$row_limit."");	
	
?>

  		     <table class="tftable">
				<tr>
					<th >Тема</th>					
					<th >Курс</th>
					<th >Направление</th>
					<th >Количество человек</th>
					<th >Кафедра преподавателя</th>
					<th >Руководитель</th>
					<th >Выполняет</th>
				</tr>	
				<?php
				while($data = mysql_fetch_array($query))
				{
					$teacher = getFullName($data['intTeacherId']);
														
					
					echo '<tr>';
					echo '<td><a href="current_topic?z='.$data["intWorkId"].'">'.$data["txtTopic"].'</a></td>';
					echo '<td>'.$data["txtCourse"].'</td>';  
					
					if ($data["txtDirectionName2"] != '' && $data["txtDirectionName1"] != $data["txtDirectionName2"]) echo '<td>'.$data["txtDirectionName1"].', '.$data["txtDirectionName2"].'</td>';  
					else echo '<td>'.$data["txtDirectionName1"].'</td>'; 
					
					echo '<td>'.$data["txtNumber_of_persons"].'</td>';
					echo '<td>'.$data["txtAbbreviation"].'</td>';
					echo '<td><a href="profile?z='.$data['intTeacherId'].'">'.$teacher.'</a></td>';  
					if ($data["txtNumber_of_persons"] == 1)
					{ 
						$student = getFullName($data['txtStudentId']);
						echo '<td><a href="profile?z='.$data['txtStudentId'].'">'.$student.'</a></td>'; 
					}
					else
					{
						$students = split_studentId($data['txtStudentId']);
						$max = sizeof($students);
						echo '<td>';
						for ($i = 1; $i <= $max; $i++)
						{	
							$student = getFullName($students[$i]);
							echo '<a href="profile?z='.$students[$i].'">'.$student.'</a><br />'; 
						}
						echo '</td>';
					}
					
					echo '</tr>';
				}
				if (mysql_num_rows($query) == 0) 
				{
					echo '<tr>';
					echo '<td>Нет работ</td>'; 
					echo '<td></td>'; 
					echo '<td></td>';	
					echo '<td></td>';
					echo '<td></td>';    
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
			echo '<a href="./../../list?page1='.$_GET['page1'].'&page2='.$_GET['page2'].'&page3='.$p.'&page4='.$_GET['page4'].'&page5='.$_GET['page5'].'&page6='.$_GET['page6'].'&page7='.$_GET['page7'].'&page8='.$_GET['page8'].'&c1='.$_GET['c1'].'&c2=1&c3='.$_GET['c3'].'&c4='.$_GET['c4'].'">'.$p.'</a> ';
		}
	}
}
?>	