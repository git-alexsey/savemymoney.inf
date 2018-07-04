<?php
session_start();
require($_SERVER['DOCUMENT_ROOT']."/struct/main.php");
unset($_SESSION['id']);
unset($_SESSION['hash']);
echo "<script language='JavaScript' type='text/javascript'>location=link</script>";
?>