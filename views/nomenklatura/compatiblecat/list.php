<?
$clientScript=Yii::app()->clientScript;
$clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/highslide/highslide-with-html.js', CClientScript::POS_HEAD);
$clientScript->registerCssFile(Yii::app()->request->baseUrl.'/js/highslide/highslide.css');
?>
<script type="text/javascript">
hs.graphicsDir = '/js/highslide/graphics/';
hs.outlineType = 'rounded-white';
hs.wrapperClassName = 'draggable-header';
hs.minWidth = 320;
hs.minHeight = 520;
hs.height =520;
hs.width= 320;
</script>

<script>
$(document).ready(function(){

});

function myfunc_razdel(id, targetform, targetitem){
//alert (id);
//window.location.reload( true );
document.getElementById(targetitem).value = id;
document.forms[targetform].submit();
return false;
}////////////////

</script>
<?php
$this->pageTitle="Привязка рекомендаемых групп к группе";
?>
<div id="Right_column" style="background-color:#666E73; width:60px; margin-left:0px; float:left">
<?
$RC = new RightColumnAdmin;
?>
</div>
<div id="mainContent" style="padding-left:3px; margin-left:60px; background-color:#fffbf0  ">
<br><?
	echo CHtml::link('Добавить раздел', array('/nomenklatura/indexgr', 'targetitem'=>'add_category', 'targetform'=>'complist') , array('onclick'=>"return hs.htmlExpand(this, { objectType: 'iframe' } )"));
	?><br><br>

<?
echo CHtml::beginForm(array('nomenklatura/catcompatiblecat', 'id'=>$CAT->category_id),  $method='post',$htmlOptions=array('name'=>'complist', 'id'=>'complist'));  
?>

<?php
if(empty($models)==false) {
?>
<table class="cat_content_table"><thead>
	<tr>
        <th>Группа</th>
        <th>Название табки</th>
        <th>Статтус</th>
        <th>Активно до</th>
        <th>Фильтры</th>
        <th>Список товаров</th>
        <th>Минимальная цена</th>
        <th>Максимальная цена</th>
        <th>Удаление</th>
    </tr>
</thead>
	<tbody>
    <?php
    	for($i=0; $i<count($models); $i++) { ?>
		<tr>
        	<td><?php
            echo $models[$i]->compcategories->category_name.'('.$models[$i]->compatible_category.')';
			?></td>
        	<td><?php 
		echo CHtml::textfield('compat_category['.$models[$i]->id.'][tabname]', $models[$i]->tabname);
		?></td>
            <td><?php
            echo CHtml::checkBox('compat_category['.$models[$i]->id.'][active]', $models[$i]->active);
			?></td>
            <td><?php 
		if(isset($models[$i]->active_till_int)) $datevalue  = date("d-m-Y", $models[$i]->active_till_int);

		$date_to = new MyDatePicker;
		$date_to->conf = array(
				'name'=>'compat_category['.$models[$i]->id.'][active_till_int]',
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

		?></td>
            <td style="text-align:left"><?php
			if(isset($characteristics_categories[$models[$i]->compatible_category])) {
				$this->renderPartial('compatiblecat/filters', array('characteristics_categories'=>$characteristics_categories[$models[$i]->compatible_category], 'values_list'=>@$values_list, 'comp_cat_cat_id'=>$models[$i]->id, 'filters'=>$models[$i]->filters));
			}
			?></td>
            <td style="text-align:left; font-family:Arial Narrow"><?php
            echo $models[$i]->products;
			
			?></td>
            <td><?php 
		echo CHtml::textfield('compat_category['.$models[$i]->id.'][minprice]', $models[$i]->minprice);
		?></td>
            <td><?php 
		echo CHtml::textfield('compat_category['.$models[$i]->id.'][maxprice]', $models[$i]->maxprice);
		?></td>
            <td><?php
            echo CHtml::checkBox('del_compat_category['.$models[$i]->id.']', 0)
			?></td>
        </tr>
   	 <?php	
		}
	?>
    </tbody>
</table>
<br><br>
<?php
echo CHtml::submitButton('Сохранить', $htmlOptions=array ('name'=>'savecomp' , 'alt'=>'Сохранить', 'title'=>'Сохранить'));
?>
<br><br>
<?php
if(isset($products) AND empty($products)==false) {
	?>
	<table class="cat_content_table"><thead>
	<tr>
   		<th>Фото</th>
   		<th>Группа</th>
        <th>Товар</th>
        <th>Артикул</th>
        <th>Цена</th>
    </tr>
</thead>
	<tbody>
    <?php
    for($i=0; $i<count($products); $i++) {
	?>
    	<tr>
        <td style="text-align:left"><div style="height:100px; overflow:hidden; width:100px;"><?php
							$iconname = Yii::app()->request->baseUrl."/pictures/add/icons/".$products[$i]->icon.'.png';
									//echo $_SERVER['DOCUMENT_ROOT'].$iconname;
									if (file_exists($_SERVER['DOCUMENT_ROOT'].$iconname)==1) echo CHtml::link("<img src=\"$iconname\"  style=\"max-width:100px; max-height:100px\" />", $url);
							?></a>
                            </div></td>
        <td style="text-align:left"><?php echo $products[$i]->belong_category->category_name?></td>
        <td style="text-align:left"><?php echo $products[$i]->product_name?></td>
        <td><?php echo $products[$i]->product_article?></td>
        <td><?php echo $products[$i]->product_price?></td>
        </tr>
        <?php
	}
		?>
    </tbody>
    </table>
	<?php
}///////////if(isset($products) AND empty($products)==false) {
?>


<?php
}
?>

<?
	echo CHtml::hiddenField('add_category',  NULL, array('id'=>'add_category') );
	
	echo CHtml::endForm(); ?>

</div>