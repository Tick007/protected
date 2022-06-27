
<table cellpadding="0" cellspacing="0" width="1255"  border="0" style="margin-left:8px">
<tr>
<td background="/themes/wood/images/left_half_round.png" height="39" width="20">&nbsp;</td>
<td bgcolor="#FFFFFF" style="text-align:center">
<?
/////////////Рисуем вендоров для верхнего меню
if(isset($models)) {

//echo count($models);
//echo "<h3>Новинки</h3>";

for($i=0; $i<count($models); $i++) {
		
		$group_icon_src='/pictures/vendors/'.strtolower($models[$i]).'.png';
		$group_icon = $_SERVER['DOCUMENT_ROOT'].$group_icon_src;
		//echo $group_icon.;
		if(file_exists($group_icon) AND is_file($group_icon)) $gr_name = "<img src=\"$group_icon_src\" title=\"Пневмоинструменты ".@$models[$i]->value."\" alt=\"Пневмоинструменты ".@$models[$i]->value."\" style=\" max-height:35px\" border=\"0\">";
		else  $gr_name = $models[$i];
		if (@trim($gr_name)) {///////Выводим только если есть маленькая фотка
		echo '<div style="float:left">';
		echo "<div align=\"center\">";
		
		//$gr_name = $models[$i]->category_name;
		//else echo 'нет картинки<br>';
		//echo $gr_name;
		echo CHtml::link($gr_name, array('product/vendor', 'alias'=>$models[$i]));
		echo "</div>";
		//echo CHtml::link($models[$i]->value, array('/product/vendor/'.$models[$i]->value));
		
		echo '</div>';
		}////////if (@trim($gr_name)) {///////Выводим только если есть маленькая фотка
		if ($i < (count($models)-1) ) echo "<div style=\"float:left; width:52px\">&nbsp;</div>";
}/////////////////for($i=0; $i<count($models); $i++) {
?>
<div style="clear:both"></div>

<?


} //////////if(isset($models)) {
?>
</td>
<td background="/themes/wood/images/right_half_round.png" height="39" width="20"></td>
</tr>
</table>