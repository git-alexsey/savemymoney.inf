<?php
session_start();
$category=-1;
require($_SERVER['DOCUMENT_ROOT']."/struct/main.php");
?>
<script>
function confirm_del(id){
	result = confirm("Вы действительно хотите удалить этот товар из заказа?");
	if(result===true){
		<?php echo "location='".$link."nfu/zakaz/show/delete.php?id='+id+'&status=Отклонён';";?>
	}
}
</script>
<?php
nfu();
$r=isManager();
if($r==true)
{
//
if(isset($_POST['submit']))
{
	$id_zakaz=$_POST['id_zakaz'];
	$status=$_POST['status'];
	db_con_root();
	utf8();
	if($status=="Принят")
		{
		$q="select * from `zakaz` where id_zakaz='".$id_zakaz."'";
		$r=mysql_query($q);
		while($row=mysql_fetch_array($r))
			{
			//
			
			$tovars=unserialize($row['tovars']);
			$counts=unserialize($row['counts']);
			//
			foreach($tovars as $value){
			$id=array_search($value,$tovars);
			$rrr=mysql_query("select count from tovar where id_tovar='".$value."'");
			//
				while($rrrow=mysql_fetch_array($rrr))
				{
				$c=$rrrow['count'] - $counts[$id];
				mysql_query("UPDATE tovar SET count='".$c."' WHERE id_tovar='".$value."'");
				}
			}
			//
			}
		}
	else if($status=="Отклонён")
	{
		$rr=mysql_query("select status from zakaz where id_zakaz='".$id_zakaz."'");
			//
				while($rrow=mysql_fetch_array($rr))
				{
				$stat=$rrow['status'];
				if($stat!="Ожидает подтверждения")
					{
					$q="select * from `zakaz` where id_zakaz='".$id_zakaz."'";
					$r=mysql_query($q);
					while($row=mysql_fetch_array($r))
							{
							$tovars=unserialize($row['tovars']);
							$counts=unserialize($row['counts']);
							//
							foreach($tovars as $value){
							$id=array_search($value,$tovars);
							$rrr=mysql_query("select count from tovar where id_tovar='".$value."'");
							//
								while($rrrow=mysql_fetch_array($rrr))
								{
								$c=$rrrow['count'] + $counts[$id];
								mysql_query("UPDATE tovar SET count='".$c."' WHERE id_tovar='".$value."'");
								}
							}
							//
						}
					}
				}
	}
	mysql_query("UPDATE zakaz SET status='".$status."' WHERE id_zakaz='".$id_zakaz."'");
	echo "<script>alert('Статус успешно изменён.');</script>";
	$dir="http://".$_SERVER['SERVER_NAME'].dirname($_SERVER['PHP_SELF'])."/";
	echo "<script language='JavaScript' type='text/javascript'>location='".$dir."?id_zakaz=".$id_zakaz."'</script>";
}
//
$status=htmlspecialchars($_GET['id_zakaz']);
?>
	<div class='content'>
		<?php
	$id_zakaz=htmlspecialchars($_GET['id_zakaz']);
	db_con();
	$q="select * from `zakaz` where id_zakaz='".$id_zakaz."'";
	$r=mysql_query($q);
	//
	if(mysql_num_rows($r)==0){
		echo "<h1 class='text_title'>Заказа с таким номером не существует</h1><hr>";
		echo "<p class='tovar_desc'>Попобуйте воспользоваться поиском</p>";
	}
	//
	# echo "<p>Выберите заказ</p>";
	while($row=mysql_fetch_array($r))
	{
		//
		$id_client=$row['id_client'];
			//
			$qq="select * from `client` where id_client='".$id_client."'";
			$rr=mysql_query($qq);
			while($rrow=mysql_fetch_array($rr)){
				$c_fio=$rrow['fio'];
				$c_phone=$rrow['phone'];
				$c_e_mail=$rrow['e_mail'];
			}
		//
		$id_zakaz=$row['id_zakaz'];
		$date=strtotime($row['date']);
		echo "<h1 class='text_title'>".$c_fio."</h1>";
		echo "<h1 class='text_title'>".$c_phone."</h1>";
		echo "<hr>";
		//
		echo "<div class='zakaz text'>";
		echo "<h3 class='tovar_desc'>".$row['status']."</h3><hr>";
		echo "<p>Заказ №".$row['id_zakaz']."</p>";
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
	$status=$row['status'];
	$err=array();
	foreach($tovars as $value){
			db_con();
			utf8();
			$qq="select `id_tovar`, `name`,`izm`,`price`,`count` from `tovar` where `id_tovar`='".$value."'";
			$rr=mysql_query($qq);
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
				if($counts[$id]>$rrow['count'])
					$err[] = $rrow['id_tovar'];
			}
			
			
	}
	echo "</table>";
	if(!empty($err) and $status=="Ожидает подтверждения"){
			echo "<div class='zakaz'><p class='error text_title'>Заказанное количетво товара, больше имеющегося. Скорее всего недостающая часть товара была куплена одним из предыдущих покупателей, который подтвердил заказ.";
			echo "<table class='tovar_table'>";
			echo "<tr><th>Номер</th><th>В заказе</th><th>Осталось</th></tr>";
			foreach($err AS $error)
			{
			$qq="select `id_tovar`, `count` from `tovar` where `id_tovar`='".$error."'";
			$rr=mysql_query($qq);
			while($rrow=mysql_fetch_assoc($rr))
				{
				$id_t=array_search($error,$tovars);
				echo "<tr><td>".$error."</td><td>".$counts[$id_t]."</td><td>".$rrow['count']."</td></tr>";
				}
			}
			echo "</table></p></div>";
			
	}

		#
		#
		echo "</div>";
		
		
//
//
if($status=="Ожидает подтверждения")
	echo "<p class='zakaz tovar_desc'>При смене статуса на \"Принят\", количество заказанного товара автоматически списываются со списка товаров.</p>";
		if($status=="Принят" or $status=="Ожидает выдачи")
	echo "<p class='zakaz tovar_desc'>При смене статуса на \"Отклонён\", количество заказанного товара снова пополнит ряды списка товаров.</p>";
//
//
if($status!=="Выдан")
{
	echo "<form class='zakaz' method='post'>";
	echo "<h4 class='text_title'>Изменить статус заказа</h4><hr>";
	echo "<input name='id_zakaz' value='".$id_zakaz."' style='display:none;'>";
	echo "<label>Статус заказа:</label>";
		echo "<select name='status' class='input_text'>";
			//echo "<option value='Ожидает подтверждения'>Ожидает подтверждения</option>";
			if($status=='Отклонён')echo "<option value='Ожидает подтверждения'>Ожидает подтверждения</option>";
			if($status=='Ожидает подтверждения' or $status=='Отклонён')echo "<option value='Принят'>Принят</option>";
			if($status=='Ожидает подтверждения' or $status=='Принят' or $status=='Ожидает выдачи')echo "<option value='Отклонён'>Отклонён</option>";
			if($status=='Принят')echo "<option value='Ожидает выдачи'>Ожидает выдачи</option>";
			if($status=='Ожидает выдачи')echo "<option value='Выдан'>Выдан</option>";
		echo "</select>";
	echo "<input name='submit' type='submit' class='button_3' value='Изменить'>";
	echo "</form>";
//
		echo "<a class='zakaz button_2' href='".$link."nfu/zakaz/change/?id_zakaz=".$id_zakaz."'>Изменение данных заказчика и примечения к заказу</a>";
		if($status=="Отклонён") 
		{
			//echo "<a class='zakaz button_2 error' href='".$link."nfu/zakaz/show/delete.php?id=".$id_zakaz."&status=".$status."'>Удалить заказ</a>";
			echo "<a class='zakaz button_2 error' onclick='confirm_del(".$id_zakaz.");'>Удалить заказ</a>";
			}
		else 
		{
			echo "<p class='zakaz tovar_desc'>Чтобы удалить заказ, нужно скачала перевести его статус в \"Отклонён\"</p>";
			}
	}
}
?>
	
	</div>
	</div>
<?php
}
else {
	echo "<script language='JavaScript' type='text/javascript'>location='".$link."'</script>";
}
//
require($struct.'footer.php');
?>