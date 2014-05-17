<?php 
include_once("./../db.php");
session_start();	

#получаем крайние сроки сдачи отчёта и работы
$query_deadlines_r = mysql_query("SELECT * FROM tblDeadlines ORDER BY intDeadlinesId DESC LIMIT 1");

#получаем крайние сроки сдачи отчёта и работы
$query_deadlines_w = mysql_query("SELECT * FROM tblDeadlines ORDER BY intDeadlinesId DESC LIMIT 1");

?>


<form action="php/add_deadline.php" method="post">
	<select name="users">
		<option value="0">Выберите работу</option>
		<?php
		while($data = mysql_fetch_array($query_deadlines_r))
		{
			if ($data["boolType"] == 0) $type_work = "КР";
			else $type_work = "ВКР";

			echo '<option value="'.$data["intWorkId"].'">['.$type_work.'] '.$data["txtTopic"].'</option>';			
		}
		?>
	</select><br />
	
	<select name="users" id="filter_deadline" onchange="filter_func();">
		<option value="0">Выберите работу</option>
		<?php
		while($data = mysql_fetch_array($query_deadlines_w))
		{
			if ($data["boolType"] == 0) $type_work = "КР";
			else $type_work = "ВКР";

			echo '<option value="'.$data["intWorkId"].'">['.$type_work.'] '.$data["txtTopic"].'</option>';			
		}
		?>
	</select><br />
<input id="submit" type="submit" name="send" value="Сохранить" />
</form>