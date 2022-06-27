<?php
class LandsodaController extends Controller {
	const PAGE_SIZE = 10;
	
	/**
	 *
	 * @return array action filters
	 */
	public function filters() {
		return array (
				'accessControl' 
		); // perform access control for CRUD operations

	}
	
	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 *
	 * @return array access control rules
	 */
	public function accessRules() {
		return array (
				array (
						'allow', // allow all users to perform 'list' and 'show' actions
						'actions' => array (
								'list',
								'show',
								'checkquantity',
								'makeorder',
								'contact',
								'remoteorder', 
								'ostatkixml'
						),
						'users' => array (
								'*' 
						) 
				),
				array (
						'allow', // allow authenticated user to perform 'create' and 'update' actions
						'actions' => array (
								'create',
								'update' 
						),
						'users' => array (
								'@' 
						) 
				),
				array (
						'allow', // allow admin user to perform 'admin' and 'delete' actions
						'actions' => array (
								'admin',
								'delete' 
						),
						'users' => array (
								'@' 
						) 
				),
				array (
						'deny', // deny all users
						'users' => array (
								'*' 
						) 
				) 
		);
	}
	public function actionCheckquantity() {
		$num_of_bottles = Yii::app ()->params ['bottle_counter'] ['max'] - 1;
		
		$cookie = Yii::app ()->request->cookies ['bottles'];
		if (empty ( $cookie ) == false) {
			$num_of_bottles = $cookie->value;
			if ($num_of_bottles > Yii::app ()->params ['bottle_counter'] ['min'])
				$num_of_bottles --;
		} else {
		}
		
		$cookie = new CHttpCookie ( 'bottles', $num_of_bottles ); // sends a cookie
		$cookie->expire = time () + 60 * 60 * 24 * 1; // /////////1 день
		Yii::app ()->request->cookies ['bottles'] = $cookie;
		
		$rnd_name = rand ( 0, count ( Yii::app ()->params ['bottle_counter'] ['names'] ) - 1 );
		$rnd_city = rand ( 0, count ( Yii::app ()->params ['bottle_counter'] ['cities'] ) - 1 );
		$response = array (
				'num_of_bottles' => $num_of_bottles,
				'customer' => Yii::app ()->params ['bottle_counter'] ['names'] [$rnd_name] . ' (' . Yii::app ()->params ['bottle_counter'] ['cities'] [$rnd_city] . ')' 
		);
		
		echo json_encode ( $response );
	}
	public function actionContact() {
		if (Yii::app ()->request->isAjaxRequest) {
			if (isset ( $_POST ['ContactForm'] )) {
				
				$contact = new ContactForm ();
				$contact->attributes = $_POST ['ContactForm'];
				
				if ($contact->validate () == true) {
					
					// $headers="From: ".Yii::app()->params['infoEmail']."\r\n";
					$headers = 'From: "sourcesoda"<sourcesoda@ams.oc-corp.ru>' . " \r\n";
					$headers .= 'Content-type: text/html; charset=windows-1251' . "\r\n";
					$subject = '';
					if (isset ( $contact->name ))
						$subject .= $contact->name . ' ';
					$subject = $subject .= $contact->subject;
					$message = 'Сообщение:<br/>' . $contact->name . '<br/> ' . $contact->body . '<br/> ' . $contact->email . '<br/> ' . $contact->subject;
					// supportEmail
					@mail ( Yii::app ()->params ['infoEmail'], iconv ( "UTF-8", "CP1251", $subject ), iconv ( "UTF-8", "CP1251", $message ), $headers );
					// @mail(Yii::app()->params['supportEmail'], iconv( "UTF-8", "CP1251",$subject), iconv( "UTF-8", "CP1251", $message),$headers);
				} else {
					echo CActiveForm::validate ( $contact );
				}
			}
		}
		Yii::app ()->end ();
	}
	
	
	
	/**
	 * @param string $order_string soda_prod1#num1|soda_prod2#num2|...
	 * @return array <int, int> array(soda_prod1=>num1, soda_prod2=>num2,....)
	 */
	private function normalize_order($order_string){
		if(trim($order_string)!=''){
			$pairs =  explode ( '|', $order_string );
			$order = null;
			foreach ($pairs as $pair){
				$pair_explode = explode('#', $pair);
				$order[$pair_explode[0]]=$pair_explode[1];
			}
			return $order;
		}
		else return null;
	}
	
	/**
	 * @Принимает удаленный XML заказ (от 1sifon.ru)
	 */
	public function actionRemoteorder() {
		$xmlRequest = Yii::app()->getRequest()->getParam('xmlRequest', null);
		$error = 'error'; /////////флаг ошибки
		if ($xmlRequest!=null) try {
			//$xmlRequest = $_POST ['xmlRequest'];
			$xml = simplexml_load_string ( $xmlRequest );
			ob_start ();
			print_r ( $xml );
			////Старая до апгрейда сифона
			//$key_str = Yii::app()->params['order']['md5key'].$xml->fio.$xml->phone.$xml->email.$xml->street.$xml->comment.$xml->save.$xml->f;
			$key_str = Yii::app()->params['order']['md5key'].$xml->yName.$xml->yEmail.$xml->yPhone.$xml->yText.$xml->sposob.$xml->oplata.$xml->save.$xml->f.$xml->kodkupon;
			$encripted_order_str = md5 ( $key_str );
			echo $encripted_order_str;
			echo $xml->sodapids;
			if ($encripted_order_str == $xml->mdcheck) {
				$order_normalized = $this->normalize_order($xml->sodapids );
				$simpla_order_id = $this->writeOrder(
						(string)$xml->yName,
						(string)$xml->yPhone,
						(string)$xml->yEmail,
						//(string)$xml->comment . '; ' . $xml->order_info,
						(string)$xml->order_info,
						(string)$xml->yText,
						'заказ с ' . $xml->host,
						'заказ с ' . $xml->host,
						'заказ с ' . $xml->host,
						$order_normalized,
						'заказ №'.(string)$xml->order_num.' с '.(string)$xml->host,
						(int)$xml->order_num, 
						0,
						(int)$xml->sposob,
						(int)$xml->oplata,
						(string)$xml->host,
						(string)$xml->kodkupon
						);
			}
			else $error = "ChecksumEroro";
		} catch ( Exception $e ) {
			throw new CHttpException ( 500, 'Ошибка: ' . $e->getMessage () );
			//Yii::app ()->end ();
		}
		else {
			throw new CHttpException ( 500, 'Не переданы параметры' );
			//echo 'Не переданы параметры';
			//Yii::app ()->end ();
		}
		$qqq = ob_get_clean ();
		
	
		/**
		 *Пишем запрос в Таблицу для проверки
		 */
		$cc = new CurlCheck ();
		$cc->zapros = $qqq;
		$cc->save ( false );
		
		if(isset($simpla_order_id)) echo $simpla_order_id;
		else echo $error;
		Yii::app ()->end ();
	}
	
	

	/**
	 * Создает локальный заказ по ajax вызову
	 */
	public function actionMakeorder() {
			if (Yii::app ()->request->isAjaxRequest) {
			
			$name = Yii::app ()->getRequest ()->getParam ( 'name', NULL );
			$tel = Yii::app ()->getRequest ()->getParam ( 'tel', NULL );
			
			if ($tel == null || trim ( $tel ) == '' || $name == null || trim ( $name ) == '') {
				header ( "HTTP/1.0 404 Not Found" );
				Yii::app ()->end ();
			}
			
			// echo $s_order->id;
			//error_reporting(E_ALL);
			try {
			
			   $simpla_order_id = $this->writeOrder ( 
						$name,
						$tel,
						NULL,
						'Заказ с лэндинга',
						'Заказ с лэндинга',
						'Заказ с лэндинга',
						'Заказ с лэндинга',
						'Заказ с лэндинга',
						array (	Yii::app ()->params ['order'] ['product_id']=>1), 
						'Заказ с лэндинга' ,
						null,
						Yii::app ()->params ['order'] ['product_price'],
			   		0,
			   		0,
			   		$_SERVER['HTTP_HOST'],
			   		'' //-kodkupon
				);
			   echo $simpla_order_id ;
			} catch (Exception $e) {
				print_r($e);
			}
			
		
			
			// echo json_encode("qweqwe");
		} else {
			header ( "HTTP/1.0 500 Ajax only" );
			Yii::app ()->end ();
		}
	}
	
	/**
	 *
	 * @param string $name        	
	 * @param string $tel        	
	 * @param string $email        	
	 * @param string $price        	
	 * @param string $comment        	
	 * @param string $address        	
	 * @param string $delivery_pack        	
	 * @param string $delivery_info        	
	 * @param string $delivery_name        	
	 * @param array() $products массив ид_товара = > количество  
	 * @param string $note       	
	 * @return string
	 */
	private function writeOrder(
			$name, //1
			$tel, //2
			$email = null, //3
			$comment = 'Заказ с лэндинга', //4
			$address = 'Заказ с лэндинга',//5
			$delivery_pack = 'Заказ с лэндинга', //6
			$delivery_info = 'Заказ с лэндинга', //7
			$delivery_name = 'Заказ с лэндинга', //8
			$products = array(),//9
			$note ='',//10
			$order_id=null,//11
			$price = null, //12
			$dostavka = 0,//13
			$oplata = 0,//14
			$host = '', //////15
			$kodkupon = '' /////16
			) {
		
		date_default_timezone_set('Europe/Moscow');
		$d = date ( 'Y-m-d H:i:s' );
		
		$s_order = new SimplaOrders ();
		$s_order->name = $name;
		$s_order->phone = $tel;
		$s_order->date = $d;
		$s_order->delivery_pack = $delivery_pack;
		$s_order->delivery_info = $delivery_info;
		$s_order->payment_date = $d;
		$s_order->closed = 0;
		$s_order->datemod = $d;
		$s_order->treking1 = '';
		$s_order->treking2 = '';
		$s_order->treking_forw = '';
		$s_order->delivery_name = $delivery_name;
		$s_order->address = $address;
		$s_order->email = ($email == null) ? Yii::app ()->params ['infoEmail'] : $email;
		$s_order->comment = $comment;
		$s_order->payment_details = 'Заказ с лэндинга';
		$s_order->ip = $_SERVER ['REMOTE_ADDR'];
		$s_order->total_price = $price; // ////////
		$s_order->total_price_discount = 0;
		$s_order->note = $note ;
		$s_order->discount = 0;
		$s_order->coupon_discount = 0;
		$s_order->coupon_code = $kodkupon;
		$s_order->discount_info = '';
		$s_order->whose_order = '';
		$s_order->city_id = '';
		$s_order->courier = '';
		$s_order->ks_time = 0;
		$s_order->ks_manager = '';
		$s_order->id_market = 0;
		$s_order->substatus_market = '';
		$s_order->firstname = $name;
		$s_order->lastname = '';
		$s_order->surname = '';
		$s_order->flat = '';
		$s_order->house = '';
		$s_order->street = '';
		$s_order->geolocation = '';
		
		/////////////////Доставка
		/*
		 Доставка курьером по Москве - 1 250р  				s_delivery.id = 1
		 Доставка до 15 км от МКАД - 2 350р - до 15 км		s_delivery.id = 3?
		 Доставка по России почтой - 3 - 0					s_delivery.id = 4 ?
		 * */
		
		//Оплата
		/*
		 Наличный расчет - 1  : В БД соды s_payment_methods.id=13
		 Онлайн оплата пластиковой картой - 2 : В БД соды s_payment_methods.id=12 ?
		 */
		$host_pure = str_replace('www.', '', $host);
		
		//return 'werwerwe = '.$host_pure;
		//exit();
		
		if($oplata>0){
			/*
			if($oplata==1)$s_order->payment_method_id =13;
			if($oplata==2)$s_order->payment_method_id =12;
			*/
			if(isset(Yii::app()->params['payment_matching']) && isset(Yii::app()->params['payment_matching'][$host_pure])){
				if(isset(Yii::app()->params['payment_matching'][$host_pure][$oplata])) {
					$s_order->payment_method_id = Yii::app()->params['payment_matching'][$host_pure][$oplata];
				}
			}
		}
		if($dostavka>0){
			/*
			if($dostavka==1) {
				$s_order->delivery_id = 1;
				$s_order->delivery_price = '250';
			}
			if($dostavka==2) {
				$s_order->delivery_id = 3;
				$s_order->delivery_price = '350';
			}
			if($dostavka==3) {
				$s_order->delivery_id = 4;
				$s_order->delivery_price = '0';
			}
			*/
			
			if(isset(Yii::app()->params['delivery_matching']) && isset(Yii::app()->params['delivery_matching'][$host_pure]) &&
					isset(Yii::app()->params['delivery_matching'][$host_pure][$dostavka])){
				$s_order->delivery_id = Yii::app()->params['delivery_matching'][$host_pure][$dostavka]['id'];
				$s_order->delivery_price = Yii::app()->params['delivery_matching'][$host_pure][$dostavka]['price'];;
			}
		}
				
		// print_r($s_order->attributes);
		
		try {
			$s_order->save ( false );
			
			if (is_numeric ( $s_order->id ) == true && $products != null && is_array ( $products ) == true && empty ( $products ) == false) {
				$order_sum = 0;
				foreach ( $products as $product_id=>$product_num ) {
					
					$s_prod = SimplaProducts::model()->findByPk ( $product_id );
					$price_corrected = 0;
					if($price!=0 && $price!=null && count($products)==1) $price_corrected = $price;
					else {
						if(isset($s_prod->variant) && $s_prod->variant!=null) $price_corrected = $s_prod->variant->price;
					}
					
					
					$s_purch = new SimplaPurchases ();
					$s_purch->order_id = $s_order->id;
					$s_purch->product_id = $s_prod->id;
					$s_purch->product_name = $s_prod->name;
					$s_purch->variant_name = $s_prod->variant->name;
					$s_purch->variant_id = $s_prod->variant->id;
					//$s_purch->price = $price;
					$s_purch->price = $price_corrected;
					$s_purch->amount = $product_num;
					$s_purch->sku = $s_prod->variant->sku;
					$s_purch->save ( false );
					$order_sum+=$price_corrected*$product_num;
				}
				
				$s_order->total_price = round($order_sum,0)+$s_order->delivery_price;
				$s_order->url =  md5(uniqid(microtime().$s_order->id, true));
				$s_order->save ( false );
			}
			return $s_order->id;
			
		} catch ( Exception $e ) {
			throw new CHttpException ( 500, 'Ошибка: ' . $e->getMessage () );
			/*
			 * echo '<pre>';
			 * var_dump($e);
			 * echo '</pre>';
			 */
			//Yii::app ()->end ();
			return $e->getMessage ();
		}
	}
	
	public function actionOstatkixml(){
		$this->layout="empty";
		$models=SimplaProducts::model()->with('variant')->findAll();
		$this->render('ostatkixml', array('models'=>$models));
	}
}
