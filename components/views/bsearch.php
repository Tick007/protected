<div class="sitesearch"><?php
echo CHtml::beginForm(array('/search/'),  $method='get', $htmlOptions=array('name'=>'searchform', 'id'=>'searchform'));  
?>
 <?php
echo CHtml::textfield('search', htmlspecialchars(Yii::app()->getRequest()->getParam('search', NULL)),  $htmlOptions=array("placeholder"=>"Поиск...", "class"=>"searchtext" )  ) ?>
<?php
 echo CHtml::endForm(); ?>
 </div>