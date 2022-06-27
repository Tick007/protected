<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Пневмоинструмент, компрессор воздушный, крепеж, гвозди, шпильки, скоба, фитинги CAMOZZI. ООО МинРесурс. Поставка пневматического оборудования.',
	//'theme'=>'trade-x',
	'theme'=>'wood',
	// preloading 'log' component
	'preload'=>array('log', 'db', 'GP'),
	'charset'=>'Windows-1251',
	//'charset'=>'utf-8',
	//'language'=>'ru',

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
		'cache'=>array(
				'class'=>'system.caching.CDbCache',
				'autoCreateCacheTable'=>true,
				'connectionID'=>'db',
			),
		
		'urlManager'=>array(
		
			'urlFormat'=>'path',
			//'urlSuffix'=>'.html',
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
			
			'product/vendor/<alias:\w+>'=>'product/vendor',

			'/page/ask'=>'page/ask',
			'/page/<id:\w+>'=>'page/byalias',
			'<controller:\w+>/<id:\d+>'=>'<controller>/show',//////////////lдля  отображения статей как бы на втором уровне
			'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
			
				
			
			),
			'showScriptName'=>false,
		),
		
		'db'=>array(
		'class'=>'CDbConnection',
		/*
		'connectionString'=>'mysql:host=localhost;dbname=yii',
		'username'=>'root',
		'password'=>'1234',
		*/
		'connectionString'=>'mysql:host=localhost;dbname=u1424315_atool',
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
		'adminEmail'=>'info@pnevmoinstrument.ru',
		'coreElementDescr'=>'Пневмоинструмент',
		'make_reserv_at_order'=>true, ////////////Делаем резерв на склад при заказе
		'reserv_sklad'=>4,
		'vendor_char_id'=>175,
		'main_sklad'=>1,
	),
	'modules'=>array(
						'forum'=>array(
						'postPerPage'=>20,
						),
					),
	
);
