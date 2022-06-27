<?
//print_r($models);
/////////////////////////////////////////В этом файле детально выводятся предложения
?>
<!--общий див-->
<div id="1" style="width:100%">
<!--путь-->
<div style="height:20px;">
<?
$this->breadcrumbs=unserialize($CAT->path);
?>
</div><!--/путь-->
<!--Левый див-->
<div id="left_col">
Отбор:
<?
echo CHtml::beginForm(array('/search/services/', 'alias'=>$CAT->alias ),  $method='get', $htmlOptions=array('name'=>'cat_filtr', 'id'=>'cat_filtr'));  

	//	for ($i=0; $i<count($models); $i++) {
	//			echo CHtml::checkBox($models[$i][category_id],  $checked=true).'&nbsp;'.$models[$i][category_name].'<br>';
	//	}/////////////for ($i=0; $i<count($models); $i++) {
		
		if (isset($filter_arr)) {///////////т.е. если заданы группы по которым был отбор - должны быть заданы
		for ($k=0; $k<count($CAT->child_categories); $k++) {
				//echo CHtml::hiddenField($filter_arr[$k], 1);
				echo CHtml::checkBox($CAT->child_categories[$k]->category_id, in_array($CAT->child_categories[$k]->category_id, $income_get_filters)).'&nbsp;'.$CAT->child_categories[$k]->category_name.'<br>';
		}//////////for ($k=0; $k<count($filter_arr); $k++) {
}///////////////if (isset($filter_arr)) {///////////т.е. если заданы г


$region = Yii::app()->getRequest()->getParam('region', NULL);	//////////Отбор по региону
$ul = Yii::app()->getRequest()->getParam('ul', NULL);	
$fl = Yii::app()->getRequest()->getParam('fl', NULL);	
echo CHtml::beginForm(array('/'.$CAT->alias ),  $method='get', $htmlOptions=array('name'=>'cat_filtr', 'id'=>'cat_filtr')); 
echo '<br>регион<hr>';
$SK = new KladrSelect('region', $region);
echo '<br><br>';
?>
Продавец:<hr>
<?
echo CHtml::checkBox('ul',  isset($ul)? $checked=true: $checked=false).'&nbsp;юридические лица<br>';
echo CHtml::checkBox('fl',   isset($fl)? $checked=true: $checked=false).'&nbsp;физические лица<br>';
?><br>
<br>
<!--список фильтров-->
<div style="font-family:Arial Narrow; font-size:8pt">
<?
  for($i=0; $i<count($characteristics);$i++){
echo CHtml::checkBox('fchar['.$characteristics[$i]->caract_id.']', in_array($characteristics[$i]->caract_id, $fchar) );
echo $characteristics[$i]->caract_name.'<br>'; 
$keywords .=$characteristics[$i]->caract_name.' ';
}/////////  for($i=0; $i<count($characteristics);$i++){
?>
</div><!--/список фильтров-->
<br>
<div align="center"><?
echo CHtml::submitButton('go', array('value'=>'Подобрать'));?>
</div>
<?
echo CHtml::endForm(); ?>
</div><!--Левый див конц-->

<!--Большой див-->
<div id="center_col">
<?
echo CHtml::beginForm(array('/compare/'.$CAT->alias.'' ),  $method='get', $htmlOptions=array('name'=>'prod_compare', 'id'=>'prod_compare', 'target'=>'_blank'));  
?>
 <div align="right" ><?php  $this->widget('CLinkPager',array('pages'=>$pages, 'header'=>'&nbsp;', 'nextPageLabel'=>'>', 'prevPageLabel'=>'<')); ?></div><hr><br>
<table width="1000" border="0" cellpadding="1" cellspacing="1" style="font-family:Arial Narrow" bgcolor="#666666">
<?
$pc = count($products);
if ($pc>0) {
for($i=0; $i<$pc; $i++) {
$keywords .=$products[$i]->product_name.' ';
?>
  <tr bgcolor="#ffffee">
    <td><nobr> <?=CHtml::link($products[$i]->product_name, array('/site/product/','alias'=>$CAT->alias, 'id'=>$products[$i]->id))?></nobr>
    <?
 // echo Yii::app()->urlManager->createUrl('site/category',array('alias'=>$CAT->alias, 'id'=>$products[$i]->id)) ;
	?>
    <br>
    <?=$products[$i]->created?>
    </td>
    <td>Пакет включает</td>
    <td>Цена</td>
  </tr>
  <tr bgcolor="#ffffee" style="padding:0px; ">
    <td>Продавец:<hr><?=$products[$i]->contr_agent->name?><br><br>
    <nobr> <?=CHtml::checkBox('compare['.$products[$i]->id.']',  false).'&nbsp;Сравнить';?></nobr>
    </td>
    <td><?=$products[$i]->attribute_value?></td>
    <td><?=$products[$i]->product_price?></td>
  </tr>

  <?
  }///////////for
  }//////////if (count($products)>0) {
  else {
  ?>
  <tr bgcolor="#ffffee" style="padding:0px; ">
    <td colspan="2">Предлжений по выбранным критериям не обнаружено</td>
  </tr>
  <?
  }
  ?>

</table>
<div align="right"><?php  $this->widget('CLinkPager',array('pages'=>$pages, 'header'=>'&nbsp;', 'nextPageLabel'=>'>', 'prevPageLabel'=>'<')); ?></div>
<?
echo CHtml::submitButton('go', array('value'=>'Сравнить пакеты услуг'));?><?
echo CHtml::endForm(); 
?>

</div><!--Большой див конц-->
<div style="clear:both"></div>
</div><!--общий див конец-->