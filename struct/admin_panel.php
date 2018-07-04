<?
echo "<div class='admin_panel'>";
	echo "<p class='tovar_desc black'>Заказы</p><hr>";
	//
	db_con();
	$statuss="Ожидает подтверждения";
	utf8();
	$q=mysql_query("select `id_zakaz` from `zakaz` where `status`='".$statuss."'");
	$r=mysql_num_rows($q);
	//
	echo "<a class='gaz' href='".$link."nfu/zakaz/?status=Ожидает подтверждения'>Ожидает подтверждения (".$r.")</a>";
	//
	$statuss="Принят";
	utf8();
	$q=mysql_query("select `id_zakaz` from `zakaz` where `status`='".$statuss."'");
	$r=mysql_num_rows($q);
	echo "<a class='gaz' href='".$link."nfu/zakaz/?status=Принят'>Принят (".$r.")</a>";
	//
	echo "<a class='gaz' href='".$link."nfu/zakaz/?status=Отклонён'>Отклонён</a>";
	//
	$statuss="Ожидает выдачи";
	utf8();
	$q=mysql_query("select `id_zakaz` from `zakaz` where `status`='".$statuss."'");
	$r=mysql_num_rows($q);
	echo "<a class='gaz' href='".$link."nfu/zakaz/?status=Ожидает выдачи'>Ожидает выдачи (".$r.")</a>";
	//
	echo "<a class='gaz' href='".$link."nfu/zakaz/?status=Выдан'>Выдан</a>";
	//
	echo "<hr><a class='gaz' href='".$link."nfu/zakaz/search/'>Поиск заказа</a>";
	//
	echo "<a class='gaz' href='".$link."nfu/export/'>Экспорт</a>";
	//
	echo "<a class='gaz' href='".$link."category/show/add/'>Загрузить изображение товара</a>";
	//
	if($status=='moderator'){
	echo "<hr><p class='tovar_desc black'>Категории</p><hr>";
	echo "<a class='gaz' href='".$link."nfu/category/add/'>Добавить категорию</a>";
	echo "<a class='gaz' href='".$link."nfu/category/change/'>Изменить категорию</a>";
	echo "<a class='gaz' href='".$link."nfu/category/del/'>Удалить категорию</a>";
	echo "<hr><p class='tovar_desc black'>Управление товарами</p><hr>";
	echo "<a class='gaz' href='".$link."nfu/import/'>Импорт товаров</a>";
	echo "<hr><p class='tovar_desc black'>Пользователи</p><hr>";
	echo "<a class='gaz' href='".$link."nfu/user/reg/'>Зарегистрировать нового</a>";
	echo "<a class='gaz' href='".$link."nfu/user/change/'>Изменение пользователя</a>";
	echo "<a class='gaz' href='".$link."nfu/user/del/'>Удаление пользователя</a>";
	}
	echo "<hr><a class='button_2' href='".$link."nfu/auht/logout.php'>Выход</a>";
echo "</div>";
?>