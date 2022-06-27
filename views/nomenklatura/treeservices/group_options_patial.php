Список опций выбранной группы
<?php
////////////////Список категорий для группы в админке в свойствах группы
//print_r($models);


$str = "<table width=\"100%\" border=\"1\" cellspacing=\"1\" cellpadding=\"1\" class=\"group_options\">
  <tr>
    <th scope=\"col\" width=\"30%\">Опция</th>
	<th scope=\"col\" width=\"30%\">Группы</th>
    <th scope=\"col\">Описание</th>
    <th scope=\"col\">Добавить";
        $str.= CHtml::link('<img src="/images/checkbox_no.png">', '#', array('onclick'=>"{ $('.group_options input:checked').removeAttr('checked')}"));
		 $str.= '&nbsp;';
		 $str.= CHtml::link('<img src="/images/checkbox_yes.png">', '#', array('onclick'=>"{ $('.group_options input:not(:checked)').attr('checked', true)}"));
	
	$str.= "</th>
  </tr>";
  for ($i=0; $i<count($models); $i++) {
  $str.="<tr>
    <td valign=\"top\"><strong>".FHtml::mb_ucfirst($models[$i]->caract_name).'</strong> ('.$models[$i]->caract_id.")</td>
	<td valign=\"top\">";
	if (isset($models[$i]->characteristics_categories)) {
		 $str.='<ul>';
		for($k=0; $k<count($models[$i]->characteristics_categories); $k++) {
			 
			if(isset($models[$i]->characteristics_categories[$k]->categories_id) AND isset($categories[$models[$i]->characteristics_categories[$k]->categories_id])) $str.= '<li>';
$str.=$categories[$models[$i]->characteristics_categories[$k]->categories_id];
if(trim($models[$i]->characteristics_categories[$k]->char_descr)!='') $str.= '<strong><em>(Описание)</strong></em>';
$str.='</li>';
		}
		 $str.='</ul>';
	}
   $str.="</td><td valign=\"top\" style=\"font-size:10px\">".$models[$i]->char_descr."</td>
    <td valign=\"middle\" align=\"center\">".CHtml::checkbox('add_existing_characteristic['.$category_id.']['.$models[$i]->caract_id.']')."</td>
  </tr>";
  }
$str.="</table>";
//$str.="<div align=\"center\">".CHtml::submitButton('save_gr_params', $htmlOptions=array ('name'=>'Сохранить' , 'alt'=>'Сохранить', 'value'=>'Сохранить', 'title'=>'Сохранить'))."</div>";


echo $str;
?><div align="center"><?php
		echo CHtml::submitButton('Добавить выбранные опции', $htmlOptions=array ('name'=>'apply_group_params' , 'alt'=>'Применить', 'value'=>'Применить', 'title'=>'Применить'));
?>
</div>