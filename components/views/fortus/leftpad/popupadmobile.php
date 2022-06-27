<?php
$krepltype  = Yii::app()->getRequest()->getParam('krepltype');
$locktype = Yii::app()->getRequest()->getParam('locktype');
$year1 = Yii::app()->getRequest()->getParam('year');
if(isset($year1)) $year = unserialize($year1);		

Yii::app()->clientScript->scriptMap=array(
       // 'jquery.js'=>false,
	   //'jquery.js'=>'/js/jquery.min.1.7.js',
);


$clientScript = Yii::app()->clientScript;
$clientScript->registerScriptFile(Yii::app()->request->baseUrl . '/themes/fortusmobile/js/chosen/chosen.jquery.js', CClientScript::POS_HEAD);
$clientScript->registerCssFile(Yii::app()->request->baseUrl . '/themes/fortusmobile/js/chosen/chosen.css', "screen");


?>



<form action="/catalog/search/<?php if(isset($locktype)) echo $locktype?>/<?php if(isset($krepltype)) echo $krepltype;?>" method="get" name="lpform"  >

<?php
if(isset($krepltype))echo CHtml::hiddenField('krepltype', $krepltype);
if(isset($locktype))echo CHtml::hiddenField('locktype', $locktype);


///////////////////Ко

?>


<div class="row">
<div class="tremulate">
   <div class="row"><?php
    echo CHtml::/*ListBox*/dropDownList('krepltype',   'kpp', $device_types_list, array('id'=>'krepltype', 'class'=>'sel180', 'tabindex'=>0));
	?></div>
<div class="row">
      <?php
//echo CHtml::dropDownList('brand',   $brand, $brand_list, array('id'=>'brand', 'class'=>'sel180', 'tabindex'=>2));
echo CHtml::/*ListBox*/dropDownList('brand',   $brand, $brand_list, array('id'=>'brand', 'class'=>'sel180', 'tabindex'=>2));
?>
     </div>
<div class="row"><?php
// echo CHtml::dropDownList('model',   @$model, $model_list, array('id'=>'model', 'class'=>'sel180'));
echo CHtml::/*ListBox*/dropDownList('model',   @$model, $model_list, array('id'=>'model', 'class'=>'sel180'));
?></div>
</div>
<div class="tremulate">
<div class="row">
<?php
	
    echo CHtml::/*ListBox*/dropDownList('year',   @$year, (isset($year_list)?$year_list:array('0'=>'выбор...')), array('id'=>'year', 'class'=>'sel180'));
	?></div>
<div class="row"><?php
	//print_r($kpp_list);
    echo CHtml::/*ListBox*/dropDownList('kpp',   @$kpp,  (isset($kpp_list)?$kpp_list:array('0'=>'выбор...')), array('id'=>'kpp', 'class'=>'sel180'));
	?></div>
<div class="row"> <?php
    echo CHtml::Button('go', array('class'=>'gobut', 'value'=>'НАЙТИ', 'id'=>'go_but'));
	?></div>
	</div>
<div id="search_answer" align="center"> </div>
   <?php
  if(isset($krepltype) AND $krepltype=='kpp' ) {///////////////Только для 
  ?> 
  <?php
}
?>
  <?php /////////расширитель
if(isset($krepltype) AND ($krepltype=='val') ) {///
?>
<?php
  }
  ?>
<?php
/////////////////Расширитель
if(isset($krepltype) AND ($krepltype=='hood') ) {///
?>
<?php
}
?>
  
  <?php
   if(isset($krepltype) AND ($krepltype=='kpp'  OR $krepltype=='val') ) {///////////////Только для  
?>

  <div>
    <td colspan="3" valign="bottom"  class="header" style="height:54px;padding-left:2px"">Выбор по номеру МПУ</td>
   </div>
   <table>
   <tr>
    <td style="height:20px" valign="bottom"><a class="false_href" onClick="switch_row(this)"  id='search_href'>Быстрый поиск</a></td>
    <td style="height:20px" valign="bottom">&nbsp;</td>
    <td style="height:20px" valign="bottom">&nbsp;</td>
   </tr>
  <tr id="search_row">
    <td style="height:20px" valign="bottom"><?php
    echo CHtml::textfield('search', NULL, array( 'id'=>'searchp', 'class'=>'seltext', 'autocomplete'=>'off'));
	?></td>
    <td style="height:20px" valign="bottom" </td>
    <td style="height:20px" valign="bottom"> </td>
  </tr></table>
  <?php
}
?>




<!--  <tr>
    <td colspan="3" align="center" valign="top" style="height:28px;"><?php
    echo CHtml::button('resetbut',  array('value'=>'Сбросить','class'=>'whitebut', 'id'=>'resetbut'));
	?>&nbsp;&nbsp;&nbsp;
   &nbsp;&nbsp;</td>
  </tr>-->
  <tr>
    <td colspan="3" align="center" valign="bottom" style="height:33px;"> </td>
  </tr>
</div>
</form>

<script>
jQuery(document).ready(function(){
	
	$('.sel180, .sel110').chosen();
	$('.chzn-search').remove();
	
	//$('.chzn-results').jScrollPane();
	
	<?php
	if(@$brand>0) echo "$('#brend_href').click();";
	?>
	<?php
	if(@$model>0) echo "$('#model_href').click();";
	?>
	<?php
	if(isset($year) AND trim($year) ) {
		echo "$('#year_href').click();
				$('#model_href').change();";
				if(isset($kpp)==false OR trim($kpp)=='') echo "$('#year').change();";
	}	
	?>
	<?php
	if((isset($kpp) AND trim($kpp)) OR (isset($kpp_list))) echo "$('#kpp_href').click();";
	?>
});



</script>