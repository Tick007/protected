<?php
$clientScript=Yii::app()->clientScript;
$clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/highslide/highslide-with-html.js', CClientScript::POS_HEAD);
$clientScript->registerCssFile(Yii::app()->request->baseUrl.'/js/highslide/highslide.css');
?>
<script type="text/javascript">
hs.graphicsDir = '<?=Yii::app()->request->baseUrl?>/js/highslide/graphics/';
hs.outlineType = 'rounded-white';
hs.wrapperClassName = 'draggable-header';
hs.minWidth = 750;
hs.minHeight = 750;
hs.Width = 750;
hs.Height = 750;
</script>

<script>

function myfunc(kontragent_id){//////////Вызов обновления списка со складами
//alert('This is myfunc()');
jQuery.ajax({'type':'POST','url':'<?=Yii::app()->request->baseUrl?>/nomenklatura/kdetails/','cache':false,'data':'kontragent_id='+kontragent_id,'success':function(html){
//jQuery("#store_doc_ca").html(html)
//alert(html);
document.getElementById('userlist_form').submit();
}});
//alert(kontragent_id);
}

function displaypopup(url){
window.open (url,"mywindow","location=0,status=0,scrollbars=1,width=600,height=600");
}


jQuery('body').delegate('#searchuser','input',function(){jQuery.ajax({'type':'POST','url':'/roles/searchusers/','cache':false,'data':jQuery(this).parents("form").serialize(),'success':function(html){
//jQuery("#table_content").html(html)
//alert (html);
if (html!='n/a') document.getElementById('table_content').innerHTML=html;
//alert('ewwer');
}});return false;});

</script>

<div id="ribbon" style="margin-left:71px">
<?

$this->widget('zii.widgets.CBreadcrumbs', array(
    'links'=>array(
        'Администрирование',
		'Управление пользователями',
        
    ),
));

?>
</div>
<div id="Right_column" style="background-color:#666E73; width:60px; margin-left:0px; float:left">
<?
$RC = new RightColumnAdmin;
?>
</div>

<div id="mainContent" style="margin-left:60px">
 <div class="actionBar">
[<?php echo CHtml::link('Создать пользователя',array(Yii::app()->request->baseUrl.'/roles/createuser/'/*, 'page'=>Yii::app()->getRequest()->getParam('page', NULL) */ )); ?>]
</div><br>
<?php echo CHtml::beginForm($action=Yii::app()->request->baseUrl.'/roles/update?page='.Yii::app()->getRequest()->getParam('page', NULL), $method='post', $htmlOptions=array('enctype'=>'multipart/form-data', 'name'=>'userlist_form', 'id'=>'userlist_form')); 
echo CHtml::hiddenField('sort', Yii::app()->getRequest()->getParam('sort') );
//echo CHtml::hiddenField('page', Yii::app()->getRequest()->getParam('page') );
echo CHtml::textfield('search', NULL, array('placeholder'=>'Поиск', 'id'=>'searchuser'));
?><br>
<?php  $this->widget('CLinkPager',array('pages'=>$pages, 'lastPageLabel'=>'Последняя', 'firstPageLabel'=>'В начало', 'htmlOptions'=>array('class'=>'yiiPager adminpger')) );
?><br>
 <?
 $sort = Yii::app()->getRequest()->getParam('sort');
 $sort  = str_replace('/', '', $sort);
 ?>
 <table width="100%" border="0" cellspacing="1" cellpadding="1">
 <thead>
        <tr bgcolor="#EAE5D8">
    <th><?
	//echo $sort;
	//var_dump($sort=='4');
    if ($sort=='4') echo CHtml::link('id' ,array('/roles/index', 'page'=>Yii::app()->getRequest()->getParam('page', 1), 'sort'=>'4d' ));
	else  echo CHtml::link('id' ,array('/roles/index', 'page'=>Yii::app()->getRequest()->getParam('page', 1), 'sort'=>'4' ));
	?></th>
    <th><?
    echo CHtml::link('Фамилия' ,array('/roles/index?&page='.Yii::app()->getRequest()->getParam('page', 1).'&sort=1'));
	?></th>
    <th><?
	//if (@$sort==2) echo CHtml::link('Имя' ,array('/roles/index?&page='.Yii::app()->getRequest()->getParam('page', 1).'&sort=2d'));
	//else
	   echo CHtml::link('Имя' ,array('/roles/index?&page='.Yii::app()->getRequest()->getParam('page', 1).'&sort=2'));
	?></th>
    <th><?php
     if ($sort=='5') echo CHtml::link('Был' ,array('/roles/index', 'page'=>Yii::app()->getRequest()->getParam('page', 1), 'sort'=>'5d' ));
	  else echo CHtml::link('Был' ,array('/roles/index', 'page'=>Yii::app()->getRequest()->getParam('page', 1), 'sort'=>'5' ));
	?>
    </th>
    <th><?
    echo CHtml::link('Логин' ,array('/roles/index?&page='.Yii::app()->getRequest()->getParam('page', 1).'&sort=3'));
	?>(правка)</th>
    <th>Группа</th>
    <th>Email</th>
    <th>Роль</th>
    <th>Клубная катра</th>
    <th><?
    //echo CHtml::link('Юр. лицо' ,array('/roles/index?&page='.Yii::app()->getRequest()->getParam('page', 1).'&sort=6'));
	?>Юр. лицо
    </th>
    <th>Добавить юрлицо</th>
    <th>Удалить</th>

  </tr>
  </thead> <tbody id="table_content">
  <?php
$this->renderPartial('partialusers', array( 'models'=>$models, 'roles_list'=>$roles_list, 'client_groups'=>@$client_groups));
?>
</tbody>
</table>
<br> <br> 
<div align="right">
<span><?
 echo CHtml::submitButton('Сохранить', $htmlOptions=array ('name'=>'savegood' ));
 ?></span>
 <span>

 </span>
 </div>
<?php echo CHtml::endForm();
?>
<br><br>
<?php
//$rec_num = Yii::app()->getRequest()->getParam('rec_num', $this->get_page_size());
	
$this->widget('CLinkPager',array('pages'=>$pages, 'lastPageLabel'=>'Последняя', 'firstPageLabel'=>'В начало', 'htmlOptions'=>array('class'=>'yiiPager adminpger' ) ) );
?>
<br>
<?php

echo CHtml::dropDownList('rec_num',  Yii::app()->createUrl('roles/index', array('rec_num'=>@$rec_num)), array(Yii::app()->createUrl('nomenklatura/contragentslist', array('rec_num'=>30))=>30, Yii::app()->createUrl('roles/index', array('rec_num'=>50))=>50, Yii::app()->createUrl('roles/index', array('rec_num'=>100))=>100), array('onChange'=>'{
		document.location=$(this).val();
	}'));
?>
</div>
