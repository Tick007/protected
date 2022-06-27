<?php
$CC = new ClientCards();
//Достаем типы цен:
//echo FHtml::enumDropDownList($CC, 'type');
$card_types = FHtml::enum($CC, 'type');

$product_prices = NULL;

if(isset($product->card_prices)) {
	foreach ($product->card_prices as $pr) {
		$product_prices[$pr->cardtype] = array(
				'id'=>$pr->id,
				'price'=>$pr->price,
		);
	}	
	
	//print_r($product_prices);
	?><table border="0" cellpadding="1" cellspacing="1" bgcolor="000"><?php 
	if(is_array($card_types)){
		?><tr bgcolor="#FFFFFF"><?php 
		foreach ($card_types AS $card_type){
			?><td bgcolor="#FFFFFF"><?php echo $card_type?></td><?php 
		}
		?>
		</tr>
		<tr><?php
		foreach ($card_types AS $card_type){
			if(isset($product_prices[$card_type])) $p=$product_prices[$card_type]['price'];
			else $p = '';
		?><td bgcolor="#FFFFFF">
		<?php echo CHtml::textField('CardPrices['.$card_type.']', $p, array('size'=>'10'));?>
		</td><?php
		}?>
		</tr>
		<?php 
	}
	?></table><?php 
}
?>