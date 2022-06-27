<?
echo CHtml::beginForm(array('/adminreports/sales/'),  $method='post',$htmlOptions=array('name'=>'report_form', 'id'=>'report_form'));  
?>
<div id="ribbon">&nbsp;</div>

<div id="Right_column" style="background-color:#666E73; width:60px; margin-left:0px; float:left">
<?
$RC = new RightColumnAdmin;
?>
</div>
<div id="mainContent" style="padding-left:3px; margin-left:70px ">
 <?
//print_r($characterictics);
$sgrouplist[0]='все';
for($i=0; $i < count($maingroups); $i++) $sgrouplist[$maingroups[$i]->category_id]=$maingroups[$i]->category_name;

$grouplist[0]='все';
for($i=0; $i < count($subgroups); $i++) $grouplist[$subgroups[$i]->category_id]=$subgroups[$i]->category_name;

$tab = new CTabView;
	$tab->tabs=array(
    'tab1'=>array(
          'title'=>'Отбор',
          'view'=>'sales_setup',
          'data'=>array('sgrouplist'=>$sgrouplist, 'grouplist'=>$grouplist,  'parametrs'=>$parametrs, 'presets'=>$presets),
	 	 ),
	'tab2'=>array(
          'title'=>'Результат',
		  'view'=>'sales_report',
          'data'=>array('rows'=>$rows, 'parametrs'=>$parametrs),	  
		),
	);
	
/*
$tab->tabs['tab2']=array(
          'title'=>'Результат',
		  'view'=>'movement_report',
          'data'=>array('characterictics'=>$characterictics),
    );
*/
$build = Yii::app()->getRequest()->getParam('build', NULL);	
if(isset($build)  ) $tab->activeTab = 'tab2';
$tab->run();


?>

</div>
<!--<div id="mainContent" style="padding-left:3px; ">-->


<div style="height: 5px; clear:both">&nbsp;</div>
<?php echo CHtml::endForm(); ?>

