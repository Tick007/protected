<!-- 
https://lokeshdhakar.com/projects/lightbox2/#options
 -->
<script>
    lightbox.option({
      'resizeDuration': 100,
      'wrapAround': false,
      'fadeDuration': 200,
      'imageFadeDuration':200,
    })
</script>

<div class="slick_popup">
<?php 
//print_r($img_array);
?>
<?php
$folder = Yii::app()->params['folders']->picture_path.'icons/';
$folder_full_size = Yii::app()->params['folders']->picture_path;
foreach ($img_array as $img) {
    $banners[] = $img['id'].'.';//.$img['ext'];
    $banners_full[] = $img['id'].'.'.$img['ext'];
}
if (isset ( $banners ) and empty ( $banners ) == false) {
    $ban_s = count($banners);
    for($i = ($ban_s-1); $i>=0;  $i--) {
		$img_url = $folder . $banners [$i].'png';
		$img_url_full = $folder_full_size . $banners_full [$i];
		
		$img_url_thumb = $img_url ?>

									<div >
									
									
									<?php  echo CHtml::link("<img src=\"$img_url\"/  width=\"600px\" height=\"425px\" 
class=\"content_img\">", "http://".$_SERVER['HTTP_HOST'].$img_url_full, 
array("data-lightbox"=>"image-1"));
								?>
									
							<!-- 	<img width="600px" height="425px"
									src="<?php echo $img_url_thumb?>?v=<?php echo rand()?> "
									alt="Golden Wheat Field">-->
									
									</div>
									
									
									<?php 
									}
									}
									?>
</div>


