<div id="Right_column">
<?
$RC = new RightColumn(2, 'L');
?>
</div>
  <div id="mainContent">
<ul>
<?

//print_r($gr_arr_name);

for($i=0; $i<count($gr_arr_id); $i++) {
// echo '<li>'.trim(CHtml::link($gr_arr_parent[$i].' '.$gr_arr_name[$i].' '.$vendor, array('product/'.$gr_arr_id[$i].'/175/'.$gr_arr_list_vals[$i]), array('target'=>'_blank') )).'</li>';
?>
<li>
<?php
//print_r($gr_arr_id);


//if(trim($gr_alias[$gr_arr_id[$i]])!='') $urr = (Yii::app()->createUrl('product/list',  array( 'alias'=>$gr_alias[$gr_arr_id[$i]], 'ListForm'=>array('cfid_arr'=>array(Yii::app()->params['vendor_char_id']=>array($gr_arr_list_vals[$i]=>1)) ) )) );
//else $urr = (Yii::app()->createUrl('product/list',  array( 'alias'=>$gr_arr_id[$i], 'ListForm'=>array('cfid_arr'=>array(Yii::app()->params['vendor_char_id']=>array($gr_arr_list_vals[$i]=>1)) ) )) );


if(trim($gr_alias[$gr_arr_id[$i]])!='') $urr = (Yii::app()->createUrl('product/list',  array( 'alias'=>$gr_alias[$gr_arr_id[$i]], 'vendor'=>$vendor )) ); 
else $urr = (Yii::app()->createUrl('product/list',  array( 'alias'=>$gr_arr_id[$i], 'vendor'=>$vendor )) ); 



//$urr = Yii::app()->createUrl('product/list', array( 'alias'=>$gr_alias[$gr_arr_id[$i]], 'vendor'=>$vendor));

$url=CHtml::link($gr_arr_parent[$i].' -> '.$gr_arr_name[$i], $urr, array('target'=>'_blank'));
echo $url;


?>
</li>
<?php
}//////////////for($i=0; $i<count($gr_arr_id); $i++) {
?>
</ul>
<?php
$file =  $_SERVER['DOCUMENT_ROOT'].'/'.Yii::app()->request->baseUrl.'protected/views/product/vendors_article/'.strtolower($vendor).'.php';
//echo $file;

if (file_exists($file) AND is_file($file)) $this->renderPartial('vendors_article/'.strtolower($vendor)); 
?>
 </div>

