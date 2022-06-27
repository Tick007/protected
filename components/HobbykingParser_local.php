<?php

Yii::import('ext.SParser.*');
class HobbykingParser extends SParserController
{
	public $inputCharset = 'utf-8';
	public $outputCharset = 'utf-8';
	public $baseUrl = 'http://www.armchairracer.com.au';
	public $headers = array(
		'User-Agent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.11 (KHTML, like Gecko) Chrome/23.0.1271.64 Safari/537.11',
		'Accept-Language' => 'ru-RU,ru;q=0.8,en-US;q=0.6,en;q=0.4',
	);
	public $pauseSeconds = 1;
	
	const MAIN_PARENT_CATEGORY_ID = 0;
	const ARTICLE_PREFIX = '';
	const CONTENT_PAGE = 1;
	const EMPTY_PAGE = 2;
	
	public $totalItems = 0;
	public $totalErrors = 0;
	public $vocabulary_chars=array(
			'type' => 'Тип',
            'body' => 'Кузов',
			'body feature'=>'Кузов',
			'n-digital'=>'Digital',
			'n-digital upgradable'=>'Digital',
			'digital upgradable'=>'Digital',
 			'lights' => 'Фары',
			'chassis' => 'шасси',
			'drivers'=>'Водители',
			'driver'=>'Водители',
			'steering'=>'Руление',
            'motor' => 'Электродвигатель',
            'motor configuration' => 'Расположение двигателя',
			'chassis rear mounted in-line' => 'Расположение двигателя',
			'chassis rear mounted anglewinder'=> 'Расположение двигателя',
			'feature'=>'Особенность',
            'gears' => 'Шестирёнки',
			'gears ratio'=>'Количество зубьев у шестирёнок',
			'pinion'=> 'Ведущая шестирёнка',
			'pinion gear'=> 'Ведущая шестирёнка',
			'spur'=> 'Осевая шестирёнка',
			'spur gear'=> 'Осевая шестирёнка',
			'guide'=>'Киль',
			'gear ratio'=>'Передаточный механизм',
            'axle dia' => 'Диаметр оси',
			'axle dimensions'=> 'Диаметр оси',
           	'where'=>'Участие в гонках',
			'were'=>'Участие в гонках',
			'wheels'=>'Колесные диски',
			'wheel' => 'Колесные диски',
			'wheel diameter' => 'Колесные диски',
			'hubs front'=>'Колесные диски',
			'hubs'=> 'Колесные диски (ведущие)',
			'hubs rear'=> 'Колесные диски (ведущие)',
            'tyres' => 'Покрышки',
			'tyre diameter'=>'Покрышки',
			'rear tyre diameter'=>'Задние покрышки',
			'tyres dia'=>'Покрышки',
			'tyre dia'=>'Покрышки',
			'team'=>'Команда',
			'interior'=>'Интерьер',
			//'Standard Rubber 19 x 10mm front 20.5 x 11.5mm rear'=>
            'magnet' => 'Магнит',
			'drive'=>'Привод',
            'bushes' => 'Втулки',
			'downforce'=>'Прижимная сила',
			'motor magnet downforce'=>'Прижимная сила двигателя',
            'scale' => 'Масштаб',
            'issued' => 'Дата выпуска', 
			//November 2012
            'shipping weight' => 'Вес с упаковкой (брутто)',
            'manufactured by' => 'Оригинальная модель',
			'overall length'=>'Общая длина',
			'overall length chassis'=>'Общая длина',
			'car length'=>'Общая длина',
			'wheelbase'=>'Колёсная база',
			'axle/hub width'=>'Ширина осей',
			"front tyre dia. & width"=>'Диаметр и ширина передних покрышек',
			'rear tyre dia. & width'=>'Диаметр и ширина задних покрышек',
			'car weight'=>'Вес',
			'weight'=>'Вес',
			'overall weight'=>'Вес',
			'bodyshell weight'=>'Вес',
			'safety notice'=>'Предупреждение',
			'note'=>'Примечание',
			'model'=>'Описание модели',
			'bearings'=>'Подшипники',
			'condition'=>'Состояние',
			'suspension'=>'Подвеска',
			'car dimensions (mm)'=>'Размеры',
			'motor lead wires'=>'Провода питания двигателя',
			'motot lead wires'=>'Провода питания двигателя',
			'cable'=>'Провода питания двигателя',
			'tourque'=>'Крутящий момент двигателя',
			'history'=>'История',
			'finish'=>'отделка',
			'length'=>'Длина',
			'width'=>'Ширина',
			'packaging'=>'Упаковка',
			
			
	);
	
	public $vocabulary_values=array(
			'Upgradable using Ninco chip 40304' =>'Апгрейд установкой контроллера Ninco chip 40304',
			'Upgradeable using Ninco chip 40304'=>'Апгрейд установкой контроллера Ninco chip 40304',
			'Yes using 40304 ndigital chip'=>'Апгрейд установкой контроллера Ninco chip 40304',
			'Yes with 20320'=>'Апгрейд установкой контроллера  SCX DIGITAL (набор 20320)',
			'Yes with 20240'=>'Апгрейд установкой контроллера SCX DIGITAL (набор 20240)',
			'Yes, with 20320 kit'=>'Апгрейд установкой контроллера SCX DIGITAL (набор 20320)',
			'Yes use kit 20320'=>'Апгрейд установкой контроллера SCX DIGITAL (набор 20320)',
			'Yes, use 20320 kit'=>'Апгрейд установкой контроллера SCX DIGITAL (набор 20320)',
			
			
			'Super Resistant (no interior)'=>'Сверхпрочный (без интерьера)',
			'Super Resistant (no interior) Blackened Windows'=>'Сверхпрочный (без интерьера)',
			'Blackened Windows'=>'Сверхпрочный (без интерьера)',
			'High Detail Full Interior'=>'Высокодетализированный c интерьером',
			'High Detail Full interior'=>'Высокодетализированный c интерьером',
			'High Detail'=>'Высокодетализированный',
			'High Detail no interior black windows'=>'Высокодетализированный без интерьера',
			'None' => 'Нет',
			'Nylon'=>'Нейлон',
			
			'Red Plastic Ratio 32'=>'Красный пластик, 32 зуба',
			'Plastic Ratio 27'=>'Пластик, 27 зубьев',
			'All plastic Ratio 27'=>'Пластик, 27 зубьев',
			'Motor Pod Anglewinder'=>'Под углом к ведущей оси',
			'Anglewinder motor mount'=>'Под углом к ведущей оси',
			'Motor Pod Angle Winder'=>'Под углом к ведущей оси',
			'Anglewinder 3 point motor mount'=>'Под углом к ведущей оси',
			'Angle winder'=>'Под углом к ведущей оси',
			'Rear Mounted Anglewinder Motor'=>'Под углом к ведущей оси',
			
			'Transparent chassis with screw motor support'=>'Прозрачное',
			
			'Anglewinder Motor Pod'=>'Под углом к ведущей оси',
			'Anglewinder'=>'Под углом к ведущей оси',
			'Inline front mounted'=>'Продольно, спереди',
			'Front mounted In Line'=>'Продольно, спереди',
			'Rear Mounted Inline Motor'=>'Для продольной установки мотора',
			'Inline Tilting chassis'=>'Продольно сзади',
			'In Line rear mounted'=>'Продольно сзади',
			'In Line'=>'Продольно сзади',
			'Inline'=>'Продольно сзади',
			'Tilting Inline'=>'Продольно сзади',
			'Rear Mounted Inline with Motor pod'=>'Установка моторной капсулы продольно',
			'Standard Plastic'=>'Стандартный пластик',
			'Requires motor pod'=>'Под установку моторной капсулы',
			'Uses new anglewinder motor mount  see photo'=>'Установка моторной капсулы под углом',
			
			'Ready for competition out of the box'=>'Готовность к гонке прямо из коробки',
			'Prorace with Motor Mount'=>'Prorace',
			
			'All Brass' => 'Все латунные',
			'55 mm Front: 57 mm Rear'=>'Передняя 55mm, ведущая 57mm',
			'This product is not suitable for children under 3 years because of small parts which can present a choking hazard.'=>'Этот продукт не предназначен для детей младше 3 лет из-за мелких деталей, которые могут представлять опасность удушья.',
			'Brass at rear'=>'Латунь сзади',
			'Rear brass'=>'Латунь сзади',
			'Rear Brass'=>'Латунь сзади',
			'Brass rear'=>'Латунь сзади',
			'Brass in rear'=>'Латунь сзади',
			'Brass fromt and rear'		=>'Латунь спереди и сзади',	
			'Brass'=>'Латунь',
			'Brass Rear'=>'Латунь сзади',
			'Brass front and rear'=>'Латунь спереди и сзади',
			'Front & Rear Brass'=>'Латунь спереди и сзади',
			'ProRAce Brass rear'=>'ProRace латунные сзади',
			'ProRace Brass rear'=>'ProRace латунные сзади',
			'All brass (x4)'=>'Латунь спереди и сзади',
			
			
			'Rear Wheels'=>'Задние колёса',
			'Rear wheels'=>'Задние колёса',
			'Rear wheel'=>'Задние колёса',
			'All wheel drive via belt'=>'Полный (ременный)',
			'All wheel via belt'=>'Полный (ременный)',
			'All Wheel Drive'=>'Полный',
			'All wheels (4x4)'=>'Полный',
			
			
			
			'Sprung swivel with copper braid'=>'Подпружиненный поворотный с медной оплеткой',
			'Sprung swivle with copper braid'=>'Подпружиненный поворотный с медной оплеткой',
			'Sprung with copper braid'=>'Подпружиненный поворотный с медной оплеткой',
			'Swivel'=>'Подпружиненный поворотный с медной оплеткой',
			'Swivel guide'=>'Подпружиненный поворотный с медной оплеткой',
			'Standard Swivle with Copper braid'=>'Подпружиненный поворотный с медной оплеткой',
			'Spring swivel with copper braid'=>'Подпружиненный поворотный с медной оплеткой',
			'Sprung'=>'Подпружиненный',
			'Standard swivel guide'=>'Подпружиненный поворотный с медной оплеткой',
			'Suspension swivel guide'=>'Подпружиненный поворотный с медной оплеткой',
			'Copper braid'=>'Подпружиненный поворотный с медной оплеткой',
			'Pivoting ARS 2007 flat blades'=>'Подпружиненный с ARS',
			'ARS Guide with Suspension'=>'Подпружиненный с ARS',
			'ARS with Suspension'=>'Подпружиненный с ARS',
			'Suspension'=>'Подпружиненный',
			'Drop Arm'=>'Подпружиненный',
			'Off Road Extension Arm with Suspension'=>'Off Road подпружиненный с ARS',
			
			'Bar, located behind motor'=>'Брусок, расположен позади мотора',
			'Bar located at rear behind motor'=>'Брусок, расположен позади мотора',
			'Bar'=>'Брусок',
			'Yes Rectangular Rear'=>'Брусок, расположен позади мотора',
			'Rectangular bar at rear'=>'Брусок, расположен позади мотора',
			'Bar located at rear'=>'Брусок перед мотором',
			'Bar mid mounted'=>'Брусок перед мотором',
			'Round button' =>'Таблетка в центре',
			'Central Bar'=>'Брусок перед мотором',
			'Yes Rectangular 2.5 mm'=>'Брусок, расположен позади мотора',
			'Round located in front of motor'=>'Круглый, расположен в центре',
			'Round located centrally'=>'Круглый, расположен в центре',
			'Round Centrally located' =>'Круглый, расположен в центре',
			'Round infront of motor'=>'Круглый, расположен в центре',
			'Round, located in front of motor'=>'Круглый, расположен в центре',
			
			'New in Original Crystal Box'=>'Новый в оригинальной прозрачной упаковке',
			'Plastic'=>'Пластик',
			'All Plastic'=>'Пластик',
			
			'Yes Xenoneffect headlights'=>'Да, с эффектом ксенона',
			'Adjustable and removable'=>'Регулируемый с возможностью удаления',
			'Front and Rear'=>'Передние и задние фонари',
			'Front and rear'=>'Передние и задние фонари',
			'Front & Rear'=>'Передние и задние фонари',
			'Zenon Front and Standard Rear'=>'Да, с эффектом ксенона',
			'Rear Only'=>'Только задние фонари',
			
			'Yes, Front wheel'=>'Да, передние колёса',
			
			'No'=>'Нет',
			'Standard Rubber 20 x 10mm'=>'Стандартные, резина 20 x 10mm',
			'Standard Rubber 19 x 10mm'=>'Стандартные, резина 19 x 10mm',
			'Standard Rubber 22 x 7mm'=>'Стандартные, резина 22 x 7mm',
			'Standard Rubber'=>'Стандартные, резина',
			
			'Yes Proshock all round'=>'Proshock по кругу',
			'Yes Proshick all round'=>'Proshock по кругу',
			'ProShock Front & Rear'=>'Proshock по кругу',
			'Proshock Independant'=>'Proshock по кругу',
			'Proshock Independant 4 wheel suspension (Blue)'=>'Proshock по кругу',
			'ProShock (blue) suspension'=>'Proshock по кругу',
			'ProShock suspension'=>'Proshock по кругу',
			'All Round'=>'По кругу',
			
			
			
			'Detailed Interior'=>'Детализированный интерьер',
			'Extra Long'=>'Экстра длинный',
			'Proshock Suspension'=>'Подвеска Proshock',
			'Light Weight Body'=>'Облегченный кузов',
			
			'New never opened, still sealed with heat srink plastic'=>'Новый в упаковке',
			
			
			'Dirt Effect'=>'Эмуляция заляпанности грязью',
			'Yes'=>'Да',
			
	);
	
	public function translate_chars($income){
		//var_dump($income);
		//echo '<br>';
		$income = trim(str_replace('&nbsp;', ' ', $income));
		if(strlen($income)>70 OR strstr($income, 'mso-special-character') OR $income=="was $79 - now $59 -" OR $income='was $49 - now $44 -' OR trim($income)=='was $49 - now $39 -' OR $income=="didier auriol began with this ford sierra its reign in corsica.") return NULL;
		if(strstr($income, 'issued')) return $this->vocabulary_chars['issued'];
		$income=str_replace('  ', ' ', $income);
		//$income=htmlspecialchars($income);
		$income=htmlspecialchars_decode($income);
		if(isset($this->vocabulary_chars[$income])) return $this->vocabulary_chars[$income];
		else {
			echo 'Неопознанная характеристика: ';
			var_dump($income);
			exit();
		}
	}
	
	public function translate_values($income){
		$income = trim(str_replace('&nbsp;', ' ', $income));
		if(isset($this->vocabulary_values[$income])) return $this->vocabulary_values[$income];
		else return $income;
	}
	
	public function commandItem($url, $ignoreErrors = false, $make_record=NULL)
	{
		$result = array();
		//$url = $this->getArg('url');
		//$ignoreErrors = $this->getArg('ignoreErrors') == 'true' ? true : false;
		
		if($this->existsParserRecord($url, $ignoreErrors))
			return array('error' => 'Запрос уже был обработан ранее.');
		
		// Определить парсер и создать запрос
		$parser = $this->getParser();
		
		
		if(!$parser->sendGet($url))
			return $this->createParseError('Невозможно обработать запрос.', $url);
		
		//print_r($parser->getContents());
		
		// Название
		$tmp1 = $parser->parse('%<h1.*?>(.*?)</h1>%is');
		if(!isset($tmp1[1]) || !$result['productName'] = $this->clean($tmp1[1]))
			return $this->createParseError('Невозможно извлечь название.', $url);
		// Категория
	if($ItemB = $parser->parseInnerTag('div', array('id' => 'navBreadCrumb')))////////див бредкрамбса
		{
			//echo '<br>Передщ вызовом поиска a:<br>';
			$pattern= '|<a[^>]+>(.+?)</a>|';
			$tmp_2 = $parser->parseTag('a', array(), $ItemB,  4, $pattern);
			if(isset($tmp_2) AND is_array($tmp_2)==true) $tmp2 = $tmp_2[1][2];
			
			//print_r($tmp2);
			
			
			if(!$result['categoryName'] = $this->clean($tmp2))
				return $this->createParseError('Невозможно извлечь категорию.', $url);
		}
		//else return $this->createParseError('Невозможно извлечь категорию.', $url);
		
		//echo $result['categoryName'];
		/*
		if($result['categoryName']=='NINCO' ) $result['categoryName']='Машинки Ninco';
		if($result['categoryName']=='SCX' ) $result['categoryName']='Машинки SCX';
		if($result['categoryName']=='SCALEXTRIC' ) $result['categoryName']='Машинки Scalextric';
		*/
		////Количество
		
		//echo 'qweqwe';
		$kolich =  $parser->parseInnerTag('div', array('id' => 'pstock2'));
		//print_r($kolich);
		//exit();
		
		if($kolich || $kolich=='0')////////Количество
		
        {
		//	echo 'Остатки = ';
        	//print_r($kolich);
		//	echo '<br>';        
		
		$pat = '/([0-9]{1,5})(\+{0,})/';
		preg_match_all($pat,$kolich,$matches);
		
	//	print_r($matches);
	//	exit();
		
		if(isset($matches)){
			if(isset($matches[1])){
				if(isset($matches[1][0])) $result['kolich'] =$matches[1][0];
			}
		}
		
		}
		
		if($price =  $parser->parseInnerTag('span', array('id' => 'price_lb')))///////Цена
		
        {
		//	echo 'Цена = ';
        //	print_r($price);
		//	echo '<br>';        
		//$result['price'] = $price;
		$pat = '/([A-Z]{1,5})([+-]?\d+\.?\d*)/';
		preg_match_all($pat,$price,$matches);
		if(isset($matches)){
			//print_r($matches);
			if(isset($matches[1]) && isset($matches[2])){
				if(isset($matches[1][0])) {
					$result['price'] =$matches[2][0];
					$result['cur'] = $matches[1][0];
				}
			}
		}
        }
		
		

		
		
			
		
		// Цена
		
	
		
		return array('result' => $result);
	}
	
	public function commandPage($url)
	{
		// Определить парсер и создать запрос
		$parser = $this->getParser();
		if(!$parser->sendGet($url))
		{
			$result = $this->createParseError('Невозможно обработать запрос.', $url);
			$this->show($result['error']);
			return $result;
		}
		
		$links = $parser->parseAll('%<a.*?item_link.*?href="(.*?)"?>%is');
		if(isset($links[1]) && !empty($links[1]))
		{
			foreach($links[1] as $link)
			{
				$this->pause();
				$this->totalItems++;
				$data = $this->commandItem($this->baseUrl . $link);
				if(isset($data['error']) && $data['error'])
				{
					$this->log($data['error'], 'error');
					$this->totalErrors++;
				}
				else
				{
					$this->log($data['result']['categoryName'] . ' - ' . $data['result']['productName']);
				}
			}
			return self::CONTENT_PAGE;
		}
		else
		{
			return self::EMPTY_PAGE;
		}
	}
	
	public function commandCategory($url)
	{
		// Определить парсер и создать запрос
		$parser = $this->getParser();
		if(!$parser->sendGet($url))
		{
			$result = $this->createParseError('Невозможно обработать запрос.', $url);
			$this->show($result['error']);
			return $result;
		}
		
		$pageNum = 1;
		do
		{
			$this->pause();
			$pageUrl = $url . '?page=' . $pageNum;
			$this->log('Parse URL=' . $pageUrl . '...');
			$data = $this->commandPage($pageUrl);
			if(is_array($data) && isset($data['error']))
			{
				$this->log('Error parse page: ' . $result['error']);
			}
			$pageNum++;
		}
		while($data === self::CONTENT_PAGE);
	}
	
	public function commandCatalog()
	{
		// Определить парсер и создать запрос
		$parser = $this->getParser();
		if(!$parser->sendGet())
		{
			$result = $this->createParseError('Невозможно обработать запрос.');
			$this->show($result['error']);
		}
		
		$links = $parser->parseAll('%<div.*?class=\'menu.*?\'.*?<a.*?href=\'(.*?)\'.*?>(.*?)</a>%is');
		if(isset($links[1]) && !empty($links[1]))
		{
			foreach($links[1] as $k => $link)
			{
				$this->pause();
				$this->log('КАТЕГОРИЯ: ' . $links[2][$k]);
				$this->commandCategory($this->baseUrl . $link);
			}
			return true;
		}
		else
		{
			return false;
		}
	}
	
	private function createParseError($msg, $url, $productId = null)
	{
		//$this->setParserRecord($url, $productId, ParserModel::STATUS_FAILURE);
		return array('error' => $msg);
	}
	
	private function existsParserRecord($url, $ignoreErrors = false)
	{
		$record = $this->getParserRecord($url);
		if($ignoreErrors)
			return $record && $record->status == ParserModel::STATUS_SUCCESS;
		else
			return $record;
	}
	
	private function getParserRecord($url)
	{
		$key = md5($url);
		return ParserModel::model()->findByPk($key);
	}
	
	private function setParserRecord($url, $product_id, $status)
	{
		$key = md5($url);
		if(!$record = ParserModel::model()->findByPk($key))
		{
			$record = new ParserModel();
			$record->key = $key;
			$record->url = $url;
		}
		
		if($product_id)
			$record->product_id = $product_id;
		
		$record->status = $status;
		$record->parsed_time = new CDbExpression('NOW()');
		$record->save();
	}
	
	private function handleCategory($name, $parentId)
	{
		if(!$parentCategory = Categories::model()->findByAttributes(array(
			'category_name' => $name,
			//'parent' => $parentId,
		)))
		{
			$parentCategory = new Categories();
			$parentCategory->category_name = $name;
			$parentCategory->title = $name;
			$parentCategory->keywords = $name;
			$parentCategory->description = $name;
			$parentCategory->h1 = $name;
			$parentCategory->alias = preg_replace('/[^a-z0-9_-]/', '', str_replace(' ', '_', strtolower(FHtml::translit($name))));
			$parentCategory->parent = $parentId;
			$parentCategory->show_category = 0;
			$parentCategory->sort_category = 0;
			$parentCategory->save();
			
			// Сгенерировать path
			$parentCategory->move($parentId);
		}
		return $parentCategory->category_id;
	}
	
	private function handleProduct($name, $categoryId, $price, $articul)
	{
		if(!$product = Products::model()->findByAttributes(array(
			'product_name' => $name,
			'category_belong' => $categoryId,
			'product_price' => $price,
			'product_article' => $articul,
		)))
		{
			$product = new Products();
			$product->product_name = $name;
			$product->product_html_title = $name;
			$product->product_html_description = $name;
			$product->product_html_keywords = $name;
			$product->category_belong = $categoryId;
			$product->product_price = $price;
			$product->product_article = $articul;
			$product->save();
			
			// Сгенерировать артикул
			//$product->product_article = self::ARTICLE_PREFIX . $product->id;
			//$product->save();
		}
		return $product->id;
	}
	
	private function handleCharacteristics($name, $value, $categoryId, $productId)
	{
		if(!$char = Characteristics::model()->findByAttributes(array(
			'caract_name' => $name,
		)))
		{
			$char = new Characteristics();
			$char->caract_name = $name;
			$char->char_type = 2;
			$char->save();
		}
		
		if(!$charCats = Characteristics_categories::model()->findByAttributes(array(
			'characteristics_id' => $char->caract_id,
			'categories_id' => $categoryId,
		)))
		{
			$charCats = new Characteristics_categories();
			$charCats->characteristics_id = $char->caract_id;
			$charCats->categories_id = $categoryId;
			$charCats->save();
		}
		
		if(!$charVal = Characteristics_values::model()->findByAttributes(array(
			'id_caract' => $char->caract_id,
			'id_product' => $productId,
		)))
		{
			$charVal = new Characteristics_values();
			$charVal->id_caract = $char->caract_id;
			$charVal->id_product = $productId;
			$charVal->value = $value;
			$charVal->save();
		}
	}
	
	private function handlePhotos($mainPhoto, $photos, $productId, $productName)
	{
		//print_r($photos);
		$createdPhotos = array();
		foreach($photos as $src)
		{
			$src1= $src ;
			$src = $this->baseUrl.'/'.$src;
			$isMain = 1;
			if(!$photoContent = file_get_contents($src))
			{
				
				if(!$photoContent = file_get_contents($src))
					continue;
			}
			
			//echo $src;
			//exit();
			
			$Photo = new Pictures();
			$Photo->ext = 'jpg';
			$Photo->type = 1;
			$Photo->description = $productName;
			$Photo->comments = $productName;
			$Photo->save();
			
			if(!$PhotoProduct = Picture_product::model()->findByAttributes(array(
				'product' => $productId,
				'picture' => $Photo->id,
			)))
			{
				$photo = '/pictures/add/' . $Photo->id . '.' . $Photo->ext;
				$resPhotoUrl = 'http://' . $_SERVER['HTTP_HOST'] . $photo;
				$resPhotoPath = $this->getRootPath() . $photo;

				if(file_exists($resPhotoPath))
					unlink($resPhotoPath);

				if($ress = file_put_contents($resPhotoPath, $photoContent))
				{
					$this->createIcon($Photo->id, 'medium');
					$this->createIcon($Photo->id, 'small');

					$PhotoProduct = new Picture_product();
					$PhotoProduct->product = $productId;
					$PhotoProduct->picture = $Photo->id;
					$PhotoProduct->is_main = $isMain;
					$PhotoProduct->save();

					$createdPhotos[] = $resPhotoUrl;
				}
			}
		}
		return $createdPhotos;
	}
	
	private function createIcon($imageName, $type)
	{
		if($type == 'medium')
		{
			$height = 300;
			$dir = 'icons';
		}
		elseif($type == 'small')
		{
			$height = 150;
			$dir = 'icons_small';
		}
		else return false;
		
		$inputFile = $this->getRootPath() . "/pictures/add/$imageName.jpg";
		$outputFile = $this->getRootPath() . "/pictures/add/$dir/$imageName.png";
		
		if(!file_exists($inputFile))
			return false;
		
		if(file_exists($outputFile))
			unlink($outputFile);
		
		$img = imagecreatefromjpeg($inputFile);
		$imgWidth = imagesx($img);
		$imgHeight = imagesy($img);
		
		$height++;
		$hRes = $imgHeight / $height;
		$wRes = $imgWidth / $hRes;
		
		$newImg = imagecreatetruecolor(($wRes-1), ($height-1));
		imagecopyresampled($newImg, $img, 0,0,0,0, $wRes, $height, $imgWidth, $imgHeight);
		imagedestroy($img);
		
		imagepng($newImg, $outputFile);
		imagedestroy($newImg);
	}
	
	private function getBigPhoto($src)
	{
		$tmp = explode('_', $src);
		$tmp[count($tmp) - 1] = 'b.jpg';
		return implode('_', $tmp);
	}
	
	private function log($msg, $status = 'ok')
	{
		$log = "Log: " . date('Y-m-d h:i:s') . ", total:{$this->totalItems}, errors:{$this->totalErrors} - $status :\t" . $msg;
		$logFile = $this->getRootPath() . '/protected/runtime/parser.log';
		
		file_put_contents($logFile, "$log\n", FILE_APPEND);
		$this->show($log);
		
	}
	
	private function getRootPath()
	{
		if($this->isConsole)
			return realpath('.');
		
		else
			return $_SERVER['DOCUMENT_ROOT'];
	}
}