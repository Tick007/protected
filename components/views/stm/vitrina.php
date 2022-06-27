<?php
 $clientScript = Yii::app()->clientScript;
$clientScript->registerScriptFile(Yii::app()->request->baseUrl . '/js/slides.min.jquery.js', CClientScript::POS_HEAD);
$clientScript->registerCssFile(Yii::app()->request->baseUrl . '/themes/stm/css/slidestyle.css', CClientScript::POS_HEAD);
?>

<?php

$debug = Yii::app()->getRequest()->getParam('debug');//

if(isset($products)) {
?>


<div id="slides">
   <div class="slides_container">
       
            
 

        <?php
	for ($i=0; $i<count($products); $i++) {
		$k = $i+1;
		
		  if(isset(Yii::app()->params['use_long_urls']) AND Yii::app()->params['use_long_urls']==true) $url=urldecode(Yii::app()->createUrl('product/details' ,array('alias'=>$products[$i]->belong_category->alias, 'path'=>FHtml::urlpath($products[$i]->belong_category->path)  ,  'pd'=>$products[$i]->id) ) );
		  else  $url=urldecode(Yii::app()->createUrl('product/details' ,array( 'pd'=>$products[$i]->id) ) );
		?>
        <div>
		<p><?php
							$iconname = Yii::app()->request->baseUrl."/pictures/add/icons/".$products[$i]->icon_id.'.png';
									//echo $_SERVER['DOCUMENT_ROOT'].$iconname;
									if(isset($debug)) echo $_SERVER['DOCUMENT_ROOT'].$iconname;
									if (file_exists($_SERVER['DOCUMENT_ROOT'].$iconname)==1) echo CHtml::link("<img src=\"$iconname\"  style=\"max-width:150px; max-height:150px\" />", $url);
							?></a>
                            </p>
          <h4><?php
		
		$str_len = strlen(trim($products[$i]->product_name));
		if($str_len>80) $pr_name = mb_substr($products[$i]->product_name, 0, 80, 'utf-8').'...';
		else $pr_name = $products[$i]->product_name;
		echo CHtml::link($pr_name, $url);
	
		?></h4>
         
		</div>
      
   <?php
		}////////for ($i=0; $i<count($products); $i++) {
	  ?>

  </div>
</div>
  <?php
		}
  ?>
 
 
<script>
     $(function(){
  $("#slides").slides({
    preload: true,
    preloadImage: '/img/loading.gif',
    play: 5000,
    pause: 2500,
    hoverPause: true
  });
});
</script>
