<?php $this->pageTitle=Yii::app()->name . ' - Первый тест'; ?>
<h1>Добавил элемент меню</h1>
<div class="yiiForm">
<?php echo CHtml::beginForm(); ?>

<?php echo CHtml::errorSummary($form); ?>

<div class="simple">
<?php echo CHtml::activeLabel($form,'Переменная 1'); ?>
<?php echo CHtml::activeTextField($form,'var1') ?>
</div>
</p>
<br/>
<?php echo CHtml::submitButton('Login'); ?>
<?php echo CHtml::endForm(); ?>
</div>
<?="Hello world !!!";?>