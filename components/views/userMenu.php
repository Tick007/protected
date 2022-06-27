<?php
$alias = Yii::app()->getRequest()->getParam('alias');
if (isset($models) AND count($models)>0) {
	echo "<ul>";
	for($i=0; $i<count($models); $i++) {
			echo '<li>';
			//if ($alias ==$models[$i]->alias ) echo "<span>".$models[$i]->category_name."</span>";
			//else echo CHtml::link($models[$i]->category_name, array('/catalog/group', 'alias'=>$models[$i]->alias));
			
			if ($alias ==$models[$i]->alias )  echo CHtml::link($models[$i]->category_name, array('/catalog/group', 'alias'=>$models[$i]->alias), array( 'class'=>'active_mml_orange'));
			else echo CHtml::link($models[$i]->category_name, array('/catalog/group', 'alias'=>$models[$i]->alias), array('class'=>'menu_link'));
			
			echo '</li>';
	}//////////for($i=0; $i<count($models); $i++) {
	echo "</ul>";
}///if (isset($models) AND count($models)>0) {
?>