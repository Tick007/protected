<h2>Просмотр темы <?php echo $model->id; ?></h2>

<div class="actionBar">
[<?php echo CHtml::link('Список тем',array('list')); ?>]
[<?php echo CHtml::link('Новая тема',array('create')); ?>]
[<?php echo CHtml::link('Обновить тему',array('update','id'=>$model->id)); ?>]
[<?php echo CHtml::linkButton('Удалеить тему',array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure?')); ?>
]
[<?php echo CHtml::link('Управление темами',array('admin')); ?>]
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
