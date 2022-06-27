<?
$clientScript=Yii::app()->clientScript;
$clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/highslide/highslide-with-html.js', CClientScript::POS_HEAD);
?>
<script type="text/javascript">
hs.graphicsDir = '/js/highslide/graphics/';
hs.outlineType = 'rounded-white';
hs.wrapperClassName = 'draggable-header';
</script>

<script>
$(document).ready(function(){
//$("#faq-answer").animate({ opacity: "hide" }, "fast");
//$("#add_product").change(function(){

//  alert( $(this).text() );

//}).change();


});

function displaypopup(url){
window.open (url,"mywindow","location=0,status=0,scrollbars=1,width=600,height=600");
}

</script>

<?
echo CHtml::beginForm(array('/adminprices/pricelist/'.$price_id),  $method='post',$htmlOptions=array('name'=>'price_form', 'id'=>'price_form', 'enctype'=>'multipart/form-data'));  
?>
<div id="ribbon">&nbsp;
<?
 if (@$pricelist->status==0) {
?>
<input name="add_product" type="hidden" id="add_product" >
<a href="#" onclick="{displaypopup('/nomenklatura?targetitem=add_product&targetform=price_form')}">
Подбор номенклатуры</a>
<?
}
?>
</div>

<div id="Right_column" style="background-color:#C6E2FF">


<br>
<table width="auto" border="0" cellspacing="1" cellpadding="1">
  <tr>
    <th align="left" scope="row">Номер</th>
    <th align="left" scope="row">&nbsp;</th>
    <td><?
    echo $pricelist->id;
	?></td>
  </tr>
  <tr>
    <th align="left" scope="row">Дата</th>
    <th align="left" scope="row">&nbsp;</th>
    <td><?
    echo $pricelist->creation_dt;
	?></td>
  </tr>
  <tr>
    <th align="left" scope="row">Валюта</th>
    <th align="left" scope="row">&nbsp;</th>
    <td><?
    echo $pricelist->currencies->currency_code;
	?></td>
  </tr>
  <tr>
    <th align="left" scope="row">Статус</th>
    <th align="left" scope="row">&nbsp;</th>
    <td><?
    if (@$pricelist->status==1) echo "<img src=\"/images/apply.png\">";
	else  echo "<img src=\"/images/stop.png\">";
	?></td>
  </tr>
  <tr>
    <th align="left" scope="row">&nbsp;</th>
    <th align="left" scope="row">&nbsp;</th>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <th colspan="3" align="left" scope="row"><?
    echo 'Загрузка из XLS: '.CHtml::fileField('xlsprice');
	?></th>
    </tr>
  <tr>
    <th colspan="3" align="left" scope="row">№ колонки (со 2го ряда)</th>
    </tr>
  <tr>
    <th align="left" scope="row">ИД</th>
    <th align="left" scope="row">Артикул</th>
    <th align="left" scope="row">Цена</th>
  </tr>
  <tr>
    <th align="left" scope="row"><?
    echo CHtml::textfield('prod_col', NULL, array('size'=>'2', 'maxlenth'=>'2'));
	?></th>
    <th align="left" scope="row"><?
    echo CHtml::textfield('article_col', 3, array('size'=>'2', 'maxlenth'=>'2'));
	?></th>
    <th align="left" scope="row"><?
    echo CHtml::textfield('price_col', 6, array('size'=>'2', 'maxlenth'=>'2'));
	?></th>
  </tr>
  <tr>
    <th align="left" scope="row">Склад</th>
    <th colspan="2" align="left" scope="row"><?
    echo CHtml::textfield('store_col', 9, array('size'=>'0', 'maxlenth'=>'5'));
	?></th>
  </tr>
  <tr>
    <th align="left" scope="row">Склад в системе</th>
    <th colspan="2" align="left" scope="row"><?
	if(isset($stores_list) AND empty($stores_list)==false)  echo CHtml::dropDownlist('store_id', 1, $stores_list);
   // echo CHtml::textfield('store_id', 1, array('size'=>'2', 'maxlenth'=>'2'));
	?></th>
    </tr>
  <tr>
    <th colspan="3" align="left" scope="row"><?php
    echo CHtml::checkbox('do_not_trancate', NULL); 
	?> - не обнулять данные предъидущего прайслиста</th>
  </tr>
  <tr>
    <th colspan="3" align="center" scope="row"><?
     if (@$pricelist->status==0) echo CHtml::submitButton('Сохранить', $htmlOptions=array ('name'=>'savepricelist' , 'alt'=>'Сохранить', 'title'=>'Сохранить'));

	?></th>
    </tr>
  <tr>
    <th colspan="3" align="center" scope="row"><?
    if (@$pricelist->status==1)  echo CHtml::submitButton('Отмена проведения', $htmlOptions=array ('name'=>'abortapply' , 'alt'=>'Отмена проведения', 'title'=>'Отмена проведения'));
	else echo CHtml::submitButton('Провести', $htmlOptions=array ('name'=>'apply' , 'alt'=>'Провести', 'title'=>'Провести'));
	?></th>
    </tr>
</table>
<hr><br><br>
<?
$RC = new RightColumnAdmin;
?>
</div>
<div id="mainContent" style="padding-left:3px; ">
<?php
if(isset($updated_products_count)AND empty($updated_products_count)==false) echo 'Обновленно товаров '.$updated_products_count;
?>
<table width="auto" border="0" cellspacing="1" cellpadding="1">
  <tr>
    <th align="left" scope="col">Артикул</th>
    <th align="left" scope="col">Номенклатура</th>
    <th align="left" scope="col">% вх НДС</th>
    <th align="left" scope="col">вх НДС</th>
    <th align="left" scope="col">Закупка с НДС</th>
    <th align="left" scope="col">Цена пред С НДС.</th>
    <th align="left" scope="col">НДС продажи</th>
    <th align="left" scope="col">Новая цена с НДС</th>
    <th align="left" scope="col">Новая цена без НДС</th>
    <th align="left" scope="col">Склад</th>
    <th align="left" scope="col"><img src="/images/delete.gif"></th>
  </tr>
  <?
  for ($i=0; $i<count($models); $i++) {
  ?>
  <tr>
    <td><?
    echo $models[$i]->product->product_article;
	?></td>
    <td><span style="font-family:Arial Narrow; font-size:120%"><?
    echo $models[$i]->product->product_name;
	?></span></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td><?
    echo $models[$i]->product->nds_out;
	?></td>
    <td><?
    //products_list_price[]
	echo CHtml::textfield('products_list_price['.$models[$i]->id.']', $models[$i]->price_with_nds,  $htmlOptions=array('encode'=>true, 'size'=>5 )  ) 
	?></td>
    <td><?
    $pr = $models[$i]->price_with_nds/(1+($models[$i]->product->nds_out));
	echo round($pr,2);
	?></td>
    <td><?

	//echo CHtml::textfield('products_list_store['.$models[$i]->id.']', $models[$i]->store,  $htmlOptions=array('encode'=>true, 'size'=>5 )  ) 
	echo $models[$i]->store;
	?></td>
    <td><?php echo CHtml::checkBox('del_product['.$models[$i]->id.']', 0)?></td>
  </tr>
  <?
  }///////////////
  ?>
</table>


<hr>
<?php

if(isset($prod_update) AND empty($prod_update)==false) {
	//echo '<pre>';
	//print_r($prod_update);
	//echo '</pre>';
}
//error_reporting(E_ALL ^ E_NOTICE);
//require_once 'excel_reader2.php';
//$data = new Spreadsheet_Excel_Reader("example.xls");
?>



</div><!--<div id="mainContent" style="padding-left:3px; ">-->


<div style="height: 5px; clear:both">&nbsp;</div>
<?php echo CHtml::endForm(); ?>
