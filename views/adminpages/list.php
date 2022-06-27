<?
$clientScript=Yii::app()->clientScript;
$clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/highslide/highslide-with-html.js', CClientScript::POS_HEAD);
$clientScript->registerCssFile('/js/highslide/highslide.css');
?>
<script type="text/javascript">
hs.graphicsDir = '/js/highslide/graphics/';
hs.outlineType = 'rounded-white';
hs.wrapperClassName = 'draggable-header';
</script>
<script>
function form_checkout(id, val) {
//document.forms('form1').submit();
document.getElementById('form1').submit();
}
</script>


<div id="Right_column" style="background-color:#666E73; width:60px; margin-left:0px; float:left">
<?
$RC = new RightColumnAdmin;
?>
</div>

<div id="mainContent" style="padding-left:3px; margin-left:70px">

<?

$this->widget('zii.widgets.CBreadcrumbs', array(
    'links'=>array(
        'Администрирование',
		'Статьи',
        
    ),
));

?>

<div class="actionBar" style="height:20px;">
<?
echo CHtml::beginForm('/adminpages/list', 'POST',$htmlOptions=array ('id'=>'form1')); 
echo CHtml::hiddenField('page', Yii::app()->getRequest()->getParam('page', NULL), $htmlOptions=array() );
echo CHtml::dropDownList('section_id', $section_id, $section_data, $htmlOptions=array('encode'=>false, 'onchange'=>"{form_checkout(this.id,this.value)}", ) );

//echo $section_id;
if (isset($section_id) AND $section_id!='0') {/////////////Если выбрана секция, то можно рисовать ссылку создания новой статьи
		?>

            [<?php echo CHtml::link('Новая запись',array('create', 'section_id'=>$section_id)); ?>]

            <hr size="1">
		<?
}
else echo ' Что бы создать запись выберите раздел';


?>
</div>
<br>
<div class="yiiPagerDiv">
<?php $this->widget('CLinkPager',array('pages'=>$pages)); ?></div>

<table class="cat_content_table">
  <tr>
    <th scope="col">Id</th>
    <th scope="col">Name</th>
    <th scope="col">Title</th>
    <th scope="col">Описание</th>
    <th scope="col">Дата</th>
    <th scope="col">Статус</th>
    <th scope="col">Раздел</th>
    <th scope="col">Рубрика</th>
    <th scope="col">Копия</th>
    <th scope="col">Удаление</th>
  </tr>

<?php foreach($models as $n=>$model): ?>
  <tr>
    <td><?php echo CHtml::link($model->id,array('/adminpages/show/', 'id'=>$model->id, 'page'=>Yii::app()->getRequest()->getParam('page', 1)  )); ?></td>
    <td align="left"><?php echo CHtml::link($model->name,array('/adminpages/show/', 'id'=>$model->id, 'page'=>Yii::app()->getRequest()->getParam('page', 1)  )); ?></td>
    <td style="text-align:left" width="200"><?php echo CHtml::link($model->title,array('/adminpages/show/', 'id'=>$model->id, 'page'=>Yii::app()->getRequest()->getParam('page', 1)  )); ?></td>
    <td class="max_narrow_300"><?php  echo $model->short_descr?></td>
    <td><?php echo FHtml::encodeDate($model->creation_date, 'medium');?></td>
    <td><?
       if ($model->active==1) echo '<img src="/images/apply.png" border="0">';
	   else echo '<img src="/images/stop.png" border="0">';
	?></td>
    <td class="max_narrow_300"><?php  echo $model->sections->section?></td>
  <td class="max_narrow_300"><?php
    if(isset($model->rubrics)) echo $model->rubrics->name;
	?></td>
  <td>
  <?php
  echo CHtml::link('<img src="/images/copy.png" border="0">', array('adminpages/copypage', 'id'=>$model->id));
  ?>
  </td>
    <td align="center"><?php echo CHtml::checkBox('del_page['.$model->id.']', 0)?></td>
  </tr>
<?php endforeach; ?>
</table>
<br>
<div align="left"><?
 echo CHtml::submitButton('Применить', $htmlOptions=array ('name'=>'apply', 'alt'=>'Применить', 'title'=>'Применить' ));
 ?></div>
<?php echo CHtml::endForm(); ?>
<br>
<div class="yiiPagerDiv">
<?php $this->widget('CLinkPager',array('pages'=>$pages)); ?></div>

</div>