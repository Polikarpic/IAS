<?php


include_once("db.php");
session_start();  


	#доступ только у преподавателя
	if ($_SESSION["statusId"] != 1){ header("Location: ./../home"); exit(); }
	
	
if (!$_FILES['f']['error']){
	/* проверка размера файла */
	if($_FILES["f"]["size"] > 1024*10*1024)
	{
		//echo "Размер файла превышает десять мегабайт";
		header("Location: ./../training_aids.php?ta=fail&reason=size");
		exit();
    }
	
	/* проверка расширения */
	$ext = substr(strrchr($_FILES['f']['name'], '.'), 1);
	

	$uploaddir = "./../doc/";

	#получаем id номер для нового документа
	$max = mysql_query("SELECT MAX(intMaterialsId) as max FROM tblMaterials");
	$max = mysql_fetch_array($max);
	$max["max"]++;    		

	$temp = md5(uniqid(rand(),1).$max["max"]).".".$ext;
	$uploadfile = $uploaddir.$temp;
	$now_time = date("Y-m-d H:i:s",mktime(date('H') + 4,date('i'),date('s'),date('m'),date('d'),date('Y'))); 	
		
	move_uploaded_file($_FILES['f']['tmp_name'], $uploadfile);
	$slink = "doc/".$temp;
	
	#добавляем информацию о документе в БД
	mysql_query("INSERT INTO `tblMaterials` (`intMaterialsId`, `txtName`, `txtLink`, `intSenderId`, `datDate`) VALUES ('".$max["max"]."', '".safe_var($_FILES["f"]["name"])."', '".safe_var($slink)."', '".$_SESSION["userId"]."', '".$now_time."')"); 
	header("Location: ./../training_aids?ta=ok"); exit();
} 
else 
{
	// "Файл для загрузки не выбран"
	header("Location: ./../training_aids?ta=fail&reason=not_file");
	exit();
}


	
?>