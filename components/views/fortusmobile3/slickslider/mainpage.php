<div class="slickcarousel">
<?php
$banners = FHtml::get_files ( $_SERVER ['DOCUMENT_ROOT'] . $folder, 1 );
//print_r ( $banners );
if (isset ( $banners ) and empty ( $banners ) == false) {
    $ban_s = count($banners);
    for($i = ($ban_s-1); $i>=0;  $i--) {

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
	  $('.slickcarousel').slick({
		  dots: true,
		  infinite: true,
		  speed: 300,
		  slidesToShow: 1,
		  adaptiveHeight: true,
		  arrows: false,
		  autoplay:true
		});
	});
</script>