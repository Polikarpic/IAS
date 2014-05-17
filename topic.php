<?php
 

	include_once("php/db.php");
	session_start(); 
	
	#передан ли номер работы
	if (!isset($_GET["z"])) { $_SESSION["um"] = 'e13'; header("location: ./home"); exit(); }
		
	#получаем информацию о работе
	$query = mysql_query("SELECT *, dd.txtDirectionName as txtDirectionName2, d.txtDirectionName as txtDirectionName1, w.intDirectionId as dirId, w.intDirectionId2 as dirId2
	FROM tblWork w
	LEFT JOIN tblDirection d ON (d.intDirectionId = w.intDirectionId)
	LEFT JOIN tblDirection dd ON (dd.intDirectionId = w.intDirectionId2)
	WHERE w.intWorkId='".safe_var($_GET["z"])."' and (w.txtStudentId is null or w.intStudentsPerformingWork < w.txtNumber_of_persons)  LIMIT 1");
	$topic = mysql_fetch_array($query);		
	
	if (mysql_num_rows($query) == 0){ $_SESSION["um"] = 'e13'; header("location: ./home.php"); exit(); }
	
	#проверяем не занята ли уже эта работа !!!
	if (!is_null($topic["intStudentId"])){ header("location: ./current_topic?z=".$_GET["z"].""); exit(); }	

	#получаем информацию о подробном описании работы
	$query = mysql_query("SELECT txtName, txtLink FROM tblReview WHERE intReviewId='".$topic["intAdditionalComment"]."' LIMIT 1");
	$material =	mysql_fetch_array($query);		
	
	#получаем ФИО преподавателя
    $teacher = getFullName($topic['intTeacherId']);
	
	#получаем логин преподавателя для отправки сообщения
	$query = mysql_query("SELECT txtLogin FROM tblUsers WHERE intUserId='".$topic['intTeacherId']."' LIMIT 1");
	$name = mysql_fetch_array($query);
	
	#получаем количество поданых заявок на выполнение этой работы
	$query = mysql_query("SELECT COUNT(*) as max FROM tblList_of_applications WHERE intWorkId='".$topic["intWorkId"]."' and boolStatus='0'");
	$count_appl = mysql_fetch_array($query);	
	

	if (!$topic["boolType"]) $typeWork = "Курсовая работа";
    else $typeWork = "Выпускная кваллификационная работа";
	
	$title	= "Работа по теме \"".$topic["txtTopic"]."\"";
	$current_menu4 = "current-menu-item current_page_item";
	include("templ/block_header.php");
	
	#сообщения для пользователя
	um();
	 
?>
<div id="edge">
<p>Тема: <b><?php echo $topic["txtTopic"]; ?></b> </p>
<p>Вид работы: <b><?php echo $typeWork; ?></b> </p>
<?php if ($topic["txtComment"] != '') echo '<p>Описание: <div id="edge">'.$topic["txtComment"].'</div></p>'; ?>
<?php if (!empty($material)) echo '<p>Подробное описание:<br /><a href="'.$material["txtLink"].'">'.$material["txtName"].'</a></p>'; ?>
<p>Курс: <b><?php echo $topic["txtCourse"]; ?></b></p>
<p>Направление: <b><?php echo $topic["txtDirectionName1"]; ?></b></p>
<?php if (!empty($topic["txtDirectionName2"])) echo '<p>Дополнительное направление: <b>'.$topic["txtDirectionName2"].'</b></p>'?>
<p>Количество человек: <b><?php echo $topic["txtNumber_of_persons"]; ?></b></p>
<p>Руководитель: <b><a href="./profile.php?z=<?php echo $topic['intTeacherId']; ?>"><?php echo $teacher; ?></a></b> </p>
<p>Подано заявок на выполнение работы: <b><?php if (!empty($count_appl["max"])) echo $count_appl["max"]; else echo 0; ?></b> </p>

</div>
	
	<?php 
		  if ($_SESSION["userId"] == $topic['intTeacherId'] || ($_SESSION["statusId"] == 2 && $_SESSION["chairId"] == $topic["intChairId"])) echo '<input id="submit" type="button" onclick="if (confirm(\'Bы действительно хотите удалить тему?\')) location.href=\'./php/remove_topic?z='.$topic["intWorkId"].'\';" value="Удалить тему" /><input id="submit" type="button" onclick="location.href=\'\edit_topic?z='.$topic["intWorkId"].'\'" value="Редактировать тему" />'; 
		  else echo '<input id="submit" type="button" onclick="location.href=\'new_message?u='.$name["txtLogin"].'&t='.$topic["txtTopic"].'\'" value="Написать сообщение преподавателю" />';
		  if ($_SESSION["statusId"] == 0 && $topic["txtCourse"] == $_SESSION["courseId"] && ($topic["dirId"] == $_SESSION["directionId"] || $topic["dirId2"] == $_SESSION["directionId"])) echo '<input id="submit" type="button" onclick="if (confirm(\'Bы действительно хотите подать заявку?\')) location.href=\'./php/apply?z='.$topic["intWorkId"].'\';" value="Подать заявку на выполнение работы" />';
	?>	
	


<?php include_once("templ/block_footer.php"); ?>	