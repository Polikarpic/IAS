<?php

include_once("tcpdf/tcpdf.php");
include_once("db.php");
session_start();  

#доступ только у руководства кафедр
if ($_SESSION["statusId"] != 3){ header("Location: ./../home"); exit(); }

# настройки цветов
$textColour = array( 0, 0, 0 );
$headerColour = array( 100, 100, 100 );
$tableHeaderTopTextColour = array( 0, 0, 0 ); //цвет заголовка
$tableHeaderTopFillColour = array( 255, 255, 255 ); //фон заголовка
$tableBorderColour = array( 0, 0, 0 ); //рамка
$tableRowFillColour = array( 255, 255, 255 ); //ячейки фон

#курс
$course = safe_var($_POST["course"]);
$typeReport = safe_var($_POST["typeReport"]);
$typeDoc = safe_var($_POST["type_doc"]);

if ($typeReport == 0)
	$query = mysql_query("SELECT intStudentId, txtTeacherMark, txtReviewerMark, txtСommissionMark, txtFinalMark FROM tblMark WHERE txtTeacherMark='Неудовлетворительно' or txtReviewerMark='Неудовлетворительно' or txtСommissionMark='Неудовлетворительно' or txtFinalMark='Неудовлетворительно'");
else
	$query = mysql_query("SELECT intUserId, txtSurname, txtName, txtSecondName, intDirectionId, txtGroup FROM tblUsers WHERE intStatusId='0' and txtCourse='".$course."' ORDER BY txtGroup");
				
$i = 1;
while($data = mysql_fetch_array($query))
{
	if ($typeReport == 0){
		#получаем информацию о студенте
		$query_u = mysql_query("SELECT * FROM tblUsers WHERE intUserId='".$data["intStudentId"]."'");
		$student = mysql_fetch_array($query_u);	
		
		#неподходящий курс смотрим следующего студента
		if ($student["txtCourse"] != $course) continue;
 			
		#получаем название направления
		$query_direction = mysql_query("SELECT * FROM tblDirection WHERE intDirectionId='".$student["intDirectionId"]."' LIMIT 1");
		$direction = mysql_fetch_array($query_direction);
		
		#получаем данные о работе
		$query = mysql_query("SELECT txtTopic FROM tblWork WHERE txtStudentId LIKE '% ".$data["intStudentId"]." %' LIMIT 1");
		$topic = mysql_fetch_array($query);	
			
		if ($data['txtTeacherMark'] == 'Нет') $data['txtTeacherMark'] = '';
		if ($data['txtReviewerMark'] == 'Нет') $data['txtReviewerMark'] = '';
		if ($data['txtСommissionMark'] == 'Нет') $data['txtReviewerMark'] = '';
		if ($data['txtFinalMark'] == 'Нет') $data['txtFinalMark'] = '';	
			
		$cell .= '<tr>';
		$cell .= '<td>'.$i.'</td>';
		$cell .= '<td>'.$student["txtSurname"]." ".$student["txtName"]." ".$student["txtSecondName"].'</td>';
		$cell .= '<td>'.$student["txtGroup"].'</td>';
		$cell .= '<td>'.$direction["txtDirectionName"].'</td>';
		$cell .= '<td>'.$topic["txtTopic"].'</td>';
		$cell .= '<td>'.$data['txtTeacherMark'].'</td>';
		$cell .= '<td>'.$data['txtReviewerMark'].'</td>';
		$cell .= '<td>'.$data['txtСommissionMark'].'</td>';
		$cell .= '<td>'.$data['txtFinalMark'].'</td>';
		$cell .= '</tr>';
	}
	else 
	{
		#проверяем, выполняет ли студент работу
		$query_w = mysql_query("SELECT * FROM tblWork WHERE txtStudentId LIKE '% ".$data["intUserId"]." %' LIMIT 1");
		
		#студент выполняет работу, смотрим следующего
		if (mysql_num_rows($query_w) != 0) continue;
			
		#получаем название направления
		$query_direction = mysql_query("SELECT * FROM tblDirection WHERE intDirectionId='".$data["intDirectionId"]."' LIMIT 1");
		$direction = mysql_fetch_array($query_direction);
		
		$cell .= '<tr>';
		$cell .= '<td>'.$i.'</td>';
		$cell .= '<td>'.$data["txtSurname"]." ".$data["txtName"]." ".$data["txtSecondName"].'</td>';
		$cell .= '<td>'.$data["txtGroup"].'</td>';
		$cell .= '<td>'.$direction["txtDirectionName"].'</td>';
		$cell .= '</tr>';	
	}
$i++;
}


if ($typeDoc == 0)
{

	$pdf = new TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, 'UTF-8');
	$pdf->SetTextColor( $textColour[0], $textColour[1], $textColour[2] );
	$pdf->AddPage();
	$pdf->SetFont( 'Tahoma', 'B', 8 );

	if ($typeReport == 0){
		$pdf->writeHTML('
			<table border="1" cellpadding="5" text-align="center" style="word-wrap: break-word;">
			<tr>
				<td colspan="8">Список студентов '.$course.'го курса, которые получили неудовлетворительную оценку</td>
			</tr>
			<tr>
				<td width="30px">№</td>	
				<td>Ф.И.О.</td>	
				<td>№ группы</td>	
				<td>Направление</td>	
				<td>Тема работы</td>	
				<td>Оценка руков.</td>	
				<td>Оценка реценз.</td>	
				<td>Оценка комисс.</td>	
				<td>Итог. оценка</td>						
			</tr>'.$cell.'</table>', true, false, true, false, '');
	}
	else
	{
		$pdf->writeHTML('
			<table border="1" cellpadding="5" text-align="center" style="word-wrap: break-word;">
			<tr>
				<td colspan="3">Список студентов '.$course.'го курса, которые не выбрали работу</td>
			</tr>
			<tr>
				<td width="30px">№</td>	
				<td>Ф.И.О.</td>	
				<td>№ группы</td>	
				<td>Направление</td>					
			</tr>'.$cell.'</table>', true, false, true, false, '');
	}

	$file = get_count_reports('pdf');
	$pdf->Output($file, 'F');
	header_autosave($file,'report.pdf');
}
else
{

if ($typeReport == 0){
		$table = '
			<table border="1" cellpadding="5" text-align="center" style="word-wrap: break-word;">
			<tr>
				<td colspan="9">Список студентов '.$course.'го курса, которые получили неудовлетворительную оценку</td>
			</tr>
			<tr>
				<td width="30px">№</td>	
				<td>Ф.И.О.</td>	
				<td>№ группы</td>	
				<td>Направление</td>	
				<td>Тема работы</td>	
				<td>Оценка руков.</td>	
				<td>Оценка реценз.</td>	
				<td>Оценка комисс.</td>	
				<td>Итог. оценка</td>						
			</tr>'.$cell.'</table>';
	}
	else
	{
		$table = '
			<table border="1" cellpadding="5" text-align="center" style="word-wrap: break-word;">
			<tr>
				<td colspan="4">Список студентов '.$course.'го курса, которые не выбрали работу</td>
			</tr>
			<tr>
				<td width="30px">№</td>	
				<td>Ф.И.О.</td>	
				<td>№ группы</td>	
				<td>Направление</td>					
			</tr>'.$cell.'</table>';
	}



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


