<table width="640" border="0" class="plain" cellpadding="0" cellspacing="1" bgcolor="#003366">
    <tr> 
      <td bgcolor="#CFC7AD">С</td>
      <td bgcolor="#CFC7AD"><nobr><?
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
      <td bgcolor="#CFC7AD">По</td>
      <td bgcolor="#CFC7AD"><nobr><?
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
      <td align="center" valign="top" bgcolor="#E9E5D9">Сохранить настр.</td>
      <td align="center" valign="top" bgcolor="#E9E5D9"><input name="save_settings_as"  type="text" class="textfield" size="15" maxlength="25"  ></td>
    </tr>
    <tr> 
      <td bgcolor="#CFC7AD">Супер группа</td>
      <td colspan="3" bgcolor="#CFC7AD"><?
    echo CHtml::dropDownList('parametrs[sgroup]', $parametrs[sgroup], $sgrouplist,  array ('ajax' => array('type'=>'POST', 'url'=>CController::createUrl('/adminreports/subgroups/'), 'update'=>'#parametrs_group') ), $htmlOptions=array('encode'=>false) );
	?></td>
      <td colspan="2" rowspan="3" align="center" valign="top" bgcolor="#E9E5D9">
      <table width="150" border="0" cellspacing="0" cellpadding="0" class="plain">
	  	  <tr bgcolor="#E9E5D9">
	    <td colspan="2">Сохр. настройки </td>
	    </tr>
	   <?
	for ($i=0; $i<count($presets); $i++) {
	?>
      <tr><td>
     <? 
	echo CHtml::link($presets[$i]->title, "/adminreports/movement/".$presets[$i]->id).'<br>';
	?>
      </td><td><? 
	echo CHtml::link('Удалить', "/adminreports/movement/?delpreset=".$presets[$i]->id).'<br>';
	?></td></tr>
	  <?
      }	
	?>
      </table>      </td>
      <?
		//echo "$usluga_id<br>";
		//for ($i=0; $i<count($usluga_id); $i++) echo "$usluga_id[$i]<br>";
		?>
    </tr>
    <tr> 
      <td bgcolor="#CFC7AD">Группа&nbsp;</td>
      <td colspan="3" bgcolor="#CFC7AD"><?
echo CHtml::dropDownList('parametrs[group]', $parametrs[group], $grouplist);  ?></td>
    </tr>
    <tr> 
      <td bgcolor="#CFC7AD">Номенклатура</td>
      <td colspan="3" bgcolor="#CFC7AD">
      <input name="add_product" type="hidden" id="add_product" >
      <a href="#" onclick="{displaypopup('/nomenklatura?targetitem=add_product&targetform=report_form')}">
Подбор номенклатуры</a>
          <input name="parametrs[goodlist]" type="hidden" value="<?=$parametrs[goodlist]?>">

</td>
    </tr>
    <tr>
      <td bgcolor="#CFC7AD">Список</td>
      <td colspan="3" bgcolor="#CFC7AD"><?
	  //print_r($parametrs[goodlist]);
	  if ($parametrs[goodlist] != NULL) {
	  $usluga_id = explode('#', $parametrs[goodlist]);
	  		//print_r($usluga_id);
			echo "<ul>";
			for ($h=0; $h<count($usluga_id); $h++) {
			echo "<li>".Yii::app()->GP->getproductname($usluga_id[$h])."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img border=0 src=\"/images/delete.gif\" alt=\"Удалить из списка\"><input name=\"del_usluga[]\" type=\"checkbox\" value=\"".$usluga_id[$h]."\"><br><hr></li>";
	
		   }/////////for ($i=0; $i<count($usluga_id); $i++) 
		   echo "</ul>";
	  }//////////if ($parametrs[goodlist] != NULL) {
	   ?></td>
      <td colspan="2" align="center" bgcolor="#CFC7AD"><input id="build" name="build" type="submit" value="Вывести список" class="1CButton"></td>
    </tr>
</table>