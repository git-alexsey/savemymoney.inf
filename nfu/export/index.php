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
				$csv_file="�����;��������� �����;��������;���������;����������;��� ���������;����\r\n";
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
			{echo "<script>alert('Список товаров пуст');</script>";}
		}
		else if($_POST['export']=='zakaz')
		{
			$q="SELECT * FROM zakaz";
			$r=mysql_query($q);
			if(mysql_num_rows($r)!==0)
			{
				while($row=mysql_fetch_assoc($r))
				{
				$csv_file.="����� �;".$row['id_zakaz']."\r\n";
				//
				$csv_file.="������ �������:;";
				$tovars=unserialize($row['tovars']);
				foreach($tovars as $value)
				{
					$csv_file.=$value.";";
				}
				$csv_file.="\r\n";
				//
				$csv_file.="���������� ������:;";
				$counts=unserialize($row['counts']);
				foreach($counts as $value)
				{
					$csv_file.=$value.";";
				}
				$csv_file.="\r\n";
				//
				//$date=strtotime($row['date']);
				$csv_file.="���� ������:;".$row['date']."\r\n";
				$csv_file.="����� ���������:;".$row['id_client']."\r\n";
				$csv_file.="����������:;".$row['prim']."\r\n";
				$csv_file.="����� ������:;".$row['summ']."\r\n";
				$csv_file.="������ ������:;".$row['status']."\r\n";
				$csv_file.="\r\n";
				}
			$file_name='zakaz.csv';
			$file=fopen($file_name,"w");
			fwrite($file,trim($csv_file));
			fclose($file);
			echo "<script language='JavaScript' type='text/javascript'>location='".$link."nfu/export/".$file_name."'</script>";
			}
			else
			{echo "<script>alert('Список товаров пуст');</script>";}
		}
		else if($_POST['export']=='client')
		{
			$q="SELECT * FROM client";
			$r=mysql_query($q);
			if(mysql_num_rows($r)!==0)
			{
				$csv_file="����� �������;���;�������;E-mail\r\n";
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
			{echo "<script>alert('Список товаров пуст');</script>";}
		}
}
//
echo "<div class='content'>";
echo "<h1 class='text_title'>Экспорт</h1><hr><br>";
echo "<form class='zakaz' method='post'>";
echo "<p class='text_title'>Выберите экспорт:</p><hr>";
echo "<select name='export' class='input_text'>";
echo "<option value='tovar'>Экспорт товаров</option>";
echo "<option value='zakaz'>Экспорт заказов</option>";
echo "<option value='client'>Экспорт клиентов</option>";
echo "</select>";
echo "<br><br><input type='submit' name='submit' value='Экспорт' class='button_2'>";
echo "</form>";


echo "</div>";
}
else {
	echo "<script language='JavaScript' type='text/javascript'>location='".$link."'</script>";
}
//
require($struct.'footer.php');
?>