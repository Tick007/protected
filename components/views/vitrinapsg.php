<?
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
?>
<div align="center"><h2>Распродажа и спецпредложения</h2></div>
<?
  $cells=count($product_name);
  $num_of_rows = $cells/$num_in_one_row;
  for ($i=0; $i<count($pr_id); $i++) {
?>

  <div  style=" float:left; padding-bottom:10px; text-align:center; width:180px; height:270px; display:inline-block">
  <table width="170" border="0" cellspacing="3" cellpadding="3" align="center">
    <tr>
      <td ><?
 $filename_gif = $_SERVER['DOCUMENT_ROOT'].'/pictures/img_small/'.$pr_id[$i].'.gif';
	$filename_jpg = $_SERVER['DOCUMENT_ROOT'].'/pictures/img_small/'.$pr_id[$i].'.jpg';
	$filename_png = $_SERVER['DOCUMENT_ROOT'].'/pictures/img_small/'.$pr_id[$i].'.png';
	$exist_gif = file_exists($filename_gif);
	$exist_jpg = file_exists($filename_jpg);
	$exist_png= file_exists($filename_png);
	if ($exist_gif==false AND $exist_jpg==false AND $exist_png==false) {/////////////Файл не существует, нужно рисовать элемент для закачки
			$picture =  "<img border=\"0\" src=\"http://yii-site/images/nophoto_200.png\" width=\"100\">";
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
			echo '<noindex>'.CHtml::link($picture, array('/product/details/','pd'=>$pr_id[$i]), $htmlOptions=array ('encode'=>false, 'alt'=>$product_name[$i])).'</noindex>';
	}//////////////////else {//////Иначе рисуем картинку
?></td>
    </tr>
    <tr>
      <td  style="font-family:Arial; font-weight:normal"><?=@CHtml::link($product_name[$i], array('/product/details/','pd'=>$pr_id[$i]), $htmlOptions=array ('encode'=>false, 'alt'=>$product_name[$i]))?></td>
      </tr>
      <tr><td >Цена <?php
      if (isset($price_with_nds[$i])) echo "<del>".@$price_card[$i]."</del> ".$price_with_nds[$i];
	  elseif(isset($price_card[$i])) echo $price_card[$i]; 
	  ?> руб.</td></tr>
  </table>
 
</div>
  <?
}////////  for ($i=0; $i<count($pr_id); $i++) {
?>
  
  <?
}////////////////////count product_name
}//////////if(isset($models)) {
?>
