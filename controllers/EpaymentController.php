<?php

class EpaymentController extends Controller/////////////Контроллер для онлайн платежей
{
	var $OutSum;
	var $InvId;
	var $SignatureValue;
	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations  
			'CheckNumberofContragents +addfirm',//////////////Смотрим, сколько уже на пользователя заведено фирм
			'CheckRkassaresultvalues + rkassaresult',//////// Проверка, открывает ли пользователь своё сообщение или нет 
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
				'actions'=>array('index', 'rkassaresult', 'tkfpayment'),
				'users'=>array('*'), 
		),
		array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('rkassasuccess', 'rkassafai'),
				'users'=>array('@'),
		),
		array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('@'),
		),
		array('deny',  // deny all users
				'users'=>array('*'),
		),
		);
	}


	public function filterCheckRkassaresultvalues ($filterChain) {
		////////Смотрим сколько фирм у пользователя
		$OutSum = Yii::app()->getRequest()->getParam('OutSum', NULL);
		$InvId = Yii::app()->getRequest()->getParam('InvId', NULL);
		$SignatureValue = Yii::app()->getRequest()->getParam('SignatureValue', NULL);
		if($SignatureValue != NULL)	 $SignatureValue = strtolower($SignatureValue);
			
		if ($OutSum != NULL AND $InvId != NULL AND $SignatureValue != NULL) {
			$this->OutSum = $OutSum;
			$this->InvId = $InvId;
			$this->SignatureValue = $SignatureValue;
			$filterChain->run();
		}//////////////if ($OutSum != NULL AND $InvId != NULL AND $SignatureValue != NULL) {
		else {
			throw new CHttpException(401,'Недостаточно параметров');
		}
	}////////////////////////////////////public function filt






	public function actionIndex(){
		/////////public function actionIndex(){
		$this->render('index');
	}//////////public function actionIndex(){


	public function actionRkassasuccess(){
		///////////Success URL (используется в случае успешного проведения платежа); Что нить пишем, и редиректи в ЛК /////Только для авторизованных
			
	}//////////public function actionRkassaok(){/

	public function actionRkassafail(){
		///////////Success URL (используется в случае успешного проведения платежа); ///////Что нить пишем и отправляем в лчный кабинет  /////Только для авторизованных
			
	}//////////public function actionRkassaok(){/

	public function actionRkassaresult(){
	    /////////Сюда будет ломиться робот робокассы сообщая результат платежа
	    //echo $this->InvId.'<br>';
	    //echo $this->OutSum.'<br>';
	    //echo $this->SignatureValue.'<br>';
	    
	    
	    $order = Orders::model()->findByPk($this->InvId);
	    
	    
	    
	    if (is_null($order)==false && isset($order->id)) {
	        //////////////Создаем транзакцию в системе
	        //$RKASSA = new Robokassa; ////////////Смотрим параметры
	        $mrh_pass2 = Yii::app()->params['inetpayment']['result_pass']; //////Здесь идет проверка на 2й пароль
	        ////////Т.е. платили под одним паролем, результаты робокасса сообщает под другим
	        ///$my_crc = strtoupper(md5("$out_summ:$inv_id:$mrh_pass2:Shp_item=$shp_item"));
	        $check_str = $this->OutSum.':'.$this->InvId.':'.$mrh_pass2.':Shp_item='.Yii::app()->params['inetpayment']['shp_item'];
	        //echo $check_str.' = '.md5($check_str).'<br>';
	        if (md5($check_str)==$this->SignatureValue) {
	            
	            
	            ////////Смотрим нет ли транзакций с таким номером заказа account_order_id
	            $DEBET = AccountDebet::model()->findByAttributes(array('order_id'=>$order->id));
	            if ($DEBET==NULL) {
	                //////Всё ок, транзакции с таким заказом нет
	                if(isset($RKASSA->procent)) $this->OutSum = $this->OutSum*(1-Yii::app()->params['inetpayment']['procent']);
	                
	                /////////////////////Смотрим баланс пользователя до оплаты
	                //$old_balance = $this->getBalance($order->account_id);
	                
	                $NEWDEBET = new AccountDebet;
	                $NEWDEBET->money = $this->OutSum;
	                //$NEWDEBET->comments = 'Платёж через платежную систему';
	                $NEWDEBET->transaction_type = 1;
	                $NEWDEBET->operation_datetime = microtime(true);
	                //$NEWDEBET->payment_system = 1;
	                $NEWDEBET->order_id =  $order->id;
	                //print_r($NEWDEBET->attributes);
	                
	                
	                try {
	                    
	                    $NEWDEBET->save();
	                    echo 'OK'. $order->id;
	                    
	                    ///////////Ставим статус заказа как оплачен
	                    if(isset(Yii::app()->params['inetpayment']['orderStatusPaid']) && $NEWDEBET!=NULL){
	                        $NEWDEBET->order->order_status = Yii::app()->params['inetpayment']['orderStatusPaid'];
	                        try {
	                            $NEWDEBET->order->save();
	                        } catch (Exception $e) {
	                        }
	                    }
	                    	                    
	                } catch (Exception $e) {
	                    echo '1. Ошибка сохранения платежа: ',  $e->getMessage(), "\n";
	                    echo '<pre>';
	                    print_r($NEWDEBET);
	                    echo '</pre>';
	                }
	                
	                if (isset($NEWDEBET->id)) {
	                    /*
	                     $order->debet_id = $NEWDEBET->id;
	                     $order->payment_system = 1;//////////////Ставим что это робокасса, потому что до этого человек мог кликнуть на форму счета - и сюда прописалось 5.
	                     try {
	                     $order->save();
	                     $new_balance = $this->getBalance($order->account_id);
	                     $this->sendnotification($order, $NEWDEBET, $old_balance, $new_balance );
	                     } catch (Exception $e) {
	                     echo 'Ошибка сохранения заказа: ',  $e->getMessage(), "\n";
	                     }/////////////////////
	                     */
	                }////////if (isset($NEWDEBET->id)) {
	                
	                
	            }/////////////if (isset($DEBET->id)==false) {//////Всё ок, транзакции с таким заказом нет
	            else{
	                // throw new CHttpException(500,'Заказ '.$cookie_order_id.' Вам не принадлежит.');
	                //exit();
	                //print "Content-type: text/html\n\already paid\n";
	                die("Already paid");
	            }
	        }/////////////if (md5()==$this->SignatureValue) {
	        else 	{
	            print "Content-type: text/html\n\nbad sign\n";
	            die("incorrect sign passed");
	        }/////////else 	{
	    }/////////////if (isset($order->id)) {//////////////С
	    else echo 'fail';
	    
	}//////////public function actionRkassaok(){/
	
	public function actionRkassaresult_old(){
		/////////Сюда будет ломиться робот робокассы сообщая результат платежа
		//echo $this->InvId.'<br>';
		//echo $this->OutSum.'<br>';
		//echo $this->SignatureValue.'<br>';


		$order = Orders::model()->findByAttributes(array('id'=>$this->InvId));
		if (isset($order)) {
			//////////////Создаем транзакцию в системе
			$RKASSA = new Robokassa; ////////////Смотрим параметры
			$mrh_pass2 = $RKASSA->mrh_pass2; //////Здесь идет проверка на 2й пароль
			////////Т.е. платили под одним паролем, результаты робокасса сообщает под другим
			///$my_crc = strtoupper(md5("$out_summ:$inv_id:$mrh_pass2:Shp_item=$shp_item"));
			if (md5($this->OutSum.':'.$this->InvId.':'.$mrh_pass2.':Shp_item='.$RKASSA->shp_item)==$this->SignatureValue) {

				////////Смотрим нет ли транзакций с таким номером заказа account_order_id
				$DEBET = Account_debet::model()->findByAttributes(array('schet'=>$order->id));
				if (isset($DEBET->id)==false) {
					//////Всё ок, транзакции с таким заказом нет
					if(isset($RKASSA->procent)) $this->OutSum = $this->OutSum*(1-$RKASSA->procent);
					
					/////////////////////Смотрим баланс пользователя до оплаты
					//$old_balance = $this->getBalance($order->account_id);
					
					$NEWDEBET = new Account_debet;
					$NEWDEBET->account_id = Yii::app()->params['self_contragent'];
					$NEWDEBET->operation_datetime = time();
					$NEWDEBET->money = $this->OutSum;
					$NEWDEBET->comments = 'Платёж через робокассу';
					$NEWDEBET->transaction_type = $order->payment;
					$NEWDEBET->schet =  $order->id;
					//print_r($NEWDEBET->attributes);


					try {

						$NEWDEBET->save();
						echo 'OK'. $order->id;
						$this->sendnotification($order, $NEWDEBET );

					} catch (Exception $e) {
						echo 'Ошибка сохранения платежа: ',  $e->getMessage(), "\n";
					}

				


				}/////////////if (isset($DEBET->id)==false) {//////Всё ок, транзакции с таким заказом нет
			}/////////////if (md5()==$this->SignatureValue) {
			else 	{
				print "Content-type: text/html\n\nbad sign\n";
				die("incorrect sign passed");
			}/////////else 	{
		}/////////////if (isset($order->id)) {//////////////С
		else echo 'fail';

	}//////////public function actionRkassaok(){/

	public function sendnotification($order, $NEWDEBET){////////////отправка  сообщения в случае успеха
			$msg_body = 	"Поступила оплата через Робокассу на сумму ".$this->OutSum." по заказу N ".$order->id.". Дата оплаты: ".date('d.M.Y', $NEWDEBET->operation_datetime);
			
			$msg_body = iconv( "UTF-8", "CP1251", $msg_body);
				
			$headers = 'From: '.Yii::app()->params['supportEmail1']. "\r\n" ;
			$headers.='Content-type: text/html; charset=windows-1251' . "\r\n";

			@mail(Yii::app()->params['supportEmail'],  iconv( "UTF-8", "CP1251", 'Поступление оплаты ').$_SERVER['HTTP_HOST'], $msg_body, $headers)	;//Yii::app()->user->setFlash('contact','Заявка создана. Мы свяжемся с вами в ближайшее время.');
			
			if (isset($order->id_client )) {////////////
				$client = Clients::model()->findByPk($order->id_client);
				if(isset($client->client_email)) {
						@mail($client->client_email,  iconv( "UTF-8", "CP1251", 'Поступление оплаты').$_SERVER['HTTP_HOST'], $msg_body, $headers)	;//Yii::app()->user->setFlash('contact','Заявка создана. Мы свяжемся с вами в ближайшее время.');
				}////////////if(isset($client->client_email)) {
			}
			
	}/////////////public function sendnotification

	public function actionPayrobokassa() {
		////////////Редирект на шлюз робокассы

	}/////////////////public function action Payrobokassa() { ////////////

	private function getBalance($contr_agent_id){
		/////////////////////Получение баланса пользователя по его организации



				$criteria=new CDbCriteria;
				$criteria->condition = " t.account_id = :contr_agent ";
				$params = array(':contr_agent'=>$contr_agent_id );
				$criteria->params = $params;
				$criteria->order= ' t.operation_datetime DESC ';
				$KREDIT = Account_kredit::model()->findAll($criteria);
				$summa_kredit = 0;
				for ($i=0; $i<count($KREDIT); $i++) $summa_kredit = $summa_kredit+$KREDIT[$i]->money;
					
				//echo $summa_kredit ;
					
				/////////////////////Теперь выбираем все приходные транзакции
				$summa_debet = 0;
				$criteria=new CDbCriteria;
				$criteria->condition = " t.account_id = :cat_id";
				$criteria->order = 't.id';
				$criteria->params=array(':cat_id'=>$contr_agent_id );
				$DEBET = Account_debet::model()->findAll($criteria);
				$summa_debet = 0;
				for ($i=0; $i<count($DEBET); $i++) $summa_debet = $summa_debet+$DEBET[$i]->money;

		if (isset($summa_kredit) OR isset($summa_debet) ) return (int)$summa_debet - (int)$summa_kredit;
		else return 0;
			
	}//////////////////////private function getBalance(){//////////////////

	
	
	/** Метод для вызова формы оплаты тинькофы
	 * https://oplata.tinkoff.ru/landing/develop/plug?section=testing&utm_source=notification&utm_medium=email&utm_campaign=acquiring_main_notifications225
	 * @param зашифрованный идентификатор заказа $id
	 * @throws CHttpException
	 */
	public function actionTkfpayment($id){
		
		//////////Получаем идентификатор заказа
		{
		$oid2 = FHtml::base64url_decode($id);
		//echo '$oid2 = '.$oid2.'<br>';
		
		$prifix = md5(Yii::app()->params['payments']['tinkoff']['order_id_code_prefix']);
		$prefix_len=strlen($prifix);
		
		$postfix = md5(Yii::app()->params['payments']['tinkoff']['order_id_code_postfix']);
		$postfix_len=strlen($prifix);
		
		$oid1a=substr($oid2, $prefix_len, strlen($oid2)-$prefix_len);
		
		$oid1=substr($oid1a, 0, strlen($oid1a)-$postfix_len);
		
		//echo '$oid1 = '.$oid1.'<br>';
		$order_id= FHtml::base64url_decode($oid1);
		//echo '$order_id = '.$order_id.'<br>';
		}
		
		if(is_numeric($order_id)){
			$order=Orders::model()->findByPk($order_id);
			if($order!=null){
				
				//paid_status ///////////Уже оплачен
				if(Yii::app()->params['payments']['tinkoff']['paid_status']==$order->order_status){
					throw new CHttpException(302,'Order been already paid');
					exit();
				}
				///////////////Заказ имеет статус готовности (пока что 3, т.е. общая готовность а не конкретно для онлайн)
				elseif($order->order_status <> Yii::app()->params['payments']['tinkoff']['ready_for_online_patment']){
					throw new CHttpException(403,'Order is not allowed to pay online');
					exit();
				}
				
				/////////////рендер тинькоффской формы
				$fio = $order->get_order_client_info();
				$this->render('tinkoff_form', array('order'=>$order, 
							'fio'=>$fio['fio'], 
							'email'=>$fio['email'], 
							'phone'=>$fio['phone'],
							'terminalkey'=>Yii::app()->params['payments']['tinkoff']['terminalkey']
							)
						);
			}
			else {////////////Не найден в БД
				throw new CHttpException(404,'Order not found');
				exit();
			}
			
		}
		else{////////////не удалось получить вменяемый идентификатор из закодированной строки
			throw new CHttpException(404,'Wrong order id');
			exit();
		}
		
		
	}
	
}//////////////////class
