<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Багажники '.$_SERVER['HTTP_HOST'],
	//'theme'=>'protuning',
	'theme'=>'bogajniki',
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
			'new'=>'site/new',
			'contact'=>'site/contact',
			'news/<id:\d+>'=>'page/show',
			'product/<alias:\w+>'=>'product/list',
			'sitemap.xml'=>'sitemap/sitemap',
			
			
			'catalog/getyears'=>'catalog/getyears',
			'catalog/getgroups'=>'catalog/getgroups',
			
			'catalog/<alias:(bagajniki)>/<id:\d+>'=>'catalog/info',
			'catalog/<alias:(bagajniki)>'=>'catalog/group',
			
			'catalog/<alias:\w+>/<filter_group:\d+>/<filter:\w+>'=>'catalog/group2',
			'catalog/<alias:\w+>'=>'catalog/group2',
			
			'catalog/<alias:\w+>/<id:\d+>'=>'catalog/info',
		
			'product/<alias:\w+>/<pd:\d+>'=>'product/details',
			'product/<id:\d+>'=>'product/list',
			'product/details/<pd:\d+>'=>'product/details',
			
			'/page/<id:\w+>'=>'page/byalias',
			'<controller:\w+>/<id:\d+>'=>'<controller>/show',//////////////lдля  отображения статей как бы на втором уровне
			'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
			
			'<id:\d+>'=>'site/error',
				
			
			),
			'showScriptName'=>false,
		),
		
		'db'=>array(
		'class'=>'CDbConnection',
		
		
		'connectionString'=>'mysql:host=localhost;dbname=u1424315_bag',
		'username'=>'u1424315_bag',
		'password'=>'J48x>djs4hls',
		
		/*
		'connectionString'=>'mysql:host=localhost;dbname=u1424315_psg',
		'username'=>'u1424315_default',
		'password'=>'4hO8AeAe',
		
		'connectionString'=>'mysql:host=localhost;dbname=u1424315_psg',
		'username'=>'root',
		'password'=>'1234',
		*/
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
		'infoEmail'=>'info@roofbag.ru',
		'coreElementDescr'=>'Про-тюнинг',
		'group_logo_x_limit'=>860,
		'group_logo_y_limit'=>500,
		'second_tree_root'=>1,
		'main_tree_root'=>2,
		'apache_auth'=>'bag:123@', ////1:2@
		'products_char_filtr'=>array(3=>28,4=>28,5=>28,7=>28,  10=>28, 8=>28, 31=>28), /////////группа = > id фильтра
	),
	'modules'=>array(
						'forum'=>array(
						'postPerPage'=>20,
						),
					),
	
);
