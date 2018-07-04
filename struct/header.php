<html>
<head>
	<?php echo "<base href='http://".$site_link."'>"; ?>
	<title>Сервисный центр. Реализация неиспользуемых товаров.</title>
	<?php
	echo "<link rel='stylesheet' type='text/css' href='http://".$site_link."style.css'>";
	?>
	<meta name='viewport' content='width=device-width,initial-scale=1'>
	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
</head>
<body>
	<div class='header'>
	<?php
	echo "<a class='logo' href='".$link."'></a>";
	echo "<a href='".$link."'><h1 class='desc'>Сервисный центр. Реализация неиспользуемых товаров.</h1></a>";
	?>
	</div>
	<div class='people'>
	<div class='auht'>
			<?php
			echo "<a class='button_track' href='".$link."track/'>Отследить заказ</a>";
			?>
	</div>
		<?php
		$status=check();
		if($status=='manager' or $status=='moderator')
		{
		require($_SERVER['DOCUMENT_ROOT']."/struct/admin_panel.php");
		}
		?>
	</div>
	<div class='main'>
		<div class='head'>
		<?php
			if($category==0) echo "<a class='head_block head_block_select' href='".$link."'>Главная</a>";
			else echo "<a class='head_block' href='".$link."'>Главная</a>";
			db_con();
			utf8();
				$q="select id_category, name from category";
				$r=mysql_query($q);
				//
				while($row=mysql_fetch_array($r))
				{
					category($row['id_category'],$row['name'],$category);
				}
				$q="";
				$r="";
				$row="";
			?>
		</div>
		<div class='head_2'>
			<?php echo "<form class='search' action='".$link."search/' method='get'>"; ?>
				<input class='input_text' name='search' placeholder='Введите товар для поиска...' type='search' required>
				<button class='button' type='submit'>Поиск</button>
			</form>
			
				<?php
				echo "<a class='bag' href='".$link."cart/'>";
				if(count($_SESSION['tovars'])=='0') echo "Корзина пуста";
				else echo "Корзина (".count($_SESSION['tovars']).")";
				echo "</a>";
				?>
		</div>