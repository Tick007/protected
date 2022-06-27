<?php
if(isset($groups) && count ($groups)>0){
	
		foreach ($groups as $cat)  if(isset($cat->childs) && count($cat->childs)>0){
?>
                  
                    <li class="level1 nav-6-1 parent item"> <a href="<?php echo Yii::app()->createUrl('catalog/group', array('alias'=> $cat->alias))?>"><span><?php echo $cat->category_name?></span></a> 
                      <!--sub sub category-->
                      <ul class="level1">
                      <?php
					  		foreach($cat->childs as $childcat) {
									if(trim($childcat->alias)!='' AND $childcat->show_category==1){
					  ?>
                        <li class="level2 nav-6-1-1"> <a href="<?php echo Yii::app()->createUrl('catalog/group', array('alias'=> $childcat->alias))?>"><span><?php echo $childcat->category_name?></span></a> </li>
                      
                      <?php
					  				}
					  }
					  ?>
                      </ul>
                      <!--sub sub category--> 
                    </li>
                    <?php
					
                    		}
					?>
                     

                 
                  <div class="nav-add">
                    <div class="push_item1">
                      <div class="push_img"> <a href="#"> <img  alt="women jwellery" src="<?php echo Yii::app()->theme->baseUrl?>/images/banner-menu.jpg"> </a> </div>
                    </div>
                    <div class="push_item1">
                      <div class="push_img"> <a href="#"> <img  alt="women_jwellery" src="<?php echo Yii::app()->theme->baseUrl?>/images/banner-menu.jpg"> </a> </div>
                    </div>
                    <div class="push_item1 push_item1_last">
                      <div class="push_img"> <a href="#"> <img  alt="women_bag" src="<?php echo Yii::app()->theme->baseUrl?>/images/banner-menu.jpg"> </a> </div>
                    </div>
                    <br class="clear">
                  </div>
                </div>
                <!--nav-block nav-block-center-->
                <div class="nav-block nav-block-right std grid12-4">
                  <p><a href="#"><img class="fade-on-hover" src="<?php echo Yii::app()->theme->baseUrl?>/images/banner-menu2.jpg" alt="nav img"></a></p>
                </div>
                <!--nav-block nav-block-right std grid12-4--> 
              </div>
            </div>
          </li>
          <?php
}
?>