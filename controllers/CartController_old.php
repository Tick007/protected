<?php

class CartController extends Controller
{
	const PAGE_SIZE=10;

	/**
	 * @var string specifies the default action to be 'list'.
	 */
	public $defaultAction='content';
	public $additional_var; /////////////Определил переменную для вывода в шабло в head

	/**
	 * @var CActiveRecord the currently loaded data model instance.
	 */
	private $_model;
	private $craeted_order;

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'Check_order_epayment + epayment',
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
	
	

	/**
	 * Shows a particular model.
	 */


	public function actionContent()
	{
		//echo '1<br>';

		$cart = Yii::app()->getRequest()->getParam('Cart');
		
		//print_r($cart);
		
		if(isset($cart['delivery_method'])) $delivery_method = $cart['delivery_method'];
		
		if(isset($delivery_method) AND in_array($delivery_method, Yii::app()->params['delivery_mail'])==true) $model = new Cart('mail');
		else $model = new Cart('nomail');
		//$model->addError('newlogin','Имя пользователя занято.');
		
		if (isset($_POST['make_order'])  ) {///////////////Записываем заказ если проверка была успешной
		
		
				if ($model->Set_basket() !="empty") { ///////////////////////////////
				
				//$model->ComposeQuery();
					$validate = $model->validate();
					$validate_adress1 =  $model->validate_adress1();
					
					//var_dump($validate_adress1);
					
					//////////Если метод доставки - самовывоз, то уже не проверяем на остальные поля
					if(isset($delivery_method)==true AND in_array($delivery_method, Yii::app()->params['delivery_samovivoz'])==true AND $validate==true) $model->SaveOrder();
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
						if(isset($delivery_method)==true AND in_array($delivery_method, Yii::app()->params['delivery_samovivoz'])==false ) $validate_adress1 =  $model->validate_adress1();
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
						
						
							if(isset($delivery_method)==true AND in_array($delivery_method, Yii::app()->params['delivery_samovivoz'])==true) {////////////Если самовывоз, то выбираем товар, что бы вывести карту
							$samovivoz_product = Products::model()->findByPk($delivery_method);		
									
							
							}////////if(isset($delivery_method)==tr
						
						//print_r($pictures);
				}
				}///////if(isset($product_ids) AND empty($product_ids)==false AND is_array($product_ids)) {
				

				
				$params_arr = array('form'=>$form, 'models'=>$model->GetCartContent() , 'stores_names'=>$model->get_stores_names(), 'stores_id'=>$model->get_stores_id(), 'products_nums_arr'=>$model->products_nums_arr, 'no_register'=>$model->no_register, 'private_face'=>$model->private_face,  'private_face_mail'=>$model->private_face_mail, 'private_face_labels'=>$model->private_face_labels,  'order_fields'=>$model->order_fields, 'order_field_labels'=>$model->order_field_labels, 'urlico_face'=>$model->urlico_face, 'urlico_labels'=>$model->urlico_labels, 'delivery_method'=>$model->delivery_method , 'model'=>$model, 'pictures'=>@$pictures, 'samovivoz_product'=>@$samovivoz_product);
				
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


	
	/**
	 * Manages all models.
	 */

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
}////////////class
