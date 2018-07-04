<?php
session_start();
$category=-1;
require($_SERVER['DOCUMENT_ROOT']."/struct/main.php");
//
check();
echo "<script language='JavaScript' type='text/javascript'>location='".$link."nfu/auht/'</script>";
//
require($struct.'footer.php');
?>