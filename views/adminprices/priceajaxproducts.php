<strong>Список позиций в категории в прайслисте:</strong><br>
<?php
echo '<pre>';
//print_r($models);
echo '</pre>';
?>

<table class="cat_content_table"><thead> 
	<tr>
        <th rowspan="2">№</th>
        <th rowspan="2" align="left">Артикул</th>
      <th rowspan="2" align="left">Товар</th>
      <th colspan="2">цена</th>
        <th colspan="2">остаток</th>
    </tr>
	<tr>
	  <th>текущая</th>
	  <th>новая</th>
	  <th>текущий</th>
	  <th>новый</th>
    </tr>
</thead>
	<tbody class="rules">
    <?php
    if(empty($models)==false) for($i=0; $i<count($models); $i++){
	?>
	<tr>
    	<td><?php echo $i+1 ?></td>
    	<td align="left" class="max_narrow_300"><?php echo $models[$i]->product->product_article?><br>(<?php echo $models[$i]->product_id?>)</td>
   	  <td align="left" class="max_narrow_300"><?php echo $models[$i]->product->product_name?></td>
      <td><?php
        if(isset($ostatki[$models[$i]->product_id])) {
				 echo $ostatki[$models[$i]->product_id]->store_price;
			}
		?></td>
        <td class="max_narrow_300"><?php echo CHtml::textfield('price_with_nds['.$models[$i]->id.']', $models[$i]->price_with_nds, array('size'=>2, 'class'=>'adjustableprice'))?></td>
        <td><?php
        if(isset($ostatki[$models[$i]->product_id])) {
				 echo $ostatki[$models[$i]->product_id]->quantity;
			}
		?></td>
        <td><?php echo $models[$i]->store?></td>
    </tr>
	<?php
	}
	?>
    </tbody>
</table>
<br><br>