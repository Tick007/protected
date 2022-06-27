<script>
function send_form(item_id){
//alert ('Добавлено в корзину');
document.getElementById("add_to_basket").value=item_id;
document.forms.MyForm.submit();
}

function set_view(view) {
document.getElementById("ListForm[out_mode]").value=view;
document.forms.MyForm.submit();
}

</script>


<?
$this->pageTitle=$group_obj->category_name;
?>

<div id="Right_column">
<?
$RC = new RightColumn(2, 'L');
?>
</div>
  <div id="mainContent">
  <?
if (@trim($path_text)) echo $path_text;
?>

<?
if (count($subgroups)>0)  { /////////////////////Выводим список подчиненных груп, если они есть
CController::renderPartial( 'main', array('models'=>$subgroups));
//echo "<div style=\"clear:both\">&nbsp;</div>";
}
?>


<form name="MyForm" method="post" action="/product/<?=@$_GET["id"]?>">
<?
//echo count($models);
//print_r($models);
//echo CHtml::beginForm(); 
// if ($main_parametr_data!=NULL) 
// { 
 ?><br><bt>
 <span style="color:#FF0000; font-weight:bold">В каталоге представлена не вся продукция. Если вы не обнаружили интересующую вас модель, уточняйте наличие по телефону или в разделе прайс-листы !</span>
 <br><br>
 <div class="yiiForm">
<?
echo CHtml::hiddenField('add_to_basket');////////////Идентификатор добавляемого товара
?>
<table width="auto" border="0" cellspacing="1" cellpadding="1">
  
  <tr>
    <td width="230"><table width="210" border="0" cellspacing="0" cellpadding="0" >
        <tr height="33">
          <td background="/images/left_h_round.png" width="17">&nbsp;</td>
          <td bgcolor="#dcdadb"><nobr>Изменить вид</nobr>&nbsp;
            <input name="ListForm[out_mode]" id="ListForm[out_mode]" type="hidden" value=''></td>
          <td bgcolor="#dcdadb">&nbsp;</td>
          <td bgcolor="#dcdadb"><?
echo CHtml::button('button',$htmlOptions=array('value'=>'' ,'style'=>'background-image:url(/images/viewtable.gif); width:25px; height:19px; background-color:#FFFFFF; font margin:0; border:0px; cursor:pointer', 'onclick'=>'set_view(1)', 'title'=>'Вывести в форме таблицы' ));
?></td>
          <td bgcolor="#dcdadb">&nbsp;</td>
          <td bgcolor="#dcdadb"><?
echo CHtml::button('button',  $htmlOptions=array('value'=>'', 'style'=>'background-image:url(/images/viewtableimg.gif); width:25px; height:19px; background-color:#FFFFFF; font margin:0; border:0px; cursor:pointer', 'onclick'=>'set_view(2)' ));
?></td>
          <td bgcolor="#dcdadb">&nbsp;</td>
          <td bgcolor="#dcdadb"><?
echo CHtml::button('button',  $htmlOptions=array('value'=>'', 'style'=>'background-image:url(/images/viewbimg.gif); width:25px; height:19px; background-color:#FFFFFF; font margin:0; border:0px; cursor:pointer', 'onclick'=>'set_view(3)' ));
?></td>
          <td width="17" background="/images/right_h_round.png" >&nbsp;</td>
          </tr>
    </table></td>
    <td><?
echo CHtml::submitButton("Применить");
?></td>
    </tr>
  <?
//  echo $charact_list;
if (isset($charact_list)) {
$charact_list_keys =  array_keys($charact_list);
$charact_list_values = array_values ($charact_list);
 for ($i=0; $i<count($charact_list_keys);$i++) {
// $id = $charact_list_keys[$i];
  $char_id = $charact_list_keys[$i];
  ?>
  <tr>
    <td><strong><?=$charact_list_values[$i]?>
    </strong></td>
    <td><?
	//@print_r($cfid_arr[$char_id]);
	$vsego_char =count($char_values_list[$i]);
	for ($k=0; $k<$vsego_char ; $k++) {
	echo trim(substr($char_values_list[$i][$k],0,35));
	if (isset($cfid_arr[$char_id])) 
	{
	if (@$cfid_arr[$char_id][$k]==1) $checked = true;
	else $checked = false;
	}
	else $checked=false;
	if ($vsego_char >1) echo CHtml::checkBox("ListForm[cfid_arr][$char_id][$k]",$checked).'&nbsp;&nbsp;';

	}/////////	for ($k=0; $k<count($char_values_list[$i]); $k++) {
?></td>
    </tr>
 <?
 }/////////////if (isset($charact_list)) {
}
  ?>
</table>


<?

//while($next=mysql_fetch_object($res)) {
//for ($i=0; $i<count($models);$i++)
$m=0;
foreach($models as $n=>$next):
//$sum_prib = $sum_prib+$next->pribil;
//$sum_nds = $sum_nds+$next->nds_razn;
$article[]=$next['product_article'];
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

for($k=0;$k<count($stores_id);$k++) {
$kk=$k+1;
$prst='prihod_store'.$kk;
$rhst='rashod_store'.$kk;
$sum_num[$kk][]=$next[$prst] - $next[$rhst];
}//////////for($k=0;$k<count($stores_id);$k++) {

$m++;
//}//////////////while
endforeach;
?>
</div>
<br>
<?php
$this->widget('CLinkPager',array('pages'=>$pages) ); ?>
<br><?
//echo "view = ".$view.'<br>';
if ($view==1 OR $view==2) {
?><br>
<table width="100%" border="0" cellspacing="1" cellpadding="1" class="plain" bgcolor="#333333">
  <tr class="list_head_left">
    <!--<td width="22" rowspan="2">&nbsp;</td>--> 
    <td rowspan="2" width="10%" >Артикул</td>
    <td rowspan="2" width="50%" <?
    if ($view==2) echo "colspan=\"2\"";
	?>>Номенклатура</td>
    <?
    if(Yii::app()->GP->GP_show_prices==1) {
	?>
    <td bgcolor="#FFFFFF">Цена</td>
    <?
    }/////////////////////////if(Yii::app()->GP->GP_show_prices==1) {
	?>
    <td colspan="<?=count($stores_names)?>" bgcolor="#FFFFFF">Остатки</td>
  </tr>
  <tr bgcolor="#C1C1C1">
    <?
    if(Yii::app()->GP->GP_show_prices==1) {
	?>
    <td class="list_head_left">Розн</td>
    <?
    }/////////////if(Yii::app()->GP->GP_show_prices==1) {
	?>
<?

for($k=0;$k<count($stores_names);$k++) {
$kk=$k+1;
?>
    <td class="list_head_left" width="10%"><nobr><?=$stores_names[$k]?></nobr></td>
	<?
	}
	?>
  </tr>
<?


 for ($i=0; $i<$m; $i++) {
  $k=$i+1;
		  ?>
         
         <?
      //   echo CHtml::beginForm(); 
		 ?>
  <tr bgcolor="#FFFFFF">
    <!--<td align="center" valign="middle" width="22"  class="plainslim" style="margin:0; margin-top:0; margin-bottom:0; margin-left:0; margin-right:0">
    <?
 ////////////////   Проверка на подчиненные товары
 if (Yii::app()->GP->check_for_child_products($pr_id[$i])==0) {
	?><!--<input  type="button"  style="cursor:pointer" value="Купить" onclick="{send_form(<?=$pr_id[$i]?>)}" <?
?>>--><?
}///////////// if (!Yii::app()->GP->check_for_child_products($pr_id[$i])) {
else echo "<a href=\"/index.php?r=product/details&pd=$pr_id[$i]\">подробнее...</a>";


?><!--</td>-->
    <td align="left" valign="middle"  class="plainslim"><?=$article[$i]?></td>
    <?
  if ($view==2) { ?>
    <td align="left" valign="middle"  class="plainslim" width="5%">
<?
 $filename_gif = $_SERVER['DOCUMENT_ROOT'].'/pictures/img_small/'.$pr_id[$i].'.gif';
	$filename_jpg = $_SERVER['DOCUMENT_ROOT'].'/pictures/img_small/'.$pr_id[$i].'.jpg';
	$filename_png = $_SERVER['DOCUMENT_ROOT'].'/pictures/img_small/'.$pr_id[$i].'.png';
	$exist_gif = file_exists($filename_gif);
	$exist_jpg = file_exists($filename_jpg);
	$exist_png= file_exists($filename_png);
	if ($exist_gif==false AND $exist_jpg==false AND $exist_png==false) {/////////////Файл не существует, нужно рисовать элемент для закачки
			echo "<img border=\"0\" src=\"http://".$_SERVER['HTTP_HOST']."/images/nophoto_h60.png\" height=\"60\">";
	}//////////Файл не существует, нужно рисовать элемент для закачки
	else {////////////////////Иначе рисуем картинку
			if ($exist_png==true) {
				$filename = $filename_png;
				$filesrc = '/pictures/img_small/'.$pr_id[$i].'.png';
			}
			elseif($exist_jpg==true) {
				$filename = $filename_jpg;
				$filesrc = '/pictures/img_small/'.$pr_id[$i].'.jpg';
			}
			elseif($exist_gif==true) {
				$filename = $filename_gif;
				$filesrc = '/pictures/img_small/'.$pr_id[$i].'.gif';
			}

			//echo "<img src=\"$filesrc\" style=\"max-height:60px\">";
			$picture = "<img src=\"$filesrc\" style=\"max-height:60px\" title=\"".$product_name[$i]."\">";
			echo CHtml::link($picture, array('/product/details/','pd'=>$pr_id[$i]), $htmlOptions=array ('encode'=>false, 'alt'=>$product_name[$i]));
	}//////////////////else {//////Иначе рисуем картинку
?>
<div class='highslide-caption' style="padding: 0 10px 10px 0">
		<?=@$product_name[$i]?>
	</div></td>
    <?
    }/////if ($view==2) {
	?>
    <td valign="middle"  class="plainslim" width="100%"><!--<a href="/index.php?r=product/details&pd=<?=$pr_id[$i]?>"><?=@$product_name[$i]?></a>-->
    <?
    echo CHtml::link($product_name[$i], array('/product/details/','pd'=>$pr_id[$i]), $htmlOptions=array ('encode'=>false))
	?>
    </td>
    <?
    if(Yii::app()->GP->GP_show_prices==1) {
	?>
        <td> 
        <?
      
			  if (isset($price_with_nds[$i])) echo $price_with_nds[$i];
			  else echo $price_card[$i];
		
	  ?></td><?
      }//////////////if(Yii::app()->GP->GP_show_prices==1) {
	  ?>
   
    <?
for($k=0;$k<count($stores_id);$k++) {
$kk=$k+1;
?>
    <td><?
	
	if (Yii::app()->GP->GP_ostatki_mode==1) {
		if ($sum_num[$kk][$i]>0) echo $sum_num[$kk][$i];
		else echo "Звоните";
	}
	else {
	if ($sum_num[$kk][$i]>0) echo "в наличии";
	else echo "временно отсутствует";
	} //////////else {
	
	?></td>

	<?
	}//////////////for($k=0;$k<count($stores_id);$k++) {
	?>
  </tr><!--</form>-->
  <?
		}///////////for
		?>
</table>
<?
}/////////////if ($view==1 OR $view==2) {Табличный вид или маленькие иконки
else {//////////Большие иконки
 for ($i=0; $i<$m; $i++) {////////////для $view = 3
  $k=$i+1;
  
?><div  style=" float:left; padding-bottom:10px; text-align:center">
<table width="300px" border="0" cellspacing="3" cellpadding="3" align="center">
  <tr>
    <td colspan="3"><?=@$product_name[$i]?></td>
  </tr>
  <tr>
    <td colspan="3"><?
 $filename_gif = $_SERVER['DOCUMENT_ROOT'].'/pictures/img_med/'.$pr_id[$i].'.gif';
	$filename_jpg = $_SERVER['DOCUMENT_ROOT'].'/pictures/img_med/'.$pr_id[$i].'.jpg';
	$filename_png = $_SERVER['DOCUMENT_ROOT'].'/pictures/img_med/'.$pr_id[$i].'.png';
	$exist_gif = file_exists($filename_gif);
	$exist_jpg = file_exists($filename_jpg);
	$exist_png= file_exists($filename_png);
	if ($exist_gif==false AND $exist_jpg==false AND $exist_png==false) {/////////////Файл не существует, нужно рисовать элемент для закачки
			echo "<img border=\"0\" src=\"http://yii-site/images/nophoto_200.png\" width=\"200\">";
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
			$picture = "<img src=\"$filesrc\" style=\"max-width:200px\" title=\"".$product_name[$i]."\">";
			echo CHtml::link($picture, array('/product/details/','pd'=>$pr_id[$i]), $htmlOptions=array ('encode'=>false, 'alt'=>$product_name[$i]));
	}//////////////////else {//////Иначе рисуем картинку
?>
</td>
    </tr>
  <tr>
    <td align="center"> <?
      if (isset($price_with_nds[$i])) echo $price_with_nds[$i];
	  else echo $price_card[$i];
	  ?></td>
    <td align="center"><?=Yii::app()->GP->GP_shop_currency_code?></td>
    <td align="center"> <?
 ////////////////   Проверка на подчиненные товары
 if (Yii::app()->GP->check_for_child_products($pr_id[$i])==0) {
	?>
    <!--<input  type="image"  style="cursor:pointer; background:url(/images/shopping_cart_add.png); background-repeat:no-repeat; height:20px; width:20px" value=""  onclick="{send_form(<?=$pr_id[$i]?>)}" <?
?>>-->
<input  type="image" onClick="{send_form(<?=$pr_id[$i]?>)}" src="/images/basket_add.png"/>
<?
}///////////// if (!Yii::app()->GP->check_for_child_products($pr_id[$i])) {
else echo "<a href=\"/index.php?r=product/details&pd=$pr_id[$i]\">подробнее...</a>";
?></td>
  </tr>
</table>
</div>
<?
}////////////// for ($i=0; $i<$m; $i++) {////////////для $view = 3
?>
<div style="height: 5px; clear:both">&nbsp;</div>
<?
}///////////else {//////////Большие иконки
?>
<br/><br/>
<?php
echo CHtml::endForm();
 $this->widget('CLinkPager',array('pages'=>$pages)); ?>
    
    </div>

