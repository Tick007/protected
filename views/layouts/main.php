<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
<meta name="language" content="en" />
<link rel="stylesheet" type="text/css" href="http://<?=$_SERVER['HTTP_HOST'].'/'.Yii::app()->GP->GP_theme_path.'main.css'?>">
<link rel="stylesheet" type="text/css" href="http://<?=$_SERVER['HTTP_HOST'].'/'.Yii::app()->GP->GP_theme_path.'form.css'?>">
<title><?php echo $this->pageTitle; ?></title>
</head>

<body class="thrColAbs">
<div id="page">
<div id="header">
<div id="mainmenu">
<?php $this->widget('application.components.MainMenu',array(
	'items'=>array(
		array('label'=>'Home', 'url'=>array('/site/index')),
		array('label'=>'О компании', 'url'=>array('page/show', 'id'=>4)),
		array('label'=>'Войти', 'url'=>array('/site/login'), 'visible'=>Yii::app()->user->isGuest),
		array('label'=>'Регистрация', 'url'=>array('/site/register'), 'visible'=>Yii::app()->user->isGuest),
		array('label'=>'Личный кабинет', 'url'=>array('/privateroom/'), 'visible'=>!Yii::app()->user->isGuest),
		array('label'=>'Выйти', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest),
		/*array('label'=>'Список пользователей', 'url'=>array('/user'), 'visible'=>!Yii::app()->user->isGuest),*/
		array('label'=>'Товары', 'url'=>array('/product')),
		array('label'=>'Корзина', 'url'=>array('/cart')),
		array('label'=>'Статьи', 'url'=>array('/page')),
		array('label'=>'Contact', 'url'=>array('/site/contact')),
	),
)); ?>
</div><!-- mainmenu -->
</div><!-- header -->
<div><?php echo $content; ?></div>

<div id="footer">
Copyright &copy; 2010 by Trade-x.<br/>
All Rights Reserved.<br/>
<?php echo Yii::powered(); ?>
</div><!-- footer -->
</div><!-- page -->


</body>
</html>