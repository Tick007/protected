<div class="amaizing-slider">
	<link rel="stylesheet" type="text/css" media="all" href="/themes/fortus_new/css/initcarousel.css?v=<?php echo rand()?>">
	
                                <?php
//$folder = '/themes/fortus_new/img/rotation/30/';

//$banners = FHtml::get_files ( $_SERVER ['DOCUMENT_ROOT'] . $folder, 1 );
//exit();
//print_r ( $banners_clear );
//exit();
if (isset ( $banners_clear ) and empty ( $banners_clear ) == false) {
	
	?>
	<div id="amazingcarousel-container-10" style="display: none">
	<?php 
	foreach ($banners_clear as $f=>$banners) {
	
		//echo '<pre>';
		//echo $f.'=>';
		//print_r($banners);
		//echo '</pre>';
		?>
	
		<div class="amazingcarousel-links" id="amazingcarousel-<?php echo $f?>"
			style="position: relative; width: 123px; margin: 0px auto 0px; display: none;"> 
			<div class="amazingcarousel-list-container" style="overflow: hidden; ">
				<ul class="amazingcarousel-list">
	<?php 
	for($i = 0; $i < count ( $banners ); $i ++) {
		//echo $i.'<br>';
		if (strpos($banners[$i], '_m')==false){
		$img_url = $folder.$f.'/'. $banners[$i];
		//echo $img_url.'<br>';
		//$img_url_thumb = "/pictures/make_mini.php?create=0&width=125&imgname=".$img_url ;
		$img_url_thumb = str_replace('.jpg', '_m.png', $img_url);
		////$img_url = $folder.$f.'/'. $banners [$i];
		?>
                <li class="amazingcarousel-item">
						<div class="amazingcarousel-item-container">


							<div class="amazingcarousel-image" align="center">
								<a class="fancybox<?php echo $f?>" href="<?php  echo $img_url?>" rel="<?php echo $f?>"><img
									src="<?php echo $img_url_thumb?>"></a>
							</div>
						</div>
					</li>
                
                <?php
				}

				}
				$banners = null;
				?>
				 </ul>
			</div>
			<div class="amazingcarousel-prev"></div>
			<div class="amazingcarousel-next"></div>
			<div class="amazingcarousel-nav"></div>
			
		</div>
	
				<?php 
			}?>
			 </div>
			<?php 
					
		}
																																?>
                
           
	<script
		src="/themes/fortus_new/js/initcarousel.js?v=<?php echo rand()?>">
		//$(window).load(function() {
		//	$('#amazingcarousel-vallink').css('display', 'none');
		//});
		
		</script>
		
</div>