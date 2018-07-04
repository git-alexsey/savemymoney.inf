<?php
// Прочитай Веб-дизайн Джеймс (вроде) Дарвин или Гарвин
//GRANT INSERT ON gazseller.zakaz TO 'default'@'%'
//GRANT INSERT ON gazseller.zakaz TO 'default'@'%'
//GRANT UPDATE('ip','hash') ON gazseller.usr TO 'default'@'%'
//GRANT INSERT ON gazseller.client TO 'default'@'%'
function db_con()
{
	$connect = mysql_connect('localhost', 'u0353240_default', '_cy8MaB!');
	if (!$connect) {
		die("<div class='error'>Ошибка</div>");
		break;
	}
	mysql_select_db("u0353240_default");
}
//
function db_con_root()
{
	$connect = mysql_connect('localhost', 'u0353240_default', '_cy8MaB!');
	if (!$connect) {
		die("<div class='error'>Ошибка</div>");
		break;
	}
	mysql_select_db("u0353240_default");
}
//
function utf8(){
mysql_query("SET NAMES 'utf8';");
mysql_query("SET CHARACTER SET 'utf8';");
mysql_query("SET SESSION collation_connection = 'utf8_general_ci';");	
}
//
function cp1251(){
mysql_query("SET NAMES 'cp1251';");
mysql_query("SET CHARACTER SET 'cp1251';");
mysql_query("SET SESSION collation_connection = 'cp1251_general_ci';");	
}
function category($id,$name,$category){
	if($id==$category) echo "<a class='head_block head_block_select' href='/category/?id=".$id."'>".$name."</a>";
	else echo "<a class='head_block' href='/category/?id=".$id."'>".$name."</a>";
}
function generateCode($length=6) {
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHI JKLMNOPRQSTUVWXYZ0123456789";
    $code = "";
    $clen = strlen($chars) - 1;  
    while (strlen($code) < $length) {
            $code .= $chars[mt_rand(0,$clen)];  
    }
    return $code;
}
//
function check(){
db_con();
if (!empty($_SESSION['id']) and !empty($_SESSION['hash']))
{
    $query = mysql_query("SELECT id_user, hash, ip, status FROM usr WHERE id_user='".intval($_SESSION['id'])."' LIMIT 1");
    $userdata = mysql_fetch_assoc($query);
    if(($userdata['hash'] != $_SESSION['hash']) or ($userdata['id_user'] != $_SESSION['id']))
    {
		unset($_SESSION['id']);
		unset($_SESSION['hash']);
        return false;
    }
	else {return $userdata['status'];}
}
else
{
	 return false;
}
}
function isModerator(){
	db_con();
	$query = mysql_query("SELECT status FROM usr WHERE id_user = '".intval($_SESSION['id'])."' LIMIT 1");
    $userdata = mysql_fetch_assoc($query);
if($userdata['status']=='moderator')return true;
else return false;
}
function isManager(){
	db_con();
	$query = mysql_query("SELECT status FROM usr WHERE id_user = '".intval($_SESSION['id'])."' LIMIT 1");
    $userdata = mysql_fetch_assoc($query);
if($userdata['status']=='manager' or $userdata['status']=='moderator')return true;
else return false;
}
function nfu(){
	$r=check();
	if($r==false)	 echo "<script language='JavaScript' type='text/javascript'>location=link</script>";
}
//
?>
