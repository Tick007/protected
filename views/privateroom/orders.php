<table width="auto" border="0" cellspacing="2" cellpadding="2"> 
<tr>
    <td>С</td>
    <td><?
    $date_from = new MyDatePicker;
$date_from->conf = array(
				'name'=>'date_from_value',
    // additional javascript options for the date picker plugin
				'options'=>array(
					'showAnim'=>'fold',
				),
				'htmlOptions'=>array(
					'style'=>'height:20px;'
				),
			);
$date_from->init();
	?></td>
    <td>По</td>
    <td><?
    $date_to = new MyDatePicker;
$date_to->conf = array(
				'name'=>'date_to_value',
    // additional javascript options for the date picker plugin
				'options'=>array(
					'showAnim'=>'fold',
				),
				'htmlOptions'=>array(
					'style'=>'height:20px;'
				),
			);
$date_to->init();
	?></td>
  </tr>
</table>


<?
//print_r($orders_list);
//echo count($orders_list);

echo "Заказы с<br>";


echo "<br>по";

/*
$date_from->widget('application.components.MyDatePicker', array(
			'conf'=>array('name'=>'publishDate',
			// additional javascript options for the date picker plugin
			'options'=>array(
				'showAnim'=>'fold',
			),
			'htmlOptions'=>array(
				'style'=>'height:20px;'
			),
		)
		)
		);
		*/
?>
<table width="100%" border="0" cellspacing="2" cellpadding="2">
  <tr>
    <td>№</td>
    <td>Дата</td>
    <td>Сумма</td>
    <td>Статус</td>
    <td>Содержание</td>
    <td>Оплата</td>
  </tr>

  <?php foreach($orders_list as $n=>$order): ?>

  <tr>
    <td>
    <?
    echo CHtml::link($order->id, array('/privateroom/','details'=>$order->id), $htmlOptions=array ('encode'=>false ) )?></td>
    <td><?=$order->recept_date;?> <?=$order->recept_time;?></td>
    <td><?=$order->summa_pokupok;?></td>
    <td><?=$order->order_status;?></td>
    <td><?=Orders::order_contents_short($order->id)?></td>
    <td><?php
	if(isset($order->payments) AND count($order->payments)>0) {

		echo 'Есть оплата';
	}
    ?></td>
  </tr>
  <?php endforeach; ?>
</table>
