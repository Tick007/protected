<?php


if(isset($this->tree) && empty($this->tree)==false) foreach($this->tree as $category_id=> $category){
	$this->all_groups_list[$category_id]=$category['category_name']; 
	if(isset($this->levels[$category_id]))  $this->childs_simple($category_id, '-' );
}


//echo $this->value;
//echo '<pre>';
//print_r($this->levels);
//echo '</pre>';
$this->all_groups_list[0]='Root';
echo CHtml::dropDownList($this->elementname, $this->value, $this->all_groups_list);

?>&nbsp;&nbsp;&nbsp;<?php
	//echo CHtml::link('Select category', array('/nomenklatura/indexgr', 'root'=>2,  'targetitem'=>$this->elementname, 'targetform'=>$this->formtoupdate) , array('onclick'=>"return hs.htmlExpand(this, { objectType: 'iframe' } ) "));
?>