<div class="amaizing-slider">
	<link rel="stylesheet" type="text/css" media="all" href="/themes/fortus_new/css/initcarousel.css?v=<?php echo rand()?>">
	<div id="amazingcarousel-container-10">
		<div id="amazingcarousel-10"
			style="display: block; position: relative; width: 123px; margin: 0px auto 0px;">
			<div class="amazingcarousel-list-container" style="overflow: hidden;">
				<ul class="amazingcarousel-list">
                                <?php
//$folder = '/themes/fortus_new/img/rotation/30/';

//$banners = FHtml::get_files ( $_SERVER ['DOCUMENT_ROOT'] . $folder, 1 );
//exit();
//print_r ( $banners_clear );
//exit();
if (isset ( $banners_clear ) and empty ( $banners_clear ) == false) {
	
	
	foreach ($banners_clear as $f=>$banners) {
	
	for($i = 0; $i < count ( $banners ); $i ++) {
		
		
		
		if (strpos($banners [$i], '_m')==false){
		
		$img_url = $folder.$f.'/'. $banners [$i];
		//echo $img_url.'<br>';
		//$img_url_thumb = "/pictures/make_mini.php?create=0&width=125&imgname=".$img_url ;
		$img_url_thumb = str_replace('.jpg', '_m.png', $img_url);
		////$img_url = $folder.$f.'/'. $banners [$i];
		?>
                <li class="amazingcarousel-item">
						<div class="amazingcarousel-item-container">


							<div class="amazingcarousel-image" align="center">
								<a class="<?php echo $fancyboxclass?>" href="<?php  echo $img_url?>" rel="<?php echo $rel?>"><img
									src="<?php echo $img_url_thumb?>"
									alt="Golden Wheat Field"></a>
								<!--<div class="amazingcarousel-text">
									<div class="amazingcarousel-text-bg"></div>
									<div class="amazingcarousel-title">Golden Wheat Field</div>
								</div>-->
							</div>
						</div>
					</li>
                
                <?php
				}
				}
			}
					
		}
																																?>
                
            </ul>
			</div>
			<div class="amazingcarousel-prev"></div>
			<div class="amazingcarousel-next"></div>
			<div class="amazingcarousel-nav"></div>
			
		</div>
	</div>
	<script
		src="/themes/fortus_new/js/initcarousel.js"></script>
</div>