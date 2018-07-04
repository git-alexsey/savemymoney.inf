<?php
session_start();
$category=-1;
require($_SERVER['DOCUMENT_ROOT']."/struct/main.php");
?>
<div class='content'>
	<div class='tovar_desc'>
	<h1>Спасибо за Заказ</h1>
	<hr>
	<p>В ближайшее время с Вами свяжется менеджер для подтверждения заказа.</p>
	<p>Вы всегда можете следить за статусом Вашего заказа через 
	<?php
	echo "<a href='".$link."track/' class='inline'>Отслеживание заказа</a>";
	?>
	.</p>
	</div>
</div>
<?php
require($struct.'footer.php');
?>