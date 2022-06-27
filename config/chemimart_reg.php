<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Chemimart',
	'charset'=>'utf-8',
	'theme'=>'chemimart',
	// preloading 'log' component
	'preload'=>array('log', 'db'),
    'sourceLanguage'=>'en',
    'language'=>'en',

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
	),
   'defaultController' => 'chemimart',

	'modules'=>array(
		// uncomment the following to enable the Gii tool
		
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'1234',
		    'ipFilters'=>array('10.10.0.16', '10.10.0.116', '10.10.0.14', '10.10.0.114'),
		),
		
	),

	// application components
	'components'=>array(
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
	    
		// uncomment the following to enable URLs in path-format
		
	    'request'=>array(
	        'class'=>'DLanguageHttpRequest',
	    ),
	    
	    'urlManager'=>array(
	        'class'=>'DLanguageUrlManager',
	        'urlFormat'=>'path',
	        'urlSuffix'=>'.html',
	        'showScriptName'=>false,
	        'rules'=>array(
	            //'defaultRoute' => 'app\modules\test\controllers\DefaultController',
	            'admin'=>'adminproducts/administration',
	            'site/login'=>'chemimart/login',
	            'site/register'=>'chemimart/register',
	            'site/logout'=>'chemimart/logout',
	           // '/' => 'chemimart/index',
	            '/page/<id:\w+>'=>'page/byalias',
	           
	            'catalog/<alias:\w+>/card<id:\d+>'=>'catalog/info',
	            'catalog/group/<alias:\w+>/page<page:\d+>'=>'catalog/group',
	            'catalog/group/<alias:\w+>'=>'catalog/group',
	            
	            
	            '<controller:\w+>/<id:\d+>'=>'<controller>/view',
	            '<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
	            'site/page/<view:\w+>'=>'chemimart/page',
	            '<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
	            //'gii'=>'gii',
	            //'gii/<controller:\w+>'=>'gii/<controller>',
	            //'gii/<controller:\w+>/<action:\w+>'=>'gii/<controller>/<action>',
	        ),
	    
			'showScriptName'=>false,
		),

	//	'db'=>array(
	//		'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
	//	),
		// uncomment the following to use a MySQL database
		
		'db'=>array(
			/*
			'connectionString' => 'mysql:host=10.10.0.16;dbname=smotr_bd',
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => '1234',
			'charset' => 'utf8',
		    */
		    'connectionString'=>'mysql:host=127.0.0.1;dbname=u1424315_chemimart',
		    'username'=>'u1424315_default',
		    'password'=>'4hO8AeAe',
		    'charset' => 'utf8',
		    
		),
		
	    /*
		'db2'=>array(
			'class'=>'CDbConnection',
			'connectionString' => 'odbc:DRIVER={Microsoft Access Driver (*.mdb)};Dbq=d:\\AZSSKU401\ZSSKU\ZSSKU\LBD_ZS.mdb;',
			'username'=>'Admin',
			'password'=>'qwe',
			'charset' => 'cp1251',
		),
	    */
		
		'errorHandler'=>array(
			// use 'site/error' action to display errors
		    'errorAction'=>'chemimart/error',
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
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
	    'mobile_theme'=>'chemimartmobile',
		'adminEmail'=>array('tick007@yandex.ru','info@chemimart.de'),
	    'infoEmail'=>'info@chemimart.de',
	    'checkout'=>array('online_checkout'=>2),///////////Идентификатор метода онлайн оплаты
	    'inetpayment'=>array(
	        'orderStatusToPay'=>3, ///При каком статусе заказа показывать ссылку на оплату в ЛК
	        ////////////////////////// если закоментировать то ссылка на оплату не будет показываться
	        'orderStatusPaid'=>4, //////////Какой статус ставить при поступлении платежа
	        
	        'getawayurl'=>'http://paysystem.trade-x.ru/payment/pay',
	        'login'=>'chemimart',
	        'shop_id'=>2,
	        'payment_pass'=>'3445',/////////пароль для формирования подписи SignatureValue 
	        ///////////////////////при передачи данных в робокассу
	        'result_pass'=>'5345',//////////пароль для формирования подписи SignatureValue 
	        'shp_item'=>1,///////хз что это но есть в робокассе
	        'procent'=>1, /////////процент удерживаемый ПС
	        //////////////////////при получении ответа от платежной системы
	    ), 
	    'main_tree_root'=>1,
	    'translatedLanguages'=>array(
	        'en'=>'English',
	        'de'=>'German',
	        'ru'=>'Русский',
	    ),
	    'defaultLanguage'=>'en',
	    ///////////Корневая директория
	    'main_page'=>(object) array('root_dir'=>2, 'limit'=>7),
	    ///////////Место сохранения и извлечения папок (пока не очень применяется)
	    'folders'=>(object) array('picture_path'=>'/pictures/add/'),
	    /////////////Количество отображаемых на странице товаров (используется при activeDataProvider для подгрузки по событию)
	    'catalog'=>(object)array('desktopItemsNum'=>36),
	    'notListHtmlFields'=>array('status', 'second_name', 'verifyCode'),
	    'display_error'=>true,
	    'cart'=>array(
	        'order_nuber_template'=>'CM-O-',
	        'checkout_policy'=>'nomail',
	        'private_face'=>array('client_email', 'client_email_copy', 'client_tels', 'second_name','first_name', 'client_city', 'client_street', 'client_house', 'client_apart'),
	        'samovivoz'=>array('client_email', 'client_email_copy', 'client_tels', 'second_name','first_name'),
	        'private_face_mail'=>array('client_email', 'client_email_copy', 'client_tels', 'second_name', 'first_name', 'last_name', 'client_post_index', 'client_oblast',  'client_city', 'client_street', 'client_house', 'client_apart'),
	        'labels'=>array( ////////////order matter
	            'client_oblast'=>'Область, республика,край',
	            'first_name'=>'Your name',
	            'second_name'=>'Second name',
	            'client_tels'=>'Tel.',
	            'client_email'=>'Email',
	            'client_email_copy'=>'Repeat email',
	            'last_name'=>'Отчество',
	            'delivery_method'=>'Способ получения',
	            'payment_method'=>'Payment',
	            'client_street'=>'Street',
	            'client_city'=>'City',
	            'client_post_index'=>'Zip code',
	            'country'=>'Country',
	            'client_apart'=>'№ квартиры',
	            'order_adress1'=>'Адрес дополнительно',
	            'order_adress2'=>'Марка авто / модель авто',
	            'primechanie'=>'Комментарии',
	            'company_name'=>'Organisation/Company',
	            'company_contact'=>'Contact person',
	            'vat'=>'VAT registration number',
	            'agreement'=>'I agree with policy',
	        ),
	        'field_types'=>array('checkboxes'=>array('agreement')),
	        'rules'=>array(/////Пробелы убрать
	            'nomail'=>array('first_name,second_name,client_tels,client_email,company_name,agreement', 'required','on'=>'nomail'),
	            'mail'=>array('first_name,second_name,client_email,client_email_copy,client_tels, company_name, client_street, client_city, client_post_index, country, company_contact, agreement', 'required', 'on'=>'mail'),
	            'mailsafe'=>array('vat', 'safe'),
	            'nomailsafe'=>array('vat', 'safe'),
	        ),
	        'VAT'=>1.19,
	        
	        ),
	    
	),
);