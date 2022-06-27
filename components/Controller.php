<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	//public $layout='//layouts/column1';
	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu=array();
	var $pageDescription;
	var $pageKeywords;
	var $pageTitle;
	var $leftpanel;
	var $rightpanel;
	var $extraCSS;
	var $meta_mo_index;
	var $js_url;
	public $browser;
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs=array();
	
	public function filterCheckPathSuffix($filterChain){/////////////
				//echo '<br>ewrwer';
					$request = Yii::app()->getRequest();
					$url = $request->getUrl();
					//echo $url.'<br>';
					$last= mb_substr($url, -1, 1);
					if ($last=='/') {
							$this->redirect(mb_substr($url, 0, mb_strlen($url)-1), true, 301);
							exit();
					}
			$filterChain->run();
	}/////////public function filterCheckPathSuffix($filterChain){/////////////
	
	public function filterCheck_product_existance($filterChain)	{////
		$id = Yii::app()->getRequest()->getParam('id', null);
	
		if($id!=null){
			$product = Products::model()->findByPk($id);
			if(isset($product) && $product!=null){
				if($product->product_visible!=1){
					throw new CHttpException(404,'Товар отключен');
					//exit();
				}
				elseif(isset($this->CAT) && $product->category_belong != $this->CAT->category_id){
					throw new CHttpException(404,'Группа товара не соответствует');
				}
				else $this->PROD = $product;
			}
			else {
				throw new CHttpException(404,'Товар не найден');
				//exit();
			}
		}
		$filterChain->run();
	}
	

	
	
	/*смторим есть ли для текущей вьюхи отдельный javascript файл и если есть, то включаем сразу в подгружаемые скрипты*/
	public function filterHasJsFile($filterChain){
	    $js_url = Yii::app()->theme->baseUrl.'/js/views/'.$this->id.'/'.$this->action->id.'.js';
	    $js_fname = $_SERVER['DOCUMENT_ROOT'].$js_url;
	    //echo $js_fname;
	    if(file_exists($js_fname) && is_file($js_fname)) {
	        $this->js_url =  $js_url;
	        //echo $js_url;
	        //exit();
	        $clientScript = Yii::app()->clientScript;
	        $clientScript->registerScriptFile(Yii::app()->request->baseUrl . $js_url.'?v='.rand(), CClientScript::POS_HEAD);
	    }
	    //else $this->js_url = null;
	    $filterChain->run();
	}
	
	public function filterUserLastUrl($filterChain) { ////////////��������� ������ �� ��������� �������� ��� ��� ������������
			$request = Yii::app()->getRequest();
			$url = $request->getUrl();
			
			//print_r(Yii::app()->user);
			//exit();
			Yii::app()->user->setReturnUrl($url);
			$filterChain->run();
	}/////////ublic function filterUserLastUrl($filterChain) { ////////
	
	public function filterReadKladr($filterChain){ ////////////������ ������� ������� �����
			$cookie=Yii::app()->request->cookies['region'];
				//print_r($cookie);	
					if (isset($cookie) AND trim($cookie->value)<>'') {
							$this->region=$cookie->value;
							$this->global_kladr = Ma_kladr::model()->findByPk($this->region);		
							//echo $this->region_word;
					}///////if (isset($cookie) AND trim($cookie->value)<>'') {
					else 	if(isset(Yii::app()->params['default_kladr_id'])) {
						$this->global_kladr = Ma_kladr::model()->findByPk(Yii::app()->params['default_kladr_id']);	
					}/////////////
			$filterChain->run();		
	}
	
	public function filterSetTheme($filterChain){
	
		$config_theme = Yii::app()->theme->name;
		
		if(isset($this->browser['platform']) && isset(Yii::app()->params['mobile_theme'])){
			$check_for_apple_android_nok = $this->browser['platform']=='Nokia' || $this->browser['platform']=='Android'||  $this->browser['platform']=='iPhone' || $this->browser['platform']=='iPad';
			$check_for_win_phone = $this->browser['platform']=='Windows'  && preg_match('/NOKIA/', $this->browser['agent_details']);
			$chek_for_rim = $this->browser['platform']=='unknown' && preg_match('/BB/', $this->browser['agent_details']);
				
			/*
				var_dump($check_for_apple_android_nok);
				var_dump($check_for_win_phone);
				echo '<pre>';
				print_r($this->browser);
				print_r($this->browser['agent_details']);
				echo '</pre>';
				exit();
			*/
				
				
			if($check_for_apple_android_nok || $check_for_win_phone || $chek_for_rim){
	
				Yii::app()->theme = Yii::app()->params['mobile_theme'];
			}
			
			
			
			
			///////////////// Принудительное переключение темы
			
			////////////////// Получаем флаг
			$forcetheme= htmlspecialchars(trim(Yii::app()->getRequest()->getParam('forcetheme', NULL)));
			////////////////// смотрим в сесии
			$cookie =  Yii::app()->request->cookies['ForceTheme'];
			///////////////Принудительный флаг на переключение
			if($forcetheme!=null ){
				if($forcetheme == 'desktop') {
					$theme =  $config_theme;
				}
				elseif($forcetheme == 'mobile') {
					$theme  = Yii::app()->params['mobile_theme'];
				}
			}
			////////////////Иначе если нет принудителоьного, но есть куки смотрим в куках
			elseif($cookie!=null && isset($cookie->value) ){
				if($cookie->value=='mobile') $theme = Yii::app()->params['mobile_theme'];
				if($cookie->value=='desktop') $theme = $config_theme;
			}
			
			
			if(isset($theme) && $theme!=NULL) Yii::app()->theme = $theme;
			
			if($forcetheme!=null){
				$cookie =new CHttpCookie('ForceTheme', $forcetheme); // sends a cookie
				$cookie->expire= 0;
				Yii::app()->request->cookies['ForceTheme']=$cookie;
			}
			
			
			////////////////////////////////// И подконец редиректим на главную страницу, что бы при закрытии броузера
			////////////////////////////////// не оставалась строка  ?forcetheme=sdfsdlfkl
			if($forcetheme!=null ){
				//Yii::app()->redirect('site/index');
				Yii::app()->getController()->redirect(array('/site/index'));
			}
			
			
		}
		
		
	
		
		$filterChain->run();
	}
	
	public function filterCheckBrouser($filterChain){
		//Yii::import('application.extensions.mbmenu.Browser');
		//$this->Browser= Browser::detect();
		
		
		
		
		$this->browser['Brouser'] = new Browser2();
		$this->browser['agent']=$this->browser['Brouser']->getBrowser();
		$this->browser['platform']=$this->browser['Brouser']->getPlatform();
		$this->browser['isMobile']=(bool)$this->browser['Brouser']->isMobile();
		$this->browser['agent_details'] =  $this->browser['Brouser']->_agent;
		
		$agent_has_edge = strpos(strtolower($this->browser['Brouser']->_agent), 'edge')==true;
		$agent_has_ie = strpos(strtolower($this->browser['Brouser']->_agent), 'msie')==true;
		$agent_has_webkit = strpos(strtolower($this->browser['Brouser']->_agent), 'webkit')==true;
		
		if($agent_has_webkit)  {
			$this->browser['is_webkit']=true;
			$this->browser['is_microsoft']=false;
		}
		else  {
			$this->browser['is_webkit']=false;
			$this->browser['is_microsoft']=true;
		}
		
		if($agent_has_edge || $agent_has_ie)  $this->browser['is_microsoft']=true;
		
		
		
	
	
		/*
		 echo '<pre>';
		 print_r($this->browser);
		 echo '</pre>';
		 echo 'Browser = '.$this->browser['Brouser']->getBrowser().'<br>';
		 echo 'Platform = '.$this->browser['Brouser']->getPlatform().'<br>isMobile = ';
		 var_dump($this->browser['Brouser']->isMobile()).'<br>';
		 echo $this->browser['Brouser']->_agent;
		 exit();
		 */
			
		/*/////// Note II дефолтный, S 3, Note 3
		 Browser = Android
		 Platform = Android
		 isMobile = bool(true)
	
		 //////////////////////////Nexus 10
		 Browser = Chrome
		 Platform = Android
		 isMobile = bool(false)
	
		 /////////////////ipad
		 Browser = iPad
		 Platform = iPad
		 isMobile = bool(true)
	
		 ////////////////////////Iphone 6
		 Browser = iPhone
		 Platform = iPhone
		 isMobile = bool(true)
	
	
		 /////////////////S4
		 Browser = Chrome
		 Platform = Android
		 isMobile = bool(false)
	
		 ///////////////PC
		 Browser = Chrome / Firefox / Safari /(Опера тоже Chrome)/ Internet Explorer
		 Platform = Windows
		 isMobile = bool(false)
	
		 /////////////Nokia
	
		 */
			
		$filterChain->run();
	}
	
	public function  filterCheckAuthority($filterChain)	{//////////Проверка, был ли передан файл
	    //echo var_dump(Yii::app()->user->checkAccess('Правка товаров'));
	    if (Yii::app()->user->checkAccess('Правка товаров') ) $filterChain->run();
	    else {
	        throw new CHttpException(401,'У вас нет прав');
	    }
	}
	
	
	

	
	
	 public function Add_To_Cart( ) {
		 
		 //print_r($_GET);
		 //print_r($_POST);
		// exit();
		 
		 
		$add_to_basket = Yii::app()->getRequest()->getParam('add_to_basket');///
		$num_to_basket = Yii::app()->getRequest()->getParam('num_to_basket', 1);//
		 
		 
	 		if (isset($add_to_basket) AND is_numeric($add_to_basket)) {//////////////////���������� � �������
			
					$tovar_id=intval(trim($add_to_basket));
					$MyBasket = new MyShoppingCart($tovar_id, $num_to_basket  );
			}
			
			$cookie=Yii::app()->request->cookies['YiiCart'];
			if (isset($cookie)){
			 $value=$cookie->value;
				//echo "������ ������������� ���� ".$value."/<br>";
	 		}
			
			//return $MyBasket ;
			//else echo "��� ����<br>";
		}
		
		public function actions()
		{
			return array(
				// captcha action renders the CAPTCHA image displayed on the contact page
				'captcha'=>array(
					'class'=>'CCaptchaAction',
					'backColor'=>0xFFFFFF, //���� ���� �����
					'testLimit'=>2, //������� ��� ����� �� ��������
					'transparent'=>false,
					'foreColor'=>0x7a7a7a, //���� ��������
				//	'width'=>'150px',
				 	'height'=>'50px',
				),
			);
		}
		
		
		
		public function CalculateCartContents(){ //////������� ���������� �������
				 $cook=Yii::app()->request->cookies['YiiCart'];
				if (isset($cook)) {
				$cookie = $cook->value;
					if (isset($cookie)) {/////////////////////////////
					//print_r($cookie);
							$goods=explode('#', $cookie);
							foreach($goods as $product) {
								$sum_num=explode(":", $product);
								if(isset($sum_num[0]) AND isset($sum_num[1])) {
									$products_arr['ids'][]=$sum_num[0];
									$products_arr['num'][$sum_num[0]]=$sum_num[1];
								}
								
								//print_r($sum_num);
							}
					//print_r($products_arr);	
					
					if (isset($products_arr['ids'])) {
							$criteria=new CDbCriteria;
							//$criteria->order = 't.sort_category';
							//print_r($products_arr['ids']);
							$criteria->condition = " t.id IN (".implode(',', $products_arr['ids']).")";
							//$criteria->params=array(':root'=>Yii::app()->params['main_tree_root']);
							$models = Products::model()->findAll($criteria);//
							if (isset($models)) {
									$sum=0;
									$num_of_products=0;
									for($k=0; $k<count($models); $k++) {
										//echo $models[$k]->id;
										$sum=$sum+round($models[$k]->product_price*$products_arr['num'][$models[$k]->id], 2);
										$num_of_products = $num_of_products+$products_arr['num'][$models[$k]->id];
									}
									//echo $sum;
									return array($sum, $num_of_products);
							}/////if (isset($models)) {
					}//////if (isset($products_arr['ids'])) {
							
					}/////if (isset($cookie)) {/////////////////
					
					
				}/////////if (isset($cook)) {
		}//////public function CalculateCartContents(){
	
		
		
	
		public function filterEmptyFilter($filterChain){
			$filterChain->run();
		}
		
		protected function getProductImages($product_id){
			$criteria=new CDbCriteria;
			$criteria->condition = " t.product = :product_id";
			$criteria->params= array(':product_id'=>$product_id);
			$picture_products = Picture_product::model()->findAll($criteria);
			if($picture_products!=null){
				foreach($picture_products as $picture){
					//print_r($picture->attributes);
					//echo '<br>';
					if($picture->is_main==1) $images['main']=$picture->attributes;
					else $images['not_main'][]=$picture->attributes;
					//$images['all'][]=$picture->attributes;
				}
			}
			$picture_products = NULL;
			if(isset($images)) return $images;
			else return null;
		}
		
		
		/**
		 * Смотрим запрошенный урл (не совсем урл, котрол + экшн + идентификатор) для определения активного пункта меню
		 */
		public function getPageUrl(){
		/////////////
                	$alais = Yii::app()->getRequest()->getParam('id', NULL);
                	$view = Yii::app()->getRequest()->getParam('view', NULL);
                	$pageurl = $this->id.'/';
					$pageurl.= $this->action->id;
					if($this->action->id=='byalias'){
						$pageurl.='/'.$alais ;
					}
					if($this->action->id=='page'){
						$pageurl.='/'.$view ;
					}
					return $pageurl;
					
		}
		
		/** метод для ыформирования подписи к каждому заказу
		 * @param int $order_id  идентификатор заказа
		 * @param float $sum сумма по заказу
		 * @return string хеш сумма md5
		 */
		protected function calculate_crc($order_id, $sum ){
		    $crc = null;
		    $crc_string = Yii::app()->params['inetpayment']['login'].':';
		    $crc_string.= $sum.':';
		    $crc_string.= $order_id.':';
		    $crc_string.= Yii::app()->params['inetpayment']['payment_pass'].':1';
		    //$this->crc  = md5($this->mrh_login.':'.$this->out_summ.':'.$this->inv_id.':'.$this->mrh_pass1.':Shp_item='.$this->shp_item);/////////Для их формы
		    $crc = md5($crc_string);
		    return $crc;
		}
		
		/*
		 * Заполняем корзину куками из БД
		 * */
		public static function getCokieProducts(){
		    $sc = new MyShoppingCart2();
		    $products_arr = $sc->getOrder(); ////////////тут уже не id товаров, а id таблицы pricevariations
		    
		    //print_r($products_arr);
		    
		    if (isset($products_arr)) {
		        /*
		         $criteria=new CDbCriteria;
		         $criteria->select=array(
		         't.*',
		         'CONCAT_WS(".", picture_product.picture, picture_product.ext) AS icon',
		         'picture_product.picture AS icon_id',
		         'picture_product.comments AS attribute_value',
		         
		         );
		         
		         $criteria->condition = " t.id IN (".implode(',', array_keys($products_arr)).")";
		         $criteria->join =" RIGHT JOIN ( SELECT picture_product.id, product, picture, ext,  pictures.comments FROM picture_product JOIN pictures ON
		         pictures.id=picture_product.picture WHERE picture_product.is_main=1) picture_product ON picture_product.product = t.product   ";
		         $price_variations = PriceVariations::model()->findAll($criteria);
		         */
		        
		        ///////////Какого то хера, критерия не возвращает ничего
		        $connection = Yii::app()->db;
		        $query = 'SELECT price_variations.*,
         CONCAT_WS( ".", picture_product.picture, picture_product.ext ) AS icon,
        picture_product.picture AS icon_id,
        picture_product.comments AS attribute_value,
        productcats.product_name,
        productcats.product_id,
        productcats.category_belong,
        productcats.alias,
        productcats.measure
FROM price_variations
LEFT JOIN (SELECT picture_product.id, product, picture, ext, pictures.comments FROM picture_product JOIN pictures
ON pictures.id = picture_product.picture WHERE picture_product.is_main =1)picture_product ON picture_product.product = price_variations.product
LEFT JOIN (SELECT measures.measure, products.id AS product_id, products.product_name AS product_name, products.category_belong, categories.alias FROM products JOIN categories
                    ON products.category_belong=categories.category_id
            LEFT JOIN measures ON measures.id = products.measure
) productcats ON productcats.product_id = price_variations.product
WHERE price_variations.id IN ( '.implode(',', array_keys($products_arr)).' )';
		        
		        
		        $command=$connection->createCommand($query)	;
		        $dataReader=$command->query();
		        $price_variations=$dataReader->readAll();////
		        
		        
		        //print_r($price_variations);
		        //exit();
		        
		        if (isset($price_variations) AND empty($price_variations)==false) {
		            //$discount = FHtml::getClientDiscount();
		            //print_r($discount);
		            
		            foreach ($price_variations as $price_var){
		                
		                $models[$price_var['id']]=array(
		                    'name'=>$price_var['product_name'],
		                    'product_id'=>$price_var['product_id'],
		                    'category'=>$price_var['category_belong'],
		                    'category_alias'=>$price_var['alias'],
		                    'icon'=>$price_var['icon'],
		                    'icon_id'=>$price_var['icon_id'],
		                    //'price'=>FHtml::getProductPrice($product),
		                    'price'=>$price_var['price'],
		                    'code'=>$price_var['code'],
		                    'num'=>$products_arr[$price_var['id']],
		                    'volume'=>$price_var['volume'].' '.$price_var['measure'],
		                    
		                    
		                );
		                
		                /////////////Сразу смотрим какие есть картинки
		                $pict_new_src = '/pictures/add/icons/'.$price_var['icon'];
		                
		                if(is_file($_SERVER['DOCUMENT_ROOT'].$pict_new_src) && file_exists($_SERVER['DOCUMENT_ROOT'].$pict_new_src)) {
		                    $models[$price_var['id']]['img_src'] = $pict_new_src;
		                }
		                
		                else $models[$price_var['id']]['img_src'] = '/images/nophoto_200.png';
		            }
		            
		            
		            $price_variations = null;
		        }/////////if (isset($products)) {
		        if(isset($models)) return $models;
		    }//////if (isset($products_arr['ids'])) {
		    //}
		    
		    return null;
		}
		
		
		public static function getHistoryProducts($hist, $current_prod=null){
		    $history = $hist->getHistory();
		    
		    $products_arr = array();
		    //print_r($products_arr);
		    
		    if (is_array($history) && empty($history)==false) {
		        $criteria=new CDbCriteria;
		        $criteria->select=array( 't.*',  'CONCAT_WS(".", picture_product.picture, picture_product.ext) AS icon',
		            'picture_product.picture AS icon_id' , 'picture_product.comments AS attribute_value');
		        $criteria->condition = " t.id IN (".implode(',', $history).")";
		        if($current_prod!=null)  $criteria->condition.= " AND t.id !=".$current_prod;
		        
		        $criteria->join =" LEFT JOIN ( SELECT picture_product.id, product, picture, ext,  pictures.comments FROM picture_product JOIN pictures ON
		pictures.id=picture_product.picture WHERE picture_product.is_main=1) picture_product ON picture_product.product = t.id  ";
		        $products = Products::model()->with('belong_category')->findAll($criteria);
		        
		        if (isset($products) AND empty($products)==false) {
		            foreach ($products as $product){
		                $models[$product->id]=array(
		                    'name'=>$product->product_name,
		                    
		                    'category'=>$product->category_belong,
		                    'category_alias'=>$product->belong_category->alias,
		                    'icon'=>$product->icon,
		                    'icon_id'=>$product->icon_id,
		                    'price'=>$product->product_price,
		                    'price_old'=>$product->product_price_old,
		                    'product_sellout'=>$product->product_sellout,
		                    'sellout_price'=>$product->sellout_price,
		                    'new_product'=>$product->new_product,
		                    //'num'=>$products_arr[$product->id],
		                    
		                );
		                
		                ////Дообрабатываем цены
		                if($models[$product->id]['product_sellout']==1 && $models[$product->id]['sellout_price']>0) {
		                    $models[$product->id]['price_old'] =$models[$product->id]['price'];
		                    $models[$product->id]['price']=$models[$product->id]['sellout_price'];
		                    
		                }
		                
		                /////////////Сразу смотрим какие есть картинки
		                $pict_new_src = '/pictures/add/icons/'.$product->icon_id.'.png';
		                $pict_old_src = '/pictures/img_med/'.$product->id.'.png';
		                
		                if(is_file($_SERVER['DOCUMENT_ROOT'].$pict_new_src) && file_exists($_SERVER['DOCUMENT_ROOT'].$pict_new_src)) {
		                    $models[$product->id]['img_src'] = $pict_new_src;
		                }
		                elseif(is_file($_SERVER['DOCUMENT_ROOT'].$pict_old_src) && file_exists($_SERVER['DOCUMENT_ROOT'].$pict_old_src)) {
		                    $models[$product->id]['img_src'] = $pict_old_src;
		                }
		                else $models[$product->id]['img_src'] = '/images/nophoto_200.png';
		            }
		            
		            $products = null;
		        }/////////if (isset($products)) {
		        if(isset($models)) return $models;
		    }//////if (isset($products_arr['ids'])) {
		    //}
		    
		    return null;
		}
		
		public function setFlashC($key, $texttostore){
		    //echo $key.'<br>'.$texttostore;
		    $cookie = new CHttpCookie ($key, $texttostore ); // sends a cookie
		    $cookie->expire = time () + 60; // /////////60 сек
		    Yii::app ()->request->cookies [$key] = $cookie;
		}
		
		public function hasFlashC($key){
		    //echo $key.'<br>'.$texttostore;
		    $cookie = Yii::app ()->request->cookies [$key];
		    // print_r($cookie);
		    if (empty ( $cookie ) == true) return false;
		    else{
		        if(trim($cookie->value)!='') return true;
		        else{
		            $cookie->value = NULL;
		            return false;
		        }
		    }
		}
		
		public function getFlashC($key, $delete=false) {
		    $cookie = Yii::app ()->request->cookies [$key];
		    // print_r($cookie);
		    if (empty ( $cookie ) == true) return NULL;
		    else{
		        $val = $cookie->value;
		        if($delete==true){
		            $cookies = new CHttpCookie ( $key, NULL );
		            $cookies->value = NULL;
		            Yii::app ()->request->cookies [$key] = $cookies;
		            
		        }
		        return $val;
		    }
		}
		
		/*
		 * Метод специально для химиков. Проверяет является ли человек верифицированым и из германии ли он
		 * */
		public function chemiCheckApprovedVat($debug=false, $ip=null){
		    if($ip == null) $o['ip'] = $_SERVER['REMOTE_ADDR'];
		    else $o['ip']= $ip;
            
		    //$o['ip'] = '176.59.46.100'; //RU
		    //$o['ip'] = '46.113.37.143'; //PL
		    //$o['ip'] = '77.245.38.202'; //DE
		    
		    if($debug) {
    		    echo 'IP пользователя: ';
    		    print_r($o['ip']);
    		    echo '<br>';
		    }
		    $geo = new Geo($o); // запускаем класс
		    // Если хотите передать в функцию уже известный IP, то можно сделать так
		    // $o['ip'] = '178.204.102.30'; <-- Пример IP адреса г. Казань
		    // $geo = new Geo($o);
		    // этот метод позволяет получить все данные по ip в виде массива.
		    // массив имеет ключи 'inetnum', 'country', 'city', 'region', 'district', 'lat', 'lng'
		    
		    
		    try {
		        $ipdata = $geo->get_value();
		    } catch (Exception $e) {
		        print_r($e);
		    }
		    
		    
		    if($debug) {
    		    echo '<pre>';
    		    print_r($ipdata);
    		    echo '</pre>';
		    }
		    $cond =   isset($ipdata) && isset($ipdata['country']) && $ipdata['country']=='DE' && (Yii::app()->user->isGuest==false && Yii::app()->user->approved==1);
		    return $cond;
		}
		
		
}