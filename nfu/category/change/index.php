<?php
session_start();
$category=-1;
require($_SERVER['DOCUMENT_ROOT']."/struct/main.php");
//
nfu();
$r=isModerator();
if($r!=true) 	echo "<script language='JavaScript' type='text/javascript'>location='".$link."'</script>";
//
echo "<div class='content'>";
if(isset($_GET['category']))
{
$category=htmlspecialchars($_GET['category']);
}
else $category=-1;
//
if(isset($_POST['submit']))
{
$err = array();
$name=htmlspecialchars($_POST['name']);
$desc=htmlspecialchars($_POST['desc']);
if(mb_strlen($name,'UTF-8') < 3 or mb_strlen($name,'UTF-8') > 45)
	{
     $err[] = "Название должно быть не меньше 3-х символов и не больше 45 символов";
	}
if(mb_strlen($desc,'UTF-8') < 5 or mb_strlen($desc,'UTF-8') > 255)
	{
     $err[] = "Описание должно быть не меньше 5-х символов и не больше 255 символов";
	}
//
 if(count($err) == 0)
	{
	db_con_root();
	utf8();
     //mysql_query("UPDATE `categoty` SET `name`='".$name."', `desc`='".$desc."' WHERE `id_category`='".$category."'");
	 mysql_query("UPDATE category SET `desc`='".$desc."' WHERE id_category='".$category."'");
	 mysql_query("UPDATE category SET name='".$name."' WHERE id_category='".$category."'");
	 echo "<script>alert('Категория успешно изменена.');</script>";
     echo "<script language='JavaScript' type='text/javascript'>location='".$link."'</script>";
	 exit();
	}
 else
	{
     echo "<br><p class='text_title'>При заполнении корзины возникли следующие ошибки:</p><br>";
     foreach($err AS $error)
     {
         echo "<p class='error'>".$error."</p>";
     }
	 echo "<hr>";
	}
}
?>
	<h1 class='text_title'>Изменение категории</h1><hr>
	<form class='zakaz' method='get'>
	<p class='text_title'>Выберите категорию</p><hr>
	<?php
	db_con();
	echo "<select name='category' class='input_text'>";
	$q="select id_category, name from category";
	$r=mysql_query($q);
	//
	while($row=mysql_fetch_array($r))
		{
		if($row['id_category']==$category)$checked="selected=''";
		else $checked='';
		echo "<option ".$checked." value='".$row['id_category']."'>".$row['name']."</option>";
		}
	echo "</select>";
	echo "<input class='button_3' type='submit' value='Изменить'>";
	?>
	</form>
<?php
if(isset($_GET['category']))
{
$category=htmlspecialchars($_GET['category']);
$q="select * from category where id_category='".$category."'";
$r=mysql_query($q);
//
while($row=mysql_fetch_array($r))
	{
	if(!isset($_POST['submit'])) {
		$name=$row['name'];
		$desc=$row['desc'];
	}
		echo "<form class='zakaz' method='post'>";
		echo "<p class='text_title'>Категория №".$category."</p><hr>";
		echo "<label>Название категории:</label>";
		echo "<input class='input_text' type='text' name='name' value='".$name."'>";
		echo "<label>Описание категории:</label>";
		echo "<textarea name='desc' rows='10' cols='45' maxlenhth='255' name='prim' placeholder='Введите описание категории...'>".$desc."</textarea>";
		echo "<br><br><input type='submit' name='submit' class='button_2' value='Изменить категорию'>";
		echo "</form>";
	}
}
echo "</div>";
//
require($struct.'footer.php');
?>