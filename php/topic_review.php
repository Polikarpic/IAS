<?php



include_once("db.php");

	#получаем информацию о отзывах(документы)
	$query_r = mysql_query("SELECT * FROM tblReview WHERE intWorkId='".$topic["intWorkId"]."' and boolType='2' ORDER BY intReviewId DESC");		   
		   
?>

	<div id="edge">
	<h1>Отзыв преподавателя</h1>
	<table class="tftable">
		<tr>
			<th >Дата загрузки</th>				
			<th >Название документа</th>					
		</tr>		
				<?php
				while($data = mysql_fetch_array($query_r))
				{
					echo '<tr>';
					echo '<td>'.strftime('%d %B %G года',strtotime($data["datDate"])).'</td>';
					echo '<td><a href="'.$data["txtLink"].'">'.$data["txtName"].'</td>';       
					echo '</tr>';
				}
				if (mysql_num_rows($query_r) == 0) 
				{
					echo '<tr>';
					echo '<td>Отзыв ещё не был загружен</td>';
					echo '<td></td>';  
					echo '</tr>';				
				}
				?>
			</table>	

<?php 
if ((($topic["intTeacherId"] == $_SESSION["userId"] || ($_SESSION["statusId"] == 2 && $_SESSION["chairId"] == $topic["intChairId"]))) && $topic["intWorkStatus"] == 0)
{
	echo '<div id="edge" class="button">';
	echo '<h1>Загрузить отзыв</h1>';
	echo '<form enctype="multipart/form-data" method="post" action="./php/teacher/add_review.php?z='.$topic["intWorkId"].'">';
	echo '<input type="file" name="f">';
	echo '<input id="submit" type="submit" value="Добавить Документ" />';
	echo '</form>'; 
	echo '</div>';
}
?>
	
	
	</div>	