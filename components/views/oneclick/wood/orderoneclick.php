<?php
$clientScript=Yii::app()->clientScript;
$clientScript->registerCssFile('/themes/'.Yii::app()->theme->name.'/css/oneclick.css');
?>

<div class="button" id="oneclickclick">Купить в 1 клик
</div>
<div class="backfield" id="backfield">
<div align="right" style="margin: -10px -15px 0px -0px; padding-top:-15px; cursor:pointer" onclick="{$('#oneclickclick').click()}"> X </div>
<div style="margin-top:-10px">Для отправки сообщения введите 
свой email</div><br>

<?php
echo CHtml::textfield('tel', NULL, array('class'=>'ordertel', 'id'=>'ordertel', 'placeholder'=>'+7 (___) ___-____'));
?>
<?php
echo CHtml::textfield('name', NULL, array('class'=>'ordertel', 'id'=>'ordername', 'placeholder'=>'Ваше имя'));
?>
<?php
echo CHtml::textfield('city', NULL, array('class'=>'ordertel', 'id'=>'ordercity', 'placeholder'=>'Введите город'));
?>

<?php
echo CHtml::textfield('email', NULL, array('class'=>'ordertel orderereq' , 'id'=>'ordermail', 'placeholder'=>'Введите email'));
?>

<?php
echo CHtml::button('Отправить заказ',  array('class'=>'oneclickbutton', 'id'=>'sendorder'));
?><br>
Наши сотрудники свяжутся 
с Вами в ближайшее время.

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
	email = $.trim($('#ordermail').val());
	city= $.trim($('#ordercity').val());
	name = $.trim($('#ordername').val());
	//if(tel!='') {
		var data={
						'tel':tel,
						'email':email,
						'name':name,
						'city':city,
						<?php
						if($product_id!=NULL) echo "'product_id':".$product_id.",";
						?>
					};
					jQuery.ajax({
						'type':'POST',
						'url':'<?php echo Yii::app()->createUrl('cart/oneclickordersimple')?>',
						'cache':false,
						'async': true,
						//'dataType':'json',
						'data':data,
						'success':function(response){
						if(response.trim()!='') {	
							//alert(response);
							//$(el).val('');
							$('#backfield').html('')
							$('#backfield').css('display', 'block');
							$('#backfield').removeClass('waitanim');
							$('#backfield').html(response);
							setTimeout("toggleoneclick()", 4000);
							$('#cartamount').html('0');
							$('#cartsum').html('0');
						}
						
						},
						'error': function(xhr, status, error) {
							$('#backfield').css('display', 'block');
							$('#backfield').removeClass('waitanim');
						  //alert(xhr.responseText);
						}
		});
		
		
		$('#backfield').css('display', 'none');
		$('#backfield').addClass('waitanim');
		/*
	}
	else {
		$('#ordertel').val('');
		$('#ordertel').attr('placeholder', 'ВВЕДИТЕ ТЕЛЕФОН !!!')
	}
	*/
});

</script>