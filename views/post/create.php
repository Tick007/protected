<h2>����� ����</h2>

<div class="actionBar">
[<?php echo CHtml::link('������ ���',array('list')); ?>]
[<?php echo CHtml::link('���������� ������',array('admin')); ?>]
</div>

<?php echo $this->renderPartial('_form', array(
	'model'=>$model,
	'update'=>false,
)); ?>