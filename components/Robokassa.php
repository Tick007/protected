<?
class Robokassa extends CWidget {////////////////////Рисует меню селекта

	var $mrh_login;
	var $mrh_pass1;
	
	var $procent="0.05"; /////////////5% Процент, который снимает робокасса

	var $mrh_pass2;//////1a722ffb50acf5310b
	// номер заказа
	// number of order
	var $inv_id = 0;
	
	// описание заказа
	// order description
	var $inv_desc = "Оплата заказа";
	
	// сумма заказа
	// sum of order
	var $out_summ = "1";
	
	// тип товара
	// code of goods
	public $shp_item = 1;
	
	// предлагаемая валюта платежа
	// default payment e-currency
	var $in_curr = "AlfaBankR";
	
	// язык
	// language
	public $culture = "ru";
	
	// кодировка
	// encoding
	public $encoding = "utf-8";
	
	// формирование подписи
	// generate signature
	var $crc	;
	
	var $merchant_id; ///////////////Идентификатор контрагента для создания заказа для транзакции дебета
	var $method_list; ///////////Сюда загружаем список методов оплаты
	var $curr_list;//////////////////////Сптсок валют
	var $rates_list;//////////////////////Сптсок обменных курсов валют

	public function __construct(){/////////
			if(isset(Yii::app()->params['robokassa']) AND empty(Yii::app()->params['robokassa'])==false){
				$this->mrh_login = Yii::app()->params['robokassa']['mrh_login'];
				$this->mrh_pass1 = Yii::app()->params['robokassa']['mrh_pass1'];
				$this->mrh_pass2 = Yii::app()->params['robokassa']['mrh_pass2'];
			}	
			
			$this->calculate_crc();
			
				
	}/////////////////function
	
	public function set_curr($curr){/////////////////Выставление вавлюты
			$this->in_curr  = $curr;
	}/////////////////////public function set_curr($curr){////////////
	
	
	
	public function setmerchant($merchant_id) {//////////Задаем контрагента
	    	$this->merchant_id = $merchant_id;
	}/////////////////public function setmerchant() {//////////Задаем контраген
	
	public function changesumm($value){
			if(is_numeric($value))  {
					$this->out_summ = $value;
			}
	}//////////////////public function changesumm($value){
	
	public function setorder_id($id) {
		$this->inv_id = $id;
		$this->inv_desc = 'Оплата заказа № '.$id;
		$this->calculate_crc();
	}
	
		
	public function createorder() {	/////////////Создаем заказ		
			if(is_numeric($this->out_summ))  {
					///////////////////Создаем заказ, получаем его идентификатор, и исподьзуем дальше
					$order =new   Account_order;
					$order->user_id = Yii::app()->user->id;
					$order->account_id = $this->merchant_id ;
					$order->operation_datetime = time() ;
					$order->money = $this->out_summ ;
					$order->crc = 'empty' ;
					try {
								$order->save();
						} catch (Exception $e) {
								 echo 'Ошибка создания заказа: ',  $e->getMessage(), "\n";
						}/////////////////////
					if (isset($order->id)) {
							
							/////////////////Смотрим сколько всего счетов у пользователя, и присваеваем ему номер
							$criteria=new CDbCriteria;
							$criteria->condition = " t.account_id = :cat_id AND partner_number IS NOT NULL";
							$criteria->params = array(':cat_id'=>$this->merchant_id);
							$num_of_orders=Account_order::model()->count($criteria);
							if ($num_of_orders>0) $num_of_orders++;
							else $num_of_orders=1;
							
							$this->inv_id = $order->id;
							$this->calculate_crc();
							$order->crc = $this->crc;
							$order->partner_number = $num_of_orders;
							try {
											$order->save();
									} catch (Exception $e) {
											 echo 'Ошибка сохранения контрольной суммы: ',  $e->getMessage(), "\n";
									}/////////////////////
					}///////////if (isset($order->id)) {
			}///////////	if(is_numeric($value))  {
	}////////////////////public function changesumm('value'){
	
	public function sendnotification(){//////////Отправка уведомления на почту об возможной оплате
			
			$MERCHANT = Contr_agents::model()->findByPk($this->merchant_id);
			$CLIENT = Clients::model()->findByPk(Yii::app()->user->id);
			$msg_body = 	"Создан заказ на оплату:<br>Имя: ".$CLIENT->first_name.' '.$CLIENT->second_name."; <br>Компания: ".$MERCHANT->name.";<br>Телефон: ".$CLIENT->client_tels."; <br>email:  ".$CLIENT->client_email;
			$msg_body.="Счет № ".@$this->inv_id.'(номер в нашей системе),<br> сумма: '. @$this->out_summ; 	
			$msg_body.="<br>
			Внимание! Данный заказ может быть как счетом на безналичную оплату (в данном случае его можно найти и закрыть после поступления средств в платажах контрагента), так и электронным платежем.
			";
			
			$msg_body = iconv( "UTF-8", "CP1251", $msg_body);
				
			$headers = 'From: '.@$CLIENT->client_email. "\r\n" ;
			$headers.='Content-type: text/html; charset=windows-1251' . "\r\n";

			@mail(Yii::app()->params['supportEmail'],  iconv( "UTF-8", "CP1251", 'Создан счет на оплату ').$_SERVER['HTTP_HOST'], $msg_body, $headers)	;//Yii::app()->user->setFlash('contact','Заявка создана. Мы свяжемся с вами в ближайшее время.');
	}//////////public function sendnotification(){//////////
	
	public function readOrder($order_id){//////////////////////Читаем данные заказа и ставим переменные
			if (isset($order_id) AND is_numeric($order_id) ) {
					$Account_order = Account_order::model()->findByPk($order_id);
					if (isset($Account_order->id) AND $Account_order->user_id == Yii::app()->user->id ) {///////////
							$this->out_summ = $Account_order->money;
							$this->inv_id        = $Account_order->id;
							$this->calculate_crc();
					}//////////////////if (isset($Account_order->id)) {
			}////////////////////if (isset($order_id) AND is_numeric($order_id) ) {
	}//////////////////////public function readOrder(){
	
	private function calculate_crc(){
			// формирование подписи
			// generate signature
			//$crc  = md5("$mrh_login:$out_summ:$inv_id:$mrh_pass1:Shp_item=$shp_item");
			
			//8c7f7325d3f9b959da533cd2626e0f25

			//Как считали MD5: uslugi:300:226:a2ba3627a18f3e:Shp_item=
			
			//$this->crc  = md5($this->mrh_login.':'.$this->out_summ.':'.$this->inv_id.':'.$this->mrh_pass1.':Shp_item='.$this->shp_item);/////////Для их формы
			//print_r($this->mrh_login.':'.$this->out_summ.':'.$this->inv_id.':'.$this->mrh_pass1.':Shp_item='.$this->shp_item);
			//echo '<br>';	
			
			$this->crc  = md5($this->mrh_login.':'.$this->out_summ.':'.$this->inv_id.':'.$this->mrh_pass1.':Shp_item='.$this->shp_item);/////////Для их формы
			//echo $this->crc.'<br>';
			//echo $this->mrh_login.':'.$this->out_summ.':'.$this->inv_id.':'.$this->mrh_pass1.':Shp_item='.$this->shp_item.'<br>';
			//echo 'qqq = '.$this->crc.'<br>';
			//$this->crc  = md5($this->mrh_login.':'.$this->out_summ.':'.$this->inv_id.':'.$this->mrh_pass1);

	}////////////////private function calculate_crc(){
	
	public function showpaymentform(){///////////Показываем их форму
			$this->render('robokassa', array('crc'=>$this->crc,'encoding'=>$this->encoding, 'culture'=>$this->culture, 'in_curr'=>$this->in_curr, 'shp_item'=>$this->shp_item,'out_summ'=>$this->out_summ,'inv_desc'=>$this->inv_desc,'inv_id'=>$this->inv_id,'mrh_pass1'=>$this->mrh_pass1,'mrh_login'=>$this->mrh_login));		
	}////////public function showpaymentform(){
	
	public function showpaymentlink() { /////////////Показывает ссылка на оплату
			///////////$crc  = md5("$mrh_login:$out_summ:$inv_id:$mrh_pass1");
			//$this->crc  = md5($this->mrh_login.':'.$this->out_summ.':'.$this->inv_id.':'.$this->mrh_pass1);
			$this->calculate_crc();
			$url = "https://merchant.roboxchange.com/Index.aspx?MrchLogin=".$this->mrh_login."&".
    "OutSum=".$this->out_summ."&InvId=".$this->inv_id."&Desc=".$this->inv_desc."&IncCurrLabel=".$this->in_curr ."&SignatureValue=".$this->crc;
			$word = 'Оплатить '.$this->rates_list[$this->in_curr]['rate'].' '.$this->in_curr;
			echo CHtml::link($word, $url, array('target'=>'_blank', 'style'=>'font-weight:bold'));
	}////////////////////////

	public function receiveXMLparams(){//////////////////////////////////////Вытаскивем список методов
		$timeout = 2;
		$payment_methods = "http://merchant.roboxchange.com/WebService/Service.asmx/GetPaymentMethods?MerchantLogin=".$this->mrh_login."&Language=".$this->culture;
		//echo $payment_methods;
		$old = ini_set('default_socket_timeout', $timeout);
		try {
		$handle = @fopen($payment_methods, "b");
		if ($handle) {
				$contents = '';
				while (!feof($handle)) {
				  $contents .= fread($handle, 8192);
				}
				fclose($handle);
				//echo "$contents";
				try {
							$xml = new SimpleXMLElement($contents);
					} catch (Exception $e) {
							 echo 'Caught exception:  Ссылка: '.$rabota_links[$s].' ',  $e->getMessage(), "\n";
					}///////////////////		 
				if (is_object($xml)) {
						foreach ($xml->Methods->Method as $method=>$atr) {///
								$attributes=$atr->attributes();
								$code = (string)$attributes[0];
								$descr =  (string)$attributes[1];
								$method_list[$code]=$descr;
								//echo '<br>';
						}/////////////foreach ($xml->channel->item as $link) {
				}//////////////////if (is_object($xml)) {
		}///////////if ($handle) {
		}/////////////try
		catch (Exception $e) {
			echo 'Ошибка получения методов оплаты с робокассы. ',  $e->getMessage(), "\n";
			exit();
		}/////
	if (isset($method_list)) $this->method_list = $method_list;
	
	
	///////////////////////Теперь грузим список валют
	$code=NULL;
	$descr=NULL;
	$attributes = NULL;
	$source = "http://merchant.roboxchange.com/WebService/Service.asmx/GetCurrencies?MerchantLogin=".$this->mrh_login."&Language=".$this->culture;
	//echo '<br>'.$source.'<br>';
	try {	
	$handle = @fopen($source, "b");
		if ($handle) {
				$contents = '';
				while (!feof($handle)) {
				  $contents .= fread($handle, 8192);
				}
				fclose($handle);
				try {
							$xml = new SimpleXMLElement($contents);
					} catch (Exception $e) {
							 echo 'Caught exception:  Ссылка: '.$rabota_links[$s].' ',  $e->getMessage(), "\n";
					}///////////////////		 
				if (is_object($xml)) {
						foreach ($xml->Groups as $groups) {///
								foreach ($groups as $group) {
										$attributes = $group->attributes();
										$code = (string)$attributes['Code'];
										$descr =  (string)$attributes['Description'];
										$curr_list[$code]['code']=$code;
										$curr_list[$code]['descr']=$descr ;
										$items=$group->Items;
												$items_arr = NULL;
												foreach ($items->Currency as $item) {//////Разбираем итемы
														$item_atr = $item->attributes();
														$label= (string)$item_atr['Label'];
														$name =  (string)$item_atr['Name'];
														$items_arr[$label]=$name;
												}/////////foreach ($items as $item) {
										$curr_list[$code]['items']=$items_arr;
								}///////////foreach ($xml->Groups as $groups) {///
						}/////////////foreach ($xml->channel->item as $link) {
				}//////////////////if (is_object($xml)) {
				
		}///////////if ($handle) {
		//echo '<pre>';
		//print_r($curr_list);	
		//echo '</pre>';
	if (isset($curr_list)) {
			/*
			/////////////////Сформируем список понятный для YII
			foreach($curr_list as $cur_list_cur) {/////////////array('id'=>256,'text'=>'TV','group'=>'Electrical'),
					//$final_curr[$cur_list_cur['code']]=$cur_list_cur['descr'];
					if (is_array($cur_list_cur['items'])) {
							foreach ($cur_list_cur['items'] as $code=>$descr) {////////
									//$final_curr[]=array('id'=>$code, 'text' =>$descr, 'group'=>$cur_list_cur['descr']);//////////для выпадающего списка с группировками
									$final_curr[]=array('id'=>$code, 'text' =>$descr);
							}//////////////foreach ($cur_list_cur['items'] as $currency) {
					}//////if (is_array($cur_list_cur['items'])) {
			}	///////////foreach(
			//$this->curr_list = CHtml::listData($final_curr,'id','text','group');
			$this->curr_list = CHtml::listData($final_curr,'id','text');
			print_r($this->curr_list);
			*/
			$this->curr_list = $curr_list;
	}//////////////if (isset($curr_list)) {
	
	}/////////////try
	catch (Exception $e) {
		echo 'Ошибка получения списков валют с робокассы. ',  $e->getMessage(), "\n";
		exit();
	}/////
	
	///////////Интерфейс получения курсов валют и расчета суммы для оплаты
	$code=NULL;
	$descr=NULL;
	$attributes = NULL;
	$items_arr = NULL;
	$source = "http://merchant.roboxchange.com/WebService/Service.asmx/GetRates?MerchantLogin=".$this->mrh_login."&IncCurrLabel=&OutSum=".$this->out_summ."&Language=".$this->culture;
	try {	
	$handle = @fopen($source, "b");
		if ($handle) {
				$contents = '';
				while (!feof($handle)) {
				  $contents .= fread($handle, 8192);
				}
				fclose($handle);
				try {
							$xml = new SimpleXMLElement($contents);
					} catch (Exception $e) {
							 echo 'Caught exception:  Ссылка: '.$rabota_links[$s].' ',  $e->getMessage(), "\n";
					}///////////////////		 
				if (is_object($xml)) {
						foreach ($xml->Groups as $groups) {///
								foreach ($groups as $group) {
										$attributes = $group->attributes();
										$code = (string)$attributes['Code'];
										$descr =  (string)$attributes['Description'];
										
										//$rates_list[$code]['code']=$code;
										//$rates_list[$code]['descr']=$descr ;
										
										$items=$group->Items;
												//$items_arr = NULL;
												foreach ($items->Currency as $item) {//////Разбираем итемы
														$item_atr = $item->attributes();
														$label= (string)$item_atr['Label'];
														$name =  (string)$item_atr['Name'];
														$rate=$item->Rate;
														$rate_atr = $rate->attributes();
														$rate_value = (string)$rate_atr['IncSum'];
														//$items_arr[$label]=$name;
														//$items_arr[$rate_value]
														$items_arr[$label]=array('label'=>$label, 'name'=>$name, 'rate'=>$rate_value);
												}/////////foreach ($items as $item) {
										//$rates_list[$code]['items']=$items_arr;
										$rates_list=$items_arr;
								}///////////foreach ($xml->Groups as $groups) {///
						}/////////////foreach ($xml->channel->item as $link) {
				}//////////////////if (is_object($xml)) {
		//echo '<pre>'; 
		//print_r($rates_list);		
		//echo '</pre>'; 
		if (isset($rates_list)) $this->rates_list = $rates_list;
		}///////////if ($handle) {
	}////////////////}/////////////try
		catch (Exception $e) {
			echo 'Ошибка получения курсов с робокассы. ',  $e->getMessage(), "\n";
			exit();
		}/////
	
	}////////////public function receiveXMLparams(){
	
}////////////////class Tree extends CWidget {
?>