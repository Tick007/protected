<h1>Новый пользователь</h1>

<div class="yiiForm">
<?php echo CHtml::beginForm(); ?>
<?php echo CHtml::errorSummary($form); ?>

<div class="simple">
<?php echo CHtml::activeLabel($form,'newlogin'); ?>
<?php echo CHtml::activeTextField($form,'newlogin') ?>
</div>

<div class="simple">
<?php echo CHtml::activeLabel($form,'client_email'); ?>
<?php echo CHtml::activeTextField($form,'client_email') ?>
</div>

<div class="simple">
<?php echo CHtml::activeLabel($form,'passcode'); ?>
<?php echo CHtml::activePasswordField($form,'passcode') ?>
</div>

<div class="simple">
<?php echo CHtml::activeLabel($form,'passcode2'); ?>
<?php echo CHtml::activePasswordField($form,'passcode2') ?>
</div>

<div class="action">
<?php echo CHtml::submitButton('Регистрация'); ?> 
</div>

<?php echo CHtml::endForm(); ?>

</div><!-- yiiForm -->