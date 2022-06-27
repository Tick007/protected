<table width="auto" border="0" cellspacing="2" cellpadding="1" background="/images/2x2.png">
  <tr>
    <th align="left" scope="col">ID Товара</th>
    <th align="left" scope="col">Артикул</th>
    <th align="left" scope="col">Номенклатура</th>
    <th align="left" scope="col">НДС товара</th>
    <th align="left" scope="col">Цена</th>
    <th align="left" scope="col">Цена без  НДС</th>
    <th align="left" scope="col">Количество</th>
    <th align="left" scope="col">Всего</th>
    <th align="left" scope="col">Всего без НДС</th>
    <th align="left" scope="col"><img src="/images/delete.gif"></th>
  </tr>
  <?
  if (count($models)>0) {
  for ($i=0; $i<count($models); $i++) {
  
  ?>
  <tr bgcolor="#FFFFFF">
    <td valign="top"><?
    echo $models[$i]->product->id;
	?></td>
    <td valign="top"><?
    echo $models[$i]->product->product_article;
	?></td>
    <td valign="top"><span style="font-family:Arial Narrow; font-size:120%"><?
    echo $models[$i]->product->product_name;
	?></span></td>
    <td valign="top"><?
    echo $models[$i]->product->nds_out;
	?></td>
    <td valign="top"><?
    //products_list_price[]
	 $pr = $models[$i]->price_no_nds*(1+($models[$i]->product->nds_out));
	  $pr = round($pr,2);
	 
	echo CHtml::textfield('products_list_price[price]['.$models[$i]->id.']', $pr,  $htmlOptions=array('encode'=>true, 'size'=>5 )  ) ;
	?></td>
    <td valign="top"><?
   echo $models[$i]->price_no_nds;
	
	?></td>
    <td valign="top"><?
    echo CHtml::textfield('products_list_price[num]['.$models[$i]->id.']', $models[$i]->num,  $htmlOptions=array('encode'=>true, 'size'=>5 )  ) ;
	?></td>
    <td valign="top"><?
    $pr_row = $pr*$models[$i]->num;
	echo round($pr_row, 2);
	?></td>
    <td valign="top"><?
    echo round($models[$i]->price_no_nds*$models[$i]->num,2);
	?></td>
    <td valign="top"><?php echo CHtml::checkBox('del_product['.$models[$i]->id.']', 0)?></td>
  </tr>
  <?
  }///////////////
  }///////if (isset($models)) {
  ?>
</table>
