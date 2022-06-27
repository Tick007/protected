<?php
$krepltype  = Yii::app()->getRequest()->getParam('krepltype');
$locktype = Yii::app()->getRequest()->getParam('locktype');
$year1 = Yii::app()->getRequest()->getParam('year');
if(isset($year1)) $year = unserialize($year1);		

/*
Yii::app()->clientScript->scriptMap=array(
       // 'jquery.js'=>false,
	   'jquery.js'=>'/js/jquery.min.1.7.js',
);
*/


$clientScript = Yii::app()->clientScript;
$clientScript->registerScriptFile(Yii::app()->request->baseUrl . '/themes/fortus/js/chosen/chosen.jquery.js', CClientScript::POS_HEAD);
$clientScript->registerCssFile(Yii::app()->request->baseUrl . '/themes/fortus/js/chosen/chosen.css', "screen");
//$clientScript->registerScriptFile(Yii::app()->request->baseUrl . '/js/jquery.jscrollpane.js', CClientScript::POS_HEAD);

?>
<script>
jQuery(document).ready(function(){
		$("#go_but").click(function() {
				//alert('qqqq');
				if($('#go_but').hasClass('gobut_red')==true) document.forms.lpform.submit();
				document.forms.lpform.submit();
			});
});
			
</script>
<div class="leftpad">

<form action="/catalog/search/<?php if(isset($locktype)) echo $locktype?>/<?php if(isset($krepltype)) echo $krepltype;?>" method="get" name="lpform"  >

<?php
if(isset($krepltype))echo CHtml::hiddenField('krepltype', $krepltype);
if(isset($locktype))echo CHtml::hiddenField('locktype', $locktype);


?>


<table width="100%" border="0" cellspacing="0" cellpadding="0" class="leftpadholder">
  <tr>
    <td colspan="2" valign="top" class="header" style="padding-left:2px" >Выбор по автомобилю</td>
    </tr>
  <tr>
    <td width="20"><i class="leftpadarraow right" id="brand_i"></i></td>
    <td><a class="false_href" onClick="switch_row(this)" id="brend_href">Марка</a></td>
  </tr>
  <tr id="brand_row" >
    <td>&nbsp;</td>
    <td valign="top"><?php
//echo CHtml::dropDownList('brand',   $brand, $brand_list, array('id'=>'brand', 'class'=>'sel180', 'tabindex'=>2));
echo CHtml::/*ListBox*/dropDownList('brand',   $brand, $brand_list, array('id'=>'brand', 'class'=>'sel180', 'tabindex'=>2));
?>

</td>
  </tr>
   <tr>
    <td width="20"><i class="leftpadarraow right" id="model_i"></i></td>
    <td><a class="false_href" onClick="switch_row(this)" id='model_href'>Модель</a></td>
  </tr>
  <tr id="model_row">
    <td>&nbsp;</td> 
    <td><?php
// echo CHtml::dropDownList('model',   @$model, $model_list, array('id'=>'model', 'class'=>'sel180'));
echo CHtml::/*ListBox*/dropDownList('model',   @$model, $model_list, array('id'=>'model', 'class'=>'sel180'));
?></td>
  </tr>
  <tr>
    <td width="20"><i class="leftpadarraow right" id="year_i"></i></td>
    <td><a class="false_href" onClick="switch_row(this)" id='year_href'>Период выпуска</a></td>
  </tr>
  <tr id="year_row">
    <td>&nbsp;</td> 
    <td><?php
	
    echo CHtml::/*ListBox*/dropDownList('year',   @$year, (isset($year_list)?$year_list:array('0'=>'выбор...')), array('id'=>'year', 'class'=>'sel110'));
	?></td>
  </tr>
   <?php
  if(isset($krepltype) AND $krepltype=='kpp' ) {///////////////Только для 
  ?> 
  <tr>
    <td width="20"><i class="leftpadarraow right" id="kpp_i"></i></td>
    <td><a class="false_href" onClick="switch_row(this)"  id='kpp_href'>Тип КПП</a></td>
  </tr>
 
  <tr id="kpp_row">
    <td>&nbsp;</td> 
    <td><?php
	//print_r($kpp_list);
    echo CHtml::/*ListBox*/dropDownList('kpp',   @$kpp,  (isset($kpp_list)?$kpp_list:array('0'=>'выбор...')), array('id'=>'kpp', 'class'=>'sel110'));
	?></td>
  </tr>
  <?php
}
?>
  <?php /////////расширитель
if(isset($krepltype) AND ($krepltype=='val') ) {///
?>
<tr>
    <td style="height:28px">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>

<?php
  }
  ?>
<?php
/////////////////Расширитель
if(isset($krepltype) AND ($krepltype=='hood') ) {///
?>
<tr>
    <td style="height:45px">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
<?php
}
?>
  
  <?php
   if(isset($krepltype) AND ($krepltype=='kpp'  OR $krepltype=='val') ) {///////////////Только для  
?>

  <tr>
    <td colspan="2" valign="bottom"  class="header" style="height:54px;padding-left:2px">Выбор по номеру МПУ</td>
    </tr>
   <tr>
    <td width="20" valign="bottom"><i class="leftpadarraow right" id="search_i"></i></td>
    <td style="height:20px" valign="bottom"><a class="false_href" onClick="switch_row(this)"  id='search_href'>Быстрый поиск</a></td>
  </tr>
  <tr id="search_row">
    <td>&nbsp;</td> 
    <td style="height:20px" valign="bottom"><?php
    echo CHtml::textfield('search', NULL, array( 'id'=>'searchp', 'class'=>'seltext', 'autocomplete'=>'off'));
	?></td>
  </tr>
  <?php
}
?>



  <tr>
    <td style="height:56px">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" align="center" valign="top" style="height:28px;"><?php
    echo CHtml::button('resetbut',  array('value'=>'Сбросить','class'=>'whitebut', 'id'=>'resetbut'));
	?>&nbsp;&nbsp;&nbsp;
      <?php
    echo CHtml::Button('go', array('class'=>'gobut', 'value'=>' ', 'id'=>'go_but'));
	?>&nbsp;&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" align="center" valign="bottom" style="height:33px;">&nbsp;</td>
  </tr>
</table>
</form>
</div>
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