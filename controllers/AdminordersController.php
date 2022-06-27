<?php

class AdminordersController extends Controller //////////////Главный контроллер администрирования товаров
{
	const PAGE_SIZE=200;/////////////////Количество записей на страницу

	/**
	 * @var string specifies the default action to be 'list'.
	 */
	public $defaultAction='index';

	/**
	 * @var CActiveRecord the currently loaded data model instance.
	 */
	private $_model;


	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'CheckAuthorityList +index, order, orderupdate, create, print',
			'CheckAuthorityUpdateOrders +orderupdate', 
		);
	}

	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'list' and 'show' actions
				'actions'=>array('details'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('index', 'order', 'orderupdate', 'create', 'print'),
				'users'=>array('@'),
			),
			
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
	
	public function  filterCheckAuthority($filterChain)	{//////////Проверка, был ли передан файл
			//echo var_dump(Yii::app()->user->checkAccess('Правка товаров'));
			if (Yii::app()->user->checkAccess('Правка товаров') ) $filterChain->run();
			else {
			throw new CHttpException(401,'У вас нет прав');
			}
		}
		
	public function  filterCheckAuthorityList($filterChain)	{//////////Проверка, был ли передан файл
			//echo var_dump(Yii::app()->user->checkAccess('Правка товаров'));
			if (Yii::app()->user->checkAccess('ViewOrders') ) $filterChain->run();
			else {
			throw new CHttpException(401,'У вас нет прав');
			}
		}
		
	public function  filterCheckAuthorityUpdateOrders($filterChain)	{//////////Проверка, был ли передан файл
			//echo var_dump(Yii::app()->user->checkAccess('Правка товаров'));
			if (Yii::app()->user->checkAccess('ChangeOrderContent') ) $filterChain->run();
			else {
			throw new CHttpException(401,'У вас нет прав');
			}
		}	
	
	public function init() {
        Yii::app()->layout = "admin";
    }
	
	public function get_productiya_path($parent){
				///////////////////////////////Вычисляем путь по дереву продукции
				$Path = Categoriestradex::model()->findbyPk($parent);
				$parent_id = $Path->parent;
				/////////Yii::app()->params->drupal_vars['taxonomy_catalog_level'] - нулевой уровень каталога
				$path_text = CHtml::link($Path->category_name, array('/adminproducts/','group'=>$Path->category_id), $htmlOptions=array ('encode'=>false));
				while ($parent_id>0) {
				
				$Path = Categoriestradex::model()->findbyPk($parent_id);
				$parent_id = $Path->parent;
				//if (trim($parent_id )=='')$parent_id =9;
				$path_text=CHtml::link($Path->category_name, array('/adminproducts/','group'=>$Path->category_id), $htmlOptions=array ('encode'=>false)).' -> '.$path_text;
				}///////while
				$path_text= CHtml::link('Список групп', '/adminproducts/', $htmlOptions=array ('encode'=>false)).' -> '.$path_text;
				return $path_text;
	}/////////////

	public function actionIndex() {///////////////Вывод списка пользователей
	
		
	
		//print_r($_POST);
		$date_from_value  = Yii::app()->getRequest()->getParam('date_from_value', NULL);
		$date_to_value  = Yii::app()->getRequest()->getParam('date_to_value', NULL);
	
		$date_from_arr = explode('/', $date_from_value );

	if(count($date_from_arr)==3) {
		$date_from_sql = $date_from_arr[2].'-'.$date_from_arr[0].'-'. $date_from_arr[1];
	}
		//echo '<br>'.$date_from_sql.'<br>';
		$date_to_arr = explode("/", $date_to_value );
		if(count($date_to_arr)==3) {
		$date_to_sql = $date_to_arr[2].'-'.$date_to_arr[0].'-'. $date_to_arr[1];
		}
		//echo '<br>'.$date_to_sql.'<br>';
		
		
		$criteria=new CDbCriteria;
		
		if (trim($date_from_value) AND trim($date_to_value) ) {
			$criteria->order = 't.id DESC';
			$criteria->condition = " t.recept_date >= :date_from AND t.recept_date  <= :date_to";
			$criteria->params=array(':date_from'=>$date_from_sql, ':date_to'=>$date_to_sql);
		}/////////////if (isset($date_from_value) AND isset($date_from_value)isset($date_from_value) ) {
			else $criteria->order = 't.id DESC';
			
			//print_r($criteria);
		
		$pages=new CPagination(Orders::model()->count($criteria)); 
		$pages->pageSize=self::PAGE_SIZE;
		$pages->applyLimit($criteria);
		//$pages->params=array('section_id'=>$section_id);
		
		$models = Orders::model()->with('client', 'PaymentMethod')->findAll($criteria);
		
		//$gruppy = Categoriestradex::model()->findAll($criteria);//
		
		///////////Постобработка
		for($i=0; $i<count($models); $i++) {
			$new_models[$models[$i]->recept_date][]=$models[$i];
		}
		//$models = $new_models;
		
		
		
		$this->render('index', array('new_models'=>$new_models, 'date_from_value'=>$date_from_value, 'date_to_value'=>$date_to_value, 'pages'=>$pages) );
	}
	
	public function actionPrint(){/////////Печать заказа
		$this->actionOrder(true);
	}
	
	public function actionOrder($print=false){///////////Содержимое заказа
		$order_id = Yii::app()->getRequest()->getParam('id', NULL);
		
		$order = Orders::model()->with('client', 'PaymentMethod', 'OrderContent', 'PaymentFace', 'OrderStatus', 'kontragent', 'documents')->findbyPk($order_id);
		
		$OrderStatuses = OrderStatuses::model()->findAll();
		for($i=0; $i<count($OrderStatuses); $i++)  $statlist[$OrderStatuses[$i]->id]=$OrderStatuses[$i]->description.' ('.$OrderStatuses[$i]->description2.')';
		
		$PaymentFaces = PaymentFaces::model()->findAll();
		for($i=0; $i<count($PaymentFaces); $i++)  $faceslist[$PaymentFaces[$i]->	face_id]=$PaymentFaces[$i]->	face;
		
		$criteria=new CDbCriteria;
		//$criteria->condition = " t.enabled = 1 ";
		$PaymentMethod = PaymentMethod::model()->findAll($criteria);
		for($i=0; $i<count($PaymentMethod); $i++)  $paymetlist[$PaymentMethod[$i]->payment_method_id]=$PaymentMethod[$i]->payment_method_name;
		
		$criteria=new CDbCriteria;
		$criteria->condition = " t.schet = :schet ";
		$criteria->params=array(':schet'=>$order_id);
		$payments = Account_debet::model()->findAll($criteria);

		
		
		if($print==true) {
			 $this->layout='empty';
			 $view = 'print';
		}
		else $view = 'order_content' ;
		$this->render($view,  array('order'=>$order, 'statlist'=>$statlist, 'faceslist'=>$faceslist, 'paymetlist'=>$paymetlist, 'payments'=>@$payments ));
	}/////////////////////public function actionOrder(){///////////Содержимое заказа

	
	public function actionOrderupdate(){
		//print_r($_POST);
		$order_id = Yii::app()->getRequest()->getParam('id', NULL);
		$contents_price = Yii::app()->getRequest()->getParam('contents_price');
		if (isset($_POST['add_product'])) {////////////Добавление новой позици в заказ
			$add_product = trim($_POST['add_product']);
			if (is_numeric($add_product)) {
					$product = Products::model()->findbyPk($add_product);
					$content = new OrderContent;
					$content->id_order = $order_id;
					$content->quantity = 1;
					$content->contents_price = Yii::app()->GP->get_actual_retail(1 , $add_product);
					$content->contents_product = $add_product;
					$content->contents_name = $product->product_name;
					$content->contents_article = $product->product_article;
					try {
						$content->save();
						} catch (Exception $e) {
						 echo 'Caught exception: ',  $e->getMessage(), "\n";
						}////
			}////////////if (is_numeric($add_product)) {
		}//////////if (isset($_POST['add_product'])) {
		
		$summa = 0;
		foreach($_POST['quantity'] as $key=>$num) {
				$content = OrderContent::model()->findbyPk($key);
				if (isset($_POST['del_product'][$key]) AND @$content != NULL ) {
					//////
					try {
						$content->delete();
						} catch (Exception $e) {
						 echo 'Caught exception: ',  $e->getMessage(), "\n";
						}////
				}
				else if(@$content != NULL) {
					$content->quantity = $num;
					if(isset($contents_price) AND isset($contents_price[$key])) $content->contents_price = $contents_price[$key];
					try {
						$content->save();
						} catch (Exception $e) {
						 echo 'Caught exception: ',  $e->getMessage(), "\n";
						}////
					$summa = $summa + round($num*$content->contents_price, 2);
				}////////////else {
		}//////////foreach($_POST['quantity'] as $key=>$num) {
		$order = Orders::model()->with('OrderContent')->findbyPk($order_id);
		$parametrs=$_POST['parametrs'];
		
		if (isset($_POST['create_bill']) AND $order != NULL AND isset($order->OrderContent) ) {////////////Выставление счета
				$doc_type = 6;//////////счет	
				$user = Yii::app()->user->id;
				$D = new Documents ;
				$D->date_dt = date("Y-m-d H:i:s");
				$D->user = $user;
				//$PL->currency = 2;
				$D->doc_status=0;
				$D->doc_type = $doc_type;
				$D->kontragent_id=$order->contragent_id;
				$D->order_id = $order->id;
				$D->store_id = $order->reserv_sklad;
				try {
						$D->save();
						} catch (Exception $e) {
						 echo 'Caught exception: ',  $e->getMessage(), "\n";
						}///////////////////
				for ($i=0; $i<count($order->OrderContent); $i++) {////////перебираем позиции заказа и вставляем в счет
							$GOOD = Products::model()->findByPk($order->OrderContent[$i]->contents_product );
							
							$new_row = new Document_table_part;
							$new_row->product_id = $order->OrderContent[$i]->contents_product;
							$new_row->doc_id = $D->id;
							$new_row->price_no_nds = round($order->OrderContent[$i]->contents_price/(1+$GOOD->nds_out), 3);
							$new_row->num = $order->OrderContent[$i]->quantity;
							$new_row->nds = $GOOD->nds_out;
							
							try {
											$new_row->save(false);
											} catch (Exception $e) {
											 echo 'Caught exception: ',  $e->getMessage(), "\n";
											}//////////////////////
				}///////for ($i=0; $i<count($order->OrderContent); $i++) {////////перебираем позиции заказа и вставляем в счет
				
		}////////////////if (isset($_POST[create_bill]) AND $order != NULL ) {////////////Выставление счета
		
		if (isset($_POST['delete_urlico_link'])) $parametrs[contragent_id]=NULL;
		$parametrs['summa_pokupok'] = $summa;
				try {
					$order->saveAttributes($parametrs);
					} catch (Exception $e) {
					 echo 'Caught exception: ',  $e->getMessage(), "\n";
					}////
		$this->redirect("/adminorders/order/".$order_id, true, 301);
	}/////////////////function
	
	
	public function actionCreate(){///////////////Создание заказа и нового пользователя и переход к нему
	
		$client =  new Clients;
		$client->save();
		$client->login = $client->id;
		$client->client_email = Yii::app()->params['infoEmail'];
		$client->client_password = md5($client->id);
		$client->save();
		
		
		$order = new Orders;
		$order->recept_date = @date("Y-m-d");
		$order->recept_time = @date("H:i:s");
		$order->id_client = $client->id;
		$order->save();	
		
		if(isset($order->id)) {
			$url=Yii::app()->createUrl('adminorders', array('order'=>$order->id));
			$this->redirect($url, true, 301);
		}
	
	}////////////////
	
}////////////////class


	
