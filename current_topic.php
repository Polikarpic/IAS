<?php
 

	include_once("php/db.php");
	include_once("php/error.php");
	include_once("php/info.php");
	session_start(); 

	#проверяем получен ли номер работы
	if (!isset($_GET["z"])) { header("location: ./home.php?current_topic=fail&reason=not_work"); exit(); }
	
	#получаем информацию о работе
	$query = mysql_query("SELECT *
	FROM tblWork w 
	LEFT JOIN tblDirection d ON d.intDirectionId = w.intDirectionId
	WHERE w.intWorkId='".safe_var($_GET["z"])."' LIMIT 1");
	$topic = mysql_fetch_array($query);
		
	if (empty($topic) || mysql_num_rows($query) == 0){ header("location: ./home.php?ct=fail&reason=not_work"); exit(); }
	
	#проверяем не свободна ли работа
	if (is_null($topic["txtStudentId"])){ header("location: ./topic.php?z=".$_GET["z"].""); exit(); }	
	
	#получаем информацию о подробном описании работы
	$query = mysql_query("SELECT txtName, txtLink FROM tblReview WHERE intReviewId='".$topic["intAdditionalComment"]."' LIMIT 1");
	$material =	mysql_fetch_array($query);	
	
	#получаем список студентов выполняющих работу
	$query_student = mysql_query("SELECT * FROM tblWork WHERE intWorkId='".$topic["intWorkId"]."' and txtStudentId LIKE '% ".$_SESSION["userId"]." %' LIMIT 1");
		
    $teacher = getFullName($topic['intTeacherId']);
	
	#рецензент
	$query = mysql_query("SELECT * FROM tblReviewers WHERE intWorkId ='".$topic["intWorkId"]."' LIMIT 1");
	$reviewers = mysql_fetch_array($query);	

	if (!$topic["boolType"]) $typeWork = "Курсовая работа";
    else $typeWork = "Выпускная кваллификационная работа";

	$title = $typeWork." студента ".$student;
	$current_menu4 = "current-menu-item current_page_item";
	include_once("templ/block_header.php"); 
	
	
	#ошибки
	if ($_GET["reason"] == "not_file")
	{
		$error[] = "Не выбран файл для загрузки";
	} 
	if ($_GET["reason"] == "size")
	{
		$error[] = "Размер файла не может превышать 10 мегабайт";
	} 
	if ($_GET["reason"] == "extension")
	{
		$error[] = "Неверный тип файла. Поддерживаемые расширения: pdf, doc, docx, docm";
	} 
	if ($_GET["add_cw"] == "ok" || $_GET["add_re"] == "ok" || $_GET["add_re"] == "ok")
	{
		$info[] = "Документ успешно загружен";
	} 
	
	info_msg($info);
	error_msg($error);
	
	
	function mark($intStudentId,$topic){
	#оценки
	$query = mysql_query("SELECT * FROM tblMark WHERE intWorkId='".safe_var($_GET["z"])."' and intStudentId='".$intStudentId."' LIMIT 1");
	$mark =	mysql_fetch_array($query);	
	
	echo '<div id="edge">';
	echo '<p>Оценка преподавателя: <b>';
	if(!empty($mark['txtTeacherMark'])) echo $mark['txtTeacherMark']; else echo 'нет'; echo '</b></p>';
	echo '<p>Оценка рецензента: <b>';
	if(!empty($mark['txtReviewerMark'])) echo $mark['txtReviewerMark']; else echo 'нет'; echo '</b></p>';
	echo '<p>Оценка комиссии: <b>';
	if(!empty($mark['txtСommissionMark'])) echo $mark['txtСommissionMark']; else echo 'нет'; echo '</b></p>';
	echo '<p>Итоговая оценка: <b>';
	if(!empty($mark['txtFinalMark'])) echo $mark['txtFinalMark']; else echo 'нет'; echo '</b></p>';
	if ($topic["intTeacherId"] == $_SESSION["userId"]) include("php/teacher/mark.php");
	if ($_SESSION["statusId"] == 2 and $topic["intChairId"] == $_SESSION["chairId"]) include("php/methodist/mark.php");
	echo '</div>';	
	}
	
		 
?>
<div id="edge">
<p>Тема: <b><?php echo $topic["txtTopic"]; ?></b> </p>
<p>Вид работы: <b><?php echo $typeWork; ?></b> </p>
<?php if ($topic["txtComment"] != '') echo '<p>Описание: <div id="edge">'.$topic["txtComment"].'</div></p>'; ?>
<?php if (!empty($material)) echo '<p>Подробное описание:<br /><a href="'.$material["txtLink"].'">'.$material["txtName"].'</a></p>'; ?>
<p>Курс: <i><?php echo $topic["txtCourse"]; ?></i></p>
<p>Направление: <i><?php echo $topic["txtDirectionName"]; ?></i></p>
<p>Руководитель: <b><a href="./profile.php?z=<?php echo $topic['intTeacherId']; ?>"><?php echo $teacher; ?></a></b> </p>
<?php
if ($topic["txtNumber_of_persons"] == 1)
{ 
	$student = getFullName(intval($topic['txtStudentId']));
	echo '<p>Выполняет: <b><a href="./profile.php?z='.intval($topic['txtStudentId']).'">'.$student.'</a></b></p>';
    mark(intval($topic['txtStudentId']),$topic);
}
else
{
	$students = split_studentId($topic['txtStudentId']);
	$max = sizeof($students);
	echo '<p>Выполняют:</p>';
	for ($i = 1; $i <= $max; $i++)
	{
		$student = getFullName($students[$i]);
		echo '<b><a href="./profile.php?z='.$students[$i].'">'.$student.'</a></b><br />';
		mark($students[$i],$topic);
	}
}
?>
<p>Рецензент: <b><?php if (isset($reviewers["intTeacherId"])) echo '<a href="profile?z='.$reviewers["intTeacherId"].'">'.getFullName($reviewers['intTeacherId']).'</a>'; else echo 'не назначен'; ?></b></p>

<?php  if ($topic["intTeacherId"] == $_SESSION["userId"])  include_once("php/teacher/assign_reviewer.php"); ?>

</div>

<?php //if ($_SESSION["userId"] == $topic['intTeacherId']) echo '<div id="edge" class="button"><input id="submit" type="button" onclick="location.href=\'\close_topic?z='.$topic["intWorkId"].'\'" value="Закрыть работу" /><input id="submit" type="button" onclick="location.href=\'\edit_topic?z='.$topic["intWorkId"].'?a=y\'" value="Редактировать тему" /></div>';
//if (mysql_num_rows($query_student) != 0) echo '<div id="edge" class="button"><input id="submit" type="button" onclick="location.href=\'refuse_work\'" value="Отказаться от выполнения работы" /></div>';


 include_once("php/topic_courseWork.php");
 include_once("php/topic_review.php");
 include_once("php/topic_critique.php");
 include_once("templ/block_footer.php"); 
  
 ?>	