<?php

Yii::app()->clientScript->scriptMap=array(
       // 'jquery.js'=>false,
	    'jquery.js'=>'/js/jquery-1.6.1.js',
	  'jquery.min.js'=>'/js/jquery-1.6.1.min.js',

);

?>
<script>
function delete_item(item_id) {
//conf =confirm("Подтвердите для удаления");
//alert (item_id);
if (confirm("Подтвердите для удаления")) {
				document.getElementById('delete_product').value = item_id;
				document.getElementById('form1').submit();
		}
}


function delete_dialog(){

	if (confirm("Подтвердите для удаления")) {
		var delete_selected = document.getElementById('delete_selected');
		  if (delete_selected.checked){
			$('#delete_products').val(1);
			document.getElementById('form1').submit();
		  }
	}
}

function send_dialog(){
	$('#transfer_to_group').val($('#new_group').val());
	//$('#new_link_group').val($('#link_group').val());
	document.getElementById('form1').submit();
}


function myfunc_razdel(id, targetform, targetitem){
//alert (id);
//window.location.reload( true );
//document.getElementById(targetitem).value = id;
$( '#'+targetitem).val(id);
if(targetitem=='new_group') $('#selected_gr').html(id);
if(targetitem=='new_link_group') $('#selected_link_gr').html(id);
//document.forms[targetform].submit();
return false;
}////////////////

</script>
<?
//print_r(Yii::app()->request->baseUrl);
?>
<?
$gr_id = Yii::app()->getRequest()->getParam('id', NULL);
echo CHtml::beginForm(array('/adminproducts/group/', 'id'=>$gr_id),  $method='GET', array('id'=>'form1'));  
echo CHtml::hiddenField('delete_product',  NULL );
echo CHtml::hiddenField('transfer_to_group',  NULL );
echo CHtml::hiddenField('new_link_group',  NULL );
echo CHtml::hiddenField('delete_products', NULL)
?>
<table width="100%" border="0" cellspacing="2" cellpadding="2" background="/images/2x2.png">
  <tr bgcolor="#CCCCCC">
    <th align="left" scope="col">&nbsp;</th>
    <th align="left" scope="col"><?php echo Chtml::link('Выбор', "", array(
				'style'=>'cursor: pointer; text-decoration: underline; padding-bottom: 5px;',
				'onclick'=>"{ $('#product_actions').dialog('open');}"
			));?></th>
    <th align="left" scope="col">Сорт</th>
    <th align="left" scope="col">Вкл/выкл</th>
    <th align="left" scope="col">Id</th>
    <th align="left" scope="col">Артикул</th>
    <th align="left" scope="col" width="100%">Наименование</th>
    <th align="left" scope="col" width="100%">ост1</th>
    <th align="left" scope="col" width="100%"><?php
    if (isset($group_filter_values) AND empty($group_filter_values)==false) {
			//print_r($group_filter_values);
			$group_filter_values['0']='Все';
	
    echo CHtml::dropDownList('char_filter', Yii::app()->getRequest()->getParam('char_filter', 0), $group_filter_values, array('onchange'=>"{document.getElementById('form1').submit();}") );
	}
	?></th>
    <th width="100%" colspan="2" align="left" scope="col">Витрина</th>
    <th width="100%" colspan="2" align="left" scope="col">Новинка</th>
    <th width="100%" colspan="2" align="left" scope="col">Распродажа</th>
    <th scope="col">Родитель</th>
    <th scope="col">Копия</th>
  </tr>
  <?
  for ($i=0; $i<count($goods); $i++) {
		
  ?>
  <tr bgcolor="#fffbf0">
    <td align="center"><?
    $filename_gif = $_SERVER['DOCUMENT_ROOT'].'/pictures/img_small/'.$goods[$i]->id.'.gif';
	$filename_jpg = $_SERVER['DOCUMENT_ROOT'].'/pictures/img_small/'.$goods[$i]->id.'.jpg';
	$filename_png = $_SERVER['DOCUMENT_ROOT'].'/pictures/img_small/'.$goods[$i]->id.'.png';
	$exist_gif = file_exists($filename_gif);
	$exist_jpg = file_exists($filename_jpg);
	$exist_png= file_exists($filename_png);
	if ($exist_gif==false AND $exist_jpg==false AND $exist_png==false) {/////////////Файл не существует, нужно рисовать элемент для закачки
			echo "<img border=\"1\" src=\"http://".$_SERVER['HTTP_HOST']."/images/nophoto_h60.png\" width=\"60\">";
	}//////////Файл не существует, нужно рисовать элемент для закачки
	else {////////////////////Иначе рисуем картинку
			if ($exist_png==true) {
				$filename = $filename_png;
				$filesrc = '/pictures/img_small/'.$goods[$i]->id.'.png';
			}
			elseif($exist_jpg==true) {
				$filename = $filename_jpg;
				$filesrc = '/pictures/img_small/'.$goods[$i]->id.'.jpg';
			}
			elseif($exist_gif==true) {
				$filename = $filename_gif;
				$filesrc = '/pictures/img_small/'.$goods[$i]->id.'.gif';
			}

			//echo "<img src=\"$filesrc\" style=\"max-height:60px\">";
			$picture = "<img src=\"$filesrc\" border=\"1\" style=\"max-width:60px\" title=\"".$goods[$i]->product_name."\">";
			echo CHtml::link($picture, array('/adminproducts/product/'.$goods[$i]->id.'/?group='.$gruppa->category_id), $htmlOptions=array ('encode'=>false, 'alt'=>$goods[$i]->product_name, 'style'=>'color:#000000'));
	}//////////////////else {//////Иначе рисуем картинку
	?></td>
    <td align="center"><?php
    echo CHtml::checkbox('sel_product['.$goods[$i]->id.']', false);
	?></td>
    <td align="center"><?php echo $goods[$i]->sort;?></td>
    <td align="center"><?
    if (@$goods[$i]->product_visible) echo " <img src=\"/images/apply.png\" border=\"0\" alt=\"Товар включен\" title=\"Товар включен\"/>";
	else echo " <img src=\"/images/stop.png\" border=\"0\" alt=\"Товар включен\" title=\"Товар включен\"/>";
	?></td>
    <td><?=CHtml::link( $goods[$i]->id, array('/adminproducts/product/'.$goods[$i]->id.'/?group='.$gruppa->category_id), $htmlOptions=array ('encode'=>false, 'title'=>'Товары' ) )?></td>
    <td><?
    echo $goods[$i]->product_article;
	?></td>
    <td>
	<?=CHtml::link( $goods[$i]->product_name, array('adminproducts/product', 'id'=>$goods[$i]->id, 'group'=>$gruppa->category_id, 'char_filter'=>Yii::app()->getRequest()->getParam('char_filter')), $htmlOptions=array ('encode'=>false, 'title'=>'Товары' ) )?>
	<?
    ///echo $goods[$i]->product_name;
	?></td>
    <td><?php echo $goods[$i]->number_in_store?></td>
    <td><?php
    //print_r($group_filter_values);
	if (empty($filtr_char_id)==false) {
		$prod_chars = explode('#;#', $products_attributes[$goods[$i]->id]);  
		echo '<pre>';
		//print_r($prod_chars );
		echo '</pre>';
			$chars_product = NULL;
			for ($b=0; $b<count($prod_chars); $b++) {
					$char_ids = explode(';#;', $prod_chars[$b]);///////
					//print_r($char_ids);
					$chars_product[$char_ids[1]]=$char_ids[0];
			}
			//print_r($chars_product);
			if (isset($chars_product[$filtr_char_id])) echo $chars_product[$filtr_char_id];
	}
	?></td>
    <td align="center"><?
    if (@$goods[$i]->product_vitrina) echo " <img src=\"/images/apply.png\" border=\"0\" alt=\"Товар на главной\" title=\"Товар включен\"/>";
	else echo " <img src=\"/images/stop.png\" border=\"0\" alt=\"Товар включен\" title=\"Товар на главной\"/>";
	?></td>
    <td align="center"><?php
    echo $goods[$i]->product_vitrina_sort;
	?></td>
    <td align="center"><?
    if (@$goods[$i]->product_new) echo " <img src=\"/images/apply.png\" border=\"0\" alt=\"Товар в новинках\" title=\"Товар включен\"/>";
	else echo " <img src=\"/images/stop.png\" border=\"0\" alt=\"Товар включен\" title=\"Товар в новинках\"/>";
	?></td>
    <td align="center"><?php
    echo $goods[$i]->product_new_sort;
	?></td>
    <td><?
    if (@$goods[$i]->product_sellout) echo " <img src=\"/images/apply.png\" border=\"0\" alt=\"Товар в распродаже\" title=\"Товар включен\"/>";
	else echo " <img src=\"/images/stop.png\" border=\"0\" alt=\"Товар включен\" title=\"Товар в распродаже\"/>";
	?></td>
    <td><?php
    echo $goods[$i]->product_sellout_sort;
	?></td>
    <td><?
	if ($goods[$i]->product_parent_id>0) echo	CHtml::link( $goods[$i]->product_parent_id, array('/adminproducts/product/'.$goods[$i]->product_parent_id.'/?group='.$gruppa->category_id), $htmlOptions=array ('encode'=>false, 'title'=>'Товары' ) )?></td>
    <td align="center"><?=CHtml::link("<img src=\"".Yii::app()->request->baseUrl."/images/copy.png\"/>", array('/adminproducts/group/'.$gruppa->category_id.'/?copy='.$goods[$i]->id ), $htmlOptions=array ('encode'=>false, 'title'=>'Товары' ) )?></td>
  </tr>
  <?
  }//////////////////////////////////////
  ?>
</table>

	<?php $this->beginWidget('zii.widgets.jui.CJuiDialog', array(
				'id'=>'product_actions',
				// additional javascript options for the dialog plugin
				'options'=>array(
					'title'=>'Действия с товарами',
					'autoOpen'=>false,
					'modal'=>false,
					'width'=>300,
					'height'=>500,
					'z-index'=>0,
				),
			)); ?>
				<div class="divForForm"><br>
                <?php
				?><h4>Перемещение выбранных в другую группу, ID группы</h4><?php
                //$cat_bel_main =new ProductGroup(NULL,  'new_group', NULL );
				//$cat_bel_main->Draw();
	echo CHtml::link('Выбрать раздел', array('/nomenklatura/indexgr', 'targetitem'=>'new_group', 'targetform'=>'form1') , array('onclick'=>"return hs.htmlExpand(this, { objectType: 'iframe' } )"));
	//echo CHtml::textfield('new_link_group', NULL, array('placeholder'=>'введите ид группы  с которой требуется сделать связь') )
	echo CHtml::hiddenField('new_group',  NULL, array('id'=>'new_group') );
	
	?><br><br>
    <div id="selected_gr" style="font-size:18px"></div><br>
    
				<br>
                <h4>Создать связь с группой</h4>
                <?php
   			//	$cat_link_main =new ProductGroup(NULL,  'link_group', NULL );
			//	$cat_link_main->Draw();
				//echo CHtml::textfield('new_link_group', NULL, array('placeholder'=>'введите ид группы  с которой требуется сделать связь') )
				echo CHtml::link('Выбрать группу', array('/nomenklatura/indexgr', 'targetitem'=>'new_link_group', 'targetform'=>'form1') , array('onclick'=>"return hs.htmlExpand(this, { objectType: 'iframe' } )"));
				//echo CHtml::hiddenField('new_link_group',  NULL, array('id'=>'new_link_group') );
	?>
    <div id="selected_link_gr" style="font-size:18px"></div><br>
                </div>
                <br>
                <?php echo CHtml::Button('Выполнить', array('onClick'=>'{send_dialog()}'))?>
                <br>
                <br>
                <h3>Удаление выбранных:</h3>
                <br>
                <?php 
                echo CHtml::checkbox('delete_selected', false);
                ?> - Да, я хочу удалить выбранные записи<br>
                <?php echo CHtml::Button('Удалить', array('onClick'=>'{delete_dialog()}'))?>
                
			<?php $this->endWidget(); ?>

 <?php echo CHtml::endForm(); ?>
 
 
 