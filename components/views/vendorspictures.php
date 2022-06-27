<?
if(isset($models)) {

//echo count($models);
//echo "<h3>Новинки</h3>";
for($i=0; $i<count($models); $i++) {
		
		$group_icon_src='/pictures/vendors/'.strtolower($models[$i]->value).'.png';
		$group_icon = $_SERVER['DOCUMENT_ROOT'].$group_icon_src;
		//echo $group_icon.;
		if(file_exists($group_icon) AND is_file($group_icon)) $gr_name = "<img src=\"$group_icon_src\" title=\"Пневмоинструменты ".$models[$i]->value."\" alt=\"Пневмоинструменты ".$models[$i]->value."\" style=\"max-width:150px\">";
		else  $gr_name = $models[$i]->value;
		if (@trim($gr_name)) {///////Выводим только если есть маленькая фотка
		echo '<div style="float:left; height:75px; border:1px; width:210px; margin:5px; padding:5px">';
		echo "<div align=\"center\">";
		
		//$gr_name = $models[$i]->category_name;
		//else echo 'нет картинки<br>';
		//echo $gr_name;
		echo CHtml::link($gr_name, array('/product/vendor/'.$models[$i]->value));
		echo "</div>";
		//echo CHtml::link($models[$i]->value, array('/product/vendor/'.$models[$i]->value));
		
		echo '</div>';
		}////////if (@trim($gr_name)) {///////Выводим только если есть маленькая фотка
}/////////////////for($i=0; $i<count($models); $i++) {
?>
<div style="clear:both"></div>

<?


} //////////if(isset($models)) {
?>