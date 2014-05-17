<?php

include_once("tcpdf/tcpdf.php");
include_once("db.php");
session_start();  

#доступ только у методиста
if ($_SESSION["statusId"] != 2){ $_SESSION["um"] = 'e0'; header("Location: ./../home"); exit(); }

# настройки цветов
$textColour = array( 0, 0, 0 );
$headerColour = array( 100, 100, 100 );
$tableHeaderTopTextColour = array( 0, 0, 0 ); //цвет заголовка
$tableHeaderTopFillColour = array( 255, 255, 255 ); //фон заголовка
$tableBorderColour = array( 0, 0, 0 ); //рамка
$tableRowFillColour = array( 255, 255, 255 ); //ячейки фон

#тип работ, включённых в отчёт
$workType = safe_var($_POST["workType"]);
$course = safe_var($_POST["course"]);
$typeDoc = safe_var($_POST["type_doc"]);
$direction = safe_var($_POST["direction"]);

#получаем информацию о работах
$query = mysql_query("SELECT txtStudentId, txtTopic, intTeacherId, intWorkId FROM tblWork WHERE txtStudentId is not null and boolType='".safe_var($workType)."' and txtCourse='".$course."' and intDirectionId ='".$direction."'");
	
#получаем крайние сроки сдачи отчёта и работы
$query_deadlines = mysql_query("SELECT * FROM tblDeadlines ORDER BY intDeadlinesId DESC LIMIT 1");
$deadlines = mysql_fetch_array($query_deadlines);

if ($workType == 0) $workType = 'курсовых работ';
else $workType = 'выпускных кваллификационных работ';
				
$i = 1;
while($data = mysql_fetch_array($query))
{
		#список студентов, выполняющих работу
		$students = split_studentId($data['txtStudentId']);
		$max = sizeof($students);
		for ($j = 1; $j <= $max; $j++)
		{

			#получаем информацию о студенте
			$query_u = mysql_query("SELECT * FROM tblUsers WHERE intUserId='".$students[$j]."'");
			$student = mysql_fetch_array($query_u);	
			
			#получаем дату сдачи отчёта и работы
			$query_rep =  mysql_query("SELECT datDate FROM tblReview WHERE boolType='1' ORDER BY intReviewId DESC LIMIT 1");
			$report_date = mysql_fetch_array($query_rep);	
			
			#получаем дату сдачи отчёта и работы
			$query_w =  mysql_query("SELECT datDate FROM tblReview WHERE boolType='4' ORDER BY intReviewId DESC LIMIT 1");
			$work_date = mysql_fetch_array($query_w);				
			
			#оценки
			$query_m = mysql_query("SELECT * FROM tblMark WHERE intWorkId='".$data['intWorkId']."' and intStudentId='".$students[$j]."' LIMIT 1");
			$mark =	mysql_fetch_array($query_m);	
			
			#рецензент
			$query_r = mysql_query("SELECT * FROM tblReviewers WHERE intWorkId='".$data["intWorkId"]."' LIMIT 1");
			$reviewers = mysql_fetch_array($query_r);	
			
			#получаем название направления
			$query_direction = mysql_query("SELECT * FROM tblDirection WHERE intDirectionId='".$student["intDirectionId"]."' LIMIT 1");
			$direction = mysql_fetch_array($query_direction);
			
			$teacher = getFullName($data['intTeacherId']);	
			
			if (mysql_num_rows($query_r) == 0) $reviewer = $teacher;
			else $reviewer =  getFullName($reviewers["intTeacherId"]);	
					
			if ($mark['txtTeacherMark'] == 'Нет') $mark['txtTeacherMark'] = '';
			if ($mark['txtReviewerMark'] == 'Нет') $mark['txtReviewerMark'] = '';
			if ($mark['txtFinalMark'] == 'Нет') $mark['txtFinalMark'] = '';
						
			if (!in_array($direction["intDirectionId"],$direction_once_list)) $direction_list .= " \"".$direction["txtDirectionName"]."\"";
			
			$direction_once_list[] = $direction["intDirectionId"];
			
			$cell .= '<tr>';
			$cell .= '<td>'.$i.'</td>';
			$cell .= '<td>'.$student["txtSurname"]." ".$student["txtName"]." ".$student["txtSecondName"].'</td>';
			$cell .= '<td>'.$student["txtGroup"].'</td>';
			$cell .= '<td>'.$teacher.'</td>';
			$cell .= '<td>'.date('Y-m-d',strtotime($report_date["datDate"])).'</td>';
			$cell .= '<td>'.$mark['txtTeacherMark'].'</td>';
			$cell .= '<td>'.date('Y-m-d',strtotime($work_date["datDate"])).'</td>';
			$cell .= '<td>'.$mark['txtTeacherMark'].'</td>';
			$cell .= '<td>'.$mark['txtReviewerMark'].'</td>';
			$cell .= '<td>'.$mark['txtFinalMark'].'</td>';
			$cell .= '<td>'.$data["txtTopic"].'</td>';
			$cell .= '<td>'.$reviewer.'</td>';						
			$cell .= '</tr>';
			if ($j != $max) $i++;
		}
		$i++;
}

if ($typeDoc == 0)
{

$pdf = new TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, 'UTF-8');
$pdf->SetTextColor( $textColour[0], $textColour[1], $textColour[2] );
$pdf->AddPage();

$pdf->SetFont( 'Tahoma', 'B', 8 );

$pdf->writeHTML('
<table border="1" cellpadding="5" width="100%" text-align="center" style="word-wrap: break-word;">
		<tr>
			<td colspan="12">Результаты защиты '.$workType.'</td>
		</tr>
		<tr>
			<td colspan="12">Список студентов '.$course.' курса, спец-сть <b>'.$direction_list.'</b></td>
		</tr>
		<tr>
			<td width="30px">№</td>	
			<td>Ф.И.О.</td>	
			<td>№ группы</td>	
			<td>Руководитель</td>	
			<td>Дата пред. пром. отч. '.$deadlines["dateRentingReport"].'</td>	
			<td>Оценка руков.</td>	
			<td>Дата пред. курс. раб. '.$deadlines["dateRentingWork"].'</td>	
			<td>Оценка руков.</td>	
			<td>Оценка реценз.</td>	
			<td>Итог. оценка</td>	
			<td width="100px">Тема курсовой работы</td>				
			<td>Рецензент</td>					
		</tr>'.$cell.'</table>', true, false, true, false, '');


  $file = get_count_reports('pdf');
  $pdf->Output($file, 'F');
  header_autosave($file,'report.pdf');
}
else
{

$table = '
<table border="1" cellpadding="5" width="100%" text-align="center" style="word-wrap: break-word;">
		<tr>
			<td colspan="13">Результаты защиты '.$workType.'</td>
		</tr>
		<tr>
			<td colspan="13">Список студентов '.$course.' курса, спец-сть <b>'.$direction_list.'</b></td>
		</tr>
		<tr>
			<td width="30px">№</td>	
			<td>Ф.И.О.</td>	
			<td>№ группы</td>	
			<td>Руководитель</td>	
			<td>Дата пред. пром. отч. '.$deadlines["dateRentingReport"].'</td>	
			<td>Оценка руков.</td>	
			<td>Дата пред. курс. раб. '.$deadlines["dateRentingWork"].'</td>	
			<td>Оценка руков.</td>	
			<td>Оценка реценз.</td>	
			<td>Итог. оценка</td>	
			<td width="100px">Тема курсовой работы</td>				
			<td>Рецензент</td>					
		</tr>'.$cell.'</table>';

$str = 'MIME-Version: 1.0
Content-Location:
Content-Transfer-Encoding: quoted-printable
Content-Type: text/html; charset="windows-1251"

<html xmlns:o=3D"urn:schemas-microsoft-com:office:office"
xmlns:w=3D"urn:schemas-microsoft-com:office:word"
xmlns=3D"http://www.w3.org/TR/REC-html40">

<head>
<meta http-equiv=3DContent-Type content=3D"text/html; charset=3Dwindows-1251">
<meta name=3DProgId content=3DWord.Document>
<meta name=3DGenerator content=3D"Microsoft Word 11">
<meta name=3DOriginator content=3D"Microsoft Word 11">
<link rel=3DFile-List href=3D"test.files/filelist.xml">
<!--[if gte mso 9]><xml>
 <w:WordDocument>
  <w:View>Print</w:View>
  <w:GrammarState>Clean</w:GrammarState>
  <w:ValidateAgainstSchemas/>
  <w:SaveIfXMLInvalid>false</w:SaveIfXMLInvalid>
  <w:IgnoreMixedContent>false</w:IgnoreMixedContent>
  <w:AlwaysShowPlaceholderText>false</w:AlwaysShowPlaceholderText>
  <w:BrowserLevel>MicrosoftInternetExplorer4</w:BrowserLevel>
 </w:WordDocument>
</xml><![endif]--><!--[if gte mso 9]><xml>
 <w:LatentStyles DefLockedState=3D"false" LatentStyleCount=3D"156">
 </w:LatentStyles>
</xml><![endif]-->
<style>
<!--
 /* Style Definitions */
 p.MsoNormal, li.MsoNormal, div.MsoNormal
    {mso-style-parent:"";
    margin:0cm;
    margin-bottom:.0001pt;
    mso-pagination:widow-orphan;
    font-size:12.0pt;
    font-family:"Tahoma";
    mso-fareast-font-family:"Tahoma";}
@page Section1
    {size:595.3pt 841.9pt;
    margin:18.0pt 19.3pt 18.0pt 18.0pt;
    mso-header-margin:35.4pt;
    mso-footer-margin:35.4pt;
    mso-paper-source:0;}
div.Section1
    {page:Section1;}
-->
</style>
<!--[if gte mso 10]>
<style>
 /* Style Definitions */
 table.MsoNormalTable
    {mso-style-name:"\041E\0431\044B\0447\043D\0430\044F \0442\0430\0431\043B\=
0438\0446\0430";
    mso-tstyle-rowband-size:0;
    mso-tstyle-colband-size:0;
    mso-style-noshow:yes;
    mso-style-parent:"";
    mso-padding-alt:0cm 5.4pt 0cm 5.4pt;
    mso-para-margin:0cm;
    mso-para-margin-bottom:.0001pt;
    mso-pagination:widow-orphan;
    font-size:10.0pt;
    font-family:"Tahoma";
    mso-ansi-language:#0400;
    mso-fareast-language:#0400;
    mso-bidi-language:#0400;
    width:100%;
}

td.br1{
    border:1px solid black;
}
</style>
<![endif]-->
</head>

<body>
'.$table.'
</body>

</html>';


$file = get_count_reports('doc');
$fp = fopen ($file, "w");
fwrite($fp,$str);
fclose($fp);
header_autosave($file,'report.doc');

}
	
?>