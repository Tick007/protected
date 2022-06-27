<?php
$clientScript=Yii::app()->clientScript;
$clientScript->registerCssFile('/themes/'.Yii::app()->theme->name.'/css/oneclick.css#234');



?>


<div class="notify">


<div class="notify_txt"><span><?php echo $name?></span><br><?php echo $num?>, добавлен в корзину</div>
<br style="clear:both" />

<div style="text-align:center"><a href="/cart" class="bt3">Оформить заказ</a>&nbsp;&nbsp;&nbsp;&nbsp;
<a href="javascript:void(0)"  style="margin-left: 10px;font-size: 14px;color: #888;" onclick="{$('.notify').css('display', 'none')}">Продолжить покупки</a>
</div>
</div>


<script>
$( document ).ready(function() {
	setTimeout("$('.notify').css('display', 'none')", 3000);
});



</script>