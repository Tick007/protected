          <div class="category-products-main">
           <?php 
              if(isset($products) AND  is_array($products)) {
              ?>

               <?php 
                foreach ($products as $id => $product){
                ?>
              <div>
                <div class="item-area">
                  <div class="product-image-area" style="background-image: url(<?php echo Yii::app()->baseUrl.'/pictures/add/icons/'.$product['icon_id'].'.png'?>)"> <a href="<?php echo Yii::app()->createUrl('catalog/info', array('alias'=>$product['category_alias'], 'id'=>$id))?>" class="product-image">
			
<div class="details-area">
                    <span class="product-name"><?php echo $product['name']?></span>
                    <div class="ratings">
                      <div class="rating-box">
                        <div class="rating"></div>
                      </div>
                    </div>
                    <div class="price-box">
                      <?php if($product['price_old']>0) {?><span class="regular-price"> <span class="price"><?php echo $product['price']?></span> </span> 
                     <?php }?>
                     <span class="rouble2">a</span>
                     </div>
                  </div>

 </a> </div>
                  
                </div>
              </div>
              <?php }
              ?>

            <?php 
              }
            ?>
          </div>
