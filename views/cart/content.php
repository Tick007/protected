<?php
 $clientScript = Yii::app()->clientScript;
$clientScript->registerScriptFile(Yii::app()->request->baseUrl . '/js/jquery.maskedinput.js', CClientScript::POS_HEAD);
?><div id="Right_column">
<?
$RC = new RightColumn(4,'L');
?>
</div>
<script>
function form_checkout(id, val) {
//document.forms(0).submit();
document.getElementById('form1').submit();
}

$(document).ready(function(){
 $('#Cart_client_email_copy').keydown(function(event) {
        if (event.ctrlKey==true && (event.which == '118' || event.which == '86')) {
            alert('Наберите пожалуйста !');
            event.preventDefault();
         }
    });
});
</script>
<div id="mainContent" style="padding-left:3px;">
<h1>Корзина</h1>

<form  method="post" name="form_basket" id="form1" >	
 <table width="100%" border="0" cellspacing="1" cellpadding="1" class="plainslim" bgcolor="#000000">
  <tr bgcolor="#C4CDCE">
    <td>&nbsp;</td>
    <td>Удаление</td>
    <td>Наименование</td>
    <td>Цена</td>
    <?
	if(isset(Yii::app()->params['display_cart_ostatki']) AND Yii::app()->params['display_cart_ostatki']==true ) {
for($k=0;$k<count($stores_id);$k++) {
$kk=$k+1;
?>
    <td  bgcolor="#C4CDCE" class="plainslim"><nobr><?=substr($stores_names[$k],0,25)?></nobr></td>
    <?
	}
 }/////////	if(isset(Yii::app()->params['display_cart_ostatki']) AND Yii::app()->params['display_cart_ostatki']==true ) {
	?>
    <td>Кол.</td>
    <?php

	?>
    <td>Сумма</td>
  </tr>
  <?



$allow_rezerv=1;///Проверка на резервирование
$sum_all = 0;
$i=0;
foreach($models as $n=>$next):
//$product_price[]=$next['price_with_nds'];
//$product_price[] = Yii::app()->GP->get_actual_retail(1 , $next['id']);
//echo $next['price_card'];
if ($next['price_with_nds']>0) $product_price[]=$next['price_with_nds'];
elseif($next['store_price']>0) $product_price[] = $next['store_price'];
else $product_price[]=$next['price_card'];
$product_name[]=$next['product_name'];
$product_ids[]=$next['id'];
$product_gr[]=$next['category_belong'];

for($k=0;$k<count($stores_id);$k++) {
$kk=$k+1;
$prst='prihod_store'.$kk;
$rhst='rashod_store'.$kk;
$sum_num[$kk][]=$next[$prst] - $next[$rhst];
}//////////for($k=0;$k<count($stores_id);$k++) {

		$id_wares=$product_ids[$i];
		$sum_this = $products_nums_arr[$id_wares]*$product_price[$i];
		$sum_all = $sum_all + $sum_this;

		echo "  <tr bgcolor=\"#FFFFFF\">
    <td>";

    $filename_gif = $_SERVER['DOCUMENT_ROOT'].'/pictures/img_small/'.$id_wares.'.gif';
	$filename_jpg = $_SERVER['DOCUMENT_ROOT'].'/pictures/img_small/'.$id_wares.'.jpg';
	$filename_png = $_SERVER['DOCUMENT_ROOT'].'/pictures/img_small/'.$id_wares.'.png';
	$exist_gif = file_exists($filename_gif);
	$exist_jpg = file_exists($filename_jpg);
	$exist_png= file_exists($filename_png);
	if ($exist_gif==false AND $exist_jpg==false AND $exist_png==false) {/////////////Файл не существует, нужно рисовать элемент для закачки
			echo "<img border=\"1\" src=\"http://".$_SERVER['HTTP_HOST']."/images/nophoto_h60.png\" height=\"60\">";
	}//////////Файл не существует, нужно рисовать элемент для закачки
	else {////////////////////Иначе рисуем картинку
			if ($exist_png==true) {
				$filename = $filename_png;
				$filesrc = '/pictures/img_med/'.$id_wares.'.png';
			}
			elseif($exist_jpg==true) {
				$filename = $filename_jpg;
				$filesrc = '/pictures/img_med/'.$id_wares.'.jpg';
			}
			elseif($exist_gif==true) {
				$filename = $filename_gif;
				$filesrc = '/pictures/img_med/'.$id_wares.'.gif';
			}

			//echo "<img src=\"$filesrc\" style=\"max-height:60px\">";
			$picture = "<img src=\"$filesrc\" style=\"max-width:100px; border-radius:5px; margin:2px; border: 1px solid #000\" title=\"".$product_name[$i]."\">";
			
			$link_param = array('product/details');
			if (isset($group_obj->alias) AND trim($group_obj->alias)!='') $link_param['alias'] = $group_obj->alias;

			$link_param['pd']=$id_wares;
			
			echo CHtml::link($picture, $link_param, $htmlOptions=array ('encode'=>false, 'alt'=>$product_name[$i], 'style'=>'color:#000000', 'target'=>'_blank'));
	}//////////////////else {//////Иначе рисуем картинку

	echo "</td>
    <td align=\"center\">";
	echo "<input name=\"Cart[product_delete][$id_wares]\" type=\"checkbox\">";
	// CHtml::text('ListForm[main_parametr1]', $main_parametr_value[0], $main_parametr_data[0], $htmlOptions=array('encode'=>false, 'prompt'=>$main_param_name[0] ) )
	echo "</td>
    <td width=\"100%\">$product_name[$i]</td>
    <td>$product_price[$i]</td>";
if(isset(Yii::app()->params['display_cart_ostatki']) AND Yii::app()->params['display_cart_ostatki']==true ) {
for($k=0;$k<count($stores_id);$k++) {
$kk=$k+1;
?>
  <td><?
	if (Yii::app()->GP->GP_ostatki_mode==1) echo $sum_num[$kk][$i];
	else {
	if ($sum_num[$kk][$i]>0) echo "в наличии";
	else echo "временно отсутствует";
	} //////////else {
	?></td>
      <?
	}//////////////for($k=0;$k<count($stores_id);$k++) {
	}///////if(isset(Yii::app()->params['display_cart_ostatki']) AND Yii::app()->params['display_cart_ostatki']==true ) {
	
	echo"<td>";
	if (@!$make_order)  {////////	if (@!$make_order)  {2
	echo "<input name=\"Cart[quantity_pereschet][$id_wares]\" type=\"text\" class=\"textfield\" size=\"2\" maxlength=\"5\" value=\"";
	echo $products_nums_arr[$id_wares];
	echo "\">";
	}
	else  {
	echo $qtys{$id_wares};
	}////////else 
	echo "</td>
    <td>$sum_this</td>
  </tr>";
		
		if ($sum_num[$kk][$i]>$products_nums_arr[$id_wares]) $allow_rezerv=$allow_rezerv*1;
		$i++;
		endforeach;

?>
  <tr bgcolor="#C4CDCE">
    <td colspan="4" align="right"><b>Итого</b></td>
   <?php
   	if(isset(Yii::app()->params['display_cart_ostatki']) AND Yii::app()->params['display_cart_ostatki']==true ) {
   ?>
    <td align="right" colspan="<?=count($stores_id)?>">&nbsp;</td>
    <?php
	}/////////	if(isset(Yii::app()->params['display_cart_ostatki']) AND Yii::app()->params['display_cart_ostatki']==true ) {
    ?>
    <td>&nbsp;</td>
    <td><?=$sum_all ?>
    </td>
  </tr>
  <tr bgcolor="#C4CDCE">
    <?
  $colsp = count($stores_id)+6;
  ?>
    <td colspan="<?=$colsp?>" align="right" bgcolor="#FFFFFF"><input  type="Submit" value="Пересчитать">
    </td>
  </tr>
</table>
<?
if (Yii::app()->user->isGuest AND  is_null($no_register)==true ) {
?>
<input name="Cart[no_register]" style="width:250; height:25; font-size:14px; font-weight:bolder" id="no_register" type="submit" class="button" value="Продолжить без регистрации">
<?
}////////////////if (Yii::app()->user->isGuest) {
else if(!Yii::app()->user->isGuest OR is_null($no_register)==false   ) {
?>
<input name="Cart[exit_register]" style="width:250; height:25; font-size:14px; font-weight:bolder" id="exit_register" type="submit" class="button" value="Прервать оформление">
<br><br>
<?
if (isset($_POST['make_order'])) echo CHtml::errorSummary($model); 
?>
   <div class="fullineblock">
<div class="fullinheader">Оплата и доставка
</div>
<div  style="margin:4px">
<?php
echo '<div class="hidden_store">'.$form['reserv_sklad'].'</div><br>';
echo $form['payment_face'];
//print_r($form['payment_method']);
if (isset($_POST['Cart'])) {
$incoming = $_POST['Cart'];
//echo "incoming_payment_method = ".$incoming['payment_method'];
if (isset($form['payment_face']) ) echo $form['payment_method'];
if (isset($form['payment_method'])) echo $form['delivery_method'];
}/////////if (isset($_POST['Cart'])) {
?>
</div></div> <br>
<?php
if (isset($form['payment_face']) AND isset($form['payment_method'])  AND  isset($form['delivery_method']) AND isset($incoming['delivery_method']) AND  $delivery_method>0) {/////////////поля 
//print_r($private_face);
		?><br>
       
<?php




//////////////////Рисуем следующую часть только если не самовывоз
//echo $incoming['delivery_method'];
//var_dump(in_array($incoming['delivery_method'], Yii::app()->params['delivery_samovivoz']));
if(isset($incoming['delivery_method'])==true  AND ( ! isset(Yii::app()->params['delivery_samovivoz']) || in_array($incoming['delivery_method'], Yii::app()->params['delivery_samovivoz'])==false))  {

?>        
<div>
<div style="float:left"> 
<?php 
//print_r(Yii::app()->params['delivery_mail']);
//echo '<br>'.$incoming['delivery_method'].'<br>';



echo "<table>";
		if ($incoming['payment_face']==1) {
			if(isset(Yii::app()->params['delivery_mail']) AND in_array($incoming['delivery_method'], Yii::app()->params['delivery_mail'])==true) $val_arr = $private_face_mail;
			else $val_arr = $private_face;
			$val_lab = $private_face_labels;
		}
		else if ($incoming['payment_face']==2) {
			$val_arr = $urlico_face;
			$val_lab = $urlico_labels;
		}
		
	//	print_r($val_arr);
		
		for ($i=0; $i<count($val_arr); $i++) {
				$elname=$val_arr[$i];
				echo "<tr><td width=\"100\">".$attr_labels[$elname];//"</td><td>";
				//if (!Yii::app()->user->isGuest AND $elname=="client_email")  echo "";
				/*else*/ echo  $form[$elname];
				echo '</td></tr>';
				//echo  var_dump($elname).'</td></tr>';
		}
		echo "</table>";
?>
</div>
<div>

<?php
//echo '<pre>';
//print_r($form['attributeLabels']);
//echo '</pre>';
?>
  <table border="0" cellspacing="1" cellpadding="1">
  <tr>
    <td valign="top"><?php echo $attr_labels['order_adress1']?></td>
    <td valign="top"><?
	echo $form['order_adress1'];
	?></td>
  </tr>
  <tr>
    <td valign="top"><?php echo $attr_labels['order_adress2']?></td>
    <td valign="top"><?
	echo $form['order_adress2'];
	?></td>
  </tr>
  <tr>
    <td valign="top"><?php echo $attr_labels['primechanie']?></td>
    <td valign="top"><?
	echo $form['primechanie'];
	?></td>
  </tr>
</table>
</div></div>
<br>
<?php
}///////////////if(isset($incoming['[delivery_method'])==tru
else{
	
//	print_r(Yii::app()->params['delivery_samovivoz']);
//echo '<br>';
//echo $incoming['delivery_method'];
	
	?>
   <div class="fullineblock">
<div class="fullinheader">Контактная информация
</div>
<div  style="margin:4px">
<?php
//print_r($private_face_labels);
?>
<table cellpadding="0" cellspacing="0"  class="samovivoz">
    <?php
	    $val_arr = Yii::app()->params['cart']['samovivoz'];
	//$val_lab = $private_face_labels;
	?><tr><?php
	for ($i=0; $i<count($val_arr); $i++) {
				$elname=$val_arr[$i];
				//echo "<td><strong>".$val_lab[$i].'</strong</td>';
				echo "<td><strong>".Yii::app()->params['cart']['labels'][$elname].'</strong</td>';

	}
	?><tr></tr><?php
	for ($i=0; $i<count($val_arr); $i++) {
		$elname=$val_arr[$i];
		echo '<td>'.$form[$elname].'</td>';
	}
	?>
    </tr>
    </table>
    </div>
</div>
<br>
	<?php
	if(isset($samovivoz_product) AND empty($samovivoz_product)==false AND trim($samovivoz_product->product_full_descr)!='') {
	////////////////Спрашиваем только имя фамилию email и телефон
	
		echo '<div align="center">'.$samovivoz_product->product_full_descr.'</div>';
	}
}
		
		//echo "<div id=\"sidebar1_bdr\" style=\"float:left\">
		
		//</div></div>";
		echo "<br><div align=\"center\">";
		echo  "<input  name=\"make_order\" style=\"width:175; height:20; font-size:14px; font-weight:bolder\" id=\"make_order\" type=\"submit\" class=\"button\" value=\"Оформить заказ\"></div>";
}/////////////////////поля 
}//////////////if (isset($_POST)) {
?>
</form> 
</div><br><br><br>
 <script>
 $(document).ready(function(){
       // tooltip();//active les tooltip simple
	 if($("#Cart_client_tels")!=typeof(undefined)) $("#Cart_client_tels").mask("+7 (999) 999-9999");
});
 </script>
 


