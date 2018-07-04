<?php
session_start();
$category=-1;
require($_SERVER['DOCUMENT_ROOT']."/struct/main.php");
?>
<script src='https://www.google.com/recaptcha/api.js'></script>
<script language='JavaScript' type='text/javascript'>
//
function confirm_del(id){
	result = confirm("Вы действительно хотите удалить этот товар из заказа?");
	if(result===true){
		<?php echo "location='".$link."cart/delete.php?id='+id;";?>
	}
}
//
window.onload=function (){calc();};
//
function calc(){
var summ=0;
var table=document.getElementById('table1');
var tr=table.rows;
var m=tr.length;
for(var i=1; i<m; i++)
{
	id=tr[i].getAttribute("name");
	var price_id=i*7+3;
	price=document.getElementById('price_'+id).innerHTML;
	var count=document.getElementById('count_'+id).value;
	summ+=count * price;
};
//
summ=Math.round(summ*100)/100
//
if (isNaN(summ)) {summ='<span class=error>Введите корректные числа в поля \"Количество для покупки\"</span>';}
else {summ+=' рублей';};
document.getElementById('summ').innerHTML=summ;
};
//
</script>
<div class='content'>
<?php
if(isset($_POST['submit']))
{
 $summ=0;
 $err = array();
 # проверям логин
 foreach($_SESSION['tovars'] as $value)
 {
	$post=mysql_real_escape_string(htmlspecialchars($_POST["count_".$value.""]));
	//
	if(!preg_match('/^\d+$/',$post))
	{
     $err[] = "Товар № ".$value.". В строку \"Количество для покупки\" можно вводить только целые числа от 0 до максимального количества товара";
	}
	//
	db_con();
	utf8();
	$q="select `count` from `tovar` where `id_tovar`='".$value."' LIMIT 1";
	$r=mysql_query($q);
	while($row=mysql_fetch_array($r))
		{
		if($post>$row['count']) $err[] = "Товар № ".$value.". Количество товара для покупки больше заявленного количества товара в таблице.";
		}
	$id_2=array_search($value,$_SESSION['tovars']);
	$_SESSION['counts'][$id_2]=$post;
	//
	$q="select `price` from `tovar` where `id_tovar`='".$value."' LIMIT 1";
	$r=mysql_query($q);
	while($row=mysql_fetch_array($r))
		{
		$summ+=$_SESSION['counts'][$id_2] * $row['price'];
		}
	//
 }
	if(!empty($_POST['g-recaptcha-response']))
	{
	$url='https://www.google.com/recaptcha/api/siteverify';
	$key='6LfzvSIUAAAAALjkTmY8XPoNrr12w0lDKk0R3acP';
	$query= $url."?secret=".$key."&response=".$_POST['g-recaptcha-response']."&remoteip=".$_SERVER['REMOTE_ADDR'];
	$data = json_decode(file_get_contents($query));
	if($data->success==0) {$err[] = "Капча введена неверно";}
	}
	else {$err[] = "Пройдите капчу";}
//
$fio=mysql_real_escape_string(htmlspecialchars(trim($_POST['fio'])));
 if(strlen($fio) < 3 or strlen($fio) > 105)
	{
     $err[] = "ФИО должно быть не меньше 3-х символов и не больше 105 символов";
	}
//
$phone=mysql_real_escape_string(htmlspecialchars(trim($_POST['phone'])));
 if(strlen($phone) < 6 or strlen($phone) > 15)
	{
     $err[] = "Телефон должен быть не меньше 7-х символов и не больше 15 символов";
	}
//
	$e_mail=mysql_real_escape_string(htmlspecialchars(trim($_POST['e_mail'])));
	//if(strlen($e_mail) < 5 or strlen($e_mail) > 50)
	//{
    // $err[] = "Введите корректный e-mail";
	//}
	if(filter_var($e_mail, FILTER_VALIDATE_EMAIL)==false)
	{
     $err[] = "Введите корректный e-mail";
	}
//
	$prim=mysql_real_escape_string(htmlspecialchars(trim($_POST['prim'])));
		if(strlen($prim) > 255)
	{
     $err[] = "Слишком большое примечание";
	}
//
 # Если нет ошибок, то добавляем в БД
 if(count($err) == 0)
	{
	$tovars=serialize($_SESSION['tovars']);
	$counts=serialize($_SESSION['counts']);
	//
	db_con();
	utf8();
	//
	$q="select * from `client` where `e_mail`='".$e_mail."' and `phone`='".$phone."' and `fio`='".$fio."' LIMIT 1";
	$r=mysql_query($q);
	if(mysql_num_rows($r)==0){
		mysql_query("INSERT INTO `client`(fio,phone,e_mail) VALUES ('".$fio."','".$phone."','".$e_mail."')");
	}
	//
	$q="select `id_client` from `client` where `e_mail`='".$e_mail."' and `phone`='".$phone."' and `fio`='".$fio."' LIMIT 1";
	$r=mysql_query($q);
	while($row=mysql_fetch_array($r))
		{
		mysql_query("INSERT INTO `zakaz`(id_client,tovars,counts,prim,summ,status) VALUES ('".$row['id_client']."','".$tovars."','".$counts."','".$prim."', '".$summ."', 'Ожидает подтверждения')");
		$id_zakaz=mysql_insert_id();
		}
	$qq="select `id_zakaz` from `zakaz` where `id_client`='".$row['id_client']."' and `tovars`='".$tovars."' and `counts`='".$fio."' LIMIT 1";
	//
     //mysql_query("INSERT INTO `zakaz`(tovars,counts,fio,prim,phone,e_mail,summ,status) VALUES ('".$tovars."','".$counts."','".$fio."','".$prim."','".$phone."','".$e_mail."', '".$summ."', 'Ожидает подтверждения')");
	 unset($_SESSION['tovars']);
	 unset($_SESSION['counts']);
	//
	$to='';
	$q="select `e_mail` from `usr`";
	$count_usr=0;
	$r=mysql_query($q);
		while($row=mysql_fetch_array($r))
			{
			if($count_usr==0){$to="<".$row['e_mail'].">"; $count_usr=1;}
			else {$to.= " ,<".$row['e_mail'].">";}
			}
		$subject = "Новый заказ"; 
		$message = "<a href='".$link."nfu/zakaz/show/?id_zakaz=".$id_zakaz."' target='blank'><h2>Подробности по ссылке.</h2></a><hr><p align='center'>С уважением, ООО \"Сервичный центр\"</p>";
		$headers  = "Content-type: text/html; charset=utf-8; \r\n"; 
		$headers .= "From: ООО \"Сервисный центр\" <from@sc-gaz.info>\r\n"; 
		$headers .= "Reply-To: reply-to@sc-gaz.info\r\n"; 
		mail($to, $subject, $message, $headers); 
	//$to  = "<alik.al@bk.ru>, " ; 
	//$to .= "<mail2@example.com>"; 
	$to  = "<".$e_mail.">";
	$subject = "Спасибо за покупку"; 
	$message = " <h2>Ваш заказ №".$id_zakaz."</h2><h2>В ближайшее время с Вами свяжется менеджер для подтверждения заказа.</h2><a href='".$link."track/?id_zakaz=".$id_zakaz."&e_mail=".$e_mail."#track' target='blank'><h2>Вы можете самостоятельно отслеживать статус Вашего заказа по этой ссылке.</h2></a><hr><p align='center'>С уважением, ООО \"Сервичный центр\"</p>";
	$headers  = "Content-type: text/html; charset=utf-8; \r\n"; 
	$headers .= "From: ООО \"Сервисный центр\" <from@sc-gaz.info>\r\n"; 
	$headers .= "Reply-To: reply-to@sc-gaz.info\r\n"; 
	mail($to, $subject, $message, $headers); 

	//
	// - $to=$e_mail;
	// - $message = "<p>Уважаемый ".$fio.", благодарим Вас за заказ на сайте ".$link." . В ближайшее время с Вами связется менеджер для подтверждения заказа</p> </br>";
	// - mail($to, $subject, $message, $headers); 
	 // echo "<script>alert('Заказ совершён, ожидайте звонка менеджера. За состоянием заказа можно следить через меню \"Отслеживание заказа\".');</script>";
     echo "<script language='JavaScript' type='text/javascript'>location='".$link."thanks/'</script>";
	 exit();
	}
 else
	{
     echo "<div class='zakaz'><p class='text_title'>При заполнении корзины возникли следующие ошибки:</p><br>";
     foreach($err AS $error)
     {
         echo "<p class='error'>".$error."</p>";
     }
	 echo "</div><hr>";
	}
}
?>
<?php
if( empty($_SESSION['tovars']))
	{
	echo "<p class='text_title'>Корзина пуста</p>";
	echo "<p class='text_title'>Если Вы уже сделали заказ, то вы можете отследить его в пункте <a href='".$link."track/' class='inline'>Отслеживание заказа</a></p>";
	}
else
{
echo "<h1 class='text_title'>Корзина</h1>";
echo "<hr>";
//
echo "<form method='post'>";
//
echo "<table class='tovar_table word_break' id='table1' name='table1'>";
echo "<tr>";
echo "	<th>Номер</th>";
echo "	<th>Название товара</th>";
echo "	<th>Изм</th>";
echo "	<th>Цена</th>";
echo "	<th>В наличии</th>";
echo "	<th>Кол-во для покупки</th>";
echo "	<th>Удалить</th>";
echo "</tr>";
		
	foreach($_SESSION['tovars'] as $value){
			db_con();
			utf8();
			$q="select `id_tovar`,`name`,`izm`,`count`,`price` from `tovar` where `id_tovar`='".$value."'";
			$r=mysql_query($q);
			//
			//$cc=key($value);
			while($row=mysql_fetch_array($r))
			{
			$id=array_search($row['id_tovar'],$_SESSION['tovars']);
			echo "<tr class='tovar_table_tr' name='".$row['id_tovar']."'>";
				echo "<td>".$row['id_tovar']."</td>";
				echo "<td>".$row['name']."</td>";
				echo "<td>".$row['izm']."</td>";
				echo "<td id='price_".$row['id_tovar']."'>".round($row['price'],2)."</td>";
				echo "<td>".$row['count']."</td>";
				echo "<td><input type='text' name='count_".$row['id_tovar']."' value='".$_SESSION['counts'][$id]."' class='input_count' id='count_".$row['id_tovar']."' onkeyup='calc()'></td>";
				echo "<td><a class='button' onclick='confirm_del(".$id.");'>Удалить</a></td>";
				echo "</tr>";
			}
			
	}
utf8();
echo "</table><hr>";
//echo "</div>";
echo "<p class='zakaz text_title'>";
echo "<b>Стоимость заказа: </b><span id='summ'></span></p>";
?>
<?php
echo "<hr>";
echo "<h2 class='text_title'>Доставка и самовывоз.</h2>";
echo "<p class='text'>На все заказы по умолчанию действует самовывоз со склада ООО \" Сервисный центр \". Отдельную доставку заказа можно оформить при контакте с менеджером, который свяжется с Вами после оформления заказа.</p>";
echo "<hr>";
echo "<div class='people'></div>";
//echo "<form method='post'>";
echo "<div class='zakaz'>";
//
echo "<p class='text_title'>Заполните форму.</p>";
echo "<hr>";
if(!isset($_POST['submit']))
{
	$fio="";
	$phone="";
	$e_mail="";
	$prim="";
}
echo "<div><label>ФИО*:</label> <input type='text' name='fio' class='input_text' value='".$fio."'></div><br>";
echo "<div><label>Телефон*:<label> <input type='tel' name='phone' class='input_text' value='".$phone."'></div><br>";
echo "<div><label>e-mail*:<label> <input type='email' name='e_mail' class='input_text' value='".$e_mail."'></div><br>";
echo "<div><label>Примечание:<label> <textarea rows='10' cols='45' maxlenhth='255' name='prim' placeholder='Если у Вас есть примечания по выполнению заказа, вы можете указать их сюда, либо обговорить об этом с менеджером.'>".$prim."</textarea></div><br>";
echo "<div class='g-recaptcha' data-sitekey='6LfzvSIUAAAAANhw1mFtLrrZJzYIoA5lumWd8HiM'></div>";
echo "<br><input type='submit' name='submit' class='button_2' value='Оформить заказ**'>";
echo "<small>* - поля, обязательные для заполнения.</small><br>";
echo "<small>** - оформляя заказ, Вы даёте согласие на хранение и обработку персональных данных в соответсивии с 152-ФЗ о защите персональных данных.</small>";
echo "</div>";
echo "</form>";
echo "<hr>";
}
?>
</div>
<?php
require($struct.'footer.php');
?>