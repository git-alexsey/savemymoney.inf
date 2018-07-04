<?php
session_start();
$category=-1;
require($_SERVER['DOCUMENT_ROOT']."/struct/main.php");
?>
<div class='content'>
	<div class='tovar_desc'>
	<h1>Запрашиваемая страница не найдена.</h1>
	<p>Проверьте правильность написания ссылки.</p>
	<?php 
	echo "<img src='".$link."/error404/404.jpg'></img>";
	?>
	</div>
</div>
<?php
require($struct.'footer.php');
?>