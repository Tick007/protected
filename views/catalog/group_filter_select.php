<?php
	if(isset($CAT->title) AND trim($CAT->title) ) $this->pageTitle=$CAT->title;
else $this->pageTitle=$CAT->category_name;
	if(isset($CAT->description) AND trim($CAT->description) ) $this->pageDescription=$CAT->description;
	if(isset($CAT->keywords) AND trim($CAT->keywords) ) $this->pageKeywords=$CAT->keywords;
?>
<script>
function send_form(item_id){
//alert ('Добавлено в корзину');
num = '#quantity_'+item_id;
document.getElementById("add_to_basket").value=item_id;
document.getElementById("num_to_basket").value=$(num).val();
document.getElementById('cat_filtr').submit();
}
</script>
<?php
/*
if(isset($CAT)) {

  $filename_png = Yii::app()->request->baseUrl."/pictures/group_ico/".$CAT->category_id.'.png';
	 $filename_jpg = Yii::app()->request->baseUrl."/pictures/group_ico/".$CAT->category_id.'.jpg';
	if (file_exists($_SERVER['DOCUMENT_ROOT'].$filename_png) AND is_file($_SERVER['DOCUMENT_ROOT'].$filename_png) )  $filename= $filename_png;
	else if (file_exists($_SERVER['DOCUMENT_ROOT'].$filename_jpg) AND is_file($_SERVER['DOCUMENT_ROOT'].$filename_jpg) )  $filename= $filename_jpg;

if ( isset ($filename) AND  file_exists($_SERVER['DOCUMENT_ROOT'].$filename)==1 AND is_file($_SERVER['DOCUMENT_ROOT'].$filename) ) echo "<img src=\"$filename\" width=\"860\" height=\"220\"/>";
}
*/
?>


 <?php
//var_dump($filter);
//var_dump($filter_values);
//  var_dump();
 //echo $_SERVER['DOCUMENT_ROOT'].'/protected/views/catalog/filterviews/'.$CAT->category_id;
 if ( (isset($filter)==false OR trim($filter)=='' OR $filter ==NULL ) AND (isset($filter_values) AND empty($filter_values)==false AND $filter_values!=NULL  ) AND file_exists($_SERVER['DOCUMENT_ROOT'].'/protected/views/catalog/filterviews/'.$CAT->category_id.'.php' )) $this->renderPartial('filterviews/'.$CAT->category_id, array('CAT'=>$CAT, 'filter_values'=>$filter_values));
/* 
if (isset($filter_values)){
	echo "<ul>";
	for ($k=0; $k<count($filter_values); $k++)  {
		$filter_word = '';
		$digit=NULL;
		$str_len = mb_strlen($filter_values[$k]->value);
		//echo $str_len.'<br>';
		for($s=0; $s<$str_len; $s++) {
		//echo (mb_strlen($filter_values[$k]->value)).'<br>';
			$symbol = $filter_values[$k]->value[$s];
			$digit[] = dechex(ord($symbol));
		}
		$filter_word=implode('s',$digit);
		echo '<li>'.CHtml::link($filter_values[$k]->value, array('catalog/group2', 'alias'=>$CAT->alias, 'filter_group'=>$filter_values[$k]->id_caract, 'filter'=>$filter_word)).'</li>';
	}/////////for ($k=0; $k<count(
	echo "</ul>";
}//////////$filter_values
  */
  ?>



<?php
//print_r($products_attributes);

echo CHtml::beginForm(array('/catalog/group2', 'alias'=>$CAT->alias,  'filter_group'=>Yii::app()->getRequest()->getParam('filter_group'), 'filter'=>Yii::app()->getRequest()->getParam('filter') ),  $method='get', $htmlOptions=array('name'=>'cat_filtr', 'id'=>'cat_filtr'));
  echo CHtml::hiddenfield('add_to_basket', NULL);
   echo CHtml::hiddenfield('num_to_basket', NULL);


echo CHtml::endForm(); 


if (isset($products) AND  count($products)>0) {
if(isset($CAT)) {
	



  $filename_png = Yii::app()->request->baseUrl."/pictures/group_ico/".$CAT->category_id.'.png';
	 $filename_jpg = Yii::app()->request->baseUrl."/pictures/group_ico/".$CAT->category_id.'.jpg';
	if (file_exists($_SERVER['DOCUMENT_ROOT'].$filename_png) AND is_file($_SERVER['DOCUMENT_ROOT'].$filename_png) )  $filename= $filename_png;
	else if (file_exists($_SERVER['DOCUMENT_ROOT'].$filename_jpg) AND is_file($_SERVER['DOCUMENT_ROOT'].$filename_jpg) )  $filename= $filename_jpg;

if ( isset ($filename) AND  file_exists($_SERVER['DOCUMENT_ROOT'].$filename)==1 AND is_file($_SERVER['DOCUMENT_ROOT'].$filename) ) echo "<img src=\"$filename\" width=\"860\" />";
}


	?>
	<table class="catalog_contents" cellpadding="0" cellspacing="0"  border="0">
    <thead>
  <tr>
    <th scope="col" id="name" width="250px">Информация</th>
    <th scope="col" align="center">Цена</th>
    <th scope="col" width="150">Количество</th>
    <th scope="col" width="150">В корзину</th>
  </tr></thead>
 </table>


	<?php
	for ($i=0; $i<count($products); $i++) {
		?>
		 <div  <?php
        if (isset($products[$i]->product_sellout) AND $products[$i]->product_sellout==1) {
			echo "class=\"tr_hit\"";
		}
		?>><table class="catalog_contents" cellpadding="0" cellspacing="0">
		<tr>
    <td>
    	<?php
							$iconname = Yii::app()->request->baseUrl."/pictures/add/icons/".$products[$i]->icon.'.png';
									//echo $_SERVER['DOCUMENT_ROOT'].$iconname;
									if (file_exists($_SERVER['DOCUMENT_ROOT'].$iconname)==1) echo CHtml::link("<img src=\"$iconname\" class=\"content_img\" />", array('catalog/info', 'alias'=>$products[$i]->belong_category->alias, 'id'=>$products[$i]->id));
							 if (isset($products[$i]->product_sellout) AND $products[$i]->product_sellout==1) {
			?>
			<img src="/themes/bogajniki/img/hit.png" class="hit_icon" />
			<?php
		}
							?><br>
    
		<span class="product_name"><?php
		//echo CHtml::link($products[$i]->product_name, array('catalog/info', 'alias'=>$CAT->alias, 'id'=>$products[$i]->id));
		echo CHtml::link($products[$i]->product_name, array('catalog/info', 'alias'=>$products[$i]->belong_category->alias, 'id'=>$products[$i]->id));
		?></span><br>
        <span class="product_article">Артикул</span>
		<?php

		if (isset($products_attributes[$products[$i]->id])) {
		echo '<ul>';
		//////
		//$prod_chars = explode('#', $products[$i]->attribute_value2);
		$prod_chars = explode('#;#', $products_attributes[$products[$i]->id]);

		//print_r($prod_chars);
		$prod_chars_count = 0; /////////////Ограничивающий счетчик сколько выводить характеристик для каждого товара
		if (count($prod_chars_count)>0) {
			////////////
			for ($b=0; $b<count($prod_chars); $b++) {
				//echo $prod_chars[$b].'------------<br>';
				$char_ids = explode(';#;', $prod_chars[$b]);///////
				if ($characteristics_array[$char_ids[1]]['is_main']==0) {////////вытаскиваем только не те которые фиьтрационные
				//echo $prod_chars[$b].'<br>';
				if (isset($char_ids[1])  )	{
					//echo $characteristics_array[$char_ids[0]]['char_type'].' ';
					if(isset($characteristics_array[$char_ids[1]])) {
						if ($characteristics_array[$char_ids[1]]['char_type']==1) {
							if ($char_ids[0] == 1) {
								echo '<li>'.$characteristics_array[$char_ids[1]]['caract_name'].'</li>';
								$prod_chars_count++;
							}////if ($char_ids[1]) == 1) {
						}//////////if ($characteristics_array[$char_ids[0]]['char_type']==1) {
						else if ($characteristics_array[$char_ids[1]]['char_type']==3 OR $characteristics_array[$char_ids[1]]['char_type']==3 OR $characteristics_array[$char_ids[1]]['char_type']==4) {
							//echo '<li>'.$characteristics_array[$char_ids[1]]['caract_name'].': '.@iconv("UTF-8", "CP1251",$char_ids[0]).'</li>';
						echo '<li>'.$characteristics_array[$char_ids[1]]['caract_name'].': '.$char_ids[0].'</li>';
							$prod_chars_count++;
						}//////////////else if ($characteristics_array[$char_ids[0]]['char_type']==3) {
					}///////	if(isset($characteristics_array[$char_ids[1]])) {
					//echo $characteristics_array[$char_ids[0]]['caract_name'].'<br>';
				}///////////////if (isset($char_ids[0]))	{
			}//////if ($characteristics_array[$char_ids[1]]['is_main']==0) {////////вытаскиваем только не те которые фиьтрационные
			}//////////////////////////////$prod_charsfor ($b=0; $b<count($prod_chars); $b++) {
		}////////////if (count($prod_chars)>0) {
	echo '</ul>';		
	}/////////////if (isset($products[$i]->attribute_value2)) {//////////////Рисуем характеристики
	?>
	&nbsp;</td>
   <td align="right" style="padding-right:100px"><span class="group_price"><?php
	echo "<span class=\"group_price\">".str_replace(',00', '', FHtml::encodeValuta((isset($prices_by_childs[$products[$i]->id])?$prices_by_childs[$products[$i]->id]:$products[$i]->product_price), ' ')).'&nbsp;руб.</span>';
    ?></span></td>
    <td width="150" align="center"><?php
    echo CHtml::textfield('quantity['.$products[$i]->id.']', 1, array('class'=>'textfield', 'id'=>'quantity_'.$products[$i]->id));
	?></td>
    <td width="150" align="center"><?php
    echo CHtml::button( ' ', array('onClick'=>"send_form(".$products[$i]->id.")", "class"=>"add_cart"));
	?></td>
  </tr></table></div>
	<?php					
	}//////for ($i=0; $i<count($products); $i++) {
?>  


<?php
}
?>
