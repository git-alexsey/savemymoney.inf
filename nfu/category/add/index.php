<?php
session_start();
$category=-1;
require($_SERVER['DOCUMENT_ROOT']."/struct/main.php");
//
nfu();
$r=isModerator();
if($r==true)
{
echo "<div class='content'>";
echo "<h1 class='text_title'>Добавление категории</h1><hr>";
//
if(isset($_POST['submit']))
{
$err = array();
$name=htmlspecialchars($_POST['name']);
$desc=htmlspecialchars($_POST['desc']);
if(strlen($name) < 3 or strlen($name) > 45)
	{
     $err[] = "Название должно быть не меньше 3-х символов и не больше 45 символов";
	}
if(strlen($desc) < 5 or strlen($desc) > 255)
	{
     $err[] = "Описание должно быть не меньше 5-х символов и не больше 255 символов";
	}
//
 if(count($err) == 0)
	{
	db_con_root();
	utf8();
     mysql_query("INSERT INTO `category` (`name`,`desc`) VALUES ('".$name."','".$desc."')");
	 echo "<script>alert('Категория успешно добавлена.');</script>";
     echo "<script language='JavaScript' type='text/javascript'>location='".$link."'</script>";
	 exit();
	}
 else
	{
     echo "<br><p class='tovar_desc'>При заполнении корзины возникли следующие ошибки:</p><br>";
     foreach($err AS $error)
     {
         echo "<p class='error'>".$error."</p>";
     }
	 echo "<hr>";
	}
}
else{
	$name='';
	$desc='';
}
//
?>
	<form class='zakaz' method='post'>
		<p class='tovar_desc'>Введите данные</p><hr>
		<label>Название категории:</label>
		<?php echo "<input class='input_text' type='text' name='name' value='".$name."'>";?>
		<label>Описание категории:</label>
		<?php echo "<textarea name='desc' rows='10' cols='45' maxlenhth='255' name='prim' placeholder='Введите описание категории...'>".$desc."</textarea>";?>
		<br><br><input type='submit' name='submit' class='button_2' value='Добавить категорию'>
	</form>
	</div>
<?php
}
else {
	echo "<script language='JavaScript' type='text/javascript'>location='".$link."'</script>";
}
//
require($struct.'footer.php');
?>