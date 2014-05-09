<?php
 

	include_once("db.php");
	include_once("php/info.php");
	session_start();
	
	$title = "Новости";
	$current_menu1 = "current-menu-item current_page_item";
	include("templ/block_header.php"); 
	
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
	$query = mysql_query("SELECT * FROM tblNews ORDER BY intNewsId DESC LIMIT ".$row_start.", ".$row_limit."");	
	
	
	#информация
	if ($_GET["add_news"] == "ok")
	{
		$info[] = "Новость была успешно добавлена";
	} 
	if ($_GET["remowe_news"] == "ok")
	{
		$info[] = "Новость была успешно удалена";
	} 
	
	info_msg($info);

	 
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
			echo '<td>'.$data["datDate"].$remove_button.'</td>';
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