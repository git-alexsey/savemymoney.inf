</div>
<div class='footer'>
		<div class='people'></div>
		<div class='footer_content'>
		
		<div class='left footer_float'><?
		echo "<a class='footer_block' href='".$link."'>Главная</a>";
		echo "<a class='footer_block' href='".$link."#contacts'>Контакты</a>";
		echo "<a class='footer_block' href='".$link."search/'>Поиск</a>";?>
		</div>
		<p class='text_title inline footer_text'>ООО "Сервисный центр" 2017</p>
		<div class='right footer_float'><?
		$r=isManager();
		if($r==true)echo "<a class='footer_block mobile_visible' href='".$link."nfu/panel/'>Панель администратора</a>";
		echo "<a class='footer_block' href='".$link."track/'>Отслеживание заказа</a>";
		echo "<a class='footer_block' href='".$link."cart/'>Корзина</a>";?>
		</div>
		
		</div>
	</div>
</body>
</html>