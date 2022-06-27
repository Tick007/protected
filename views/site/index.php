<LINK rel=stylesheet type=text/css href="/highslide/hideslide.css">
 <SCRIPT type=text/javascript src="/highslide/highslide.js"></SCRIPT>
<SCRIPT type=text/javascript> 
  hs.graphicsDir = '/highslide/graphics/';
</SCRIPT>
&nbsp;
<?php $this->pageTitle=Yii::app()->name; ?>
<table width="100%" border="0" cellspacing="2" cellpadding="5">
  <tr>
    <td width="50%" valign="top"><div><div id="my_block_head_left">&nbsp;</div>
			<div id="my_block_head_right">&nbsp;</div>
			<div id="my_block_head_midle">Последние поступления</div><div id="my_block">
			  <br>
              <?
              if(isset($income_list )) {
			  $m=0;
foreach($income_list as $n=>$next):

$sgr[]=$next['sgr'];
$gr[]=$next['gr'];
$price_with_nds[]=$next['price_with_nds'];
$price_with_nds2[]=$next['price_with_nds2'];
$price_card[]=$next['price_card'];
 $product_name[]=$next['product_name'];
 $pr_id[]=$next['id'];
if (@$enable_suplier==1)  $supname[]=$next['supname'];
if (@$enable_buyer==1) $kname[]=$next['kname'];
if (@$detailed) $doc_id1[]=$next['doc_id1'];
if (@$detailed) $operation_dt[]=$next['operation_dt'];
//echo $next['id']." ".$next['product_name']."<br>";
 echo "<a href=\"/index.php?r=product/details&pd=".$next['id']."\">".$next['product_name']."...</a><br>";
 
/*for($k=0;$k<count($stores_id);$k++) {
$kk=$k+1;
$prst='prihod_store'.$kk;
$rhst='rashod_store'.$kk;
$sum_num[$kk][]=$next[$prst] - $next[$rhst];
}//////////for($k=0;$k<count($stores_id);$k++) {
*/
$m++;
//}//////////////while
endforeach;
}///////////////////////isset income
			  ?>
			</div></div><br><div><div id="my_block_head_left">&nbsp;</div>
			<div id="my_block_head_right">&nbsp;</div>
			<div id="my_block_head_midle">Новости</div><div id="my_block">
			  ewrwerew<br>  24352345
			  kopkj09
			</div></div></td>
    <td width="50%" valign="top"><div><div id="my_block_head_left">&nbsp;</div>
			<div id="my_block_head_right">&nbsp;</div>
			<div id="my_block_head_midle">Популярные товары</div><div id="my_block">
<br>
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
foreach($models as $n=>$next):
$sgr[]=$next['sgr'];
$gr[]=$next['gr'];
$price_with_nds[]=$next['price_with_nds'];
$price_with_nds2[]=$next['price_with_nds2'];
$price_card[]=$next['price_card'];
 $product_name[]=$next['product_name'];
 $pr_id[]=$next['id'];
/*for($k=0;$k<count($stores_id);$k++) {
$kk=$k+1;
$prst='prihod_store'.$kk;
$rhst='rashod_store'.$kk;
$sum_num[$kk][]=$next[$prst] - $next[$rhst];
}//////////for($k=0;$k<count($stores_id);$k++) {
*/
$m++;
//}//////////////while
endforeach;

if (!isset($num_in_one_row)) $num_in_one_row=3;

  if (count(@$product_name)) {////////Может вообще ничего нет для отображения
  $cells=count($product_name);
  $num_of_rows = $cells/$num_in_one_row;
?>

<table border="0" cellspacing="5" cellpadding="1" align="center" class="plainslim" width="auto">
<?
  if (round($num_of_rows,0)<$num_of_rows) $num_of_rows = round($num_of_rows,0)+1;
  else $num_of_rows = round($num_of_rows,0);
$i=0;  
$k=0;
  for ($r=1; $r<=$num_of_rows; $r++) {
  ?>
  <tr><?
for ($f=1;$f<=$num_in_one_row;$f++) {
  
  if (isset($pr_id[$k])) {///3
  $pers = 100/$num_in_one_row;
  ?>
    <td width="<?=$pers?>%" align="center" valign="bottom" height="100%"  bgcolor="#FFFFFF">

    <table border="0" cellspacing="1" cellpadding="1" height="100%" width="100%" align="center">
<form name="FORM_<?=$pr_id[$k]?>" method="post">
        <input name="show_group" type="hidden" value="<?=$gr[$k]?>">
<input type="hidden" name="item_to_add" value="<?=$pr_id[$k]?>">
          <tr>

          <td align="center" ><?
	if ($i<$cells) {
	echo "<a href=\"/index.php?r=product/details&pd=$pr_id[$k]\">";
	echo $product_name[$k];
	$product_name[$k]=NULL;
	echo "</a>";
	}//
	else echo "&nbsp;";
	?></td></tr>
<tr>
          <td align="center"><?
    
	$file_name=$_SERVER['DOCUMENT_ROOT']."/pictures/img_med/$pr_id[$k].jpg";
	$file_link="http://".$_SERVER['HTTP_HOST']."/pictures/img_med/$pr_id[$k].jpg";
	$fli_link_expand = "/pictures/img/$pr_id[$k].jpg";
	$fli_link_expand_check = $_SERVER['DOCUMENT_ROOT']."/pictures/img/$pr_id[$i].jpg";
	
	$fli_link_expand_med = "/pictures/img_med/$pr_id[$k].jpg";
	$fli_link_expand_check_med = $_SERVER['DOCUMENT_ROOT']."/pictures/img_med/$pr_id[$k].jpg";
	if (!@file_exists($fli_link_expand_check) AND @file_exists($fli_link_expand_check_med)) {
	$fli_link_expand = $fli_link_expand_med;
	$fli_link_expand_check = $fli_link_expand_check_med;
	} 
		 
		 if (file_exists($fli_link_expand_check)) echo "<a  href=\"$fli_link_expand\" class=\"hideslide\"  onclick=\"return hs.expand(this,	{ outlineType: 'rounded-white' })\">";
		
		if (file_exists($file_name)) echo "<img src=\"$file_link\"  border=\"0\" >";
		else echo "<img border=\"0\" src=\"http://".$_SERVER['HTTP_HOST']."/images/nophoto_h60.png\" height=\"60\">";
	if (file_exists($fli_link_expand_check)) echo "</a>";
	?><div class='highslide-caption' style="padding: 0 10px 10px 0">
		<?=@$product_name[$k]?>
    	
	</div></td>
        </tr>
        <tr>
          <td align="center" valign="bottom"><strong>Цена: <?=@$price_with_nds[$k]?>&nbsp;<?=Yii::app()->GP->GP_shop_currency_code?></strong>&nbsp;</td>
          <?

		  ?>
        </tr></form>
      </table>
    <?
     
	  ?></td>
      
    <?
    $k++;
	 }////////if (isset($pr_id[$k])) {///3
		
    }
	?>
  </tr>

  
  <?
  }/////for ($r=1; $r<$num_of_rows; $r++) {

  ?>
</table>
<?
}////////////////////count product_name
}//////////if(isset($models)) {
?>
</div></div></td>
  </tr>
  <tr>
    <td width="50%" valign="top">3</td>
    <td width="50%" valign="top">4</td>
  </tr>
</table>
