<?php



include_once("db.php");
session_start();  

	$old_password = safe_var($_POST['old_password']);
	$new_password = safe_var($_POST['new_password']);
	$confirm_password = safe_var($_POST['confirm_password']);

	#�������� ���������� � ������ ������������
	$query = mysql_query("SELECT txtPass FROM tblUsers WHERE intUserId='".$_SESSION["userId"]."' LIMIT 1");
    $pass = mysql_fetch_array($query);
		
	#��������� ��������� �� ������
	if ($pass["txtPass"] === md5(md5('c2tsE}DkAJK!]W%'.$old_password.'QdmrA}SM(Z4n?\/'))){
		if ($new_password === $confirm_password){ #��������� �� ������ �������������
			#��������� ������ ������������
			mysql_query("UPDATE tblUsers SET txtPass='".md5(md5('c2tsE}DkAJK!]W%'.$new_password.'QdmrA}SM(Z4n?\/'))."' WHERE intUserId='".$_SESSION["userId"]."'");
			
			$_SESSION["um"] = 'i17';
			header("Location: ./../settings");
		} 
		else 
		{
			$_SESSION["um"] = 'e7';
			header("Location: ./../settings"); exit();
		}	
	}
	else 
	{
		$_SESSION["um"] = 'e8';
		header("Location: ./../settings"); exit();
	}
?>