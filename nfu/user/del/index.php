<?php
session_start();
$category=-1;
require($_SERVER['DOCUMENT_ROOT']."/struct/main.php");
?>
<script>
function confirm_del(){
	result = confirm("Вы действительно хотите удалить этого пользователя?");
	if(result===true){
	id=document.getElementById("user").value;
	location=link+'nfu/user/del/delete.php?id='+id;
	alert("Пользователь успешно удалён");
	}
}
</script>
<?php
nfu();
$r=isModerator();
if($r!=true) 	echo "<script language='JavaScript' type='text/javascript'>location='".$link."'</script>";
//
echo "<div class='content'>";
?>
	<h1 class='text_title'>Удалить пользователя</h1><hr>
	<form class='zakaz text_title'>
	<p class='text_title'>Выберите пользователя</p><hr>
	<?php
	db_con();
	echo "<select name='user' id='user' class='input_text'>";
	$q="select id_user, login from usr";
	$r=mysql_query($q);
	//
	while($row=mysql_fetch_array($r))
		{
		echo "<option value='".$row['id_user']."'>".$row['login']."</option>";
		}
	echo "</select>";
	echo "<input class='button_3' value='Удалить' onclick='confirm_del();'>";
	?>
	</form>
<?php
echo "</div>";
require($struct.'footer.php');
?>