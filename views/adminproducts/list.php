
<?php

Yii::app()->clientScript->scriptMap=array(
       // 'jquery.js'=>false,
	  'jquery.js'=>'/js/jquery-1.6.1.js',
	  'jquery.min.js'=>'/js/jquery-1.6.1.min.js',
);

?>

<script>
$(document).ready(function(){
//$("#faq-answer").animate({ opacity: "hide" }, "fast");
});

function collapse(el) {
//$(el).animate({ opacity: "hide" }, "slow");
//$(el).hide();
document.getElementById('group_params').style.display='none';
}

function expand(el) {
//alert('click');
//$(el).show();
document.getElementById('group_params').style.display='';
}

</script>
<div id="ribbon">&nbsp;<?
echo @$path_text;
?>
</div>
<div id="Right_column" style="background-color:#666E73; width:60px; margin-left:0px; float:left">
<?
$RC = new RightColumnAdmin;
?>
</div>
<div id="mainContent" style="padding-left:3px; margin-left:70px ">

<div width="100%">

<div style="background-color:#F0F0F0; float:left;  <?
if (count($gruppy)==0) echo "display:none";
else echo "width:300px;";
?>"  id="left_col"><!--Рисуем список групп-->
<?php echo CHtml::beginForm(array('/adminproducts/updategrouplist/', 'id'=>Yii::app()->getRequest()->getParam('id')),  $method='post',$htmlOptions=array('name'=>'EditPage'));  ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <th colspan="2" scope="col">Группа</th>
    <th scope="col">сорт</th>
    <th scope="col">вкл/выкл</th>
  </tr>
<?
for ($i=0; $i<count($gruppy); $i++) {
?>
  <tr>
    <td><img src="<?=Yii::app()->request->baseUrl?>/images/folder.png" border="0" /></td>
    <td><?=CHtml::link($gruppy[$i]->category_name.'->', array('/adminproducts/', 'group'=>$gruppy[$i]->category_id), $htmlOptions=array ('encode'=>false, 'title'=>'Товары' ) )?></td>
    <td><?php echo CHtml::textfield('sort_category['.$gruppy[$i]->category_id.']', $gruppy[$i]->sort_category,  $htmlOptions=array('encode'=>true, 'size'=>2, 'style'=>"font-family:Tahoma" )  ) ?></td>
    <td align="center"><?php echo CHtml::checkBox('show_category['.$gruppy[$i]->category_id.']', $gruppy[$i]->show_category)?></td>
  </tr>
<?
}
?>
  <tr>
    <td colspan="4">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="4"><?php echo CHtml::checkBox('new_main_category', 0)?> - новая группа</td>
  </tr>
  <tr>
    <td colspan="4" align="center"><?
      echo CHtml::submitButton('Сохранить', $htmlOptions=array ('name'=>'save_dir_list' , 'alt'=>'Сохранить', 'title'=>'Закрыть не сохраняя'));
	?></td>
    </tr>
</table>
<?php echo CHtml::endForm(); ?>
</div>
<div <?
if (count($gruppy)>0) echo "style=\"margin-left:300px\"";
?>>
<div style="background-color:#B7D9FF; cursor:pointer">Параметры категории:&nbsp;<a
					title="развернуть" onclick="expand('#group_params')">развернуть</a>
				<br>
	<div id="group_params" <?php
				$save_gr_params =Yii::app()->getRequest()->getParam('save_gr_params');
				?> style="<?php
                $open_params= Yii::app()->getRequest()->getParam('open_params');
				//if (isset($open_params)) echo $open_params;
                    if (isset($open_params)==false) echo "display: none";
                    ?>; cursor: default; width: auto">
					<div>
						<a style="cursor: pointer" title="Свернуть"
							onclick="collapse('#group_params')">скрыть</a>
					</div>
<?
//print_r($all_groups);
//echo $gruppa->category_name;
//exit();
if (isset($gruppa)) {
		if(isset(Yii::app()->params['group_characteristics_mode']) AND Yii::app()->params['group_characteristics_mode']=='multi') $grupp_characteristics_view =  'grupp_characteristics_new';
		else $grupp_characteristics_view =  'grupp_characteristics';
		CController::renderPartial( $grupp_characteristics_view, array('grupp_characteristics'=>@$grupp_characteristics, 'gruppa'=>$gruppa,  'all_groups'=>@$all_groups, 'gruppa_files'=>@$gruppa_files, 'linked_pages'=>@$linked_pages, 'all_char_types'=>@$all_char_types)); 
}///////////if (isset($gruppa)) {
?>
<div align="right">&nbsp;&nbsp;&nbsp;<a  style="cursor:pointer" title="Свернуть"  onclick="collapse('#group_params')">скрыть</a></div>
</div>
</div>
<div style="background-color:#fffbf0">Товары в группе:<br>
<?
//print_r($grupp_characteristics);
//print_r($group_filter_values);
if (isset($gruppa)) CController::renderPartial( 'grupp_goods', array('goods'=>$goods, 'gruppa'=>$gruppa, 'group_filter_values'=>$group_filter_values, 'products_attributes'=>$products_attributes, 'filtr_char_id'=>$filtr_char_id ) );
?>
</div>
</div>
<div style="height: 5px; clear:both">&nbsp;</div>
</div>

</div><!--<div id="mainContent" style="padding-left:3px; ">-->


<div style="height: 5px; clear:both">&nbsp;</div>

