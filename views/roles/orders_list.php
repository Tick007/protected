<?
if (@count($models)==0) echo "<h2>Заказов нет</h2>";
else {////if1
?>

<table width="auto" border="0" cellspacing="2" cellpadding="2">
<thead>
  <tr>
    <th><span class="стиль1">№</span></td>
    <th><span class="стиль1">Дата</span></td>
    <th colspan="2"><span class="стиль1">Покупатель</span></td>
    <th><span class="стиль1">Метод оплаты</span></td>
    <th><span class="стиль1">Сумма</span></td>
    <th><span class="стиль1">Статус</span></td>
    <th><span class="стиль1">Содержание</span></td>
  </tr>
</thead>
<?
for($i=0; $i<count($models); $i++) {
?>

  <tr>
    <td>
    <?
    echo CHtml::link($models[$i]->id, array('/adminorders/','order'=>$models[$i]->id), $htmlOptions=array ('encode'=>false, 'target'=>'_blank' ) )?></td>
    <td><?=$models[$i]->recept_date;?> <?=$models[$i]->recept_time;?></td>
    <td><?=$models[$i]->client->first_name?>&nbsp;<?=$models[$i]->client->second_name?></td>
    <td><?php if (isset($models[$i]->PaymentFace)) echo $models[$i]->PaymentFace->face?></td>
    <td><?php if(isset($models[$i]->PaymentMethod)) echo $models[$i]->PaymentMethod->payment_method_name?></td>
    <td><?=$models[$i]->summa_pokupok;?></td>
    <td><?=$models[$i]->OrderStatus->description?></td>
    <td style="font-family:Arial Narrow; font-size:100%"><?=Orders::order_contents_short($models[$i]->id)?></td>
  </tr>
<?
}//for($i=0; $i<count($models); $i++) {
?>
</table>

<?
}//////else {////if1
?>