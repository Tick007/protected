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

		<div class="threesixty_car">
			<div class="threesixty car">
				<div class="spinner">
					<span>0%</span>
				</div>
				<ol class="threesixty_images">
				</ol>
			</div>
		</div>

<?php
$banners = FHtml::get_files ( $_SERVER ['DOCUMENT_ROOT'] . $folder, 1 );
//print_r ( $banners );
if (isset ( $banners ) and empty ( $banners ) == false) {
	for($i = 0; $i < count ( $banners ); $i ++) {

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


//Отключаем мышку на картинках

$('.threesixty_images').on('mousedown', function (evt) {
	evt.preventDefault();
	return false;
	});



//window.onload = init;

$(window).load(function() { //////////////Включаем фон на первой странице
//$('.container').css('background-size', 'cover');

});


function ImgLoaded(){

}

var car;
function init(){

car = $('.car').ThreeSixty({
    totalFrames: 50, // Total no. of image you have for 360 slider
    endFrame: 51, // end frame for the auto spin animation
    currentFrame: 1, // This the start frame for auto spin
    imgList: '.threesixty_images', // selector for image list
    progress: '.spinner', // selector to show the loading progress
   // imagePath:'/themes/fortus_new/js/threesixty/assets/', // path of the image assets
   imagePath:'/themes/fortus_new/img/rotation/26032019/',
    filePrefix: '', // file prefix if any
    ext: '.jpg', // extention for the assets
    //height: 447,
    height: 484,
    width: 733,
    navigation: true,
    imageLoaded: 1
});

$('.custom_previous').bind('click', function(e) {
  car.previous();
});

$('.custom_next').bind('click', function(e) {
  car.next();
});

$('.custom_play').bind('click', function(e) {
  car.play();
});

$('.custom_stop').bind('click', function(e) {
  car.stop();
});

//////////////////////////////////////Пробуем принудительно инициализировать первый кадр
car.init();

FrameIds['masterlink'] = 1; //39;//39;
FrameIds['kpplink'] =  3;//13;//13;
FrameIds['hoodlink'] = 42;//45;
FrameIds['vallink'] = 29;///20;//20;
FrameIds['pinlessval'] = 23;///20;//20;
FrameIds['sparetire'] = 15;//29;//29;
FrameIds['controlblocklink'] = 38;//10;//29;
FrameIds['headlamp'] = 47;//4;//4;
FrameIds['electrolink'] = 3;//13;//4;

}

///////////////слайдер
$(document).ready(function(){
	
	  init();///////////Плагин для машины
	  
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