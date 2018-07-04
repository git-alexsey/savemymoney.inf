<!DOCTYPE html>
<?php
$site=$_SERVER['DOCUMENT_ROOT']."/";
$site_link=$_SERVER['SERVER_NAME']."/";
$link="http://sc-gaz.info/";
$struct=$_SERVER['DOCUMENT_ROOT']."/struct/";
include($site."connect.php");
require($struct.'header.php');
echo "<script>var link='".$link."';</script>";
?>