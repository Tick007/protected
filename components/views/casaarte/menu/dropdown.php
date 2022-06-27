<div id="mainnav">
	<div class="menu">
    <ul id="topnav">
    <?php
    if(isset($this->levels[Yii::app()->params['main_tree_root']])) {
	$counter = 1;
	$main_cats = count($this->levels[Yii::app()->params['main_tree_root']]['children']);
	foreach ($this->levels[Yii::app()->params['main_tree_root']]['children'] as $category_id=>$category){
	?>
	<li class="mega">
    <?php 
	//echo '<a class="choice"><span>'.$category['name'].'</span></a>';
		echo CHtml::link('<span>'.$category['name'].'</span>', array('product/list', 'alias'=>$category['alias']), array('class'=>'choice')); 
	 if(isset($this->levels[$category_id])){?>
     	<div class="sub">
            <div class="bgiframe">
                <div class="innersub"><div class="m">
                 <?php
				   $submunu = $this->submenu_items($category_id, 1);
				   echo $submunu;
				?></div><div class="p"><?php
					//$this->promotional($category_id);
				?></div>
                	
                </div><!--<div class="innersub">-->
             </div><!--<div class="bgiframe">-->
        </div>  <!--<div class="sub">-->     
     <?php
     }
     ?>
    </li>
	<?php
	}
	}
	?>
    
				

				</ul>
			
</div>
					</div>