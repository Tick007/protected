<?php

$criteria=new CDbCriteria;
$params = Characteristics::model()->findAll($criteria);
if(isset($params)) $paramslist = CHtml::listdata($params, 'caract_id', 'caract_name');

//print_r($paramslist);

$params = Products::model()->get_product_params(6392);
print_r($params);










?>