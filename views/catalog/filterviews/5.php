<?php
if (isset($filter_values)){
	//print_r($filter_values);
	//echo "<ul>";
	for ($k=0; $k<count($filter_values); $k++)  {
		//echo $filter_values[$k]->value.'<br>';
		$filter_word = '';
		$digit=NULL;
		$str_len = mb_strlen($filter_values[$k]->value);
		//echo $str_len.'<br>';
		for($s=0; $s<$str_len; $s++) {
		//echo (mb_strlen($filter_values[$k]->value)).'<br>';
			$symbol = $filter_values[$k]->value[$s];
			$digit[] = dechex(ord($symbol));
		}
		if (is_array($digit) AND empty($digit)==false) $filter_word=implode('s',$digit);
		
//		$img="<img src=\"/pictures/banners/".$img_arr[$k].".jpg\">";
//		echo '<div class="gr5">'.CHtml::link($img.'<br>'.$filter_values[$k]->value, array('catalog/group2', 'alias'=>$CAT->alias, 'filter_group'=>$filter_values[$k]->id_caract, 'filter'=>$filter_word)).'</div>';
		//$link[$k]=Yii::app()->createUrl('catalog/group2', array( 'alias'=>$CAT->alias, 'filter_group'=>$filter_values[$k]->id_caract, 'filter'=>$filter_word));
		$link[$k]=Yii::app()->createUrl('catalog/group2', array( 'alias'=>$CAT->alias, 'filter_group'=>$filter_values[$k]->id_caract, 'filter'=>$filter_word));
	}/////////for ($k=0; $k<count(
	//print_r(CHtml::listdata($filter_values, 'value', 'value'));
	//echo "</ul>";
}//////////$filter_values
//print_r($link);

?>
<div >
<img src="/pictures/banners/5.jpg" border="0" usemap="#mymap">
<map name="mymap"><area shape="rect" coords="697,90,848,169" href="<?php echo @$link[0]?>" alt="Venus" /><area shape="rect" coords="281,119,355,203" href="<?php echo @$link[2]?>" alt="Mercury" />
  <area shape="rect" coords="1,10,254,172" href="<?php echo @$link[1]?>" alt="Sun" />
  <area shape="rect" coords="141,202,467,377" href="<?php echo @$link[2]?>" alt="Mercury" />
  <area shape="rect" coords="589,167,847,390" href="<?php echo @$link[0]?>" alt="Venus" />
  
    <area shape="rect" coords="385,5,651,166" href="<?php echo @$link[3]?>" alt="Venus" />
</map>
</div>
<?php
if(isset($CAT->page) AND isset($CAT->page->contents)) echo$CAT->page->contents;
?>