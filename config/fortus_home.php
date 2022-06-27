<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'FORTUS AUTO',
	//'theme'=>'protuning', 
	//'theme'=>'bogajniki',
	//'theme'=>'bsystems',
	'theme'=>'fortus', 
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
			
			'news/list/<id:\d+>'=>'page/fortusnews',
			'news/year/<year:\d+>/<mounth:\d+>'=>'page/list',
			'news/year/<year:\d+>'=>'page/list',
			'news'=>'page/list',
			'page/region/<id:\d+>/<id2:\d+>'=>'page/city',
						
			'page/region/<id:\d+>'=>'page/region',
			'page/faq/<id:\d+>'=>'page/faqrubrics',
			'page/faq'=>'page/faqrubrics',
			'page/ask'=>'page/ask',
			'news/list'=>'page/list',
			'news/<id:\d+>'=>'page/show',
		//	'regions/<regid:\d+>'=>'page/show',
			'product/<alias:\w+>'=>'product/list',
			
			'vacancy/list/<id:\d+>'=>'vacancy/vacancyinfo', 
			
			'new'=>'site/new',
			'/page/<id:\w+>'=>'page/byalias',
			
			'contact'=>'site/contact',
			
			'catalog/<locktype:\w+>/<krepltype:(kpp|val|hood|garage|pricep)>/<view:(break|mini|classic|contra)>'=>'constructcatalog/index',
			
			'catalog/search/<locktype:\w+>/<krepltype:(kpp|val|hood|garage|pricep)>'=>'constructcatalog/search',///////
			
			'zamki/<locktype:\w+>/<krepltype:(kpp|val|hood|garage|pricep)>'=>'constructcatalog/zamki',
			
			'catalog/tools/<id:\d+>'=>'constructcatalog/tools',
			'catalog/tools'=>'constructcatalog/tools',
			
			'catalog/adv/<id:\d+>'=>'constructcatalog/adv',
			'catalog/adv'=>'constructcatalog/adv',
			
			'catalog/<locktype:\w+>/<krepltype:(kpp|val|hood|garage|pricep)>/<path:[\w+-\/]{0,}\w+>/<alias:[\w+-]{0,}\w+>/<id:\d+>'=>'constructcatalog/info',///////товар
			'catalog/<locktype:\w+>/<path:[\w+-\/]{0,}\w+>/<alias:[\w+-]{0,}\w+>/<id:\d+>'=>'constructcatalog/info',///////товар
			
			'catalog/<locktype:\w+>/<krepltype:(kpp|val|hood|garage|pricep)>/<path:[\w+-\/]{0,}\w+>/<alias:[\w+-]{0,}\w+>'=>'constructcatalog/group',///////
			'catalog/<locktype:\w+>/<krepltype:(kpp|val|hood|garage|pricep)>/<alias:[\w+-]{0,}\w+>'=>'constructcatalog/group',///////
			
			
			
			'catalog/<locktype:\w+>/<krepltype:(kpp|val|hood|garage|pricep)>'=>'constructcatalog/index',
			'catalog/<locktype:\w+>'=>'constructcatalog/index',
			'catalog'=>'constructcatalog/index',
			//'catalog/<path:[\w+-\/]{0,}\w+>/<alias:[\w+-]{0,}\w+>'=>'constructcatalog/group',///////
			//'catalog/<alias:[\w+-]{0,}\w+>'=>'constructcatalog/group',////////
			
			
			'site/page/<view:\w+>'=>'site/page',
			
			'<controller:\w+>/<id:\d+>'=>'<controller>/show',//////////////lдля  отображения статей как бы на втором уровне
			'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				
			
			),
			'showScriptName'=>false,
		),
		
		'db'=>array(
		'class'=>'CDbConnection',

		'connectionString'=>'mysql:host=localhost;dbname=fortus',
		'username'=>'root',
		'password'=>'1234',
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
	  
	
	
			'errorHandler'=>array(
// use 'site/error' action to display errors
            'errorAction'=>'constructcatalog/error',
			),
	  
		
	),



	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'webmaster@example.com',
		'infoEmail'=>'info@mul-t-lock.ru',
		'jobEmail'=>'job@mul-t-lock.ru',
		'cooperationEmail'=>'tick007@yandex.ru',
		'coreElementDescr'=>'Про-тюнинг',
		'group_logo_x_limit'=>860,
		'group_logo_y_limit'=>400,
		'second_tree_root'=>1,
		'main_tree_root'=>2,
		'vacancies_root'=>655, 
		'tools_root'=>657,/////////////Категория инструментов
		'adv_root'=>658,////////////Категория рекламы
		'apache_auth'=>'', ////1:2@
		'products_char_filtr'=>array(5=>11), /////////группа = > id фильтра
		'filters'=>array('year'=>2, 'kpp'=>12, 'kreplen'=>11, 'kppfrontend'=>8),
		'typs_kpp'=>array('auto'=>'Автомобильный', 'moto'=>'Мотоциклетный', 'outher'=>'Другие'),
		'typs_krepleniya'=>array('kpp'=>'КПП', 'val'=>'Рулевого вала', 'hood'=>'Капота'),
		'regions_root'=>552,//////////////ИД группы регионов
		'fortus_carusel'=>864,
		'yandex_map_key'=>'AO-HNFIBAAAAV0bkBgIA-HYacDHx_leZ_ZpTWt9UMFdphpgAAAAAAAAAAAAMVKPxTV1GgA3Tw5PUhO-4_xQmQA==',
		'install_centers'=>670,
		'db3'=>array(
			'connectionString' => 'mysql:host=webserver;dbname=wwwconstructrusr',
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => '1234',
			'charset' => 'utf8',
// включаем профайлер
			'enableProfiling'=>true,
// показываем значения параметров
			'enableParamLogging' => true,
		),
		
	),
	'modules'=>array(
						'forum'=>array(
						'postPerPage'=>20,
						),
					),
	
);
