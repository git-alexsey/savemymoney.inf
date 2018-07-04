<?php
session_start();
$category=-1;
require($_SERVER['DOCUMENT_ROOT']."/struct/main.php");
//
nfu();
$r=isManager();
if($r==true)
{
if(isset($_POST['submit']))
{
	cp1251();
	$csv_file='';
	if($_POST['export']=='tovar')
		{
			$q="SELECT * FROM tovar";
			$r=mysql_query($q);
			if(mysql_num_rows($r)!==0)
			{
				$count_str=1;
				$csv_file="Номер;Табельный номер;Название;Измерение;Количество;Код категории;Цена\r\n";
				while($row=mysql_fetch_assoc($r))
				{
				$csv_file.=$count_str.";".$row['id_tovar'].";".$row['name'].";".$row['izm'].";".$row['count'].";".$row['id_category'].";".$row['price']."\r\n";
				$count_str+=1;
				}
			$file_name='products.csv';
			$file=fopen($file_name,"w");
			fwrite($file,trim($csv_file));
			fclose($file);
			echo "<script language='JavaScript' type='text/javascript'>location='".$link."nfu/export/".$file_name."'</script>";
			}
			else
			{echo "<script>alert('РЎРїРёСЃРѕРє С‚РѕРІР°СЂРѕРІ РїСѓСЃС‚');</script>";}
		}
		else if($_POST['export']=='zakaz')
		{
			$q="SELECT * FROM zakaz";
			$r=mysql_query($q);
			if(mysql_num_rows($r)!==0)
			{
				while($row=mysql_fetch_assoc($r))
				{
				$csv_file.="Заказ №;".$row['id_zakaz']."\r\n";
				//
				$csv_file.="Номера товаров:;";
				$tovars=unserialize($row['tovars']);
				foreach($tovars as $value)
				{
					$csv_file.=$value.";";
				}
				$csv_file.="\r\n";
				//
				$csv_file.="Количество товара:;";
				$counts=unserialize($row['counts']);
				foreach($counts as $value)
				{
					$csv_file.=$value.";";
				}
				$csv_file.="\r\n";
				//
				//$date=strtotime($row['date']);
				$csv_file.="Дата заказа:;".$row['date']."\r\n";
				$csv_file.="Номер заказчика:;".$row['id_client']."\r\n";
				$csv_file.="Примечание:;".$row['prim']."\r\n";
				$csv_file.="Сумма заказа:;".$row['summ']."\r\n";
				$csv_file.="Статус заказа:;".$row['status']."\r\n";
				$csv_file.="\r\n";
				}
			$file_name='zakaz.csv';
			$file=fopen($file_name,"w");
			fwrite($file,trim($csv_file));
			fclose($file);
			echo "<script language='JavaScript' type='text/javascript'>location='".$link."nfu/export/".$file_name."'</script>";
			}
			else
			{echo "<script>alert('РЎРїРёСЃРѕРє С‚РѕРІР°СЂРѕРІ РїСѓСЃС‚');</script>";}
		}
		else if($_POST['export']=='client')
		{
			$q="SELECT * FROM client";
			$r=mysql_query($q);
			if(mysql_num_rows($r)!==0)
			{
				$csv_file="Номер клиента;ФИО;Телефон;E-mail\r\n";
				while($row=mysql_fetch_assoc($r))
				{
				$csv_file.=$row['id_client'].";".$row['fio'].";".$row['phone'].";".$row['e_mail']."\r\n";
				}
			$file_name='clients.csv';
			$file=fopen($file_name,"w");
			fwrite($file,trim($csv_file));
			fclose($file);
			echo "<script language='JavaScript' type='text/javascript'>location='".$link."nfu/export/".$file_name."'</script>";
			}
			else
			{echo "<script>alert('РЎРїРёСЃРѕРє С‚РѕРІР°СЂРѕРІ РїСѓСЃС‚');</script>";}
		}
}
//
echo "<div class='content'>";
echo "<h1 class='text_title'>Р­РєСЃРїРѕСЂС‚</h1><hr><br>";
echo "<form class='zakaz' method='post'>";
echo "<p class='text_title'>Р’С‹Р±РµСЂРёС‚Рµ СЌРєСЃРїРѕСЂС‚:</p><hr>";
echo "<select name='export' class='input_text'>";
echo "<option value='tovar'>Р­РєСЃРїРѕСЂС‚ С‚РѕРІР°СЂРѕРІ</option>";
echo "<option value='zakaz'>Р­РєСЃРїРѕСЂС‚ Р·Р°РєР°Р·РѕРІ</option>";
echo "<option value='client'>Р­РєСЃРїРѕСЂС‚ РєР»РёРµРЅС‚РѕРІ</option>";
echo "</select>";
echo "<br><br><input type='submit' name='submit' value='Р­РєСЃРїРѕСЂС‚' class='button_2'>";
echo "</form>";


echo "</div>";
}
else {
	echo "<script language='JavaScript' type='text/javascript'>location='".$link."'</script>";
}
//
require($struct.'footer.php');
?>