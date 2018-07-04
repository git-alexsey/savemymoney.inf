<?php
session_start();
$category=-1;
require($_SERVER['DOCUMENT_ROOT']."/struct/main.php");
//
nfu();
?>
<div class='content'>
	<div class='tovar_desc'>
	<h2>Поиск заказа</h2><hr>
	<div class='zakaz'>
	<h2 class='text_title'>Заполните одно из полей</h2>
	<hr>
	<form method='get'>
	<label>Введите ФИО, указанный в заказе.</label>
	<input type='text' name='FIO' class='input_text'>
	<input type='submit' class='button_3' value='Найти заказ'>
	</form>
	<hr>
	<form method='get'>
	<label>Введите телефон, указанный в заказе.</label>
	<input type='text' name='phone' class='input_text'>
	<input type='submit' class='button_3' value='Найти заказ'>
	</form>
	<hr>
	<form method='get'>
	<label>Введите e-mail, указанный в заказе.</label>
	<input type='text' name='e_mail' class='input_text'>
	<input type='submit' class='button_3' value='Найти заказ'>
	</form>
	</div>
<?php
if(isset($_GET['FIO']))search('FIO');
else if(isset($_GET['phone']))search('phone');
else if(isset($_GET['e_mail']))search('e_mail');
function search($param){
	$err = array();
	$track=trim(mysql_real_escape_string(htmlspecialchars($_GET[$param])));
	if(strlen($track)<1 or strlen($track)>45)
	{
		$err[]="Введите корректное значение поиска";
	}
	db_con();
	if(count($err)==0)
	{
		echo "<hr><h2 class='text_title'>Поиск \"".$track."\"</h2><hr>";
		$q="select `id_client`,`fio` from `client` where `".$param."` like '%".$track."%'";
		$r=mysql_query($q);
		if(mysql_num_rows($r)==0){
		echo "<div class='zakaz'><h1 class='text_title'>Поиск не дал результатов по вашему запросу</h1><hr>";
		echo "<p class='tovar_desc'>Проверьте правильность написания запроса</p></div>";
		}
		else 
		{
		echo "<p class='text_title'>Найдены следующие заказы:</p>";
		//
		//echo "<div class='zakaz'>";
		while($row=mysql_fetch_array($r))
		{
			$c_fio=$row['fio'];
			$qq="select `id_zakaz`,`date` from `zakaz` where `id_client` like '".$row['id_client']."' ORDER BY id_zakaz DESC";
			$rr=mysql_query($qq);
			while($rrow=mysql_fetch_array($rr))
			{
			$date=strtotime($rrow['date']);
			//echo "<a class='button_2' href='http://gazseller.ru/nfu/zakaz/show/?id_zakaz=".$rrow['id_zakaz']."'>Заказ №".$rrow['id_zakaz']." от ".date('d.m.Y H:i',$date)."<br> ".$c_fio."</a><br>";
			echo "<a class='block' href='".$link."nfu/zakaz/show/?id_zakaz=".$rrow['id_zakaz']."'><strong>Заказ №".$rrow['id_zakaz']."</strong><hr><p>Дата заказа: <strong>".date('d.m.Y H:i',$date)."</strong></p><p>Заказчик: <strong>".$c_fio."</strong></p></a>";
			}
			if(mysql_num_rows($rr)==0)echo "<hr>";
			//
		}
		}
			//echo "</div>";
	}
}
?>
	</div>
</div>
<?php
require($struct.'footer.php');
?>