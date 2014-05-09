<?php


include_once("db.php");

	$row_limit = 10; //число работ на страницу
	
	# получаем номер страницы 
	if (intval($_GET['page2']) > 0) $pages = safe_var(intval($_GET['page2']));
	else $pages = 1;
	
	# получаем максимальное число записей на страницу
	$count = mysql_fetch_array(mysql_query("select COUNT(*) as max from tblWork WHERE boolType='0' and txtStudentId is not null"));
	$max_pages = ceil($count["max"]/$row_limit);
		
	# если число страниц меньше нуля
	if ($pages < 0) $pages = 1;
	if ($pages > $max_pages) $pages = $max_pages;
	$_GET["page2"] = $pages;	
	
	#получаем номер сообщения для вывода
	$row_start = ($pages - 1) * $row_limit;	

	#получаем информацию о работе
	$query = mysql_query("SELECT *
	FROM tblWork w
	LEFT JOIN tblDirection d ON d.intDirectionId = w.intDirectionId
	WHERE w.boolType='0' and w.txtStudentId is not null ORDER BY w.intWorkId DESC LIMIT ".$row_start.", ".$row_limit."");	
	
	
?>

	<div id="edge" class="button">			
			<h1>Курсовые работы</h1>
		
			
		    <table class="tftable">
				<tr>
					<th >Тема</th>					
					<th >Курс</th>
					<th >Направление</th>
					<th >Количество человек</th>
					<th >Руководитель</th>
					<th >Выполняет</th>
				</tr>	
				<?php
				while($data = mysql_fetch_array($query))
				{
					$teacher = getFullName($data['intTeacherId']);
														
					
					echo '<tr>';
					echo '<td><a href="current_topic.php?z='.$data["intWorkId"].'">'.$data["txtTopic"].'</a></td>';
					echo '<td>'.$data["txtCourse"].'</td>';  
					echo '<td>'.$data["txtDirectionName"].'</td>';  
					echo '<td>'.$data["txtNumber_of_persons"].'</td>';    
					echo '<td>'.$teacher.'</td>';  
					if ($data["txtNumber_of_persons"] == 1)
					{ 
						$student = getFullName($data['txtStudentId']);
						echo '<td>'.$student.'</td>'; 
					}
					else
					{
						$students = split_studentId($data['txtStudentId']);
						$max = sizeof($students);
						echo '<td>';
						for ($i = 1; $i <= $max; $i++)
						{	
							$student = getFullName($students[$i]);
							echo $student.'<br />'; 
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
			echo '<a href="'.$_SERVER["PHP_SELF"].'?page2='.$p.'&page1='.$_GET['page1'].'&page3='.$_GET['page3'].'&page4='.$_GET['page4'].'">'.$p.'</a> ';
		}
	}
}
?>				
</div>	