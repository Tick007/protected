<?php
 $clientScript = Yii::app()->clientScript;
$clientScript->registerScriptFile(Yii::app()->request->baseUrl . Yii::app()->theme->baseUrl.'/js/jquery.jcarousel.js', CClientScript::POS_HEAD);
//$clientScript->registerCssFile(Yii::app()->request->baseUrl . '/themes/enterteh/css/jcarousel.css', CClientScript::POS_HEAD);
$clientScript->registerCssFile(Yii::app()->request->baseUrl .Yii::app()->theme->baseUrl.'/css/type/puritan.css', CClientScript::POS_HEAD);
?>
<link href="<?php echo 'http://'.$_SERVER['HTTP_HOST'].Yii::app()->theme->baseUrl; ?>/css/jcarousel.css" rel="stylesheet" type="text/css" >
<script type="text/javascript">
jQuery(document).ready(function() {
    // Initialise the first and second carousel by class selector.
	// Note that they use both the same configuration options (none in this case).
	jQuery('.d-carousel .carousel').jcarousel({
        scroll: 1
    });
});
</script>
<?php

$debug = Yii::app()->getRequest()->getParam('debug');//

if(isset($products)) {
?>
<div class="leftpanelblock2" align="center">
      <div class="blockheader2"><?php echo $title;?></div>

  
 <!-- Begin Wrapper -->
  <div id="wrapper">
  
    <div class="d-carousel">
 
      <ul class="carousel">
        <?php
	for ($i=0; $i<count($products); $i++) {
		$k = $i+1;
		
		  if(isset(Yii::app()->params['use_long_urls']) AND Yii::app()->params['use_long_urls']==true) $url=urldecode(Yii::app()->createUrl('product/details' ,array('alias'=>$products[$i]->belong_category->alias, 'path'=>FHtml::urlpath($products[$i]->belong_category->path)  ,  'pd'=>$products[$i]->id) ) );
		  else  $url=urldecode(Yii::app()->createUrl('product/details' ,array( 'pd'=>$products[$i]->id) ) );
		?>
        <li><div style="height:150px; overflow:hidden; width:144px;"><?php
							$iconname = Yii::app()->request->baseUrl."/pictures/add/icons/".$products[$i]->icon_id.'.png';
									//echo $_SERVER['DOCUMENT_ROOT'].$iconname;
									if(isset($debug)) echo $_SERVER['DOCUMENT_ROOT'].$iconname;
									if (file_exists($_SERVER['DOCUMENT_ROOT'].$iconname)==1) echo CHtml::link("<img src=\"$iconname\"  style=\"max-width:150px; max-height:150px\" />", $url);
							?></a>
                            </div>
          <h4><?php
		
		$str_len = strlen(trim($products[$i]->product_name));
		if($str_len>80) $pr_name = mb_substr($products[$i]->product_name, 0, 80, 'utf-8').'...';
		else $pr_name = $products[$i]->product_name;
		echo CHtml::link($pr_name, $url);
	
		?></h4>
         
        </li>
      
   <?php
		}////////for ($i=0; $i<count($products); $i++) {
	  ?>
      </ul>
     
    </div>
     </div>  
  

 </div>  
  <?php
		}
  ?>
 