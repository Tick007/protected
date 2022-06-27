<?php
class SimpleCarousel extends CWidget {


function __construct(){
		
		 $clientScript = Yii::app()->clientScript;
		
		Yii::app()->clientScript->scriptMap=array(
        'jquery.js'=>false,
);
		$clientScript->registerScriptFile( 'http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js', CClientScript::POS_HEAD);
		//$clientScript->registerScriptFile(Yii::app()->theme->baseUrl .'/js/jquery.jcarousel.js', CClientScript::POS_HEAD);
		//$clientScript->registerScriptFile(Yii::app()->theme->baseUrl .'/js/carousel.js', CClientScript::POS_HEAD);
		Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/amazingcarousel.js?v='.rand(), CClientScript::POS_HEAD);
		//$clientScript->registerCSSFile( Yii::app()->theme->baseUrl.'/css/carousel.css', CClientScript::POS_HEAD);
}

function draw($view, $banner_path, $banners){
	
$this->render(Yii::app()->theme->name.'/simplecarousel/'.$view, array('banner_path'=>$banner_path, 'banners'=>$banners));
}/////function draw(){
}////class
?>