<?php echo CHtml::beginForm(array('adminproducts/product_update_charact', 'id'=>$product->id, 'group'=>$group,  'char_filter'=>Yii::app()->getRequest()->getParam('char_filter'), 'activetab'=>'tab5'),  $method='post',$htmlOptions=array('name'=>'MainParams'));  
//echo count($parametrs);
//echo count($parametrs_values);
$pr_id = Yii::app()->getRequest()->getParam('id');
 $time1=microtime(true);
?>
<table width="auto" border="0" cellspacing="1" cellpadding="1">
  <tr>
    <td valign="top">Добавитьпараметр к товару</td>
    <td valign="top">Список параметров и значений товара</td>
  </tr>
  <tr>
    <td valign="top"><?
    for($i=0; $i<count($parametrs);$i++) {
	$list_parametrs[$parametrs[$i]->caract_id]=$parametrs[$i]->caract_name;
	}////// for($i=0; $i<count($parametrs);$i++) {
	if(count($parametrs)==0) echo 'Нет характеристик'; 
	else echo CHtml::dropDownList('add_char[]', 0, $list_parametrs, array('size'=>10, 'multiple'=>true, 'style'=>'width:200px'));
	
	?></td>
    <td valign="top"><table width="100%" border="0" cellspacing="1" cellpadding="1">
  <tr>
    <th scope="col">Параметр</th>
    <th scope="col">Выбор значения</th>
    <th scope="col">Новое значение</th>
    <th scope="col">Удалить</th>
  </tr><?
   for($i=0; $i<count($parametrs_product);$i++){
   ?> <tr>
    <td><?=$parametrs_product[$i]->caract_name    ?></td>
    <td><?
	echo 
	$values_list = NULL;
	$para_val = NULL;
    for($k=0; $k<count($parametrs_values); $k++) {
	 //$values_list[$parametrs_product[$i]->values[$k]->value_id]=$parametrs_product[$i]->values[$k]->value;
	 if ($parametrs_values[$k]->id_product == $pr_id AND $parametrs_values[$k]->id_caract == $parametrs_product[$i]->caract_id) $para_val = $parametrs_values[$k]->value;/////////выясняем значение для текущего параметра
	// if ($parametrs_values[$k]->id_caract==$parametrs_product[$i]->caract_id AND @array_key_exists($parametrs_values[$k]->value_id, $values_list)==false )
	$val = htmlspecialchars($parametrs_values[$k]->value);
	if( $parametrs_values[$k]->id_caract==$parametrs_product[$i]->caract_id AND   isset($values_list[$val])==false)  $values_list[$val]=$val;///////////////формируем список уникальных значений для select
	}
	
 //if(isset($values_list) AND  count($values_list)<51)	

 echo CHtml::dropDownList('car_val['.$parametrs_product[$i]->caract_id.']', htmlspecialchars($para_val) , $values_list, array('style'=>'width:200px'));
//  $time2=microtime(true);
//  echo ( $time1- $time2).'<br>';
	?></td>
    <td><?
    	//echo ($parametrs_product[$i]->values[0]->value);
		//print_r($para_val);
		echo CHtml::textfield('new_value['.$parametrs_product[$i]->caract_id.']', NULL,  $htmlOptions=array('encode'=>true, 'size'=>20 )  ) ;
	?></td>
    <td align="center"><?php echo CHtml::checkBox('del_param['.$parametrs_product[$i]->caract_id.']', 0)?></td>
  </tr>
  <?
  }
  ?>
</table></td>
  </tr>
  <tr>
    <td valign="top">
    <?php echo CHtml::checkBox('add_params', 0)?>
    <br>Для добавления параметров в <br>
      список выделите 1 или несколько</td>
    <td valign="top">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" align="center" valign="top"><?
      echo CHtml::submitButton('Сохранить', $htmlOptions=array ('name'=>'save_characteristics' , 'alt'=>'Сохранить', 'title'=>'Сохранить'));
	?></td>
  </tr>
</table>
<?php echo CHtml::endForm(); ?>