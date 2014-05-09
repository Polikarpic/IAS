<?php
include_once("db.php");


?>

	<div id="edge" class="button">			
			<h1>Темы для курсовых работ
				<div id="right">
					<select id="filter_w" onchange="filter_func();">
						<option value="1">Подходящие</option>
						<option value="2">Все</option>
					</select>  
				</div>
			</h1>
			
			<div id="works">
			<?php include_once("work_filter/work_filter.php"); ?>
			</div>
			


<?php if ($_SESSION["statusId"] == 1) echo '<input id="submit" type="button" onclick="location.href=\'\add_topic\'" value="Добавить тему" />'; ?>	
</div>	