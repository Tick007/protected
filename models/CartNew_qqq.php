<?
class CartNew extends CFormModel
{
	
	public $products;
	public $client_email;
	public $client_email_copy;	
	public $first_name;
	public $second_name;
	public $client_city;
	public $last_name;
	public $client_tels;
	public $client_post_index;
	public $payment;
	public $delivery;
	public $order_adress1;
	public $order_adress2;
	public $primechanie;
	public $quantity_pereschet; ///////////////Массив количест товара с формы
	public $characteristics_array;
	public $products_attributes;
	public $summa_pokupok = 0;
	public $orderCompeted = false;
	public $created_order = null;
	
	
	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			// username and password are required
			// password needs to be authenticated
			
			isset(Yii::app()->params['cart']['rules']['nomail'])?Yii::app()->params['cart']['rules']['nomail'] :array('client_email, first_name, client_city, client_tels,  delivery , order_adress1', 'required'),
			isset(Yii::app()->params['cart']['rules']['nomail'])?Yii::app()->params['cart']['rules']['mail'] :array('client_email, first_name, client_city, client_tels,  delivery , order_adress1, client_post_index', 'required', 'on'=>'mail'),
			//array('passcode2', 'compare', 'compareAttribute'=>'passcode'),
			//array('first_name, second_name', 'exist'), 
			array('client_email', 'email','message'=>"Ввидете правильный email"),
			array('client_email, client_email_copy',  'email'),
			array('client_email_copy', 'compare', 'compareAttribute'=>'client_email', 'message'=>'Не совпадают адреса электронной почты'),
			array('order_adress2, primechanie, payment', 'safe'),
			array('client_post_index, second_name, last_name', 'safe', 'on'=>'nomail')
		//	array('first_name, second_name, last_name, client_country', 'allowEmpty'=>true),
			//array('passcode', 'authenticate'),
		);
	}
	
	public function isRequired($attr_name){
		$scenar = self::getScenario();
		
		//echo $scenar.'|'.$attr_name.'<br>';

		
		$rules = $this->rules();
		foreach ($rules as $rule){

			if(isset($rule['on']) && $rule['on']==$scenar){
				$fields = explode(',', $rule[0]);
				/*
				echo '<pre>';
				print_r($fields);
				echo '</pre>';
				*/
				//var_dump($fields);
				//var_dump(in_array($attr_name, $fields));
				if(is_array($fields) && in_array($attr_name, $fields)) return(array('class'=>$rule[1]));
			}
		}
		
		
		
		//return(array('class'=>'required'));
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'last_name'=>'Отчество',
			'Отчество'=>'last_name',
			'client_post_index'=>'Почтовый индекс',
			'client_email'=>'Электронный адрес',
			'first_name'=>'Имя',
			'second_name'=>'Фамилия',
			'client_country'=>'Страна',
			'client_tels'=>'Телефон для связи',
			'client_city'=>'Выберите город из списка',
			'delivery'=>'Метод доставки',
			'order_adress1'=>'Адрес доставки',
			'order_adress2'=>'Если Вы не нашли подходящий
		   город, введите адрес доставки
   и мы предложим Вам
   варианты доставки',
				'client_email_copy'=>'Ещё раз email',
	   'primechanie'=>'Примечания к заказу',
	   'payment'=>'Способ оплаты'
		);
	}	
	
	
	public function updateCart(){///////////апдейтим кукисы в зависимомти от того что пришло с формы
		//print_r($this->quantity_pereschet);
			$cookie =  Yii::app()->request->cookies['YiiCart'];
			//print_r($cookie);
			if(empty($cookie)==false) {
				$qqq=explode("#",$cookie->value);
					for ($i=0; $i<count($qqq); $i++) {//////////////
						$qqq2=explode(":",$qqq[$i]);
						if(is_numeric($qqq2[0]) AND is_numeric($qqq2[1])){
							//echo '$qqq2[0] = '.$qqq2[0];
							if(isset($this->quantity_pereschet[$qqq2[0]]) AND $this->quantity_pereschet[$qqq2[0]]>0 ) { ////////////Только если есть на форме
								$id[]=$qqq2[0];
								$num[]=$this->quantity_pereschet[$qqq2[0]];
								$products[$qqq2[0]]=array('num'=>$this->quantity_pereschet[$qqq2[0]]);
							}
						}
					}
					
					if(isset($products)) {
						$this->updateCookies($products);
						return $products;
					}
					else $this->updateCookies();
			}
	}
	
	private function updateCookies($products=NULL){
		$new_basket=NULL;	
					/*if (isset ($this->product_ids_arr)) {
							for ($i=0; $i<count($this->product_ids_arr); $i++) {
									$new_basket.=$this->product_ids_arr[$i].':'.$this->product_num_arr[$i].'#';
							}
					}
					*/
					$new_basket = '';
					
					if($products!=NULL) foreach($products as $id=> $prod) {
						if($prod['num']>0 )$new_basket.= $id.':'.$prod['num'].'#';
					}
					//echo 'cook = '.$new_basket;
					if(trim($new_basket)=='')$new_basket = NULL;
					$cookie =new CHttpCookie('YiiCart', $new_basket); // sends a cookie
					$cookie->expire= time()+60*60*24*30; ///////////30 дней
					Yii::app()->request->cookies['YiiCart']=$cookie;
	}//////private function updateCookies($products){

	public function getProducts($product_id=NULL){
		
		
		
		//print_r($this->quantity_pereschet);
		if(empty($this->quantity_pereschet)==false AND is_array($this->quantity_pereschet)) {
			$this->products = $this->updateCart();
		}
		else {
			

			
				$cookie =  Yii::app()->request->cookies['YiiCart'];
				if(empty($cookie)==false) {
					$qqq=explode("#",$cookie->value);
						for ($i=0; $i<count($qqq); $i++) {//////////////
							$qqq2=explode(":",$qqq[$i]);
							if(is_numeric($qqq2[0]) AND is_numeric($qqq2[1])){
								$id[]=$qqq2[0];
								$num[]=$qqq2[1];
								$this->products[$qqq2[0]]=array('num'=>$qqq2[1]);
							}
						}
				}
			/*	
			$sc = new MyShoppingCart();
			$this->products = $sc->getOrder();
			*/
			}///if(empty($cookie)==false) {

			/*
		echo '<pre>';	
		print_r(array_keys($this->products));	
		echo '</pre>';
		*/
		

		
		if ($product_id != NULL ) {
			$this->products = array();
			$this->products[$product_id]=array('num'=>1);
		}


		if(is_array($this->products)==true AND empty($this->products)==false )	 {
			$criteria=new CDbCriteria;
			 $criteria->select=array( 't.*',  'CONCAT_WS(".", picture_product.picture, picture_product.ext) AS icon', 'picture_product.picture AS icon_id' , 'picture_product.comments AS attribute_value');
			  $criteria->join =" LEFT JOIN ( SELECT picture_product.id, product, picture, ext, pictures.comments FROM picture_product JOIN pictures
			  		ON pictures.id=picture_product.picture WHERE is_main=1) picture_product ON picture_product.product = t.id  ";
 $criteria->addCondition("t.product_visible = 1 AND picture_product.picture IS NOT NULL ");
			$criteria->order = ' t.id ';
			$criteria->condition = "t.product_visible=1 AND t.product_price>0 AND t.id IN (".implode(',',array_keys($this->products) ).")";
			$models = Products::model()->findAll($criteria);
			if(isset($models))  {
				for($i=0, $c=count($models); $i<$c; $i++) {
					$this->products[$models[$i]->id]['product']=$models[$i]->attributes;
					$this->products[$models[$i]->id]['product']['category_alias']=$models[$i]->belong_category->alias;
					$actual_price = FHtml::getProductPrice($models[$i]);
					$this->products[$models[$i]->id]['product']['product_price'] = $actual_price;
					$this->summa_pokupok+=round($actual_price*$this->products[$models[$i]->id]['num'],2);
					//echo $models[$i]->id.': '.$actual_price.'-'.$this->products[$models[$i]->id]['num'].' - '.$this->summa_pokupok.'<br>';
				}
				
						$char_neded = array(
						1, ////бренд
						3///////////материал
						);
				
						for ($i=0; $i<count($models); $i++) $productslist[]=$models[$i]->id;
						if (isset($productslist) AND is_array($productslist)==true AND empty($productslist)==false) {
						//print_r($productslist);
						$connection = Yii::app()->db;
						$query = "SELECT id_product,  value, id_caract   FROM characteristics_values  WHERE id_product IN (".implode(',', $productslist).") AND id_caract IN (".implode(',', $char_neded).")  ";////////////
						//echo $query;
						$command=$connection->createCommand($query)	;
						$dataReader=$command->query();
						$records=$dataReader->readAll();////
				
									if (isset($records)) {
												for($i=0; $i<count($records); $i++) $this->products_attributes[$records[$i]['id_product']][$records[$i]['id_caract']]=$records[$i]['value'];
														}
								
														//print_r($products_attributes);
									 }
										
										
									
							
							/////////////////////////////////////////Вытаскиваем все характеристики, доступные для этой группы
							$criteria=new CDbCriteria;
							$criteria->condition = "t.caract_id IN (".implode(',', $char_neded).")";
							//$criteria->order = "characteristics_categories.sort, t.char_type , t.caract_name ";
							$characteristics1 = Characteristics::model()->findAll($criteria);
							for ($i=0; $i<count($characteristics1); $i++) {
									$this->characteristics_array[$characteristics1[$i]->caract_id]=array('char_type'=>4,  'caract_name'=>$characteristics1[$i]->caract_name, 'is_main'=>$characteristics1[$i]->is_main);
							}//////////for ($i=0; $i<count($characteristics); $i++) {
							
				/*
				echo '<pre>';			
				print_r($this->characteristics_array);
				echo '</pre>';			
				echo '<pre>';			
				print_r($this->products_attributes);
			echo '</pre>';			
			*/
			}
			
			
		}
			
		
		
	}////public function getProducts($cookie){

	
	public function makeorderSimple(){////////////создание заказа
		//print_r($this->products);
		/*
		foreach($this->products as $prod) {
			echo '<pre>';
			print_r($prod);
			echo '</pre>';
		}
		*/
		
		
		$AR_Order = new Orders;
		$AR_Order->delivery = $this->delivery;
		$AR_Order->payment = $this->payment;
		//$AR_Order->client_city = $this->client_city;
		$AR_Order->recept_date = @date("Y-m-d");
		$AR_Order->recept_time = @date("H:i:s");
		$AR_Order->id_client =FHtml::checkClientByemail(trim($this->client_email), true, array('client_tels'=>$this->client_tels, 'first_name'=>$this->first_name,'client_city'=>$this->client_city));
		$AR_Order->primechanie = $this->primechanie;
		$AR_Order->order_adress1 =  $this->order_adress1;
		$AR_Order->order_adress2 = $this->order_adress2;
		//$AR_Order->reserv_sklad = $this->reserv_sklad;
		//$AR_Order->currency = Yii::app()->GP->GP_shop_currency;
		//$AR_Order->contragent_id = $AR_Client->urlico;
		//$AR_Order->currency_rate = Yii::app()->GP->get_currency_rate();
		$AR_Order->summa_pokupok = $this->summa_pokupok;
		
		
// 		echo '<pre>';
// 		print_r($AR_Order->attributes);
// 		echo '</pre>';
// 		exit();
		
		
		
		
		try {
			$AR_Order->save();
				
		} catch (Exception $e) {
			echo 'Ошибка сохранения  заказа: ',  $e->getMessage(), "\n";
		}/////
		$this->saveProducts($AR_Order->id, $AR_Order->delivery);
		$this->send_mail($AR_Order->id, $AR_Order->id_client);
		$this->products = null;
		$this->ClearBasket();
		$this->created_order = $AR_Order->id;
		$this->orderCompeted = true;
	}
	
	
	public function makeorder(){////////////создание заказа
		//print_r($this->products);
		$AR_Order = new Orders;
			$deliv_paym = $this->deliveryDetails();
			
		//	print_r($deliv_paym );
			
			
		
			
			$AR_Order->delivery = $this->delivery;
			$AR_Order->payment = $this->payment;
			
			$AR_Order->recept_date = @date("Y-m-d");
			$AR_Order->recept_time = @date("H:i:s");
			$AR_Order->id_client =FHtml::checkClientByemail(trim($this->client_email), true, array('client_tels'=>$this->client_tels, 'first_name'=>$this->first_name,'client_city'=>$deliv_paym[2]));
			//$AR_Order->payment = $this->payment_method;
			$AR_Order->payment_face = $deliv_paym[4]; ///////////////город кладр
			
			$AR_Order->primechanie = $this->primechanie;
			$AR_Order->order_adress1 =  $this->order_adress1;
			//$AR_Order->order_adress2 = $this->order_adress2;
			//$AR_Order->reserv_sklad = $this->reserv_sklad;
			//$AR_Order->currency = Yii::app()->GP->GP_shop_currency;
			//$AR_Order->contragent_id = $AR_Client->urlico;
			//$AR_Order->currency_rate = Yii::app()->GP->get_currency_rate();
			$AR_Order->summa_pokupok = $this->summa_pokupok;
				try {
					$AR_Order->save();
					
				} catch (Exception $e) {
					echo 'Ошибка сохранения  заказа: ',  $e->getMessage(), "\n";
				}/////
			$this->saveProducts($AR_Order->id);
			$this->send_mail($AR_Order->id, $AR_Order->id_client);
			$this->products = null;
			$this->ClearBasket();
			
	}


	private function send_mail($order_id, $client_id){///////////////Отправка сообщений
		
		$msg_body = "Заказ №".$order_id." от ".$this->first_name."(".$this->client_email.")<br>\r\n";
		$msg_body.="Телефон ".$this->client_tels."<br>\r\n";
		$msg_body.="Комментарий к заказу ".$this->primechanie."<br>\r\n";
		$PM = PaymentMethod::model()->findByPk($this->payment);
		$msg_body.= "Оплата ".$PM->payment_method_name."<br>\r\n";
		$msg_body.= $PM->message."<br>\r\n";
		$msg_body.="<table  border=\"0\" cellspacing=\"1\" cellpadding=\"1\" bgcolor=\"#ffffff\">";

		$sum = 0;
		$amount = -1; /////Один уходит на доставку
		foreach($this->products as $prod_id=>$prod) {/////////Перебор содержимого заказ
				$sum+=round($prod['num']*$prod['product']['product_price'],2);
				$amount+=$prod['num'];
				if(isset($prod['product']['category_alias'])) $msg_body.="<tr bgcolor=\"#FFFFFF\"><td>&nbsp;".CHtml::link($prod['product']['product_name'],
						array('catalog/info', 'id'=>$prod_id, 'alias'=>$prod['product']['category_alias'])).",&nbsp;&nbsp; http://".$_SERVER['HTTP_HOST'].Yii::app()->createUrl('catalog/info', array( 'id'=>$prod_id, 'alias'=>$prod['product']['category_alias']))."</td>";
				else $msg_body.="<tr bgcolor=\"#FFFFFF\"><td>&nbsp;".$prod['product']['product_name']."</td>";
				$msg_body.="<td>&nbsp;&nbsp;&nbsp;".$prod['num']." шт.</td>";
				$msg_body.="<td>&nbsp;&nbsp;&nbsp;".$prod['product']['product_price']."</td>";
				$msg_body.="</tr>";

		}////////////for ($i=0; $i<count($order); $i++) {
		$msg_body.="<tr bgcolor=\"#FFFFFF\">";
		$msg_body.="<td>Итого</td>";
		$msg_body.="<td>$amount</td>";
		$msg_body.="<td>$sum</td>";
		$msg_body.="</tr>";
			
		$msg_body.=" </table>";
		
		
		$headers = 'From: '.Yii::app()->params['infoEmail']. "\r\n" ;
		//$headers.='Content-type: text/html; charset=windows-1251' . "\r\n";
		$headers.='Content-type: text/html; charset=windows-1251' . "\r\n";
		//mail(Yii::app()->params['sendOrderEmail'], 'Ваш заказ на novline.com', $msg_body, $headers);
		//mail('info@autofides.ru', 'Ваш заказ на novline.com', $msg_body, $headers);
		@mail($this->client_email, iconv( "UTF-8", "CP1251",'Ваш заказ №'.$order_id.' на ').$_SERVER['HTTP_HOST'], iconv( "UTF-8", "CP1251",$msg_body), $headers);
		@mail(Yii::app()->params['infoEmail'],  iconv( "UTF-8", "CP1251", 'Сделан заказ №'.$order_id.' на ').$_SERVER['HTTP_HOST'],  iconv( "UTF-8", "CP1251",$msg_body), $headers);
		//mail('tick007@yandex.ru', 'Копия заказа на  '.$_SERVER['HTTP_HOST'], $msg_body, $headers);
	
	}////////////////////
	
	function ClearBasket() {//////////очистка корзины
		$cookie =new CHttpCookie('YiiCart', NULL);
		$cookie->value=NULL;
		Yii::app()->request->cookies['YiiCart']=$cookie;
	}/////////function ClearBasket() {////////

	private function	deliveryDetails(){///////////////////достаем по коду типа 5:p детали доставки оплаты
		$temp = explode(':', $this->delivery);
		//print_r($temp);
		//Array ( [0] => 13 [1] => 15790560 [2] => 18 [3] => 1905 )
		// products_regions.id     kladr_id   payment_method   product_id (служба доставки)   
		//exit();
		$this->delivery = $temp[3];//////////Метод доставки
		$this->payment =  $temp[2];////////Метод оплаты
		if($temp[0]=='default') {//////////ЕМС
			/*
			$delivery_default = Yii::app()->params['delivery_default'];
			$this->products[$delivery_default['product']]['num']=1;
			$this->products[$delivery_default['product']]['product'] = Products::model()->findByPk($delivery_default['product']);
			$params[0] = 0;
			if($temp[2]=='p'){
					$this->products[$delivery_default['product']]['product']->product_price = $delivery_default['price'];
					$params[1]=0; 
				}
				elseif($temp[2]=='e'){
					$this->products[$delivery_default['product']]['product']->product_price = $delivery_default['eprice'];
					$params[1]=1; 
				}
			$city = World_adres_cities::model()->findByPk($this->client_city);
			$params[2] = $city->name.', '.$city->region->name;
			$params[4] = $temp[1];
			*/
		}
		else {$products_region = Products_regions::model()->with('productmodel')->findByPk($temp[0]);
			if(isset($products_region)) {
				$params[0] = $products_region->id;
				$params[2] = $products_region->gorod->name.', '.$products_region->gorod->region->name;
				$params[4] = $temp[1];
				$this->products[$products_region->product]['num']=1;
				$this->products[$products_region->product]['product']=$products_region->productmodel;
				$products_region->productmodel->product_price = ($this->summa_pokupok>$products_region->freelimitcash)?0:($products_region->price);
				$params[1]=1;  /////т.е. поле нал, хотя другое вообще теперь не используется
				}
				
		}
		return $params;
	}//////////////private function	deliveryDetails(){/////
			
	private function saveProducts($order_id, $delivery_prod_id=null){ //////////////////Сохранение товаров в заказ
		
			if($delivery_prod_id!=null){///////Добавляем доставку
				$deliv = Products::model()->findByPk($delivery_prod_id);
				if($deliv!=null) $this->products[$delivery_prod_id]=array(
						'num'=>1,
						'product'=>$deliv->attributes
				);
			}
			/*
			echo '<pre>';
			print_r($this->products);
			echo '</pre>';
			exit();
			*/
			
			if(empty($this->products)==false) foreach($this->products as $pid=>$product_array){
				$rec = new OrderContent();
				$rec->id_order = $order_id;
				$rec->quantity = $product_array['num'];
				$rec->contents_price = $product_array['product']['product_price'];
				$rec->contents_product	= $product_array['product']['id'];
				$rec->contents_name = $product_array['product']['product_name'];
				$rec->contents_article = $product_array['product']['product_article'];
				try {
					$rec->save();
				} catch (Exception $e) {
					echo 'Ошибка сохранения содержимого заказа: ',  $e->getMessage(), "\n";
				}/////
			}
			
			
	}///////////private function saveProducts($order_id){ /////////
			

}///////class
?>