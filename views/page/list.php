<?
$this->breadcrumbs=array(
	'Новости',
);
?>
<script>
function form_checkout(id, val) {
document.forms(0).submit();
}
</script>

<div id="ribbon" style="height:10px">
</div>
<div id="Right_column" style="margin-left:0px;">
    <?
    $LC = new RightColumn(3, 'L');
	?>
</div>
<div id="mainContent" style="padding-left:3px;">

<h2>Новости</h2>
<?php $this->widget('CLinkPager',array('pages'=>$pages)); ?>
<br><br>
<?php foreach($models as $n=>$model): ?>
<div class="item">
<?php
if (isset($model->alais))  echo Chtml::link($model->title, array('/news/'.$model->alais));
	else echo Chtml::link($model->title, array('/news/'.$model->id));
?>
<br/>
<div style="float:left">
<?php echo $model->short_descr; ?>
</div><div style=" text-align:right">
<?php 
//echo CHtml::encode($model->creation_date);
$qqq = explode(' ', $model->creation_date);
$qqq1 = explode('-', $qqq[0]);
echo $qqq1[2].'/'.$qqq1[1].'/'.$qqq1[0];
 ?></div>
</div><div style="clear:both"></div>
<?php endforeach; ?>
<br/>
<?php $this->widget('CLinkPager',array('pages'=>$pages)); ?>
</div>