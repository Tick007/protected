<script type="text/javascript" src="/themes/fortus/js/liquid/jquery.liquidcarousel.js"></script>
<link href="/themes/fortus/css/liquid/liquidcarousel.css" rel="stylesheet" type="text/css">
<div id="liquid" class="liquid" style="height: 150px; overflow: hidden;">
	<span class="previous"></span>
	<div class="wrapper">
		<ul>
      <?php
      for ($i=0; $i<count($pictures); $i++) {
		  $pict = $pictures[$i]->id.".".$pictures[$i]->ext;
		  $qqq = explode('#', $pictures[$i]->comments);
			?>
			<li><a href="<?php echo $qqq[1]?>"  target="_blank"><img class="liquide_carusel" src="/pictures/add/icons/<?php echo $pictures[$i]->id?>.png"></a><br>
        <div align="center"><?php
        echo CHtml::link($qqq[0], $qqq[1], array('style'=>'text-decoration: none;
font-family: Verdana, Geneva, sans-serif;
font-size: 14px;
color: #000000;', 'target'=>'_blank'));
		?></li>
			<?php	
		}
	  ?>
</ul>
	</div>
	<span class="next"></span>
</div>
<script>
$(document).ready(function() {
	$('#liquid').liquidcarousel({
		height: 150,		//the height of the list
		duration: 500,		//the duration of the animation
		//hidearrows: true	//hide arrows if all of the list items are visible
	});
});
</script>
  