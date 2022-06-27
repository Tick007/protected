<?php
//print_r($products);

?>
<table width="100%" border="0" cellspacing="1" cellpadding="1" bgcolor="#333333" class="regionproducts">
  <tr bgcolor="#FFFFFF">
    <td>&nbsp;</td>
    <td style="font-size:10px" width="200">Сумма бесплатной доставки при оплате электронным платежом при оформлении заказа на сумму более:</td>
    <td style="font-size:10px" width="200">Сумма бесплатной доставки при оплате наличными денежными средствами и безналичным расчетом 
 при получении заказа на сумму более :</td>
    <?php
    for($i=0, $c=count($products); $i<$c; $i++) {
	?>
    <td><?php echo '<strong>'.$products[$i]->product_name.'</strong>';
	echo '<br><div class="servicedescr">'.$products[$i]->product_short_descr.'</div>';
	?></td>
    <?php
	}
	?>
  </tr>
  <?php
  for($k=0, $cc=count($cities); $k<$cc; $k++) {
  ?>
  <tr bgcolor="#FFFFFF">
    <td><?php
    echo $cities[$k]->name;
	?></td>
    <td><?php
    if(isset($prices[$cities[$k]->id][0])) $freelimitcash = $prices[$cities[$k]->id][0]['freelimitcash'];
	else $freelimitcash = 3000;
	echo CHtml::textfield('freelimitcash['.$cities[$k]->id.'][0]', $freelimitcash, array('class'=>'freelimitcash'));
	?></td>
    <td><?php
    if(isset($prices[$cities[$k]->id][0])) $freelimitepay = $prices[$cities[$k]->id][0]['freelimitepay'];
	else $freelimitepay = NULL;
//	echo CHtml::textfield('freelimitepay['.$cities[$k]->id.'][0]', $freelimitepay, array('class'=>'freelimitepay'));
	?></td>
    <?php
    for($i=0; $i<$c; $i++) {
	?>
    <td> <?php
    if(isset($prices[$cities[$k]->id][$products[$i]->id])) $eprice = $prices[$cities[$k]->id][$products[$i]->id]['eprice'];
	else $eprice = NULL;
	echo CHtml::textfield('delivery_eprice['.$cities[$k]->id.']['.$products[$i]->id.']', $eprice, array('class'=>'deliveryeprice', 'placeholder'=>'безнал'));
	?> / <?php
    if(isset($prices[$cities[$k]->id][$products[$i]->id])) $price = $prices[$cities[$k]->id][$products[$i]->id]['price'];
	else $price = NULL;
	echo CHtml::textfield('delivery_price['.$cities[$k]->id.']['.$products[$i]->id.']', $price, array('class'=>'deliveryprice', 'placeholder'=>'нал'));
	?>
   
    </td>
    <?php
	}
	?>
  </tr>
  <?php
  }
  ?>
</table>

