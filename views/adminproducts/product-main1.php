<?php

Yii::app()->clientScript->scriptMap=array(
       // 'jquery.js'=>false,
	  'jquery.js'=>'/js/jquery-1.6.1.js',
	  'jquery.min.js'=>'/js/jquery-1.6.1.min.js',
);

?>

<script>
$(document).ready(function(){
//$("#faq-answer").animate({ opacity: "hide" }, "fast");
});

function collapse(el) {
//$(el).animate({ opacity: "hide" }, "slow");
$(el).hide();
}

function expand(el) {
$(el).show();
}

</script>
<div id="ribbon">&nbsp;<?
echo $path_text;
?>
</div>
<div id="Right_column" style="background-color:#666E73; width:60px; margin-left:0px">
<?
$RC = new RightColumnAdmin;
?>
</div>
<div id="mainContent" style="padding-left:3px; margin-left:70px ">

<div width="100%">

<div style="background-color:#F0F0F0; float:left;width:295px; padding:1px"><strong>Товары в группе</strong>

  <table   width="100%" border="0" cellspacing="2" cellpadding="2" background="/images/2x2.png"><?
for ($i=0; $i<count($products_in_gr); $i++ ) {

  echo "<tr ><td scope=\"col\"";
  if ($products_in_gr[$i]->id == $product->id) echo " bgcolor=\"#CCCCCC\" ";
	else  echo " bgcolor=\"#fffbf0\" ";
  echo ">";
  ?>
  <?
    $filename_gif = $_SERVER['DOCUMENT_ROOT'].'/pictures/img_small/'.$products_in_gr[$i]->id.'.gif';
	$filename_jpg = $_SERVER['DOCUMENT_ROOT'].'/pictures/img_small/'.$products_in_gr[$i]->id.'.jpg';
	$filename_png = $_SERVER['DOCUMENT_ROOT'].'/pictures/img_small/'.$products_in_gr[$i]->id.'.png';
	$exist_gif = file_exists($filename_gif);
	$exist_jpg = file_exists($filename_jpg);
	$exist_png= file_exists($filename_png);
	if ($exist_gif==false AND $exist_jpg==false AND $exist_png==false) {/////////////Файл не существует, нужно рисовать элемент для закачки
			echo "<img border=\"1\" src=\"http://".$_SERVER['HTTP_HOST']."/images/nophoto_h60.png\" height=\"30\">";
	}//////////Файл не существует, нужно рисовать элемент для закачки
	else {////////////////////Иначе рисуем картинку
			if ($exist_png==true) {
				$filename = $filename_png;
				$filesrc = '/pictures/img_small/'.$products_in_gr[$i]->id.'.png';
			}
			elseif($exist_jpg==true) {
				$filename = $filename_jpg;
				$filesrc = '/pictures/img_small/'.$products_in_gr[$i]->id.'.jpg';
			}
			elseif($exist_gif==true) {
				$filename = $filename_gif;
				$filesrc = '/pictures/img_small/'.$products_in_gr[$i]->id.'.gif';
			}

			//echo "<img src=\"$filesrc\" style=\"max-height:60px\">";
			$picture = "<img src=\"$filesrc\" border=\"1\" style=\"max-width:50px; max-height:30px\" title=\"".$products_in_gr[$i]->product_name."\">";
			echo CHtml::link($picture,  array('adminproducts/product', 'id'=>$products_in_gr[$i]->id, 'group'=>$group, 'char_filter'=>Yii::app()->getRequest()->getParam('char_filter')) , $htmlOptions=array ('encode'=>false, 'alt'=>$products_in_gr[$i]->product_name, 'style'=>'color:#000000'));
	}//////////////////else {//////Иначе рисуем картинку
	?>
  <?
    echo "</td><td scope=\"col\" valign=\"top\" ";
	if ($products_in_gr[$i]->id == $product->id) echo " bgcolor=\"#CCCCCC\" ";
	else  echo " bgcolor=\"#fffbf0\" ";
	echo ">".CHtml::link( $products_in_gr[$i]->id,array('adminproducts/product', 'id'=>$products_in_gr[$i]->id, 'group'=>$group, 'char_filter'=>Yii::app()->getRequest()->getParam('char_filter')), $htmlOptions=array ('encode'=>false, 'style'=>'font-family: Arial Narrow; text-decoration:none') )."</td>
    <td scope=\"col\" valign=\"top\" ";
	if ($products_in_gr[$i]->id == $product->id) echo " bgcolor=\"#CCCCCC\" ";
	else  echo " bgcolor=\"#fffbf0\" ";
	echo ">".CHtml::link( $products_in_gr[$i]->product_name,array('adminproducts/product', 'id'=>$products_in_gr[$i]->id, 'group'=>$group, 'char_filter'=>Yii::app()->getRequest()->getParam('char_filter')), $htmlOptions=array ('encode'=>false, 'style'=>'font-family: Arial Narrow; text-decoration:none') )."</td>
  </tr>";


}////////////for ($i=0; $i<count($products_in_gr); $i++ ) {
?></table>
</div>
<div style="margin-left:300px">

<h2 style="padding-bottom:0px">ID<?=$product->id?>,&nbsp;<?=$product->product_name?></h2>
<div style="background-color:#fffbf0; padding-left:5px; ">
<?
//////////////////////////////////////////Рисуем табки с фотограйиями
$tab = new CTabView;
$tab->tabs=array(
    'tab1'=>array(
          'title'=>'Основные',
          'view'=>'product-descr',
          'data'=>array('product'=>$product, 'measures'=>$measures, 'all_groups'=>$all_groups, 'group'=>$group, 'second_tree_list'=>@$second_tree_list, 'categories_products_second'=>@$categories_products_second, 'main_tree_list'=>@$main_tree_list, 'categories_products_main'=>@$categories_products_main),
    ),
/*	
    'tab2'=>array(
          'title'=>'Основн. изображения',
		  'view'=>'product-img',
          'data'=>array('product'=>$product, 'group'=>$group),
    ),
	
	'tab3'=>array(
          'title'=>'Дополнительные изображения',
		  //'view'=>'product-img-add',
		  'view'=>'product_images',
          'data'=>array('product'=>$product, 'group'=>$group, 'linked_pictures'=>$linked_pictures ),
    ),
	
	'tab4'=>array(
          'title'=>'Описание',
		  'view'=>'product-html',
          'data'=>array('product'=>$product, 'group'=>$group),
    ),

	'tab5'=>array(
          'title'=>'Параметры',
		  'view'=>'product-char',
          'data'=>array('product'=>$product, 'group'=>$group,  'parametrs'=>$parametrs, 'parametrs_product'=>$parametrs_product,'parametrs_values'=>$parametrs_values),
    ),
	
	'tab6'=>array(
          'title'=>'Рекомендуемые товары',
		  'view'=>'product-recomend',
          'data'=>array('product'=>$product, 'group'=>$group, 'compabile'=>$compabile),
    ),
	
	'tab7'=>array(
          'title'=>'Остатки',
		  'view'=>'product-store',
          'data'=>array('product'=>$product, 'group'=>$group,  'stores_list'=>$stores_list, 'triggers'=>$triggers),
    ),
	
	'tab8'=>array(
		   'title'=>'Варианты товара',
		  'view'=>'child-products',
          'data'=>array('product'=>$product, 'group'=>$group,  'stores_list'=>$stores_list, 'childs'=>$childs, 'parametrs_product'=>$parametrs_product, 'child_id'=>$child_id, 'childs_params'=>$childs_params, 'child_parametrs_values'=>$child_parametrs_values),
	),
	
		'tab9'=>array(
          'title'=>'Инструкции',
		  'view'=>'files',
          'data'=>array('product'=>$product, 'group'=>$group),
    ),
	
	'tab10'=>array(
          'title'=>'Комплекты',
		  'view'=>'product-packs',
          'data'=>array('product'=>$product, 'group'=>$group, 'compabile'=>$packs),
    ),

	*/
);


if(isset($_GET['activetab'])  ) $tab->activeTab = $_GET['activetab'];
$tab->run();
?>

</div>
</div>
<div style="height: 5px; clear:both">&nbsp;</div>
</div>

</div><!--<div id="mainContent" style="padding-left:3px; ">-->


<div style="height: 5px; clear:both">&nbsp;</div>


