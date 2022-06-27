



<?php if(Yii::app()->user->hasFlash('contact')): ?>

<div class="flash-success">
	<?php echo Yii::app()->user->getFlash('contact'); ?>
</div>

<?php else: ?>



<div class="form" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:x-small">
<p> Пожалуйста заполните поля приведенные ниже.
</p>
<?php $form=$this->beginWidget('CActiveForm'); ?>

<!--	<p class="note">Поля со <span class="required">* </span>обязательны к заполнению.</p>-->
<?
echo Chtml::hiddenfield('ContactForm[nid]', $nid);
?>
	<?php echo $form->errorSummary($model); ?>

	<div class="row"><div style="width:70px; float:left">
		<?php echo $form->labelEx($model,'name'); ?></div><div>
		<?php echo $form->textField($model,'name'); ?></div>
	</div>

	<div class="row">
    <div style="width:70px; float:left">
		<?php echo $form->labelEx($model,'email'); ?></div>
		<?php echo $form->textField($model,'email'); ?>
	</div>

	<div class="row">
		<div style="width:70px; float:left"><?php echo $form->labelEx($model,'subject' ); ?></div>
		<?php echo $form->textField($model,'subject',array('size'=>40,'maxlength'=>128)); ?>
	</div>

	<div class="row"><div style="width:70px; float:left">
		<?php echo $form->labelEx($model,'body'); ?></div>
		<?php echo $form->textArea($model,'body',array('rows'=>8, 'cols'=>50)); ?>
	</div>

	<?php if(extension_loaded('gd')): ?>
	<div class="row">
		<?php //echo $form->labelEx($model,'verifyCode'); ?>
		<div>
		<?php // $this->widget('CCaptcha'); ?>
		<?php // echo $form->textField($model,'verifyCode'); ?>
		</div>

	</div>
	<?php endif; ?>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Отправить'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->

<?php endif; ?>