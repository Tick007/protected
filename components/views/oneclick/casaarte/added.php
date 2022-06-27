<?php
$clientScript=Yii::app()->clientScript;
$clientScript->registerCssFile('/themes/'.Yii::app()->theme->name.'/css/oneclick.css');



?>


<div class="notify">

<div class="notify_img">
	<img border="0" src="<?php echo $url?>" />
</div>
<div class="notify_txt"><span><?php echo $name?></span><br><?php echo $num?>, добавлен в корзину</div>
<br style="clear:both" />
</div>


<script>
$( document ).ready(function() {
	setTimeout("$('.notify').css('display', 'none')", 3000);
});



</script>