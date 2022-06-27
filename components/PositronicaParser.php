<?php

Yii::import('ext.SParser.*');
class PositronicaParser extends SParserController
{
	public $inputCharset = 'windows-1251';
	public $outputCharset = 'utf-8';
	public $baseUrl = 'http://anapa.positronica.ru';
	public $headers = array(
		'User-Agent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.11 (KHTML, like Gecko) Chrome/23.0.1271.64 Safari/537.11',
		'Accept-Language' => 'ru-RU,ru;q=0.8,en-US;q=0.6,en;q=0.4',
	);
	public $pauseSeconds = 1;
	
	const MAIN_PARENT_CATEGORY_ID = 2156;
	const ARTICLE_PREFIX = 'sp-';
	const CONTENT_PAGE = 1;
	const EMPTY_PAGE = 2;
	
	public $totalItems = 0;
	public $totalErrors = 0;
	
	public function commandItem($url, $ignoreErrors = false)
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
		
		// Название
		$tmp1 = $parser->parse('%<h1.*?>(.*?)</h1>%is');
		if(!isset($tmp1[1]) || !$result['productName'] = $this->clean($tmp1[1]))
			return $this->createParseError('Невозможно извлечь название.', $url);
		
		// Категория
		if($ItemB = $parser->parseInnerTag('div', array('id' => 'ItemB')))
		{
			$tmp2 = $parser->parseInnerTag('a', array(), $ItemB);
			if(!$result['categoryName'] = $this->clean($tmp2))
				return $this->createParseError('Невозможно извлечь категорию.', $url);
		}
		else return $this->createParseError('Невозможно извлечь категорию.', $url);
		
		// Родительская категория
		$tmp3 = $parser->parse('%<div.*?>(.*?)>%is', $ItemB);
		if(!isset($tmp3[1]) || !$result['parentCategoryName'] = $this->clean($tmp3[1]))
			return $this->createParseError('Невозможно извлечь родительскую категорию.', $url);
		
		// Характеристики
		$result['characteristics'] = array();
		$tmp8 = $parser->parse('%<div[^\n.]*?ItemR-property[^\n.]*?>(.*?)</div>%is');
		if(!empty($tmp8) && isset($tmp8[1]))
		{
			$tmp4 = $parser->parse('%<div[^\n.]*?id=\'RubricView\'[^\n.]*?>(.*?)</table>.*?</div>%is');
			if(isset($tmp4[1]))
			{
				$tmp5 = $parser->parseAll('%<tr.*?>(.*?)</tr>%is', $tmp4[1]);
				if(isset($tmp5[1]) && !empty($tmp5[1]))
				{
					foreach($tmp5[1] as $rItem)
					{
						$tmp6 = $parser->parse('%<td.*?>(.*?)</td>.*?<td.*?>(.*?)</td>%is', $rItem);
						if(isset($tmp6[1]) && isset($tmp6[2]))
						{
							$prop = preg_replace(array(
								'%<span.*?</span>%is',
								'%<div.*?</div>%is',
							), '', $tmp6[1]);
							$result['characteristics'][$this->hardClean($prop)] = trim($tmp6[2]);
						}
					}
				}
			}
		}
		
		// Цена
		$result['price'] = null;
		if($result['price'] = $parser->parseInnerTag('span', array('class' => 'price')))
			$result['price'] = str_replace(' ', '', $this->clean($result['price']));
		
		// Артикул
		$result['articul'] = null;
		if($tmp10 = $parser->parse('%<div.*?class="silv small".*?>.*?(\d+).*?</div>%is'));
			if(isset($tmp10[1]))
				$result['articul'] = $this->clean($tmp10[1]);
		
		// Главное фото
		$tmp9 = $parser->parseTagAttribute('img', 'src', array('id' => 'ItemPhoto'));
		// Если фото не является заглушкой
		if($tmp9 && strpos($tmp9, '.png') === false)
		{
			$result['mainPhoto'] = $tmp9;
			
			// Остальные фото
			$tmp7 = $parser->parseAll('%<div.*?class="phim".*?<img.*?src="(.*?)".*?>.*?</div>%is');
			$result['photos'] = isset($tmp7[1]) ? $tmp7[1] : array();
		}
		
		//$this->dump($result);
		
		// Загрузка данных
		$parentCategoryId = $this->handleCategory($result['parentCategoryName'], self::MAIN_PARENT_CATEGORY_ID);
		$categoryId = $this->handleCategory($result['categoryName'], $parentCategoryId);
		$productId = $this->handleProduct($result['productName'], $categoryId, $result['price'], $result['articul']);
		if(!empty($result['characteristics']))
		{
			foreach($result['characteristics'] as $charName => $charVal)
				$this->handleCharacteristics($charName, $charVal, $categoryId, $productId);
		}
		if(isset($result['photos']) && !empty($result['photos']))
		{
			$result['createdPhotos'] = $this->handlePhotos($result['mainPhoto'], $result['photos'], $productId, $result['productName']);
		}
		elseif(isset($result['mainPhoto']))
		{
			$result['createdPhotos'] = $this->handlePhotos($result['mainPhoto'], array($result['mainPhoto']), $productId, $result['productName']);
		}
		
		$this->setParserRecord($url, $productId, ParserModel::STATUS_SUCCESS);
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
		$this->setParserRecord($url, $productId, ParserModel::STATUS_FAILURE);
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
			'parent' => $parentId,
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
		$createdPhotos = array();
		foreach($photos as $src)
		{
			$isMain = ($src == $this->getBigPhoto($mainPhoto));
			if(!$photoContent = file_get_contents($this->getBigPhoto($src)))
			{
				$isMain = ($src == $mainPhoto);
				if(!$photoContent = file_get_contents($src))
					continue;
			}
			
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
					$PhotoProduct->is_main = $isMain ? 1 : 0;
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
			$height = 100;
			$dir = 'icons';
		}
		elseif($type == 'small')
		{
			$height = 60;
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