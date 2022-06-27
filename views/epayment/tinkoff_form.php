

<h1>Оплата заказа № <?php echo $order->id?></h1>
<h2>на сумму <?php echo $order->summa_pokupok?></h2>
<h3>провайдер платежа: <a href="https://oplata.tinkoff.ru" target="_blank">Тинкофф</a></h3>

<style>.tinkoffPayRow{display:block;margin:1%;width:160px;}</style>
<!-- <script src="https://securepay.tinkoff.ru/html/payForm/js/tinkoff.js"></script>
<form name="TinkoffPayForm" onsubmit="pay(this); return false;">-->
<form name="TinkoffPayForm" action="http://smotr" method="post">
	<input class="tinkoffPayRow" type="hidden" name="terminalkey" value="<?php echo $terminalkey?>">
	<input class="tinkoffPayRow" type="hidden" name="frame" value="true">
	<input class="tinkoffPayRow" type="hidden" name="language" value="ru">
    <input class="tinkoffPayRow" type="text" placeholder="Сумма заказа" name="amount" required value="<?php echo $order->summa_pokupok?>">
    <input class="tinkoffPayRow" type="text" placeholder="Номер заказа" name="order" value="<?php echo $order->id?>">
    <input class="tinkoffPayRow" type="text" placeholder="Описание заказа" name="description" value="Заказ в интернет магазине <?php echo $_SERVER['HTTP_HOST']?>">
    <input class="tinkoffPayRow" type="text" placeholder="ФИО плательщика" name="name" value="<?php echo $fio?>">
    <input class="tinkoffPayRow" type="text" placeholder="E-mail" name="email" value="<?php echo $email?>">
    <input class="tinkoffPayRow" type="text" placeholder="Контактный телефон" name="phone" value="<?php echo $phone?>">
    <input class="tinkoffPayRow" type="submit" value="Оплатить">
</form>