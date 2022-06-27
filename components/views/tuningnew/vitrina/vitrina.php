 <div class="offer-banner-section">
    <div class="container">
     <?php 
              if(isset($products) AND  is_array($products)) {
              ?>
      <div class="row">
       <?php 
                foreach ($products as $id => $product){
                ?>
                 <?php 
                       if(isset(Yii::app()->params['enable_watermark'])&& Yii::app()->params['enable_watermark']==true){
                       	$imgsrc = Yii::app()->createUrl('imagetools/watermark', array('img'=>$product['icon_id'].'.png', 'f'=>'ai'));
                       }
                       else {
                       	$imgsrc =  Yii::app()->baseUrl.'/pictures/add/icons/'.$product['icon_id'].'.png';
                       }
                       ?>
        <div class="col-lg-4 col-xs-12 col-md-4 col-sm-4 wow"><a href="<?php echo Yii::app()->createUrl('catalog/info', array('alias'=>$product['category_alias'], 'id'=>$id))?>">
<span><img alt="offer banner1" src="<?php echo $imgsrc;?>"></span>
        
        </a></div>
     <?php
                }
     ?>      <div class="col-lg-4 col-xs-12 col-md-4 col-sm-4 wow"><a href="<?php echo Yii::app()->createUrl('catalog/info', array('alias'=>'unp_seats', 'id'=>109))?>">
     
     <img alt="offer banner1" src="<?php echo Yii::app()->theme->baseUrl?>/images/banner.jpg">
             </a></div>
      </div>
      <?php 
              }
      ?>
    </div>
  </div>