<style type="text/css">
<!--
.стиль1 {color: #FFFFFF}
-->
</style>
<div id="ribbon" style="margin-left:71px">
&nbsp;<strong>Документы по складу</strong>
</div>
<div id="Right_column" style="background-color:#666E73; width:60px; margin-left:0px; float:left">
<?
$RC = new RightColumnAdmin;
?>
</div>
<div id="mainContent" style="padding-left:3px; margin-left:70px ">
<table width="auto" border="0" cellspacing="2" cellpadding="2"> 
<?
echo CHtml::beginForm(array('/admindocs/list/'),  $method='post',$htmlOptions=array('name'=>'listparams'));  
?>
<tr>
  <td>Интервал отображения</td>
  <td>&nbsp;</td>
    <td>С</td>
    <td bgcolor="#CCCCCC"><?
    $date_from = new MyDatePicker;
$date_from->conf = array(
				'name'=>'date_from_value',
				'value'=>$date_from_value,
    // additional javascript options for the date picker plugin
				'options'=>array(
					'showAnim'=>'fold',
				),
				'htmlOptions'=>array(
					'style'=>'height:18px; padding:1px; border:0px'
				),
			);
$date_from->init();
	?> *</td>
    <td>По</td>
    <td bgcolor="#CCCCCC"><?
    $date_to = new MyDatePicker;
$date_to->conf = array(
				'name'=>'date_to_value',
				'value'=>$date_to_value,
    // additional javascript options for the date picker plugin
				'options'=>array(
					'showAnim'=>'fold',
				),
				'htmlOptions'=>array(
					'style'=>'height:18px; padding:1px; border:0px'
				),
			);
$date_to->init();
	?> *</td>
    <td align="right">Создать новый -</td>
    <td><?php echo CHtml::checkBox('create_price', 0)?></td>
    <td>&nbsp;</td>
    <td><?
    echo CHtml::submitButton('Применить', $htmlOptions=array ('name'=>'prices_period' , 'alt'=>'Вывести', 'title'=>'Вывести'));
	?></td>
</tr>
<tr>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>* клик для вызова календаря</td>
  <td>&nbsp;</td>
  <td>* клик для вызова календаря</td>
  <td align="center"><?
  
    echo CHtml::dropDownList('new_doc_type', NULL, $doc_type_list, $htmlOptions=array('encode'=>false, ) );
	?></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
</tr>
<?php echo CHtml::endForm(); ?>
</table>
<br>
<?php $this->widget('CLinkPager',array('pages'=>$pages)); ?><br><br>
<table width="100%" border="0" cellspacing="1" cellpadding="1">
  <tr bgcolor="#666E73">
    <th align="left" scope="col"><span class="стиль1">Номер</span></th>
    <th align="left" scope="col"><span class="стиль1">Заказ</span></th>
    <th align="left" scope="col"><span class="стиль1">Тип</span></th>
    <th align="left" scope="col"><span class="стиль1">Дата</span></th>
    <th align="left" scope="col"><span class="стиль1">Автор</span></th>
    <th align="left" scope="col"><span class="стиль1">Контрагент</span></th>
    <th align="left" scope="col"><span class="стиль1">Склад кредит</span></th>
    <th align="left" scope="col"><span class="стиль1">Склад дебет</span></th>
    <th align="left" scope="col"><span class="стиль1">Сумма</span></th>
    <th align="left" scope="col"><span class="стиль1">Статус</span></th>
  </tr>
  <?
  for ($i=0; $i<count($models); $i++) {
  ?>
  <tr>
    <td><?=CHtml::link($models[$i]->id, array('/admindocs/', 'doc'=>$models[$i]->id), $htmlOptions=array ('encode'=>false) )?></td>
    <td><?php
    if ( trim($models[$i]->order_id)!='') echo CHtml::link($models[$i]->order_id, array('adminorders/order', 'id'=>$models[$i]->order_id));
	?></td>
    <td><?
    echo $models[$i]->doctype->type;
	?></td>
    <td><?
    echo $models[$i]->date_dt;
	?></td>
    <td>&nbsp;</td>
    <td><?
    echo $models[$i]->kontragent->name;
	?></td>
    <td><?
    echo $models[$i]->store->name;
	?></td>
    <td><?
    echo $models[$i]->store_ca->name;
	?></td>
    <td>&nbsp;</td>
    <td><?
    if (@$models[$i]->doc_status==2) echo "<img src=\"/images/apply.png\">";
	else  echo "<img src=\"/images/stop.png\">";
	?></td>
  </tr>
  <?
  }///////////////
  ?>
</table>
<?php $this->widget('CLinkPager',array('pages'=>$pages)); ?>
</div><!--<div id="mainContent" style="padding-left:3px; ">-->


<div style="height: 5px; clear:both">&nbsp;</div>

