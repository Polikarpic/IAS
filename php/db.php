<?php
//


// соединяемся с БД
$db = mysql_connect('mysql.hostinger.ru','u862070688_admin','161514');
mysql_select_db('u862070688_db',$db);
mysql_query("SET NAMES utf8");



function safe_var ($var) // защита переменных
{
	$var = trim($var);
	$var = mysql_real_escape_string($var);
	$var = htmlspecialchars($var);
	return $var;
}

function getFullName ($id) // получает ФИО пользователя (Фамилия И. О.)
{
	$query = mysql_query("SELECT txtSurname, txtName, txtSecondName FROM tblUsers WHERE intUserId='".safe_var($id)."' LIMIT 1");
    $data = mysql_fetch_array($query);
	$ret = $data["txtSurname"]." ".mb_substr($data["txtName"],0,1,'UTF-8').". ".mb_substr($data["txtSecondName"],0,1,'UTF-8').". ";
	return $ret;
}

function getMessCount ($id) // получает количество сообщений пользователя
{
	$query = mysql_query("SELECT COUNT(*) as max FROM tblMessage WHERE intRecipientId='".safe_var($id)."'");
    $data = mysql_fetch_array($query);
	return $data["max"];
}

function getAlertCount ($id) // получает количество оповещений пользователя
{
	$query = mysql_query("SELECT COUNT(*) as amax FROM tblNotification WHERE intRecipientId='".safe_var($id)."'");
    $data = mysql_fetch_array($query);
	return $data["amax"];
}

function getMessCountNR ($id) // получает количество непрочитанных сообщений пользователя
{
	$query = mysql_query("SELECT COUNT(*) as max FROM tblMessage WHERE intRecipientId='".safe_var($id)."' and boolCheck='0'");
    $data = mysql_fetch_array($query);
	return $data["max"];
}

function getAlertCountNR ($id) // получает количество непрочитанных оповещений пользователя
{
	$query = mysql_query("SELECT COUNT(*) as amax FROM tblNotification WHERE intRecipientId='".safe_var($id)."' and boolCheck='0'");
    $data = mysql_fetch_array($query);
	return $data["amax"];
}


function lastNews() #вывод последней новости
{

$max = 400; # максимальное число символов для вывода

	#студент
	if ($_SESSION["statusId"] == 0)
		$query = mysql_query("SELECT txtText, datDate FROM tblNews WHERE 
		(intGroup_of_readers = '".$_SESSION["statusId"]."' or intGroup_of_readers=NULL) 
		and (intDirectionId = '".$_SESSION["directionId"]."' or intDirectionId=NULL)
		and (intCourseId ='".$_SESSION["courseId"]."' or intCourseId=NULL)
		ORDER BY intNewsId DESC LIMIT 1");	
	#другие группы пользователей
	else
		$query = mysql_query("SELECT txtText, datDate FROM tblNews WHERE 
		(intGroup_of_readers = '".$_SESSION["statusId"]."' or intGroup_of_readers=NULL) 
		and (intChairId = '".$_SESSION["chairId"]."' or intChairId=NULL)
		ORDER BY intNewsId DESC LIMIT 1");	

$data = mysql_fetch_assoc($query);

$string = substr($data["txtText"], 0, $max+1);

if (strlen($data["txtText"]) > $max)
{
    $string = wordwrap($data["txtText"], $max);
    $i = strpos($string, "\n");
    if ($i) {
        $string = substr($string, 0, $i).'...<a href="news.php" title="Подробнее">>></a>';
    }
}
return $string;
}

// возвращает массив со списком студентов, выполняющих работу
function split_studentId($txtStudentId){ 
    $r = explode(" ",$txtStudentId);
	$last_n = sizeof($r) - 1;
	unset($r[$last_n]);
	unset($r[0]);
	return $r;
}


#настройки для автоматического скачивания файла со страницы
function header_autosave ($file,$filename)
{
    header ('Content-Type: application/octet-stream');
    header ('Accept-Ranges: bytes');
    header ('Content-Length: '.filesize($file));
    header ('Content-Disposition: attachment; filename='.$filename);
	readfile($file);
}

#считает количество файлов в папке reports и возвращает имя нового файла
function get_count_reports($ext)
{
 $dirPath = './../doc/reports/';
 $items = scandir($dirPath);

  $cnt = 0;

  foreach ($items as $item)
    if (is_file($item))
      $cnt ++;
	  
	return $dirPath.md5(uniqid(rand(),1).($cnt+1)).'.'.$ext;


}

?>