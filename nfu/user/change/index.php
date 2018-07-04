<?php
session_start();
$category=-1;
require($_SERVER['DOCUMENT_ROOT']."/struct/main.php");
//
echo "<div class='content'>";
if(isset($_POST['submit']))
{
$id=htmlspecialchars($_POST['id']);
$login=htmlspecialchars($_POST['login']);
$status=htmlspecialchars($_POST['password']);
$e_mail=htmlspecialchars($_POST['e_mail']);
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
if(empty($_POST['password']))$no_change_pass=true;
else $no_change_pass=false;
 if ($no_change_pass==false)
 {
 if(strlen($_POST['password']) < 3 or strlen($_POST['password']) > 30)
	{
     $err[] = "Пароль должен быть не меньше 3-х символов и не больше 30";
	}
 }
 # Если нет ошибок, то добавляем в БД нового пользователя
 if(count($err) == 0)
	{
     $login = $_POST['login'];
     # Убераем лишние пробелы и делаем двойное шифрование
     if($no_change_pass==false)
		 $password = md5(md5(trim($_POST['password'])));
	 $status=$_POST['status'];
	 db_con_root();
		 mysql_query("UPDATE usr SET login='".$login."', e_mail='".$e_mail."', status='".$status."' WHERE id_user='".$id."'");
		 //
		 if($no_change_pass==false)
		 {
			mysql_query("UPDATE usr SET password='".$password."' WHERE id_user='".$id."'");
		 }
		 //
	 echo "<script>alert('Данные пользователя успешно заменены.');</script>";
     echo "<script language='JavaScript' type='text/javascript'>location='".$link."'</script>";
	 exit();
	}
 else
	{
     echo "<br><p class='text_title'>При регистрации возникли следующие ошибки:</p><br>";
     foreach($err AS $error)
     {
         echo "<p class='error'>".$error."</p>";
     }
	}
}
//
nfu();
$r=isModerator();
if($r!=true) 	echo "<script language='JavaScript' type='text/javascript'>location='".$link."'</script>";
//
?>
	<h1 class='text_title'>Изменение данных пользователя</h1><hr>
	<form class='zakaz text_title' method='get'>
	<p class='text_title'>Выберите пользователя</p><hr>
	<?php
	db_con();
	echo "<select name='user' id='user' class='input_text'>";
	$q="select id_user, login from usr";
	$r=mysql_query($q);
	//
	while($row=mysql_fetch_array($r))
		{
		echo "<option value='".$row['id_user']."'>".$row['login']."</option>";
		}
	echo "</select>";
	echo "<input class='button_3' type='submit' value='Выбрать' onclick='confirm_del();'>";
	?>
	</form>
<?php
if(isset($_GET['user']) or !empty($_GET['user']))
{
	$id=htmlspecialchars($_GET['user']);
	$q="select id_user, login, status, e_mail from usr where id_user='".$id."'";
	$r=mysql_query($q);
	//
	while($row=mysql_fetch_array($r))
		{
		//
		if(!isset($_POST['submit'])){
			$login=$row['login'];
			$password='';
			$status=$row['status'];
			$e_mail=$row['e_mail'];
		}
		//
echo "<form method='post' class='zakaz'>";
		echo "<p class='text_title'>Изменение пользователя \"".$login."\"</p><hr>";
		echo "<p class='error text'>Если вы не хотите менять пароль, то оставьте поле 'Пароль:' пустым.</p>";
		echo "<input type='text' name='id' value='".$id."' style='display:none;'>";
		echo "<div><label>Логин:</label> <input type='text' name='login' class='input_text' value='".$login."'></div><br>";
		echo "<div><label>Пароль:</label> <input type='password' name='password' class='input_text'></div><br>";
		echo "<div><label>e_mail:</label> <input type='text' name='e_mail' class='input_text' value='".$e_mail."'></div><br>";
		echo "<div>";
			echo "<label>Права:</label>";
			echo "<select name='status' class='input_text'>";
				if($status=='user')$checked="selected=''";
				else $checked='';
				echo "<option ".$checked." value='user'>Пользователь</option>";
				if($status=='manager')$checked="selected=''";
				else $checked='';
				echo "<option ".$checked." value='manager'>Менеджер</option>";
				if($status=='moderator')$checked="selected=''";
				else $checked='';
				echo "<option ".$checked." value='moderator'>Модератор</option>";
			echo "</select>";
		echo "</div><br>";
		echo "<input type='submit' name='submit' value='Изменить данные' class='button_2'>";
		echo "</form>";
		}
}
echo "</div>";
//
require($struct.'footer.php');
?>