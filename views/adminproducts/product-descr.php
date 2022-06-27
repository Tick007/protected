<?
//function check_sub() {

//}//////////function check_sub() {
?>


<?php echo CHtml::beginForm(array('adminproducts/product_update_main', 'id'=>$product->id, 'group'=>$group,  'char_filter'=>Yii::app()->getRequest()->getParam('char_filter')),  $method='post',$htmlOptions=array('name'=>'MainParams', 'id'=>'MainParams'));  ?>
<table width="100%" border="0" cellspacing="1" cellpadding="1">
  <tr>
    <td width="125" valign="top">Группа:</td>
    <td colspan="3" valign="top"><?php
	//echo $product->category_belong;
	/*
    for ($k=0; $k<count($all_groups); $k++) {
			$all_groups_list[$all_groups[$k]->category_id] = $all_groups[$k]->category_name;
			if (count($all_groups[$k]->child_categories)>0) {
					for($g=0; $g<count($all_groups[$k]->child_categories); $g++) {
							$all_groups_list[$all_groups[$k]->child_categories[$g]->category_id] = '---'.$all_groups[$k]->child_categories[$g]->category_name;
									$subcateg=Categoriestradex::model()->with('child_categories')->findAll('t.parent = '.$all_groups[$k]->child_categories[$g]->category_id);
									if (isset($subcateg)) {
											for ($p=0; $p<count($subcateg); $p++) {
													$all_groups_list[$subcateg[$p]->category_id] = '------'.$subcateg[$p]->category_name;
													if (isset($subcateg[$p]->child_categories)) {
															for ($m=0; $m<count($subcateg[$p]->child_categories); $m++) {///////
																	$all_groups_list[$subcateg[$p]->child_categories[$m]->category_id] = '---------'.$subcateg[$p]->child_categories[$m]->category_name;															
																	}//////for ($m=0; $m<count(subcateg[$p]->child_categories); $m++) {
													}/////////if (isset($subcateg[$p]->'child_categories')) {
											}///////for ($p=0; $p<count($subcateg); $p++) {
									}//////////if (isset($subcateg)) {
					}////////for($g=0; $g<count($all_groups[$k]->child_categories); $g++) {
			}////////if (count($all_groups[$k]->child_categories)) {
	} //////////for ($k=0; $k<count($all_groups); $k++) {
	//print_r($all_groups_list);
	*/
	//  echo CHtml::dropDownList('category_belong', $product->category_belong, $all_groups_list);

	$cat_bel =new ProductGroup($all_groups,  'category_belong', $product->category_belong,  'simple', 'MainqqqParams' );
	$cat_bel->Draw();
	
	
	?></td>
  </tr>
   <?php
  	if (isset(Yii::app()->params['main_tree_root'])) {
  ?>
  <tr>
    <td valign="top">Дерево 1</td>
    <td valign="top"><?php
    // echo CHtml::dropDownList('category_belong_main',  NULL, $main_tree_list);

	
	$cat_bel_main =new ProductGroup($all_groups,  'category_belong_main', NULL );
	$cat_bel_main->Draw();
	?></td>
    <td colspan="2" align="left" valign="top"><?php
    if (isset($categories_products_main)) {
		?>
        <table width="100%" border="1" cellspacing="1" cellpadding="1">
  <tr>
    <td>Группа</td>
    <td>Удалить</td>
  </tr>
  <?php
  for($i=0; $i<count($categories_products_main); $i++) {
  ?>
  <tr>
    <td><?php
    echo $categories_products_main[$i]->category->category_name;
	?></td>
    <td><?php
    echo CHtml::checkbox('del_second_linked_tree['.$categories_products_main[$i]->id.']', NULL);
	?></td>
  </tr>
   <?php
	}
  ?>
</table>

        <?php
	}
	?></td>
  </tr>
  <?php
	}
  	if (isset(Yii::app()->params['second_tree_root'])) {
  ?>
  <tr>
    <td valign="top">Дерево 2</td>
    <td valign="top"><?php
     echo CHtml::dropDownList('category_belong2', NULL, $second_tree_list);
	?></td>
    <td colspan="2" valign="top" align="left"><?php
    if (isset($categories_products_second)) {
		?>
        <table width="100%" border="1" cellspacing="1" cellpadding="1">
  <tr>
    <td>Группа</td>
    <td>Удалить</td>
  </tr>
  <?php
  for($i=0; $i<count($categories_products_second); $i++) {
  ?>
  <tr>
    <td><?php
    echo $categories_products_second[$i]->category->category_name;
	?></td>
    <td><?php
    echo CHtml::checkbox('del_second_linked_tree['.$categories_products_second[$i]->id.']', NULL);
	?></td>
  </tr>
   <?php
	}
  ?>
</table>

        <?php
	}
	?></td>
  </tr>
  <?php
	}/////////	if (isset(Yii::app()->params['second_tree_root'])) {
  ?>

  <tr>
    <td width="125" valign="top">Наименование:</td>
    <td valign="top"><?php echo CHtml::textarea('product_name', $product->product_name,  $htmlOptions=array('encode'=>true, 'rows'=>2, 'cols'=>50, 'style'=>"font-family:Tahoma" )  ) ?></td>
    <td valign="top">Код:</td>
    <td valign="top"><strong><?php echo $product->id?></strong></td>
  </tr>
  <tr>
    <td width="125" valign="top">Артикул:</td>
    <td valign="top"><?php echo CHtml::textfield('product_article', $product->product_article,  $htmlOptions=array('encode'=>true, 'size'=>50 )  ) ?></td>
    <td valign="top">Дата добавления</td>
    <td valign="top"><?php echo $product->created?></td>
  </tr>
  <tr>
    <td width="125" valign="top">Ед.измерения</td>
    <td valign="top"><?
	$measures_list[0]='...';
	for($k=0; $k<count($measures); $k++) $measures_list[$measures[$k]->id]=$measures[$k]->measure;
	//print_r($measures_list);
   echo CHtml::dropDownList('measure', $product->measure, $measures_list);
	?></td>
    <td valign="top">&nbsp;</td>
    <td valign="top">&nbsp;</td>
  </tr>
  <tr>
    <td width="125" valign="top">НДС продажи</td>
    <td valign="top"><?php echo CHtml::textfield('nds_out', $product->nds_out,  $htmlOptions=array('encode'=>true, 'size'=>10 )  ) ?></td>
    <td valign="top">&nbsp;</td>
    <td valign="top">&nbsp;</td>
  </tr>
  <tr>
    <td width="125" valign="top">Цена </td>
    <td colspan="3" valign="top">
      <table width="auto" border="1" cellspacing="1" cellpadding="1">
      <tr>
        <td>Текущая</td>
        <td>Старая</td>
        <td>Рекомендованная</td>
      </tr>
      <tr>
        <td><?php echo CHtml::textfield('product_price', $product->product_price,  $htmlOptions=array('encode'=>true, 'size'=>10 )  ) ?></td>
        <td><?php echo CHtml::textfield('product_price_old', $product->product_price_old,  $htmlOptions=array('encode'=>true, 'size'=>10 )  ) ?></td>
        <td><?php
		 echo CHtml::textfield('product_price_recomended', @$product->product_price_recomended,  $htmlOptions=array('encode'=>true, 'size'=>10 )  ) ?></td>
      </tr>
    </table>
    <p>&nbsp;</p></td>
  </tr>
  <tr>
    <td valign="top">Склад</td>
    <td valign="top"><?php echo CHtml::textfield('number_in_store', $product->number_in_store,  $htmlOptions=array('encode'=>true, 'size'=>10 )  ) ?></td>
    <td valign="top">&nbsp;</td>
    <td valign="top">&nbsp;</td>
  </tr>
  <tr>
    <td height="30" valign="top">Товар включен</td>
    <td valign="top">
    <?
echo CHtml::checkBox('product_visible', ($product->product_visible == 1) ? $checked=true : $checked=false);
?></td>
    <td valign="top">&nbsp;</td>
    <td valign="top">&nbsp;</td>
  </tr>
  <tr>
    <td valign="top">Ключи и сортировки</td>
    <td colspan="3" valign="top"><table width="auto" border="1" cellspacing="1" cellpadding="1">
      <tr>
        <td>Общая сортировка</td>
        <td colspan="2">Витрина</td>
        <td colspan="2">Новинка</td>
        <td colspan="4">Распродажа</td>
        <td colspan="2">Хит</td>
        </tr>
      <tr>
        <td align="center"><?php echo CHtml::textfield('sort', $product->sort,  $htmlOptions=array('encode'=>true, 'size'=>2 )  ) ?></td>
        <td align="center"><?php
echo CHtml::checkBox('product_vitrina', ($product->product_vitrina== 1) ? $checked=true : $checked=false);
?></td>
        <td align="center"><?php echo CHtml::textfield('product_vitrina_sort', $product->product_vitrina_sort,  $htmlOptions=array('encode'=>true, 'size'=>2 )  ) ?>&nbsp;</td>
        <td align="center"><?php
echo CHtml::checkBox('product_new', ($product->product_new== 1) ? $checked=true : $checked=false);
?></td>
        <td align="center"><?php echo CHtml::textfield('product_new_sort', $product->product_new_sort,  $htmlOptions=array('encode'=>true, 'size'=>2 )  ) ?>&nbsp;</td>
        <td align="center"><?php
echo CHtml::checkBox('product_sellout', ($product->product_sellout== 1) ? $checked=true : $checked=false);
?></td>
        <td align="center"><?php echo CHtml::textfield('product_sellout_sort', $product->product_sellout_sort,  $htmlOptions=array('encode'=>true, 'size'=>2 )  ) ?>&nbsp;</td>
        <td align="center">$<?php echo CHtml::textfield('sellout_price', $product->sellout_price,  $htmlOptions=array('encode'=>true, 'size'=>4, 'placeholder'=>'Цена' )  ) ?>&nbsp;</td>
        <td align="center"><?php 
		if(isset($product->sellout_active_till_int)) $datevalue  = date("d-m-Y", $product->sellout_active_till_int);

		$date_to = new MyDatePicker;
		$date_to->conf = array(
				'name'=>'sellout_active_till',
				'value'=>isset($datevalue)?$datevalue:'',
		// additional javascript options for the date picker plugin
				'options'=>array(
					'showAnim'=>'fold',
					'dateFormat'=>'dd-mm-yy',
		),
				'htmlOptions'=>array(
					'style'=>'height:18px; padding:1px; border:0px; width:70px',
					'placeholder'=>'Дата',
		),
		'language' => 'ru',
		);
		$date_to->init();

		?>&nbsp;</td>
        <td align="center"><?php
echo CHtml::checkBox('vitrina_key_1', ($product->vitrina_key_1== 1) ? $checked=true : $checked=false);
?></td>
        <td align="center"><?php echo CHtml::textfield('vitrina_key_1_sort', $product->vitrina_key_1_sort,  $htmlOptions=array('encode'=>true, 'size'=>2 )  ) ?></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td colspan="4" align="center" valign="top"><?
      echo CHtml::submitButton('Сохранить', $htmlOptions=array ('name'=>'save_main_parametrs' , 'alt'=>'Сохранить', 'title'=>'Сохранить'));
	?></td>
  </tr>
</table>
<?php echo CHtml::endForm(); ?>