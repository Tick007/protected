<?php


$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
    'id'=>'loginpopup',
    // additional javascript options for the dialog plugin
    'options'=>array(
        'title'=>'',
		'dialogClass'=> 'profress_div',
        'autoOpen'=>false,
        'modal'=>true,
		'width'=>390,
		'height'=>215,
    ),
));
?>


<div  style=" float:right; background:url(/themes/enterteh/images/del_cart.png); width:25px;  background-repeat:no-repeat; background-position:right; cursor:pointer; height:21px; display:none" onclick="{
                  $('#mydialog2').dialog('close');
                    }" id="dialog_close">&nbsp; </div>

<div class="yiiForm" align="center">
<?php echo CHtml::beginForm(Yii::app()->createUrl('site/login'),'post', array('class'=>'loginpopupform')); ?>

<?php echo CHtml::errorSummary($form); ?>

<div class="simple">
<?php // echo CHtml::activeLabel($form,'username'); ?>
<?php echo CHtml::activeTextField($form,'username', array('placeholder'=>'Логин*')) ?>
</div>

<div class="simple">
<?php // echo CHtml::activeLabel($form,'password'); ?>
<?php echo CHtml::activePasswordField($form,'password', array('placeholder'=>'Пароль*')) ?>

</div>

<div class="action">
<?php echo CHtml::submitButton('Вход'); ?> 
</div>

<?php echo CHtml::endForm(); 
?>

</div><!-- yiiForm -->




<?php $this->endWidget();



?>
