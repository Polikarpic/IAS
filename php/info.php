<?php 



#����� ��������� � �������� ������
function info_msg ($info)
{
	if (empty($info)) return;
	
	echo '<div id="edge" class="info">';
	foreach($info as $i)
	{
		echo "$i<br />";
	}
	echo '</div>';
}
?>