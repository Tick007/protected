
<ul id="css3menu6" class="topmenu" style="">

<?php
//print_r($this->levels[Yii::app()->params['main_tree_root']]);

$left_pointer=array(348);

if(isset($this->levels[Yii::app()->params['main_tree_root']])) {
	$counter = 1;
	$main_cats = count($this->levels[Yii::app()->params['main_tree_root']]['children']);
	foreach ($this->levels[Yii::app()->params['main_tree_root']]['children'] as $category_id=>$category){
		//echo $category_id;
		?><li <?php
        if($counter==1) echo 'class="topfirst" ';
		if($counter==$main_cats) echo 'class="toplast" ';
		else echo 'class="topmenu" ';
		echo "id=topli_".$category_id;
		?>>
		<?php
		//$category['name'] = str_replace(' ', '<br>', $category['name']);
		
		//echo CHtml::link($this->category_icon($category_id,$category['name'] ).$category['name'], array('product/list', 'alias'=>$category['alias']));
		echo CHtml::link($category['name'], array('product/list', 'alias'=>$category['alias']), array('style'=>'height:70px'));
        if(isset($this->levels[$category_id])){?>
		<div class="submenu" id="sub<?php echo $category_id?>" >
        <div class="pointer" style="left:<?php
       // echo 150 + $counter*55;
		echo 160;
		?>px;"></div>
        <?php
       $submunu = $this->submenu_items($category_id, 1);
	   echo $submunu;
		?>
        </div>
		<?php }
		?>
		</li><?php
	$counter++;	
	}/////////foreach ($this->tree as $category_id=>$category){

}
?>

<li class="topmenu" style="background: url(/themes/enterteh_nofoto/img/verh/verh_06_new.png) no-repeat -560px 0px;
width: 50px !important;">
		<?php echo CHtml::link('Доставка', array('page/byalias', 'id'=>'delivery'), array('style'=>'height:70px'))?>	
</li>
<li class="topmenu" style="background: url(/themes/enterteh_nofoto/img/verh/verh_06_new.png) no-repeat -616px 0px;
width: 58px !important;">
		<?php echo CHtml::link('Контакты', array('site/contact'), array('style'=>'height:70px'))?>	
</li>
<li class="topmenu" style="background: url(/themes/enterteh_nofoto/img/verh/verh_06_new.png) no-repeat -675px 0px;
width: 50px !important;">
		<?php echo CHtml::link('Акции', array('page/byalias', 'id'=>'aktsia'), array('style'=>'height:70px'))?>
</li>
</ul>
