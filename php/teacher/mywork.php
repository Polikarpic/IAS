<?php
	include_once("db.php");
?>



<?php
# получаем запись о работе
$query = mysql_query("SELECT intWorkId, txtStudentId, boolType, txtTopic, txtNumber_of_persons FROM tblWork WHERE intTeacherId='".$_SESSION['userId']."' and intWorkStatus='0' and txtStudentId is not null");
  		

if (mysql_num_rows ($query) != 0)
{
	echo '<div id="edge" class="button">';
	echo '<h1>Работы студентов</h1>';
	$j = 1; 

	while($data = mysql_fetch_array($query))
	{
		#тип выполняемой работы
		if (!$data["boolType"]) $typeWork = "Курсовая работа";
		else $typeWork = "Выпускная кваллификационная работа";
			
		echo '<p>['.$typeWork.']<a href="current_topic?z='.$data["intWorkId"].'"><b>'.$data["txtTopic"].'</b></a></p>';
		$students = split_studentId($data['txtStudentId']);
		$max = sizeof($students);
		if ($max == 1) echo '<p>Студент: </p>';
		else echo '<p>Студенты: </p>';
		for ($i = 1; $i <= $max; $i++)
		{
			$student = getFullName($students[$i]);
			echo '<p><a href="profile.php?z='.$students[$i].'"><b>'.$student.'</b></a></p>';
		}
		echo '<input id="submit" type="button" onclick="location.href=\'current_topic?z='.$data["intWorkId"].'\'" value="Подробнее" />';		
	
		if ($j != mysql_num_rows ($query))	echo '<br /><br /><hr />';
		$j++;
	}
	
echo '</div>';
}	

#рецензент
$query = mysql_query("SELECT * 
FROM tblReviewers r
LEFT JOIN tblWork w ON w.intWorkId = r.intWorkId
WHERE r.intTeacherId ='".$_SESSION['userId']."' LIMIT 1");


if (mysql_num_rows($query) != 0)
{
	echo '<div id="edge" class="button">';
	echo '<h1>Рецензии</h1>';
	$j = 1; 
	while($data = mysql_fetch_array($query))
	{
		#тип выполняемой работы
		if (!$data["boolType"]) $typeWork = "Курсовая работа";
		else $typeWork = "Выпускная кваллификационная работа";
	
		echo '<p>['.$typeWork.']<a href="current_topic?z='.$data["intWorkId"].'"> <b>'.$data["txtTopic"].'</b></a></p>';
		$students = split_studentId($data['txtStudentId']);
		$max = sizeof($students);
		if ($max == 1) echo '<p>Студент: </p>';
		else echo '<p>Студенты: </p>';
		for ($i = 1; $i <= $max; $i++)
		{
			
			$student = getFullName($students[$i]);
			echo '<p><a href="profile.php?z='.$students[$i].'"><b>'.$student.'</b></a></p>';
		}
		
		echo '<input id="submit" type="button" onclick="location.href=\'current_topic?z='.$data["intWorkId"].'\'" value="Подробнее" />';		

		if ($j != mysql_num_rows ($query))	echo '<br /><br /><hr />';
		$j++;

	}
	
	echo '</div>';
}
	
	
?>
