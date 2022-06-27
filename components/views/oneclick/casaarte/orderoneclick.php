<?php
$clientScript=Yii::app()->clientScript;
$clientScript->registerCssFile('/themes/'.Yii::app()->theme->name.'/css/oneclick.css');
?>

<div class="button" id="oneclickclick">Купить в 1 клик
</div>
<div class="backfield" id="backfield">
<div align="right" style="margin: -10px -15px 0px -0px; padding-top:-15px; cursor:pointer" onclick="{$('#oneclickclick').click()}"> X </div>
<div style="margin-top:-10px">Пожалуйста, введите 
свой номер телефона 
для связи. </div><br>
Наши сотрудники свяжутся 
с Вами в ближайшее время.
<?php
echo CHtml::textfield('tel', NULL, array('class'=>'ordertel', 'id'=>'ordertel', 'placeholder'=>'+7 (___) ___-____'));
?>
<?php
echo CHtml::button('Отправить заказ',  array('class'=>'oneclickbutton', 'id'=>'sendorder'));
?>
<br><br>
Ваши данные НЕ будут переданы третьим лицам
</div>


<script>
$('#oneclickclick').click(function() {
	toggleoneclick();
});

function toggleoneclick(){
	$('#backfield').toggle();
}

$('#sendorder').click(function() {
	
	
	
	tel = $.trim($('#ordertel').val());
	if(tel!='') {
		var data={
						'tel':tel,
						<?php
						if($product_id!=NULL) echo "'product_id':".$product_id.",";
						?>
					};
					jQuery.ajax({
						'type':'POST',
						'url':'<?php echo Yii::app()->createUrl('cart/oneclickorder')?>',
						'cache':false,
						'async': true,
						//'dataType':'json',
						'data':data,
						'success':function(response){
						if(response.trim()!='') {	
							//alert(response);
							//$(el).val('');
							$('#backfield').removeClass('waitanim');
							$('#backfield').html(response);
							setTimeout("toggleoneclick()", 2000);
							$('#cartamount').html('0');
							$('#cartsum').html('0');
						}
						
						},
						'error':function(response){
							
						//alert(response);
						//$(el).val('');
						}
		});
		
		$('#backfield').html('');
		$('#backfield').addClass('waitanim');
		
	}
	else {
		$('#ordertel').val('');
		$('#ordertel').attr('placeholder', 'ВВЕДИТЕ ТЕЛЕФОН !!!')
	}
});

</script>