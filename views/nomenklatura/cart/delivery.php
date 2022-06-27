<div class="delivery_methods">
<?php
//print_r($delivery_methods_final);
//echo count($models);

//echo 'current = '.$current;
//echo 'cart_sum = '.$cart_sum;
$h=0;
foreach($models as $deliv_cat=>$model) {
	/*
	echo $deliv_cat;
	echo '<pre>';
	print_r($model);
	echo '</pre>';
	*/
?><div class="delivery_group">
<table width="auto" border="0" cellspacing="0" cellpadding="0" height="37" style="margin-bottom:22px">
  <tr>
    <td bgcolor="#cecd99" style="padding-left:30px; <?php
    if($h>0) echo 'color:#FFF';
	?>" ><?php echo $deliv_cat;?></td>
    <td background="/themes/casaarte/images/boxright4.png" style="background-repeat:no-repeat; width:39px;">&nbsp;</td>
  </tr>
</table>

<?php

//print_r($service_payments_final);

foreach($model as $product=>$delivery) {
	
	//print_r($delivery);
	
	 $datalist_dostavka = NULL;
	
	if(isset($product) AND isset($service_payments_final) AND isset($service_payments_final[$product])){
		foreach ($service_payments_final[$product] as $method_id=>$method_name) {
			//echo 'weqwqe = '.$method_name;
			//$deliv_rec_id = $method_id
			$deliv_rec_id = $delivery['rec'].':'.$delivery['city'].':'.$method_id.':'.$product;
			 $datalist_dostavka[$deliv_rec_id] = $method_name;
			 
		}
			
		//	$deliv_rec_id=

?>

<div class="deliverymethod">
<div class="deliveryanme"><strong><?php
//echo CHtml::link($delivery['service'], array('cart/info', 'id'=>$delivery['product_id']), array('target'=>'_blank'));
echo CHtml::link($delivery['service'], '#', array('class'=>'deliverynamelink'));
?></strong><span class="delivprice">
<?php
if(trim($delivery['prices']['freelimitcash']) AND $cart_sum>=$delivery['prices']['freelimitcash']) {
	echo 'БЕСПЛАТНО';
}
else echo $delivery['prices']['price'].' рублей'?> 
</span>
<?php

?>

</div><div class="deliverymethodoptions" <?php
if(($delivery['category_id'] == Yii::app()->params['delivery_groups'][1] AND $current==$deliv_rec_id ) OR (count($models)==1)) echo 'style="display:block"';
elseif($delivery['category_id'] == Yii::app()->params['delivery_groups'][0])  echo 'style="display:block"'; 
?>> <span><?php
echo $delivery['descr'];
?></span><br>

<?php


//echo CHtml::radioButtonList('Cart[delivery_method]', $model->delivery_method>0?$model->delivery_method:NULL, $datalist_dostavka);
echo CHtml::radioButtonList('CartNew[delivery]', $current, $datalist_dostavka, array('class'=>'delivmethodradio', 'rel'=>(trim($delivery['prices']['freelimitcash']) AND $cart_sum>=$delivery['prices']['freelimitcash'])?0:$delivery['prices']['price']));
?>
<br>
<?php
if(isset($delivery['html']) AND trim($delivery['html'])!='') {
	// echo $delivery['html'];?>
<br>
    <iframe width="470" height="100" src="<?php echo Yii::app()->createUrl('cart/infowidget', array('id'=>$product))?>"></iframe>
    <?php
}

?>
</div>
</div>

<?php

	}/////////if(isset($product) AND isset($delivery_methods_final) AND isset($delivery_methods_final[$product])){
}
?>
</div>
<?php
$h++;

}

?>
</div>
<br style="clear:both">
<script>

$( document ).ready(function() {
	///////////Смотрим, есть ли активная опция, если да, то смотрим её значение
	//var checked_site_radio = $('input:radio[name=CartNew[delivery]]:checked').attr('rel');
	var checked_site_radio = $('input:radio[class=delivmethodradio]:checked').attr('rel');
	if(typeof(checked_site_radio)!='undefined') $('#summa_dostavki').val(checked_site_radio).trigger('change'); 
});



$('.delivmethodradio').bind( "click",function() { ////////////////Выставляем на головной форме цену доставки
	 $('#summa_dostavki').val($(this).attr("rel")).trigger('change') ;
});

$('.deliverynamelink').bind( "click",function() {
event.preventDefault();
radios = $(this).parents('.deliverymethod').children('.deliverymethodoptions');
$(radios).toggle();

});
</script>