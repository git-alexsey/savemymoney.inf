<?php
session_start();
$category=-1;
require($_SERVER['DOCUMENT_ROOT']."/struct/main.php");
//
nfu();
$r=isManager();
if($r==true)
{
//
$status=htmlspecialchars($_GET['status']);
?>
	<div class='content'>
	<div class='text_title'>
		<?php
		echo "<h1 class='text_title'>".$status."</h1><hr>";
		//echo "<div class='zakaz'>";
		$q="select `id_zakaz`,`date`,`id_client` from `zakaz` where `status`='".$status."' ORDER BY id_zakaz DESC";
		$r=mysql_query($q);
		if(mysql_num_rows($r)==0){
			echo "<p class='text_title'>По данному статусу нет соответствующих заказов</p>";
		}
		#echo "<p>Выберите заказ</p>";
		while($row=mysql_fetch_array($r))
		{
			$qq="select `fio` from `client` where `id_client`='".$row['id_client']."'";
			$rr=mysql_query($qq);
			while($rrow=mysql_fetch_array($rr)){$c_fio=$rrow['fio'];}
			//	
			$date=strtotime($row['date']);
			//echo "<a class='button_2' href='".$link."nfu/zakaz/show/?id_zakaz=".$row['id_zakaz']."'>Заказ №".$row['id_zakaz']." от ".date('d.m.Y H:i',$date)."<br> ".$c_fio."</a><br>";
			echo "<a class='block' href='".$link."nfu/zakaz/show/?id_zakaz=".$row['id_zakaz']."'><strong>Заказ №".$row['id_zakaz']."</strong><hr><p>Дата заказа: <strong>".date('d.m.Y H:i',$date)."</strong></p><p>Заказчик: <strong>".$c_fio."</strong></p></a>";
		}
		//echo "</div>";
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