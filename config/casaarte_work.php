<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	//'name'=>'Пневмоинструмент, крепеж, гвозди, шпильки, скоба, компрессоры, фитинги. ООО МинРесурс. Поставка пневматического оборудования.',\
	'name'=>'Casa-Arte. Стиль и уют вашего дома.',
	//'theme'=>'trade-x',
	'theme'=>'casaarte',
	// preloading 'log' component
	'preload'=>array('log', 'db'),
	'charset'=>'Windows-1251',
	'language'=>'ru',

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
		 'application.extensions.yiifilemanager.*', 
		
	),
	
	'modules'=>array(
			 'forum'=>array(
						'postPerPage'=>20,
						),
			 'pricelist',
),
	

	// application components
	'components'=>array(
		'fileman' => array(
			'class'=>'application.extensions.yiifilemanager.YiiDiskFileManager',
			'storage_path' => "/var/tmp/fileman",
		),
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
			
			
		//	www.gydz.ru/categoru/НАЗВАИЕ КАЕГОРИИ
		//	www.gydz.ru/product/id
						
			'urlFormat'=>'path',
			'urlSuffix'=>'.html',
			'rules'=>array(
			
			'pricelist'=>'pricelist',			
			'site/login'=>'site/logincombined',

			'catalog/<path:[\w+-\/]{0,}\w+>/<alias:\w+>/<pd:\d+>'=>'product/details',
			//'catalog/<alias:\w+>/<pd:\d+>'=>'product/details',
			'product/<pd:\d+>'=>'product/details',
			
			'catalog/<path:[\w+-\/]{0,}\w+>/<alias:\w+>/f<filtr_id:\d+>/v<filtr_value:\w+>'=>'product/list', //////////////для стат фильтров
			'catalog/<path:[\w+-\/]{0,}\w+>/<alias:\w+>'=>'product/list',
			//'catalog/<alias:\w+>'=>'product/list',
			'category/<alias:\w+>'=>'product/list',
			
			
			'product/vendor/<alias:\w+>'=>'product/vendor',
			
			'info/<id:\w+>'=>'page/byalias',
			'news/list/<section_id:\d+>/<rubric_id:\d+>'=>'page/list',
			'news/list/<section_id:\d+>'=>'page/list',
			'news/list'=>'page/list',
			
			//'news/list/<id:\d+>'=>'page/show',

			//'imagetools/podlojka/<create:(0|1)>/<pw:\d+>/<ph:\d+>/<create_type:\w+>/<folder:\w+>/<fname:\w+>'=>'imagetools/podlojka',
			//array('imagetools/podlojka', 'imagetools/podlojka/<create:(0|1)>/<pw:\d+>/<ph:\d+>/<create_type:\w+>/<folder:\w+>/<fname:\w+>', 'urlSuffix'=>''),
			'imagetools/podlojka/<create:\d+>/<pw:\d+>/<ph:\d+>/<create_type:\w+>/<folder:[\w+-\/]{0,}\w+>/<fname:[\w+\.]{0,}\w+>'=>array('imagetools/podlojka', 'urlSuffix'=>''),
			//'pricelist.xml'=>'nomenklatura/yml',
			
			
			//'adminorders/print/<id:\d+>'=>'adminorders/print',
			
			'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
			
			'<controller:\w+>/<id:\d+>'=>'<controller>/show',//////////////lдля  отображения статей как бы на втором уровне
			'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			
				
			
			),
			'showScriptName'=>false,
		),
		
		'db'=>array(
		'class'=>'CDbConnection',
		
		'connectionString'=>'mysql:host=localhost;dbname=casaarte',
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
		'allow_order_with_no_products'=>true,
		'reserv_sklad'=>4,
		'main_sklad'=>1,
		'self_contragent'=>1,
		'infoEmail'=>'tick007@yandex.ru',
		'adminEmail'=>'tick007@yandex.ru',
		'coreElementDescr'=>array('old'=>'Главная', 'new'=>'Gudz.ru'),
		'pay_faces'=>array(1),
		'delivery_samovivoz'=>array(6691,6692),
		'delivery_dostavka'=>array(6693, 6694, 6695, 6696),
		'delivery_groups'=>array(197, 198),
		'delivery_default'=>array('product'=>1907, 'price'=>500, 'eprice'=>1000, 'freelimitcash'=>5000, 'freelimitepay'=>5000 ), /////////Ид товара, дефолт доставка
		'orderoneclick'=>array('client_email'=>'oneclickoreder@casaarte.ru', 'first_name'=>'Новый заказ в 1 клик', 'client_city'=>'15790560', 'client_tels'=>'', 'delivery'=>'default:15790560:p', 'order_adress1'=>'Заказ в 1 клик'),
		'display_cart_ostatki'=>false,
		'main_tree_root'=>1,
		'group_logo_x_limit'=>860,
		'group_logo_y_limit'=>500,
		'group_characteristics_mode'=>'multi',
		'url_correction'=>array('controller'=>'product', 'view'=>'list'),
		'cart'=>array('oformlenie_separate'=>'oformlenie'),
		'market_params'=>array( 'typePrefix'=>691, 'vendor'=>684, 'model'=>734),
		'vendor_char_id'=>1,
		'dbname'=>'enterteh',
		'dont_show_price_null'=>true,
		'dont_show_ostatki_null'=>false,
		'default_products_sort'=>' products.product_name ',
		'display_error'=>true,//////
		'use_long_urls'=>true,////////////////Длинные пути в урлах
		'use_temp_tables'=>false,
		'group_stores'=>true,
		'apache_auth'=>'casa:123456@', ////1:2@
		//'bad_parametrs'=>array(727),
	),
	
);
