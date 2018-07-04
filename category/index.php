<?php
session_start();
function num_rows($r)
{
if(mysql_num_rows($r)==0){
	echo "<div class='zakaz'><h1 class='text_title'>По  данной категории нет товаров для продажи</h1><hr>";
	echo "<p class='text_title'>Попробуйте зайти позже</p></div>";
	return true;
	}
else return false;
}
//
//
if(!isset($_GET['id']) or empty($_GET['id'])) echo "<script language='JavaScript' type='text/javascript'>location='../'</script>";
$category=htmlspecialchars($_GET['id']);
require($_SERVER['DOCUMENT_ROOT']."/struct/main.php");
?>
<script>
	var malert=0;
</script>
<?
if(mysql_real_escape_string(htmlspecialchars($_GET['buy']))==1)
{
	echo "<div id='alert'>";
	echo "<div id='alert_wrap'>";
	echo "</div>";
	echo "<div id='alert_content'>";
	echo "<h1 class='text_title'>Товар добавлен в корзину</h1>";
	echo "<a id='close'>X</a>";
	echo "<hr>";
	echo "<p class='text_title'>После добавления товаров перейдите в <a href='".$link."cart/'>корзину</a> и оформите заказ.</p>";
	echo "<a class='button_50 left' id='continue'>Продолжить</a>";
	echo "<a class='button_50' href='".$link."cart/'>Корзина</a>";
	echo "</div>";
	echo "</div>";
	echo "<script>document.getElementById('alert').style.display = 'block'; malert=1;</script>";
}
if(mysql_real_escape_string(htmlspecialchars($_GET['buy']))==2)
{
	echo "<div id='alert'>";
	echo "<div id='alert_wrap'>";
	echo "</div>";
	echo "<div id='alert_content'>";
	echo "<h1 class='text_title'>Товар уже есть в корзине</h1>";
	echo "<a id='close'>X</a>";
	echo "<hr>";
	echo "<p class='text_title'>Этот товар уже есть в корзине. Изменить количество для покупки можно <a href='".$link."cart/'>в корзине.</a></p>";
	echo "<a class='button_50 left' id='continue'>Продолжить покупки</a>";
	echo "<a class='button_50' href='".$link."cart/'>Корзина</a>";
	echo "</div>";
	echo "</div>";
	echo "<script>document.getElementById('alert').style.display = 'block'; malert=1;</script>";
}

?>
<script>
//document.body.onclick=function(event)
document.getElementById("alert_wrap").onclick=function(event)
{
	//&& document.getElementById("alert").style.display == "visible";
    if(event.toElement.id!="alert" && malert==1)
    {
		document.getElementById("alert").style.visibility = "hidden";
		malert=0;
    }
}
document.getElementById("continue").onclick=function(event)
{
		document.getElementById("alert").style.visibility = "hidden";
		malert=0;
}
document.getElementById("close").onclick=function(event)
{
		document.getElementById("alert").style.visibility = "hidden";
		malert=0;
}
	</script>
<div class='content'>
<?php
	db_con();
	utf8();
	$q="select `desc` from `category` where `id_category`='".$category."'";
	$r=mysql_query($q);
	//
	//
	while($row=mysql_fetch_array($r))
	{
	echo "<p class='text_title'>".$row['desc']."</p>";
	}
	echo "<hr>";
?>
<?php
	$q="select `id_tovar`,`name`,`izm`,`count`,`price` from `tovar` where `id_category`='".$category."'";
	$r=mysql_query($q);
	//
	$ch=num_rows($r);
	if($ch==false){
	echo "<table class='tovar_table'>";//border='1' исключено 29.04
	echo "<tr>";
	echo "<th class='word_break'>Номер</th>";
	echo "<th class='word_break'>Название</th>";
	echo "<th>Изм</th>";
	echo "<th>Кол-во</th>";
	echo "<th>Цена</th>";
	echo "<th>Купить</th>";
	echo "</tr>";
	}
	unset($ch);
	//
	while($row=mysql_fetch_array($r))
	{
	if($row['count']>0)
	{
	echo "<tr class='tovar_table_tr'>";
		echo "<td class='word_break'>".$row['id_tovar']."</td>";
		echo "<td class='word_break'>".$row['name']."</td>";
		echo "<td>".$row['izm']."</td>";
		echo "<td>".$row['count']."</td>";
		echo "<td class='word_break'>".round($row['price'],2)."р</td>";//trim(number_format($row['price'], 2, ',', ' '), '0.');
		echo "<td class='word_break'><a class='buy' href='".$link."category/show/?id_tovar=".$row['id_tovar']."&category=".$category."'>Подробнее</a></td>";
		echo "</tr>";
	}
	}
?>
</table>
</div>
<?php
require($struct.'footer.php');
?>