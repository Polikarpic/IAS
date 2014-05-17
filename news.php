<?php
 

	include_once("db.php");
	session_start();
	
	$title = "Новости";
	$current_menu1 = "current-menu-item current_page_item";
	include("templ/block_header.php"); 
	
	#сообщения для пользователя
	um();
	
	$row_limit = 10; //число новостей на страницу
	
	# получаем номер страницы 
	if (intval($_GET['page']) > 0) $pages = safe_var(intval($_GET['page']));
	else $pages = 1;
	
	# получаем максимальное число записей на страницу
	$count = mysql_fetch_array(mysql_query("select COUNT(*) as max from tblNews"));
	$max_pages = ceil($count["max"]/$row_limit);
		
	# если число страниц меньше нуля
	if ($pages < 0) $pages = 1;
	if ($pages > $max_pages) $pages = $max_pages;
	$_GET["page"] = $pages;	
	
	#получаем номер новости для вывода
	$row_start = ($pages - 1) * $row_limit;	
	
	
	#получаем список новостей
	#для студента фильтрация по направлению, курсу
	if ($_SESSION["statusId"] == 0)
		$query = mysql_query("SELECT txtText, datDate, intNewsId FROM tblNews WHERE 
		(intGroup_of_readers = '".$_SESSION["statusId"]."' or intGroup_of_readers is null) 
		and (intDirectionId = '".$_SESSION["directionId"]."' or intDirectionId  is null)
		and (intCourse ='".$_SESSION["courseId"]."' or intCourse  is null)
		ORDER BY intNewsId DESC LIMIT ".$row_start.", ".$row_limit."");	
	#другие группы пользователей
	#фильтрация по кафедре
	else
		$query = mysql_query("SELECT txtText, datDate, intNewsId FROM tblNews WHERE 
		(intGroup_of_readers = '".$_SESSION["statusId"]."' or intGroup_of_readers  is null) 
		and (intChairId = '".$_SESSION["chairId"]."' or intChairId  is null) or (intSenderId='".$_SESSION["userId"]."')
		ORDER BY intNewsId DESC LIMIT ".$row_start.", ".$row_limit."");	
	 
?>
<div id="edge">
<table class="tftable">
		<tr>
			<th >Дата</th>	
			<th >Новость</th>									
		</tr>		
		<?php 
		
		
		while($data = mysql_fetch_array($query))
		{
			if ($_SESSION["statusId"] != 0) $remove_button = ' <image src="./images/remove.png" title="Удалить новость" style="cursor: pointer;" onclick="location.href=\'php/remove_news.php?z='.$data["intNewsId"].'\'" />';
			else $remove_button = "";
			echo '<tr>';
			echo '<td>'.strftime('%d %B %G года',strtotime($data["datDate"])).$remove_button.'</td>';
			echo '<td>'.$data["txtText"].'</td>';
			echo '</tr>';
		}
		if (mysql_num_rows($query) == 0) 
		{
					echo '<tr>';
					echo '<td>Новостей нет</td>';
					echo '<td></td>';  
					echo '</tr>';				
		}
		?>
</table>
<?php
#вывод списка страниц
if ($max_pages > 1){
echo 'Страница: ';
	for ($p = 1; $p <= $max_pages; $p++)
	{		
		if ($p == $pages)
		{
			echo '['.$p.'] ';
		}
		else
		{
			echo '<a href="'.$_SERVER["PHP_SELF"].'?page='.$p.'">'.$p.'</a> ';
		}
	}
}
?>		
</div>
<?php if ($_SESSION["statusId"] != 0) echo '<input id="submit" type="button" onclick="location.href=\'new_news\'" value="Добавить новость" />'; ?><br /><br />

<?php include_once("templ/block_footer.php"); ?>	