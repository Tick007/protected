<?php echo CHtml::beginForm(array('/adminproducts/manage_childs/'.$product->id.'/?group='.$group.'&child_id='.$child_id),  $method='post',$htmlOptions=array('name'=>'ChildsForm'));  ?>
<table width="auto" border="0" cellspacing="2" cellpadding="1"  background="/images/2x2.png" >
  <tr bgcolor="#fffbf0">
    <td colspan="5" align="center"><strong>Список подчиненных товаров</strong></td>
  </tr>
  <tr bgcolor="#fffbf0">
    <td colspan="2" valign="top"><input name="create_new_child_tovar" type="checkbox">
      - создать новый вариант </td>
    <td colspan="3" valign="top">или id существующего &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      <input type="text" name="id_child" class="textfield" size="10"></td>
    </tr>
  <tr bgcolor="#fffbf0">
    <td colspan="5">&nbsp;</td>
  </tr>
  <tr bgcolor="#fffbf0">
    <td>ID</td>
    <td>Артикул</td>
    <td>Наименование</td>
    <td>Удалить связь</td>
    <td align="center">вкл/выкл</td>
  </tr>
  <?
 // print_r($childs);
  
if($childs!=null && count($childs)>0)for ($i=0; $i<count($childs); $i++ ) {
  ?>
  <tr bgcolor="#fffbf0">
    <td><?
	echo 	 CHtml::link($childs[$i]->id, array('/adminproducts/product/'.$childs[$i]->id.'/?group='.$group));
	?></td>
    <td><?=$childs[$i]->product_article?></td>
    <td><?
	echo CHtml::hiddenField('child_item_id['.$childs[$i]->id.']', $childs[$i]->id);
    echo 	 CHtml::link($childs[$i]->product_name.', '.$childs[$i]->attribute_value, array('/adminproducts/product/'.$product->id.'/?group='.$group.'&activetab=tab8&child_id='.$childs[$i]->id));
	?></td>
    <td align="center"><input name="delete_child_product_rel[<?=$childs[$i]->id?>]" type="checkbox" <?
			?> class="fe"></td>
    <td align="center"><input name="child_product_visible[<?=$childs[$i]->id?>]" type="checkbox" <?
			if (@$childs[$i]->product_visible) echo " checked";
			?> class="fe"></td>
  </tr>
  <?
}///////////for ($i=0; $i<count($childs); $i++ ) {
  ?>
</table>
<div align="right"><input type="submit" name="save_child_product" value="Применить" class="1CButton"></div>

<br>
<br>
<?
if ($child_id>0) { ////////////////Если выбран один издочерних товаров
?>
<table width="100%" border="0" cellspacing="1" cellpadding="1">
  <tr>
    <td align="center" valign="top">Доступные параметры основного товара</td>
    <td align="center" valign="top">Параметры дочернего товара</td>
  </tr>
  <tr>
    <td width="50%" valign="top"><table width="100%" border="0" cellspacing="2" cellpadding="2" background="/images/2x2.png">
  <tr bgcolor="#fffbf0">
    <th scope="col">Параметр</th>
    <th scope="col">Добавить подчиненному</th>
  </tr><?
   for($i=0; $i<count($parametrs_product);$i++){
   ?> <tr bgcolor="#fffbf0">
    <td><?=$parametrs_product[$i]->caract_name    ?></td>
    <td align="center"><?php echo CHtml::checkBox('add_param['.$parametrs_product[$i]->caract_id.']', 0)?></td>
  </tr>
  <?
  }
  ?>
</table></td>
    <td width="50%" valign="top">
    <table width="100%" border="0" cellspacing="2" cellpadding="1" background="/images/2x2.png">
  <tr bgcolor="#fffbf0">
    <th scope="col">Параметр</th>
    <th scope="col">Выбор значения</th>
    <th scope="col">Новое значение</th>
    <th scope="col">Удалить</th>
  </tr><?
   for($i=0; $i<count($childs_params);$i++){
   ?> <tr bgcolor="#fffbf0">
    <td><?=$childs_params[$i]->caract_name    ?></td>
    <td><?
	echo 
	$values_list = NULL;
	$para_val = NULL;
	$parametrs_values=$child_parametrs_values;
	//echo "Всего".count($parametrs_values)."<br>";
    for($k=0; $k<count($parametrs_values); $k++) {
	//echo 'знач = '.$parametrs_values->value.'<br>';
	 //$values_list[$parametrs_product[$i]->values[$k]->value_id]=$parametrs_product[$i]->values[$k]->value;
	 if ($parametrs_values[$k]->id_product == $child_id AND $parametrs_values[$k]->id_caract == $childs_params[$i]->caract_id) $para_val = $parametrs_values[$k]->value;/////////выясняем значение для текущего параметра
	 if ($parametrs_values[$k]->id_caract==$childs_params[$i]->caract_id AND @array_key_exists($parametrs_values[$k]->value_id, $values_list)==false ) $values_list[$parametrs_values[$k]->value]=$parametrs_values[$k]->value;///////////////формируем список уникальных значений для select
	}
	
	echo CHtml::dropDownList('car_val['.$childs_params[$i]->caract_id.']', $para_val , $values_list, array('style'=>'width:200px'));
	?></td>
    <td><?
    	//echo ($parametrs_product[$i]->values[0]->value);
		//print_r($para_val);
		echo CHtml::textfield('new_value['.$childs_params[$i]->caract_id.']', NULL,  $htmlOptions=array('encode'=>true, 'size'=>20 )  ) ;
	?></td>
    <td align="center"><?php echo CHtml::checkBox('del_param['.$childs_params[$i]->caract_id.']', 0)?></td>
  </tr>
  <?
  }
  ?>
</table>
    </td>
  </tr>
</table>

<?
}////////////////////
?>

<?php echo CHtml::endForm(); ?>