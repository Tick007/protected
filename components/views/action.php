<?php
$iconname = Yii::app()->request->baseUrl."/pictures/add/".$PRODUCT->icon.'.'.$PRODUCT->ext;
?>
<style>
.indexpage{
	background-image:url(<?php echo $iconname?>);
	height:220px;
	width:860px;
	overflow:hidden;
	background-repeat:repeat-x;
}
</style>
<div class="indexpage">
<?php
if (isset($params['nored'])==false) {?>
<div class="action_red"><?php echo $PRODUCT->product_name?></div>
<?php
}
echo $PRODUCT->product_full_descr;
?>
</div>
