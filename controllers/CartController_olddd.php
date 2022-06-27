<?php

class CartController extends Controller
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
			'Check_order_epayment + epayment',
			'check_product_existance +add'
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
			$sc = new MyShoppingCart();
			$add_to_basket = Yii::app()->getRequest()->getParam('add_to_basket');
			$num_to_basket = Yii::app()->getRequest()->getParam('num_to_basket');
			//$basket = $this->Add_To_Cart();		
			$products = NULL;	
			
			$sc->changeOrder($add_to_basket, $num_to_basket);
			$products = $sc->getOrder();
			
			
			if(isset($products) && $products!=null){
				
				///////////// выбираем товары по спис
				$criteria=new CDbCriteria;
			    $criteria->addCondition("t.product_visible = 1 ");
				$criteria->order = ' t.id ';
				$criteria->condition = "t.product_visible=1 AND t.product_price>0 AND t.id IN (".implode(',',array_keys($products) ).")";
				$models = Products::model()->findAll($criteria);
				
				
				if(isset($models))  {
					$summa_pokupok = 0;
					$kol_tovarov = 0;
					$cur_summ = 0;
					$alltoweb = NULL;
					$nums = NULL;
					for($i=0, $c=count($models); $i<$c; $i++) {
						//echo $models[$i]->product_name.'<br>';
						$price = FHtml::getProductPrice($models[$i]);
						$count = $products[$models[$i]->id];
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
		if(isset($_POST['CartNew']) && isset($_POST['CartNew']['client_city'])){
			if($_POST['CartNew']['client_city']!=null) {
				$city = $_POST['CartNew']['client_city'];
		
				//echo $_POST['CartNew']['client_city'];
		
				if($city=='15790560') $cart = new CartNew('nomail');
				else{
					//if($city=='15790561') $prods =  Yii::app()->params['delivery_by_kladr']['mo'];
					$CIT = World_adres_cities::model()->findByPk($city);
					//print_r($CIT->attributes);
					if($CIT!=null){
						if($CIT->region_id==15789450 || ($CIT->region_id==15789449))  $prods =  $cart = new CartNew('nomail');
						else $cart = new CartNew('mail');
					}
		
				}
		
			}
			else $cart = new CartNew('nomail');
		}
		else $cart = new CartNew();
		if(isset($_POST['CartNew']))
		{
				
			//print_r($_POST);
				
			$cart->setAttributes($_POST['CartNew']);
			$cart->attributes=$_POST['CartNew'];
			$check = $cart->validate();
			$cart->getProducts();
			if($check==true AND empty($cart->products)==false ) {
				$cart->makeorderSimple();
			}
			
				
		}
		//print_r($cart->attributes);
		
		$cart->getProducts();///////////////делает что-то полезное. Что ХЗ
		
		if(isset($cart->client_city)){
				
		}
		
		$payment_options=array();
		if(isset($cart->delivery) && $cart->delivery!=null){
			$payments = PaymentMethod::getPayments();
			if(isset($payments[$cart->delivery])) $payment_options = $payments[$cart->delivery];
		}
		
		
		
		if(empty($cart->products)==false)$this->render('delivery', array('cart'=>$cart,'payment_options'=>$payment_options));
		else {
			if($cart->orderCompeted == true AND $cart->created_order!=NULL ){
				$this->render('empty', array('model'=>@$cart));
			}
			else $this->render('empty');
		}
	}
	
	////Метод для касаарты  только
	public function actionDeliveryinfo(){
		
		
		if(isset($_POST['CartNew']) && isset($_POST['CartNew']['client_city'])){
			if($_POST['CartNew']['client_city']!=null) {
				$city = $_POST['CartNew']['client_city'];
				
				//echo $_POST['CartNew']['client_city'];
				
				if($city=='15790560') $cart = new CartNew('nomail');
				else{
					//if($city=='15790561') $prods =  Yii::app()->params['delivery_by_kladr']['mo'];
					$CIT = World_adres_cities::model()->findByPk($city);
					//print_r($CIT->attributes);
					if($CIT!=null){
						if($CIT->region_id==15789450 || ($CIT->region_id==15789449))  $prods =  $cart = new CartNew('nomail');
						else $cart = new CartNew('mail');
					}
				
				}
				
			}
			else $cart = new CartNew('nomail');
		}
		else $cart = new CartNew();
		if(isset($_POST['CartNew']))
		{
			
			//print_r($_POST);
			
			$cart->setAttributes($_POST['CartNew']);
			$cart->attributes=$_POST['CartNew'];
			if(isset($_POST['CartNew']['quantity_pereschet']))	$cart->quantity_pereschet = $_POST['CartNew']['quantity_pereschet'];
			$check = $cart->validate();
			$cart->getProducts();
			if($check==true AND empty($cart->products)==false ) {
						$cart->makeorder();
			}
			else {
				/////////////Просто пересчитываем кукисы
				if($cart->quantity_pereschet!=null && empty($cart->quantity_pereschet)==false ) $cart->updateCart();
			}
			
		}
		//print_r($cart->attributes);
		
		$cart->getProducts();///////////////делает что-то полезное. Что ХЗ
		
		if(isset($cart->client_city)){
			
		}
		
		$payment_options=array();
		if(isset($cart->delivery) && $cart->delivery!=null){
			$payments = PaymentMethod::getPayments();
			if(isset($payments[$cart->delivery])) $payment_options = $payments[$cart->delivery];
		}
		
		
		
		if(empty($cart->products)==false)$this->render('delivery', array('cart'=>$cart,'payment_options'=>$payment_options));
		else {
			 if($cart->orderCompeted == true AND $cart->created_order!=NULL ){
				$this->render('empty', array('model'=>@$cart));
			 }
			 else $this->render('empty');
		}
	}
	
	
	/**Новый метод для ньютюнинг (добавил 20.10.2015). Приходит ид товара являющегося способом доставки, нужно отдать способы оплаты
	 * В соответствии со структурой таблиц будем смотреть методы оплаты для физ лиц
	 * 
	 */
	public function actionGetpayment(){ ////////////ajax запрос доступных для города методов доставки из новой корзины
		if (Yii::app()->request->isAjaxRequest) {
			$delivery = Yii::app()->getRequest()->getParam('delivery', NULL);
			//$current = Yii::app()->getRequest()->getParam('current', NULL); /////////текущее значение элемента управления
			if($delivery!=null){
				$payments = PaymentMethod::getPayments();
			}
			if(isset($payments[$delivery])) {
				
				$pm='';
				foreach ($payments[$delivery] as $id=>$name){
					$pm.='<option value="'.$id.'"';
					//if($current==$model->id)$devlist.=' selected';
					$pm.='>'.$name.'</option>';
				}
				if($pm!='') echo $pm;
			}
		}
	}
	/*
	 * Новый метод для ньютюнинг (добавил 20.10.2015). Тут нужно в зависимости от выбранного города по кладр возвращать список доставок
	 * */
	public function actionGetdelivery(){ ////////////ajax запрос доступных для города методов доставки из новой корзины
		if (Yii::app()->request->isAjaxRequest) {
			$city = Yii::app()->getRequest()->getParam('city');
			$current = Yii::app()->getRequest()->getParam('current', NULL); /////////текущее значение элемента управления
			$cart_sum = Yii::app()->getRequest()->getParam('cart_sum', NULL);///цена товаров в корзине
		
			//echo $city.'|' ;///////////Выбранный город
			//echo $current;/////////////Текущий город
			
			if($city=='15790560') $prods = Yii::app()->params['delivery_by_kladr']['msk'];
			else{
				//if($city=='15790561') $prods =  Yii::app()->params['delivery_by_kladr']['mo'];
				$CIT = World_adres_cities::model()->findByPk($city);
				//print_r($CIT->attributes);
				if($CIT!=null){
					if($CIT->region_id==15789450 || ($CIT->region_id==15789449))  $prods =  Yii::app()->params['delivery_by_kladr']['mo'];
					else $prods =  Yii::app()->params['delivery_by_kladr']['rus'];
				}
				
			}
			if(isset($prods)){
				
				$criteria=new CDbCriteria;
				$criteria->order = ' t.product_name ';
				$criteria->condition = "t.id IN (".implode(',', $prods).") AND t.product_visible=1";
				$models=Products::model()->findAll($criteria);
				if($models!=null){
					//$devlist = CHtml::listData($models, 'id', 'product_name');
					$devlist = '<option>Выберете...</option>';
					foreach ($models as $model){
						$devlist.='<option value="'.$model->id.'"';
						if($current==$model->id)$devlist.=' selected';
						$devlist.='>'.$model->product_name;
						if($model->product_price>0) $devlist.=' | '.$model->product_price;
						$devlist.='</option>';
					}
					if($devlist != '') {
						echo $devlist;
						exit();
					}
					
				}
			}
			echo '<option value="0">Не удалось определить опции доставки</option>';
			
		}
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

		$cart = Yii::app()->getRequest()->getParam('Cart');
		
		//print_r($cart);
		
		if(isset($cart['delivery_method'])) $delivery_method = $cart['delivery_method'];
		
		if(isset($delivery_method) AND in_array($delivery_method, Yii::app()->params['delivery_mail'])==true) $model = new Cart('mail');//////////////Дополнительные поля для почтовой отправки
		else $model = new Cart('nomail'); //////////////
		//$model->addError('newlogin','Имя пользователя занято.');
		
		if (isset($_POST['make_order'])  ) {///////////////Записываем заказ если проверка была успешной
		
		
				if ($model->Set_basket() !="empty") { ///////////////////////////////
				
				//$model->ComposeQuery();
					$validate = $model->validate();
					$validate_adress1 =  $model->validate_adress1();
					
					//var_dump($validate_adress1);
					
					//////////Если метод доставки - самовывоз, то уже не проверяем на остальные поля
					if(isset($delivery_method)==true AND ( ! isset(Yii::app()->params['delivery_samovivoz']) || in_array($incoming['delivery_method'], Yii::app()->params['delivery_samovivoz'])==false) AND $validate==true) $model->SaveOrder();
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
				//
				$headers = 'From: '.Yii::app()->params['infoEmail']. "\r\n" ; /////////Менять для проверки не здесь, а ниже
						//$headers.='Content-type: text/html; charset=windows-1251' . "\r\n";
						$headers.='Content-type: text/html; charset=utf8' . "\r\n";
						if(trim($product->belong_category->alias)) $plink = CHtml::link($product->product_name, array('product/details', 'alias'=>$product->belong_category->alias, 'pd'=>$product->id))."&nbsp;&nbsp;&nbsp;http://".$_SERVER['HTTP_HOST'].Yii::app()->createUrl('product/details', array( 'alias'=>$product->belong_category->alias,  'pd'=>$product->id));
						else $plink = CHtml::link($product->product_name, array('product/details', 'pd'=>$product->id))."&nbsp;&nbsp;&nbsp;http://".$_SERVER['HTTP_HOST'].Yii::app()->createUrl('product/details', array(  'pd'=>$product->id));
					$msg_body	= "Сделан заказ на ".$plink."     (id = $product_id). Телефон клиента: $tel<br>
					Клиент: $name<br>
					Город: $city<br>
					email: $email";
						
						
						
						if(@mail(Yii::app()->params['infoEmail'],  'Сделан заказ в один клик на '.$_SERVER['HTTP_HOST'],  $msg_body, $headers))		$this->renderPartial('oneclicksuccess');
						//if(@mail(Yii::app()->params['adminEmail'],  'Сделан заказ в один клик на '.$_SERVER['HTTP_HOST'],  $msg_body, $headers))		$this->renderPartial('oneclicksuccess');
						else echo "Не удалось отправить";
					
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
					$models = CartController::getCokieProducts();					
				}/////////if (isset($cook)) {
				
				$render_params = array();
				if(isset($models) AND $models!=null) {
					$render_params['products']=$models;
					$render_params['products_num'] = $this->CalculateCartProducts($models);
				}
				if($target=='topcartcontent')$this->renderPartial('topcart', $render_params); //////////Рендерим для верхнего меню
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
 * Заполняем корзину куками из БД
 * */
private static function getCokieProducts(){
	$sc = new MyShoppingCart();
	$products_arr = $sc->getOrder();
	
	//print_r($products_arr);
	
	if (isset($products_arr)) {
		$criteria=new CDbCriteria;
		$criteria->select=array( 't.*',  'CONCAT_WS(".", picture_product.picture, picture_product.ext) AS icon',
				'picture_product.picture AS icon_id' , 'picture_product.comments AS attribute_value');
		$criteria->condition = " t.id IN (".implode(',', array_keys($products_arr)).")";
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
						'price'=>FHtml::getProductPrice($product),
						'price_old'=>$product->product_price_old,
						'product_sellout'=>$product->product_sellout,
						'sellout_price'=>$product->sellout_price,
						'new_product'=>$product->new_product,
						'num'=>$products_arr[$product->id],

				);
				
				////Дообрабатываем цены
				
				if($models[$product->id]['product_sellout']==1 && $models[$product->id]['sellout_price']>0) {
					$models[$product->id]['price_old'] =$models[$product->id]['price'];
					//$models[$product->id]['price']=$models[$product->id]['sellout_price'];
					
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


/*
 * Метод для добавления в корзина по Ajax
 * */	
public function  actionAdd($id){
	if (Yii::app()->request->isAjaxRequest) {
		
		//echo $this->PROD->product_name;
		
		if ( $this->PROD!=null) {
				
			$MyBasket = new MyShoppingCart($this->PROD->id, 1);
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
	
	$hist = new MyShoppingCart(); ////Выборка истории просмотра товаров
	require_once ($_SERVER['DOCUMENT_ROOT'].'/protected/controllers/CatalogController.php');
	
	$models = CartController::getCokieProducts();
	
	//print_r($models);
	
	$render_params = array();
	if(isset($models) AND $models!=null) $render_params['products']=$models;
	$render_params['recently']=CatalogController::getHistoryProducts($hist, null);
	
	$this->render('index', $render_params);
}

	
}////////////class
