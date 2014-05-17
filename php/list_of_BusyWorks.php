<?php
include_once("db.php");

echo '<script src="http://code.jquery.com/jquery-latest.js"></script>';
echo '<script type="text/javascript" src="js/jQuery_load.js"></script>';	
?>

	<div id="edge" class="button">			
			<h1>Курсовые работы
				<div id="right">
					<select id="filter_bw" onchange="filter_func();">
						<option <?php if ($_GET["c2"] == 1) echo 'selected="selected"'; ?> value="1">Текущего года</option>
						<option <?php if ($_GET["c2"] == 2) echo 'selected="selected"'; ?> value="2">Архив работ</option>
					</select>  
				</div>
			</h1> 
			
		<div id="b_works">
			<?php
			if ($_GET["c2"] == 2) include_once("work_filter/b_work_archive.php"); 
			else include_once("work_filter/b_work_all.php"); 
			
			?>
		</div>			
	</div>	
	
	