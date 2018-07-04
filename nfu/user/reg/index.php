<?php
session_start();
$category=-1;
require($_SERVER['DOCUMENT_ROOT']."/struct/main.php");
//
check();
//db_con_user();
db_con_root();
$r=isModerator();
if($r==true)
{
if(isset($_POST['submit']))
{
 $err = array();
 # проверям логин
 if(!preg_match("/^[a-zA-Z0-9]+$/",$_POST['login']))
	{
     $err[] = "Логин может состоять только из букв английского алфавита и цифр";
	}
 if(strlen($_POST['login']) < 3 or strlen($_POST['login']) > 30)
	{
     $err[] = "Логин должен быть не меньше 3-х символов и не больше 30";
	}
 if(strlen($_POST['password']) < 3 or strlen($_POST['password']) > 30)
	{
     $err[] = "Пароль должен быть не меньше 3-х символов и не больше 30";
	}
$login=htmlspecialchars($_POST['login']);
$e_mail=htmlspecialchars($_POST['e_mail']);
	if(filter_var($e_mail, FILTER_VALIDATE_EMAIL)==false)
	{
     $err[] = "Введите корректный e-mail";
	}
 # проверяем, не сущестует ли пользователя с таким именем
 $query = mysql_query("SELECT COUNT(id_user) FROM usr WHERE login='".mysql_real_escape_string($_POST['login'])."'");
 if(mysql_result($query, 0) > 0)
	{
     $err[] = "Пользователь с таким логином уже существует в базе данных";
	}
 # Если нет ошибок, то добавляем в БД нового пользователя
 if(count($err) == 0)
	{
     $login = $_POST['login'];
     # Убераем лишние пробелы и делаем двойное шифрование
     $password = md5(md5(trim($_POST['password'])));
	 $status=$_POST['status'];
	 db_con_root();
	 mysql_query("INSERT INTO `usr` (`login`, `password`, `status`, `e_mail`) VALUES ('".$login."', '".$password."', '".$status."', '".$e_mail."');");
	 echo "<script>alert('Пользователь успешно зарегистрирован.');</script>";
     echo "<script language='JavaScript' type='text/javascript'>location='".$link."'</script>";
	 exit();
	}
 else
	{
     echo "<br><p class='tovar_desc'>При регистрации возникли следующие ошибки:</p><br>";
     foreach($err AS $error)
     {
         echo "<p class='error'>".$error."</p>";
     }
	}
}
else {
	$login='';
	$e_mail='';
}
?>
<?php
$dir=$link.dirname($_SERVER['PHP_SELF'])."/";
?>
	<div class='zakaz content'>
		<h1 class='tovar_desc'>Регистрация</h1>
		<div class='text'>
		<h2>Права:</h2>
		<p><h3 class='inline'>Пользователь</h3> - Аккаунт без доступа к панели управления. Никаких отличий от обычного пользователя без аккаунта.</p>
		<p><h3 class='inline'>Менеджер</h3> - Аккаунт с частичным доступом к панели управления. Менеджер имеет право просматривать и удалять заказы, изменять содержимое заказа и их статус, а так же осуществлять поиск заказа, экспортировать данные о заказах, товарах и клиентах, загружать изображения к товарам.</p>
		<p><h3 class='inline'>Модератор</h3> - Аккаунт с полным доступом к панели управления. Имеет полный список возможностей менеджера, а так же добавлять, изменять и удалять категории, импортировать товары на сайт, регистрировать, изменять и удалять пользователей панели администратора.</p>
		</div>
		<?php
		echo "<div class='people'></div>";
		echo "<form method='post' action='".$dir."' class='zakaz'>";
		echo "<p class='text_title'>Заполните все поля</p><hr>";
		echo "<div><label>Логин:</label> <input type='text' name='login' class='input_text' value='".$login."'></div><br>";
		echo "<div><label>Пароль:</label> <input type='password' name='password' class='input_text'></div><br>";
		echo "<div><label>e_mail:</label> <input type='text' name='e_mail' class='input_text' value='".$e_mail."'></div><br>";
		echo "<div>";
		?>
			<label>Права:</label>
			<select name='status' class='input_text'>
				<option value='user'>Пользователь</option>
				<option value='manager'>Менеджер</option>
				<option value='moderator'>Модератор</option>
			</select>
		</div><br>
		<input type="submit" name="submit" value="Регистрация" class="button_2">
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