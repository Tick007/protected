<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	//'name'=>'Пневмоинструмент, крепеж, гвозди, шпильки, скоба, компрессоры, фитинги. ООО МинРесурс. Поставка пневматического оборудования.',\
	'name'=>'GYDZ - У НАС ЕСТЬ ВСЕ',
	//'theme'=>'trade-x',
	'theme'=>'enterteh',
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
		
		
		'errorHandler'=>array(
                        'errorAction'=>'site/error',
                ),
		
			'cache'=>array(
				'class'=>'system.caching.CDbCache',
				'autoCreateCacheTable'=>true,
				'connectionID'=>'db',

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
			
			'catalog/<path:[\w+-\/]{0,}\w+>/<alias:\w+>/<pd:\d+>'=>'product/details',
			
			'catalog/<alias:\w+>/<pd:\d+>'=>'product/details',
			'catalog/<path:[\w+-\/]{0,}\w+>/<alias:\w+>'=>'product/list',
			'catalog/<alias:\w+>'=>'product/list',
			'/info/<id:\w+>'=>'page/byalias',
			
			'pricelist/mail.xml'=>'nomenklatura/yml',
			'pricelist.xml'=>'nomenklatura/yml',
			
			// Парсер
			'parser' => 'parser/index',

			'<controller:\w+>/<id:\d+>'=>'<controller>/show',//////////////lдля  отображения статей как бы на втором уровне
			'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				
			
			),
			'showScriptName'=>false,
		),
		
		'db'=>array(
		'class'=>'CDbConnection',
		
		'connectionString'=>'mysql:host=localhost;dbname=u5492617_default',
		'username'=>'u5492617_default',
		'password'=>'02041987',
		
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
		'allow_order_with_no_products'=>true,
		'reserv_sklad'=>4,
		'allow_order_with_no_products'=>true,
		'main_sklad'=>1,
		'self_contragent'=>1,
		'infoEmail'=>'info@gydz.ru',
		'supportEmail'=>'info@gydz.ru',
		//'infoEmail'=>'shop@gydz.ru',
		//'supportEmail'=>'shop@gydz.ru',
		'adminEmail'=>'tick007@yandex.ru',
		'coreElementDescr'=>'Бакуган',
		'pay_faces'=>array(1),
		'delivery_samovivoz'=>array(6691,6692),
		'delivery_dostavka'=>array(6693, 6694, 6695, 6696),
		'display_cart_ostatki'=>false,
		'main_tree_root'=>506,
		'group_logo_x_limit'=>1260,
		'group_logo_y_limit'=>800,
		'group_characteristics_mode'=>'multi',
		'url_correction'=>array('controller'=>'product', 'view'=>'list'),
		'cart'=>array('oformlenie_separate'=>'oformlenie'),
		'dbname'=>'u5492617_default',
		'dont_show_price_null'=>true,
		'dont_show_ostatki_null'=>true,
		'default_products_sort'=>' products.product_name ',
		'use_long_urls'=>true,////////////////Длинные пути в урлах
		//'bad_parametrs'=>array(725),
	),
	'modules'=>array(
						'forum'=>array(
						'postPerPage'=>20,
						),
					),
	
);
