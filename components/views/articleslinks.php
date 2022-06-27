<div class="leftpanelblock">
<div class="blockheader"><?php
echo $this->headtext;
?></div>

<?php

if (isset($models)) {
		
		echo "<div class=\"vmenu\"><ul>";
		for($k=0; $k<count($models); $k++) {
				if (isset($models[$k]->alais)) {
				echo "<li>";
				echo CHtml::link($models[$k]->title, array('page/byalias' , 'alais'=>$models[$k]->alais) );
				echo "</li>";
				}//////////if (isset($models[$k]->alias))
		}	/////////for($k=0; $k<count($models); $k++) {
		echo "</ul></div>";	
}///////////if (isset($models())) {
?>

</div>