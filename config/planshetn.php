<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	//'name'=>'разработка-сайтов.рф',
	//'name'=>'создание-сайтов.рф',
	'theme'=>'classic',
	'name'=>'планшетный-компьютер.рф',
	'preload'=>array('log', 'db', 'GP'),
	'charset'=>'Windows-1251',
	'language'=>'ru',

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
	),

	'modules'=>array(
		// uncomment the following to enable the Gii tool
		/*
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'Enter Your Password Here',
		),
		*/
	),

	// application components
	'components'=>array(
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
		),
		// uncomment the following to enable URLs in path-format
		
		'urlManager'=>array(
			'urlFormat'=>'path',
			'rules'=>array(
//	'<id:\d+>'=>'page/show',

			'product/<path:[\w+-\/]{0,}\w+>/<alias:\w+>/<pd:\d+>'=>array( 'product/details', 'urlSuffix'=>'.html'),
			'product/<cat:\d+>/info/<pd:\d+>'=>array( 'product/details', 'urlSuffix'=>'.html'),
			'product/<id:\d+>'=>array( 'product/list', 'urlSuffix'=>'.html'),

			'news'=>'page',
			'news/<id:\d+>'=>'page/show',
			'news/<id:\w+>'=>'page/byalias',
				'<controller:\w+>/<id:\d+>'=>'<controller>/show',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			),
			'showScriptName'=>false,
		),
		
		'db'=>array(
		'class'=>'CDbConnection',
		'connectionString'=>'mysql:host=127.0.0.1;dbname=u1424315_planshetniy',
		'username'=>'u1424315_default',
		'password'=>'4hO8AeAe',
		
		//'username'=>'root',
		//'password'=>'a0806975a',
		'emulatePrepare'=>true, // needed by some MySQL installations
		'charset' => 'utf8',
		),
		// uncomment the following to use a MySQL database
		/*
		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=testdrive',
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => '',
			'charset' => 'utf8',
		),
		*/
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
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
				// uncomment the following to show log messages on web pages
				/*
				array(
					'class'=>'CWebLogRoute',
				),
				*/
			),
		),
		
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
		'main_sklad'=>1,
		'self_contragent'=>1,
		'infoEmail'=>'sales@bakugan-store.ru',
		'adminEmail'=>'tick007@yandex.ru',
		'coreElementDescr'=>array('old'=>'Главная', 'new'=>'Gudz.ru'),
		'pay_faces'=>array(1),
		'delivery_samovivoz'=>array(6691,6692),
		'delivery_dostavka'=>array(6693, 6694, 6695, 6696),
		'display_cart_ostatki'=>false,
		'main_tree_root'=>519,
		'group_logo_x_limit'=>860,
		'group_logo_y_limit'=>500,
		'group_characteristics_mode'=>'multi',
		'url_correction'=>array('controller'=>'product', 'view'=>'list'),
		'cart'=>array('oformlenie_separate'=>'oformlenie'),
		'market_params'=>array( 'typePrefix'=>691, 'vendor'=>684, 'model'=>734),
		'dbname'=>'enterteh',
		'dont_show_price_null'=>false,
		'dont_show_ostatki_null'=>false,
		'default_products_sort'=>' products.product_name ',
		'display_error'=>true,//////
		'use_long_urls'=>true,////////////////Длинные пути в урлах
		'use_temp_tables'=>true,
		'group_stores'=>true,
		'product_page_size'=>50,
		'pictures_sourse'=>'213.141.153.76/enterteh', 
	),
);