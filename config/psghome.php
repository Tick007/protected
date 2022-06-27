<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Тюнинг автомобилей ваз, ксенон, спортивный ряд кпп, тюнинговые фары, обвесы, спортивные  глушители, сиденья, спортивные фильтры, растяжки ваз - '.$_SERVER['HTTP_HOST'],
	'theme'=>'protuning',
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
				'cache'=>array(
/*/////////////////////////////////////////////////////////////////////Работает круто, но на виртуальном хостинге не встречается CDbCache
 'class'=>'system.caching.CMemCache',
'servers'=>array(
array('host'=>'localhost', 'port'=>11211, 'weight'=>100),
),
),
*/
				'class'=>'system.caching.CDbCache',
				'autoCreateCacheTable'=>true,
				'connectionID'=>'db',
),
		
		'urlManager'=>array(
		
			'urlFormat'=>'path',
			//'urlSuffix'=>'.html',
			'rules'=>array(
			//	'<controller:\w+>/<id:\d+>'=>'<controller>/view',
			'mail'=>'mail',
			'news'=>'page/list',
			'news/<id:\d+>'=>'page/show',
			'product/sale'=>'product/sale',
			
			'product'=>'product/index',
			'product/<alias:\w+>'=>'product/list',
			'product/<alias:\w+>/<pd:\d+>'=>'product/details',
			
			'product/<id:\d+>'=>'product/list',
			'product/details/<pd:\d+>'=>'product/details',
			
			'/page/<id:\w+>'=>'page/byalias',
			
			'mailings/<action:\w+>/<id:\d+>'=>'mailings/<action>/<id:\d+>',
			
			'gallery/pictures/<img:\w+>'=>'imagetools/watermark/<img:\w+>', 
			
			'<controller:\w+>/<id:\d+>'=>'<controller>/show',//////////////lдля  отображения статей как бы на втором уровне
			'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				
			
			),
			'showScriptName'=>false,
		),
		
		'db'=>array(
		'class'=>'CDbConnection',
		'connectionString'=>'mysql:host=localhost;dbname=psg',
		//'username'=>'psg-root',
		//'password'=>'H7dSLyB64.G',
		'username'=>'root',
		'password'=>'1234',
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
		'adminEmail'=>'webmaster@example.com',
		//'infoEmail'=>'shop@protuning-psg.ru',
		'supportEmail'=>'shop@protuning-psg.ru',
		'infoEmail'=>'protuning-psg@yandex.ru',
		'coreElementDescr'=>'Про-тюнинг',
		'group_logo_x_limit'=>400,
		'group_logo_y_limit'=>400,
		'reserv_sklad'=>0,
		'delivery_mail'=>array(3954),
		'payment_method_initval'=>'Выберете способ оплаты',
		'delivery_method_initval'=>'Выберете способ доставки',
	),
	'modules'=>array(
						'forum'=>array(
							'postPerPage'=>20,
						),
						'mail'=>array(
							'message_theme'=>'Доска объявлений о кошках petmarket.su',
						 ),
					),
	
);
