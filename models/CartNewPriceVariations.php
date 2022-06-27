<?php
///////////Сделан 20.11.2019 для chemimart. Работает не с товарами, а с priceVariations
class CartNewPriceVariations extends CFormModel {
	public $products;
	public $client_email;
	public $client_email_copy;
	public $first_name;
	public $second_name;
	public $client_city;
	public $client_street;
	public $last_name;
	public $client_tels;
	public $client_post_index;
	public $payment;
	public $delivery;
	public $order_adress1;
	public $order_adress2;
	public $primechanie;
	public $quantity_pereschet; // /////////////Массив количест товара с формы
	public $characteristics_array;
	public $products_attributes;
	public $summa_pokupok = 0;
	public $orderCompeted = false;
	public $created_order = null;
	public $company_name;
	public $company_contact;
	public $country;
	public $vat;
	public $agreement;
	
	
	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules() {
		return array (
				// username and password are required
				// password needs to be authenticated
				
				isset ( Yii::app ()->params ['cart'] ['rules'] ['nomail'] ) ? Yii::app ()->params ['cart'] ['rules'] ['nomail'] : array (
						'client_email, first_name, client_city, client_tels,  delivery , order_adress1',
						'required' 
				),
				isset ( Yii::app ()->params ['cart'] ['rules'] ['mail'] ) ? Yii::app ()->params ['cart'] ['rules'] ['mail'] : array (
						'client_email, first_name, client_city, client_tels,  delivery , order_adress1, client_post_index',
						'required',
						'on' => 'mail' 
				),
    		    isset ( Yii::app ()->params ['cart'] ['rules'] ['mailsafe'] ) ? Yii::app ()->params ['cart'] ['rules'] ['mailsafe'] : array (),
		    
				// array('passcode2', 'compare', 'compareAttribute'=>'passcode'),
				// array('first_name, second_name', 'exist'),
				array (
						'client_email',
						'email',
						'message' => "Input correct email" 
				),
				
		    /*
		    array (
						'client_email, client_email_copy',
						'email' 
				),
				array (
						'client_email_copy',
						'compare',
						'compareAttribute' => 'client_email',
						'message' => 'Emails doen\'t maеch', 
				),
		    */
		    
		          
		        array('agreement', 'compare', 'compareValue' => 1, 'message'=>'You must agree with policy') ,
		    
				
				array (
						'order_adress2, primechanie, payment',
						'safe' 
				),
				array (
						'order_adress1, client_post_index, second_name, last_name',
						'safe',
						'on' => 'nomail' 
				) 
		)
		// array('first_name, second_name, last_name, client_country', 'allowEmpty'=>true),
		// array('passcode', 'authenticate'),
		;
	}
	
	function triim(&$str){$str= trim($str);}
	
	public function isRequired($attr_name) {
		$scenar = self::getScenario ();
		
		// echo $scenar.'<br>'.$attr_name.'<br>';
		
		$rules = $this->rules ();
		foreach ( $rules as $rule ) {
		    
		    //print_r($rule);
		    
			
			if (isset ( $rule ['on'] ) && $rule ['on'] == $scenar) {
				$fields = explode ( ',', $rule [0] );
				array_walk($fields ,  'triim');
				  //echo '<pre>';
				  //print_r($fields);
				  //echo '</pre>';
				
				// var_dump($fields);
				// var_dump(in_array($attr_name, $fields));
				if (is_array ( $fields ) && in_array ( $attr_name, $fields ))
					return (array (
							'class' => $rule [1] 
					));
			}
		}
		
		// return(array('class'=>'required'));
	}
	
	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels() {

	    return Yii::app()->params['cart']['labels'];
	}
	public function updateCart() { // /////////апдейтим кукисы в зависимомти от того что пришло с формы
	                              // print_r($this->quantity_pereschet);
		$cookie = Yii::app ()->request->cookies ['YiiCart'];
		// print_r($cookie);
		if (empty ( $cookie ) == false) {
			$qqq = explode ( "#", $cookie->value );
			for($i = 0; $i < count ( $qqq ); $i ++) { // ////////////
				$qqq2 = explode ( ":", $qqq [$i] );
				if (is_numeric ( $qqq2 [0] ) and is_numeric ( $qqq2 [1] )) {
					// echo '$qqq2[0] = '.$qqq2[0];
					if (isset ( $this->quantity_pereschet [$qqq2 [0]] ) and $this->quantity_pereschet [$qqq2 [0]] > 0) { // //////////Только если есть на форме
						$id [] = $qqq2 [0];
						$num [] = $this->quantity_pereschet [$qqq2 [0]];
						$products [$qqq2 [0]] = array (
								'num' => $this->quantity_pereschet [$qqq2 [0]] 
						);
					}
				}
			}
			
			if (isset ( $products )) {
				$this->updateCookies ( $products );
				return $products;
			} else
				$this->updateCookies ();
		}
	}
	private function updateCookies($products = NULL) {
		$new_basket = NULL;
		/*
		 * if (isset ($this->product_ids_arr)) {
		 * for ($i=0; $i<count($this->product_ids_arr); $i++) {
		 * $new_basket.=$this->product_ids_arr[$i].':'.$this->product_num_arr[$i].'#';
		 * }
		 * }
		 */
		$new_basket = '';
		
		if ($products != NULL)
			foreach ( $products as $id => $prod ) {
				if ($prod ['num'] > 0)
					$new_basket .= $id . ':' . $prod ['num'] . '#';
			}
			// echo 'cook = '.$new_basket;
		if (trim ( $new_basket ) == '')
			$new_basket = NULL;
		$cookie = new CHttpCookie ( 'YiiCart', $new_basket ); // sends a cookie
		$cookie->expire = time () + 60 * 60 * 24 * 30; // /////////30 дней
		Yii::app ()->request->cookies ['YiiCart'] = $cookie;
	} // ////private function updateCookies($products){
	
	
	/**
	 * Новый метод (9.01.2017) для сохранения в БД заказа в 1 клик.
	 * По заказу протюнинга.
	 * 
	 * @param Product $product
	 *        	$cart->city=$city;
	 *        	$cart->name=$name;
	 *        	$cart->tel=$tel;
	 *        	$cart->email=$email;
	 * @param OneClickOrderForm $cart        	
	 */
	
	
	/**
	 * @param boolean $send_mail Флаг слать почту или нет
	 * @param array() $products Простой массив продуктов с ценами PriceVariations. Генерится в Cart2Controller.getCokieProducts()
	 * @param Cart2Controller $ParentController Контроллер для вызова метода renderPartial
	 */
	public function makeorderSimple($send_mail = true, $products, Cart2Controller $ParentController) { // //////////создание заказа
	  $AR_Order = new Orders ();
		$AR_Order->delivery = $this->delivery;
		if(isset($this->payment))$AR_Order->payment = $this->payment;
		else $AR_Order->payment=0;
		// $AR_Order->client_city = $this->client_city;
		$AR_Order->recept_date = @date ( "Y-m-d" );
		$AR_Order->recept_time = @date ( "H:i:s" );
		
		$client_params = array (
		    'client_tels' => $this->client_tels,
		    'first_name' => $this->first_name,
		    'second_name' => $this->second_name,
		    'client_city' => $this->client_city ,
		    'client_post_index'  => $this->client_post_index,
		    'client_country' => $this->country,
		    'client_street' => $this->client_street,
		    //'urlico_txt' => $this->company_name.'/'.$this->company_contact,
		    'urlico_txt' => $this->company_name,
		    'client_passport' => 'VAT: '.$this->vat,
		    
		);
		
		$AR_Order->id_client = FHtml::checkClientByemailNew ( trim ( $this->client_email ), true, $client_params );
		$AR_Order->primechanie = $this->primechanie;
		$AR_Order->order_adress1 = $this->order_adress1;
		$AR_Order->order_adress2 = $this->order_adress2;
		//$AR_Order->host_id = Hosts::getHostId ();
		
		try {
			$AR_Order->save ();
		} catch ( Exception $e ) {
			echo 'Ошибка сохранения  заказа place 1: ', $e->getMessage (), "\n";
			exit();
		} // ///
		$this->saveProducts ( $AR_Order->id, $AR_Order->delivery, $products );
		$AR_Order->summa_pokupok = $this->summa_pokupok;
		
		//echo '$AR_Order->summa_pokupok = '.$AR_Order->summa_pokupok;
		
		
		try {////////////Второй раз сохраняем сумму покупок
		    $AR_Order->delivery = 0; ////////////без этого не сохраняет
		    $AR_Order->save();
		} catch ( Exception $e ) {
		    echo 'Ошибка сохранения  заказа place 2: ', $e->getMessage (), "\n";
		    exit();
		} // ///
		
		/*
		echo '<pre>';
		print_r($AR_Order);
		echo '</pre>';
		exit();
		*/
		
		if ($send_mail == true) {
		    $render_params['order_id'] = $AR_Order->id;
		    $render_params['recept_date'] = $AR_Order->recept_date;
		    $render_params['first_name'] = $this->first_name;
		    $render_params['client_email'] = $this->client_email;
		    $render_params['client_tels'] = $this->client_tels;
		    $render_params['client_email'] = $this->client_email;
		    if($this->payment) $render_params['payment'] = $this->payment;
		    $render_params['second_name'] =  $this->second_name;
		    $render_params['client_street'] =  $this->client_street;
		    $render_params['client_city'] =  $this->client_city ;
		    $render_params['client_post_index'] =  $this->client_post_index;
		    $render_params['client_country'] =  $this->country;
		    $render_params['urlico_txt'] =  $this->company_name.'/'.$this->company_contact;
		    $render_params['client_passport'] =  'VAT: '.$this->vat;
		    $render_params['products'] = $products;
		    $msg_body = $ParentController->emailNotify($render_params);////Сделал так чтобы отвязать представление данных от модели
		    ///////////////////////////////(убрал формирование письма из модели, чтобы не рушить MVC). Хотя не понятно, насколько правильно
		    ///////////////////////////////Передовать весь контроллер в модель
		    $this->send_mail ( $AR_Order->id, $AR_Order->id_client, $msg_body );
		}
		$products = null;
		$this->ClearBasket ();
		$this->created_order = $AR_Order->id;
		$this->orderCompeted = true;
	}

	private function send_mail($order_id, $client_id, $msg_body) { // /////////////Отправка сообщений
	    
	    $admin_mail = Yii::app()->params['adminEmail']; ////////По умолчанию массив
	    if(is_array(Yii::app()->params['adminEmail'])==false) $admin_mail =(array)Yii::app()->params['adminEmail'];
	    
	    $headers = 'From: ' . Yii::app ()->params ['infoEmail'] . "\r\n";
		// $headers.='Content-type: text/html; charset=windows-1251' . "\r\n";
		$headers .= 'Content-type: text/html; charset=windows-1251' . "\r\n";
		// mail(Yii::app()->params['sendOrderEmail'], 'Ваш заказ на novline.com', $msg_body, $headers);
		@mail ( $this->client_email, iconv ( "UTF-8", "CP1251", 'Your request #' . $order_id . ' at ' ) . $_SERVER ['HTTP_HOST'], iconv ( "UTF-8", "CP1251", $msg_body ), $headers );
		
		foreach ($admin_mail as $mailto) {
		    @mail ( $mailto, iconv ( "UTF-8", "CP1251", 'A request was created #' . $order_id . ' at ' ) . $_SERVER ['HTTP_HOST'], iconv ( "UTF-8", "CP1251", $msg_body ), $headers );
		}
		
		
		// mail('tick007@yandex.ru', 'Копия заказа на '.$_SERVER['HTTP_HOST'], $msg_body, $headers);
	} // //////////////////
	function ClearBasket() { // ////////очистка корзины
		$cookie = new CHttpCookie ( 'YiiCart', NULL );
		$cookie->value = NULL;
		Yii::app ()->request->cookies ['YiiCart'] = $cookie;
	} // ///////function ClearBasket() {////////
	
	private function saveProducts($order_id, $delivery_prod_id = null, $products) { // ////////////////Сохранение товаров в заказ
		if (empty ( $products ) == false)
			$discount = FHtml::getClientDiscount();
			foreach ( $products as $price_variant_id => $product_array ) {
				$rec = new OrderContent ();
				$rec->id_order = $order_id;
				$rec->quantity = $product_array['num'];
				$rec->price_id = $price_variant_id;
				$rec->price_volume =$product_array['volume'];
				$rec->contents_price =$product_array['price'];
				$rec->contents_product = $product_array ['product_id'];
				$rec->contents_name = $product_array ['name'];
				$rec->contents_article = $product_array ['code'];
				$this->summa_pokupok = $this->summa_pokupok+ $product_array['price']*$product_array['num'];
				try {
					$rec->save ();
				} catch ( Exception $e ) {
					echo 'Ошибка сохранения содержимого заказа: ', $e->getMessage (), "\n";
				} // ///
			}
	} // /////////private function saveProducts($order_id){ /////////
} // /////class
?>