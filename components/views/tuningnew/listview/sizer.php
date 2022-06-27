<div id="limiter" class="sizer">
	<label>Показывать по: </label>
	<ul>
	<li><a href="#"><?php echo $size?></a><span class="right-arrow"></span>
	<ul>
	<?php 
	foreach($links as $link){
		?><li><?php echo $link?></li><?php 
	}
	?></ul></li>
	</ul>
</div>