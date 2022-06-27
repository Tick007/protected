<?php
 $clientScript = Yii::app()->clientScript;
$clientScript->registerScriptFile('/js/jquery.touchSwipe.min.js', CClientScript::POS_HEAD);
$clientScript->registerScriptFile('/js/jquery.advancedSlider.min.js', CClientScript::POS_HEAD);
$clientScript->registerCssFile('/css/advanced-slider-base.css');
$clientScript->registerCssFile('/css/glossy-square-gray.css');
?>

<script type="text/javascript">

	jQuery(document).ready(function($){
		$('#responsive-slider').advancedSlider({width: 940,
												height: 485,
												responsive: true,
												skin: 'glossy-square-gray',
												shadow: false,
												effectType: 'swipe',
												slideshow: true,
												pauseSlideshowOnHover: true,
												swipeThreshold: 30,
												slideButtons: false,
												thumbnailType: 'none',
												thumbnailWidth: 140,
												thumbnailHeight: 80,
												thumbnailButtons: true,
												thumbnailSwipe: true,
												thumbnailScrollerResponsive: true,
												minimumVisibleThumbnails: 2,
												maximumVisibleThumbnails: 4,
												keyboardNavigation: true
		});
	});

</script>
<div class="advanced-slider" id="responsive-slider">
		<ul class="slides">

			<li class="slide">
				<img class="image" src="http://bqworks.com/products/advanced-slider/images/v4/large/image1.jpg" alt="">
    			<img class="thumbnail" src="http://bqworks.com/products/advanced-slider/images/v4/thumbnails/thumb1.jpg" alt="Slide 1">

    			<div class="layer black" data-horizontal="40" data-vertical="40">
					Need a slider that is...
				</div>

				<div class="layer white" data-horizontal="40" data-vertical="80" data-transition="left" data-delay="400" data-duration="300">
					responsive
				</div>

				<div class="layer black" data-horizontal="138" data-vertical="80" data-transition="left" data-delay="600" data-duration="300">
					touch-screen ready
				</div>

				<div class="layer white" data-horizontal="300" data-vertical="80" data-transition="left" data-delay="800" data-duration="300">
					and fully customizable ?
				</div>

				<div class="layer black" data-horizontal="40" data-vertical="122" data-transition="up" data-offset="20" data-delay="1200">
					Advanced Slider includes all that, and even more...
				</div>
			</li>

			
			<li class="slide rounded-caption">
				<img class="image" src="http://bqworks.com/products/advanced-slider/images/v4/large/image2.jpg" alt="">
    			<img class="thumbnail" src="http://bqworks.com/products/advanced-slider/images/v4/thumbnails/thumb2.jpg" alt="Slide 2">

    			<div class="layer black static" data-width="280" data-horizontal="center" data-vertical="30%">
					Layers can be static or animated...
				</div>

				<div class="layer white" data-width="280" data-horizontal="center" data-vertical="40%">
					...and you can easily customize their size, position, animation and style
				</div>
			</li>

			
			<li class="slide">
				<img class="image" src="http://bqworks.com/products/advanced-slider/images/v4/large/image3.jpg" alt="">
    			<img class="thumbnail" src="http://bqworks.com/products/advanced-slider/images/v4/thumbnails/thumb3.jpg" alt="Slide 3">

    			<div class="layer white" data-position="bottomLeft" data-horizontal="30" data-vertical="70" data-width="320" data-transition="down">
					Layers can contain anything from simple text to complex HTML content like videos
				</div>

				<div class="layer white" data-position="bottomLeft" data-horizontal="30" data-vertical="30" data-transition="left" data-delay="500">
					<a href="mixed.html">Mixed content example ></a>
				</div>
			</li>
			

			<li class="slide">
				<img class="image" src="http://bqworks.com/products/advanced-slider/images/v4/large/image4.jpg" alt="">
    			<img class="thumbnail" src="http://bqworks.com/products/advanced-slider/images/v4/thumbnails/thumb4.jpg" alt="Slide 4">

    			<div class="layer black" data-position="topRight" data-horizontal="100" data-vertical="30" data-transition="up">
					One of the most important features is Video handling
				</div>

				<div class="layer black" data-position="topRight" data-horizontal="100" data-vertical="70" data-transition="up" data-delay="150">
					<a href="video.html">Video example ></a>
				</div>

				<div class="layer white" data-position="topRight" data-horizontal="100" data-vertical="110" data-width="320" data-transition="up" data-delay="300">
					The slider has built-in support for Vimeo, YouTube, HTML5 video and Video JS. Also, Vimeo and YouTube videos can be loaded by default or can be lazy loaded.
				</div>
			</li>

			
			<li class="slide">
				<img class="image" src="http://bqworks.com/products/advanced-slider/images/v4/large/image5.jpg" alt="">
    			<img class="thumbnail" src="http://bqworks.com/products/advanced-slider/images/v4/thumbnails/thumb5.jpg" alt="Slide 5">

				<div class="layer black" data-position="bottomRight" data-horizontal="30" data-vertical="30">
					Navigate to the <a href="#" onclick="jQuery('.advanced-slider').advancedSlider().previousSlide(); return false;">previous</a>
					or <a href="#" onclick="jQuery('.advanced-slider').advancedSlider().nextSlide(); return false;">next</a> slide.
				</div>
			</li>
			

			<li class="slide">
				<img class="image" src="http://bqworks.com/products/advanced-slider/images/v4/large/image6.jpg" alt="">
    			<img class="thumbnail" src="http://bqworks.com/products/advanced-slider/images/v4/thumbnails/thumb6.jpg" alt="Slide 6">

    			<div class="layer static white" data-horizontal="10%" data-vertical="center" data-width="320">
					Not only slides can contain HTML content, but also the thumbnails. Please check these <a href="text-thumbnails.html">two</a> <a href="lazy-loading.html">examples</a>. 
				</div>
			</li>
			

			<li class="slide">
				<img class="image" src="http://bqworks.com/products/advanced-slider/images/v4/large/image7.jpg" alt="">
    			<img class="thumbnail" src="http://bqworks.com/products/advanced-slider/images/v4/thumbnails/thumb7.jpg" alt="Slide 7">
			</li>
			

			<li class="slide">
				<img class="image" src="http://bqworks.com/products/advanced-slider/images/v4/large/image8.jpg" alt="">
    			<img class="thumbnail" src="http://bqworks.com/products/advanced-slider/images/v4/thumbnails/thumb8.jpg" alt="Slide 8">

    			<div class="layer black" data-position="bottomRight" data-horizontal="300" data-vertical="120" data-transition="down">
					Other features include...
				</div>

				<div class="layer white" data-position="bottomRight" data-horizontal="358" data-vertical="80" data-delay="300">
					lightbox support
				</div>

				<div class="layer black" data-position="bottomRight" data-horizontal="190" data-vertical="80" data-delay="600">
					keyboard navigation
				</div>

				<div class="layer white" data-position="bottomRight" data-horizontal="45" data-vertical="80" data-delay="900">
					per slide settings
				</div>

				<div class="layer black" data-position="bottomRight" data-horizontal="384" data-vertical="40" data-delay="1200">
					various skins
				</div>

				<div class="layer white" data-position="bottomRight" data-horizontal="230" data-vertical="40" data-delay="1400">
					fullscreen support
				</div>

				<div class="layer black" data-position="bottomRight" data-horizontal="126" data-vertical="40" data-delay="1600">
					and more...
				</div>
			</li>
			

			<li class="slide">
				<img class="image" src="http://bqworks.com/products/advanced-slider/images/v4/large/image9.jpg" alt="">
    			<img class="thumbnail" src="http://bqworks.com/products/advanced-slider/images/v4/thumbnails/thumb9.jpg" alt="Slide 9">

    			<div class="layer static black" data-horizontal="50" data-vertical="50" data-width="320">
					Please check the other examples to see some of the slider's features in action. 
				</div>
			</li>


			<li class="slide">
				<img class="image" src="http://bqworks.com/products/advanced-slider/images/v4/large/image10.jpg" alt="">
    			<img class="thumbnail" src="http://bqworks.com/products/advanced-slider/images/v4/thumbnails/thumb10.jpg" alt="Slide 10">
			</li>

		</ul>
	</div>
