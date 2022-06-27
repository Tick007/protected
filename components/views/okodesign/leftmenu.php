<?php
$alias = Yii::app()->getRequest()->getParam('alias');



if(isset($this->items)) {
	?>
	<ul class="mainmenugroups">
    <?php
	
    foreach( $this->items as $model){
		$model_child_alias = NULL;
		if(isset($model->childs)) for($k=0; $k<count($model->childs); $k++) {
				if($model->childs[$k]->show_category == 1 AND trim($model->childs[$k]->alias) ) $model_child_alias[]=$model->childs[$k]->alias;
		}
		
		?><li
        <?php
        if($model->alias==$alias OR in_array($alias, $model_child_alias)) echo 'class="active"';
		?>
        >
        <?php
        echo CHtml::link(mb_strtoupper($model->category_name, 'utf-8'), array('product/list', 'alias'=>$model->alias));
		
		if(isset($alias) AND $alias==$model->alias AND isset($model->products) ) { 	
			$this->mainmenuproducts($model);
		}
		if(isset($model->childs) AND  ($alias==$model->alias OR in_array($alias, $model_child_alias) ) ) { 
			$this->categorychilds($model);
		}
		?>
    </li>
		<?php
	}
	?>
    </ul>
	<?
}

?>
<div class="whiterib" style="margin-top:10px; margin-bottom:10px;"></div>
<div class="leftinfolinks">
<?php
echo CHtml::link('ЦЕНЫ', array('page/byalias', 'id'=>'pricelist'));
echo '<br>';
echo CHtml::link('ИНФОРМАЦИЯ', array('page/byalias', 'id'=>'info'));
echo '<br>';
echo CHtml::link('КОНТАКТЫ', array('page/byalias', 'id'=>'contacts'));
?>

<div class="whiterib" style="margin-top:10px; margin-bottom:10px;"></div>
E-MAIL:<a href="mailto:design@okodesign.ru">design@okodesign.ru</a><br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="mailto:video@okodesign.ru" style="display:inline-block; margin-left:2px">video@okodesign.ru</a>
<br>
ТЕЛ.: 8 (909) 12398 00

</div>

