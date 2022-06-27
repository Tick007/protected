<div class="slick_mp">
<?php
$banners = FHtml::get_files ( $_SERVER ['DOCUMENT_ROOT'] . $folder, 1 );
//print_r ( $banners );
if (isset ( $banners ) and empty ( $banners ) == false) {
    
    $ban_s = count($banners);
    for($i = ($ban_s-1); $i>=0;  $i--) {
		$img_url = $folder . $banners [$i];
		//$img_url_thumb = $img_url
		
		$img_url_thumb = '/pictures/make_mini.php?create=0&height=230&imgname='.$img_url;

		 ?>
<div>
<?php  echo CHtml::link("<img src=\"$img_url_thumb\"/ height=\"250px\" 
class=\"content_img\">", "http://".$_SERVER['HTTP_HOST'].$img_url, 
array("data-lightbox"=>"image-2"));
								?>
									
									</div>
									
									
									<?php 
									}
									}
									?>
</div>

<script>
$(document).ready(function(){
	  $('.slick_mp').slick({
		  dots: false,
		  infinite: true,
		  speed: 300,
		  slidesToShow: 3,
		  adaptiveHeight: true,
		  arrows: true,
		  autoplay:false,
		});
	});
	
</script>