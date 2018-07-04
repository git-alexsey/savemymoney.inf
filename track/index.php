<?php
session_start();
$category=-1;
require($_SERVER['DOCUMENT_ROOT']."/struct/main.php");

?>
<script src='https://www.google.com/recaptcha/api.js'></script>
<div class='content'>
	<div class='text_title'>
	<h2>Отслеживание заказа</h2>
	<hr>
	<form method='post' class='zakaz'>
	<label>Введите e-mail, указанный в заказе.</label>
	<div class='p5'><input type='text' name='track' class='input_text'>
	<input type='submit' name='submit' class='button_3' value='Отследить заказ'></div>
	<div class="g-recaptcha" data-sitekey="6LfzvSIUAAAAANhw1mFtLrrZJzYIoA5lumWd8HiM" align='center'></div>
	</form>
<?php
if(isset($_POST['submit']))
{
	$err = array();
	$track=mysql_real_escape_string(trim(htmlspecialchars($_POST['track'])));
	if(filter_var($track, FILTER_VALIDATE_EMAIL)==false)
	{
		$err[] = "Введите корректный e-mail";
	}
	//
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
	if(count($err)==0){
		echo "<hr><h2 class='text_title'>Отслеживание \"".$track."\"</h2>";	
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
	//
	db_con();
	if(count($err)==0)
	{
		$q="select `id_client`,`fio` from `client` where `e_mail`='".$track."'";
		$r=mysql_query($q);
		if(mysql_num_rows($r)==0){
			echo "<div class='zakaz'><h1 class='text_title'>Поиск не дал результатов по вашему запросу</h1><hr>";
			echo "<p class='tovar_desc'>Проверьте правильность написания запроса</p></div>";
		}
		else{
		while($row=mysql_fetch_array($r))
			{
				$qq="select `id_zakaz`,`date` from `zakaz` where `id_client`='".$row['id_client']."' ORDER BY id_zakaz DESC";
				$rr=mysql_query($qq);
				if(mysql_num_rows($rr)!=0)echo "<hr>";
				//echo "<div class='zakaz'>";
				while($rrow=mysql_fetch_array($rr))
				{
					$date=strtotime($rrow['date']);
					//echo "<a class='button_2' href='".$link."track/?id_zakaz=".$rrow['id_zakaz']."&e_mail=".$track."'>Заказ №".$rrow['id_zakaz']." от ".date('d.m.Y H:i',$date)."<br> ".$row['fio']."</a><br>";
				//
				echo "<a class='block' href='".$link."track/?id_zakaz=".$rrow['id_zakaz']."&e_mail=".$track."#track'><strong>Заказ №".$rrow['id_zakaz']."<hr class='bold'></strong><p>Дата заказа: <strong>".date('d.m.Y H:i',$date)."</strong></p><p>Заказчик: <strong>".$row['fio']."</strong></p></a>";
				//
				}
				//echo "</div>";
			}
		}
		//
	}
	else
	{
		echo "<div class='zakaz'><h1 class='text_title'>Введите e-mail, указанный при оформлении заказа.</h1><hr>";
		echo "<p class='text_title'>Если у вас возникли проблемы с отслеживанием заказа, обратитесь к менеджеру по телефону, указанному в контактах. </p></div>";
	}
}

if(isset($_GET['id_zakaz']) and isset($_GET['e_mail']))
{
	$id_zakaz=mysql_real_escape_string(htmlspecialchars($_GET['id_zakaz']));
	$e_mail=mysql_real_escape_string(htmlspecialchars($_GET['e_mail']));
	db_con();
	//
	$id_client=0;
	//
	$q="select * from `zakaz` where `id_zakaz`='".$id_zakaz."'";
	$r=mysql_query($q);
	while($row=mysql_fetch_array($r))
	{
		$id_client=$row['id_client'];
		//
		$qq="select * from `client` where `id_client`='".$id_client."' and `e_mail`='".$e_mail."'";
		$rr=mysql_query($qq);
		while($rrow=mysql_fetch_array($rr))
		{
			$c_fio=$rrow['fio'];
			$c_phone=$rrow['phone'];
			$c_e_mail=$rrow['e_mail'];
		}
		if($c_e_mail!==$e_mail)break;
		//
		
		$date=strtotime($row['date']);
		echo "<a name='track'></a><hr>";
		echo "<div class='zakaz text'>";
		echo "<p class='text_title'>Заказ №".$row['id_zakaz']."</p><hr>";
		echo "<p>Дата заказа: ".date('d.m.Y H:i',$date)."</p>";
		echo "<p>Заказчик: ".$c_fio."</p>";
		echo "<p>Контактный телефон: ".$c_phone."</p>";
		echo "<p>E-mail: ".$c_e_mail."</p>";
		echo "<p>Сумма заказа: ".$row['summ']." рублей</p>";
		echo "<p>Статус заказа: ".$row['status']."</p>";
		if($row['prim']!='')
		{
			echo "<p>Примеччание к заказу: ".$row['prim']."</p>";
		}
		echo "<p class='tovar_desc'>Товары</p>";
		#
		#
		#
		echo "<table class='tovar_table'>";
		echo "<tr>";
		echo "	<th>Номер</th>";
		echo "	<th>Название товара</th>";
		echo "	<th>Изм</th>";
		echo "	<th>Кол-во</th>";
		echo "	<th>Цена</th>";
		echo "</tr>";

	$tovars=unserialize($row['tovars']); 
	$counts=unserialize($row['counts']);
	foreach($tovars as $value){
			db_con();
			utf8();
			$qq="select `id_tovar`,`name`,`izm`,`price` from `tovar` where `id_tovar`='".$value."'";
			$rr=mysql_query($qq);
			//
			if(mysql_num_rows($rr)==0){
				echo "<tr class='tovar_table_tr'>";
				echo "<td colspan=5>Товар №<span class='error'>".$value."</span> был удалён. Для предоставления информации о нём обратитесь к менеджеру.</td>";
				echo "</tr>";
			}
			//
			//$cc=key($value);
			while($rrow=mysql_fetch_array($rr))
			{
			$id=array_search($rrow['id_tovar'],$tovars);
			echo "<tr class='tovar_table_tr'>";
				echo "<td>".$rrow['id_tovar']."</td>";
				echo "<td>".$rrow['name']."</td>";
				echo "<td>".$rrow['izm']."</td>";
				echo "<td><p>".$counts[$id]."</p></td>";
				echo "<td>".round($rrow['price'],2)."р</td>";
				echo "</tr>";
			}
			
			
	}
echo "</table>";
		#
		#
		echo "</div>";
		
		}
}
?>
	</div>
</div>
<?php
require($struct.'footer.php');
?>