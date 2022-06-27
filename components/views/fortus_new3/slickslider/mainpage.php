<div class="slick_mp">
<?php
$banners = FHtml::get_files ( $_SERVER ['DOCUMENT_ROOT'] . $folder, 1 );
//print_r ( $banners );
if (isset ( $banners ) and empty ( $banners ) == false) {
    
    $ban_s = count($banners);
    for($i = ($ban_s-1); $i>=0;  $i--) {
		$img_url = $folder . $banners [$i];
		$img_url_thumb = $img_url ?>

		

									<div >
									<?php 
									if($banners[$i]=='banner (1).png'){
									    $url = Yii::app()->createUrl('site/page/production', array('#'=>'hoodset'));
									    //echo $url;
									    ?>
									    <a href="<?php echo $url?>">
									    <?php 
									}
									?>
								<img
									src="<?php echo $img_url_thumb?>?v=<?php echo rand()?> "
									alt="Golden Wheat Field">
									<?php 
									if($banners[$i]=='banner (1).png'){?>
									</a>
									<?php 
                                    }
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
		  dots: true,
		  infinite: true,
		  speed: 300,
		  slidesToShow: 1,
		  adaptiveHeight: true,
		  arrows: true,
		  autoplay:false
		});
	});
</script>