<div class="slick_setups">
<?php
$banners = FHtml::get_files ( $_SERVER ['DOCUMENT_ROOT'] . $folder, 1 );
//print_r ( $banners );
if (isset ( $banners ) and empty ( $banners ) == false) {
	for($i = 0; $i < count ( $banners ); $i ++) {

		$img_url = $folder . $banners [$i];
		$img_url_thumb = $img_url ?>

		

									<div >
								<img
									src="<?php echo $img_url_thumb?>?v=<?php echo rand()?> "
									alt="Golden Wheat Field"></div>
									
									
									<?php 
									}
									}
									?>
</div>

<script>
$(document).ready(function(){
	  $('.slick_setups').slick({
		  dots: true,
		  infinite: true,
		  speed: 2000,
		  slidesToShow: 3,
		  adaptiveHeight: true,
		  arrows: true,
		  autoplay:true
		});
	});
</script>