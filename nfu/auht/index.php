<?php
session_start();
$category=0;
require($_SERVER['DOCUMENT_ROOT']."/struct/main.php");
?>
<?php
db_con();
$dir=$link.dirname($_SERVER['PHP_SELF'])."/";
//
if(isset($_POST['submit']))
{
    # Вытаскиваем из БД запись, у которой логин равняеться введенному
    $query = mysql_query("SELECT id_user, password FROM usr WHERE login='".htmlspecialchars($_POST['login'])."' LIMIT 1");
    $data = mysql_fetch_assoc($query);
    # Соавниваем пароли
    if($data['password'] === md5(md5(htmlspecialchars($_POST['password']))))
    {
        # Генерируем случайное число и шифруем его
        $hash = md5(generateCode(10));
		// Перевод ip в строку
        $insip=$_SERVER['REMOTE_ADDR'];
        # Записываем в БД новый хеш авторизации и IP
		mysql_query("UPDATE usr SET hash='".$hash."', ip='".$insip."' WHERE id_user='".$data['id_user']."'");
		//mysql_query("UPDATE usr SET hash='555', ip='".$insip."' WHERE id_user='3'");
		//mysql_query("UPDATE usr SET hash='5', ip='1' WHERE id_user='1'");
        # Ставим куки
        //setcookie("id", $data['id_user'], time()+60*60*24*30);
        //setcookie("hash", $hash, time()+60*60*24*30);
		//
		$_SESSION['hash']=$hash;
		$_SESSION['id']=$data['id_user'];
        # Переадресовываем браузер на страницу проверки нашего скрипта
		echo "<script language='JavaScript' type='text/javascript'>location='".$link."'</script>";
		exit();
    }
    else
    {
        echo "<p class='error'>Вы ввели неправильный логин/пароль</p>";
    }
}
?>
	<div class='content'>
		<h1 class='tovar_desc'>Авторизация</h1><br>
		<?php
		echo "<form method='post' action='".$dir."' class='zakaz'>";
		?>
		<p class='text_title'>Введите данные для входа</p><hr>
		<div><label>Логин: </label><input type="text" name="login" class='input_text'></div><br>
		<div><label>Пароль: </label><input type="password" name="password" class='input_text'></div><br>		
		<div><input type="submit" name="submit" value="Войти" class="button_2"></div>
		</form>
	</div>
<?php
require($struct.'footer.php');
?>