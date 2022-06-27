<div id="Right_column" style="margin-left:0px;">
    <?
    $LC = new RightColumn(3, 'L');
	?>
</div>

<div id="mainContent" style="padding-left:3px;">
<?
if (isset($model) AND is_numeric($model->created_order) ){///////////Пишем сообщение что создан заказ .....
echo "<div class=\"order_success\">Успешно создан заказ № ".$model->created_order;
echo '.<br>'; ?>
В ближайшее время вы получите уведомление на свой <span style="color:#00F">email</span> или<br> вам перезвонит сотрудник компании.</div> 
<?
}
?><br><h2>Корзина пуста</h2>
</div>


