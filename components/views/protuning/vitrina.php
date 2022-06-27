<?php
 $clientScript = Yii::app()->clientScript;
$clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/easySlider1.7.js', CClientScript::POS_HEAD);
$clientScript->registerCSSFile(Yii::app()->request->baseUrl.'/themes/protuning/css/easy-slider.css');
?>


	<div id="slider"><ul>
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
if (!@in_array($next['id'], $added_id)) { /////////////Отсекаем те, которые попадаются во связанных группах
$sgr[]=$next['sgr'];
$gr[]=$next['gr'];
$price_with_nds[]=$next['price_with_nds'];
$price_with_nds2[]=$next['price_with_nds2'];
$price_card[]=$next['price_card'];
$product_name[]=$next['product_name'];
 $pr_id[]=$next['id'];

$added_id[]= $next['id'];
$m++;
//}//////////////while
}////////if (!@in_array($next['id'], $added_id)) { /////////////Отсекаем те, которые попадаются во связанных группах
endforeach;

if (!isset($num_in_one_row)) $num_in_one_row=1;

  if (count(@$product_name)) {////////Может вообще ничего нет для отображения

  $cells=count($product_name);
  $num_of_rows = $cells/$num_in_one_row;
  for ($i=0; $i<count($pr_id); $i++) {
?><li>
<?
//echo $i.' - '.$product_name[$i];

 $filename_gif = $_SERVER['DOCUMENT_ROOT'].'/pictures/img/'.$pr_id[$i].'.gif';
	$filename_jpg = $_SERVER['DOCUMENT_ROOT'].'/pictures/img/'.$pr_id[$i].'.jpg';
	$filename_png = $_SERVER['DOCUMENT_ROOT'].'/pictures/img/'.$pr_id[$i].'.png';
	$exist_gif = file_exists($filename_gif);
	$exist_jpg = file_exists($filename_jpg);
	$exist_png= file_exists($filename_png);
			if ($exist_png==true) {
				$filename = $filename_png;
				$filesrc = '/pictures/img/'.$pr_id[$i].'.png';
			}
			elseif($exist_jpg==true) {
				$filename = $filename_jpg;
				$filesrc = '/pictures/img/'.$pr_id[$i].'.jpg';
				//$filesrc="http://".$_SERVER['HTTP_HOST'].Yii::app()->createUrl('imagetools/watermark', array('img'=>$pr_id[$i].'.jpg'));
			}
			elseif($exist_gif==true) {
				$filename = $filename_gif;
				$filesrc = '/pictures/img/'.$pr_id[$i].'.gif';
			}

			//echo "<img src=\"$filesrc\" style=\"max-height:60px\">";
			$picture = "<img border=\"0\" src=\"$filesrc\" style=\"max-height:420px; max-width:450px\" title=\"".$product_name[$i]."\">";
			echo CHtml::link($picture, array('/product/details/','pd'=>$pr_id[$i]), $htmlOptions=array ('encode'=>false, 'alt'=>$product_name[$i]));

	?></li><?php
}////////  for ($i=0; $i<count($pr_id); $i++) {
?>
  
  <?
}////////////////////count product_name
}//////////if(isset($models)) { 
?>
</ul>
    </div>


 <script>
 $(document).ready(function(){    
           
			$("#slider").easySlider({
                auto: true, 
                continuous: true
            });
			
        });  
 </script>