<?php



include_once("db.php");

	#получаем информацию о рецензиях(документы)
	$query_c = mysql_query("SELECT * FROM tblReview WHERE intWorkId='".$topic["intWorkId"]."' and boolType='3' ORDER BY intReviewId DESC");		   
		   
?>

	<div id="edge">
	<h1>Рецензия</h1>
	<table class="tftable">
		<tr>
			<th >Дата</th>		
			<th >Загрузил</th>
			<th >Название документа</th>					
		</tr>		
				<?php
				while($data = mysql_fetch_array($query_c))
				{
					echo '<tr>';
					echo '<td>'.$data["datDate"].'</td>';
					echo '<td><a href="profile?z='.$data["intSenderId"].'">'.getFullName($data["intSenderId"]).'</a></td>';
					echo '<td><a href="'.$data["txtLink"].'">'.$data["txtName"].'</td>';       
					echo '</tr>';
				}
				if (mysql_num_rows($query_c) == 0) 
				{
					echo '<tr>';
					echo '<td>Рецензия ещё не добавлена</td>';
					echo '<td></td>'; 
					echo '<td></td>';  
					echo '</tr>';				
				}
				?>
			</table>	
<?php 
if ($topic["intTeacherId"] == $_SESSION["userId"] || $reviewers["intTeacherId"] == $_SESSION["userId"])
{	
	echo '<div id="edge" class="button">';
	echo '<h1>Загрузить рецензию</h1>';
	echo '<form enctype="multipart/form-data" method="post" action="./php/teacher/add_critique.php?z='.$topic["intWorkId"].'">';
	echo '<input type="file" name="f">';
	echo '<input id="submit" type="submit" value="Добавить Документ" />';
	echo '</form>'; 
	echo '</div>';
}
?>	
	
	
	</div>	