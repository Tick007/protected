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
//echo CHtml::beginForm(array('/adminproducts/addpacks/'.$product->id.'?group='.$group.'&activetab=tab6'),  $method='post',$htmlOptions=array('name'=>'price_form', 'id'=>'price_form'));  

echo CHtml::beginForm(array('/adminproducts/updatepacks', 'id'=>$product->id, 'group'=>$group,  'char_filter'=>Yii::app()->getRequest()->getParam('char_filter'), 'activetab'=>'tab10'),  $method='post',$htmlOptions=array('name'=>'included_goods'));  

?>
<input name="add_product" type="hidden" id="add_product" >
<a href="#" onclick="{displaypopup('/nomenklatura?targetitem=add_product&targetform=price_form')}">
Подбор номенклатуры</a>
<br>
<?
//print_r($compabile);
?>
<table width="auto" border="0" cellspacing="2" cellpadding="1"  background="/images/2x2.png" >
<tr bgcolor="#fffbf0">
    <td colspan="2">Id подчиненного</td>
    <td colspan="4"><input type="text" name="child_id" class="textfield" size="10"></td>
  </tr>
<tr bgcolor="#fffbf0">
    <td>ID</td>
    <td>Артикул</td>
    <td>Наименование</td>
    <td>Цена</td>
    <td>Сортировка</td>
    <td>Удалить</td>
  </tr>
  <?
  echo count($compabile);
  $sum_price = 0;
  for ($i=0; $i<count($compabile);$i++) {
	 $sum_price+=$compabile[$i]->packed->product_price;
  ?>
<tr bgcolor="#fffbf0">
    <td><?php echo $compabile[$i]->packed->id?></td>
    <td><?php echo $compabile[$i]->packed->product_article?></td>
    <td><?php echo $compabile[$i]->packed->product_name?></td>
    <td><?php echo CHtml::textfield('productpack['.$compabile[$i]->packed->id.']', $compabile[$i]->packed->product_price, array('size'=>6))?></td>
    <td><?php echo CHtml::textfield('packsort['.$compabile[$i]->id.']', $compabile[$i]->sort, array('size'=>3));?></td>
    <td><?php echo CHtml::checkBox('del_packed['.$compabile[$i]->id.']', 0)?></td>
  </tr>
    <?
  }
  ?>
<tr bgcolor="#fffbf0">
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td align="right">Сумма&nbsp;</td>
  <td align="center"><?php echo $sum_price?></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
</tr>
<tr bgcolor="#fffbf0">
  <td>&nbsp;</td>
  <td colspan="2" align="right">Цена в карточке&nbsp;</td>
  <td align="center"><strong><?php
  echo $product->product_price;
  ?></strong></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
</tr>

</table>


<br>
<?
echo CHtml::submitButton('Сохранить', $htmlOptions=array ('name'=>'savecomp' , 'alt'=>'Сохранить', 'title'=>'Сохранить'));
 echo CHtml::endForm(); ?>
