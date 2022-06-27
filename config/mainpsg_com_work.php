<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'ПРОТЮНИНГ new',
	'theme'=>'tuningnew',
	// preloading 'log' component
	'preload'=>array('log', 'db', 'GP'),
	//'charset'=>'utf-8',
	'charset'=>'Windows-1251',
	'language'=>'ru',

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
		'application.components.PictureType',	
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
		'errorHandler'=>array(
				'errorAction'=>'catalog/error',
		),
			
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
			'urlSuffix'=>'.html',
			'rules'=>array(
			//	'<controller:\w+>/<id:\d+>'=>'<controller>/view',
			'mail'=>'mail',
			'page/list'=>'page/list',
			'news/<id:\d+>'=>'page/show',
			
			
			
					
			'pricelist.xml'=>'nomenklatura/yml',
			
			'catalog/group/<alias:\w+>/card<id:\d+>'=>'catalog/info',
			'catalog/group/<alias:\w+>/page<page:\d+>'=>'catalog/group',
			'catalog/group/<alias:\w+>'=>'catalog/group',
			'product/vendor/<alias:\w+>'=>'product/vendor',
			
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
		'connectionString'=>'mysql:host=localhost;dbname=protun',
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
		'adminEmail'=>'tick007@yandex.ru',
		'supportEmail'=>'shop@protuning-psg.ru',
		'display_error'=>true,
		'self_contragent'=>1,
		'infoEmail'=>'shop@protuning-psg.ru',
		//'infoEmail'=>'protuning-psg@yandex.ru',
		'coreElementDescr'=>'Про-тюнинг',
		'mobile_theme'=>'tuning-mobile-ru',
		'group_logo_x_limit'=>400,
		'group_logo_y_limit'=>400,
		'delivery_samovivoz'=>array(3959, 3960),
		'vendor_char_id'=>175,
		'main_tree_root'=>0,
		'delivery_mail'=>array(3954),
		'delivery_by_kladr'=>array(
				'mo'=>array(3960,3959,5), 
				'msk'=>array(3960,3959,4), 
				'rus'=>array(3954,3590)
		),
		'reserv_sklad'=>4,
		'payment_method_initval'=>'Выберете способ оплаты',
		'delivery_method_initval'=>'Выберете способ доставки',
		'cart'=>array(
			'private_face'=>array('client_email', 'client_email_copy', 'client_tels', 'second_name','first_name', 'client_city', 'client_street', 'client_house', 'client_apart'),
			'samovivoz'=>array('client_email', 'client_email_copy', 'client_tels', 'second_name','first_name'),
			'private_face_mail'=>array('client_email', 'client_email_copy', 'client_tels', 'second_name', 'first_name', 'last_name', 'client_post_index', 'client_oblast',  'client_city', 'client_street', 'client_house', 'client_apart'),
			'labels'=>array(
					'client_tels'=>'Телефоны',
					'client_email_copy'=>'Ещё раз email',
					'client_email'=>'Email',
					'client_oblast'=>'Область, республика,край',
					'client_city'=>'Город', 
					'first_name'=>'Имя',
					'second_name'=>'Фамилия',
					'last_name'=>'Отчество',
					'delivery_method'=>'Способ получения',
					'payment_method'=>'Способ оплаты',
					'client_post_index'=>'Почтовый индекс',
					'client_street'=>'Улица',
					'client_house'=>'№ дома',
					'client_apart'=>'№ квартиры',
					'order_adress1'=>'Адрес дополнительно',
					'order_adress2'=>'Марка авто / модель авто',
					'primechanie'=>'Комментарии',
				),
			'rules'=>array(/////Пробелы убрать
				'nomail'=>array('first_name,client_email,client_email_copy,client_tels,delivery,payment,client_city', 'required','on'=>'nomail'),
				'mail'=>array('order_adress1, first_name,second_name,client_email,client_email_copy,client_tels,delivery,payment,last_name,client_post_index,client_city', 'required', 'on'=>'mail'), 
			),
		),
	),
	'modules'=>array(
						'forum'=>array(
							'postPerPage'=>20,
						),
						'mail'=>array(
							'message_theme'=>'Интернет магазин тюнинга автомобилей PROTUNING-PSG',
						 ),
					),
	
);
