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
window.open (url,"mywindow","location=0,status=0,scrollbars=1,width=1200,height=700");
}

function myfunc_razdel(id, targetform, targetitem){
//alert (id);
//window.location.reload( true );
//document.getElementById(targetitem).value = id;
$( '#'+targetitem).val(id);
document.forms[targetform].submit();
return false;
}////////////////


 function show(ele) {
      var srcElement = document.getElementById(ele);
      if(srcElement != null) {
          if(srcElement.style.display == "block") {
            srcElement.style.display= 'none';
          }
          else {
            srcElement.style.display='block';
          }
      }
  }

</script>

<?
echo CHtml::beginForm(array('/adminproducts/addcompatible/'.$product->id.'?group='.$group.'&activetab=tab6'),  $method='post',$htmlOptions=array('name'=>'price_form', 'id'=>'price_form'));  
?>
<input name="add_product" type="hidden" id="add_product" >
<a href="#" onclick="{displaypopup('/nomenklatura?targetitem=add_product&targetform=price_form')}">
Подбор номенклатуры</a>
<br>
<?
//print_r($compabile);
?>
<table width="100%" border="0" cellspacing="10" cellpadding="1">
  <tr>
    <td>ID</td>
    <td>Артикул</td>
    <td>Наименование</td>
    <td>Удалить</td>
  </tr>
  <?
  for ($i=0; $i<count($compabile);$i++) {
  ?>
  <tr >
    <td><?=$compabile[$i]->compprod->id?></td>
    <td><?=$compabile[$i]->compprod->product_article?></td>
    <td><?=$compabile[$i]->compprod->product_name?></td>
    <td><?php echo CHtml::checkBox('del_product['.$compabile[$i]->id.']', 0)?></td>
  </tr>
  <?
  }
  ?>
</table>


<br>
<?
echo CHtml::submitButton('Сохранить', $htmlOptions=array ('name'=>'savecomp' , 'alt'=>'Сохранить', 'title'=>'Сохранить'));
 ?>
 
 
 <br>
<br>
<h2>Рекомендовать товар в группах</h2>
	<?
	echo CHtml::link('Добавить раздел', array('/nomenklatura/indexgr', 'targetitem'=>'add_category', 'targetform'=>'price_form') , array('onclick'=>"return hs.htmlExpand(this, { objectType: 'iframe' } )"));
	?>
<br>
<table width="100%" border="0" cellspacing="10" cellpadding="1">
	<tr>
		<td>ID</td>
		<td>Категория</td>
		<td>Активно до</td>
		<td>Фильтры</td>
		<td>Минимальная цена</td>
		<td>Максимальная цена</td>
		<td>&nbsp;</td>
		<td>Удалить</td>
	</tr>
	<?
	for ($i=0; $i<count($compabile_categories);$i++) {
		?>
	<tr>
		<td><?php echo $compabile_categories[$i]->compcategories->category_id?>
		</td>
		<td><?php echo $compabile_categories[$i]->compcategories->category_name?>
		</td>
		<td><?php 
		if(isset($compabile_categories[$i]->active_till_int)) $datevalue  = date("d-m-Y", $compabile_categories[$i]->active_till_int);

		$date_to = new MyDatePicker;
		$date_to->conf = array(
				'name'=>'date_to_value['.$compabile_categories[$i]->id.']',
				'value'=>isset($datevalue)?$datevalue:'',
		// additional javascript options for the date picker plugin
				'options'=>array(
					'showAnim'=>'fold',
					'dateFormat'=>'dd-mm-yy',
		),
				'htmlOptions'=>array(
		//	'style'=>'height:18px; padding:1px; border:0px'
		),
		'language' => 'ru',
		);
		$date_to->init();

		?>
		</td>
		<td><div class="headline" onclick="{
       $('#list<?php echo $compabile_categories[$i]->id?>') .toggle('slow');
       }" ><strong>Список значений </strong></div>
          <div class="exposed2" id="list<?php echo $compabile_categories[$i]->id?>" >Быавыц</div></td>
		<td><?php 
		echo CHtml::textfield('minprice['.$compabile_categories[$i]->id.']', $compabile_categories[$i]->minprice);
		?></td>
		<td><?php 
		echo CHtml::textfield('maxprice['.$compabile_categories[$i]->id.']', $compabile_categories[$i]->maxprice);
		?></td>
		<td> <div class="headline" onclick="{
       $('#list<?php echo $compabile_categories[$i]->id?>') .toggle('slow');
       }" ></div></td>
		<td><?php echo CHtml::checkBox('del_product_category['.$compabile_categories[$i]->id.']', 0)?>
		</td>
	</tr>
	<?
	}
	?>
</table>
<br>
	<?
	echo CHtml::hiddenField('add_category',  NULL, array('id'=>'add_category') );
	echo CHtml::submitButton('Сохранить', $htmlOptions=array ('name'=>'savecomp' , 'alt'=>'Сохранить', 'title'=>'Сохранить'));
	echo CHtml::endForm(); ?>

 
