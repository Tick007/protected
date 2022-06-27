<div id="Right_column" style="background-color:#666E73; width:60px; margin-left:0px; float:left">
<?
//$RC = new RightColumnAdmin;
?>
</div>
<?php echo CHtml::beginForm($action='/roles/usersave?page='.Yii::app()->getRequest()->getParam('page', NULL).'&sort='.Yii::app()->getRequest()->getParam('sort', 0), $method='post', $htmlOptions=array('enctype'=>'multipart/form-data')); 
echo CHtml::hiddenField('uid', Yii::app()->getRequest()->getParam('id') );
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
		 'data'=>array('user'=>$user, 'roles_list'=>$roles_list,'form'=>$form),
    ),
/*
    'tab2'=>array(
          'title'=>'Дополнительные поля',
		  'view'=>'ud_addition',
          'data'=>array('profile_values'=>$user->profile_values, 'FIELDS'=>$FIELDS),
    ),
*/
		
		'tab3'=>array(
          'title'=>'Заказы',
		  'view'=>'orders_list',
          'data'=>array( 'models'=>$orders_list),
		
				
				
    ),
    
    'tab4'=>array(
    		'title'=>'Клуб PSG',
    		'view'=>'club',
    		'data'=>array( 'user'=>$user),
    ),
	
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

