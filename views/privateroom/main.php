<div id="container">
<div id="ribbon">Личный кабинет</div>
   <div id="Right_column">
<?
	//$RC = new RightColumn(5, 'L');
	?>
</div>
 <div id="mainContent">
<?
if (isset($_POST['saveclient'])){
	echo CHtml::errorSummary($model); 
	$error_content = CHtml::errorSummary($model);
	if(trim($error_content)==''){
		?>
		<div class="updateSummary">
		Успешно сохранено
		</div>
		<?php 
	}
}



?>

<br>
<?
echo CHtml::beginForm(array('/privateroom'),  $method='post', $htmlOptions=array('name'=>'Room', 'id'=>'Room'));  


	if (isset($order_id)) $tab3 = array(
          'title'=>'Содержание заказа',
		  'view'=>'order_content',
          'data'=>array('OrderUnit'=>$Order, 'ClientUnit'=>$model) );
		else $tab3 = array(
          'title'=>'Содержание заказа',
		  'content'=>'Выбирете заказ') ;
		  
	$tab = new CTabView;
	$tab->tabs=array(
	'tab1'=>array(
		  'title'=>'Данные пользователя',
          'view'=>'client',
          'data'=>array('model'=>$model, 'form'=>$form) 
	),
    'tab2'=>array(
          'title'=>'Заказы',
          'view'=>'orders',
          'data'=>array('orders_list'=>$orders_list)  ),
	 'tab3'=>$tab3,
	'tab4'=>array(
		  'title'=>'Клуб PSG',
          'view'=>'club',
          'data'=>array('model'=>$model)
	),

	 );	  
	 
	if (isset($order_id))  $tab->activeTab  ='tab3';
	$tab->run();
?>

<?php echo CHtml::endForm(); ?>
</div>

<div style="height: 5px; clear:both">&nbsp;</div>