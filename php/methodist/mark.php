<?php


include_once("php/db.php");
session_start(); 

	$m = 'mark'.$intStudentId;
	if (isset($_POST[$m]))
{
	$list_mark = array (1 => 'Нет', 'Неудовлетворительно', 'Удовлетворительно', 'Хорошо', 'Отлично');
	$mark1 = 'Нет'; $mark2 = 'Нет'; $mark3 = 'Нет'; $mark4 = 'Нет'; 
	$mark1 = $list_mark[$_POST["mark1"]];
	$mark2 = $list_mark[$_POST["mark12"]];
	$mark3 = $list_mark[$_POST["mark3"]];
	$mark4 = $list_mark[$_POST["mark4"]];
 
	$now_time = date("Y-m-d H:i:s",mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y')));
	
	$query = mysql_query("SELECT * FROM tblMark WHERE intWorkId='".$topic["intWorkId"]."' and intStudentId='".safe_var($intStudentId)."'");
	if (mysql_num_rows($query) != 0) 
	{
		mysql_query("UPDATE  tblMark SET txtTeacherMark = '$mark1', txtReviewerMark = '$mark2',txtСommissionMark =  '$mark3',txtFinalMark =  '$mark4' WHERE intWorkId='".$topic["intWorkId"]."' and intStudentId='".safe_var($intStudentId)."'");
		echo '<meta http-equiv="refresh" content="0; url="./../../current_topic?z='.$topic["intWorkId"].'">';;
	}
	else
	{
		mysql_query("INSERT INTO `tblMark` (`intMarkId`,`intWorkId`,`intStudentId`, `txtReviewerMark`,`txtСommissionMark`,`txtFinalMark`) VALUES (NULL,'".$topic["intWorkId"]."','".safe_var($intStudentId)."', '$mark2', '$mark3','$mark4')");	
		echo '<meta http-equiv="refresh" content="0; url="./../../current_topic?z='.$topic["intWorkId"].'">';
	}
	
	$alert = mysql_real_escape_string("Преподаватель <a href='profile?z=".$topic["intTeacherId"]."'>".$teacher."</a> изменил оценку(и) к вашей работе <b><a href='current_topic.php?z=".$topic["intWorkId"]."'>".$topic["txtTopic"]."</a></b>");
	
	
	#оповещение для студента о оценке
	mysql_query("INSERT INTO `tblNotification` (`intNotificationId`, `intRecipientId`, `txtMessage`, `boolCheck`, `datDate`) VALUES (NULL, '".safe_var($intStudentId)."', '".$alert."', '0', '".$now_time."')");
	
	$mark =	mysql_fetch_array($query);	
	
	$_SESSION["um"] = 'i28';
	
}	
?>

<br />
<div id="edge" class="button">
<h1>Выставить оценку</h1>
<form action="" method="post">
<label>Оценка преподавателя: </label>
<select id="type" name="mark1">
	<option value="1">Нет</option>
	<option value="2" <?php if ($mark['txtTeacherMark'] == "Неудовлетворительно") echo 'selected="selected"'; ?>>Неудовлетворительно</option>
    <option value="3" <?php if ($mark['txtTeacherMark'] == "Удовлетворительно") echo 'selected="selected"'; ?>>Удовлетворительно</option>
    <option value="4" <?php if ($mark['txtTeacherMark'] == "Хорошо") echo 'selected="selected"'; ?>>Хорошо</option>
	<option value="5" <?php if ($mark['txtTeacherMark'] == "Отлично") echo 'selected="selected"'; ?>>Отлично</option>
</select><br />
<label>Оценка рецензента: </label>
<select id="type" name="mark12">
	<option value="1">Нет</option>
	<option value="2" <?php if ($mark['txtReviewerMark'] == "Неудовлетворительно") echo 'selected="selected"'; ?>>Неудовлетворительно</option>
    <option value="3" <?php if ($mark['txtReviewerMark'] == "Удовлетворительно") echo 'selected="selected"'; ?>>Удовлетворительно</option>
    <option value="4" <?php if ($mark['txtReviewerMark'] == "Хорошо") echo 'selected="selected"'; ?>>Хорошо</option>
	<option value="5" <?php if ($mark['txtReviewerMark'] == "Отлично") echo 'selected="selected"'; ?>>Отлично</option>
</select><br />
<label>Оценка комиссии: </label>
<select id="type" name="mark3">
	<option value="1">Нет</option>
	<option value="2" <?php if ($mark['txtСommissionMark'] == "Неудовлетворительно") echo 'selected="selected"'; ?>>Неудовлетворительно</option>
    <option value="3" <?php if ($mark['txtСommissionMark'] == "Удовлетворительно") echo 'selected="selected"'; ?>>Удовлетворительно</option>
    <option value="4" <?php if ($mark['txtСommissionMark'] == "Хорошо") echo 'selected="selected"'; ?>>Хорошо</option>
	<option value="5" <?php if ($mark['txtСommissionMark'] == "Отлично") echo 'selected="selected"'; ?>>Отлично</option>
</select><br />
<label>Итоговая оценка: </label>
<select id="type" name="mark4">
	<option value="1">Нет</option>
	<option value="2" <?php if ($mark['txtFinalMark'] == "Неудовлетворительно") echo 'selected="selected"'; ?>>Неудовлетворительно</option>
    <option value="3" <?php if ($mark['txtFinalMark'] == "Удовлетворительно") echo 'selected="selected"'; ?>>Удовлетворительно</option>
    <option value="4" <?php if ($mark['txtFinalMark'] == "Хорошо") echo 'selected="selected"'; ?>>Хорошо</option>
	<option value="5" <?php if ($mark['txtFinalMark'] == "Отлично") echo 'selected="selected"'; ?>>Отлично</option>
</select><br />
<input id="submit" type="submit" name="mark<?php echo $intStudentId; ?>" value="Выставить" />
</form>
</div> 
