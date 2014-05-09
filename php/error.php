<?php 



#вывод сообщений об ошибке
function error_msg ($error)
{
	if (empty($error)) return;
	
	echo '<div id="edge" class="error">';
	foreach($error as $er)
	{
		echo "$er<br />";
	}
	echo '</div>';
}
?>