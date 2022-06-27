<?php
 $clientScript = Yii::app()->clientScript;
$clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/theatre/jquery.theatre.js', CClientScript::POS_HEAD);
$clientScript->registerCSSFile(Yii::app()->request->baseUrl.'/themes/protuning/css/theatre/theatre.css');
?>
    <div class="vbox">
	<div id="demo1" class="theatreDemo" style="width: 632px; margin: auto;">
   <?php

if(isset($products)) {
	foreach ($products as $id => $product){

   		$filename_gif = $_SERVER['DOCUMENT_ROOT'].'/pictures/img_med/'.$id.'.gif';
		$filename_jpg = $_SERVER['DOCUMENT_ROOT'].'/pictures/img_med/'.$id.'.jpg';
		$filename_png = $_SERVER['DOCUMENT_ROOT'].'/pictures/img_med/'.$id.'.png';
		$exist_gif = file_exists($filename_gif);
		$exist_jpg = file_exists($filename_jpg);
		$exist_png= file_exists($filename_png);
		
			if ($exist_png==true) {
				$filename = $filename_png;
				$filesrc = '/pictures/img_med/'.$id.'.png';
			}
			elseif($exist_jpg==true) {
				$filename = $filename_jpg;
				$filesrc = '/pictures/img_med/'.$id.'.jpg';
			}
			elseif($exist_gif==true) {
				$filename = $filename_gif;
				$filesrc = '/pictures/img_med/'.$id.'.gif';
			}
			elseif ($exist_gif==false AND $exist_jpg==false AND $exist_png==false) {/////////////Файл не существует, нужно рисовать элемент для закачки
				$picture = "<img border=\"1\" src=\"http://".$_SERVER['HTTP_HOST']."/images/nophoto_200.png\" height=\"60\">";
				
			}//////////Файл не существует, нужно рисовать элемент для закачки
			
			
			if(isset($filesrc)) {
				$picture = "<img border=\"0\" src=\"$filesrc\" alt=\"".str_replace('"', '', $product['name'])."\"  class=\"mwh450\" title=\"".str_replace('"', '', $product['name'])."\"/>";
			echo CHtml::link($picture, array('/product/details/','pd'=>$id), $htmlOptions=array ('encode'=>false, 'title'=>str_replace('"', '', $product['name'])));
			}
			else echo '&nbsp;';

}////////  for ($i=0; $i<count($pr_id); $i++) {

  

}//////////if(isset($models)) { 
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
