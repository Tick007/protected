<?php
	
?>
<ul id="vertical_menu">
<?
for($i=0; $i<count($models); $i++) {
	
	 if(isset(Yii::app()->params['use_long_urls']) AND Yii::app()->params['use_long_urls']==true)  $url=urldecode(Yii::app()->createUrl('product/list' ,array('alias'=>$models[$i]->alias, 'path'=>FHtml::urlpath($models[$i]->path) ) ) );
	else {
		if(trim($models[$i]->alias)=='') $url=urldecode(Yii::app()->createUrl('product/list' ,array('id'=>$models[$i]->category_id) ) );
		else $url=urldecode(Yii::app()->createUrl('product/list' ,array('alias'=>$models[$i]->alias) ) );
	}
	
		echo '<li>';
		//echo CHtml::link($models[$i]->category_name, array('/product/'.$models[$i]->category_id), array('class'=>'parent'));
		if(isset($models[$i]->childs)) {
		if (count($models[$i]->childs)>0) {
		echo CHtml::link($models[$i]->category_name, $url, array('class'=>'parent'));
			//echo count($models[$i]->childs);
			
			for($k=0; $k<count($models[$i]->childs); $k++) {
				if($models[$i]->childs[$k]->show_category==1) $childs[$models[$i]->childs[$k]->category_id] = $models[$i]->childs[$k];
			}
			if(count($childs)>0) {
					
					//print_r($childs);
					echo '<ul>';
						//for($k=0; $k<count($models[$i]->childs); $k++) {/
						foreach($childs AS $key=>$val):
						
				if(isset(Yii::app()->params['use_long_urls']) AND Yii::app()->params['use_long_urls']==true)  $url1=urldecode(Yii::app()->createUrl('product/list' ,array('alias'=>$val->alias, 'path'=>FHtml::urlpath($val->path) ) ) );
				else {
					if(trim($val->alias)=='') $url1=urldecode(Yii::app()->createUrl('product/list' ,array('id'=>$val->category_id) ) );
					else $url1=urldecode(Yii::app()->createUrl('product/list' ,array('alias'=>$val->alias) ) );
				}
						
								echo '<li>';
								echo CHtml::link($val->category_name, $url1);
								////////Смотрим количество позиций 3го уровня//////////
								check_level_tree($key);
								echo '</li>';
						//}////////////$models[$i]->childs
						endforeach;
						echo '</ul>';
				}/////////if(count($childs)>0) {
			$childs=NULL;
		
		}////////////if (count($models[$i]->childs)>0) {
		else echo CHtml::link($models[$i]->category_name, array('/product/list', 'id'=>$models[$i]->category_id));
				
				
		}////////////if(isset($models[$i]->childs)) {
		echo '</li>';
}/////////////////for($i=0; $i<count($models); $i++) {
?>
</ul>
<?php



function check_level_tree($id){////////////Проверка на уровне 3
		$criteria=new CDbCriteria;
		$criteria->order = ' t.category_name, childs.category_name';
		$criteria->condition = " t.parent= $id AND t.show_category = 1 ";
		$criteria->order = "t.sort_category, childs.sort_category";
		
		$models = Catalog::model()->with('childs')->findAll($criteria);//
		
		if (count($models)>0) {////////////Если найдено на 3м уровне
				echo "<ul>";
					for($i=0; $i<count($models); $i++) {
						
					 if(isset(Yii::app()->params['use_long_urls']) AND Yii::app()->params['use_long_urls']==true)  $url=urldecode(Yii::app()->createUrl('product/list' ,array('alias'=>$models[$i]->alias, 'path'=>FHtml::urlpath($models[$i]->path) ) ) );
					else {
						if(trim($models[$i]->alias)=='') $url=urldecode(Yii::app()->createUrl('product/list' ,array('id'=>$models[$i]->category_id) ) );
						else $url=urldecode(Yii::app()->createUrl('product/list' ,array('alias'=>$models[$i]->alias) ) );
					}
						
					echo '<li>';
					echo CHtml::link($models[$i]->category_name,$url, array('class'=>'parent'));
					echo '</li>';
					}////////////for($i=0; $i<count($models); $i++) {							
				echo "</ul>";
		}/////////////////////if (count($models)>0) {////////////Если найдено на 3м уровне
		
}//////////////////////////////////////////function check_level_tree($id){

?>
