<?php
 

	include_once("php/db.php");
	session_start(); 

	$title = "Помощь по сайту";
	$current_menu1 = "current-menu-item current_page_item";	
	include_once("templ/block_header.php"); 
?>

<div id="s_types">
<div id="sh_types">Интерфейс</div>
<ul>
<li><a href="#">Панель быстрого доступа</a></li>
<li><a href="#">Боковая панель новостей</a></li>
<li><a href="#">Главная страница</a></li>
<li><a href="#">Профиль</a></li>
<li><a href="#">Сообщения</a></li>
<li><a href="#">Список работ</a></li>
<li><a href="#">Документы</a></li>
<li><a href="#">Новости</a></li>
<li><a href="#">Настройки</a></li>
<li><a href="#">Связь с администрацией проекта</a></li>
</ul>
</div>

<div id="s_types">
<div id="sh_types">Работа с сайтом</div>
<ul>
<li><a href="#">Панель "моя работа"</a></li>
<li><a href="#">Список работ</a></li>
<li><a href="#">Подача заявки для выполнения работы</a></li>
<li><a href="#">Отзыв поданной заявки</a></li>
<li><a href="#">Загрузка документа в работу</a></li>
<li><a href="#">Отказ от выполнения работы</a></li>
</ul>
</div>

<div id="s_types">
<div id="sh_types">Работа с сайтом</div>
<ul>
<li><a href="#">Панель "работы студентов"</a></li>
<li><a href="#">Список работ</a></li>
<li><a href="#">Добавление темы работы</a></li>
<li><a href="#">Редактирование темы работы</a></li>
<li><a href="#">Удаление темы работы</a></li>
<li><a href="#">Просмотр списка заявок на выполнение работ</a></li>
<li><a href="#">Добавление новостей</a></li>
<li><a href="#">Загрузка документов в работу</a></li>
<li><a href="#">Выставление оценки</a></li>
<li><a href="#">Назначение рецензента</a></li>
<li><a href="#">Добавление документов</a></li>
</ul>
</div>

<div id="s_types">
<div id="sh_types">Работа с сайтом</div>
<ul>
<li><a href="#">Панель "работы кафедры"</a></li>
<li><a href="#">Добавление темы работы</a></li>
<li><a href="#">Редактирование темы работы</a></li>
<li><a href="#">Удаление темы работы</a></li>
<li><a href="#">Добавление новостей</a></li>
<li><a href="#">Загрузка документов в работу</a></li>
<li><a href="#">Выставление оценки</a></li>
<li><a href="#">Назначение рецензента</a></li>
<li><a href="#">Добавление документов</a></li>
<li><a href="#">Добавление пользователя</a></li>
<li><a href="#">Редактирование пользователя</a></li>
<li><a href="#">Установка крайних сроков сдачи работ</a></li>
<li><a href="#">Добавление кафедры</a></li>
<li><a href="#">Добавление направления</a></li>
<li><a href="#">Создание отчётов</a></li>
</ul>
</div>


<div id="s_types">
<div id="sh_types">Работа с сайтом</div>
<ul>
<li><a href="#">Добавление новостей</a></li>
<li><a href="#">Добавление пользователя</a></li>
<li><a href="#">Редактирование пользователя</a></li>
<li><a href="#">Установка крайних сроков сдачи работ</a></li>
<li><a href="#">Добавление кафедры</a></li>
<li><a href="#">Добавление направления</a></li>
<li><a href="#">Создание отчётов</a></li>
</ul>
</div>


<div id="s_feed"></div>
	
	  
<?php include_once("templ/block_footer.php"); ?>	