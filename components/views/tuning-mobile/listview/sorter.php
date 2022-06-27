<?php 

$Products_sort = Yii::app()->getRequest()->getParam('Products_sort', null);

if($Products_sort!=null) $sortp = str_replace('.desc', '', $Products_sort );
$attributes = $this->sortableAttributes;
$sort_class = 'asc';
if($sp = explode('.',$Products_sort)) {
	if(isset($sp[1])) $sort_class = $sp[1];
}


echo CHtml::openTag('div',array('class'=>$this->sorterCssClass,'id'=>'sort-by'))."\n";
echo '<label class="left">';
echo $this->sorterHeader===null ? Yii::t('zii','Sort by: ') : $this->sorterHeader;
echo '</label>';
echo "<ul>\n";
echo "<li>";
echo '<a href="#" class="';
if(isset($sortp) && isset($attributes[$sortp])) echo $sort_class;
echo '">';
if(isset($sortp) && isset($attributes[$sortp])) echo $attributes[$sortp];
else echo 'выберете';
echo '<span class="right-arrow"></span></a>';

//print_r($this);

echo "<ul>";



$sort=$this->dataProvider->getSort();

foreach($attributes as $name=>$label)
{
	echo "<li>";
	if(is_integer($name))
		echo $sort->link($label);
	else
		echo $sort->link($name,$label);
	echo "</li>\n";
}
echo "</ul></li></ul>";
echo $this->sorterFooter;
echo CHtml::closeTag('div');
?>