<h2>�������� ���� <?php echo $model->id; ?></h2>

<div class="actionBar">
[<?php echo CHtml::link('������ ���',array('list')); ?>]
[<?php echo CHtml::link('����� ����',array('create')); ?>]
[<?php echo CHtml::link('�������� ����',array('update','id'=>$model->id)); ?>]
[<?php echo CHtml::linkButton('�������� ����',array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure?')); ?>
]
[<?php echo CHtml::link('���������� ������',array('admin')); ?>]
</div>

<table class="dataGrid" border="1">
<tr>
	<th class="label"><?php echo CHtml::encode($model->getAttributeLabel('content')); ?>
</th>
    <td><?php echo CHtml::encode($model->content); ?>
</td>
</tr>
<tr>
	<th class="label"><?php echo CHtml::encode($model->getAttributeLabel('title')); ?>
</th>
    <td><?php echo CHtml::encode($model->title); ?>
</td>
</tr>
<tr>
	<th class="label"><?php echo CHtml::encode($model->getAttributeLabel('createTime')); ?>
</th>
    <td><?php echo CHtml::encode($model->createTime); ?>
</td>
</tr>
</table>
