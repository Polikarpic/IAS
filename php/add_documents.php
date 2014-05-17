<?php


include_once("db.php");
session_start();  


#доступ
if ($_SESSION["statusId"] == 0){ $_SESSION["um"] = 'e0'; header("Location: ./../home"); exit(); }
	


	
if (!$_FILES['f']['error']){
	/* проверка размера файла */
	if($_FILES["f"]["size"] > 1024*10*1024)
	{
		$_SESSION["um"] = 'e4';
		header("Location: ./../documents");
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
	$now_time = date("Y-m-d H:i:s",mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y')));	
		
	move_uploaded_file($_FILES['f']['tmp_name'], $uploadfile);
	$slink = "doc/".$temp;
	
	if ($_POST["fname"] != '') $dname = $_POST["fname"];
	else $dname = $_FILES["f"]["name"];
	
	#добавляем информацию о документе в БД
	mysql_query("INSERT INTO `tblMaterials` (`intMaterialsId`, `txtName`, `txtLink`, `intSenderId`, `datDate`) VALUES ('".$max["max"]."', '".safe_var($dname)."', '".safe_var($slink)."', '".$_SESSION["userId"]."', '".$now_time."')"); 
	
	
	$_SESSION["um"] = 'i12';
	header("Location: ./../documents"); exit();
} 
elseif (!empty($_POST["wlink"]))
{
/* проверка расширения */
$ext = substr(strrchr($_POST["wlink"], '.'), 1);	

$uploaddir = "./../doc/";

#получаем id номер для нового документа
$max = mysql_query("SELECT MAX(intMaterialsId) as max FROM tblMaterials");
$max = mysql_fetch_array($max);
$max["max"]++;    		
$now_time = date("Y-m-d H:i:s",mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y')));	

if ($_POST["fname"] != '') $dname = $_POST["fname"];
	else $dname = $_POST["wlink"];
	
#добавляем информацию о документе в БД
mysql_query("INSERT INTO `tblMaterials` (`intMaterialsId`, `txtName`, `txtLink`, `intSenderId`, `datDate`) VALUES ('".$max["max"]."', '".safe_var($dname)."', '".safe_var($_POST["wlink"])."', '".$_SESSION["userId"]."', '".$now_time."')"); 
	
	
$_SESSION["um"] = 'i12';
header("Location: ./../documents"); exit();
}
else 
{
	$_SESSION["um"] = 'e5';
	header("Location: ./../documents");
	exit();
}


	
?>