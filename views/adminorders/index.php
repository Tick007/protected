<div id="ribbon" style="margin-left:71px"><?
$this->widget('zii.widgets.CBreadcrumbs', array(
    'links'=>array(
        'Администрирование',
		'Заказы'=>'/adminorders',
   ),
));
?>
</div>

<div id="Right_column" style="background-color:#666E73; width:60px; margin-left:0px">
<?
$RC = new RightColumnAdmin;
?>
</div>
<div id="mainContent" style="padding-left:3px; margin-left:70px ">
<?
echo CHtml::beginForm(array('/adminorders/index/'),  $method='post',$htmlOptions=array('name'=>'listparams'));  
?>
<table width="auto" border="0" cellspacing="2" cellpadding="2"> 
<tr>
    <td>С</td>
    <td><?
    $date_from = new MyDatePicker;
$date_from->conf = array(
				'name'=>'date_from_value',
    // additional javascript options for the date picker plugin
				'value'=>$date_from_value,
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
			'value'=>$date_to_value,
				'options'=>array(
					'showAnim'=>'fold',
				),
				'htmlOptions'=>array(
					'style'=>'height:20px;'
				),
			);
$date_to->init();
	?></td>
    <td>&nbsp;</td>
    <td><?
    echo CHtml::submitButton('Применить', $htmlOptions=array ('name'=>'prices_period' , 'alt'=>'Вывести', 'title'=>'Вывести'));
	?></td>
</tr>
</table>
<?php echo CHtml::endForm(); ?>

<?php $this->widget('CLinkPager',array('pages'=>$pages)); ?>

<table width="100%" border="0" cellspacing="1" cellpadding="1" bgcolor="#333333">
  <thead>
  <tr bgcolor="#666E73">
    <th width="10%">Дата</td>
    <th width="5%">Сайт</td>
    <th width="2%">№</td>
    <th colspan="2" width="23.5%">Покупатель</td>
    <th width="14%">Метод оплаты</td>
    <th>Сумма</td>
    <th>Статус</td>
    <th width="35%">Содержание</td>
  </tr></thead>
</table>
<?
	foreach ($new_models as $recept_date => $models) {?>
  <div>
    <div style="float:left; cursor:pointer; width:10%" id="date_<?php echo $recept_date?>" onclick="{
    $(this).next().children('table').toggle();
    }">  <?php echo $recept_date?> (<?php echo count($models)?>)</div>
    <div style="float:left; width:90%">
    	 <table width="100%" border="0" cellspacing="1" cellpadding="1" bgcolor="#333333" style="display:none">
   <?php
    for($i=0; $i<count($models); $i++){?>
      <tr bgcolor="#FFFFFF">
     <td width="5%"><?php echo $models[$i]->recept_time;?>&nbsp;</td>
      <td width="5%"><?php if(isset($models[$i]->host)) echo $models[$i]->host->name?></td>
      <td width="2%">   
    <?
    echo CHtml::link($models[$i]->id, array('/adminorders/','order'=>$models[$i]->id), $htmlOptions=array ('encode'=>false, 'target'=>'_blank' ) )?>
    </td>
    <td width="16%"><?php 
	$name = $models[$i]->client->first_name.' '.$models[$i]->client->second_name;
	echo CHtml::link($name, array('roles/details', 'id'=>$models[$i]->client->id), array('target'=>'_blank'))
	?></td>
    <td width="10.5%"><?php if(isset($models[$i]->PaymentFace->face)) echo $models[$i]->PaymentFace->face;
	else echo 'неопределенно';
	?></td>
    <td width="15.5%"><?php if(isset($models[$i]->PaymentMethod)) echo $models[$i]->PaymentMethod->payment_method_name?></td>
    <td><?=$models[$i]->summa_pokupok;?></td>
    <td><?php echo $models[$i]->OrderStatus->description?></td>
    <td width="39%" style="font-family:Arial Narrow; font-size:100%"><?php echo Orders::order_contents_short($models[$i]->id)?></td>
  </tr>
<?
}//for($i=0; $i<count($models); $i++) {
	?>
	</table>&nbsp;
    </div>
    </div>
    
	<?php
}////////foreach
?>

<?php $this->widget('CLinkPager',array('pages'=>$pages)); ?>

</div><!--<div id="mainContent" style="padding-left:3px; ">-->


<div style="height: 5px; clear:both">&nbsp;</div>