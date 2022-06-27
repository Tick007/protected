<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	//'name'=>'Пневмоинструмент, крепеж, гвозди, шпильки, скоба, компрессоры, фитинги. ООО МинРесурс. Поставка пневматического оборудования.',\
	'name'=>'CMS для интернет магазинов Trade-x',
	//'theme'=>'trade-x',
	'theme'=>'trade-x',
	// preloading 'log' component
	'preload'=>array('log', 'db'),
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
			'urlSuffix'=>'.html',
			'rules'=>array(
			//	'<controller:\w+>/<id:\d+>'=>'<controller>/view',
			//'catalog/list'=>'product/list',
			
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
		
		'connectionString'=>'mysql:host=localhost;dbname=trade-x',
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
		/*
		'GP'  => array(
         'class' => 'application.components.GP',
		 //'class' => 'application.components.Shopmenu',
      ),
	  */
	  
		
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'make_reserv_at_order'=>true, ////////////Делаем резерв на склад при заказе
		'reserv_sklad'=>4,
		'main_sklad'=>1,
		'self_contragent'=>1,
		'infoEmail'=>'sales@bakugan-store.ru',
		'adminEmail'=>'tick007@yandex.ru',
		'coreElementDescr'=>'Бакуган',
		'pay_faces'=>array(1),
		'delivery_samovivoz'=>array(391),
		'display_cart_ostatki'=>false,
	),
	'modules'=>array(
						'forum'=>array(
						'postPerPage'=>20,
						),
					),
	
);
