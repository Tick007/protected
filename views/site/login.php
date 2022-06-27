<?php $this->pageTitle=Yii::app()->name . ' - Login'; ?>

<h1>Вход</h1>

<div class="yiiForm">
<?php echo CHtml::beginForm(); ?>

<?php echo CHtml::errorSummary($form); ?>

<div class="simple">
<?php echo CHtml::activeLabel($form,'username'); ?>
<?php echo CHtml::activeTextField($form,'username') ?>
</div>

<div class="simple">
<?php echo CHtml::activeLabel($form,'password'); ?>
<?php echo CHtml::activePasswordField($form,'password') ?>

</div>

<div class="action">
<?php echo CHtml::activeCheckBox($form,'rememberMe'); ?>
<?php echo CHtml::activeLabel($form,'rememberMe'); ?>
<br/>
<?php echo CHtml::submitButton('Вход'); ?> 
</div>

<?php echo CHtml::endForm(); 
?>
<br>
<a href="/site/register">регистрация</a>
</div><!-- yiiForm -->

