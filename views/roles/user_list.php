<div id="ribbon">&nbsp;<?
echo $msg;
?>
</div>
<div id="Right_column" style="background-color:#666E73; width:60px; margin-left:0px">
<?
echo CHtml::link('Роли',array('/roles/list/')); 
?>
</div>

<div id="mainContent" style="padding-left:3px; margin-left:70pxt">
 <h2>Список пользователей</h2>
 <div class="actionBar">
[<?php echo CHtml::link('Назад в список',array('/roles/index/', 'page'=>Yii::app()->getRequest()->getParam('page', NULL) , 'sort'=>Yii::app()->getRequest()->getParam('sort', NULL) )); ?>]
</div>
<?php echo CHtml::beginForm($action='/roles/usersave?page='.Yii::app()->getRequest()->getParam('page', NULL).'&sort='.Yii::app()->getRequest()->getParam('sort', 0), $method='post', $htmlOptions=array('enctype'=>'multipart/form-data')); 
echo CHtml::hiddenField('uid', Yii::app()->getRequest()->getParam('uid') );
echo CHtml::hiddenField('page', Yii::app()->getRequest()->getParam('page') );
echo CHtml::hiddenField('sort', Yii::app()->getRequest()->getParam('sort') );
?>


<?
$tab = new CTabView;
$tab->tabs=array(
    'tab1'=>array(
          'title'=>'Основное',
          'view'=>'ud_main',
         // 'data'=>array('fotos'=>$model->foto, 'fotoTextRus'=>$model->fotoTextRus, 'fotoTextEng'=>$model->fotoTextEng, 'http_srv'=>$http_srv, 'doc_root'=>$doc_root),
		 'data'=>array('user'=>$user, 'roles_list'=>$roles_list),
    ),

    'tab2'=>array(
          'title'=>'Дополнительные поля',
		  'view'=>'ud_addition',
          'data'=>array('profile_values'=>$user->profile_values, 'FIELDS'=>$FIELDS),
    ),
		/*
		'tab3'=>array(
          'title'=>'Отображение в др. группах',
		  'view'=>'category_products',
          'data'=>array('model'=>$model,'model_cat'=>$model_cat),
    ),
	*/
);
if(isset($_POST['del_link']) OR isset($_POST['add_link'] ) ) $tab->activeTab = "tab3";
$tab->run();
?>
<br>
<div align="right">
<?
 echo CHtml::submitButton('Сохранить', $htmlOptions=array ('name'=>'saveuser' ));?>&nbsp;<?
echo CHtml::submitButton('Ок', $htmlOptions=array ('name'=>'save_close_user' , 'alt'=>'Сохранить и закрыть', 'title'=>'Сохранить и закрыть'));
 ?></div>
<?php echo CHtml::endForm();
?>

</div>
