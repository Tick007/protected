<?php
//////////Новый класс копия старого для работы с моделью MyShoppingCart2. Для цен заданных в price variations
//////////Для сайта chemimart
class Cart2Controller extends Controller
{
	const PAGE_SIZE=10;

	/**
	 * @var string specifies the default action to be 'list'.
	 */
	public $defaultAction='content';
	public $additional_var; /////////////Определил переменную для вывода в шабло в head
	
	public $params;
	/**
	 * @var CActiveRecord the currently loaded data model instance.
	 */
	private $_model;
	private $craeted_order;
	
	var $PRICEVAR;
	var $PROD; //////////Товар, заполняется при info
	
	public $nums = array(
			1=>'1',
			2=>'2',
			3=>'3',
			4=>'4',
			5=>'5',
			6=>'6',
			7=>'7',
			8=>'8',
			9=>'9',
	);
	
	

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'CheckBrouser +index, getcart, delivery, error',
			'Check_order_epayment + epayment',
		    'Check_pricevar_existance +add', //'check_product_existance +add',
			'SetTheme +index, getcart, delivery, error',
		    'HasJsFile + index, delivery',
		    
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'list' and 'show' actions
				'actions'=>array('list'),
				'users'=>array('*'),
			),
			
		);
	}
	
	public function filterCheck_pricevar_existance($filterChain)	{////
	    $id = Yii::app()->getRequest()->getParam('id', null);
	    
	    if($id!=null){
	        $pricevar = PriceVariations::model()->findByPk($id);
	        if(isset($pricevar) && $pricevar!=null){
	            if($pricevar->active!=1){
	                throw new CHttpException(404,'Комбинация цены недоступна');
	                //exit();
	            }
	            elseif(isset($this->CAT) && $product->category_belong != $this->CAT->category_id){
	                throw new CHttpException(404,'Группа товара не соответствует');
	            }
	            else $this->PRICEVAR = $pricevar;
	            
	        }
	        else {
	            throw new CHttpException(404,'Комбинация цены не найдена');
	            //exit();
	        }
	    }
	    $filterChain->run();
	}

	public function  filterCheck_order_epayment($filterChain)	{///////
		$id = Yii::app()->getRequest()->getParam('id');
		//echo $id;
		
		$ORDER = Orders::model()->findByPk($id);
		if(isset($ORDER)==false) {
			throw new CHttpException(404,'Заказ не найден.');
			exit();
		}
		else {
			$this->craeted_order = $ORDER;
		}
		
		$filterChain->run();
	}
	
	


	
	public function actionUpdate(){ //////////////////Аджакс сохранение корзины - кукисов
		if (Yii::app()->request->isAjaxRequest) {
			$sc = new MyShoppingCart2();
			$add_to_basket = Yii::app()->getRequest()->getParam('add_to_basket');
			$num_to_basket = Yii::app()->getRequest()->getParam('num_to_basket');
			//$basket = $this->Add_To_Cart();		
			$price_variations = NULL;	
			
			$sc->changeOrder($add_to_basket, $num_to_basket);
			$price_variations = $sc->getOrder();
			
			
			if(isset($price_variations) && $price_variations!=null){
				
				///////////// выбираем товары по спис
				$criteria=new CDbCriteria;
			    $criteria->addCondition("t.active = 1 ");
				$criteria->order = ' t.id ';
				$criteria->condition = " t.price>0 AND t.id IN (".implode(',',array_keys($price_variations) ).")";
				$models = PriceVariations::model()->findAll($criteria);
				
				
				if(isset($models))  {
					$summa_pokupok = 0;
					$kol_tovarov = 0;
					$cur_summ = 0;
					$alltoweb = NULL;
					$nums = NULL;
					for($i=0, $c=count($models); $i<$c; $i++) {
						//echo $models[$i]->product_name.'<br>';
						$price = $models[$i]->price;
						$count = $price_variations[$models[$i]->id];
						$kol_tovarov+= $count ;
						$cur_summ = $price*$count;
						$summa_pokupok+= $cur_summ;
						$nums[$models[$i]->id]=array('num'=>$count, 'summ'=>str_replace(',00', '',FHtml::encodeValuta( round($cur_summ, 0), '')));
						//$summa_pokupok = $this->summa_pokupok + $this->products[$models[$i]->id]['product']->product_price*$this->products[$models[$i]->id]['num'];
					}
					$alltoweb = array('summa_pokupok'=>str_replace(',00', '',FHtml::encodeValuta( round($summa_pokupok, 2), '')), 'kol_tovarov'=>$kol_tovarov, 'details'=>$nums, 'summa_pokupok_math'=>round($summa_pokupok, 2));
					echo json_encode($alltoweb);
				}
				
			}
		}//if (Yii::app()->request->isAjaxRequest) {
	}
	

	
	public function actionDelivery(){
		
	    //print_r($_POST['CartNewPriceVariations']);
	    
	    $cart = new CartNewPriceVariations(Yii::app()->params['cart']['checkout_policy']);
	    
	    if(Yii::app()->user->isGuest==false && isset($_POST['CartNewPriceVariations'])==false   ){
            $user = User::model()->findByPk(Yii::app()->user->id);
	        if(isset($user)) {
	            $cart->first_name = $user->first_name;
	            $cart->client_email = $user->client_email;
	            $cart->client_tels = $user->client_tels;
	            $cart->second_name = $user->second_name;
	            $cart->client_post_index = $user->client_post_index;
	            $cart->country = $user->client_country;
	            $cart->client_city = $user->client_city;
	            $cart->company_name = $user->urlico_txt;
	            $cart->company_contact = $user->urlico_txt;
	            $cart->vat = $user->client_passport;
	            
	            
	        }
	    }
	    

		
		
		if(isset($_POST['CartNewPriceVariations']) && $_POST['CartNewPriceVariations']['submitbut'] )
		{
				
			//print_r($_POST['CartNewPriceVariations']);
				
			$cart->setAttributes($_POST['CartNewPriceVariations']);
			$cart->attributes=$_POST['CartNewPriceVariations'];
			$check = $cart->validate();
			$products = $this->getCokieProducts();
			if($check==true && empty($products)==false ) {
			    $cart->makeorderSimple(true, $products, $this);
			    
			    
                //////////////Делаем ссылку на оплату
			    if(isset(Yii::app()->params['checkout']['online_checkout']) && Yii::app()->params['checkout']['online_checkout']==$cart->payment){
			        if($cart->orderCompeted==true && is_numeric($cart->created_order)==true && $cart->created_order!=null){
			            $order_id = $cart->created_order;
			            $sum = Orders::getSumm($order_id);
			            $crc_signature = $this->calculate_crc($order_id, $sum );////////////подсчет конт суммы
			            $request_params = '?order_id='.$order_id;
			            $request_params.='&shop_id='.Yii::app()->params['inetpayment']['shop_id'];
			            $request_params.='&summ='.$sum;
			            $request_params.='&signaturevalue='.$crc_signature;
			            //echo $request_params;
			            //exit();
			            ////////////////так не работает, потому что тогда
			            /////////////// в getUrlReferrer на другой стороне попадает delivery
			            /////////////// поэтому будем через яваскрипт или урл с отдельной страницы
			            //$this->redirect(Yii::app()->params['inetpayment']['getawayurl'].$request_params, true, 302);
			            
			            /////Это нужно раскоментировать что бы ссылка на оплату показывалась сразу
			            //$payment_url=Yii::app()->params['inetpayment']['getawayurl'].$request_params;

			        }
			    }
			    
			}
			
				
		}
		//print_r($cart->attributes);
		
		//$cart->getProducts();///////////////делает что-то полезное. Что ХЗ
		$cart->products=$this->getCokieProducts();
		
		$payment_options=array();
		$payment_options = PaymentMethod::getPayMethods();


		
		//print_r($cart->Attributes);
		
		
		if(empty($cart->products)==false)$this->render('delivery', array('cart'=>$cart,'payment_options'=>$payment_options));
		else {
			if($cart->orderCompeted == true AND $cart->created_order!=NULL ){
			    $render_params = array('model'=>@$cart);
			    $render_params['order']=Orders::model()->findByPk($cart->created_order);
			    if(isset($payment_url)) $render_params['payment_url']=$payment_url;
			    if(Yii::app()->user->isGuest==true) { ///////Если гость то редиректим на форму заполнения данных нового пользователя
			        
			        Yii::app()->user->setFlash('order_created',
			            "Request # CM-".date('Y', strtotime($render_params['order']->recept_date)).'-'.$cart->created_order." was created<br>
We will respond to you as soon as possible.");
			        /////////////И редиректим куда нить
			        
			        $url = Yii::app()->createUrl('chemimart/registerfull', array('client'=>base64_encode($render_params['order']->id_client), 'order'=>base64_encode($render_params['order']->id)));
			        $this->redirect($url, true);
			    }
			    else $this->render('empty', $render_params);
			}
			else $this->render('empty');
		}
	}
	
	
	/**
	 *Страница для перехода на страницу оплаты платежной системы. На эту же страницу будет редиректить платежная система
	 *после совершения оплаты (и обращения ПС к resultUrl в фоне)
	 */
	public function actionPaymentgetaway($id){
	    if(is_numeric($id)){
	        
	        $order = Orders::model()->findByPk($id); 
	        if($order == null){
	            throw new CHttpException(404,'Not exist');
	            exit();
	        }
	        else{
	            $this->render('getaway', array('order'=>$order));
	        }
            
	        
	    }
	    else{////Ну это фактически никогда не вызовется, потому-что в правилах маршрутизации id интовое (controller/action/id<d>)
	        throw new CHttpException(500,'Error');
	        exit();
	    }
	}
	
	
	/**
	 * array() $render_params Параметры заказа для формирования тела сообщения
	 */
	public  function emailNotify($render_params){
	    
	    $render_params['field_list']=$render_params;
	    
	    return   $this->renderPartial('cartmail', $render_params, true);
   }
	

	

	
	public function actionOrderinfo(){ /////////////Новая функция для оформления корзины в несколько этапов.Это этап 1. Корректировка количества
		$cart = new CartNew();
		
		if(isset($_POST['CartNew']))
		{
			$cart->setAttributes($_POST['CartNew']);
			$cart->attributes=$_POST['CartNew'];
			
			$cart->quantity_pereschet = $_POST['CartNew']['quantity_pereschet'];
			//print_r($cart->quantity_pereschet);
			//print_r($cart->quantity_pereschet);
			//$cart->validate();
			$cart->getProducts();
			$cart->updateCart();
			
		}
		else $cart->getProducts();
		
		if(empty($cart->products)==false) $this->render('orderinfo', array('cart'=>$cart));
		else $this->render('empty');
	}
	

	public function actionContent()
	{
		//echo '1<br>';
		
		$client_discount = FHtml::getClientDiscount();

		$cart = Yii::app()->getRequest()->getParam('Cart');
		
		//print_r($cart);
		
		if(isset($cart['delivery_method'])) $delivery_method = $cart['delivery_method'];
		
		if(isset($delivery_method) AND in_array($delivery_method, Yii::app()->params['delivery_mail'])==true) $model = new Cart('mail');//////////////Дополнительные поля для почтовой отправки
		else {
			if(isset($delivery_method) AND in_array($delivery_method, Yii::app()->params['delivery_samovivoz'])==true)  $model = new Cart('samovivoz');
			else $model = new Cart('nomail'); //////////////
		}
		//$model->addError('newlogin','Имя пользователя занято.');
		
		if (isset($_POST['make_order'])  ) {///////////////Записываем заказ если проверка была успешной
		
		
				if ($model->Set_basket() !="empty") { ///////////////////////////////
				
				//$model->ComposeQuery();
					$validate = $model->validate();
					$validate_adress1 =  $model->validate_adress1();
					
					//var_dump($validate);
					//var_dump($validate_adress1);
					//echo 'qweqweqwe<br>';
					//////////Если метод доставки - самовывоз, то уже не проверяем на остальные поля
					//if(isset($delivery_method)==true AND ( ! isset(Yii::app()->params['delivery_samovivoz']) || in_array($incoming['delivery_method'], Yii::app()->params['delivery_samovivoz'])==false) AND $validate==true) $model->SaveOrder();
					//if(isset($delivery_method)==true AND ( ! isset(Yii::app()->params['delivery_samovivoz']) ) AND $validate==true) $model->SaveOrder();
					if($validate==true)$model->SaveOrder(); //по идее нужные поля подставляются в модели, так что в доп проверках помими validate смысла нет
					elseif ($validate==true AND $validate_adress1==true) $model->SaveOrder();
				}/////////////////////if (!$model->Set_basket()=="empty") { 
		}///////////////////////

		
		if (@$model->Set_basket()=="empty") {////////////Если в корзине ничего нет ////То выплевываем пустую страницу
			 $model->SaveCookie();
			 
			 if(isset(Yii::app()->params['robokassa']) AND isset(Yii::app()->params['robokassa']['method_ids']) AND empty(Yii::app()->params['robokassa']['method_ids'])==false) {
				 if(in_array($model->payment_method, Yii::app()->params['robokassa']['method_ids'])==true) { ////////////////////////Редеректим на страницу где можно выбрать валюту робокассы
				 	$url = Yii::app()->createUrl('cart/epayment', array('id'=>$model->created_order));
					$this->redirect($url, true);	
					exit(); 
				  }
			  }
			 
			 $this->render('empty', array('model'=>$model));
		}
		else {////////////////////////////////Если есть то рисуем товары
				
				if (isset($_POST['clear_cart'])) {
						////////Очистка корзины
						//Yii::app()->request->cookies['YiiCart']=NULL;
						//$cookie=Yii::app()->request->cookies['YiiCart'];
						//unset($cookie);
						 unset(Yii::app()->request->cookies['YiiCart']);
						$this->render('empty', array('model'=>$model));
						exit();
				}
				else {
					
						$model->validate();
						if(isset($delivery_method)==true AND isset(Yii::app()->params['delivery_samovivoz']) AND in_array($delivery_method, Yii::app()->params['delivery_samovivoz'])==false ) $validate_adress1 =  $model->validate_adress1();
						$model->SaveCookie();
						$model->ComposeQuery();
						
						
						$form = new CForm($model->GetStructure1(), $model);
						$form->name='MyBasketForm';
						
						//echo '2<br>';
						////////////Смотрим фотки
						$models = $model->GetCartContent();
						for($k=0; $k<count($models); $k++) {
								$product_ids[] = $models[$k]['id'];
						}
						//print_r($product_ids);
						//exit();
						/////////////////////Вытаскиваем фотки
						if(isset($product_ids) AND empty($product_ids)==false AND is_array($product_ids)) {
						$criteria=new CDbCriteria;
						//$criteria->order = ' t.title ';
						$criteria->condition = "t.product IN (".implode(',', $product_ids).") AND t.is_main = 1";
						$pictures1=Picture_product::model()->with('img')->findAll($criteria);
						if(isset($pictures1)) for($i=0; $i<count($pictures1); $i++)$pictures[$pictures1[$i]->product]= $pictures1[$i];
						
						
							if(isset($delivery_method)==true AND isset(Yii::app()->params['delivery_samovivoz']) AND in_array($delivery_method, Yii::app()->params['delivery_samovivoz'])==true) {////////////Если самовывоз, то выбираем товар, что бы вывести карту
							$samovivoz_product = Products::model()->findByPk($delivery_method);		
									
							
							}////////if(isset($delivery_method)==tr
						
						//print_r($pictures);
				}
				}///////if(isset($product_ids) AND empty($product_ids)==false AND is_array($product_ids)) {
				

				
				$params_arr = array('form'=>$form, 'models'=>$model->GetCartContent() , 'stores_names'=>$model->get_stores_names(), 'stores_id'=>$model->get_stores_id(), 'products_nums_arr'=>$model->products_nums_arr, 'no_register'=>$model->no_register, 'private_face'=>$model->private_face,  'private_face_mail'=>$model->private_face_mail, 'private_face_labels'=>$model->private_face_labels,  'order_fields'=>$model->order_fields, 'order_field_labels'=>$model->order_field_labels, 'urlico_face'=>$model->urlico_face, 'urlico_labels'=>$model->urlico_labels, 'delivery_method'=>$model->delivery_method , 'model'=>$model, 'pictures'=>@$pictures, 'samovivoz_product'=>@$samovivoz_product, 'attr_labels'=>$model->attributeLabels());
				if($client_discount !=null)$params_arr['client_discount']=$client_discount;
				//var_dump($model->no_register);
				
				if(isset(Yii::app()->params['cart']) AND isset(Yii::app()->params['cart']['oformlenie_separate']) AND ( is_null($model->no_register)==false) ) {/////////рендерим отдельную форму оформления заказа
						$this->render(Yii::app()->params['cart']['oformlenie_separate'], $params_arr);		
				}/////////'oformlenie_separate']
				else $this->render('content', $params_arr);
				}
				
				if (Yii::app()->user->isGuest) {
				//////////Предлагаем кнопочку продолжения без регистрации
				//echo "Гость<br>";
				}
				else {//////////////начинаем оформление
				//echo "Пользователь<br>";
				//print_r(Yii::app()->user->getId());
				}
	}



public function actionOneclickordersimple(){
	
	if (Yii::app()->request->isAjaxRequest) {
		$tel = Yii::app()->getRequest()->getParam('tel');
		$city = Yii::app()->getRequest()->getParam('city');
		$name = Yii::app()->getRequest()->getParam('name');
		$email = Yii::app()->getRequest()->getParam('email');
		$product_id = Yii::app()->getRequest()->getParam('product_id', NULL); 
			
		$cart = new OneClickOrderForm();
		$cart->city=$city;
		$cart->name=$name;
		$cart->tel=$tel;
		$cart->email=$email;
		

				
			$product = Products::model()->findByPk($product_id);
			if(isset($product) && trim($tel)){
				
			$check = $cart->validate();
			
			
			if($check==true) {	
				
				$order_num = null;
				
				$cartNew = new CartNew();
				try {
					$order_num = $cartNew->saveDBOneClickOrder($product, $cart);
				} catch (Exception $e) {
					
				}
				
				//
				$headers = 'From: '.Yii::app()->params['infoEmail']. "\r\n" ; /////////Менять для проверки не здесь, а ниже
						//$headers.='Content-type: text/html; charset=windows-1251' . "\r\n";
						$headers.='Content-type: text/html; charset=utf8' . "\r\n";
						if(trim($product->belong_category->alias)) $plink = CHtml::link($product->product_name, array('product/details', 'alias'=>$product->belong_category->alias, 'pd'=>$product->id))."&nbsp;&nbsp;&nbsp;http://".$_SERVER['HTTP_HOST'].Yii::app()->createUrl('product/details', array( 'alias'=>$product->belong_category->alias,  'pd'=>$product->id));
						else $plink = CHtml::link($product->product_name, array('product/details', 'pd'=>$product->id))."&nbsp;&nbsp;&nbsp;http://".$_SERVER['HTTP_HOST'].Yii::app()->createUrl('product/details', array(  'pd'=>$product->id));
					$msg_body	= "Сделан заказ ";
					if(isset($order_num) && $order_num!=null) $msg_body.= " №".$order_num.' ';
					$msg_body.= "на ".$plink."     (id = $product_id). Телефон клиента: $tel<br>
					Клиент: $name<br>
					Город: $city<br>
					email: $email";
						
					
					if(is_array(Yii::app()->params['adminEmail'])==false) $admin_mail ==(array)Yii::app()->params['adminEmail'];
					else $admin_mail = Yii::app()->params['adminEmail'];
					
					//print_r($admin_mail);
					//exit();
					
					foreach ($admin_mail as $amail) {
					    if(@mail($amail,  'Сделан заказ в один клик на '.$_SERVER['HTTP_HOST'],  $msg_body, $headers))		{
					        $this->renderPartial('oneclicksuccess', array('order_num'=>$order_num));
					    }
					    //if(@mail(Yii::app()->params['adminEmail'],  'Сделан заказ в один клик на '.$_SERVER['HTTP_HOST'],  $msg_body, $headers))		$this->renderPartial('oneclicksuccess');
					    else echo "Не удалось отправить";
					}
						
					
					}/////////if($check==true) {
					else {
							//throw new CHttpException(500 , 'Заполните все поля верно!');
							//echo CHtml::errorSummary($cart);
							//echo  'Заполните все поля верно!';
							header("HTTP/1.0 500 Заполните все поля верно!");
							exit();
					}
						
	
				}
			

	}
	else {
		throw new CHttpException(404,'Ajax only');
		exit();
	}
	
}

public function actionOneclickorder(){//////////Купить в 1 клик
	if (Yii::app()->request->isAjaxRequest) {
		$tel = Yii::app()->getRequest()->getParam('tel');
		$product_id = Yii::app()->getRequest()->getParam('product_id', NULL); 

		$cart = new CartNew();
		$cart->attributes=Yii::app()->params['orderoneclick'];
		//print_r($cart->attributes);
		$cart->client_tels = $tel;
		$check = $cart->validate();
		$cart->getProducts($product_id);
		if($check==true) {
			$cart->makeorder();
			$this->renderPartial('oneclicksuccess');
		}
		else {
			echo CHtml::errorSummary($cart);
		}
		
	}
	else {
		throw new CHttpException(404,'Ajax only');
		exit();
	}
}

public function actionOneclick($id){//////////Купить в 1 клик

	$this->layout="empty";
	$model = new OneClickOrderForm;
	$id = Yii::app()->getRequest()->getParam('id');
	$OneClickOrderForm = Yii::app()->getRequest()->getParam('OneClickOrderForm');
	
	if(isset($OneClickOrderForm)) {
		
		$model->setAttributes($OneClickOrderForm);
		$validate = $model->validate();
		if($validate==true) {
			
					$product=Products::model()->findByPk($id);
					$msg_body = "Заявка на обратный звонок<br>\r\n";
					 $msg_body.="Телефон ".$model->tel."<br>\r\n";
					 $msg_body.="Имя ".$model->name."<br>\r\n";
					 $url=urldecode(Yii::app()->createUrl('product/details' ,array('alias'=>$product->belong_category->alias, 'path'=>FHtml::urlpath($product->belong_category->path)  ,  'pd'=>$product->id) , array('target'=>'blank')) );
					 $msg_body.= "Товар: ".CHtml::link($product->product_name, $url)."<br>\r\n";
					
					$headers = 'From: '.Yii::app()->params['infoEmail']. "\r\n" ;
					$headers.='Content-type: text/html; charset=windows-1251' . "\r\n";
					
					@mail(Yii::app()->params['infoEmail'],  iconv( "UTF-8", "CP1251", 'Заявка на обратный звонок'),  iconv( "UTF-8", "CP1251",$msg_body), $headers);
			//@mail('tick007@yandex.ru',  iconv( "UTF-8", "CP1251", 'Заявка на обратный звонок'),  iconv( "UTF-8", "CP1251",$msg_body), $headers);
			
			
			$this->render('oneclick', array('succeed'=>true, 'msg_body'=>$msg_body));
			exit();
		}
	}
	

	
	$criteria=new CDbCriteria;
		$criteria->condition = " t.id = $id";
		$criteria->select=array( 't.*',  'picture_product.picture AS icon' , 'picture_product.ext AS ext');
			//$criteria->together = true;
			$criteria->join ="
			LEFT JOIN ( SELECT product, picture, pictures.ext as ext FROM picture_product  JOIN pictures ON pictures.id= picture_product.picture  WHERE is_main=1 ) picture_product ON picture_product.product = t.id  ";
		$product = Products::model()->with('belong_category')->find($criteria);

	$this->render('oneclick', array('product'=>$product, 'model'=>$model));
}


public function actionEpayment() {///////////Оплата через робоувссу	
	
	$Robokassa = new Robokassa;

			 $Robokassa->changesumm($this->craeted_order->summa_pokupok);
			//$Robokassa->changesumm(10.96);
			 $Robokassa->receiveXMLparams();
		
				//if ($curr != NULL) $Robokassa->set_curr($curr);
				//$Robokassa->setmerchant($CA->id);
				//$Robokassa->createorder();
				$Robokassa->setorder_id($this->craeted_order->id);
				//$Robokassa->setorder_id(0);
				//$Robokassa->sendnotification();
		//	$this->redirect(array("/privateroom/currencyselect", 'id'=>$cat_id, 'id2'=>$Robokassa->inv_id), true, 302);


		
	$this->render('epayment',  array('Robokassa'=>$Robokassa));
}/////////public function actionEpayment() {
	
public function actionInfo($id){ ////////////Для вывода в корзине инфо о товаре
$model = Products::model()->findByPk($id);
if(isset($model)) $this->render('info', array('model'=>$model));
else {
	 throw new CHttpException(404,'Нет такой службы доставки');
					 exit();
	}

}	//////////public function actionInfo($id){ //////////
	
	public function actionInfowidget($id){ ////////////Для вывода в корзине инфо о товаре
	
$model = Products::model()->findByPk($id);
if(isset($model)) $this->renderPartial('include/widget_'.$id, array('model'=>$model));
else {
	 throw new CHttpException(404,'Нет такой службы доставки');
					 exit();
	}

}	//////////public function actionInfo($id){ //////////
	

/*
 * Новый метод для рендера содержимого корзины по Ajax. Вызывается из виджета NavbarTuning-> drawCart;
 * */
public function actionGetcart(){

				$target = Yii::app()->request->getParam('target', null);
				
				$cook=Yii::app()->request->cookies['YiiCart'];
				if (isset($cook)) {
					$models = Cart2Controller::getCokieProducts();					
				}/////////if (isset($cook)) {
				
				
				$render_params = array();
				if(isset($models) AND $models!=null) {
					$render_params['products']=$models;
					$render_params['products_num'] = $this->CalculateCartProducts($models);
				}
				if($target=='topcartcontent' || $target=='topcartcontentmobile')$this->renderPartial('topcart', $render_params); //////////Рендерим для верхнего меню
				elseif($target=='rightcartcontent') $this->renderPartial('cart_cart', $render_params); //////////Рендерим для правой части корзины
				elseif($target=='cartcontentdelivery') $this->renderPartial('cartcontentdelivery', $render_params); ///Рендерим для страницы delivery
}


public function CalculateCartProducts($models){
	$prodnum=0;
	foreach ($models as $prod_id=>$prod){
		$prodnum+=(int)$prod['num'];
	}
	return $prodnum;
}





/*
 * Метод для добавления в корзина по Ajax
 * */	
public function  actionAdd($id){
	if (Yii::app()->request->isAjaxRequest) {
		$kol = Yii::app()->getRequest()->getParam('kol', 1);	
		//echo $this->PROD->product_name;
		
		if ( $this->PRICEVAR!=null) {
				
		    $MyBasket = new MyShoppingCart2($this->PRICEVAR->id, $kol);
		}
			
		$cookie=Yii::app()->request->cookies['YiiCart'];
		if (isset($cookie)){
			$value=$cookie->value;
			//echo "cockies:  ".$value."/<br>";
		}
		
		
	}
	else {
		throw new CHttpException(404,'Ajax only');
		exit();
	}
}



public function actionIndex(){
	$hist = new MyShoppingCart2(); ////Выборка истории просмотра товаров
	$models = Controller::getCokieProducts();
	
	//print_r($models);
	
	$render_params = array();
	if(isset($models) AND $models!=null) $render_params['products']=$models;
	$render_params['recently']=Controller::getHistoryProducts($hist, null);
	
	$this->render('index', $render_params);
}


public function actionClear(){
    
    $cart=new MyShoppingCart2();
    $cart->DeleteCookie();
    
    $this->render('index', null);
}

	
}////////////class
