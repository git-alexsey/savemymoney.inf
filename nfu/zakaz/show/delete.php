<?php session_start();
require($_SERVER['DOCUMENT_ROOT']."/struct/main.php");
include($site."connect.php");
$r=isManager();
if($r==true)
{
if(!isset($_GET['id']) or empty($_GET['id'])) echo "<script language='JavaScript' type='text/javascript'>location='http://gazseller.ru/'</script>";
if(!isset($_GET['status']) or empty($_GET['status'])) echo "<script language='JavaScript' type='text/javascript'>location='http://gazseller.ru/'</script>";
$id=htmlspecialchars($_GET['id']);
$status=htmlspecialchars($_GET['status']);
db_con_root();
mysql_query("DELETE FROM zakaz WHERE id_zakaz='".$id."'");
echo "<script>alert('Заказ успешно удалён.');</script>";
echo "<script language='JavaScript' type='text/javascript'>location=link+'nfu/zakaz/?status=".$status."'</script>";
}
//echo "<a class='button' href='http://gazseller.ru/nfu/zakaz/?status=".$status."'>Если Ваш Браузер не поддерживает переадресацию, нажмите сюда.</a>";
?>