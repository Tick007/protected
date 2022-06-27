<?php


$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
    'id'=>'registerpopup',
    // additional javascript options for the dialog plugin
    'options'=>array(
        'title'=>'',
		'dialogClass'=> 'profress_div',
        'autoOpen'=>false,
        'modal'=>true,
		'width'=>390,
		'height'=>630,
    ),
));
?>

<div class="yiiForm" align="center">
<?php echo CHtml::beginForm(Yii::app()->createUrl('site/requestregister'),'post', array('class'=>'loginpopupform')); ?>
<?php echo CHtml::errorSummary($form); ?>

<div class="requestregform">

		<?php
			$labels = $form->attributeLabels();
			foreach($labels as $var_name=>$label_name){
				if(!in_array($var_name, $form->notListHtmlFields())){
				?>
			<div class="vacblock">
	          <?php echo CHtml::activeTextField($form,$var_name,array('size'=>60,'maxlength'=>128, 'class'=>'vactextinp', 'placeholder'=>$form->getAttributeLabel($var_name).'*')); ?>
	        </div>
				<?php 
				}
			}////////endforeach
		?>

		<?php if(extension_loaded('gd')){ ?>                
                    <p class="formfield captchaholder">
						<span class="captcha">
							<var>
<?php $this->widget('CCaptcha', array('buttonLabel'=>iconv("UTF-8", "CP1251", '<br>показать другую<br>картинку'), 'buttonOptions'=>array('encoding'=>true))); ?>
	
</var><br><br>
						</span>
						<span class="inputwrap">
							<?php echo CHtml::activeTextField($form,'verifyCode', array('class'=>'text', 'placeholder'=>'Введите символы с картинки выше*')); ?>
						</span>
					</p>
<?php 
    }
?>    

      	 <div ><?php
       		echo  CHtml::submitButton('werwe', array('class'=>'submit_register_request', 'value'=>'Отправить заявку'));	
        ?></div> 
		

</div>



<?php echo CHtml::endForm(); ?>

</div><!-- yiiForm -->

<?php $this->endWidget();



?>