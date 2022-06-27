
<table width="640" border="0" class="plain" cellpadding="0" cellspacing="1" bgcolor="#003399">
    <tr bgcolor="#E9E5D9"> 
      <td bgcolor="#E9E5D9">С</td>
      <td><nobr><?
    $date_from = new MyDatePicker;
$date_from->conf = array(
				'name'=>'parametrs[date_from_value]',
				//'value'=>Yii::app()->getRequest()->getParam('date_from_value', NULL),
				'value'=>(string)$parametrs[date_from_value],
    // additional javascript options for the date picker plugin
				'options'=>array(
					'showAnim'=>'fold',
				),
				'htmlOptions'=>array(
					'style'=>'height:18px; padding:1px; border:0px'
				),
			);
$date_from->init();
	?> *</nobr></td>
      <td>По</td>
      <td><nobr><?
    $date_to = new MyDatePicker;
$date_to->conf = array(
				'name'=>'parametrs[date_to_value]',
				//'value'=>Yii::app()->getRequest()->getParam('date_to_value', NULL),
				'value'=>(string)$parametrs[date_to_value],
    // additional javascript options for the date picker plugin
				'options'=>array(
					'showAnim'=>'fold',
				),
				'htmlOptions'=>array(
					'style'=>'height:18px; padding:1px; border:0px'
				),
			);
$date_to->init();
	?> *</nobr></td>
      <td align="right">Склад</td>
      <td><?
    echo CHtml::dropDownList('parametrs[store_id]', $parametrs[store_id], $stores_list);
	?></td>
      <td><input  type="submit" value="Вывести список" class="1CButton" name="build"></td>
    </tr>
    <tr bgcolor="#E9E5D9">
      <td>СГруппа</td>
      <td><?
    echo CHtml::dropDownList('parametrs[sgroup]', $parametrs[sgroup], $sgrouplist,  array ('ajax' => array('type'=>'POST', 'url'=>CController::createUrl('/adminreports/subgroups/'), 'update'=>'#parametrs_group') ), $htmlOptions=array('encode'=>false) );
	?></td>
      <td colspan="2" align="right">Группа&nbsp;</td>
      <td bgcolor="#E9E5D9"><?
echo CHtml::dropDownList('parametrs[group]', $parametrs[group], $grouplist);  ?>      </td>
      <td align="right"><nobr>Сохранить настройки</nobr></td>
      <td align="center"><input name="save_settings_as"  type="text"  size="15" maxlength="25"  ></td>
    </tr>
    <tr>
      <td bgcolor="#CFC7AD">Отборы </td>
      <td valign="middle" bgcolor="#CFC7AD"><input type="checkbox" name="parametrs[enable_suplier]"  <?
	  if (@$parametrs[enable_suplier]==1 OR @$parametrs[enable_suplier]=='on') echo " checked";
	  ?> />
Поставщик</td>
      <td colspan="2" valign="middle" bgcolor="#CFC7AD"><input type="checkbox" name="parametrs[detailed]"  <?
	  if (@$parametrs[detailed]==1 OR @$parametrs[detailed]=='on') echo " checked";
	  ?> />
Партии </td>
      <td colspan="2" valign="middle" bgcolor="#CFC7AD"><input type="checkbox" name="parametrs[not_nulls]"  <?
	  if (@$not_nulls==1) echo " checked";
	  ?>>
        Не нули (для партий)</td>
      <td rowspan="2" valign="top" bgcolor="#FFFFFF">
      
      
        <table width="150" border="0" cellspacing="0" cellpadding="0" class="plain">
	<tr bgcolor="#E9E5D9">
	  <td colspan="2">Сохр. настройки <br>      </td>
	  </tr>
       <?
	for ($i=0; $i<count($presets); $i++) {
	?>
      <tr><td>
     <? 
	echo CHtml::link($presets[$i]->title, "/adminreports/sales/".$presets[$i]->id).'<br>';
	?>
      </td><td><? 
	echo CHtml::link('Удалить', "/adminreports/sales/?delpreset=".$presets[$i]->id).'<br>';
	?></td></tr>
	  <?
      }	
	?>
	 </table>		  </td>
    </tr>
    
    
    <tr>
      <td bgcolor="#E9E5D9">Сортировка</td>
      <td colspan="3" bgcolor="#E9E5D9"><input name="parametrs[sort_order]" type="radio" value="6" <?
	if (@$parametrs[sort_order]==6) echo " checked";
	?> />
Поставщик</td>
      <td colspan="2" bgcolor="#E9E5D9"><input name="parametrs[sort_order]" type="radio" value="0" <?
	if (!@$parametrs[sort_order]) echo " checked";
	?>>
      Группы/СГруппы      </td>
      </tr>
</table>
