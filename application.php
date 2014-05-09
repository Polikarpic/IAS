<?php
 

	include_once("php/db.php");
	session_start(); 

	#доступ к странице только для преподавателя
	if ($_SESSION["statusId"] != 1){ header("Location: ./../home.php"); exit();}

	#передан ли номер работы
	if (isset($_GET["z"]))
	{	
		# получаем информацию о заявке
		$query = mysql_query("SELECT * FROM tblList_of_applications WHERE intApplicationsId='".safe_var($_GET["z"])."' and intTeacherId='".$_SESSION["userId"]."'  LIMIT 1");
		$appl = mysql_fetch_array($query);
		
		#проверка на результат запроса
		if (empty($appl) || mysql_num_rows($query) == 0){ header("location: ./alert.php"); exit(); }
		
		#делаем оповещение прочитанным
		mysql_query("UPDATE tblNotification SET boolCheck='1' WHERE intNotificationId='".safe_var($_GET["n"])."' and intRecipientId='".$_SESSION["userId"]."'");
	}
	
	#получаем информацию о работе
	$query = mysql_query("SELECT * FROM tblWork WHERE intWorkId='".$appl["intWorkId"]."' LIMIT 1");
	$work = mysql_fetch_array($query);
	
	
	#получаем информацию о работе
	$check = mysql_query("SELECT * FROM tblWork WHERE txtStudentId LIKE '%".$appl["intStudentId"]."%' LIMIT 1");
	
	$student = getFullName($appl["intStudentId"]);
	
	$title = "Заявка на выполнение работы от студента ".$student;
	$current_menu4 = "current-menu-item current_page_item";
	include_once("templ/block_header.php"); 
	 
?>
<div id="edge">
<p>Тема: <a href="topic.php?z=<?php echo $appl["intWorkId"]; ?>"><b><?php echo $work["txtTopic"]; ?></b></a> </p>
<p>Студент: <i><a href="profile.php?z=<?php echo $appl["intStudentId"]; ?>"><?php echo $student; ?></a></i> </p>
</div>

<?php
if ($appl["boolStatus"] and ($work["intStudentsPerformingWork"] >= $work["txtNumber_of_persons"]))
{
	echo 'Эту работу уже выполняет максимальное число студентов';
}
elseif(mysql_num_rows($check) != 0)
{
	echo 'Студент уже выполняет другую работу';
	echo '<input id="submit" onclick="location.href=\'php/remove_notification.php?&z='.safe_var($_GET["n"]).'\';" type="button" name="send" value="Удалить заявку" />';
}
else
{
	echo '<input id="submit" onclick="if (confirm(\'Bы действительно хотите одобрить заявку?\')) location.href=\'php/take.php?z='.safe_var($_GET["z"]).'&n='.safe_var($_GET["n"]).'\';" type="button" name="send" value="Одобрить заявку" />';
	echo '<input id="submit" onclick="if (confirm(\'Bы действительно хотите отклонить заявку?\')) location.href=\'php/reject.php?z='.safe_var($_GET["z"]).'&n='.safe_var($_GET["n"]).'\';" type="button" name="send" value="Отклонить заявку" />';
}
?>					

<?php include_once("templ/block_footer.php"); ?>	