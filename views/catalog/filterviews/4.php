<?php
if (isset($filter_values)){
	//echo "<ul>";
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
		if (is_array($digit) AND empty($digit)==false) $filter_word=implode('s',$digit);
		//echo '<li>'.CHtml::link($filter_values[$k]->value, array('catalog/group2', 'alias'=>$CAT->alias, 'filter_group'=>$filter_values[$k]->id_caract, 'filter'=>$filter_word)).'</li>';
		$link[$k]=Yii::app()->createUrl('catalog/group2', array( 'alias'=>$CAT->alias, 'filter_group'=>$filter_values[$k]->id_caract, 'filter'=>$filter_word));
	}/////////for ($k=0; $k<count(
	//echo "</ul>";
}//////////$filter_values

?>



<div class="boxes_img">
<img src="/pictures/banners/4.jpg" border="0" usemap="#mymap">
<map name="mymap"><area shape="rect" coords="547,184,671,231" href="<?php echo $link[2]?>" alt="Venus" /><area shape="rect" coords="8,178,148,213" href="<?php echo $link[0]?>" alt="Sun" />
  <area shape="rect" coords="13,30,256,170" href="<?php echo $link[0]?>" alt="Sun" />
  <area shape="rect" coords="160,176,477,391" href="<?php echo $link[1]?>" alt="Mercury" />
  <area shape="rect" coords="389,3,696,168" href="<?php echo $link[2]?>" alt="Venus" />
</map>
</div>
<?php
if(isset($CAT->page) AND isset($CAT->page->contents)) echo$CAT->page->contents;
?>