<?php 
$clientScript = Yii::app()->clientScript;
/*
Yii::app()->clientScript->scriptMap=array(
		'jquery.js'=>false,
);
$clientScript->registerScriptFile( 'http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js', CClientScript::POS_HEAD);
*/
$clientScript->registerScriptFile(Yii::app()->theme->baseUrl .'/js/threesixty/src/threesixty.js', CClientScript::POS_HEAD);
//$clientScript->registerScriptFile(Yii::app()->theme->baseUrl .'/js/carousel.js', CClientScript::POS_HEAD);
//$clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/threesixty/src/styles/threesixty.css?v='.rand(), CClientScript::POS_HEAD);
$clientScript->registerCSSFile( Yii::app()->theme->baseUrl.'/js/threesixty/src/styles/threesixty.css?v='.rand(), 'screen');
?>
<div class="sixty-slider">


<?php
$banners = FHtml::get_files ( $_SERVER ['DOCUMENT_ROOT'] . $folder, 1 );
//print_r ( $banners );
if (isset ( $banners ) and empty ( $banners ) == false) {
    $ban_s = count($banners);
    for($i = ($ban_s-1); $i>=0;  $i--) {

		$img_url = $folder . $banners [$i];
		$img_url_thumb = $img_url ?>

		

<div class="slickimg <?php 
if (strpos($img_url_thumb, 'kpp')) echo 'kpplink';
elseif (strpos($img_url_thumb, 'hood')) echo 'hoodlink';
elseif (strpos($img_url_thumb, 'pinless')) echo 'pinlessval';
elseif (strpos($img_url_thumb, 'master')) echo 'masterlink';
elseif (strpos($img_url_thumb, 'pin')) echo 'vallink';
elseif (strpos($img_url_thumb, 'lamp')) echo 'headlamp';
elseif (strpos($img_url_thumb, 'tyre')) echo 'sparetire';
elseif (strpos($img_url_thumb, 'control')) echo 'controlblocklink';
elseif (strpos($img_url_thumb, 'kpp')) echo 'kpplink';
elseif (strpos($img_url_thumb, 'electro')) echo 'electrolink';


?>">
	<img src="<?php echo $img_url_thumb?>" alt="Golden Wheat Field">
</div>
									
									
									<?php 
									}
									}
									?>
									

									
</div>

<script>
///////////////слайдер

$(document).ready(function(){

	SlickObject = $('.sixty-slider').slick({
		  dots: true,
		  speed: 300,
		  slidesToShow: 1,
		  adaptiveHeight: true,
		  arrows: true,
		  infinite: true

		});
		
	});
</script>
