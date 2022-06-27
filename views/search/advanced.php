<?
$clientScript=Yii::app()->clientScript;
$clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/highslide/highslide-with-html.js', CClientScript::POS_HEAD);
$clientScript->registerCssFile(Yii::app()->request->baseUrl.'/js/highslide/highslide.css');
?>
<script type="text/javascript">

$(document).ready(function(){
<?
$go = Yii::app()->getRequest()->getParam('go', NULL);
$page = Yii::app()->getRequest()->getParam('page', NULL);
if ($go != NULL OR $page!=NULL) echo "document.getElementById('cat1').style.display= 'none';";
?>
});

hs.graphicsDir = '<?=Yii::app()->request->baseUrl?>/js/highslide/graphics/';
hs.outlineType = 'rounded-white';
hs.wrapperClassName = 'draggable-header';
hs.minWidth = 250;
hs.minHeight = 500;


function myfunc(id, targetform, targetitem){
//alert (id);
//window.location.reload( true );
document.getElementById(targetitem).value = id;
document.forms[targetform].submit();
//document.forms.targetform.submit();
//window.location.href='http://<?=$_SERVER['HTTP_HOST']?><?=Yii::app()->request->baseUrl?>/privateroom#tab2';
//document.location='http://<?=$_SERVER['HTTP_HOST']?><?=Yii::app()->request->baseUrl?>/privateroom#tab2';
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
mb_internal_encoding("UTF-8");
//$typePred= mb_strtolower(FHtml::declinate($CAT->category_name, 'prep'));
$this->pageTitle="Расширенный поиск";
$this->pageDescription="  ";
////$kerwords  после прогона своств
?>
<!--общий див-->
<div id="1" style="width:100%">
<!--путь-->
<div style="height:20px;">
<?
//$this->breadcrumbs=unserialize($CAT->path);
//$this->breadcrumbs = $path_info;
?>
</div><!--/путь-->
<?
echo CHtml::beginForm(array('/search/advanced' ),  $method='get', $htmlOptions=array('name'=>'adv_search', 'id'=>'adv_search')); 
?>


<div class="headline" onclick="show('cat1')"><strong>Параметры поиска</strong></div>
    <div class="hidden" id="cat1">

        
<table width="100%" border="1" cellspacing="1" cellpadding="1">
  <tr>
    <td valign="top">Регион и раздел</td>
    <td valign="top">Спецификации</td>
    <td valign="top">Фильтр продавцов</td>
    <td valign="top">Временной интервал размещения объявления</td>
  </tr>
  <tr>
    <td width="25%" valign="top"><?
echo CHtml::hiddenField('filter_region', NULL, array('id'=>'filter_region') ); 
echo CHtml::hiddenField('filter_razdel', NULL, array('id'=>'filter_razdel') ); 
echo CHtml::hiddenField('sort', Yii::app()->getRequest()->getParam('sort', NULL) ); 

echo CHtml::link('Выбрать регион', array('/nomenklatura/regions', 'targetitem'=>'filter_region', 'targetform'=>'adv_search') , array('onclick'=>"return hs.htmlExpand(this, { objectType: 'iframe' } )"));
if ($kladr_filters!=NULL) {/////////////Выводим список регионов для возможности удаления
echo '<br>Выбранные регионы:<br>';
		for($i=0; $i<count($kladr_filters); $i++) {////////
				//echo $kladr_filters[$i]->name.'<br>';
				echo CHtml::link($kladr_filters[$i]->name, array('/search/advanced/', 'unsetregion'=>$kladr_filters[$i]->kladr_id )).'<br>';
		}////////////for($i=0; $i<count($kladr_filters); $i++) {
}/////////////////if ($kladr_filters!=NULL) {
echo '<br>';
echo '<br>'.CHtml::link('Выбрать раздел', array('/nomenklatura/indexgr', 'targetitem'=>'filter_razdel', 'targetform'=>'adv_search') , array('onclick'=>"return hs.htmlExpand(this, { objectType: 'iframe' } )"));

if ($groups_filters != NULL) {
		echo '<br>Выбранные группы:<br>';
		for($i=0; $i<count($groups_filters); $i++) echo CHtml::link($groups_filters[$i]->category_name, array('/search/advanced/', 'unsetrazdel'=>$groups_filters[$i]->category_id )).'<br>';
}//////////if ($groups_filters != NULL) {

?></td>
    <td width="25%" valign="top">
<?
$search_region_list =Yii::app()->request->cookies['search_region_list'];
//print_r(unserialize($search_region_list->value));
?>
<br>
<?
$search_razdel_list =Yii::app()->request->cookies['search_razdel_list'];
//print_r(unserialize($search_razdel_list->value));
?></td>
    <td width="25%" valign="top"><?
 $ul = Yii::app()->getRequest()->getParam('ul', NULL);	
$fl = Yii::app()->getRequest()->getParam('fl', NULL);	

echo CHtml::checkBox('ul',  isset($ul)? $checked=true: $checked=false).'&nbsp;юридические лица<br>';
echo CHtml::checkBox('fl',   isset($fl)? $checked=true: $checked=false).'&nbsp;физические лица<br>';
	?>
    <br><br>
Опыт работы:
<?

//Read more
//http://jqueryui.com/demos/slider/
$this->widget('zii.widgets.jui.CJuiSliderInput', array(
    //'model'=>$model,
    //'attribute'=>'size',
    'name'=>'expirience',
    'value'=>Yii::app()->getRequest()->getParam('expirience', 0),
    'event'=>'change',
    'options'=>array(
        'min'=>0,
        'max'=>2,
		'step'=>0.1,
        'slide'=>'js:function(event,ui){$("#amount").text(ui.value);}',
    ),
    'htmlOptions'=>array(
        'style'=>'width:200px; float:left;'
    ),
));

?>
<table width="200" border="0" cellspacing="0" cellpadding="0" style="font-size:9px">
  <tr>
    <td align="center">0</td>
    <td align="center">&nbsp;</td>
    <td align="center">0.5</td>
    <td align="center">&nbsp;</td>
    <td align="center">1</td>
    <td align="center">&nbsp;</td>
    <td align="center">1.5</td>
    <td align="center">&nbsp;</td>
    <td align="center">2</td>
  </tr>
</table>

<br><br><br>
    </td>
    <td width="25%" valign="top"><table width="auto" border="0" cellspacing="1" cellpadding="1">
      <tr>
        <td>от</td>
        <td><?
    $date_from = new MyDatePicker;
$date_from->conf = array(
				'name'=>'date_from_value',
				'value'=>Yii::app()->getRequest()->getParam('date_from_value', NULL),
    // additional javascript options for the date picker plugin
				'options'=>array(
					'showAnim'=>'fold',
					'language'=>'ru',
					'dateFormat'=>'dd-mm-yy',
				),
				'htmlOptions'=>array(
					'style'=>'height:18px; padding:1px; border:0px'
				),
			);
$date_from->init();?></td>
        <td>до</td>
        <td><?
    $date_to = new MyDatePicker;
$date_to->conf = array(
				'name'=>'date_to_value',
				'value'=>Yii::app()->getRequest()->getParam('date_to_value', NULL),
    // additional javascript options for the date picker plugin
				'options'=>array(
					'showAnim'=>'fold',
					'language'=>'ru',
					'dateFormat'=>'dd-mm-yy',
				),
				'htmlOptions'=>array(
					'style'=>'height:18px; padding:1px; border:0px'
				),
			);
$date_to->init();
	?></td>
      </tr>
    </table></td>
  </tr>
</table>


<div align="center"><?
echo CHtml::submitButton('go', array('value'=>'Подобрать', 'name'=>'go'));?>
</div>
</div>
<?
echo CHtml::endForm(); 
?>
<!--разворачивающися блок-->


<!--Результаты поиски-->
<div align="right" ><?php  $this->widget('CLinkPager',array('pages'=>$pages, 'header'=>'&nbsp;', 'nextPageLabel'=>'>', 'prevPageLabel'=>'<')); ?></div></div><hr><br>
<table width="100%" border="0" cellpadding="1" cellspacing="1">
<tr valign="top" >
    <td valign="top">&nbsp;</td>
    <td align="right" width="auto"><?
	$date_to_value = Yii::app()->getRequest()->getParam('date_to_value', NULL);
	if ($date_to_value != NULL) $adr['date_to_value'] = $date_to_value;
	$date_from_value = Yii::app()->getRequest()->getParam('date_from_value', NULL);
	if ($date_from_value != NULL) $adr['date_from_value'] = $date_from_value;
	$adr['page'] = Yii::app()->getRequest()->getParam('page', 1);
	$imd_decr = "<img border=\"0\" src=\"".Yii::app()->request->baseUrl."/images/decrease.png\">";
	$img_incr = "<img border=\"0\" src=\"".Yii::app()->request->baseUrl."/images/increase.png\">";
	
	if  (@$sort=='1') echo CHtml::link('Наименование' ,array_merge(array('/search/advanced', 'sort'=>'1d'), $adr)  );
    else  echo CHtml::link('Наименование' , array_merge(array('/search/advanced',  'sort'=>'1'), $adr) );
	
	?></td>
    <td width="100%"><?
	if  (@$sort=='1') echo CHtml::link($img_incr ,array_merge(array('/search/advanced', 'sort'=>'1d'), $adr));
    elseif  (@$sort=='1d')  echo CHtml::link($imd_decr , array_merge(array('/search/advanced', 'sort'=>'1'), $adr));
	?></td>
    <td><?
	if  (@$sort=='2') echo CHtml::link('Раздел' ,array_merge(array('/search/advanced', 'sort'=>'2d'), $adr));
    else  echo CHtml::link('Раздел' , array_merge(array('/search/advanced', 'sort'=>'2'), $adr));
	?></td>
    <td>&nbsp;</td>
    <td align="right"><?
	if  (@$sort=='3') echo CHtml::link('Регион' ,array_merge(array('/search/advanced', 'sort'=>'3d'), $adr));
    else  echo CHtml::link('Регион' , array_merge(array('/search/advanced', 'sort'=>'3'), $adr));
	?></td>
    <td align="left"><?
	if  (@$sort=='3') echo CHtml::link($img_incr ,array_merge(array('/search/advanced', 'sort'=>'3d'), $adr));
    elseif  (@$sort=='3d')  echo CHtml::link($imd_decr, array_merge(array('/search/advanced', 'sort'=>'3'), $adr));
	?></td>
    <td>Срок работы мерчанта</td>
    <td>Мерчант</td>
    <td align="right"><?
	if  (@$sort=='6') echo CHtml::link("Дата размещения" , array_merge(array('/search/advanced', 'sort'=>'6d'), $adr));
    else  echo CHtml::link("Дата размещения" , array_merge(array('/search/advanced', 'sort'=>'6'), $adr));
	?></td>
    <td align="left"><?
	if  (@$sort=='6') echo CHtml::link($img_incr ,array_merge(array('/search/advanced', 'sort'=>'6d'), $adr));
    elseif(@$sort=='6d')   echo CHtml::link($imd_decr , array_merge(array('/search/advanced', 'sort'=>'6'), $adr));
	?></td>
    <td>Описание</td>
    <td align="right"><?
	if  (@$sort=='7') echo CHtml::link("Цена" ,array('/search/advanced', 'page'=>Yii::app()->getRequest()->getParam('page', 1), 'sort'=>'7d'));
    else   echo CHtml::link("Цена" , array('/search/advanced', 'page'=>Yii::app()->getRequest()->getParam('page', 1), 'sort'=>'7'));
	?></td>
    <td align="left"><?
	if  (@$sort=='7') echo CHtml::link($img_incr , array_merge(array('/search/advanced', 'sort'=>'7d'), $adr));
    elseif  (@$sort=='7d')   echo CHtml::link($imd_decr , array_merge(array('/search/advanced', 'sort'=>'7'), $adr) );
	?></td>
</tr>
<?
$pc = count($products);
$keywords = '';
if ($pc>0) {

for($i=0; $i<$pc; $i++) {
$keywords .=$products[$i]->product_name.' ';
?>
  
  <tr>
    <td valign="top" width="10"><?
	$iconname = Yii::app()->request->baseUrl."/pictures/add/icons/".$products[$i]->icon.'.png';
			//echo $_SERVER['DOCUMENT_ROOT'].$iconname;
			if (file_exists($_SERVER['DOCUMENT_ROOT'].$iconname)==1) echo "<img src=\"$iconname\" />";
	?></td>
    <td width="350" colspan="2">
    <?=CHtml::link($products[$i]->product_name, array('/site/product/','alias'=>$products[$i]->belong_category->alias, 'id'=>$products[$i]->id), array('style'=>'font-size:12px; font-family:Century Gothic, Arial, Sans-serif; font-weight:bold'))?>
   
      <?
 // echo Yii::app()->urlManager->createUrl('site/category',array('alias'=>$CAT->alias, 'id'=>$products[$i]->id)) ;
	?>
      <br />
      
    <? // $products[$i]->created?></td>
    <td><?=$products[$i]->belong_category->category_name?></td>
    <td>&nbsp;</td>
    <td colspan="2"><?=$products[$i]->kladr->name?></td>
    <td><?=$products[$i]->expirience?></td>
    <td><?=CHtml::link($products[$i]->contr_agent->name, array('/merchant/info/', 'alias'=>$products[$i]->contr_agent->alias))?></td>
    <td colspan="2"><?=$products[$i]->created?></td>
    <td><?=$products[$i]->product_short_descr?>    </td>
    <td colspan="2"><nobr><strong style="font-size:12px; ">
      <?
	//$str[strlen($str)-3] = 'e';
	if (strlen($products[$i]->product_price) >3)$price = substr($products[$i]->product_price, 0, strlen($products[$i]->product_price)-3).' 000';
	else $price = $products[$i]->product_price;
	echo $price ;
	//echo strlen($products[$i]->product_price)
	//echo $products[$i]->product_price?>
руб.</strong><nobr></td>
  </tr>
  

  <?
  }///////////for
  }//////////if (count($products)>0) {
  else {
  ?>
  <tr bgcolor="#ffffee" style="padding:0px; ">
    <td colspan="14">Предлжений по выбранным критериям не обнаружено</td>
  </tr>
  <?
  }
  ?>
</table>

<div align="right"><?php  $this->widget('CLinkPager',array('pages'=>$pages, 'header'=>'&nbsp;', 'nextPageLabel'=>'>', 'prevPageLabel'=>'<')); ?></div>
<!--/Результаты поиски-->


</div><!--общий див конец-->