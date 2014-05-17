<?php
 
	include_once("php/db.php");
	session_start(); 
	
	#доступ 
	if ($_SESSION["statusId"] != 2 && $_SESSION["statusId"] != 3){ $_SESSION["um"] = 'e0'; header("Location: ./../home"); exit(); }
	
	$title = "Добавить направление";
	$current_menu1 = "current-menu-item current_page_item";
	include_once("templ/block_header.php"); 	
	
	#получаем список кафедр
	$query = mysql_query("SELECT * FROM tblDirection");    
	 
	#сообщения для пользователя
	um();
	
?>
<div id="edge" class="button">
			<form action="php/add_direction.php" method="post">
			<label>Название направления: </label><input type="text" onkeyup="this.value=this.value.replace(/^\s*/,'');" required="" name="direction" maxlength="256" placeholder="Название направления"/><br />
			<input id="submit" type="submit" name="send" value="Добавить" />
			</form>
</div>

<div id="edge">
	<table class="tftable">
		<tr>
			<th></th>				
			<th>Название направления</th>					
		</tr>	
	
		<?php
		while($data = mysql_fetch_array($query))
		{
			if (mysql_num_rows($query) != 0) 
			{
				echo '<td><image src="./images/remove.png" title="Удалить" style="cursor: pointer;" onclick="if (confirm(\'Вы действительно хотите удалить направление?\')) location.href=\'php/remove_direction.php?z='.$data["intDirectionId"].'\'"></td>';
				echo '<td>'.$data["txtDirectionName"].'</td>';		
				echo '</tr>';		
			}	
			else
			{
				echo '<tr>';
				echo '<td>Кафедры ещё не добавлены</td>';
				echo '<td></td>';  
				echo '</tr>';				
			}
		}
		?>		
	</table>	
</div>

<?php include_once("templ/block_footer.php"); ?>	