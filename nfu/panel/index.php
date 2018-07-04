<?php
session_start();
$category=-1;
require($_SERVER['DOCUMENT_ROOT']."/struct/main.php");
//
check();
//db_con_user();
db_con_root();
$r=isManager();
if($r==true)
{
require($_SERVER['DOCUMENT_ROOT']."/struct/admin_panel.php");
}
else {
	echo "<script language='JavaScript' type='text/javascript'>location='".$link."'</script>";
}
//
require($struct.'footer.php');
?>