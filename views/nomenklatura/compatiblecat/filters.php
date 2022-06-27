<?php

if(isset($characteristics_categories)) {
	
	if(isset($filters) AND empty($filters)==false ) {
		$filters = unserialize($filters);
		//print_r($filters);
	}
	 
	
	?>
	<ul>
    <?php
    for($i=0; $i<count($characteristics_categories); $i++) {
		?><li>
        <?php
        echo $characteristics_categories[$i]->characteristic->caract_name.'('.$characteristics_categories[$i]->characteristics_id.')';
		if(isset($values_list[$characteristics_categories[$i]->categories_id][$characteristics_categories[$i]->characteristics_id])) {
			//print_r($values_list[$characteristics_categories[$i]->categories_id][$characteristics_categories[$i]->characteristics_id]);
			//$listdata=array();
			/*for($k=0; $k<count($values_list[$characteristics_categories[$i]->categories_id][$characteristics_categories[$i]->characteristics_id]); $k++) {
				$listdata[$values_list[$characteristics_categories[$i]->categories_id][$characteristics_categories[$i]->characteristics_id][$k]]=$values_list[$characteristics_categories[$i]->categories_id][$characteristics_categories[$i]->characteristics_id][$k];
			}*/
			//print_r($listdata);
			echo CHtml::dropdownlist('compat_category['.$comp_cat_cat_id.'][filters]['.$characteristics_categories[$i]->characteristics_id.']', isset($filters[$characteristics_categories[$i]->characteristics_id])?$filters[$characteristics_categories[$i]->characteristics_id]:NULL, $values_list[$characteristics_categories[$i]->categories_id][$characteristics_categories[$i]->characteristics_id]);
		}
		?>
		</li><?php
	}/////////for($i=0; $i<count($characteristics_categories); $i++) {
	?>
    </ul>
	<?php
}
?>