
<?php

$debug = Yii::app()->getRequest()->getParam('debug');//

if(isset($products)) {
?>

        <?php

	for ($i=0,  $c=count($products); $i<$c; $i++) {
		//echo 'i='.$i.'<br>';
		$fname = $_SERVER['DOCUMENT_ROOT'].'/pictures/add/'.$products[$i]->icon;
	$icon  = '/pictures/add/icons/'.$products[$i]->icon_id.'.png';
	$fname_icon = $_SERVER['DOCUMENT_ROOT'].$icon;
	
	//echo $icon.'<br>';
	
	if(is_file($fname) AND file_exists($fname) AND file_exists($fname_icon) ) {
		$k = $i+1;
		if(isset(Yii::app()->params['use_long_urls']) AND Yii::app()->params['use_long_urls']==true) $url=urldecode(Yii::app()->createUrl('product/details' ,array('alias'=>$products[$i]->belong_category->alias, 'path'=>FHtml::urlpath($products[$i]->belong_category->path)  ,  'pd'=>$products[$i]->id) ) );
		else  $url=urldecode(Yii::app()->createUrl('product/details' ,array( 'pd'=>$products[$i]->id) ) );
		?>
        <div class="vertlist">
        <div class="vertlistimg"><?php
							$iconname = Yii::app()->request->baseUrl."/pictures/add/icons/".$products[$i]->icon_id.'.png';
									//echo $_SERVER['DOCUMENT_ROOT'].$iconname;
									if(isset($debug)) echo $_SERVER['DOCUMENT_ROOT'].$iconname;
									if (file_exists($_SERVER['DOCUMENT_ROOT'].$iconname)==1) echo CHtml::link("<img src=\"$iconname\"  />", $url);
							?></a>
                            </div>


		<div class="vertlist_text">
          <div class="vertlist_name">

		  <?php
		
		$str_len = strlen(trim($products[$i]->product_name));
		if($str_len>60) $pr_name = mb_substr($products[$i]->product_name, 0, 60, 'utf-8').'...';
		else $pr_name = $products[$i]->product_name;
		echo CHtml::link($pr_name, $url);
		
		if($products[$i]->vitrina_key_1==1)$products[$i]->sellout_price = $products[$i]->product_price;
		
		?></div>
        <div class="vertlist_price" align="right"><?php 
         if($products[$i]->vitrina_key_1!=1) {?>
        <div align="right" class="priceregular"><?php
        echo str_replace(',00', '', FHtml::encodeValuta($products[$i]->product_price, ''))?></div><?php 
        }
        else {
        	?><div>&nbsp;</div><?php 
        }
        ?>
        		<span class="pricesellout"><?php echo str_replace(',00', '', FHtml::encodeValuta($products[$i]->sellout_price, ''))?></span><span class="rouble">a</span>
        </div><br style="clear:both"></span>
        </div>
      </div>
   <?php
			}
		}////////for ($i=0; $i<count($products); $i++) {
	  ?>

     

  <?php
		}
  ?>
 