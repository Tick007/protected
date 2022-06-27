<?php $this->pageTitle=Yii::app()->name . ' - Тест коннект к БД'; ?>
<h1>Пробую соедениться с БД</h1>
<div class="yiiForm">
<?php echo CHtml::beginForm(); ?>

<?php echo CHtml::errorSummary($form); ?>

<div class="simple">
<?php echo CHtml::activeLabel($form,'Переменная 1'); ?>
<?php echo CHtml::activeTextField($form,'var1') ?>
</div>
</p>
<br/>
<?php echo CHtml::submitButton('Подтвердить'); ?>
<?php echo CHtml::endForm(); ?>
</div>
<?="Hello world !!!";?>