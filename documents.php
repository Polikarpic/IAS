<?php
 

	include_once("php/db.php");
	session_start(); 

	$title = "Документы";
	$current_menu5 = "current-menu-item current_page_item";
	include("templ/block_header.php"); 
	 
	$row_limit = 10; //число сообщений на страницу
	
	# получаем номер страницы 
	if (intval($_GET['page']) > 0) $pages = safe_var(intval($_GET['page']));
	else $pages = 1;
	
	# получаем максимальное число записей на страницу
	$count = mysql_fetch_array(mysql_query("select COUNT(*) as max from tblMaterials"));
	$max_pages = ceil($count["max"]/$row_limit);
	
	# если число страниц меньше нуля
	if ($pages < 0) $pages = 1;
	if ($pages > $max_pages) $pages = $max_pages;
	$_GET["page"] = $pages;	
	
	#получаем номер сообщения для вывода
	$row_start = ($pages - 1) * $row_limit;	
	
	#получаем список документов
	$query = mysql_query("SELECT * FROM tblMaterials ORDER BY intMaterialsId DESC LIMIT ".$row_start.", ".$row_limit."");
	
	#сообщения для пользователя
	um();
	
	  
?>
<div id="edge">
<table class="tftable">
		<tr>
			<th>Дата загрузки</th>
			<th>Документ</th>									
		</tr>		
		
		<?php		
		
		while($data = mysql_fetch_array($query))
		{
			if ($_SESSION["statusId"] == 0) $remove_button = '';
			else $remove_button = ' <image src="./images/remove.png" title="Удалить документ" style="cursor: pointer;" onclick="location.href=\'php/remove_documents.php?z='.$data["intMaterialsId"].'\'" />';		
			
			echo '<tr>';
			echo '<td>'.strftime('%d %B %G года',strtotime($data["datDate"])).' '.$remove_button.'</td>';	
			echo '<td><a href="'.$data["txtLink"].'">'.$data["txtName"].'</a></td>';	
			echo '</tr>';					
		}		
		if (mysql_num_rows($query) == 0) 
		{
					echo '<tr>';
					echo '<td>Документы ещё не загружены</td>';
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

<?php 
#добавление нового документа, возможность преподавателя
if ($_SESSION["statusId"] != 0)
{
 echo '<div id="edge" class="button">';
 echo '<h1>Загрузить документ</h1>';
 echo '<form enctype="multipart/form-data" method="post" action="php/add_documents.php">';
 echo '<label>Загрузить с компьютера:</label><input type="file" name="f"><br />';
 echo '<label>или с интернета: </label><input type="text" name="wlink" maxlength="256" placeholder="Ссылка"><br />';
 echo '<label>Название: </label><input type="text" name="fname" maxlength="256" placeholder="Название"><br />';
 echo '<input id="submit" type="submit" value="Загрузить" />';
 echo '</form>'; 
 echo '</div>';
}

?>

 
<?php include_once("templ/block_footer.php"); ?>	