<div class="slick_blog">
<?php


$criteria=new CDbCriteria;
$criteria->condition='t.section = 7 AND t.active = 1';
$criteria->order="t.creation_date ";
$criteria->limit = '3';

$banners=Page::model()->findAll($criteria);

if (isset($banners) and empty($banners) == false) {
    for ($i = 0; $i < count($banners); $i ++) {
?>
		

									<div>
		<div class="blog">
			<div style="line-height: 60px;">&#10078;</div>
			<div class="blog_contents">
				<!-- <div class="blog_header"><?php echo $banners[$i]->name?></div>-->
				<div class="blog_contents_header"><?php echo $banners[$i]->description?></div>
				<div class="blog_text">
					<?php echo $banners[$i]->contents?><br><br>
					<div align="right">
						<a href="<?php echo $banners[$i]->source?>" target="_blank">Подробнее</a> >>>
					</div>
				</div>

			</div>

			<div align="right">&#10077;</div>
		</div>
			</div>						
									
									<?php
    }
}
?>

</div>

	<script>
$(document).ready(function(){
	  $('.slick_blog').slick({
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