<?php


include_once("php/db.php");
session_start(); 

	if ($_SESSION["statusId"] == 0){ $_SESSION["um"] = 'e0'; header("Location: ./../../home"); exit();}

	if (isset($_POST["assign_reviewer"]))
	{
		$rid = $_POST["reviewer"];

		$query = mysql_query("SELECT * FROM tblReviewers WHERE intWorkId='".$topic["intWorkId"]."' LIMIT 1");
		if (mysql_num_rows($query) == 0)		
			mysql_query("INSERT INTO tblReviewers (`intReviewersId`, `intWorkId`, `intTeacherId`) VALUES (NULL, '".$topic["intWorkId"]."', '".$rid."')");
		else
			mysql_query("UPDATE tblReviewers SET intWorkId ='".$topic["intWorkId"]."', intTeacherId='".$rid."'");
		
		echo '<meta http-equiv="refresh" content="0; url="./../../current_topic?z='.$topic["intWorkId"].'">';

		#ид рецензента, если он есть
		$review_id = mysql_fetch_array(mysql_query("SELECT intTeacherId FROM tblReviewers WHERE intWorkId='".$topic["intWorkId"]."' LIMIT 1"));	
		$now_time = date("Y-m-d H:i:s",mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y')));	
		$alert = mysql_real_escape_string("Преподаватель <a href='profile.php?z=".$topic["intTeacherId"]."'>".$teacher."</a> назначил Вас рецензентом к работе <b><a href='current_topic.php?z=".$topic["intWorkId"]."'>".$topic["txtTopic"]."</a></b>");
		
		#оповещение для студента о оценке
		mysql_query("INSERT INTO `tblNotification` (`intNotificationId`, `intRecipientId`, `txtMessage`, `boolCheck`, `datDate`) VALUES (NULL, '".$review_id["intTeacherId"]."', '".$alert."', '0', '".$now_time."')");
	
		#оповещение на почту для пользователя
		notification_by_mail($review_id["intTeacherId"], 'alert');
		
		$_SESSION["um"] = 'i29';
	}

	
	#получаем список рецензентов
	$reviewers = mysql_query("SELECT txtName, txtSecondName, txtSurname, intUserId, txtAbbreviation
	FROM tblUsers u
	LEFT JOIN tblChair ch ON ch.intChairId = u.intChairId
	WHERE u.intStatusId ='1' and u.intUserId <> '".$_SESSION["userId"]."'");
	
	#ид рецензента, если он есть
	$review_id = mysql_fetch_array(mysql_query("SELECT intTeacherId FROM tblReviewers WHERE intWorkId='".$topic["intWorkId"]."' LIMIT 1"));			
?>

<br />
<div id="edge" class="button">
<h1>Назначить рецензента</h1>
<form action="" method="post">
<label>Рецензент: </label> 
		<select name="reviewer">
		<?php
		while($data = mysql_fetch_array($reviewers))
		{
		if (isset($review_id["intTeacherId"]) && $review_id["intTeacherId"] == $data["intUserId"]) $selected = 'selected="selected"';
		else $selected = '';
		echo '<option value="'.$data["intUserId"].'" '.$selected.'>'.$data["txtSurname"].' '.$data["txtName"].' '.$data["txtSecondName"].' (каф. '.$data["txtAbbreviation"].')</option>';			
		}
		?>
</select>  
<input id="submit" type="submit" name="assign_reviewer" value="Назначить" />
</form>
</div> 
