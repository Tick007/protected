<script type="text/javascript" src="/themes/fortus/js/step/stepcarousel.js">
</script>

<script type="text/javascript" src="/themes/fortus/js/step/option.js">
</script>
<!--
<script type="text/javascript" src="/themes/fortus/js/step/showhide.js">
</script>
<script type="text/javascript" src="/themes/fortus/js/step/photos.js">
</script>
<script src="/themes/fortus/js/step/styleswitch.js" type="text/javascript">
</script>
-->

<link href="/themes/fortus/css/step/red.css" rel="stylesheet" type="text/css" title="red">
<div class="cont" align="center">
<div id="mygallery" class="stepcarousel">
    <div class="belt">
      <!--<div class="panel"><a href="photos/photo1_hover.jpg" class="photo"><span class="img_hover"><font>Photo</font></span><img src="photos/photo1.jpg" alt="Photo" title="Photo"></a>
        <div class="desc"><span>01</span><br>
          <em>24 May 2011</em>
          <h2>Night in <strong>Manhattan</strong></h2>
          <p><a href="#">by Angelo Mazzilli</a></p>
        </div>
      </div>-->
      <?php
      for ($i=0; $i<count($pictures); $i++) {
		  $pict = $pictures[$i]->id.".".$pictures[$i]->ext;
		  $qqq = explode('#', $pictures[$i]->comments);
			?>
			<div class="panel"><a href="<?php echo $qqq[1]?>"  target="_blank"><span class="img_hover"><font>Photo</font></span><img src="/pictures/add/icons/<?php echo $pictures[$i]->id?>.png" alt="Photo" title="Photo"></a><br>
        <div align="center"><?php
        echo CHtml::link($qqq[0], $qqq[1], array('style'=>'text-decoration: none;
font-family: Verdana, Geneva, sans-serif;
font-size: 14px;
color: #000000;', 'target'=>'_blank'));
		?></div>
      </div>
			<?php	
		}
	  ?>
      
    </div>
  </div>
  </div>
  