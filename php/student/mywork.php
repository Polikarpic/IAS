<?php



/* Выводит информацию о выполняемой работе студента. */

include_once("db.php");

	# получаем запись о работе
	$query = mysql_query("SELECT intWorkId, intTeacherId, boolType, txtTopic FROM tblWork WHERE txtStudentId LIKE '%".$_SESSION['userId']."%' LIMIT 1");
    $data = mysql_fetch_assoc($query);
	
	# проверяем есть ли запись
	if (mysql_num_rows ($query) == 0) return;
	
	#получаем ФИО преподавателя
    $teacher = getFullName($data['intTeacherId']);
	
	#тип выполняемой работы
	if (!$data["boolType"]) $typeWork = "Курсовая работа";
    else $typeWork = "Выпускная кваллификационная работа";

?>

<div id="edge" class="button">
<h1>Моя работа</h1>
<p>[<?php echo $typeWork; ?>] <a href="current_topic.php?z=<?php echo $data["intWorkId"]; ?>"><b><?php echo $data["txtTopic"]; ?></b></a></p>
<p>Руководитель: <a href="profile.php?z=<?php echo $data["intTeacherId"]; ?>"><b><?php echo  $teacher; ?></b></a></p>
<input id="submit" type="button" onclick="location.href='current_topic?z=<?php echo $data["intWorkId"]; ?>'" value="Подробнее" />
</div>