<?php
$clientScript=Yii::app()->clientScript;
$clientScript->registerCssFile('/themes/'.Yii::app()->theme->name.'/css/oneclick.css');
?>

<div id="callback">Заказать обратный звонок
</div>
<div class="callbackfield" id="callbackfield">
<div align="right" style="margin: -10px -15px 0px -0px; padding-top:-15px; cursor:pointer" onclick="{$('#callback').click()}"> X </div>
<div style="margin-top:-10px">Пожалуйста, введите 
свой номер телефона 
для связи. </div><br>
Наши сотрудники свяжутся 
с Вами в ближайшее время.
<?php
echo CHtml::textfield('tel', NULL, array('class'=>'callbacktel', 'id'=>'callbacktel', 'placeholder'=>'+7 (___) ___-____'));
?>
<?php
echo CHtml::button('Перезвоните мне !',  array('class'=>'oneclickbutton', 'id'=>'sendcallback'));
?>
<br><br>
Ваши данные НЕ будут переданы третьим лицам
</div>


<script>

$("#callbacktel").mask("+7 (999) 999-9999");

$('#callback').click(function() {
	callbackfield();
});

function callbackfield(){
	$('#callbackfield').toggle();
}

$('#sendcallback').click(function() {
	
	
	
	tel = $.trim($('#callbacktel').val());
	if(tel!='') {
		var data={
						'tel':tel,
					};
					jQuery.ajax({
						'type':'POST',
						'url':'<?php echo Yii::app()->createUrl('site/ordercallback')?>',
						'cache':false,
						'async': true,
						//'dataType':'json',
						'data':data,
						'success':function(response){
						if(response.trim()!='') {	
							//alert(response);
							//$(el).val('');
							$('#callbackfield').removeClass('waitanim');
							$('#callbackfield').html(response);
							setTimeout("callbackfield()", 2000);
						}
						
						},
						'error':function(response){
							
						//alert(response);
						//$(el).val('');
						}
		});
		
		$('#callbackfield').html('');
		$('#callbackfield').addClass('waitanim');
		
	}
	else {
		$('#callbacktel').val('');
		$('#callbacktel').attr('placeholder', 'ВВЕДИТЕ ТЕЛЕФОН !!!')
	}
});

</script>