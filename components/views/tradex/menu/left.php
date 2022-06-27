<div class="leftpanelblock">
	<div class="blockheader">Trade-x</div>
    <div class="vmenu">
   <ul>
<?php


	$alias = Yii::app()->getRequest()->getParam('id');/////тут сидит alias в pagecontroleer
	//echo $controller.'<br>';
	//echo $action.'<br>';
	//echo  '$alias = ' .$alias; 
	
	foreach ($this->points as $link_name => $link) {
		
		//print_r($link);
		if ($link['url']!="/#") {
			echo '<li';
			if (isset($link['alias'])) {
				if (in_array($alias, $link['alias']) ) echo " class=\"active";
				else  echo " class=\"";
				//if (in_array('faq', $link['action']) ) echo '_faq';
				
				if (in_array($alias, $link['alias']) ) echo "\"";
				else  echo "\"";
			}
			elseif(isset($link['alias'])==false) {
				//print_r($link);
				
					
				if ( (isset($link['controller']) AND in_array($controller, $link['controller']) AND (isset($link['action']) AND  in_array($action, $link['action'])  )) OR ( isset($link['controller']) AND in_array($controller, $link['controller']) AND isset($link['action'])==false  ) ) {
					// if (in_array('faq', $link['action']) )echo " class=\"active_faq\"";
					 //else
					  echo " class=\"active\"";
					}
				// if (in_array('faq', $link['action']) ) echo ' class="_faq"';
				
			}
			
				echo ">";
				echo CHtml::link($link_name, $link['url']);

			echo '</li>';
			
		}///////
		
	}
		?>
</ul>
    </div>
</div>