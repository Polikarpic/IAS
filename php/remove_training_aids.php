<?php
include_once("db.php");
session_start();  
	
	#доступ
	if ($_SESSION["statusId"] == 0){ header("Location: ./home"); exit(); }
	
	$query = mysql_query("SELECT * FROM tblMaterials WHERE intMaterialsId='".safe_var($_GET["z"])."' LIMIT 1");
	$data = mysql_fetch_array($query);	
		
	#удаляем документ
	mysql_query("DELETE FROM tblMaterials WHERE intMaterialsId='".safe_var($_GET["z"])."'");
  	
	#удаляем документ с сервера
	unlink("./../".$data["txtLink"]);
		
	#возврат на главную страницу
	header("Location: ./../training_aids?remove_ta=ok");	
?>