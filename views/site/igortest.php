<?php $this->pageTitle=Yii::app()->name . ' - ������ ����'; ?>
<h1>������� ������� ����</h1>
<div class="yiiForm">
<?php echo CHtml::beginForm(); ?>

<?php echo CHtml::errorSummary($form); ?>

<div class="simple">
<?php echo CHtml::activeLabel($form,'���������� 1'); ?>
<?php echo CHtml::activeTextField($form,'var1') ?>
</div>
</p>
<br/>
<?php echo CHtml::submitButton('Login'); ?>
<?php echo CHtml::endForm(); ?>
</div>
<?="Hello world !!!";?>