<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'ПРОТЮНИНГ new',
	'theme'=>'landsoda',
	// preloading 'log' component
	'preload'=>array('log', 'db'),
	'charset'=>'utf-8',
	//'charset'=>'Windows-1251',
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
				'errorAction'=>'site/error',
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
		
			/*
			'db'=>array(
					'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
			),
			*/
			
			
		'db'=>array(
		'class'=>'CDbConnection',
		'connectionString'=>'mysql:host=localhost;dbname=soda_db',
		'username'=>'soda',
		'password'=>'s0wwpI!?PKe4',
		'emulatePrepare'=>true, // needed by some MySQL installations
		'charset' => 'utf8',
		
		),
		
		
		//'GP' => array(
		//'class'=>'GP',
		//			),

	  
		
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'tick007@yandex.ru',
		'supportEmail'=>'tick007@yandex.ru',
		'display_error'=>true,
		'self_contragent'=>1,
		'infoEmail'=>'a.kravtsov1987@gmail.com',
		//'infoEmail'=>'protuning-psg@yandex.ru',
		'coreElementDescr'=>'Про-тюнинг',
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
				'mail'=>array('first_name,second_name,client_email,client_email_copy,client_tels,delivery,payment,last_name,client_post_index,client_city', 'required', 'on'=>'mail'), 
			),
		),
		'contactformattributes'=>array(
			'verifyCode'=>'Антиспам',
			'name'=>'Имя',
			'subject'=>'Телефон',
			'body'=>'Сообщение',
			 'tel'=>'Телефон',
				
		),
		'contactformrules'=>array(
			// name, email, subject and body are required
			array('name, subject, body', 'required'),
			// verifyCode needs to be entered correctly
			array('tel', 'safe'),
			//array('verifyCode', 'captcha', 'allowEmpty'=>!extension_loaded('gd')),
				
		),
		'bottle_counter'=>array(
			'max'=>28,
			'min'=>11,
			'interval'=>40000, //mc
				'names' => array (
						'Гурьева Майя',
						'Князева Ирина',
						'Суворова Регина',
						'Матвиенко Клавдия',
						'Горбачёва Нина',
						'Баранов Яков',
						'Павлов Юрий',
						'Селиверстова Евдокия',
						'Путин Сергей',
						'Родионова Ирина',
						'Гусев Филат',
						'Воронцов Мэлс',
						'Зуева Надежда',
						'Шарапова Надежда',
						'Елисеев Станислав',
						'Макарова Ангелина',
						'Никифорова Галина',
						'Ермакова Валентина',
						'Павлова Анна',
						'Рябов Антонин',
						'Макаров Глеб',
						'Калинина Ия',
						'Нестеров Даниил',
						'Исаков Антон',
						'Давыдова Вера',
						'Юдина Лариса',
						'Самсонов Бронислав',
						'Титова Виктория',
						'Копылова Зинаида',
						'Мясникова Ульяна',
						'Овчинников Арсений',
						'Васильев Мстислав',
						'Родионов Анатолий',
						'Михайлов Серапион',
						'Волкова Марфа',
						'Сысоев Федосей',
						'Одинцов Олег',
						'Мясников Якун',
						'Зайцев Антонин',
						'Боброва Феврония',
						'Кузнецов Тихон',
						'Блинов Яков',
						'Силина Фаина',
						'Поляков Антонин',
						'Силин Валентин',
						'Шарапов Алексей',
						'Фомичёва Октябрина',
						'Наумова Лора',
						'Филиппова Наталья',
						'Осипов Виталий',
						'Моисеев Владлен',
						'Воронов Ярослав',
						'Степанов Эдуард',
						'Суханова Зоя',
						'Исакова Екатерина',
						'Фадеева Венера',
						'Семёнов Улеб',
						'Никитин Анатолий',
						'Суханов Михаил',
						'Пестова Анна',
						'Иванов Дмитрий',
						'Лыткина Евфросиния',
						'Стрелкова Нонна',
						'Анисимов Гордей',
						'Яковлева Анжела',
						'Игнатьев Николай',
						'Мухин Созон',
						'Игнатова Виктория',
						'Романов Авдей',
						'Тетерина Фёкла',
						'Анисимова Эмилия',
						'Максимова Фаина',
						'Ершова Пелагея',
						'Андреев Глеб',
						'Жуков Митрофан',
						'Овчинникова Тамара',
						'Мамонтов Роман',
						'Лыткин Виктор',
						'Нестеров Константин',
						'Захаров Евгений',
						'Дементьев Ярослав',
						'Кузнецова Лукия',
						'Кудряшов Авксентий',
						'Борисов Виктор',
						'Воронцов Мстислав',
						'Попова Оксана',
						'Филиппова Василиса',
						'Михеева Антонина',
						'Щукин Куприян',
						'Костин Фёдор',
						'Лаврентьев Донат',
						'Котов Егор',
						'Рыбакова Евдокия',
						'Никонова Оксана',
						'Красильников Семён',
						'Федосеев Тихон',
						'Самсонова Лора',
						'Медведьев Леонид',
						'Лапин Вячеслав',
						'Богданова Ксения' 	),
				'cities'=>array(
						'Химки',
						'Краснодар',
						'Калининград',
						'Красногорск',
						'Сочи',
						'Сургут',
						'Тюмень',
						'Мытищи',
						'Белгород',
						'Ставрополь',
						'Одинцово',
						'Воронеж',
						'Курск',
						'Липецк',
						'Великий Новгород',
						'Тамбов',
						'Нижневартовск',
						'Нижнекамск',
						'Кемерово',
						'Ростов-на-Дону',
						'Пенза',
						'Рязань',
						'Новый Уренгой',
						'Подольск',
						'Астрахань',
						'Новосибирск',
						'Орёл',
						'Уфа',
						'Екатеринбург',
						'Новороссийск',
						'Саратов',
						'Старый Оскол',
						'Хабаровск',
						'Барнаул',
						'Владимир',
						'Люберцы',
						'Пятигорск',
						'Саранск',
						'Томск',
						'Нижний Новгород',
						'Вологда',
						'Смоленск',
						'Альметьевск',
						'Ярославль',
						'Тверь',
						'Псков',
						'Ульяновск',
						'Коломна',
						'Брянск',
						'Красноярск',
						'Благовещенск',
						'Иркутск',
						'Киров',
						'Оренбург',
						'Щёлково',
						'Тула',
						'Челябинск',
						'Нефтекамск',
						'Черкесск',
						'Самара',
						'Волгоград',
						'Омск',
						'Волгодонск',
						'Южно-Сахалинск',
						'Пермь',
						'Череповец',
						'Елец',
						'Батайск',
						'Новокузнецк',
						'Балашиха',
						'Королёв',
						'Абакан',
						'Междуреченск',
						'Кострома',
						'Петрозаводск',
						'Первоуральск',
						'Энгельс',
						'Ижевск',
						'Нефтеюганск',
						'Курган',
						'Йошкар-Ола',
						'Камышин',
						'Салават',
						'Находка',
						'Серпухов',
						'Новочеркасск',
						'Димитровград',
						'Нальчик',
						'Ленинск-Кузнецкий',
						'Набережные Челны',
						'Сергиев Посад',
						'Армавир',
						'Иваново',
						'Владивосток',
						'Таганрог',
						'Стерлитамак',
						'Сыктывкар',
						'Чебоксары',
						'Калуга',
						'Магнитогорск'),
		),
		'order' => array (
						'product_id' => 119 ,
						'product_price'=>4900,
						'md5key'=>'Hy73j!jwox',
				) ,
	),

	
);
