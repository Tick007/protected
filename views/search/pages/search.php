
<div id="Right_column">

<?

$RC = new RightColumn(2,'L');

?>
</div>
<div id="mainContent">
<?
/*
echo '<pre>';
print_r($this->tree);
echo '</pre>';
*/
if ($search != '') {
		$all_sum = 0;
		if (isset($this->tree)) {
				$txt2 = '';
				foreach ($this->tree as $gr_id => $gr ) {
						//echo $gr['name'].' - ';
						$sum = $this->show_vetv($gr_id);
						//echo $sum[0].'<br>';
						if ($sum[0]>0) {
								$txt2 .= "<li>".$gr['name'];	
								$txt2 .= $sum[2];
								$txt2 .= "</li>";
							$all_sum = $all_sum + $sum[0];
						}//////if ($sum[0]>0)
				}/////////foreach ($this->tree as $gr_id => $gr ) {
		}/////	if (isset($this->tree)) {
		
		
		
		$txt1='';
		for($i=0; $i<count($models); $i++) {
				//if ($models[$i]->parent==0) {
						//echo $models[$i]->category_name.'<br>';
						$txt1 .='<li>'.CHtml::link($models[$i]->category_name, array('product/list', 'id'=>$models[$i]->category_id, 'search'=>$search)).'('.count($models[$i]->products).')</li>';
						$all_sum = $all_sum + count($models[$i]->products);
				//		}
				
		}////////////////////
		
		echo "<h3>Результаты поиска по \"<i>$search</i>\"(".$all_sum."):</h3><ul>";
		if (isset($txt1)) echo $txt1 ;
		if (isset($txt2)) echo $txt2 ;
						
		echo "</ul>";		
		/*
		for($i=0; $i<count($contr_agents); $i++) {
				if ($contr_agents[$i]->alias!='') echo CHtml::link($contr_agents[$i]->name, array('merchant/info', 'alias'=>$contr_agents[$i]->alias, 'search'=>$search)).'<br><br>';
		}////////////////////
		*/
}//////////
?>

    </div>

