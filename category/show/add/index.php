<?php
session_start();
$category=htmlspecialchars($_GET['category']);
require($_SERVER['DOCUMENT_ROOT']."/struct/main.php");
//
nfu();
$check=isManager();
if($check==true)
{
//
if(isset($_POST['submit']))
{
$info=pathinfo($_FILES['file']['name']);
$filename=basename($_FILES['file']['name'],'.'.$info['extension']);
$q="select id_category from tovar where id_tovar='".$filename."'";
$r=mysql_query($q);
//
if(mysql_num_rows($r)==0){
	echo "<script>alert('Товара с таким номером не найдено');</script>";
}
else
{
//
while($row=mysql_fetch_array($r))
	{
	$id_category=$row['id_category'];
	}
//
$path=$site."category/show/img/".$_FILES['file']['name'];
move_uploaded_file($_FILES['file']['tmp_name'],$path);
//
echo "<script>alert('Изображение успешно загружено');</script>";
echo "<script language='JavaScript' type='text/javascript'>location='".$link."/category/show/?id_tovar=".$filename."&category=".$id_category."'</script>";
}
}

echo "<div class='content'>";
echo "<h1 class='text_title'>Загрузка изображения</h1><hr>";
echo "<p class='text'>Для того, чтобы загрузить избражение к нужному товару, нужно переменовать изображение в табельный номер товара.</p>";
echo "<p class='text_title'>Пример загрузки изображения к товару с табельным номером 410087.</p>";
echo "<img src='".$link."img/upload.png' class='img_center'>";
echo "<p class='text'>К товару можно заргузить изображение с расширениями: .<b>jpg</b>, .<b>jpeg</b>, .<b>png</b>, .<b>gif</b></p>";
db_con();
//
echo "<form enctype='multipart/form-data' method='post' class='zakaz'>";
	echo "<p class='text_title'>Загрузка изображения</p><hr>";
	echo "<label>Выберите файл:</label>";
	echo "<input type='file' name='file' accept='.jpg, .png, .jpeg, .gif'><br><br>";
	echo "<input type='submit' name='submit' value='Загрузить' class='button_2'>";
echo "</form>";
echo "</div>";
}
//
require($struct.'footer.php');
?>