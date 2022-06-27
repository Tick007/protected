<?
if(isset($models)) {
$m=0;
$sgr=NULL;
$gr=NULL;
$price_with_nds=NULL;
$price_with_nds2=NULL;
$price_card=NULL;
 $product_name=NULL;
 $alias = NULL;
 $path = NULL;
 $pr_id=NULL;
// print_r($models);
$added_id=NULL;
foreach($models as $n=>$next):
//print_r($added_id);

if (!@in_array($next['id'], $added_id)) { /////////////Отсекаем те, которые попадаются во связанных группах
$sgr[]=$next['sgr'];
$gr[]=$next['gr'];
$price_with_nds[]=$next['price_with_nds'];
if(isset($next['price_with_nds2']))$price_with_nds2[]=$next['price_with_nds2'];
$price_card[]=$next['price_card'];
$product_name[]=$next['product_name'];
 $pr_id[]=$next['id'];
$alias[] = $next['alias'];
$path[]= $next['path'];

$added_id[]= $next['id'];
$m++;
//}//////////////while
}////////if (!@in_array($next['id'], $added_id)) { /////////////Отсекаем те, которые попадаются во связанных группах
endforeach;

//print_r($alias); 

if (!isset($num_in_one_row)) $num_in_one_row=1;

  if (count(@$product_name)) {////////Может вообще ничего нет для отображения
?><br>

 <div align="center"><img src="/themes/wood/images/new.png" /></div>
<?php
  for ($i=0; $i<count($pr_id); $i++) {
?>

  <div  style=" float:left; padding-bottom:10px; text-align:center; width:180px; height:215px">
  <table width="170" border="0" cellspacing="3" cellpadding="3" align="center">
  <!--
  <?php
      if (isset ($price_with_nds) AND $price_with_nds[$i] != 0 ) {
	  ?>
	  <tr><td colspan="3"align="center" style="color:#F00; background-color:#d9ad7c; font-weight:normal; font-size:13px">Акция</td></tr>
	  <?php
	  }
		  ?>
	  -->
    <tr>
      <td colspan="3"><?
 $filename_gif = $_SERVER['DOCUMENT_ROOT'].'/pictures/img_small/'.$pr_id[$i].'.gif';
	$filename_jpg = $_SERVER['DOCUMENT_ROOT'].'/pictures/img_small/'.$pr_id[$i].'.jpg';
	$filename_png = $_SERVER['DOCUMENT_ROOT'].'/pictures/img_small/'.$pr_id[$i].'.png';
	$exist_gif = file_exists($filename_gif);
	$exist_jpg = file_exists($filename_jpg);
	$exist_png= file_exists($filename_png);
	if ($exist_gif==false AND $exist_jpg==false AND $exist_png==false) {/////////////Файл не существует, нужно рисовать элемент для закачки
			$picture =  "<img border=\"0\" src=\"http://yii-site/images/nophoto_200.png\" width=\"200\">";
			echo '<noindex>'.CHtml::link($picture, array('/product/details/','pd'=>$pr_id[$i]), $htmlOptions=array ('encode'=>false, 'alt'=>$product_name[$i])).'</noindex>';
	}//////////Файл не существует, нужно рисовать элемент для закачки
	else {////////////////////Иначе рисуем картинку
			if ($exist_png==true) {
				$filename = $filename_png;
				$filesrc = '/pictures/img_med/'.$pr_id[$i].'.png';
			}
			elseif($exist_jpg==true) {
				$filename = $filename_jpg;
				$filesrc = '/pictures/img_med/'.$pr_id[$i].'.jpg';
			}
			elseif($exist_gif==true) {
				$filename = $filename_gif;
				$filesrc = '/pictures/img_med/'.$pr_id[$i].'.gif';
			}

			//echo "<img src=\"$filesrc\" style=\"max-height:60px\">";
			$picture = "<img border=\"0\" src=\"$filesrc\" style=\"max-height:100px; max-width:180px\" title=\"".$product_name[$i]."\">";
			
			if(isset(Yii::app()->params['use_long_urls']) AND Yii::app()->params['use_long_urls']==true AND isset($alias[$i]) ) {
				$url=urldecode(Yii::app()->createUrl('product/details' ,array('alias'=>$alias[$i],  'pd'=>$pr_id[$i]) ) );
			}
			else $url = urldecode(Yii::app()->createUrl('product/details' ,array( 'pd'=>$pr_id[$i]) ) );
				
			//echo '<noindex>'.CHtml::link($picture, array('/product/details/','pd'=>$pr_id[$i]), $htmlOptions=array ('encode'=>false, 'alt'=>$product_name[$i])).'</noindex>';
			echo '<noindex>'.CHtml::link($picture, $url).'</noindex>';
	
	}//////////////////else {//////Иначе рисуем картинку
?></td>
    </tr>
    <tr>
      <td colspan="3" style="font-family:Arial; font-weight:normal"><?=@CHtml::link($product_name[$i], array('/product/details/','pd'=>$pr_id[$i]), $htmlOptions=array ('encode'=>false, 'alt'=>$product_name[$i]))?></td>
      </tr>
      <?php
      if (isset ($price_with_nds) AND $price_with_nds[$i] != 0 ) {
	  ?>
        <tr><td colspan="3"><?php
      if (isset($price_with_nds[$i]) AND $price_with_nds[$i]!=$price_card[$i]) echo "<del style=\"font-weight:normal\">".@$price_card[$i]."</del> ".$price_with_nds[$i];
	  elseif(isset($price_card[$i])) echo $price_card[$i]; 
	  ?> руб.</td></tr>
     <?php
	  }///////if (isset ($price_with_nds)) {
	  ?>
  </table>
</div>
<?
	  }
?>


  
  <?
}////////////////////count product_name
}//////////if(isset($models)) {
?>
