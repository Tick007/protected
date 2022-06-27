<div class="yiiForm">

<p>
Fields with <span class="required">*</span> are required.
</p>

<?php echo CHtml::beginForm(); ?>

<?php echo CHtml::errorSummary($model); ?>

<div class="simple">
<?php echo CHtml::activeLabelEx($model,'title'); ?>
<?php echo CHtml::activeTextField($model,'title',array('size'=>60,'maxlength'=>128)); ?>
</div>
<div class="simple">
<?php echo CHtml::activeLabelEx($model,'content'); ?>
<?php echo CHtml::activeTextArea($model,'content',array('cols'=>45, 'rows'=>6)); ?>
</div>
<div class="simple">
<?php echo CHtml::activeLabelEx($model,'createTime'); ?>
<?php echo CHtml::activeTextField($model,'createTime',array('size'=>60,'createTime'=>128)); ?>
</div>

<div class="action">
<?php echo CHtml::submitButton($update ? 'Save' : 'Create'); ?>
</div>

<?php echo CHtml::endForm(); ?>

</div><!-- yiiForm -->