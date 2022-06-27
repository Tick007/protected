		<div class="lab_sl">
			
			

<?php
$banners = FHtml::get_files ( $_SERVER ['DOCUMENT_ROOT'] . $folder, 1 );
//print_r ( $banners );
if (isset ( $banners ) and empty ( $banners ) == false) {
    
    $ban_s = count($banners);
    for($i = ($ban_s-1); $i>=0;  $i--) {
		$img_url = $folder . $banners [$i];
		$img_url_thumb = $img_url ?>

		
<div class="item">
				<div class="pic"><img src="<?php echo $img_url_thumb?>?v=<?php echo rand()?> "  class="img-responsive"></div>
			</div>
									
								
									
									
									<?php 
									}
									}
									?>

		</div>
		<div class="cifr"><span id="cp">1</span> / <span class="vsego">0</span></div>
<script>
jQuery(document).ready(function() {
			$('.lab_sl').slick({
		slidesToShow: 1,
		arrows: true,
		dots: false,
		infinite: true,
		responsive: [{
			breakpoint: 768,
			settings: {
				arrows: true,
			}
		}]
	});
	$(".lab_sl").on('afterChange', function(event, slick, currentSlide){
		$("#cp").text(currentSlide + 1);
	});
	var slickk=$('.lab_sl');
$('.vsego').html( slickk.slick("getSlick").slideCount);
});
</script>
