<?php
 

	include_once("php/db.php");
	include_once("php/error.php");
	include_once("php/info.php");
	session_start(); 

	$title = "Учебные материалы";
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
	
		#ошибки
	if ($_GET["reason"] == "not_file")
	{
		$error[] = "Не выбран файл для загрузки";
	} 
	if ($_GET["reason"] == "size")
	{
		$error[] = "Размер файла не может превышать 10 мегабайт";
	} 
	if ($_GET["ta"] == "ok")
	{
		$info[] = "Документ успешно загружен";
	} 
	if ($_GET["remove_ta"] == "ok")
	{
		$info[] = "Документ успешно удалён";
	} 
	
	info_msg($info);
	error_msg($error);
		
	  
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
			else $remove_button = ' <image src="./images/remove.png" title="Удалить документ" style="cursor: pointer;" onclick="location.href=\'php/remove_training_aids.php?z='.$data["intMaterialsId"].'\'" />';		
			
			echo '<tr>';
			echo '<td>'.$data["datDate"].' '.$remove_button.'</td>';	
			echo '<td><a href="'.$data["txtLink"].'">'.$data["txtName"].'</a></td>';	
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
 echo '<form enctype="multipart/form-data" method="post" action="php/add_training_aids.php">';
 echo '<p><input type="file" name="f">';
 echo '<input id="submit" type="submit" value="Добавить Документ" />';
 echo '</form>'; 
}

?>

 
<?php include_once("templ/block_footer.php"); ?>	