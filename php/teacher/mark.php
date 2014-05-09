<?php


include_once("php/db.php");
session_start(); 

	$m = 'mark'.$intStudentId;
	if (isset($_POST[$m]))
{
	$list_mark = array (1 => 'Нет', 'Неудовлетворительно', 'Удовлетворительно', 'Хорошо', 'Отлично');
	$mark1 = 'Нет'; $mark2 = 'Нет'; $mark3 = 'Нет'; $mark4 = 'Нет'; 
	$mark1 = $list_mark[$_POST["mark1"]];
 
	$now_time = date("Y-m-d H:i:s",mktime(date('H') + 4,date('i'),date('s'),date('m'),date('d'),date('Y'))); 
	
	$query = mysql_query("SELECT * FROM tblMark WHERE intWorkId='".$topic["intWorkId"]."' and intStudentId='".safe_var($intStudentId)."'");
	if (mysql_num_rows($query) != 0) 
	{
		mysql_query("UPDATE  tblMark SET  txtTeacherMark = '$mark1' WHERE intWorkId='".$topic["intWorkId"]."' and intStudentId='".safe_var($intStudentId)."'");
		echo "<script>location.reload();</script>";
	}
	else
	{
		mysql_query("INSERT INTO `tblMark` (`intMarkId`,`intWorkId`,`intStudentId`,`txtTeacherMark`) VALUES (NULL,'".$topic["intWorkId"]."','".safe_var($intStudentId)."','$mark1')");	
		echo "<script>location.reload();</script>";
	}
	
	$alert = mysql_real_escape_string("Преподаватель <a href='profile.php?z=".$topic["intTeacherId"]."'>".$teacher."</a> изменил оценку к вашей работе <b><a href='current_topic.php?z=".$topic["intWorkId"]."'>".$topic["txtTopic"]."</a></b>");
	
	
	#оповещение для студента о оценке
	mysql_query("INSERT INTO `tblNotification` (`intNotificationId`, `intRecipientId`, `txtMessage`, `boolCheck`, `datDate`) VALUES (NULL, '".safe_var($intStudentId)."', '".$alert."', '0', '".$now_time."')");
	
	
	
	
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
<input id="submit" type="submit" name="mark<?php echo $intStudentId; ?>" value="Выставить" />
</form>
</div> 
