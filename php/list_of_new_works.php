<?php
include_once("db.php");
session_start();

echo '<script src="http://code.jquery.com/jquery-latest.js"></script>';
echo '<script type="text/javascript" src="js/jQuery_load.js"></script>';

?>

	<div id="edge" class="button">			
			<h1>Новые темы 
				<div id="right">
					<select id="filter_nw" onchange="filter_func();">
						<option value="1">Подходящие</option>
						<option value="2">Все</option>
					</select>  
				</div>
			</h1>
			
	
			
			<div id="new_works">
			<?php include_once("work_filter/new_work_filter.php"); ?>
			</div>
		<input id="submit" type="button" onclick="location.href='list.php'" value="Все темы" />	
		<?php if ($_SESSION["statusId"] == 1) echo '<input id="submit" type="button" onclick="location.href=\'\add_topic\'" value="Добавить тему" />'; ?>
	</div>	