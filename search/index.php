<?php
session_start();
$category=-1;
require($_SERVER['DOCUMENT_ROOT']."/struct/main.php");
?>
<div class='content'>
<?php
	db_con();
	utf8();
	$search=mysql_real_escape_string(trim(htmlspecialchars($_GET['search'])));
echo "<h2 class='text_title'>Поиск '".$search."'</h2><hr>";
if(empty($search)){
	echo "<div class='zakaz'><h1 class='text_title'>Введите название товара или его номенклатурный номер</h1><hr>";
	echo "<p class='text_title'>Поиск идёт по всем товарам из всех категорий. </p></div>";
}
else {
//
	$q="select `id_tovar`,`name`,`izm`,`count`,`price`, `id_category` from `tovar` where `name` like '%".$search."%'";
	$r=mysql_query($q);
	//
	if(mysql_num_rows($r)!==0){
		echo "<table class='tovar_table'>";
		echo "<tr>";
		echo "<th>Номер</th>";
		echo "<th>Название</th>";
		echo "<th>Изм</th>";
		echo "<th>Кол-во</th>";
		echo "<th>Цена</th>";
		echo "<th>Купить</th>";
		echo "</tr>";
	}
	//
	while($row=mysql_fetch_array($r))
	{
	if($row['count']!=0)
	{
	echo "<tr class='tovar_table_tr'>";
		echo "<td>".$row['id_tovar']."</td>";
		echo "<td>".$row['name']."</td>";
		echo "<td>".$row['izm']."</td>";
		echo "<td>".$row['count']."</td>";
		echo "<td>".round($row['price'],2)."р</td>";
		echo "<td><a class='button' href='".$link."category/show/index.php?id_tovar=".$row['id_tovar']."&category=".$row['id_category']."'>Подробнее</a></td>";
		echo "</tr>";
	}
	}
	if(mysql_num_rows($r)==0){
	$qq="select `id_tovar`,`name`,`izm`,`count`,`price` from `tovar` where `id_tovar` like '".$search."'";
	$rr=mysql_query($qq);
	if(mysql_num_rows($rr)!==0){
		echo "<table class='tovar_table' border='1'>";
		echo "<tr>";
		echo "<th>Номер</th>";
		echo "<th>Название</th>";
		echo "<th>Измерение</th>";
		echo "<th>Кол-во</th>";
		echo "<th>Цена</th>";
		echo "<th>Купить</th>";
		echo "</tr>";
	}
	else{
	echo "<div class='zakaz'><h1 class='text_title'>Поиск не дал результатов по вашему запросу</h1><hr>";
	echo "<p class='text_title'>Проверьте правильность написания запроса</p></div>";
	}
		while($rrow=mysql_fetch_array($rr))
	{
	if($rrow['count']!=0)
		{
		echo "<tr class='tovar_table_tr'>";
			echo "<td>".$rrow['id_tovar']."</td>";
			echo "<td>".$rrow['name']."</td>";
			echo "<td>".$rrow['izm']."</td>";
			echo "<td>".$rrow['count']."</td>";
			echo "<td>".round($rrow['price'],2)."р</td>";
			echo "<td><a class='button' href='".$link."category/show/index.php?id_tovar=".$rrow['id_tovar']."&category=".$category."'>Подробнее</a></td>";
			echo "</tr>";
		}
	}
	}
}
?>
</table>
</div>
<?php
require($struct.'footer.php');
?>