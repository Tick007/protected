<div  class="mainmenu" align="right">
<!--
<ul>
<li class="active">Главная</li>
<li><?php echo CHtml::link('О компании', array('page/byalias', 'id'=>'about'));?></li>
<li><?php
echo CHtml::link('Продукция', array('constructcatalog'));
?></li>
<li class="active">Поддержка</li>
<li>Контакты</li>
</ul>-->
<ul>
<?php


	$alias = Yii::app()->getRequest()->getParam('id');/////тут сидит alias в pagecontroleer
	//echo $controller.'<br>';
	//echo $action.'<br>';
	foreach ($this->points as $link_name => $link) {
		
		//print_r($link);
		if ($link['url']!="/#") {
			echo '<li';
			if (isset($link['alias'])) {
				if (in_array($alias, $link['alias']) ) echo " class=\"active\"";
			}
			elseif(isset($link['alias'])==false) {

				if ( (isset($link['controller']) AND in_array($controller, $link['controller']) AND (isset($link['action']) AND  in_array($action, $link['action'])  )) OR ( isset($link['controller']) AND in_array($controller, $link['controller']) AND isset($link['action'])==false  ) ) echo " class=\"active\"";
			}
				echo ">";
				echo CHtml::link($link_name, $link['url']);

			echo '</li>';
			
		}///////
		
	}
		?>
</ul>
</div>
<div style="clear:both"></div>