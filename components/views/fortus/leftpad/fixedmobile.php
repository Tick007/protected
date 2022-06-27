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
$clientScript->registerCssFile(Yii::app()->request->baseUrl . '/themes/fortusmobile/js/chosen/chosen.css?v='.rand(), 'screen');


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
<br style="clear:both">
<div class="search_form">



<?php
if(isset($krepltype))echo CHtml::hiddenField('krepltype', $krepltype);
if(isset($locktype))echo CHtml::hiddenField('locktype', $locktype);

echo CHtml::hiddenField('return_ajax', 1);

///////////////////Ко

?>

<div class="tab1">

      <?php
//echo CHtml::dropDownList('brand',   $brand, $brand_list, array('id'=>'brand', 'class'=>'sel180', 'tabindex'=>2));
echo CHtml::/*ListBox*/dropDownList('brand',   $brand, $brand_list, array('id'=>'brand', 'class'=>'sel180', 'tabindex'=>2));
?><?php
// echo CHtml::dropDownList('model',   @$model, $model_list, array('id'=>'model', 'class'=>'sel180'));
echo CHtml::/*ListBox*/dropDownList('model',   @$model, $model_list, array('id'=>'model', 'class'=>'sel180'));
?>


<?php 
    //print_r($device_types_list);
    ?>
    <?php
/*		'typs_krepleniya'=>array('kpp'=>'КПП', 'val'=>'Рулевого вала', 'hood'=>'Капота', 'headlamp'=>'Защита фар',
		'sparetire'=>'Запасного колеса', 
		'electromeh'=>'Электромеханические',
		'pinlessval'=>'Бесштыревые замки РВ',
		'controlblock'=>'Защита ЭБУ',*/
    
    //////////////Ваграм попросил переименовать  4. Переименуй в поиске “защита запаски” и “Электромеханика”
    ///////////////Письмо от Романа от 18 января 2019 в 11:47
    /*
     - Механический замок КПП
- Штыревой замок рулевого вала
- Замок капота
- Защита фар
- Защита запаски
- Электромеханический замок КПП
- Бесштыревой замок рулевого вала
- Защита ЭБУ/блока сертификации
     */
    $device_types_list['kpp'] = 'Механический замок КПП';
    $device_types_list['val'] = 'Штыревой замок рулевого вала';
    // $device_types_list['hood'] = 'Замок капота';
    $device_types_list['electromeh'] = 'Электромеханический замок КПП';
    $device_types_list['sparetire'] = 'Защита запасного колеса';
    $device_types_list['controlblock'] = 'Защита ЭБУ/блока сертификации';
    $device_types_list['pinlessval'] = 'Бесштыревой замок рулевого вала';
    $device_types_list['hood'] = 'Замок капота универсальный'; //////По запросу от Романа 14.03.2020
    $device_types_list['hoodmodel'] = 'Замок капота модельный';
    echo CHtml::/*ListBox*/dropDownList('krepltype',   '0', $device_types_list, array('id'=>'krepltype', 'class'=>'sel180', 'tabindex'=>0));
	?>

    <div  class="mrg-top-1">  <?php
    echo CHtml::Button('go', array('class'=>'gobut gobutrad', 'value'=>'НАЙТИ', 'id'=>'go_but'));
	?></div>
    <div class="redseparator"></div>
    <div id="search_answer" > </div>
    
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
  

</div>
<div class="tab2" style="display: none;">



	<div class="mpu_mini_form">
	


	<div>
	<?php
	echo CHtml::textfield ( 'search', NULL, array (
			'id' => 'searchp',
			'class' => 'seltext',
			'placeholder' => 'Поиск инструкции по номеру устройства',
			'autocomplete' => 'off' 
	) );
	?></div>
	<div class="mrg-top-1">
	 <?php
		echo CHtml::Button ( 'go', array (
				'class' => 'gobut',
				'value' => 'НАЙТИ',
				'id' => 'go_but_mpu' 
		) );
		?></div>


		<div class="redseparator"></div>
		<div class="mtl_content" id="search_mtu_answer"></div>
	</div>


</div>
<div class="instructions" style="font-size: 11px; display:none;">
<!-- <a href="/themes/fortus/manuals/hl_razriv.pdf" target="_blank" style="color: #993333;">ИНСТРУКЦИЯ ПО МОНТАЖУ HL  «в разрыв»</a>
<a href="/themes/fortus/manuals/hl_counter_move.pdf" target="_blank" style="color: #993333;float:right">ИНСТРУКЦИЯ ПО МОНТАЖУ HL  «в противоход»</a> <br>
<a href="/themes/fortus/manuals/hl_classic.pdf" target="_blank" style="color: #993333;">ИНСТРУКЦИЯ ПО МОНТАЖУ HL Classic</a>
<a href="/themes/fortus/manuals/hl_classic-mini.pdf" target="_blank" style="color: #993333; float:right">ИНСТРУКЦИЯ ПО МОНТАЖУ HL Classic mini</a>
-->

<div class="unihood_answer">
			<div class="search_cell"><div align="center">
        	<div class="break_hood_lock img"></div></div>
        	<div class="option_descr">
        	<span class="grey">Тип устройства:</span>&nbsp;<span class="strong upper"><?php echo  $device_types_list['hood'] ?></span>
        	</div><br>
        	<div>&bull;&nbsp;HL в разрыв</div><br>
        	<div class="download_link_but" align="center"><a href="/themes/fortus/manuals/hl_razriv.pdf" target="_blank">
        	<div class="dwnlmanual">скачать инструкцию</div></a></div>
        </div>

<div class="search_cell centercell_1"><div align="center">
			<div class="contra_hood_lock img"></div></div>
        	<div class="option_descr">
        	<span class="grey">Тип устройства:</span>&nbsp;<span class="strong upper"><?php echo  $device_types_list['hood'] ?></span>
        	</div><br>
        	<div>&bull;&nbsp;HL в противоход</div><br>
        	<div class="download_link_but" align="center"><a href="/themes/fortus/manuals/hl_counter_move.pdf" target="_blank">
        	<div class="dwnlmanual">скачать инструкцию</div></a></div>
</div>

<div class="search_cell centercell_2"><div align="center">
			<div class="classic_hood_lock img"></div></div>
        	<div class="option_descr">
        	<span class="grey">Тип устройства:</span>&nbsp;<span class="strong upper"><?php echo  $device_types_list['hood'] ?></span>
        	</div><br>
        	<div>&bull;&nbsp;HL Classic</div><br>
        	<div class="download_link_but" align="center"><a href="/themes/fortus/manuals/hl_classic.pdf" target="_blank">
        	<div class="dwnlmanual">скачать инструкцию</div></a></div>
</div>
<div class="search_cell"><div align="center">
			<div class="classic_mini_hood_lock img"></div></div>
        	<div class="option_descr">
        	<span class="grey">Тип устройства:</span>&nbsp;<span class="strong upper"><?php echo  $device_types_list['hood'] ?></span>
        	</div><br>
        	<div>&bull;&nbsp;HL Classic mini</div><br>
        	<div class="download_link_but" align="center"><a href="/themes/fortus/manuals/hl_classic-mini.pdf" target="_blank">
        	<div class="dwnlmanual">скачать инструкцию</div></a></div>
</div>

<div class="search_cell"><div align="center">
			<div class="contra_hood_lock img"></div></div>
        	<div class="option_descr">
        	<span class="grey">Тип устройства:</span>&nbsp;<span class="strong upper"><?php echo  $device_types_list['hood'] ?></span>
        	</div><br>
        	<div>&bull;&nbsp;HL в противоход 103006N</div><br>
        	<div class="download_link_but" align="center"><a href="/themes/fortus/manuals/hl_contra_103006n.pdf" target="_blank">
        	<div class="dwnlmanual">скачать инструкцию</div></a></div>
</div>

</div> <br style="clear: both">


</div> 

</div>
 </div>
</form>
<div style="clear:both; display:none" class="search_contact">*Для заказа обращайтесь к дилеру вашего региона, указанного в разделе <a target="_blank" href="<?php echo Yii::app()->createUrl('site/map')?>">контакты</a></div>

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