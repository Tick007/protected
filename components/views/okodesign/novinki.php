<?php
if(isset($products)) {
?>



<?php //////
$cells = 3;  ///////Количество ячеек в одно строке
?>
	<?php
	for ($i=0; $i<count($products); $i++) {
		$k = $i+1;
		  $url=urldecode(Yii::app()->createUrl('product/details' ,array('alias'=>$products[$i]->belong_category->alias ,  'pd'=>$products[$i]->id) ) );
		?>
        <div class="vitrina_cell <?php
  if($k/$cells== round($k/$cells,0)) echo 'last';
  ?>">
      
        <div class="vitrina_cell_body">
       
    	<?php
							$iconname = Yii::app()->request->baseUrl."/pictures/add/icons/".$products[$i]->icon.'.png';
									//echo $_SERVER['DOCUMENT_ROOT'].$iconname;
									if (file_exists($_SERVER['DOCUMENT_ROOT'].$iconname)==1) echo CHtml::link("<img src=\"$iconname\" class=\"content_img_new\" />", $url);
							?>
  </div><!--<div class="vitrina_cell_body">-->
  </div>
	<?php	
	
	if ($k/$cells == round($k/$cells, 0)  AND  $k!=count($products) ) echo "<div class=\"clear\"></div>";
	
	}//////for ($i=0; $i<count($products); $i++) {
?>  
<div class="clear"></div>



  <?php
		}
  ?>