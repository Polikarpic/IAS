<?php 
// Формирует ссылку для возврата на предыдущую страницу(если есть куда возвращаться), или на главную страницу
if (!empty($_SERVER['HTTP_REFERER']))
	echo '<a href="'.$_SERVER["HTTP_REFERER"].'">Назад</a>';
else 
	echo '<a href="home">Назад</a>';
?>