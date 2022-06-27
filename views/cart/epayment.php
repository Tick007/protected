<h2>Выберете валюту оплаты через платежный шлюз робокасса</h2>
<?php


//$Robokassa->showpaymentform();
//echo "<div><h2>Платёж через робокассу</h2>";
//$Robokassa->showpaymentlink();

//echo '<pre>';
//print_r($Robokassa->curr_list);
//echo '</pre>';

if (isset($Robokassa->curr_list)){
$rates = $Robokassa->rates_list;

//print_r($rates);

//$all = count($Robokassa->curr_list);
//$num =  $all/4;
//if (round($num, 0)<$num ) $num = round($num, 0) +1;
//echo $num;
//$counter=1;
?>
<table width="100%" border="0" cellpadding="1" cellspacing="1"><tr>
<?php
foreach($Robokassa->curr_list as $curr_code=>$curr) {
if(isset($curr['items'])) {	
?><td><?php
echo $curr['descr'];?></td><?php
}///////$curr['items']
}
?>
</tr><tr>
<?php
foreach($Robokassa->curr_list as $curr_code=>$curr) {
	
	?>
		<td valign="top" align="center">
		<?php
	
foreach($curr['items'] as $code=>$name) {
		
		if(isset($curr['items'])) {	
		//if (($counter/4) == round($counter/4, 0)) echo "<div class=\"inner\">";
		//echo "<div style=\"float:left; width:150px; height:50px; margin:10px\">";
		$img=$_SERVER['DOCUMENT_ROOT'].Yii::app()->request->baseUrl.'/images/robokassa/'.$code.'.png';
		if (is_file($img)==true AND file_exists($img)==true) {
				$img_url=Yii::app()->request->baseUrl.'/images/robokassa/'.$code.'.png';
				$pict = "<img src=\"$img_url\">";
		}
		else $pict =$code;
		//echo $img;
		$url = "https://merchant.roboxchange.com/Index.aspx?MrchLogin=".$Robokassa->mrh_login."&OutSum=".$Robokassa->out_summ."&InvId=".$Robokassa->inv_id."&Desc=".$Robokassa->inv_desc."&IncCurrLabel=".$code."&SignatureValue=".$Robokassa->crc.'&Encoding=utf-8';
	//echo $url; RL: http://test.robokassa.ru/Index.aspx
	//	$url = "http://test.robokassa.ru/Index.aspx?MrchLogin=".$Robokassa->mrh_login."&OutSum=".$Robokassa->out_summ."&InvId=".$Robokassa->inv_id."&Desc=".$Robokassa->inv_desc."&IncCurrLabel=".$code."&SignatureValue=".$Robokassa->crc.'&Encoding=utf-8';
		
	
		$word = $name.'<br>'.$rates[$code]['rate'].' '.$code;
		//echo CHtml::link($word, $url, array('target'=>'_blank', 'style'=>'font-weight:bold'));
		$txt = CHtml::link($pict , $url, array('target'=>'_blank'));
		//echo "</div>";
		//if (($counter/4) == round($counter/4) OR $counter!=0 OR $counter == $all) echo "</div>";
		
		$link[$counter] =$txt;
		?><div  style="background-color:#FFF; border: 1px solid #333;  margin:3px; height:35px">
		<?php
		echo $link[$counter];
		?></div>
		<?php
//$counter++;
}////////if(isset($curr['items'])) {	

}////////foreach($curr['items'] as $code=>$name) {
	?>
</td>
<?php
}//////////foreach($Robokassa->curr_list as $curr_code=>$curr) {
	?>

    </tr>
    
    </table>
	
	<?php
//echo "</div>";
/*
for ($k=1; $k<=$num; $k++) {
		echo "<div class=\"inner\">";
				for ($g=1; $g<=4; $g++) {
					if (isset($link[($k-1)*4+$g]))echo  $link[($k-1)*4+$g];
				}/////////////////for ($g=1; $g<=4; $g++) {
		echo "</div>";	
}/////////////for ($k=1; $k<=$num; $k++) {
*/

}
?>

