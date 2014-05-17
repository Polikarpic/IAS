<?php
setlocale(LC_ALL, 'ru_RU.UTF-8');

// соединяемся с БД
$db = mysql_connect('localhost','iaspetrsu_test','zgBvABud');
mysql_select_db('iaspetrsu_test',$db);
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
	$query = mysql_query("SELECT COUNT(*) as max FROM tblMessage WHERE intRecipientId='".safe_var($id)."' and boolType='0'");
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
		(intGroup_of_readers = '".$_SESSION["statusId"]."' or intGroup_of_readers  is null) 
		and (intDirectionId = '".$_SESSION["directionId"]."' or intDirectionId  is null)
		and (intCourse ='".$_SESSION["courseId"]."' or intCourse is null)
		ORDER BY intNewsId DESC LIMIT 1");	
	#другие группы пользователей
	else
		$query = mysql_query("SELECT txtText, datDate FROM tblNews WHERE 
		(intGroup_of_readers = '".$_SESSION["statusId"]."' or intGroup_of_readers  is null) 
		and (intChairId = '".$_SESSION["chairId"]."' or intChairId  is null) or (intSenderId='".$_SESSION["userId"]."')
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


#вывод сообщений об ошибке
function error_msg ($error)
{
	if (empty($error)) return;	
	echo '<div id="edge" class="error">';
	echo $error;
	echo '</div>';
}


#вывод сообщений о успешной работе
function info_msg ($info)
{
	if (empty($info)) return;	
	echo '<div id="edge" class="info">';
	echo $info;
	echo '</div>';
}


#возвращает пользовательские настройки
#принимает номер настройки
function get_user_settings($setId = 'NULL', $uId = 'NULL')
{
	if ($uId == 'NULL') $uId = $_SESSION["userId"];

	$query = mysql_query("SELECT * FROM tblSettings WHERE intUserId='".safe_var($uId)."'");
	$data = mysql_fetch_array($query);
	
	#если не нашли запись
	if (mysql_num_rows($query) == 0) $data["txtSettings"] = '0;0;0;0;0';
	
	$r = explode(";",$data["txtSettings"]);
	$setId--;
	if ($setId != 'NULL') return $r[$setId];
	else return $r;
}

#устанавливает настройки пользователя
function set_user_settings($setId, $valId)
{
	$settings = get_user_settings();
	$setId--;
	$settings[$setId] = $valId;	
	$str = implode(';',$settings);
	
	$query = mysql_query("SELECT * FROM tblSettings WHERE intUserId='".$_SESSION["userId"]."'");
	if (mysql_num_rows($query) != 0) 
	{
		mysql_query("UPDATE tblSettings SET txtSettings='".$str."' WHERE intUserId='".$_SESSION["userId"]."'");
	}
	else
	{
		mysql_query("INSERT INTO `tblSettings` (`intUserId`,`txtSettings`) VALUES ('".$_SESSION["userId"]."','".$str."')");	
	}
}


#осуществляет отправку оповещений на почту пользователю
function notification_by_mail($uId = 'NULL', $nId, $aInf = ''){
	
	#проверяем включены ли оповещения у пользователя
	#если не включены - оповещать не нужно
	if (!get_user_settings(4, $uId)) return;
	
	#получаем почтовый ящик пользователя
	$query = mysql_query("SELECT txtMail FROM tblUsers WHERE intUserId='".safe_var($uId)."' LIMIT 1");
    $data = mysql_fetch_array($query);
	
	#если почта не задана - конец алгоритма
	if (empty($data["txtMail"])) return;
	
	
	
	if ($nId == 'news')
	{
		$message = '<p>Здравствуйте!</p> <p>На сайте <a href="ias-petrsu.ru"><b>ias-petrsu.ru</b></a> появилась новая новость, которая, возможно, будет Вам интересна.</p> <p></p>';
	}
	elseif ($nId == 'mess')
	{
		$message = '<p>Здравствуйте!</p> <p>Вы получили новое личное сообщение на сайте <a href="ias-petrsu.ru"><b>ias-petrsu.ru</b></a>.</p>'.$aInf;	
	}
	else 
	{
		$message = '<p>Здравствуйте!</p> <p>Вы получили новое оповещение на сайте <a href="ias-petrsu.ru"><b>ias-petrsu.ru</b></a>.</p><hr /><p>НЕ ОТВЕЧАЙТЕ НА ЭТО ПИСЬМО!</p> <p>Вы можете просмотреть оповещение по указанной ниже ссылке: <br /> <a href="ias-petrsu.ru/message">ias-petrsu.ru/alert</a></p>';	
	}
	
	$to = $data["txtMail"];
	$subject = 'ias-petrsu.ru';	
	$headers = 'From: ias-petrsu.ru' . "\r\n" .
    'Reply-To: ' . "\r\n";	
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
	mail($to, $subject, $message, $headers);
}


#осуществляет поиск и вывод сообщений для пользователя
function um()
{
	session_start();
	if (empty($_SESSION["um"])) return;	
	
	#определяем тип сообщения 
	$mT = mb_substr($_SESSION["um"],0,1,'UTF-8');	
	
	$m = '';
	switch($_SESSION["um"])
	{
		case 'e0': $m = 'У Вас нет прав доступа к этой странице'; break; 
		case 'e1': $m = 'Вы уже подавали заявку на выполнение этой работы'; break; 
		case 'e2': $m = 'Вы уже выполняете другую работу'; break; 
		case 'e3': $m = 'Вы уже подали заявки на 5 работ. Пожалуйста, подождите пока преподаватели рассмотрят их'; break; 
		case 'e4': $m = 'Максимальный размер загружаемого файла: 10мб'; break; 
		case 'e5': $m = 'Произошла ошибка при загрузке файла'; break; 
		case 'e6': $m = 'Пользователь с таким логином уже существует'; break; 
		case 'e7': $m = 'Неверный пароль подтверждения'; break;
		case 'e8': $m = 'Пароли не совпадают'; break;
		case 'e9': $m = 'Неверный формат ввода телефона'; break;
		case 'e10': $m = 'Неверный формат ввода адреса электронной почты'; break;
		case 'e11': $m = 'Нельзя удалить кафедру, если она используется хотя бы одним пользователем'; break;
		case 'e12': $m = 'Нельзя удалить направление, если оно используется хотя бы одним пользователем'; break;
		case 'e13': $m = 'Запрашиваемая тема работы не найдена'; break;
		case 'e14': $m = 'Вы не можете отправить сообщение самому себе'; break;
		case 'e15': $m = 'Пользователь для отправки сообщения не найден'; break;
		case 'e16': $m = 'Запрашиваемая работа не найдена'; break;
		case 'e17': $m = 'Неверное расширение файла. Поддерживаемые расширения: pdf, doc, docx, docm'; break; 
				
		case 'i1': $m = 'Вы успешно отклонили заявку студента на выполнение работы'; break;
		case 'i2': $m = 'Заявка на выполнение работы была успешно подана'; break;
		case 'i3': $m = 'Тема была успешно отредактирована'; break;
		case 'i4': $m = 'Тема была успешно удалена'; break;	
		case 'i5': $m = 'Вы успешно одобрили заявку студента на выполнение работы'; break;
		case 'i6': $m = 'Тема была успешно создана'; break;	
		case 'i7': $m = 'Сообщение было успешно отправлено'; break;	
		case 'i8': $m = 'Вы успешно добавили кафедру'; break;
		case 'i9': $m = 'Вы успешно установили новые крайние сроки сдачи работ'; break;
		case 'i10': $m = 'Вы успешно добавили направление'; break;
		case 'i11': $m = 'Вы успешно добавили новость'; break;
		case 'i12': $m = 'Вы успешно добавили документ'; break;
		case 'i13': $m = 'Пользователь был успешно добавлен'; break;
		case 'i14': $m = 'Адрес электронной почты был успешно изменён'; break;
		case 'i15': $m = 'Телефон был успешно изменён'; break;
		case 'i16': $m = 'Настройки оповещений были успешно изменены'; break;
		case 'i17': $m = 'Пароль был успешно изменён'; break;
		case 'i18': $m = 'Пользователь был успешно добавлен'; break;	
		case 'i19': $m = 'Тема была успешно скопирована'; break;		
		case 'i20': $m = 'Кафедра была успешно удалена'; break;	
		case 'i21': $m = 'Направление было успешно удалено'; break;	
		case 'i22': $m = 'Сообщение было успешно удалено'; break;	
		case 'i23': $m = 'Новость была успешно удалена'; break;	
		case 'i24': $m = 'Оповещение было успешно удалено'; break;	
		case 'i25': $m = 'Заявка на выполнение работы была успешно отозвана'; break;
		case 'i26': $m = 'Вы успешно отказались от выполнения работы'; break;
		case 'i27': $m = 'Вы успешно изменили настройки оповещений'; break;
		case 'i28': $m = 'Вы успешно изменили оценку студента'; break;
		case 'i29': $m = 'Вы успешно назначили рецензента к работе'; break;
		case 'i30': $m = 'Пользователь был успешно отредактирован'; break;
		case 'i31': $m = 'Пользователь был успешно удалён'; break;
		case 'i32': $m = 'Документ был успешно удалён'; break;
		case 'i33': $m = 'Работа была успешно закрыта и перемещена в архив'; break;
		case 'i34': $m = 'Работа была успешно открыта'; break;
		
	}
	
	unset($_SESSION["um"]);
	if ($mT == 'e') error_msg($m);
	else info_msg($m);
	
}








?>