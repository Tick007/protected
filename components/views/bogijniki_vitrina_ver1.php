<div style="padding-top:20px">


<div style="background-image:url(/images/4x4.png); background-repeat:repeat-y; background-position:left; background-position-x: -2px;">
<div style="background-image:url(/images/4x4.png); background-repeat:repeat-y; background-position:right  ">
<div class="vitrina_container">
	<?php
	for ($i=0; $i<count($products); $i++) {
		?>
        <div class="vitrina_cell">
        <table border="0" height="300" style="overflow:inherit">
		<tr>
    <td valign="top" height="150">
    	<?php
							$iconname = Yii::app()->request->baseUrl."/pictures/add/icons/".$products[$i]->icon.'.png';
									//echo $_SERVER['DOCUMENT_ROOT'].$iconname;
									if (file_exists($_SERVER['DOCUMENT_ROOT'].$iconname)==1) echo "<img src=\"$iconname\" class=\"content_img\" />";
							?></td></tr><tr><td>
     <span class="product_article">Артикул&nbsp;<?php echo $products[$i]->product_article?></span><br>
		<span class="product_name"><?php
		echo CHtml::link($products[$i]->product_name, array('catalog/info', 'alias'=>$products[$i]->belong_category->alias, 'id'=>$products[$i]->id));
		?></span>
       
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
							echo '<li><strong>'.$characteristics_array[$char_ids[1]]['caract_name'].':</strong> '.@iconv("UTF-8", "CP1251",$char_ids[0]).'</li>';
							//echo '<li><strong>'.$characteristics_array[$char_ids[1]]['caract_name'].'</strong>: '.$char_ids[0].'</li>';
							$prod_chars_count++;
						}//////////////else if ($characteristics_array[$char_ids[0]]['char_type']==3) {
					}///////	if(isset($characteristics_array[$char_ids[1]])) {
					//echo $characteristics_array[$char_ids[0]]['caract_name'].'<br>';
				}///////////////if (isset($char_ids[0]))	{
			}//////////////////////////////$prod_charsfor ($b=0; $b<count($prod_chars); $b++) {
		}////////////if (count($prod_chars)>0) {
	echo '</ul>';		
	}/////////////if (isset($products[$i]->attribute_value2)) {//////////////Рисуем характеристики
	?>
	&nbsp;</td></tr><tr>
    <td height="17"><?php
	echo "<span class=\"vitrina_price\">".FHtml::encodeValuta($products[$i]->product_price, ' ').'&nbsp;руб.</span>';
    ?></td>

  </tr></table></div>
	<?php					
	}//////for ($i=0; $i<count($products); $i++) {
?>  
<div style="clear:both"></div>
</div>
</div>
</div>
</div>
<!--<div class="vitrina_container">-->
