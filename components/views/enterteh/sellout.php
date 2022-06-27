<?php
if(isset($products)) {
?>
<div class="leftpanelblock2" align="center">
      <div class="blockheader4"><?php echo $title;?></div>

<?php //////
$cells = 4;  ///////Количество ячеек в одно строке
?>
	<?php
	for ($i=0; $i<count($products); $i++) {
		$k = $i+1;
		?>
        <div class="sellout_cell">
        <div class="sellout_cell_body">
        <table border="0" style="overflow:inherit" cellpadding="0" cellspacing="0">
        <tr><td colspan="3" align="center"><span>
        <?php
    echo $products[$i]->belong_category->category_name;
	?></span><br>
        <?php
		//echo CHtml::link($products[$i]->product_name, array('product/details', 'alias'=>$products[$i]->belong_category->alias, 'pd'=>$products[$i]->id));
		 $url=urldecode(Yii::app()->createUrl('product/details' ,array('alias'=>$products[$i]->belong_category->alias, 'path'=>FHtml::urlpath($products[$i]->belong_category->path)  ,  'pd'=>$products[$i]->id) ) );
		$str_len = strlen(trim($products[$i]->product_name));
		if($str_len>60) $pr_name = mb_substr($products[$i]->product_name, 0, 60, 'utf-8').'...';
		else $pr_name = $products[$i]->product_name;
		echo CHtml::link($pr_name, $url);
		?>
        </td></tr>
		<tr>
		  <td valign="top"><br><span style="color:#666; font-weight:normal">Цена:</span>&nbsp;<del style="color:#F00; font-size:15px;"><?php 
	echo "".str_replace(',00', '', FHtml::encodeValuta($products[$i]->product_price, 'руб.'));
    ?></del></td>
    <td height="125" rowspan="3" valign="top">
     <div class="sellout_ostatki">
     <div style="background-color:#ffaa00; width:25px; height:22px; float:left; margin-top:4px; line-height:22px" align="center">ещё</div>
       <div style="background-color:#ffaa00; width:30px; height:30px; float:left; font-size:17px; font-weight:bold" align="center"><?php
       echo $products[$i]->number_in_store;
	   ?></div>
      <div style="background-color:#ffaa00; width:25px; height:22px; float:left; margin-top:4px; line-height:22px" align="center">шт</div>
     </div>
      <?php
							$iconname = Yii::app()->request->baseUrl."/pictures/add/icons/".$products[$i]->icon.'.png';
									//echo $_SERVER['DOCUMENT_ROOT'].$iconname;
									if (file_exists($_SERVER['DOCUMENT_ROOT'].$iconname)==1) echo CHtml::link("<img src=\"$iconname\" class=\"content_img_130\" />", array('product/details', 'alias'=>$products[$i]->belong_category->alias, 'pd'=>$products[$i]->id));
							?>
     
      </td>
    </tr>
		<tr>
		  <td valign="top">	<?php
          echo "<span class=\"sellout_price\">".str_replace(',00', '', FHtml::encodeValuta($products[$i]->sellout_price, 'руб.')).'</span>';?></td>
		  </tr>
		<tr>
		  <td valign="bottom">
           <?php
         if( $products[$i]->sellout_active_till_int>0) {
			 $dif = $products[$i]->sellout_active_till_int - time();
		  ?><div>
         
          <div class="counter_head">до конца распродажи</div>
          <table width="135" border="0" cellspacing="1" cellpadding="1" bgcolor="#666666">
  <tr bgcolor="#FFFFFF" >
    <td width="45" align="center" valign="middle"><span class="sellout_number"><?php
    $days = round($dif/86400, 0);
	echo $days;
	$ost = ($dif/86400-$days)*86400;
	
	if($days<$dif/86400) {
		$hours = round(($ost/3600), 0);
		$ost_hours = $ost;
		$ost = (($ost/3600)-$hours)*3600;
		if($ost<0) {
			$ost = ($hours-($ost_hours/3600))*3600;
			$hours = $hours -1;
		}
	}
	
	?></span><br><span class="sellout_time">день</span></td>
    <td width="45" align="center" valign="middle"><span class="sellout_number"><?php
    if(isset($hours)) {
		echo $hours;
	}
	?></span><br><span class="sellout_time">час</span></td>
    <td width="45" align="center" valign="middle"><span class="sellout_number"><?php
    echo round($ost/60, 0);
	?></span><br><span class="sellout_time">мин</span></td>
  </tr>
</table>
         </div>
         <?php
		 }/////////  if( $products[$i]->sellout_active_till_int>0) {
		 ?>
          </td>
		  </tr>
		</table>
  </div><!--<div class="vitrina_cell_body">-->
  </div>

	<?php	
	if($k!=count($products) ) echo '<div style="height:220px; width:1px; float:left; background-color:#c9cacb; "></div>';
	
	
	if ($k/$cells == round($k/$cells, 0)  AND  $k!=count($products) ) echo "<div class=\"clear\"></div>";
	
	}//////for ($i=0; $i<count($products); $i++) {
?>  
<div class="clear"></div>


 </div>  
  <?php
		}
  ?>