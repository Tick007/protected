<?php

Yii::import('ext.SParser.*');
class HobbykingParser extends SParserController
{
	public $inputCharset = 'utf-8';
	public $outputCharset = 'utf-8';
	public $baseUrl = 'http://www.hobbyking.com/hobbyking_api.asp?id=';
	//&switch=1
	public $hobbySwitchs = array(
		'1'=>'kolich',
		'3'=>'price',
		//'4'=>'price', - Название
	);
	public $headers = array(
		'User-Agent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.11 (KHTML, like Gecko) Chrome/23.0.1271.64 Safari/537.11',
		'Accept-Language' => 'ru-RU,ru;q=0.8,en-US;q=0.6,en;q=0.4',
	);
	public $pauseSeconds = 1;
	
	const MAIN_PARENT_CATEGORY_ID = 0;
	const ARTICLE_PREFIX = '';
	const CONTENT_PAGE = 1;
	const EMPTY_PAGE = 2;
	
	
	
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
	
public $proxies = 	array(
		'151.80.225.105:8080',// good
		'65.255.32.15:8080', //good
		'176.31.175.43:80', //good
		'122.226.21.196:8080',//good
		'208.76.196.84:80', //good
		'128.199.147.170:8888',	//good
		'212.47.235.33:3129',//good
		'125.140.118.12:3128', //good
		'192.99.3.129:3128',//good
		'5.129.231.10:3130', //good
		'95.0.218.10:8080',//good
		'217.9.195.227:3128',//good
		'2.222.45.88:8888',//good
		'103.10.22.242:3128',//good
		'202.29.97.2:3128',//good
		'111.161.126.101:80',//good
		'52.20.229.27:3128',//good
		'104.28.23.190:80',//good
		'141.101.127.102:80',//good
		'199.27.134.216:80',
		'141.101.116.129:80',
		'61.234.249.107:8118',
		'202.77.57.63:3128',
		'177.234.0.110:3130',
		'212.47.233.46:3129',
		'178.57.217.150:11897',
		'5.189.184.3:3128',
		'80.95.113.66:3128',
		'199.27.135.76:80',
		'80.95.113.70:3128',
		'162.208.49.45:3127',
		'5.129.231.10:3128',
		'103.251.43.53:80',
		'118.143.207.1:8080',
		'46.105.39.156:3128',
 );
	
	
	function getContent($url, $referer = null, $proxies = array(null))
	{
		$proxies = (array) $proxies;
		$steps = count($proxies);
		$tried_proxies=array();
		$step = 0;
		$try = true;
		$proxynum = count($proxies);
		
		//exit();
		
		while($try){
			
			if($proxynum>0){
			$rnd_proxy_num = rand(0, ($proxynum-1));
			if(in_array($rnd_proxy_num, $tried_proxies)==false) $tried_proxies[]=$rnd_proxy_num;
			else continue;
		}
			
			// create curl resource
			$ch = curl_init();
			//$proxy = isset($proxies[$step]) ? $proxies[$step] : null;
			//$proxy = isset($proxies[$tried_proxies[count($tried_proxies)-1]]) ? $proxies[$step] : null;
			$proxy =(isset($tried_proxies) )? $proxies[count($tried_proxies)-1] : null;
			//echo $proxy.'<br>';
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_REFERER, $referer);
			curl_setopt($ch, CURLOPT_USERAGENT, "Opera/9.80 (Windows NT 5.1; U; ru) Presto/2.9.168 Version/11.51");
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_PROXY, $proxy);
			curl_setopt($ch, CURLOPT_TIMEOUT, 2);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //return the transfer as a string
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	
			$output = curl_exec($ch); // get content
			$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE); // �������� HTTP-���
	
			// close curl resource to free up system resources
			curl_close($ch);
	
			$step++;
			$try = (($step < $steps) && ($http_code != 200));
		}
		if(isset($proxies[$step])) {
			$message =  'Подключено через: '.$proxies[$step].', время: '.date('d.m.Y H:i:s');
			$err=false;
		}
		else {
			//echo '$http_code = '.$http_code.'<br>';
			//print_r($ch);
			$message =  'Подключено напрямую, время: '.date('d.m.Y H:i:s');
			$err=true;
		}
		return array('output'=>$output, 'message'=>$message, 'err'=>$err) ;
	}
	
	
	public function commandItemId($id){
		

		
		$result = array('cur'=>'USD');
		//$parser = $this->getParser();
		
		//var_dump($parser);
		//exit();
		
		//$parser = $this->getParser();
		foreach($this->hobbySwitchs as $sw=>$key){
			$url = $this->baseUrl.$id.'&switch='.$sw;
			//echo $url.'<br>';
			//$qqq = file_get_contents($url,0);
			//$parser->sendGet($url);
			//$qqq = $parser->getContents();
			
			
			$res= $this->getContent($url, null, $this->proxies);
			$qqq = $res['output'];
			
			//echo $res['err'];
			//exit();
			
			$qqq = str_replace("\r\n",'',$qqq);
			$qqq = str_replace("\n",'',$qqq);
			//var_dump($qqq);
			//exit();
			//echo '<br>';
			$pat = '/(<body>)(\d+(?:\.\d+)?)/';
		preg_match_all($pat,$qqq ,$matches);
			//print_r($matches);
		if(isset($matches) && isset($matches[2]) && isset($matches[2][0])) 	{
			//var_dump($matches[2][0]);
			//echo '<br>';
			$result[$key] = trim($matches[2][0]);
		}
		}

		return array('result' => $result);
		
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