<?php

//error_reporting(E_ERROR);

$yiic=dirname(__FILE__).'/../../../yii-1.1.21.733ac5/framework/yiic.php'; 
$config=dirname(__FILE__).'/config/console.php';
require_once($yiic);

$app = Yii::createConsoleApplication($config);
$app->run(); 
