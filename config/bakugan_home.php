<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	//'name'=>'Пневмоинструмент, крепеж, гвозди, шпильки, скоба, компрессоры, фитинги. ООО МинРесурс. Поставка пневматического оборудования.',\
	'name'=>'Бакуган. Бластеры Нерф. Bakugan. Nerf Blasters',
	//'theme'=>'trade-x',
	'theme'=>'bakugan',
	//'theme'=>'protuning',
	// preloading 'log' component
	'preload'=>array('log', 'db', 'GP'),
	'charset'=>'Windows-1251',
	'language'=>'ru',

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
	),

	// application components
	'components'=>array(
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
			),
		),
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
		),
		
		'authManager'=>array(
			//'class'=>'srbac.components.SDbAuthManager',
			'class'=>'CDbAuthManager',
			'connectionID'=>'db',
			//'defaultRoles'=>array('authenticated', 'guest'),
		),
		// uncomment the following to set up database
				/*
		'db'=>array(
			'connectionString'=>'sqlite:protected/data/source.db', ////////SQLLITE
		),
		*/
		
		'urlManager'=>array(
		
			'urlFormat'=>'path',
			//'urlSuffix'=>'.html',
			'rules'=>array(
			//	'<controller:\w+>/<id:\d+>'=>'<controller>/view',
			//'catalog/list'=>'product/list',
			

			'parser' => 'parser/index',

			'news'=>'page/list',
			'news/<id:\d+>'=>'page/show',
			'product/<alias:\w+>'=>'product/list',
			'product/<alias:\w+>/<pd:\d+>'=>'product/details',
			'product/<id:\d+>'=>'product/list',
			'product/details/<pd:\d+>'=>'product/details',
			
			'/product/<id:\d+>/<vendor:\w+>'=>'/product/list',
			'product/<id:\d+>'=>'product/list',
			
			'product/details/<pd:\d+>'=>'product/details',
			
			//'/page/<id:\w+>'=>'page/byalias',
			
			
			
			'/info/<id:\w+>'=>'page/byalias',
			'<controller:\w+>/<id:\d+>'=>'<controller>/show',//////////////lдля  отображения статей как бы на втором уровне
			'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				
			
			),
			'showScriptName'=>false,
		),
		
		'db'=>array(
		'class'=>'CDbConnection',
		
		'connectionString'=>'mysql:host=localhost;dbname=bakugan',
		'username'=>'root',
		'password'=>'1234', 
		
		/*'connectionString'=>'mysql:host=localhost;dbname=u1424315_atool',
		'username'=>'root',
		'password'=>'1234',
		/*
		
		'connectionString'=>'mysql:host=localhost;dbname=yii',
		'username'=>'root',
		'password'=>'a0806975a',
		*/
		'emulatePrepare'=>true, // needed by some MySQL installations
		'charset' => 'utf8',
		),
		
		//'GP' => array(
		//'class'=>'GP',
		//			),
		'GP'  => array(
         'class' => 'application.components.GP',
		 //'class' => 'application.components.Shopmenu',
      ),
	  
	  
		
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'make_reserv_at_order'=>false, ////////////Делаем резерв на склад при заказе
		'reserv_sklad'=>4,
		'main_sklad'=>1,
		'self_contragent'=>1,
		'adminEmail'=>'tick007@yandex.ru',
		'supportEmail'=>'tick007@yandex.ru',
		'coreElementDescr'=>'Бакуган',
		'robokassa'=>array(
			'mrh_login'=>"demo",
			'mrh_pass1' => "password_1",
			'mrh_pass2' => 'password_2',
			'method_ids'=>array(14),
		),
		'display_cart_ostatki'=>true,
		'delivery_samovivoz'=>array(382, 383),
		'product_page_size'=>50,
		'group_characteristics_mode'=>'multi',
	),
	'modules'=>array(
						'forum'=>array(
						'postPerPage'=>20,
						),
					),
	
);
