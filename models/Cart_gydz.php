<?
class Cart extends CFormModel
{
	public $username;
    public $password;
    public $rememberMe=false;
	public $myselect;
	public $myselect_data;
	
		var $connection;
		var $query;
		var $query1; ////////////для выборки складов
		var $stores_id;////////Массив для хранения id складов
		var $stores_names;////////Массив для хранения имен складов 
		var $stores_data;
		var $command;
		var $dataReader;
		var $row;
		
		private $basket;///////////Здесь будет храниться  заказ из куки
		public $product_ids_arr; //////////идентификаторы товаров заказа
		public $product_num_arr; //////////идентификаторы товаров заказа
		public $products_nums_arr;
		
		private $product_delete;//////////////Массив флагов на удаление
		private $quantity_pereschet;///////////////Массив содержащий значения ид, кого пересчитывать
		public $no_register;/////////////////////Признак оформления без регистрации
		public $exit_register;/////////////////Признак выхода
		
		public $reserv_sklad;
		public $payment_face;
		private $payment_face_data;
		
		public $payment_method;
		public $payment_method_data;
		
		public $delivery_method;
		public $delivery_method_data;
		
		
		public $client_email;
		public $first_name;
		public $second_name;
		public $client_tels;
		public $client_post_index;
		public $client_passport;
		
		public $private_face=array('client_email', 'first_name', 'second_name', 'client_tels');
		public $private_face_labels=array('Email', 'Имя', 'Фамилия', 'Телефоны', 'Почтовый индекс', 'Серия, № паспорта, <br>кем, когда выдан');
		
		public $urlico_face=array('urlico_txt', 'first_name', 'client_email',  'client_tels');
		public $urlico_labels=array('Название организации', 'Контактное лицо', 'Email', 'Телефоны/факс');
		
		
		public $urlico;
		public $urlico_txt;
		public $order_adress1;
		public $order_adress2;
		public $primechanie;
		
		public $order_fields=array('order_adress1', 'order_adress2', 'primechanie');
		public $order_field_labels=array('Адрес доставки', 'Дополнительня инфомация', 'Примечание');
		
		private $skidka_ps;////////////////Процент скидки
		private $skidka_sum_shop_currency;/////////////////Скидка в валюте магазина
		private $sum_all_for_save;
	
		public $make_order;///////////Кнопка оформления закза
		private $el_ar;
		public $created_order; ////////////Признак что заказ был создан
		//public $Cart;
					
	
		public function rules(){
			return array(
				// username and password are required
				array('reserv_sklad',  'check_ostatki'),
				// password needs to be authenticated
				array('first_name, client_email, client_tels, delivery_method, payment_method', 'required'),
					// email has to be a valid email address
				array('client_email', 'email'),
				
			);
		}
		
		public function attributeLabels()
			{
				return array(
					'client_tels'=>'Телефоны',
					'order_adress1'=>'Адрес доставки',
					'client_email'=>'Email',
					'first_name'=>'Имя',
					'delivery_method'=>'Способ получения',
					'payment_method'=>'Способ оплаты',
				);
			}


		
		public function check_ostatki() {////////Проверка остатков
		
			if (isset(Yii::app()->params['make_reserv_at_order']) AND Yii::app()->params['make_reserv_at_order']==true AND  trim($this->reserv_sklad)!='') {
					//echo 'werwer';
					//echo 'резерв = '.$this->reserv_sklad.'<br>';
					//print_r($this->basket);
					//$qqq = explode(':', $this->basket);
					//print_r($this->product_ids_arr);
					//print_r($this->products_nums_arr);
					$bask_cont_arr = $this->products_nums_arr;
					//print_r($bask_cont_arr);
					//exit();
					$check_real_ostatki = FHtml::product_ostatok_by_store ($this->product_ids_arr,  $this->reserv_sklad);
					if(isset($check_real_ostatki) AND empty($check_real_ostatki)==false) {
						//print_r($check_real_ostatki);
						for ($k=0; $k<count($check_real_ostatki); $k++) {
							if ($bask_cont_arr[$check_real_ostatki[$k]['id']]>$check_real_ostatki[$k]['prod_quant']) {
								///////	$this->addError('username','Username is incorrect.');
								$prod = Products::model()->findByPk($check_real_ostatki[$k]['id']);
								$this->addError($prod->product_name,$prod->product_name.': недостаточно на складе - '.$check_real_ostatki[$k]['prod_quant']);
								}
						}
					}
			}
		
		}////////private function check_ostatki() {
				
			
			function Set_basket() {/////////////////////////Установка private $basket; для выбора критериев
			$cookie=Yii::app()->request->cookies['YiiCart'];
					if (isset($cookie) AND trim($cookie->value)<>'') {
					$this->basket=$cookie->value;
					$basket_content = NULL;
					//echo "Заказ = ".$this->basket."<br>";
					if(substr($this->basket, strlen($this->basket)-1,1)=="#") $this->basket = substr($this->basket, 0, strlen($this->basket)-1);
					$qqq=explode("#",$this->basket);
									for ($i=0; $i<count($qqq); $i++) {//////////////
									$qqq2=explode(":",$qqq[$i]);
									$id=$qqq2[0];
									$num=$qqq2[1];
									if(isset($this->product_delete[$id])) $this->quantity_pereschet[$id]=0;/////Обработка checkbox
									if (isset($this->quantity_pereschet[$id]) AND is_numeric($this->quantity_pereschet[$id])==true AND $this->quantity_pereschet[$id]>0) $this->product_num_arr[]=$this->quantity_pereschet[$id];
									else if (isset($this->quantity_pereschet[$id]) AND $this->quantity_pereschet[$id]<=0) continue;
									else	$this->product_num_arr[]=$num;
									$this->product_ids_arr[]=$id;
							}
					}
					
					if (isset($this->product_ids_arr)) {
					//$this->product_ids_arr = array_unique($this->product_ids_arr);
					$this->products_nums_arr=array_combine($this->product_ids_arr, $this->product_num_arr);
					//print_r($this->products_nums_arr);
							//echo "<br>";
					}
					else return "empty";////////////// Здесь может быть нуль при удалении всех позиций, значит после этой функции нужно делать редирект на другой view
			}////////////function Set_basket(){/////////////////////////Установка private $basket; для выбора критериев			

public function SaveCookie() {
		   			$new_basket=NULL;	
					if (isset ($this->product_ids_arr)) {
							for ($i=0; $i<count($this->product_ids_arr); $i++) {
									$new_basket.=$this->product_ids_arr[$i].':'.$this->product_num_arr[$i].'#';
							}
					}
					$cookie =new CHttpCookie('YiiCart', $new_basket); // sends a cookie
					$cookie->expire= time()+60*60*24*30; ///////////30 дней
					Yii::app()->request->cookies['YiiCart']=$cookie;
			}////////////private function SaveCookie() {	
	
		function __construct(){
			$this->connection = Yii::app()->db;

		////////////////////////////////////////////////////////////////////////////////////////////////////////Инициализируем приход с формы
					if (isset($_POST['Cart'])) 
					{
							$incoming = $_POST['Cart'];
							while (list($key, $val) = each($incoming)) {
									if (@isset($incoming[$key])) {
									$this->$key = $incoming[$key];
									//echo $key.": ".$incoming[$key]."<br>";
									}
									else  $this->$key=NULL;	
							}
					}
					//echo "Массив пересчета = ";
					//print_r($this->quantity_pereschet);
			//}/////////////////////////////////////////////////////////////

			$this->CheckEnter();////////////////Проверяем вошел пользователь или оформляет заказ без регистрации   
				/////////////////////////////Инициализируем склады
			 $this->query1= "SELECT id, name FROM stores WHERE kontragent_id = ".Yii::app()->GP->GP_self_contragent." AND show_in_html=1  ORDER BY is_main DESC";
			$this->command=$this->connection->createCommand($this->query1);
			$this->dataReader=$this->command->query();
			while(($this->row=$this->dataReader->read())!==false) {
			$this->stores_id[]=$this->row['id'];
			$this->stores_names[]=$this->row['name']; 
			}
			$this->stores_data=array_combine( $this->stores_id, $this->stores_names );
			
			$this->query1= "SELECT face_id, face FROM payment_faces ";
			if(isset(Yii::app()->params['pay_faces'])) $this->query1.=" WHERE face_id IN (".implode(',', Yii::app()->params['pay_faces']).")";
			
			$this->command=$this->connection->createCommand($this->query1);
			$this->dataReader=$this->command->query();
			$this->payment_face_data[0] = '...выбор';
			while(($this->row=$this->dataReader->read())!==false) {
					$this->payment_face_data[$this->row['face_id']] = $this->row['face'];
					//$payment_face_data_values[] = $this->row['face_id'];
			}
			//if ()
			
			$this->el_ar=array(
        'username'=>array(
            'type'=>'text',
			'value'=>'просто текст',
            'maxlength'=>32,
        ),
        'password'=>array(
            'type'=>'password',
            'maxlength'=>32,
        ),
    
	    'rememberMe'=>array(
            'type'=>'checkbox',
        ),
		
		'payment_face'=>array (
		'type'=>'dropdownlist',
		'items'=>$this->payment_face_data,
		'label'=>'Покупатель',
		'onchange'=>"{form_checkout(this.id,this.value)}",
		),
		
		'reserv_sklad'=>array (
		'type'=>'dropdownlist',
		'items'=>$this->stores_data,
		'label'=>'Резервировать на склад',
		'onchange'=>"{form_checkout(this.id,this.value)}",
		),
		
		'client_email'=>array (
		'type'=>'text',
		'label'=>'',
		'maxlength'=>100,
		'value'=>isset($this->client_email) ? $this->client_email :  '',
		'class'=>'cart_textfield',
		),
		
		'first_name'=>array (
		'label'=>'',
		'type'=>'text',
		'maxlength'=>100,
		'value'=>isset($this->first_name) ? $this->first_name:   '',
		'class'=>'cart_textfield',
		),
		
		'second_name'=>array (
        'type'=>'text',
		'label'=>'',
		'maxlength'=>100,
		'value'=>isset($this->second_name) ? $this->second_name:   '',
		'class'=>'cart_textfield',
		),
		
		'client_tels'=>array (
		'type'=>'text',
		'label'=>'',
		'maxlength'=>150,
		'value'=>isset($this->client_tels) ? $this->client_tels:   '',
		'class'=>'cart_textfield',
		),
		
		'client_post_index'=>array (
		'type'=>'text',
		'label'=>'',
		'maxlength'=>10,
		'value'=>isset($this->client_post_index) ? $this->client_post_index :   '',
		),
		
		'client_passport'=>array (
		'type'=>'text',
		'label'=>'',
		'maxlength'=>255,
		'value'=>isset($this->client_passport) ? $this->client_passport :   '',
		),
		
		'urlico'=>array (
		'type'=>'text',
		'label'=>'',
		'value'=>isset($this->urlico) ? $this->urlico :  0,
		),
		
		'urlico_txt'=>array (
		'type'=>'text',
		'label'=>'',
		'value'=>isset($this->urlico_txt) ? $this->urlico_txt :   '',
		),
		
		'order_adress1'=>array (
		'type'=>'textarea',
		'label'=>'',
		'cols'=>'30',
		'rows'=>'3',
		'value'=>isset($this->order_adress1) ? $this->order_adress1 :   '',
		),
		
		'order_adress2'=>array (
		'type'=>'textarea',
		'label'=>'',
		'cols'=>'30',
		'rows'=>'3',
		'value'=>isset($this->order_adress2) ? $this->order_adress2 :   '',
		),
		
		'primechanie'=>array (
		'type'=>'textarea',
		'label'=>'',
		'cols'=>'30',
		'rows'=>'3',
		'value'=>isset($this->primechanie) ? $this->primechanie :   '',
		'class'=>'cart_textarea',
		),
			
    );
			
			
			if (!isset($this->exit_register)) {
			if(isset($this->payment_face) AND $this->payment_face>0) $this->query1= "SELECT payment_method_id , payment_method_name FROM payment_method WHERE payment_face = ".$this->payment_face." AND enabled = 1";
			else $this->query1= "SELECT payment_method_id , payment_method_name FROM payment_method WHERE payment_face = 1 AND enabled = 1";
			
			
			$this->command=$this->connection->createCommand($this->query1);
			$this->dataReader=$this->command->query();
			$this->payment_method_data[0]= '...выбор';
			while(($this->row=$this->dataReader->read())!==false) {
			$this->payment_method_data[$this->row['payment_method_id']] = $this->row['payment_method_name'];
			$payment_method_data_values[]=$this->row['payment_method_id'];
			}
			if ( isset ($this->payment_method) AND isset($payment_method_data_values) AND in_array($this->payment_method,  $payment_method_data_values)==false) 
					{
							$this->payment_method = 0;
					}

			//print_r($this->payment_method_data);
			$data = array( 'payment_method'=>array (
		'type'=>'dropdownlist',
		'items'=>$this->payment_method_data,
		'label'=>'Метод оплаты',
		'onchange'=>"{form_checkout(this.id,this.value)}",
		));
					$this->el_ar = array_merge ($this->el_ar, $data);
			}//////////if (isset($this->payment_face)) {
			
			if (isset($this->payment_face) AND $this->payment_face>0 AND  isset ($this->payment_method) AND $this->payment_method>0 AND !isset($this->exit_register)) {
			$this->query1= "SELECT nomenklatura_list FROM payment_method WHERE payment_method_id = ".$this->payment_method;
			$this->command=$this->connection->createCommand($this->query1);
			$this->dataReader=$this->command->query();
			$this->row=$this->dataReader->read();
			//$this->payment_method_data[0]= '...выбор';
			 $nomenklatura_array = explode('#',trim($this->row['nomenklatura_list']));
			 $this->delivery_method_data[0]='...выбор';
			 for ($i=0; $i<count($nomenklatura_array); $i++) {
					$this->delivery_method_data[$nomenklatura_array[$i]] = Yii::app()->GP->getproductname($nomenklatura_array[$i]) ;
					//$this->delivery_method_data[$nomenklatura_array[$i]];
					$delivery_method_data_values[] = $nomenklatura_array[$i];
			}////for ($i=0; $i<count($nomenklatura_array); $i++) {
				if ( isset ($this->delivery_method) AND in_array($this->delivery_method,  $delivery_method_data_values)==false) 
					{
							$this->delivery_method = 0;
					}
				
				//echo 	$this->delivery_method;
				$data=array('delivery_method'=>array (
				'type'=>'dropdownlist',
				'items'=>$this->delivery_method_data,
				'label'=>'Способ получения',
				'onchange'=>"{form_checkout(this.id,this.value)}",
				));
				$this->el_ar = array_merge ($this->el_ar, $data);
			}//////if (isset($this->payment_face) AND isset ($this->payment_method)) {
				
			///////////////////////////////////////////////Проверка no_register
					 
					
				}/////////////////////////////	function __construct(){

	function CheckEnter(){
					$session=new CHttpSession;
			    	 $session->open();
					// echo "NO_RE = ".$session['NO_REG']."<br>";
					 //echo "Yii::app()->user->isGuest = ".Yii::app()->user->isGuest."<br>";
					// echo "this->no_register = ".$this->no_register."<br>";
					  if (Yii::app()->user->isGuest AND $this->no_register) {
					  //echo  "присваиваем";
					 	 $session['NO_REG']=1;  //////////////// get session variable 'name1'
					  }////// if (Yii::app()->user->isGuest AND $this->no_register) {
					 elseif(isset(Yii::app()->params['cart']) AND isset(Yii::app()->params['cart']['oformlenie_separate']) AND $this->no_register) {
						
						$session['NO_REG']=1; 	
					}
					 elseif (is_numeric(Yii::app()->user->getId()) ) {///////////////ТТ.е. пользователь вошел
					  /////////////!isset($_POST['make_order'], т.е. когда нажали на кнопку оформить, не нужно что бы значения клиента
					  /////////// из AR присваивались форме
					  		if (!isset($_POST['make_order'])) {
									$AR_Client  = Clients::model()->find('id=:ID', array(':ID'=>Yii::app()->user->getId() ));//
									$this->client_email = $AR_Client->client_email;
									$this->first_name = $AR_Client->first_name;
									$this->second_name = $AR_Client->second_name;
									$this->client_tels = $AR_Client->client_tels;
									$this->client_post_index = $AR_Client->client_post_index;
									$this->client_passport = $AR_Client->client_passport ;
									$this->urlico_txt = $AR_Client->urlico_txt;
									$this->order_adress1 = $AR_Client->client_post_index.', '.$AR_Client->client_country.', '.$AR_Client->client_oblast.', '.$AR_Client->client_district.', г.'.$AR_Client->client_city;
									$this->order_adress2 = 'ул.'.$AR_Client->client_street.', д.'.$AR_Client->client_house.', строение '.$AR_Client->client_stroenie.', корп.'.$AR_Client->client_korpus.', кв.'.$AR_Client->client_apart.', этаж '.$AR_Client->client_flore.', подъезд '.$AR_Client->client_entrance.', домофон '.$AR_Client->client_code	;
									$this->primechanie = $AR_Client->client_comments;
							}/////////if (!isset($_POST['make_order'])) {
					  }////////////elseif (is_numeric(Yii::app()->user->getId() ) {///
					  
					  
					  if (isset($this->exit_register) ) {
					  $session['NO_REG']=NULL;
					  }/////////  if (isset($this->exit_register)) {
					  //echo "NO_RE = ".$session['NO_REG'];
					   $session->close();
					  $this->no_register = $session['NO_REG'];
					  /*
					  print_r($session);
					  echo '<br>';
					  var_dump($this->no_register);
					  */
			}
			/*
			function assign_default_client_values() {//////////Вызываем только до момента перед нажатием кнопки оформить
					if (is_numeric(Yii::app()->user->getId() ) AND !) {///////////////ТТ.е. пользователь вошел
							$AR_Client  = Clients::model()->find('id=:ID', array(':ID'=>Yii::app()->user->getId() ));//
							$this->client_email = $AR_Client->client_email;
							$this->first_name = $AR_Client->first_name;
							$this->second_name = $AR_Client->second_name;
							$this->client_tels = $AR_Client->client_tels;
							$this->client_post_index = $AR_Client->client_post_index;
							$this->client_passport = $AR_Client->client_passport ;
						}//////////if (is_numeric(Yii::app()->user->getId() ) AND !) {//
			}///////////function assign_default_client_values () {
			*/
			
			function ClearBasket() {//////////очистка корзины
					$cookie=Yii::app()->request->cookies['YiiCart'];
					//print_r($cookie);
					Yii::app()->request->cookies['YiiCart']=NULL;
			}/////////function ClearBasket() {////////
			

	
			private function send_mail($order_id){///////////////Отправка сообщений
				
					
					if(isset(Yii::app()->params['delivery_samovivoz'])) $limit_prods = Yii::app()->params['delivery_samovivoz'];
					if(isset($limit_prods) AND isset(Yii::app()->params['delivery_dostavka'])) $limit_prods = array_merge($limit_prods, Yii::app()->params['delivery_dostavka']);
					elseif(isset(Yii::app()->params['delivery_dostavka'])) $limit_prods = Yii::app()->params['delivery_dostavka'];
					
					/*
					print_r(Yii::app()->params['delivery_samovivoz']);
					echo '<br>';
					print_r(Yii::app()->params['delivery_dostavka']);
					echo '<br>';
					print_r($limit_prods);
					*/
				
					  $msg_body = "Номер заказа: №".$order_id."<br>\r\n"; 
					  $msg_body.="Здравствуйте ".$this->first_name."<br>\r\n";
					  $msg_body.="Поступил заказ<br>\r\n";
					  
					  	$models = OrderContent::model()->findAllByAttributes(array('id_order'=>$order_id));
					  $msg_body.="<table  border=\"0\" cellspacing=\"1\" cellpadding=\"1\" bgcolor=\"#000000\">";
					$msg_body.="<thead><tr bgcolor=\"#FFFFFF\"><th align=\"center\"><strong>Товар</strong></td><th align=\"center\"><strong>Количество</strong></td><th align=\"center\"><strong>Цена</strong></td><th align=\"center\"><strong>Стоимость</strong></td></tr></thead>";
						$delivery=0;
						for ($i=0; $i<count($models); $i++) {/////////Перебор содержимого заказ
							if (trim($models[$i]->field_id) ) {
								if( (isset($limit_prods) AND in_array($models[$i]->contents_product, $limit_prods)==false) OR isset($limit_prods)==false ){
									$msg_body.="<tr bgcolor=\"#FFFFFF\"><td>&nbsp;".CHtml::link($models[$i]->contents_name, array('product/details', 'pd'=>$models[$i]->contents_product), array('target'=>'_blank'))."</td>";
									$msg_body.="<td>&nbsp;&nbsp;&nbsp;".$models[$i]->quantity." шт.</td>";
									$msg_body.="<td>&nbsp;&nbsp;&nbsp;".$models[$i]->contents_price."</td>";
									$msg_body.="<td align=\"right\">&nbsp;&nbsp;&nbsp;".round($models[$i]->contents_price*$models[$i]->quantity, 2)." руб.</td>";
									$msg_body.="</tr>";
									$tr_sum = round($models[$i]->contents_price*$models[$i]->quantity, 2);
									if(isset($sum))$sum = $sum + $tr_sum;
									else $sum = $tr_sum;
								}/////////if( (isset($limit_prods) AND in_array($
								else {////////////Это доставка
									$delivery = $delivery + $models[$i]->contents_price;
									$msg_body.="<tr bgcolor=\"#FFFFFF\"><td colspan=\"3\" align=\"right\"><strong>Итого:&nbsp;</strong></td><td align=\"right\" >".$sum." руб.</td></tr>";
								$msg_body.="<tr bgcolor=\"#FFFFFF\"><td colspan=\"3\" align=\"right\">Доставка:&nbsp;".$models[$i]->contents_name."</td><td align=\"right\" >".$models[$i]->contents_price." руб.</td></tr>";
								$msg_body.="<tr bgcolor=\"#FFFFFF\"><td colspan=\"3\" align=\"right\"><strong>Итого (с учётом доставки):&nbsp;</strong></td><td align=\"right\" >".($sum+$models[$i]->contents_price)." руб.</td></tr>"; 
								}
							}
						}////////////for ($i=0; $i<count($order); $i++) {
						if($delivery>0) {
							$order = Orders::model()->findByPk($order_id);
							$order->dostavka 	 = $delivery;
							$order->save();
						}
					
						 $msg_body.=" </table><br><br>";
						 
					  $msg_body.= "Форма оплаты ".$this->payment_method_data[$this->payment_method]."<br>\r\n";	 
						 
					$msg_body.= "<strong>Информация о заказчике:</strong><br>\r\n";	
					$msg_body.="<table  border=\"0\" cellspacing=\"1\" cellpadding=\"1\" bgcolor=\"#000000\">";
					$msg_body.="<tr bgcolor=\"#FFFFFF\"><td><strong>ФИО</strong></td><td>".$this->first_name.' '.$this->second_name."</td></tr>"; 
					$msg_body.="<tr bgcolor=\"#FFFFFF\"><td><strong>E-mail</strong></td><td>".$this->client_email."</td></tr>"; 
					$msg_body.="<tr bgcolor=\"#FFFFFF\"><td><strong>Контактный телефон</strong></td><td>".$this->client_tels."</td></tr>"; 
					$msg_body.="<tr bgcolor=\"#FFFFFF\"><td><strong>Адрас доставки</strong></td><td>".$this->order_adress1."</td></tr>"; 
					$msg_body.="<tr bgcolor=\"#FFFFFF\"><td><strong>Комментарий</strong></td><td>".$this->primechanie."</td></tr>"; 
					 $msg_body.=" </table><br><br>";
					  
					 // $msg_body.="Телефон ".$this->client_tels."<br>\r\n";
					//  $msg_body.="Комментарий к заказу ".$this->order_adress1."<br>\r\n";
					//  $msg_body.="Плательщик ".$this->payment_face_data[$this->payment_face]."<br>\r\n";
					
					//  $PM = PaymentMethod::model()->findByPk($this->payment_method);
					//  $msg_body.= $PM->message."<br>\r\n";
					  
					//  for ($i=0; $i<count($AR_Client->profile_values); $i++) {
					//  		$msg_body.= $fields[$AR_Client->profile_values[$i]->fid].' - '.$AR_Client->profile_values[$i]->value."<br>\r\n";
					//  }
					
					
					$headers = 'From: '.Yii::app()->params['infoEmail']. "\r\n" ;
					$headers.='Content-type: text/html; charset=windows-1251' . "\r\n";
					//mail(Yii::app()->params['sendOrderEmail'], 'Ваш заказ на novline.com', $msg_body, $headers);
					//mail('info@autofides.ru', 'Ваш заказ на novline.com', $msg_body, $headers);
					@mail($this->client_email, iconv( "UTF-8", "CP1251",'Ваш заказ №'.$order_id.' на ').$_SERVER['HTTP_HOST'], iconv( "UTF-8", "CP1251",$msg_body), $headers);
					@mail(Yii::app()->params['infoEmail'],  iconv( "UTF-8", "CP1251", 'Сделан заказ №'.$order_id.' на ').$_SERVER['HTTP_HOST'],  iconv( "UTF-8", "CP1251",$msg_body), $headers);
					//mail('tick007@yandex.ru', 'Копия заказа на  '.$_SERVER['HTTP_HOST'], $msg_body, $headers);
				
				//echo $msg_body;
				//exit();
				
			}////////////////////
			
			private function get_skidka() {
			/////Смотрим скидку
			$query="SELECT discounts_conditions.discount_ps   FROM discounts_conditions JOIN 
			discounts   ON discounts_conditions.discount_id =  discounts.id  WHERE discounts.currency = ".Yii::app()->GP->GP_shop_currency."  AND ".$this->sum_all_for_save." >= discounts_conditions.bolshe_ravno AND  ".$this->sum_all_for_save."< discounts_conditions.menshe  ";
			//echo $query;
			$this->command=$this->connection->createCommand($query)	;
			$this->dataReader=$this->command->query();
			$def=$this->dataReader->read();
			if (isset($def['discount_ps']))	$this->skidka_ps = $def['discount_ps'];
			else $this->skidka_ps = 0;
			$this->skidka_sum_shop_currency=round(($this->sum_all_for_save*($this->skidka_ps/100)),2);
			//echo $this->skidka_ps;
			}//////////	private function get_skidka() {
	
			public function SaveOrder() {
			if ($this->no_register)  {////////Записываем для нового физлица
									$AR_Client =  new Clients;
			}//////////if ($this->no_register)  {/
			else {
					if (is_numeric(Yii::app()->user->getId() ))
							{
									$AR_Client  = Clients::model()->find('id=:ID', array(':ID'=>Yii::app()->user->getId() ));//
							}
					}/////////////////else {
					
			foreach($this->private_face AS $key){	
					if (isset($this->$key)) $AR_Client->$key = $this->$key;
			}
			
			foreach($this->urlico_face AS $key){	
					if (isset($this->$key)) $AR_Client->$key = $this->$key;
			}
			
			
			$AR_Client->save();
			if ($this->no_register) {
				$AR_Client->login = $AR_Client->id;
				$AR_Client->save();///////////////////Сохраняем в логине уникальный идентификатор
			}
			
			$this->ComposeQuery();
			
			/////////////////////////////////////////////////Теперь пишем заказ
			$AR_Order = new Orders;
			$AR_Order->recept_date = @date("Y-m-d");
			$AR_Order->recept_time = @date("H:i:s");
			if(isset(Yii::app()->user->id)) $AR_Order->id_client = Yii::app()->user->id;
			else 	$AR_Order->id_client = $AR_Client->id;
			$AR_Order->payment = $this->payment_method;
			$AR_Order->payment_face = $this->payment_face;
			$AR_Order->primechanie = $this->primechanie;
			$AR_Order->order_adress1 =  $this->order_adress1;
			$AR_Order->order_adress2 = $this->order_adress2;
			$AR_Order->reserv_sklad = $this->reserv_sklad;
			$AR_Order->currency = Yii::app()->GP->GP_shop_currency;
			$AR_Order->contragent_id = $AR_Client->urlico;
			$AR_Order->currency_rate = Yii::app()->GP->get_currency_rate();
			$AR_Order->save();			
			$order_id = $AR_Order->id;
			///////////////////////И содержимое заказа
			//print_r($this->GetCartContent () );
			$cart_content = $this->GetCartContent ();
			$this->sum_all_for_save = 0;
			for ($i=0; $i<count($cart_content); $i++) {
							//	print_r($cart_content[$i]);
						$OrdCont = new OrderContent(); 
						$OrdCont->id_order = 	$AR_Order->	id;
						$OrdCont->contents_product = $cart_content[$i]['id'];
						$OrdCont->quantity = $this->products_nums_arr[$OrdCont->contents_product];
						if (isset($cart_content[$i]['price_with_nds'])) $OrdCont->contents_price = $cart_content[$i]['price_with_nds'];
						else{
							// $OrdCont->contents_price = $cart_content[$i]['price_card'];
							$OrdCont->contents_price = Yii::app()->GP->get_actual_retail(1,$cart_content[$i]['id'] );
						}
						$OrdCont->contents_name = $cart_content[$i]['product_name'];
						$sum_this = $OrdCont->quantity*$OrdCont->contents_price;
			  			$this->sum_all_for_save = $this->sum_all_for_save + $sum_this;
						$OrdCont->save();
						}
						//////////////////////Добавляем стоимость доставки
							$OrdCont = new OrderContent(); 
							$OrdCont->id_order = 	$AR_Order->	id;
							$OrdCont->contents_product = $this->delivery_method;
							$OrdCont->quantity = 1;
							$OrdCont->contents_price = Yii::app()->GP->get_actual_retail(1, $this->delivery_method);
							$OrdCont->contents_name = Yii::app()->GP->getproductname($this->delivery_method);
							$sum_this = $OrdCont->quantity*$OrdCont->contents_price;
							$this->sum_all_for_save = $this->sum_all_for_save + $sum_this;
							$OrdCont->save();
							  
						///////////////////////////
						if ( Yii::app()->GP->GP_enable_discounts) {
								$this->get_skidka();
								$AR_Order = Orders::model()->find('id=:orderID', array(':orderID'=>$order_id));////после save нужно заново открывать
								$AR_Order->skidka = $this->skidka_sum_shop_currency;
								$AR_Order->skidka_ps = $this->skidka_ps;
						}////////if ( Yii::app()->GP->GP_enable_discounts) {
						$AR_Order->summa_pokupok = $this->sum_all_for_save;
						$this->created_order = $order_id;
						$AR_Order->save();
						
						if (isset(Yii::app()->params['make_reserv_at_order']) AND Yii::app()->params['make_reserv_at_order']==true AND isset(Yii::app()->params['reserv_sklad']) ) {
								
								//$doc_id = create_new_document(3, $cn, 0); 
								$doc = new Documents;
								$doc->doc_type = 3;
								$doc->date_dt =  date('Y-m-d H:s:i');
								$doc->date_int =time();
								$doc->kontragent_id = Yii::app()->params['self_contragent'];
								$doc->store_id = Yii::app()->params['main_sklad'];
								$doc->store_id_ca = Yii::app()->params['reserv_sklad'];
								$doc->order_id = $order_id;
								try {
									$doc->save();
									} catch (Exception $e) {
									 echo 'Ошибка сохранения: ',  $e->getMessage(), "\n";
									}//////////////////////
								if (isset($doc)) {//////////////
								
									
								
									for ($i=0; $i<count($cart_content); $i++) {
										
												$doc_cont = new Document_table_part;
												$doc_cont->doc_id = $doc->id;
												$doc_cont->product_id = $cart_content[$i]['id'];
														//$doc_cont->price_no_nds
												//$doc_cont->nds
												$doc_cont->num = $this->products_nums_arr[$cart_content[$i]['id']];
												try {
													$doc_cont->save();
													} catch (Exception $e) {
													 echo 'Ошибка сохранения: ',  $e->getMessage(), "\n";
													}//////////////////////
									}//////for ($i=0; $i<count($cart_content); $i++) {
									$DD = new Documents_details($doc->id);	
									$errors = $DD->ProvedenieRashod_Trasfer();
									if ($errors!='') {
										throw new CHttpException(301,'Ошибка резервирования');
									}
									else $doc->doc_status = 2;
									$doc->save();
								}///////if (isset($doc)) {///
								
								
									
						}
						
						//////////////Удаляем содержимое карзины
						
						$this->product_ids_arr=NULL;
						$cookie =new CHttpCookie('YiiCart', NULL);
						$cookie->value=NULL;
						Yii::app()->request->cookies['YiiCart']=$cookie;
						$this->SaveCookie();
						
						 $this->send_mail($order_id);
						
			}///////function
	
			
			
			public function ComposeQuery() {
			$query="SELECT products.id, 	CONCAT_WS(',', products.product_name , product_attribute.attribute_value) AS product_name, 		  products.product_short_descr,  price_list.price_with_nds, products.product_dlina, products.product_shirina,  	products.product_visota, products.product_ves, products.category_belong, products.product_article, products.product_price As price_card ";
			for($k=0;$k<count($this->stores_id);$k++) {
			$kk=$k+1;
			$query.=", store".$kk.".quantity AS prihod_store".$kk.",  0 AS rashod_store".$kk."  ";
			}
		
			$query.="FROM  products
			 LEFT JOIN  ";
			 for($k=0;$k<count($this->stores_id);$k++) {
			$kk=$k+1;
			$query.=" (SELECT parent_categories.category_name AS sgr, products.product_name, ostatki.quantity, categories.category_name AS gr, products.id AS product_id
			FROM products
			LEFT JOIN (
			SELECT quantity, store, tovar
			FROM ostatki_trigers
			WHERE store = ".$this->stores_id[$k];
			$query.=") ostatki ON ostatki.tovar = products.id
			JOIN  categories  ON categories.category_id = products.category_belong 
			JOIN  categories parent_categories ON categories.parent = parent_categories.category_id
			WHERE ostatki.store = ".$this->stores_id[$k];
			
			$query.= " GROUP BY products.product_name, products.id ORDER BY products.product_name, products.id ";
			$query.= "  ) store".$kk;
			if ($k>0) $query.= " ON products.id = store$kk.product_id "; 
			if ($k==0) $query.= "  ON products.id = store1.product_id "; 
			if (($k+1)<count($this->stores_id)) $query.=" LEFT JOIN ";
			}//////////  for($k=0;$k<count($this->stores_id);$k++) {
			$query.= "LEFT  JOIN (SELECT price_list_header.creation_dt, price_list_products_list.product_id, price_list_products_list.price_with_nds, price_list_header.price_type, dates_products.cr_dt
			FROM price_list_products_list
			JOIN price_list_header ON price_list_header.id = price_list_products_list.pricelist_id
			JOIN (
			
			SELECT MAX( price_list_header.creation_dt ) AS cr_dt, price_list_products_list.product_id
			FROM price_list_products_list
			JOIN price_list_header ON price_list_header.id = price_list_products_list.pricelist_id
			WHERE price_list_header.price_type =1 AND price_list_header.status = 1  AND price_list_header.currency =".Yii::app()->GP->GP_shop_currency."
			GROUP BY price_list_products_list.product_id
			)dates_products ON dates_products.cr_dt = price_list_header.creation_dt
			WHERE price_list_header.price_type =1  AND price_list_header.currency =".Yii::app()->GP->GP_shop_currency." 
			AND dates_products.product_id = price_list_products_list.product_id) price_list ON products.id=price_list.product_id ";
			
				 $query.= "LEFT JOIN (SELECT id_product, GROUP_CONCAT( value) AS  attribute_value
			FROM `characteristics_values` JOIN (SELECT * FROM products WHERE product_parent_id >0) child_products ON child_products.id = characteristics_values.id_product GROUP BY id_product) product_attribute ON product_attribute.id_product = products.id ";
			
			$query.= "  WHERE products.id>0  AND products.id IN (";
			$query.=implode(",", $this->product_ids_arr) ;
		
			$query.= ")  ORDER BY products.product_name ";
			$this->query=$query;
			//echo $this->query=$query;
			}
	
	function GetCartContent () {
			$this->command=$this->connection->createCommand($this->query)	;
			$this->dataReader=$this->command->query();
			$rows=$this->dataReader->readAll();
			return $rows;
	}
	
	function GetStructure1() {
	
	
	return array(
		'showErrorSummary' => true,
	    'elements'=> $this->el_ar,
	
    'buttons'=>array(
		'make_order'=>array(
        'type'=>'submit',
        'label'=>'Оформить',
		),
    ),
);
	}
	
		public function get_stores_names () {
				return $this->stores_names;
		}
		
		public function get_stores_id () {
				return $this->stores_id;
		}
				
		public function validate_adress1(){
			if (trim($this->order_adress1)=='') 	{
				$this->addError('order_adress1','Необходимо заполнить поле Aдрес доставки');
				return false;
			}
			else return true;
		}
		///, order_adress1		

}///////class
?>