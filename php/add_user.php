<?php


include_once("db.php");
session_start();  

	#������
	if ($_SESSION["statusId"] == 0){ $_SESSION["um"] = 'e0'; header("Location: ./../home"); exit(); }
	
	$login = safe_var($_POST['login']);
	$pass = safe_var($_POST['pass']);
	$pass = md5(md5('c2tsE}DkAJK!]W%'.safe_var($pass).'QdmrA}SM(Z4n?\/'));	
	$status = safe_var($_POST['status']);	
	$surname = safe_var($_POST['surname']);
	$name = safe_var($_POST['name']);
	$secondname = safe_var($_POST['secondname']);
	$course = safe_var($_POST['course']);
	$group = safe_var($_POST['group']);
	$direction = safe_var($_POST['direction']);
	
	#�������� �� ��������� ������
	$query = "select * from `tblUsers` where txtLogin='".$login."'";
	$result = mysql_query($query);
	
	if(!mysql_fetch_array($result)) #����� �� �����
	{	
		if ($status != 0) # �� �������
		{
			$chair = safe_var($_POST['chair']);
			mysql_query("INSERT INTO `tblUsers` (`intUserId`, `txtSurname`, `txtName`, `txtSecondName`, `txtLogin`, `txtPass`, `intStatusId`, `intChairId`, `txtCourse`, `intDirectionId`) VALUES (NULL, '".$surname."', '".$name."', '".$secondname."', '".$login."', '".$pass."', '".$status."', '".$chair."', NULL, NULL)");
		}
		else #�������
		{
			mysql_query("INSERT INTO `tblUsers` (`intUserId`, `txtSurname`, `txtName`, `txtSecondName`, `txtLogin`, `txtPass`, `intStatusId`, `intChairId`, `txtCourse`, `txtGroup`, `intDirectionId`) VALUES (NULL, '".$surname."', '".$name."', '".$secondname."', '".$login."', '".$pass."', '".$status."', NULL, '".$course."', '".$group."', '".$direction."')");
		}
		
		$_SESSION["um"] = 'i13';
		header("Location: ./../home");
	}
	else #������������ � ����� ������� ��� ����������
	{
		$_SESSION["um"] = 'e6';
		header("Location: ./../add_user");	
	}
?>