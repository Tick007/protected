<?php
if(empty($banners_clear)==false){
	?>
		<a class="<?php echo $fancyboxclass?>" href="<?php echo  $folder.$banners_clear[0]?>" rel="<?php echo $rel?>">
		<img src="<?php echo $icon_image?>" ></a>
		<div class="hidden">
		<?php 
		$c = count($banners_clear);
		for($i=1; $i<$c; $i++){
			?>
			<a class="<?php echo $fancyboxclass?>" href="<?php echo $folder.$banners_clear[$i]?>" rel="<?php echo $rel?>">
			  <img src="<?php echo $icon_image?>"/>
			</a>
			<?php 
		}
		
		?>
		</div><!-- hidden -->
		<?php 
	
	}
?>