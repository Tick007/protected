<?php
$this->pageTitle="Обработка прайслиста от поставщика: шаг 1";
?>
<div id="Right_column" style="background-color:#666E73; width:60px; margin-left:0px; float:left">
<?
$RC = new RightColumnAdmin;
?>
</div>
<div id="mainContent" style="padding-left:3px; margin-left:60px; background-color:#fffbf0  ">
<h2>Загрузка прайслистов от поставщика: шаг 1</h2>

<?
echo CHtml::beginForm(array('/adminprices/stepone', 'id'=>$id),  $method='post', array('enctype'=>'multipart/form-data'));  
echo CHtml::errorSummary($stepone); ?>

Выберите файл прайс листа XLS:
<?php
//echo CHtml::fileField('xlsprice');
echo CHtml::activeFileField($stepone, 'xlsprice');
?>
<br><br>
Выберите склад:
<?php
//echo CHtml::dropDownlist('store_id', 0, $stores_list);
echo CHtml::activeDropDownList($stepone, 'store_id', $stores_list);
?>
<br><br>
Определите настройки обработки колонок:
<table>
  <tr>
    <th align="left" scope="row">ИД</th>
    <th align="left" scope="row">Артикул</th>
    <th align="left" scope="row">Цена</th>
    <th align="left" scope="row">Остаток</th>
  </tr>
  <tr>
    <th align="left" scope="row"><?
	
    //echo CHtml::textfield('prod_col', NULL, array('size'=>'2', 'maxlenth'=>'2'));
	?></th>
    <th align="left" scope="row"><?
    //echo CHtml::textfield('article_col', 3, array('size'=>'2', 'maxlenth'=>'2'));
	 echo CHtml::activeTextField($stepone,'article_col',array('size'=>5,'maxlength'=>2));
	?></th>
    <th align="left" scope="row"><?
	echo CHtml::activeTextField($stepone,'price_col',array('size'=>5,'maxlength'=>2));
	?></th>
    <th align="left" scope="row"><?php 
	echo CHtml::activeTextField($stepone,'store_col',array('size'=>5,'maxlength'=>2));
	?></th>
  </table>
<br><br>

<?
     if (@$pricelist->status==0) echo CHtml::submitButton('Продолжить', $htmlOptions=array ('name'=>'savepricelist' , 'alt'=>'Продолжить', 'title'=>'Продолжить'));

	?>

<?
	echo CHtml::hiddenField('pricelist_id',  $id );
	
	echo CHtml::endForm(); ?>

</div>