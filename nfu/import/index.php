<?php
session_start();
$category=-1;
require($_SERVER['DOCUMENT_ROOT']."/struct/main.php");
?>
<script>
function alert_z(){
	alert("После закрытия этого сообщения начнётся загрузка и обработка файла. Пожалуйста, не закрывайте страницу до появления соответствующего сообщения об окончании загрузки!");
}
</script>
<div class='content'>
<?php
nfu();
$r=isModerator();
if($r==true)
{
//
if(isset($_POST['submit']))
{
$file=$_FILES['file']['tmp_name'];
db_con_root();
cp1251();
if($_POST['status']=='refresh')mysql_query("TRUNCATE TABLE tovar");
$count_str=0;
if(($handle = fopen($file,"r"))!==FALSE){
	while(($data=fgetcsv($handle,1000,";"))!==FALSE){
		if($count_str!=0)
		{
		$str_for_insert_csv="INSERT INTO `tovar`(
		`id_tovar`,`name`,`izm`,`count`,`id_category`,`price`) VALUES (
		'".$data[1]."','".$data[2]."','".$data[3]."','".$data[4]."','".$data[5]."','".$data[6]."');";
		$query_for_insert_csv=mysql_query($str_for_insert_csv) or die(mysql_error());
		}
		$count_str+=1;
	}
	fclose($handle);
}
echo "<script>alert('Загрузка файлов завершена.');</script>";
echo "<script language='JavaScript' type='text/javascript'>location='".$link."'</script>";
}
//
echo "<h1 class='text_title'>Импорт товаров</h1><hr>";
echo "<p class='text_lite'>Перед импортом товаров, пожалуйства проверьте корректность подготовленных данных в Microsoft Exel.</p>";
echo "<p class='text_lite'>Первую строку файла должна занимать шапка таблицы. Она должна занимать обязательно только одну строку.</p>";
echo "<p class='text_lite'>Недопустимо совмещение строк или столбцов.</p>";
echo "<p class='text_lite'>Таблица должна содержать строки: <b>Номер</b>, <b>Табельный номер</b>, <b>Название</b>, <b>Измерение</b>, <b>Количество</b>, <b>Номер категории</b>, <b>Цена</b>.</p>";
echo "<p class='text_lite'>Для защиты от появления ошибок, столбец <b>Номер</b> не берётся в учёт.</p>";
echo "<p class='tovar_desc'>Столбец \"<b>Код категории</b>\" нужно заполнять номером категории с сайта:</p>";
//
echo "<table class='table_mini'>";
	$q="select `id_category`,`name` from `category`";
	$r=mysql_query($q);
	//
	echo "<tr>";
	echo "<th>Название</th>";
	echo "<th>Код категории</th>";
	echo "</tr>";
	while($row=mysql_fetch_array($r))
	{
	echo "<tr class='tovar_table_tr'>";
		echo "<td>".$row['name']."</td>";
		echo "<td>".$row['id_category']."</td>";
		echo "</tr>";
	}
echo "</table>";
//
echo "<p class='tovar_desc'>Пример заполненой таблицы:</p>";
echo "<img src='".$link."img/example.png' class='img_center'>";
echo "<p class='text_lite'>Файл нужно сохрать с расширением \"<b>.csv</b>\", выбрав пункт \"<b>CSV (разделители - запятые)</b>\".</p>";
echo "<img src='".$link."img/example_save.png' class='img_center'><hr>";
//
echo "<p class='tovar_desc'>Внимательно отнеситесь к способу добавления товаров на сайт.</p>";
echo "<p class='text_lite'><b>Добавить к текущему списку</b> - пополнить список товаров, добавив список к уже имеющимуся списку на сайте.</p>";
echo "<p class='text_lite'><b>Полностью обновить список</b> - полностью очистить список товаров и загрузить новый список.</p>";
echo "<p class='error'>Выбирая способ \"<b>Полностью обновить список</b>\", возможны ошибки в случае работы с заказом, у которого заказанные товары были удалёны из списка товаров и не загружены в новом списке.</p>";
//
echo "<form enctype='multipart/form-data' method='post' class='zakaz'>";
	echo "<p class='text_title'>Импорт товаров</p><hr>";
	echo "<label>Действие:</label>";
	echo "<select name='status' class='input_text'>";
		echo "<option value='add'>Добавить к текущему списку</option>";
		echo "<option value='refresh'>Полностью обновить список</option>";
	echo "</select><br><br>";
	echo "<label>Выберите файл:</label>";
	echo "<input type='file' name='file' accept='.csv'><br><br>";
	echo "<input type='submit' name='submit' value='Начать импорт' class='button_2' onclick='alert_z();'>";
echo "</form>";

}
?>
</div>
<?php
require($struct.'footer.php');
?>