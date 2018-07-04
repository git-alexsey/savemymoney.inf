<?php session_start();
require($_SERVER['DOCUMENT_ROOT']."/struct/main.php");
include($site."connect.php");
$r=isModerator();
if($r==true)
{
if(!isset($_GET['id']) or empty($_GET['id'])) echo "<script language='JavaScript' type='text/javascript'>location=link</script>";
$id=htmlspecialchars($_GET['id']);
db_con_root();
mysql_query("DELETE FROM category WHERE id_category='".$id."'");
mysql_query("DELETE FROM tovar WHERE id_category='".$id."'");
cp1251();
echo "<script language='JavaScript' type='text/javascript'>location=link</script>";
}
//echo "<a class='button' href='http://gazseller.ru/nfu/zakaz/?status=".$status."'>Если Ваш Браузер не поддерживает переадресацию, нажмите сюда.</a>";
?>