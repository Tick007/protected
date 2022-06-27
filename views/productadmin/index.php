 <?php // $this->pageTitle=$model->title ?>

<div id="ribbon">&nbsp;
</div>
<div id="Right_column">
<?
//$RC = new RightColumn;
?>
</div>

<div id="mainContent" style="padding-left:3px;">
<?
$allowable_productions=array(9,10,20,36,11,37,38,19);
//print_r($models);
for  ($i=0; $i<count($models);$i++) {
echo '<strong>'.$models[$i]->name."</strong><br>";
	
		for ($k=0; $k<count($models[$i]->child_categories); $k++) {
		
		$goods_products=NULL;
		for ($u=0; $u<count($models[$i]->child_categories[$k]->goods[$u]); $u++) 
		{
		$goods_products=($goods_products || in_array($models[$i]->child_categories[$k]->goods[$u]->production,$allowable_productions) );
		} 
		if($goods_products){
		
		
			echo "---".$models[$i]->child_categories[$k]->name."<br>";
			for ($t=0; $t<count($models[$i]->child_categories[$k]->goods); $t++) {
					if (in_array($models[$i]->child_categories[$k]->goods[$t]->production, $allowable_productions)) {////
						echo "---------".	$models[$i]->child_categories[$k]->goods[$t]->name.' - '.$models[$i]->child_categories[$k]->goods[$t]->production;
								if(strstr(strtolower($models[$i]->child_categories[$k]->goods[$t]->name), 'серы')) $color='серый';
								else if(strstr(strtolower($models[$i]->child_categories[$k]->goods[$t]->name), 'черн') OR strstr(strtolower($models[$i]->child_categories[$k]->goods[$t]->name), 'чёрн')) $color='чёрный';
								else if(strstr(strtolower($models[$i]->child_categories[$k]->goods[$t]->name), 'бежев')) $color='бежевый';
								else $color='непонятный';
								echo ', color: '.$color;
								if(strstr($models[$i]->child_categories[$k]->goods[$t]->name, 'полиуретан')) $material='полиуретан';
								else if(strstr($models[$i]->child_categories[$k]->goods[$t]->name, 'резинов')) $material ='резина';
								else if(strstr($models[$i]->child_categories[$k]->goods[$t]->name, 'текстиль')) $material ='текстиль на резине';
								echo ', material: '.$material;
								if (in_array($models[$i]->child_categories[$k]->goods[$t]->production, array(9,10,20,36)) ) $type = "в салон";	
								elseif(in_array($models[$i]->child_categories[$k]->goods[$t]->production, array(11,37,38,19)) ) $type = "в багажник";	
								else $type='непонятный';
								echo ', type: '.$type.'<br>';
					}	///////////if (in_array($models[$i]->child_categories[$k]->goods[$t]->production, $allowable_productions)) {/
			}
		}//////if($goods_products){
		}/////////for ($k=0; $k<count($models[$i]->
}///////////for  ($i=0; $i<count($models);$i++) {
?>
</div>
