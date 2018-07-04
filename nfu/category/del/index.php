<?php
session_start();
$category=-1;
require($_SERVER['DOCUMENT_ROOT']."/struct/main.php");
?>
<script>
function confirm_del(){
	result = confirm("Вы действительно хотите удалить эту категорию?");
	if(result===true){
	id=document.getElementById("category").value;
		<?php echo "location='".$link."nfu/category/del/delete.php?id='+id;";?>
	alert("Категория успешно удалена");
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
	<h1 class='text_title'>Удалить категории</h1><hr>
	<p class='error text_title'>Обратите внимание, что удаляя категорию, Вы удаляете все товары связанные с этой категорией.</p>
	<form class='zakaz tovar_desc'>
	<p class='tovar_desc'>Выберите категорию</p><hr>
	<?php
	db_con();
	echo "<select name='category' id='category' class='input_text'>";
	$q="select id_category, name from category";
	$r=mysql_query($q);
	//
	while($row=mysql_fetch_array($r))
		{
		echo "<option value='".$row['id_category']."'>".$row['name']."</option>";
		}
	echo "</select>";
	echo "<input class='button_3' value='Удалить' onclick='confirm_del();'>";
	?>
</form>
<?php
echo "</div>";
require($struct.'footer.php');
?>