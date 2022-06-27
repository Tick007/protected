<script>
$(document).ready(function(){
//$("#faq-answer").animate({ opacity: "hide" }, "fast");
//$("#add_product").change(function(){

//  alert( $(this).text() );

//}).change();


});

function displaypopup(url){
window.open (url,"mywindow","location=0,status=0,scrollbars=1,width=600,height=600");
}

</script>
<style type="text/css">
<!--
.стиль1 {color: #FFFFFF}
-->
</style>

<div id="ribbon" style="margin-left:71px">&nbsp;
Оплата и доставка
</div>

<div id="Right_column" style="background-color:#666E73; width:60px; margin-left:0px">
<?
$RC = new RightColumnAdmin;
?>
</div>

<div id="mainContent" style="padding-left:3px; margin-left:70px ">

<table width="100%" border="0" cellspacing="1" cellpadding="1" class="plain" bgcolor="#333333">
<tr bgcolor="#FFFFFF">
<?php echo CHtml::beginForm(array('/adminpayment/addmethod/','method'=>@$method),  $method='post',$htmlOptions=array('name'=>'form1'));  
?>
    <td colspan="6" align="right">&nbsp;   
   <?
      echo CHtml::submitButton('добавить метод оплаты', $htmlOptions=array ('name'=>'add_method"' , 'alt'=>'Добавить', 'title'=>'Добавить'));
	?>
    
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
<?php echo CHtml::endForm(); ?></tr>
<tr bgcolor="#666E73">
  <td width="230" class="list_head_left стиль1">Метод</td>
    <td width="130" class="list_head_left стиль1">Активный</td>
    <td width="130" class="list_head_left стиль1">Платильщик</td>
    <td class="list_head_left стиль1">Варианты стоимости доставки</td>
    <td class="list_head_left стиль1">Сообщение HTML(для электронной почты)</td>
    <td width="130" class="list_head_left стиль1">&nbsp;</td>
</tr>
<?
for($i=0; $i<count($models); $i++) {
//echo "method = |$method|<br>";
//echo "payment_method_id = |".$models[$i]->payment_method_id."|<br>";
if ($method_id==$models[$i]->payment_method_id) {

echo CHtml::beginForm(array('/adminpayment/updatemethod/'),  $method='post',$htmlOptions=array('name'=>'form_control'));
echo CHtml::hiddenfield('method_id', $models[$i]->payment_method_id); 
?>
  <tr bgcolor="#FFFFFF">
    <td valign="top"><?php echo CHtml::textfield('payment_method_name', $models[$i]->payment_method_name,  $htmlOptions=array('encode'=>true, 'size'=>50 )  ) ?>    </td>
    <td align="center" valign="top" bgcolor="#FFFFFF"><label>
 <?php echo CHtml::checkBox('method_enabled', $models[$i]->enabled ? ' checked' : '')?>
      
    </label></td>
    <td valign="top"><?
    echo CHtml::dropDownList('payment_face', $models[$i]->payment_face, $payment_face_list);
	?></td>
    <td valign="top">
    <a href="#" onclick="{displaypopup('/nomenklatura?targetitem=usluga_id&targetform=form_control')}">
Подбор номенклатуры</a>
      <?
	 echo CHtml::hiddenfield('usluga_id'); 
	//if (trim($next[5])=='') $next[5] = NULL;
    $nomenklatura_array = explode('#',trim($models[$i]->nomenklatura_list));
	//print_r($nomenklatura_array);
	//echo $next[5].":".count($nomenklatura_array).", 0 = ".$nomenklatura_array[0]."<br>";
	if ($num_products=count($nomenklatura_array) AND trim($models[$i]->nomenklatura_list)!='' ) {
	echo "<table width=\"100%\" class=\"plain\" bgcolor=\"#000000\" border=\"0\" cellspacing=\"1\" cellpadding=\"0\"><tr bgcolor=\"#FFFFFF\"><td class=\"list_head_left\">услуга</td><td class=\"list_head_left\" align=\"center\">удалить</td></tr>";
	for ($k=0; $k<$num_products; $k++) {
	$product_id = $nomenklatura_array[$k];
	//echo 'product_id = '.$product_id.'<br>';
	echo "<tr bgcolor=\"#FFFFFF\"><td>";
	echo Yii::app()->GP->getproductname($product_id)."</td><td align=\"center\">";
	//<input name=\"del_tov\" type=\"checkbox\" value = \"".$product_id."\">
	echo CHtml::checkBox('del_tov['.$product_id.']');
	echo "</td></tr>";
	}///////////for ($i=0; $i<count($nomenklatura_array); $i++) {
	echo "</table>";
	}//////////if ($num_products=count($nomenklatura_array)) {
	?></td>
    <td valign="top"><label>
      <?php echo CHtml::textarea('message', $models[$i]->message,  $htmlOptions=array('encode'=>true,'cols'=>'45', 'rows'=>'5' )  ) ?>
    </label></td>
    <td valign="top"><?
      echo CHtml::submitButton('Сохранить', $htmlOptions=array ('name'=>'save_method' , 'alt'=>'Сохранить', 'title'=>'Сохранить'));
	?></td>
  </tr>
  
<?
}////////////////////if ($method == $models[$i]->payment_method_id) {
else {
?>
  <tr bgcolor="#FFFFFF">
    <td valign="top">
    <?
    echo CHtml::link('-> '.$models[$i]->payment_method_name, array('/adminpayment/index', 'method_id'=>$models[$i]->payment_method_id), $htmlOptions=array ('encode'=>false ) )?>    </td>
    <td align="center" valign="top" bgcolor="#FFFFFF"><?php echo CHtml::checkBox('method_enabled', $models[$i]->enabled ? ' checked' : '', array(' disabled'=>true))?></td>
    <td valign="top"><?
   if(isset($payment_face_list[$models[$i]->payment_face])) echo $payment_face_list[$models[$i]->payment_face];
   else 'Неопределено';
	?></td>
    <td valign="top"><?
    $nomenklatura_array = explode('#',trim($models[$i]->nomenklatura_list));
	//print_r($nomenklatura_array);
	//echo $next[5].":".count($nomenklatura_array).", 0 = ".$nomenklatura_array[0]."<br>";
	if ($num_products=count($nomenklatura_array) AND trim($models[$i]->nomenklatura_list)!='' ) {
	
	for ($k=0; $k<$num_products; $k++) {
	$product_id = $nomenklatura_array[$k];
	echo Yii::app()->GP->getproductname($product_id).'<br>' ;
	//if ($i<($num_products-1)) echo "<strong>;</strong> ";
	}///////////for ($i=0; $i<count($nomenklatura_array); $i++) {
	}//////////if ($num_products=count($nomenklatura_array)) {
	?></td>
    <td valign="top"><?
    echo $models[$i]->message;
	?></td>
    <td valign="top">&nbsp;</td>
  </tr>
    <?
	}/////////////////else /if ($method == $models[$i]->payment_method_id) {
}////////for($i=0; $i<count($models); $i++) {
  ?>
</table>

</div><!--<div id="mainContent" style="padding-left:3px; ">-->


<div style="height: 5px; clear:both">&nbsp;</div>

