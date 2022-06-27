<?php
 $clientScript = Yii::app()->clientScript;
$clientScript->registerScriptFile(Yii::app()->request->baseUrl . '/themes/fortus/js/jquery.jcarousel.js', CClientScript::POS_HEAD);
$clientScript->registerCssFile(Yii::app()->request->baseUrl . '/themes/fortus/css/jcarousel.css', CClientScript::POS_HEAD);
//$clientScript->registerCssFile(Yii::app()->request->baseUrl . '/themes/enterteh/css/type/puritan.css', CClientScript::POS_HEAD);
?>
<link href="<?php echo 'http://'.$_SERVER['HTTP_HOST'].Yii::app()->theme->baseUrl; ?>/css/jcarousel.css" rel="stylesheet" type="text/css" >
<script type="text/javascript">
jQuery(document).ready(function() {
    // Initialise the first and second carousel by class selector.
	// Note that they use both the same configuration options (none in this case).
	jQuery('.d-carousel .carousel').jcarousel({
        scroll: 1
    });
});
</script>
<?php

$debug = Yii::app()->getRequest()->getParam('debug');//

if(isset($pictures)) {
?>


  
 <!-- Begin Wrapper -->
  <div id="wrapper">
  
    <div class="d-carousel">
 
      <ul class="carousel">
        <?php
      for ($i=0; $i<count($pictures); $i++) {
		  $pict = $pictures[$i]->id.".".$pictures[$i]->ext;
		  $qqq = explode('#', $pictures[$i]->comments);
			?>
			<li><a href="<?php echo $qqq[1]?>"  target="_blank"><img class="img_carusel" src="/pictures/add/icons/<?php echo $pictures[$i]->id?>.png"></a><br>
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
 
  <?php
		}
  ?>