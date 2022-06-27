<?php
 $clientScript = Yii::app()->clientScript;
$clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/theatre/jquery.theatre.js', CClientScript::POS_HEAD);
$clientScript->registerCSSFile(Yii::app()->request->baseUrl.'/themes/protuning/css/theatre/theatre.css');
?>
    <div class="vbox">
	<div id="demo1" class="theatreDemo" style="width: 632px; margin: auto;">
   <?php

if(isset($models)) {
$m=0;
$sgr=NULL;
$gr=NULL;
$price_with_nds=NULL;
$price_with_nds2=NULL;
$price_card=NULL;
 $product_name=NULL;
 $pr_id=NULL;
// print_r($models);
$added_id=NULL;
foreach($models as $n=>$next):
//print_r($added_id);
//echo '<br>';
if (!@in_array($next['id'], $added_id)) { /////////////Îòñåêàåì òå, êîòîðûå ïîïàäàþòñÿ âî ñâÿçàííûõ ãðóïïàõ
$sgr[]=$next['sgr'];
$gr[]=$next['gr'];
$price_with_nds[]=$next['price_with_nds'];
//$price_with_nds2[]=$next['price_with_nds2'];
$price_card[]=$next['price_card'];
$product_name[]=$next['product_name'];
 $pr_id[]=$next['id'];

$added_id[]= $next['id'];
$m++;
//}//////////////while
}////////if (!@in_array($next['id'], $added_id)) { /////////////Îòñåêàåì òå, êîòîðûå ïîïàäàþòñÿ âî ñâÿçàííûõ ãðóïïàõ
endforeach;

if (!isset($num_in_one_row)) $num_in_one_row=1;

  if (count(@$product_name)) {////////Ìîæåò âîîáùå íè÷åãî íåò äëÿ îòîáðàæåíèÿ

  $cells=count($product_name);
  $num_of_rows = $cells/$num_in_one_row;
  for ($i=0; $i<count($pr_id); $i++) {
?>
<?
//echo $i.' - '.$product_name[$i];

 $filename_gif = $_SERVER['DOCUMENT_ROOT'].'/pictures/img_med/'.$pr_id[$i].'.gif';
	$filename_jpg = $_SERVER['DOCUMENT_ROOT'].'/pictures/img_med/'.$pr_id[$i].'.jpg';
	$filename_png = $_SERVER['DOCUMENT_ROOT'].'/pictures/img_med/'.$pr_id[$i].'.png';
	$exist_gif = file_exists($filename_gif);
	$exist_jpg = file_exists($filename_jpg);
	$exist_png= file_exists($filename_png);
			if ($exist_png==true) {
				$filename = $filename_png;
				$filesrc = '.png';
			}
			elseif($exist_jpg==true) {
				$filename = $filename_jpg;
				$filesrc = '.jpg';
				//$filesrc="http://".$_SERVER['HTTP_HOST'].Yii::app()->createUrl('imagetools/watermark', array('img'=>$pr_id[$i].'.jpg'));
			}
			elseif($exist_gif==true) {
				$filename = $filename_gif;
				$filesrc = '.gif';
			}
			
			$filebase = $pr_id[$i].$filesrc;
			$filesrc = '/pictures/img_med/'.$pr_id[$i].$filesrc;
				
			$file_link=Yii::app()->createUrl('/imagetools/watermark', array('img'=>$filebase, 'f'=>'pi'));
			
			//echo "<img src=\"$filesrc\" style=\"max-height:60px\">";
			if(isset($filesrc)) {
				//$picture = "<img border=\"0\" src=\"$filesrc\" alt=\"".str_replace('"', '', $product_name[$i])."\"  class=\"mwh450\" title=\"".str_replace('"', '', $product_name[$i])."\"/>";
				$picture = "<img border=\"0\" src=\"$file_link\" alt=\"".str_replace('"', '', $product_name[$i])."\"  class=\"mwh450\" title=\"".str_replace('"', '', $product_name[$i])."\"/>";
			echo CHtml::link($picture, array('/product/details/','pd'=>$pr_id[$i]), $htmlOptions=array ('encode'=>false, 'title'=>str_replace('"', '', $product_name[$i])));
			}
			else echo '&nbsp;';
	?><?php
}////////  for ($i=0; $i<count($pr_id); $i++) {
?>
  
  <?
}////////////////////count product_name
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
