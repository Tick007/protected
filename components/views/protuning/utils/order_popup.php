<?php 
//print_r($model->getAttributes());
if(isset($model->belongs_order) && $model->belongs_order!=null){
	//print_r($model->belongs_order->getAttributes());
	if(isset($model->belongs_order->client) AND $model->belongs_order->client!=NULL ) {
	//print_r($model->belongs_order->client->getAttributes());

?>
<div class="popup_zakaz">
	<div class="plashka">
		<div class="text">
			<span id="cudtomer_name"><?php echo $model->belongs_order->client->first_name?>&nbsp;
			<?php echo $model->belongs_order->client->second_name?></span> заказал <span id="popupprod"><?php echo $model->contents_name?></span>
		</div>
	</div>
</div>
<?php 
}
}
?>

