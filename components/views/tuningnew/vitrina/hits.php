        <div class="col-lg-8 col-xs-12 col-sm-8 best-seller-pro wow">
            <div class="slider-items-products">
              <div class="new_title center">
                 <h2><?php echo $title?></h2>
              </div>
              <?php 
              if(isset($products) AND  is_array($products)) {
              ?>
              <div id="best-seller-slider" class="product-flexslider hidden-buttons set_align_img">
                <div class="slider-items slider-width-col4"> 
                 <?php 
                foreach ($products as $id => $product){
                ?>
                  <!-- Item -->
                  <div class="item">
                    <div class="col-item">
                    <?php 
                    if($product['product_sellout']==1){
                    ?>
                      <div class="sale-label sale-top-right">SALE</div>
                      <?php 
                    }
                      ?>
                      <div class="images-container"> <a class="product-image" title="Sample Product" href="<?php echo Yii::app()->createUrl('catalog/info', array('alias'=>$product['category_alias'], 'id'=>$id))?>">
                       <span>
                       <?php 
                       if(isset(Yii::app()->params['enable_watermark'])&& Yii::app()->params['enable_watermark']==true){
                       	$imgsrc = Yii::app()->createUrl('imagetools/watermark', array('img'=>$product['icon_id'].'.png', 'f'=>'ai'));
                       }
                       else {
                       	$imgsrc =  Yii::app()->baseUrl.'/pictures/add/icons/'.$product['icon_id'].'.png';
                       }
                       ?>
                       <img src="<?php echo $imgsrc  ?>" class="img-responsive" alt="product-image" />
                      
                       </span>
                        </a>
                        <div class="actions">
                          <div class="actions-inner">
                          
                            <button type="button" title="В корзину" class="button btn-cart"  rel="<?php echo $id?>"><span>В корзину</span></button>
                            <!-- <ul class="add-to-links">
                              <li><a href="wishlist.html" title="В избранное" class="link-wishlist"><span>В избранное</span></a></li>
                              <li><a href="compare.html" title="В сравнение" class="link-compare "><span>Добавить в сравнение</span></a></li>
                            </ul>-->
                          </div>
                        </div>
                        <div class="qv-button-container"> <a href="<?php echo Yii::app()->createUrl('catalog/info', array('alias'=>$product['category_alias'], 'id'=>$id))?>" class="qv-e-button btn-quickview-1"><span><span>Просмотр товара</span></span></a> </div>
                      </div>
                      <div class="info">
                        <div class="info-inner">
                          <div class="item-title"> <a title="Комбинация приборов Gamma GF 825 Лада Нива 4x4" href="l"><?php echo $product['name']?></a> </div>
                          <!--item-title-->
                          <div class="item-content">
                            <div class="ratings">
                              <div class="rating-box">
                                <div style="width:60%" class="rating"></div>
                              </div>
                            </div>
                            <div class="price-box">
                              <p class="special-price"> <span class="price"><?php echo $product['price']?> </span> </p>
                              <?php 
                              if($product['price_old']>0) {
                              ?><p class="old-price"> <span class="price-sep">-</span> <span class="price"><?php echo $product['price_old']?> </span> </p>
                              <?php 
                              }
                              ?><span class="rouble2">a</span>
                            </div>
                          </div>
                          <!--item-content--> 
                        </div>
                        <!--info-inner--> 
                        
                        <!--actions-->
                        <div class="clearfix"> </div>
                      </div>
                    </div>
                  </div>
                  <!-- End Item --> 
                 <?php 
              }
                 ?>
                 
                </div>
              </div>
              <?php 
              }
              ?>
            </div>
          </div>