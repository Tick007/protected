<?php
if(isset($products) AND empty($products)==false){

for ($k=0; $k<count($products); $k++) {

	$product = $products[$k];
	
	
	$banner_file=$_SERVER['DOCUMENT_ROOT'].'/pictures/add/'.$product->icon;
	$src = '/pictures/add/'.$product->icon;
	if(file_exists($banner_file) AND is_file($banner_file)==true) {
	$img = '<img src="'.$src.'" width="390" style="margin-bottom:3px">';
	echo CHtml::link($img, $product->attribute_value);
	}


}


}///if(isset($products) AND empty($products)==false){
?>



<?php
/*
$img = '<img src="/themes/fortus/img/news_banner.png" width="390">';
$url = Yii::app()->createUrl('news');
echo CHtml::link($img, $url)
*/
?>