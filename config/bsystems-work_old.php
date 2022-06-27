<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Противоугонные системы - '.$_SERVER['HTTP_HOST'],
	//'theme'=>'protuning', 
	//'theme'=>'bogajniki',
	//'theme'=>'bsystems',
	'theme'=>'bsystems2',
	//'theme'=>'wood', 
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
			'swiftMailer' => array(
					'class' => 'ext.swiftMailer.SwiftMailer',
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
		        'catalog'=>'constructcatalog',
		        'news'=>'page/list',
		        'page/captcha'=>'page/captcha',
		        'page/faq'=>'page/faq',
		        'page/ask'=>'page/ask',
		        'page/static/<view:\w+>'=>'site/page',
		        '<rubrika:[\w+-]{0,}\w+>/list/<section:[\w+-]{0,}\w+>'=>'page/listinrubric',
		        'news/list/<rubrika:[\w+-]{0,}\w+>'=>'page/listinrubric',
		        'news/list'=>'page/list',
		        'news/<id:\d+>'=>'page/show',
		        '<rubrika:[\w+-]{0,}\w+>/page/<id:\w+>'=>'page/byalias',
		        '/page/<id:\w+>'=>'page/byalias',
		        
		        'bcatalog/index'=>'constructcatalog/index',////////
		        'catalog/<path:[\w+-\/]{0,}\w+>/<alias:[\w+-]{0,}\w+>/<id:\d+>'=>'constructcatalog/info',///////товар
		        'catalog/<path:[\w+-\/]{0,}\w+>/<alias:[\w+-]{0,}\w+>'=>'constructcatalog/group',///////
		        'catalog/<path:[\w+-\/]{0,}\w+>/<alias:[\w+-]{0,}\w+>'=>'constructcatalog/group',///////
		        'catalog/<alias:[\w+-]{0,}\w+>'=>'constructcatalog/group',////////
		        
		        
		        'site/page/<view:\w+>'=>'site/page',
		        
		        '<controller:\w+>/<id:\d+>'=>'<controller>/show',//////////////lдля  отображения статей как бы на втором уровне
		        '<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
		        '<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				
			
			),
			'showScriptName'=>false,
		),
		
		'db'=>array(
		'class'=>'CDbConnection',
		
		/*
		'connectionString'=>'mysql:host=localhost;dbname=u1424315_atool',
		'username'=>'root',
		'password'=>'1234',
		*/
		/*
		'connectionString'=>'mysql:host=localhost;dbname=bogajniki',
		'username'=>'root',
		'password'=>'1234',
		*/
		
		'connectionString'=>'mysql:host=localhost;dbname=bsystems',
		'username'=>'root',
		'password'=>'1234',
		
		/*
		'connectionString'=>'mysql:host=localhost;dbname=u1424315_psg',
		'username'=>'u1424315_default',
		'password'=>'4hO8AeAe',
		*/
		/*
		'connectionString'=>'mysql:host=localhost;dbname=u1424315_psg',
		'username'=>'root',
		'password'=>'1234',
		*/
		//'charset'=>'cp1251',
		'charset' => 'utf8',
		'emulatePrepare'=>true, // needed by some MySQL installations
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
		'infoEmail'=>'support@b-system.ru',
		'coreElementDescr'=>'Про-тюнинг',
		'group_logo_x_limit'=>860,
		'group_logo_y_limit'=>400,
		'second_tree_root'=>1,
		'main_tree_root'=>2,
		'reserv_sklad'=>'0',
		'apache_auth'=>'', ////1:2@
		'products_char_filtr'=>array(5=>11), /////////группа = > id фильтра
		//'authenticated_catalog'=>array('group', 'index', 'getgroups', 'getyears', 'info',  'getgroups2', 'getyears', 'getkpptypes', 'search', 'error', 'zamki', 'tools', 'adv'),
		'authenticated_brands'=>array('ford'=>'Ford', 'nissan'=>'Nissan', 'kia'=>'Kia', 'hyundai'=>'Hyundai'),
	),
	'modules'=>array(
						'forum'=>array(
						'postPerPage'=>20,
						),
					),
	
);
