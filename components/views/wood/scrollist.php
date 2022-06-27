<?php
 //$clientScript = Yii::app()->clientScript;
 //$clientScript->registerCssFile(Yii::app()->request->baseUrl .'/themes/'.Yii::app()->theme->name. '/css/jcarousel.css', CClientScript::POS_HEAD); /////////////в site index
//$clientScript->registerScriptFile(Yii::app()->request->baseUrl . '/themes/casaarte/js/jquery.jcarousel.js', CClientScript::POS_HEAD);
//$clientScript->registerCssFile(Yii::app()->request->baseUrl . '/themes/casaarte/css/type/puritan.css', CClientScript::POS_HEAD);

?>


<script type="text/javascript">
jQuery(document).ready(function() {
    // Initialise the first and second carousel by class selector.
	// Note that they use both the same configuration options (none in this case).
	jQuery('.d-carousel .carousel').jcarousel({
        scroll: 4,

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

	for ($i=0,  $c=count($products); $i<$c; $i++) {
		
		$fname = $_SERVER['DOCUMENT_ROOT'].'/pictures/add/'.$products[$i]->icon;
	$icon  = '/pictures/add/icons/'.$products[$i]->icon_id.'.png';
	$fname_icon = $_SERVER['DOCUMENT_ROOT'].$icon;
	
	//echo $icon.'<br>';
	
	if(is_file($fname) AND file_exists($fname) AND file_exists($fname_icon) ) {
		$k = $i+1;
		if(isset(Yii::app()->params['use_long_urls']) AND Yii::app()->params['use_long_urls']==true) $url=urldecode(Yii::app()->createUrl('product/details' ,array('alias'=>$products[$i]->belong_category->alias, 'path'=>FHtml::urlpath($products[$i]->belong_category->path)  ,  'pd'=>$products[$i]->id) ) );
		else  $url=urldecode(Yii::app()->createUrl('product/details' ,array( 'pd'=>$products[$i]->id) ) );
		?>
        <li><div style="height:130px"><?php
							$iconname = Yii::app()->request->baseUrl."/pictures/add/icons/".$products[$i]->icon_id.'.png';
									//echo $_SERVER['DOCUMENT_ROOT'].$iconname;
									if(isset($debug)) echo $_SERVER['DOCUMENT_ROOT'].$iconname;
									if (file_exists($_SERVER['DOCUMENT_ROOT'].$iconname)==1) echo CHtml::link("<img src=\"$iconname\" class=\"carosel_image110\" />", $url);
							?></a>
                            </div>

          <div class="carousel_pname">
		  <div class="carousel_pname_poloska"></div>
		  <?php
		
		$str_len = strlen(trim($products[$i]->product_name));
		if($str_len>180) $pr_name = mb_substr($products[$i]->product_name, 0, 60, 'utf-8').'...';
		else $pr_name = $products[$i]->product_name;
		echo CHtml::link($pr_name, $url);
	
		?></div><del><?php echo str_replace(',00', '', FHtml::encodeValuta($products[$i]->product_price, ''))?></del>
        <div class="carousel_pprice">
        		<span><?php echo str_replace(',00', '', FHtml::encodeValuta($products[$i]->sellout_price, ''))?></span><span class="rouble2">Р</span>&nbsp;&nbsp;&nbsp;
        </div>
        </li>
      
   <?php
			}
		}////////for ($i=0; $i<count($products); $i++) {
	  ?>
      </ul>
     
    </div>
     </div>  
  

 </div>  
  <?php
		}
  ?>
 