 <?php
 if (trim($product->product_html_title)) $this->pageTitle=$product->product_html_title;
 else $this->pageTitle=$product->product_name;
 if(isset($product->product_html_keywords)) $this->pageKeywords = $product->product_html_keywords;
 if(isset($product->product_html_description)) $this->pageDescription = $product->product_html_description; 
  
$clientScript=Yii::app()->clientScript;
$clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/fancybox/fancybox/jquery.mousewheel-3.0.4.pack.js', CClientScript::POS_HEAD);
$clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/fancybox/fancybox/jquery.fancybox-1.3.4.pack.js', CClientScript::POS_HEAD);
$clientScript->registerCssFile(Yii::app()->request->baseUrl.'/js/fancybox/fancybox/jquery.fancybox-1.3.4.css');
$clientScript->registerCssFile(Yii::app()->request->baseUrl.'/js/fancybox/style.css');
?>
<script>
$(document).ready(function() {
	$("a[rel=pictures_group]").fancybox({
				'transitionIn'		: 'none',
				'transitionOut'		: 'none',
				'titlePosition' 	: 'over',
				'titleFormat'		: function(title, currentArray, currentIndex, currentOpts) {
					return '<span id="fancybox-title-over">Image ' + (currentIndex + 1) + ' / ' + currentArray.length + (title.length ? ' &nbsp; ' + title : '') + '</span>';
				}
			});
});


function send_form(item_id){
//alert ('Добавлено в корзину');
num = '#quantity_'+item_id;
document.getElementById("add_to_basket").value=item_id;
document.getElementById("num_to_basket").value=$(num).val();
document.getElementById('cat_filtr').submit();
}

</script>

<?php
echo CHtml::beginForm(array('/catalog/info', 'alias'=>$CAT->alias, 'id'=>$product->id ),  $method='get', $htmlOptions=array('name'=>'cat_filtr', 'id'=>'cat_filtr'));
  echo CHtml::hiddenfield('add_to_basket', NULL);
  echo CHtml::hiddenfield('num_to_basket', NULL);
?>
<?php
echo CHtml::endForm(); 
if(count($childs)==0) {////////////Если нет подчиненных товаров
?>
<table border="0" cellspacing="5" cellpadding="0" class="catalog_contents">
  <tr>
    <td> <h1><?php
 echo $product->product_name;
 ?></h1></td>
    <td align="right" style="padding-right:30px"><?php
	echo "<span class=\"group_price\"><nobr>".str_replace(',00', '', FHtml::encodeValuta($product->product_price, ' ')).'&nbsp;руб.</nobr></span>';
    ?></td>
    <td width="150" align="center"><?php
    echo CHtml::textfield('quantity['.$product->id.']', 1, array('class'=>'textfield'));
	?></td>
    <td width="150" align="center"><?php
    echo CHtml::button( ' ', array('onClick'=>"send_form(".$product->id.")", "class"=>"add_cart"));
	?></td>
  </tr>
</table>
<?
}////////////
else {//////if(count($childs)==0) {, т.е. есть подчиненные товары
?>
 <h2><?php
 echo $product->product_name;
 ?></h2>Состав комплекта:
<table border="0" cellspacing="5" cellpadding="0" class="catalog_contents">
<?php
for ($k=0; $k<count($childs); $k++) {
?>
  <tr>
  <td><?php
							$iconname = Yii::app()->request->baseUrl."/pictures/add/icons_small/".$childs[$k]->icon.'.png';
							$bigfilename = Yii::app()->request->baseUrl."/pictures/add/".$childs[$k]->icon.'.'.$childs[$k]->ext;
							
									//echo $_SERVER['DOCUMENT_ROOT'].$iconname;
									if (file_exists($_SERVER['DOCUMENT_ROOT'].$iconname)==1 AND is_file($_SERVER['DOCUMENT_ROOT'].$iconname)) echo CHtml::link("<img src=\"$iconname\" class=\"product_img\" />", array('catalog/info', 'alias'=>$childs[$k]->belong_category->alias, 'id'=>$childs[$k]->id));
							
							?>
  </td>
    <td><?php
 echo $childs[$k]->product_name;
 ?></td>
    <td align="right" style="padding-right:30px"><?php
	echo "<span class=\"group_price\"><nobr>".str_replace(',00', '', FHtml::encodeValuta($childs[$k]->product_price, ' ')).'&nbsp;руб.</nobr></span>';
    ?></td>
    <td width="150" align="center"><?php
    echo CHtml::textfield('quantity['.$childs[$k]->id.']', 1, array('class'=>'textfield'));
	?></td>
    <td width="150" align="center"><?php
    echo CHtml::button( ' ', array('onClick'=>"send_form(".$childs[$k]->id.")", "class"=>"add_cart"));
	?></td>
  </tr>
  <?php
	}
  ?>
</table><br>
<?php
}//////else {//////i
?>
<div class="product_foto">
<?php
							$iconname = Yii::app()->request->baseUrl."/pictures/add/".$product->icon.'.'.$product->ext;
									//echo $_SERVER['DOCUMENT_ROOT'].$iconname;
									if (file_exists($_SERVER['DOCUMENT_ROOT'].$iconname)==1 AND is_file($_SERVER['DOCUMENT_ROOT'].$iconname)) echo CHtml::link("<img src=\"$iconname\" class=\"product_img\" alt=\"".$product->product_name."\"/>",  "http://".$_SERVER['HTTP_HOST'].$iconname, array('class'=>'preview', 'target'=>'_blank'));
							
							?></div>
                            <h3>Спецификация</h3>
         <div class="product_attributes">
          <?php
 if (isset($products_attributes[$product->id])) {
		echo "<ul class=\"product_info\">";
		//////
		//$prod_chars = explode('#', $products[$i]->attribute_value2);
		//$prod_chars = @explode('#;#', iconv("UTF-8", "CP1251", $products_attributes[$product->id]));
		$prod_chars = @explode('#;#', $products_attributes[$product->id]);
		
		//print_r($characteristics_array);
		$prod_chars_count = 0; /////////////Ограничивающий счетчик сколько выводить характеристик для каждого товара
		if (count($prod_chars_count)>0) {
			////////////
			for ($b=0; $b<count($prod_chars); $b++) {
				//echo $prod_chars[$b].'------------<br>';
				$char_ids = explode(';#;', $prod_chars[$b]);///////
				if ($characteristics_array[$char_ids[1]]['is_main']==0) {////////вытаскиваем только не те которые фиьтрационные
				//print_r($char_ids);
				//echo '<br>';
				if (isset($char_ids[1])  )	{
					//echo $characteristics_array[$char_ids[0]]['char_type'].' ';
					if(isset($characteristics_array[$char_ids[1]])) {
						// $characteristics_array[$char_ids[1]];
						if ($characteristics_array[$char_ids[1]]['char_type']==1) {
							if ($char_ids[0] == 1) {
								echo '<li>'.$characteristics_array[$char_ids[1]]['caract_name'].'</li>';
								$prod_chars_count++;
							}////if ($char_ids[1]) == 1) {
						}//////////if ($characteristics_array[$char_ids[0]]['char_type']==1) {
						else if ($characteristics_array[$char_ids[1]]['char_type']==3 OR $characteristics_array[$char_ids[1]]['char_type']==3 OR $characteristics_array[$char_ids[1]]['char_type']==4) {
							echo '<li>'.$characteristics_array[$char_ids[1]]['caract_name'].': '.$char_ids[0].'</li>';
							//echo '<li>'.$characteristics_array[$char_ids[1]]['caract_name'].': '.iconv("UTF-8", "CP1251",$char_ids[0]).'</li>';
							$prod_chars_count++;
						}//////////////else if ($characteristics_array[$char_ids[0]]['char_type']==3) {
					}///////	if(isset($characteristics_array[$char_ids[1]])) {
					//echo $characteristics_array[$char_ids[0]]['caract_name'].'<br>';
				}///////////////if (isset($char_ids[0]))	{
			}/////if ($characteristics_array[$char_ids[1]]['is_main']==0) {////////вытаскиваем только не те которые фиьтрационные
			}//////////////////////////////$prod_charsfor ($b=0; $b<count($prod_chars); $b++) {
		}////////////if (count($prod_chars)>0) {
			
	///////////////////Смотрим есть ли инструкции
	if (isset($product->files)) {
	for ($i=0; $i<count($product->files); $i++)  {
		if ($product->files[$i]->filetype1==1) {
			if (is_file($product->files[$i]->filepath) AND file_exists($product->files[$i]->filepath))	echo "<li style=\"margin-top:10px\">".CHtml::link('инструкция по установке', '/'.$product->files[$i]->filepath, array('target'=>'blank')).'</li>';
		}
	}
	}
	echo '</ul>';		
	}/////////////if (isset($products[$i]->attribute_value2)) {//////////////Рисуем характеристики
 ?><br>
  <div class="twopointline">&nbsp;</div>
  <br> <br>
 </div>

 <div style="float:left">   </div> 
<br>
  <?php
                        if (isset($pictures)) {///////////////Есть фотки
						?>
						  <?
						  for ($i=0; $i<count($pictures); $i++) {
							//if ($pictures[$i]->is_main != 1) {
								?><?php
							$filename = Yii::app()->request->baseUrl."/pictures/add/icons/".$pictures[$i]->picture.'.png';
							$iconname = Yii::app()->request->baseUrl."/pictures/add/".$pictures[$i]->picture.'.'.$pictures[$i]->img->ext;
							//echo $filename.'<br>';
						//$iconname = Yii::app()->request->baseUrl."/pictures/add/icons/".$pictures[$i]->picture.'.png';
						
								//	if (file_exists($_SERVER['DOCUMENT_ROOT']. $filename)==1) echo "<img src=\" $filename\" class=\"content_img\"   />";
								if (file_exists($_SERVER['DOCUMENT_ROOT']. $filename)==1)  echo CHtml::link("<img src=\"$filename\"/  class=\"content_img_product\">", "http://".$_SERVER['HTTP_HOST'].$iconname, array("rel"=>'pictures_group'));
								
									?>
									<?php
							//}///////////if ($pictures->is_main == 1) {
						  }//////////////////////  for ($i=0; $i<count($pictures); $i++) {
							  ?>
							  <?php
						}/////////  if (isset($pictures)) {///////////////Есть фотки
  ?>
 

<div style="clear:both"></div>



   <div class="twopointline">&nbsp;</div>
<?php
if (isset($product->product_full_descr) AND trim($product->product_full_descr)!='') echo $product->product_full_descr;
?>
   <div class="twopointline">&nbsp;</div>
  <br> 
  
   <?
 if (@$compabile!=NULL) {
 ?>
 <h3 class="grey1">Сопутствующие товары</h3>
 <div>
  <?php
  for($i=0; $i<count($compabile); $i++) {
		//if ($pictures[$i]->is_main != 1) {
								?>
<div class="linked_products">
  <table border="0" height="150" style="overflow:inherit" cellpadding="0" cellspacing="0">

		<tr>
    <td valign="top" height="125">
<?php
							$filename = Yii::app()->request->baseUrl."/pictures/add/icons/".$compabile[$i]->icon.'.png';
							$iconname = Yii::app()->request->baseUrl."/pictures/add/".$compabile[$i]->icon.'.'.$compabile[$i]->ext;
							//echo $filename.'<br>';
						//$iconname = Yii::app()->request->baseUrl."/pictures/add/icons/".$pictures[$i]->picture.'.png';
						
								//	if (file_exists($_SERVER['DOCUMENT_ROOT']. $filename)==1) echo "<img src=\" $filename\" class=\"content_img\"   />";
								if (file_exists($_SERVER['DOCUMENT_ROOT']. $filename)==1)  echo CHtml::link("<img src=\"$filename\"/  class=\"content_img_compabile\">", "http://".$_SERVER['HTTP_HOST'].$iconname, array('rel'=>'pictures_group'));
						?>	
                        </td></tr>
  <tr><td><span class="product_name"><?php
 // echo CHtml::link($compabile[$i]->compprod->product_name, '#');
  echo CHtml::link($compabile[$i]->compprod->product_name, array('catalog/info', 'alias'=>$compabile[$i]->compprod->belong_category->alias, 'id'=>$compabile[$i]->compprod->id));
  ?></span></td></tr>
    <tr><td> <span class="product_article">Артикул</span>
    <?php
  echo $compabile[$i]->compprod->product_article;
  ?>
    </td></tr>
 <tr>
    <td height="17"><?php
	echo "<span class=\"group_price\">".str_replace(',00', '', FHtml::encodeValuta($compabile[$i]->compprod->product_price, ' ')).'&nbsp;руб.</span>';
    ?></td></tr>
    <tr><td><?php
    echo CHtml::button( ' ', array('onClick'=>"send_form(".$compabile[$i]->compprod->id.")", "class"=>"add_cart"));
	?></td>

  </tr></table>	
	</div> 	<?php			
	}/////////////  for($i=0; $i<count($compabile); $i++) {
?> </div>  
<?php
  }///////////// if (@$compabile!=NULL) {
 ?>  
 
                            
                           

 
<?php
//if (isset($product->product_full_descr)) echo $product->product_full_descr;
 ?>
 
 <script>
this.imagePreview = function(){	

	xOffset = 100;
	yOffset = 30;

	$("a.preview").hover(function(e){
		//alert ('sdfsdf');
		this.t = this.title;
		this.title = "";	
		var c = (this.t != "") ? "<br/>" + this.t : "";
		$("body").append("<p id='preview'><img src='"+ this.href +"' alt='' />"+ c +"</p>");								 
		$("#preview")
			.css("top",(e.pageY - xOffset ) + "px")
			.css("left",(e.pageX + yOffset) + "px")
			.fadeIn("fast");						
    },
	function(){
		this.title = this.t;	
		$("#preview").remove();
    });	
	$("a.preview").mousemove(function(e){
		$("#preview")
			.css("top",(e.pageY - xOffset) + "px")
			.css("left",(e.pageX + yOffset) + "px");
	});			
};

$(document).ready(function(){
       // tooltip();//active les tooltip simple
       imagePreview();//active les tooltip image preview
        //screenshotPreview();//active les tooltip lien avec preview
});
</script>


 <br>

 
 