<?php

header("Content-type: text/xml");
echo "<?xml version=\"1.0\"  encoding=\"UTF-8\"?>\r\n";

if (isset($products) AND $groups) {
	
	?>
<yml_catalog date="<?php echo date('Y-m-d H:s')?>">
<shop>
<name><?php echo $_SERVER['HTTP_HOST']?></name>
<company><?php echo $_SERVER['HTTP_HOST']?></company>
<url>http://<?php echo $_SERVER['HTTP_HOST']?></url>
<currencies>
<currency id="RUR" rate="1"/>
</currencies>
<categories>
	<?php
	for ($k=0; $k<count($categories); $k++) {
		if($categories[$k]->category_id == $categories[$k]->parent) {
			echo 'Ошибка с группой '.$categories[$k]->category_id;
			exit();
		}
		if($categories[$k]->parent==Yii::app()->params['main_tree_root']) echo '<category id="'.$categories[$k]->category_id.'">'.htmlspecialchars($categories[$k]->category_name).'</category>';
		elseif(trim($categories[$k]->category_name)!='') echo '<category id="'.$categories[$k]->category_id.'" parentId="'.$categories[$k]->parent.'">'.htmlspecialchars($categories[$k]->category_name).'</category>';
	}
	?>
</categories>	
<offers>
<?php
for ($i=0; $i<count($products); $i++) { 

?>
<offer id="<?php echo $products[$i]->id?>" available="true">
	<url><?php
    $url=urldecode(Yii::app()->createAbsoluteUrl('product/details' ,array('alias'=>$products[$i]->belong_category->alias, 'path'=>FHtml::urlpath($products[$i]->belong_category->path), 'pd'=>$products[$i]->id ) ) );
	echo $url;
	?></url>
    <price><?php echo $products[$i]->product_price?></price>
    <currencyId>RUR</currencyId>
    <categoryId><?php echo $products[$i]->category_belong?></categoryId>
    <?php
	if(isset($pictures_list[$products[$i]->id]))   $iconname = Yii::app()->request->baseUrl."/pictures/add/".$pictures_list[$products[$i]->id]['pict_id'].'.'.$pictures_list[$products[$i]->id]['ext'];
	if (file_exists($_SERVER['DOCUMENT_ROOT'].$iconname)==1 AND is_file($_SERVER['DOCUMENT_ROOT'].$iconname)) {
			?>
			<picture>
            http://<?php echo $_SERVER['HTTP_HOST']?><?php echo $iconname?>
            </picture>
			<?php
	} 
	?>
    <name><?php echo htmlspecialchars($products[$i]->product_name)?></name>
    <description>
    <?php
    echo  htmlspecialchars($products[$i]->product_html_description);
	?>
    </description>
</offer>
<?php }////////for ($i=0; $i<count($products); $i++) {
?>
</offers>
	</shop>
    </yml_catalog>
	<?php
}//////////
?>