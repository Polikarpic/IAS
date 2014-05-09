<?php



/* Выводит информацию о студентах, которые выполняют работы преподавателя */

include_once("db.php");

	# получаем запись о работе
	$query = mysql_query("SELECT intWorkId, txtStudentId, boolType, txtTopic, txtNumber_of_persons FROM tblWork WHERE intTeacherId='".$_SESSION['userId']."' and txtStudentId is not null");
  		
	# проверяем есть ли запись
	if (mysql_num_rows ($query) == 0) return;
	

?>

<div id="edge" class="button">
<h1>Работы студентов</h1>
<?php
$j = 1; 
	while($data = mysql_fetch_array($query))
{
	#тип выполняемой работы
	if (!$data["boolType"]) $typeWork = "Курсовая работа";
    else $typeWork = "Выпускная кваллификационная работа";
	
		
	echo '<p>['.$typeWork.']<a href="current_topic.php?z='.$data["intWorkId"].'"><b>'.$data["txtTopic"].'</b></a></p>';
    if ($data["txtNumber_of_persons"] == 1)
	{	
		$student = getFullName(intval($data['txtStudentId']));
		echo '<p>Студент: <a href="profile.php?z='.intval($data["txtStudentId"]).'"><b>'.$student.'</b></a></p>';
		echo '<input id="submit" type="button" onclick="location.href=\'current_topic?z='.$data["intWorkId"].'\'" value="Подробнее" />';
	}
	else 
	{
		$students = split_studentId($data['txtStudentId']);
		$max = sizeof($students);
		echo '<p>Студенты: </p>';
		for ($i = 1; $i <= $max; $i++)
		{
			
			$student = getFullName($students[$i]);
			echo '<p><a href="profile.php?z='.$students[$i].'"><b>'.$student.'</b></a></p>';
		}
		echo '<input id="submit" type="button" onclick="location.href=\'current_topic?z='.$data["intWorkId"].'\'" value="Подробнее" />';		
	}
	if ($j != mysql_num_rows ($query))	echo '<br /><br /><hr />';
	$j++;
}
?>
</div>