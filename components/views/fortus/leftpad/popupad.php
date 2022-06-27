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
$clientScript->registerScriptFile(Yii::app()->request->baseUrl . '/themes/fortus_new/js/chosen/chosen.jquery.js', CClientScript::POS_HEAD);
$clientScript->registerCssFile(Yii::app()->request->baseUrl . '/themes/fortus_new/js/chosen/chosen.css?v='.rand(), 'screen');


?>
<script>

	
$( document ).ready(function() {


	
	$(".tab_button").on("click", function(){

		
		///////
		if($(this).hasClass('closed')){
			active_button = $(".tab_button.active");
			active_button.removeClass("active");
			active_button.addClass("closed");
	
			//////////
			closed_button = $(this);
			closed_button.removeClass("closed");
			closed_button.addClass("active");

			$('.tab1').toggle();
			$('.tab2').toggle();
		}
	});
	
    
});
			
</script>

<form action="/catalog/search/<?php if(isset($locktype)) echo $locktype?>/<?php if(isset($krepltype)) echo $krepltype;?>" method="get" name="lpform" id="lpform" >


 <div class="b-popup-content">

<div class="tabs_header">
	<div class="tab_button active left"><span>поиск по марке автомобиля</span></div>
	<div class="tab_button closed right"><span>поиск по номеру устройства</span></div>
</div>

				    <div class="b-popup-closer"><a href="#" onClick="{hideManuals(this)}">x</a></div>
						
						<div class="popup_search">



<?php
if(isset($krepltype))echo CHtml::hiddenField('krepltype', $krepltype);
if(isset($locktype))echo CHtml::hiddenField('locktype', $locktype);

echo CHtml::hiddenField('return_ajax', 1);

///////////////////Ко

?>

<div class="tab1">
<table width="570" border="0" cellspacing="0" cellpadding="0" >
  <tr>
    <td width="33%"><?php
    //////////////Ваграм попросил переименовать  4. Переименуй в поиске “защита запаски” и “Электромеханика”
   $device_types_list['electromeh'] = 'Электромеханика';
   $device_types_list['sparetire'] = 'Защита запаски';
 
    echo CHtml::/*ListBox*/dropDownList('krepltype',   'kpp', $device_types_list, array('id'=>'krepltype', 'class'=>'sel180', 'tabindex'=>0));
	?></td>
	<td class="narrow_td_white"></td>
    <td width="33%">
      <?php
//echo CHtml::dropDownList('brand',   $brand, $brand_list, array('id'=>'brand', 'class'=>'sel180', 'tabindex'=>2));
echo CHtml::/*ListBox*/dropDownList('brand',   $brand, $brand_list, array('id'=>'brand', 'class'=>'sel180', 'tabindex'=>2));
?>
      </td>
      <td class="narrow_td_white"></td>
    <td width="33%"><?php
// echo CHtml::dropDownList('model',   @$model, $model_list, array('id'=>'model', 'class'=>'sel180'));
echo CHtml::/*ListBox*/dropDownList('model',   @$model, $model_list, array('id'=>'model', 'class'=>'sel180'));
?></td>
  </tr>
  <tr><td colspan="5" style="height:10px;"></tr>
  <tr id="brand_row" >
    <td valign="top"><?php
	
    echo CHtml::/*ListBox*/dropDownList('year',   @$year, (isset($year_list)?$year_list:array('0'=>'выбор...')), array('id'=>'year', 'class'=>'sel180'));
	?></td>
	<td class="narrow_td_white"></td>
    <td valign="top"><?php
	//print_r($kpp_list);
    echo CHtml::/*ListBox*/dropDownList('kpp',   @$kpp,  (isset($kpp_list)?$kpp_list:array('0'=>'выбор...')), array('id'=>'kpp', 'class'=>'sel180'));
	?></td>
	<td class="narrow_td_white"></td>
    <td valign="top">   <?php
    echo CHtml::Button('go', array('class'=>'gobut gobutrad', 'value'=>'НАЙТИ', 'id'=>'go_but'));
	?></td>
  </tr>
  <tr >
    <td colspan="5" valign="top"><div id="search_answer" align="left"> </div>
    <div align="right">+7 (495) 785-99-00</div>
    </td>
    </tr>
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

  <tr>
    <td colspan="3" valign="bottom"  class="header" style="height:54px;padding-left:2px"">Выбор по номеру МПУ</td>
    </tr>
   <tr>
    <td style="height:20px" valign="bottom"><a class="false_href" onClick="switch_row(this)"  id='search_href'>Быстрый поиск</a></td>
    <td style="height:20px" valign="bottom"> </td>
    <td style="height:20px" valign="bottom"> </td>
   </tr>
  <tr id="search_row">
    <td style="height:20px" valign="bottom"><?php
    echo CHtml::textfield('search', NULL, array( 'id'=>'searchp', 'class'=>'seltext', 'autocomplete'=>'off'));
	?></td>
    <td style="height:20px" valign="bottom"> </td>
    <td style="height:20px" valign="bottom"> </td>
  </tr>
  <?php
}
?>



<!--   <tr>
    <td colspan="3" align="center" valign="top" style="height:28px;"><?php
    echo CHtml::button('resetbut',  array('value'=>'Сбросить','class'=>'whitebut', 'id'=>'resetbut'));
	?>&nbsp;&nbsp;&nbsp;
   &nbsp;&nbsp;</td>
  </tr>-->

</table>
</div>
<div class="tab2" style="display: none;">



	<div class="mpu_mini_form">
	
	<table align="center" width="570" style="margin-left: -1px;" cellpadding="0" cellspacing="1">
<tr>
	<td align="left">
	<?php
	echo CHtml::textfield ( 'search', NULL, array (
			'id' => 'searchp',
			'class' => 'seltext',
			'placeholder' => 'Поиск инструкции по номеру устройства',
			'autocomplete' => 'off' 
	) );
	?></td><td align="left">
	 <?php
		echo CHtml::Button ( 'go', array (
				'class' => 'gobut',
				'value' => 'НАЙТИ',
				'id' => 'go_but_mpu' 
		) );
		?></td>
		</tr>
		</table><br>
		<div class="mtl_content" id="search_mtu_answer"></div>
	</div>


</div>
<div class="instructions" style="font-size: 22px; display:none;">
<a href="/themes/fortus/manuals/hl_razriv.pdf" target="_blank" style="color: #993333;">ИНСТРУКЦИЯ ПО МОНТАЖУ HL  «в разрыв»</a>
<a href="/themes/fortus/manuals/hl_counter_move.pdf" target="_blank" style="color: #993333;float:right">ИНСТРУКЦИЯ ПО МОНТАЖУ HL  «в противоход»</a>
<a href="/themes/fortus/manuals/hl_classic.pdf" target="_blank" style="color: #993333;">ИНСТРУКЦИЯ ПО МОНТАЖУ HL Classic</a>
<a href="/themes/fortus/manuals/hl_classic-mini.pdf" target="_blank" style="color: #993333; float:right">ИНСТРУКЦИЯ ПО МОНТАЖУ HL Classic mini</a>
</div>
</div>

	
						
				    </div
				   <br/>

</form>

<script>
jQuery(document).ready(function(){
	
/*
	$('searchp').keypress(function(event) {
	    if (event.keyCode == 13) {
	        event.preventDefault();
	    }
	});
	*/
	 $("#searchp").keypress(function(event) {
         if (event.keyCode == 13) {
             event.preventDefault();
             return false;
         }
     });

	
	
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