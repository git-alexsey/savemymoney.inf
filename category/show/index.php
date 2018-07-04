<?php
session_start();
$category=htmlspecialchars($_GET['category']);
require($_SERVER['DOCUMENT_ROOT']."/struct/main.php");
?>
<script>
window.onload=function (){calc();};
//
function calc(){
var summ=0;
var count=document.getElementById('count').value;
var price=document.getElementById('price').innerHTML;
summ+=count * price;
summ=Math.round(summ*100)/100
if (isNaN(summ)) {summ='<span class=error>Введите корректные числа в поля \"Количество для покупки\"</span>';}
else {summ+=' рублей';};
document.getElementById('summ').innerHTML=summ;
};
</script>
<?php
//
function add($id_tovar,$category,$count){
$check=0;
if(isset($_SESSION['tovars'])==false){$_SESSION['tovars']=array(); $_SESSION['counts']=array();}
foreach($_SESSION['tovars'] as $value){
	if($value == $id_tovar)$check=1;
}
if($check==1) 
{	
	//echo "<script>alert('Этот товар уже есть в корзине, его количеством можно управлять из корзины.');</script>";
	echo "<script language='JavaScript' type='text/javascript'>location=link+'category/?id=".$category."&buy=2'</script>";
}
	else 
	{
	array_push($_SESSION['tovars'],$id_tovar);
	array_push($_SESSION['counts'],$count);
	//echo "<script>alert('Товар успешно добавлен в корзину.');</script>";
	echo "<script language='JavaScript' type='text/javascript'>location=link+'category/?id=".$category."&buy=1'</script>";
	}
}
//
$id_tovar=htmlspecialchars($_GET['id_tovar']);
//
if(isset($_POST['submit']))
{
	$id=htmlspecialchars($_POST['id']);
	$count=htmlspecialchars($_POST['count']);
	$q="select count, id_category from tovar where id_tovar='".$id."' LIMIT 1";
	$r=mysql_query($q);
	while($row=mysql_fetch_array($r))
	{
		if($count>0 and $count<=$row['count']) add($id,$category,$count);
		else
			echo "<p class='error text_title'>Введите в поле \"Количество для покупки\" число от 0 до максимального количества товара</p>";
	}
}

	echo "<div class='content'>";
	db_con();
	//
	$q="select * from tovar where id_tovar='".$id_tovar."'";
	$r=mysql_query($q);
	//
	if(mysql_num_rows($r)==0){
		echo "<h1 class='text_title'>Заказа с таким номером не существует</h1><hr>";
		echo "<p class='text_title'>Попобуйте воспользоваться <a href='".$link."search/?search=".$id_tovar."'>поиском</a></p>";
	}
	else {
		while($row=mysql_fetch_array($r))
		{
			echo "<h1 class='text_title'>".$row['name']."</h1><hr>";
			//
			$jpg='.jpg';
			$png='.png';
			$jpeg='.jpeg';
			$gif='.gif';
			$filename=$site."category/show/img/".$row['id_tovar'];
			if(file_exists($filename.$jpg))
				echo "<img src='".$link."category/show/img/".$row['id_tovar'].$jpg."' class='img_center'>";
			if(file_exists($filename.$png))
				echo "<img src='".$link."category/show/img/".$row['id_tovar'].$png."' class='img_center'>";
			if(file_exists($filename.$jpeg))
				echo "<img src='".$link."category/show/img/".$row['id_tovar'].$jpeg."' class='img_center'>";
			if(file_exists($filename.$gif))
				echo "<img src='".$link."category/show/img/".$row['id_tovar'].$gif."' class='img_center'>";
			//
			echo "<p class='zakaz text_title'>";
			echo "<b>Стоимость: </b><span id='summ'></span></p>";
			//
			echo "<form class='zakaz text' method='post'>";
			echo "<p class='text_title'>Подробная информация о товаре</p><hr>";
			echo "<p>Номер товара: ".$row['id_tovar']."</p>";
			//
			$qq="select * from category where id_category='".$row['id_category']."'";
			$rr=mysql_query($qq);
			while($rrow=mysql_fetch_array($rr))
			{
				echo "<p>Категория: ".$rrow['name']."</p>";
			}
			//
			echo "<p>Количество: ".$row['count']."</p>";
			echo "<p>Единица измерения: ".$row['izm']."</p>";
			echo "<p>Цена за единицу товара: <span id='price'>".$row['price']."</span> рублей</p>";
			echo "<input name='id' value='".$row['id_tovar']."' style='display:none;'>";
			echo "<p class='inline'>Количество для покупки:</p> <input type='text' name='count' class='input_text' id='count' value='".$row['count']."' onkeyup='calc()'>";
			echo "<br><br><input type='submit' name='submit' class='button_2' value='Добавить в корзину'>";
			echo "</form>";
		}
	}
	echo "</div>";
?>
	
	</div>
	</div>
<?php
require($struct.'footer.php');
?>