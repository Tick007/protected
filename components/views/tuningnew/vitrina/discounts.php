     <div class="col-sm-4 pro-block">
        <div class="inner-div">
          <h2 class="category-pro-title"><span><?php echo $title?></span></h2>
          <div class="category-products">
           <?php 
              if(isset($products) AND  is_array($products)) {
              ?>
            <div class="products small-list">
               <?php 
                foreach ($products as $id => $product){
                ?>
              <div class="item">
                <div class="item-area">
                  <div class="product-image-area"> <a href="<?php echo Yii::app()->createUrl('catalog/info', array('alias'=>$product['category_alias'], 'id'=>$id))?>" class="product-image"> <img src="<?php echo Yii::app()->baseUrl.'/pictures/add/icons_small/'.$product['icon_id'].'.png'?>" alt="<?php echo $product['name']?>"> </a> </div>
                  <div class="details-area">
                    <h2 class="product-name"><a href="<?php echo Yii::app()->createUrl('catalog/info', array('alias'=>$product['category_alias'], 'id'=>$id))?>"><?php echo $product['name']?></a></h2>
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
                </div>
              </div>
              <?php }
              ?>
            </div>
            <?php 
              }
            ?>
          </div>
        </div>
      </div>