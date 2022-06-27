<?php
 $clientScript = Yii::app()->clientScript;
$clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/theatre/jquery.theatre.js', CClientScript::POS_HEAD);
$clientScript->registerCSSFile('/themes/'.Yii::app()->theme->name.'/css/theatre/theatre.css');
?>
    <div class="vbox">
	<div id="demo1" class="theatreDemo" style="width: 300px; margin: auto;">
   <?php

  foreach ($products as $id => $product){
                ?>
       <a href="<?php echo Yii::app()->createUrl('catalog/info', array('alias'=>$product['category_alias'], 'id'=>$id))?>">
<img  src="<?php echo Yii::app()->baseUrl.'/pictures/add/icons/'.$product['icon_id'].'.png'?>">
        
        </a>
     <?php
                }
     ?> 





	</div>

 <div id="myPaging" style="display:none"><span class="button">Image #{#}</span></div>

  <script type="text/javascript">
	$(window).load(function() {
	  $('#demo1').theatre({
	    selector: 'img', // We want to resize/rotate images and not links
		effect: '3d',
		still:2000,
		speed: 500,
		random:true,
                paging: '#myPaging'
	  });

	  // $('#demo a').fancybox();
	});
  </script>

</div>
