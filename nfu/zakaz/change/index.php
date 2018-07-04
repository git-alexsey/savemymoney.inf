<?php
session_start();
$category=-1;
require($_SERVER['DOCUMENT_ROOT']."/struct/main.php");
//
nfu();
$r=isManager();
//
echo "<div class='content'>";
if($r==true)
{
if(!isset($_GET['id_zakaz']) or empty($_GET['id_zakaz'])) echo "<p class='error'>Ошибка, номер заказа не был передан на обработку, попробуйте выполнить запрос снова.</p>";
else{
$id_zakaz=htmlspecialchars($_GET['id_zakaz']);
echo "<h2 class='text_title'>Изменение заказа №".$id_zakaz."</h2><hr>";
//
if(isset($_POST['submit']))
{
 $err = array();
//
$id_client=htmlspecialchars($_POST['id_client']);
$fio=htmlspecialchars($_POST['fio']);
 if(strlen($fio) < 3 or strlen($fio) > 105)
	{
     $err[] = "ФИО должно быть не меньше 3-х символов и не больше 105 символов";
	}
//
$phone=htmlspecialchars($_POST['phone']);
 if(strlen($phone) < 6 or strlen($phone) > 15)
	{
     $err[] = "Телефон должен быть не меньше 7-х символов и не больше 15 символов";
	}
//
	$e_mail=htmlspecialchars($_POST['e_mail']);
	if(filter_var($e_mail, FILTER_VALIDATE_EMAIL)==false)
	{
     $err[] = "Введите корректный e-mail";
	}
//
	$prim=htmlspecialchars($_POST['prim']);
		if(strlen($prim) > 255)
	{
     $err[] = "Слишком большое примечание";
	}
//
 # Если нет ошибок, то добавляем в БД
 if(count($err) == 0)
	{
	db_con_root();
	utf8();
     mysql_query("UPDATE client SET fio='".$fio."', phone='".$phone."', e_mail='".$e_mail."' WHERE id_client='".$id_client."'");
	 mysql_query("UPDATE zakaz SET prim='".$prim."' WHERE id_zakaz='".$id_zakaz."'");
	 echo "<script>alert('Заказ успешно изменён.');</script>";
     echo "<script language='JavaScript' type='text/javascript'>location='".$link."nfu/zakaz/show/?id_zakaz=".$id_zakaz."'</script>";
	 exit();
	}
 else
	{
     echo "<br><p class='tovar_desc'>При заполнении корзины возникли следующие ошибки:</p><br>";
     foreach($err AS $error)
     {
         echo "<p class='error'>".$error."</p>";
     }
	 echo "<hr>";
	}
}
//
$q="select * from `zakaz` where `id_zakaz`='".$id_zakaz."' LIMIT 1";
$r=mysql_query($q);
while($row=mysql_fetch_array($r))
	{
	$id_client=$row['id_client'];
	$qq="select * from `client` where `id_client`='".$id_client."' LIMIT 1";
	$rr=mysql_query($qq);
	while($rrow=mysql_fetch_array($rr))
	{
		$fio=$rrow['fio'];
		$phone=$rrow['phone'];
		$e_mail=$rrow['e_mail'];
	}
	//
	if($row['status']=="Выдан") 
		echo "<script language='JavaScript' type='text/javascript'>location='".$link."nfu/zakaz/show/?id_zakaz=".$id_zakaz."'</script>";
	$prim=$row['prim'];
	$status=$row['status'];
	}
//
echo "<form method='post'>";
echo "<div class='zakaz'><p class='text_title'>Изменение примечания к заказу №".$id_zakaz."</p><hr>";
echo "<p>Заказ №".$id_zakaz."</p>";
echo "<p>Статус: ".$status."</p>";
echo "<div><label>Примечание:<label> <textarea rows='10' cols='45' maxlenhth='255' name='prim' placeholder='Если у Вас есть примечания по выполнению заказа, вы можете указать их сюда, либо обговорить об этом с менеджером.'>".$prim."</textarea></div><br>";
echo "</div>";
echo "<div class='zakaz'><p class='text_title'>Изменение данных заказчика</p><hr><p class='error'>При изменении данных заказчика, данные изменятся во всех заказах этого заказчика</p>";
echo "<hr>";
echo "<input name='id_client' value='".$id_client."' style='display:none;'>";
echo "<div><label>ФИО*:</label> <input type='text' name='fio' class='input_text' value='".$fio."'></div><br>";
echo "<div><label>Телефон*:<label> <input type='text' name='phone' class='input_text' value='".$phone."'></div><br>";
echo "<div><label>e-mail*:<label> <input type='text' name='e_mail' class='input_text' value='".$e_mail."'></div><br>";
echo "<p>* - поля, обязательные для заполнения.</p>";
echo "<input type='submit' name='submit' class='button_2' value='Изменить заказ'>";
echo "</div>";
echo "</form>";
echo "<hr>";
//
}

}
echo "</div>";
//
require($struct.'footer.php');
?>