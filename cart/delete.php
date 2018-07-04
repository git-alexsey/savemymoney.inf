<?php session_start();
require($_SERVER['DOCUMENT_ROOT']."/struct/main.php");
if(!isset($_GET['id']) or empty($_GET['id'])) echo "<script language='JavaScript' type='text/javascript'>location=link+'cart/'</script>";
$id=htmlspecialchars($_GET['id']);
if(isset($_SESSION['tovars'][$id]))
{
unset($_SESSION['tovars'][$id]);
unset($_SESSION['counts'][$id]);
}
echo "<script language='JavaScript' type='text/javascript'>location=link+'cart/'</script>";
echo "<a class='button' href='".$link."cart/'>Если Ваш Браузер не поддерживает переадресацию, нажмите сюда.</a>";
?>