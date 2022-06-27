<h2>Новая тема</h2>

<div class="actionBar">
[<?php echo CHtml::link('Список тем',array('list')); ?>]
[<?php echo CHtml::link('Управление темами',array('admin')); ?>]
</div>

<?php echo $this->renderPartial('_form', array(
	'model'=>$model,
	'update'=>false,
)); ?>