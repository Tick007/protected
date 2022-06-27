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
	'theme'=>'fortus',
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
			'news'=>'page/list',
			
			'page/region/<id:\d+>/<id2:\d+>'=>'page/city',
			'page/region/<id:\d+>'=>'page/region',
			
			'page/faq'=>'page/faq',
			'page/ask'=>'page/ask',
			
			
			
			'news/list'=>'page/list',
			'news/<id:\d+>'=>'page/show',
			'/page/<id:\w+>'=>'page/byalias',
		
			
			
			'catalog/<locktype:\w+>/<krepltype:(kpp|val|hood)>/<path:[\w+-\/]{0,}\w+>/<alias:[\w+-]{0,}\w+>/<id:\d+>'=>'constructcatalog/info',///////товар
			'catalog/<locktype:\w+>/<path:[\w+-\/]{0,}\w+>/<alias:[\w+-]{0,}\w+>/<id:\d+>'=>'constructcatalog/info',///////товар
			
			'catalog/<locktype:\w+>/<krepltype:(kpp|val|hood)>/<path:[\w+-\/]{0,}\w+>/<alias:[\w+-]{0,}\w+>'=>'constructcatalog/group',///////
			'catalog/<locktype:\w+>/<krepltype:(kpp|val|hood)>/<alias:[\w+-]{0,}\w+>'=>'constructcatalog/group',///////
			
			'catalog/<locktype:\w+>/<krepltype:(kpp|val|hood)>'=>'constructcatalog/index',
			
			'catalog/<locktype:\w+>'=>'constructcatalog/index',
			'catalog'=>'constructcatalog/index',
						
			
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
		
		'connectionString'=>'mysql:host=localhost;dbname=u1424315_fortus',
		'username'=>'u1424315_default',
		'password'=>'4hO8AeAe',
		
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
		'infoEmail'=>'krohin@constructrus.ru', 
		'coreElementDescr'=>'Про-тюнинг',
		'group_logo_x_limit'=>860,
		'group_logo_y_limit'=>400,
		'second_tree_root'=>1,
		'main_tree_root'=>2,
		'filters'=>array('year'=>2, 'kpp'=>8),
		'typs_krepleniya'=>array('kpp'=>'КПП', 'val'=>'Рулевого вала', 'hood'=>'Капота'),
		'apache_auth'=>'', ////1:2@
		'products_char_filtr'=>array(5=>11), /////////группа = > id фильтра
		'regions_root'=>552,//////////////ИД группы регионов
	),
	'modules'=>array(
						'forum'=>array(
						'postPerPage'=>20,
						),
					),
	
);
