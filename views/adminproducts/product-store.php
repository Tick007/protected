<?
//print_r($triggers);
//echo count($triggers);
?>
<?php echo CHtml::beginForm(array('/adminproducts/update_trigers/'.$product->id.'/?group='.$group),  $method='post',$htmlOptions=array('name'=>'TrigersForm'));  ?>

<table width="345" border="0" cellspacing="1" cellpadding="1" class="plain" bgcolor="#333333">
  
    <?
    if (!@$ostatki_tovar AND isset($tovar_id)) $ostatki_tovar = $tovar_id;
	?>

      <tr bgcolor="#CCCCCC">
        <td width="54" class="list_head_left">Склад</td>
        <td width="74" class="list_head_left">&nbsp;</td>
        <td width="62" class="list_head_left">Количество</td>
        <td width="62" class="list_head_left">Цена</td>
        <td width="45" class="list_head_left">Удалить</td>
        <td width="94" class="list_head_left"><nobr>По данным учета</nobr></td>
      </tr>
    <?
    for($i=0; $i<count($triggers); $i++) {
	?>
           <tr bgcolor="#DAFEEF">
        <td><?
		//print_r($stores_list);
        echo $stores_list[$triggers[$i]->store];
		?></td>
        <td align="center">&nbsp;</td>
        <td><input name="quantity[<?=$triggers[$i]->id?>]" type="text" size="2" class="textfield" value="<?php echo $triggers[$i]->quantity?>"></td>
        <td><input name="store_price[<?=$triggers[$i]->id?>]" type="text" size="2" class="textfield" value="<?php echo $triggers[$i]->store_price?>"></td>
        <td align="center"><input name="delete_triger[<?php echo $triggers[$i]->id?>]" type="checkbox"></td>
        <td align="center"><input name="stores_default[<?=$i?>]" type="checkbox"></td>
      </tr>
      <?
      }/////for($i=0; $i<count($triggers); $i++) {
	  ?>
      <tr bgcolor="#FFFFFF">
        <td>Добавить склад</td>
        <td colspan="3" align="center">
<?
       if(isset( $stores_list) AND empty( $stores_list)==false) echo CHtml::dropDownList('store_doc', NULL, $stores_list);
		?>        </td>
        <td colspan="2" rowspan="2" align="center"><input type="submit" name="save_ostatki" value="Сохранить" class="1CButton" style="width:68; height:20"></td>
      </tr><!--
      <tr bgcolor="#FFFFFF">
        <td>Добавить все склады</td>
        <td align="center"><input name="add_all_stores" type="checkbox">&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td><input name="quantity_to_add" type="text" size="2" class="textfield" value="25"></td>
      </tr>-->

</table>
    <?php echo CHtml::endForm(); ?>

