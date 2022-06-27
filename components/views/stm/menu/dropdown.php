
<?php
//print_r($this->levels[Yii::app()->params['main_tree_root']]);

$left_pointer=array(348);

if(isset($this->levels[Yii::app()->params['main_tree_root']])) {
	$counter = 1;
	$main_cats = count($this->levels[Yii::app()->params['main_tree_root']]['children']);
	foreach ($this->levels[Yii::app()->params['main_tree_root']]['children'] as $category_id=>$category){
		?>
        <td class="b-header-nav-section"> 
        
		<div class="b-header-nav-section-h"><?php echo CHtml::link($category['name'], array('product/list', 'alias'=>$category['alias']), array('class'=>'b-header-nav-anchor'));?>
        <?php
                                      if(isset($this->levels[$category_id])){
									?>
        		<div class="_b-header-nav-dropdown side_left">
                                <div class="_b-header-nav-dropdown-body">
                                    <div class="_b-header-nav-dropdown-body-h"><div class="_b-header-nav-dropdown-col">
                                    
                                    <div class="_b-header-nav-dropdown-category">
                                
                                        
                                         <?php
										   $submunu = $this->submenu_items($category_id, 1);
										   echo $submunu;
										?>
                                   
                                    <!--
                                    <div class="_b-header-nav-dropdown-category">
                                    	<a href="/category/bluetooth_garnityra.html">Bluetooth гарнитуры</a>
                                   </div>
                                   <div class="_b-header-nav-dropdown-category">
                                   		<a href="/category/radiotelefony.html">Радиотелефоны</a>
                                   </div>-->
                               </div>
                               
                          </div>
                     </div>
               </div>
                            <?php
									  }////////////if(isset($this->levels[$category_id])){
									?>
        </div><!--<div class="b-header-nav-section-h">-->
        </td>
		<?php
	$counter++;	
	}/////////foreach ($this->tree as $category_id=>$category){

}
?>


