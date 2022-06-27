<?php $this->pageTitle=Yii::app()->name . ' - Contact Us'; ?>

<h1>Обратная связь</h1>
<?php

//print_r($_POST);
//print_r($_GET);

?>
<?php if(Yii::app()->user->hasFlash('contact')): ?>
<div class="confirmation">
<?php echo Yii::app()->user->getFlash('contact'); ?>
</div>
<?php else: ?>



<div class="yiiForm">

<?php echo CHtml::beginForm(); ?>

<?php if (isset($_POST['ContactForm']['callback'])==false) echo CHtml::errorSummary($contact); ?>

<div class="simple">
<?php echo CHtml::activeLabel($contact,'name'); ?>
<?php echo CHtml::activeTextField($contact,'name',array('size'=>60,'maxlength'=>128, 'placeholder'=>'Ваше имя')); ?>
</div>
<div class="simple">
<?php echo CHtml::activeLabel($contact,'email'); ?>
<?php echo CHtml::activeTextField($contact,'email',array('size'=>60,'maxlength'=>128, 'placeholder'=>'Ваша почта')); ?>
</div>
<?
//print_r($contact);
?>
<div class="simple">
<?php echo CHtml::activeLabel($contact,'subject'); ?>
<?php echo CHtml::activeTextField($contact,'subject',array('size'=>60,'maxlength'=>128, 'placeholder'=>'Сообщение')); ?>
</div>
<div class="simple">
<label class="error" for="ContactForm_body">Номер телефона</label>
<?php // echo CHtml::activeLabel($contact,'body'); ?>
<?php echo CHtml::activeTextField($contact,'body', array('size'=>60,'maxlength'=>128)); ?>
</div>

<?php if(extension_loaded('gd')): ?> 
<div class="simple">
	<?php echo CHtml::activeLabel($contact,'verifyCode'); ?>
	<div>
		<?php $this->widget('CCaptcha', array('buttonLabel' => iconv("UTF-8", "CP1251", "показать другой код") )) ; ?>
		<?php echo CHtml::activeTextField($contact,'verifyCode'); ?>
&nbsp;&nbsp;&nbsp;&nbsp;<br>Введите символф с картинки.&nbsp;&nbsp;&nbsp;Регистр не учитывается.</div>
	</div>
<?php endif; ?>
<br>
<div class="action" align="center">
<?php echo CHtml::submitButton('Отправить'); ?>
</div>

<?php echo CHtml::endForm(); ?>

</div><!-- yiiForm -->
<?php endif; ?>