<?php
if(isset($products)) {
?>
<div class="leftpanelblock2" align="center">
      <div class="blockheader3">
	  <div class="all_nov" align="right"> <?php echo CHtml::link('посмотреть все новинки', array('product/novinki'))?></div><div style="margin-top:-8px">
	  <?php echo $title;?></div></div>

<?php //////
$cells = 4;  ///////Количество ячеек в одно строке
?>
	<?php
	for ($i=0; $i<count($products); $i++) {
		//print_r($products[$i]->attributes);
		$k = $i+1;
		   if(isset(Yii::app()->params['use_long_urls']) AND Yii::app()->params['use_long_urls']==true) $url=urldecode(Yii::app()->createUrl('product/details' ,array('alias'=>$products[$i]->belong_category->alias, 'path'=>FHtml::urlpath($products[$i]->belong_category->path)  ,  'pd'=>$products[$i]->id) ) );
		    else  $url=urldecode(Yii::app()->createUrl('product/details' ,array( 'pd'=>$products[$i]->id,  'cat'=>$products[$i]->category_belong) ) );
		?>
        <div class="vitrina_cell">
        <div class="vitrina_cell_header"><?php
		//echo CHtml::link($products[$i]->product_name, $url);
			$str_len = strlen(trim($products[$i]->product_name));
		if($str_len>80) $pr_name = mb_substr($products[$i]->product_name, 0, 80, 'utf-8').'...';
		else $pr_name = $products[$i]->product_name;
		echo CHtml::link($pr_name, $url);
		?></div>
        <div class="vitrina_cell_body">
        <table border="0" style="overflow:inherit" cellpadding="0" cellspacing="0">
		<tr>
    <td height="125" rowspan="2" valign="top">
    	<?php
							//$iconname = Yii::app()->request->baseUrl."/pictures/add/icons/".$products[$i]->icon.'.png';
							$iconname ='http://'.Yii::app()->params['pictures_sourse']."/pictures/add/icons/".$products[$i]->icon.'.png';
									//echo $_SERVER['DOCUMENT_ROOT'].$iconname;
									//if (file_exists($_SERVER['DOCUMENT_ROOT'].$iconname)==1) echo CHtml::link("<img src=\"$iconname\" class=\"content_img150\" />", $url);
									echo CHtml::link("<img src=\"$iconname\" class=\"content_img150\" />", $url);
							?></td>
    <td valign="bottom" height="90" style="font-weight:bold" align="center"><?php
    echo $products[$i]->belong_category->category_name;
	?></td>
		</tr>
		<tr>
		  <td valign="top" align="center"><?php
	echo "<span class=\"vitrina_price\">".str_replace(',00', '', FHtml::encodeValuta($products[$i]->product_price, 'руб.')).'</span>';
    ?></td>
		  </tr>
		</table>
  </div><!--<div class="vitrina_cell_body">-->
  </div>
	<?php	
	
	if ($k/$cells == round($k/$cells, 0)  AND  $k!=count($products) ) echo "<div class=\"clear\"></div>";
	
	}//////for ($i=0; $i<count($products); $i++) {
?>  
<div class="clear"></div>


 </div>  
  <?php
		}
  ?>