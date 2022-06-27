<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	//'name'=>'Пневмоинструмент, крепеж, гвозди, шпильки, скоба, компрессоры, фитинги. ООО МинРесурс. Поставка пневматического оборудования.',\
	'name'=>'Бластеры Нерф (Nerf). Бакуган (Bakugan)',
	//'theme'=>'trade-x',
	'theme'=>'bakugan',
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
			
			'pricelist.xml'=>'nomenklatura/yml',
			'parser' => 'parser/index',
			'product'=>'product/index',
				'news'=>'page/list',
			'news/<id:\d+>'=>'page/show',
			'product/<alias:\w+>'=>'product/list',
			
			//'product/<path:[\w+-\/]{0,}\w+>/<alias:\w+>/<pd:\d+>'=>'product/details',
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
		
		'connectionString'=>'mysql:host=localhost;dbname=u1424315_bakugan',
		'username'=>'u1424315_default',
		'password'=>'4hO8AeAe',
		
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
		'main_tree_root'=>0,
		'infoEmail'=>'sales@bakugan-store.ru',
		'adminEmail'=>'tick007@yandex.ru',
		'coreElementDescr'=>'Бакуган',
		'delivery_samovivoz'=>array(391),
		'display_cart_ostatki'=>false,
		'product_page_size'=>50,
		'group_characteristics_mode'=>'multi',
		
		'pay_faces'=>array(1),
		'delivery_mail'=>array(385),
		'cart'=>array(
			'private_face'=>array('client_email', 'client_email_copy', 'client_tels', 'second_name','first_name', 'client_city', 'client_street', 'client_house', 'client_apart'),
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
					'order_adress1'=>'Адрес дополнительно 1',
					'order_adress2'=>'Адрес дополнительно 2',
					'primechanie'=>'Комментарии',
				),
			'rules'=>array(
				'nomail'=>array('first_name, client_email, client_email_copy, client_tels, delivery_method, payment_method, client_city, client_street, client_house, client_apart', 'required','on'=>'nomail'),
				'mail'=>array('first_name, second_name, client_email, client_email_copy, client_tels, delivery_method, payment_method, last_name,client_post_index, client_oblast, client_city, client_street, client_house, client_apart', 'required', 'on'=>'mail'), 
			),
		),
		
		
	),
	'modules'=>array(
						'forum'=>array(
						'postPerPage'=>20,
						),
					),
	
);
