<ul class="promotion">
<?php
for($i=0, $c=count($products); $i<$c; $i++) {
	
	$fname = $_SERVER['DOCUMENT_ROOT'].'/pictures/add/'.$products[$i]->icon;
	$icon  = '/pictures/add/icons/'.$products[$i]->icon_id.'.png';
	$fname_icon = $_SERVER['DOCUMENT_ROOT'].$icon;
	//echo $fname.'<br>';
	//echo $fname_icon.'<br>';
	if(is_file($fname) AND file_exists($fname) AND file_exists($fname_icon) ) {
	
	  if(isset(Yii::app()->params['use_long_urls']) AND Yii::app()->params['use_long_urls']==true)  $url = urldecode(Yii::app()->createUrl('product/details' ,array('alias'=>$products[$i]->belong_category->alias, 'path'=>FHtml::urlpath($products[$i]->belong_category->path)  ,  'pd'=>$products[$i]->id) ) );
	  else $url=urldecode(Yii::app()->createUrl('product/details' ,array( 'pd'=>$products[$i]->id) ) );	
?>
	<li>
<a href="<?php echo $url?>">
<img onerror="this.src='/styles/images/noImageSelected.jpg'" class="marketing" alt="" src="<?php echo $icon?>" />
</a>
<div><br><span class="oportunidad">Выгодное предложение!</span>
<div class="promprice">
<span class="priceregular"><?php echo str_replace(',00', '', FHtml::encodeValuta($products[$i]->product_price, '')) ?></span>&nbsp;<span class="pricesellout"><?php echo str_replace(',00', '', FHtml::encodeValuta($products[$i]->sellout_price, ''))?></span><span class="rouble">Р</span></div>
<br>
<span class="promname"><?php echo $products[$i]->product_name?></span>
<div class="promlinkdiv"><?php
echo CHtml::link('Подробнее »', $url, array('class'=>'promlink'));
?></div>
</div>
</li>
<?php
	if($i>=0) break;
	}//////////files exixtance
}
?>
</ul>