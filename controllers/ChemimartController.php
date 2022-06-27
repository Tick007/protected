<?php

class ChemimartController extends Controller
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}
	
	
	
	public function filters()
	{
	    return array(
	        'accessControl', // perform access control for CRUD operations
	        'HasJsFile + index, register',
	        'CheckBrouser +index, contact, login, register, remind, page',
	        'SetTheme +index, contact, login, register, remind, page', 
	        'HasPopupText +index'
	       
	    );
	}
	
	public function accessRules()
	{
	    return array(
	        array('allow',  // allow all users to perform 'list' and 'show' actions
	            
	            'users'=>array('*'),
	        ),


	    );
	}
	

	///////////Метод для запоминания сообщения getFlash, что бы в дальнейшем был вызван актион actionPopupFlash
	public function filterHasPopupText($filterChain){
	    /*
	    if(Yii::app()->user->hasFlash('popuptext')){
	        ////////////////Перезапоминаем
	        $msg =Yii::app()->user->getFlash('popuptext');
	        Yii::app()->user->setFlash('popuptext2',  'qweqwewe');
	        
	        echo $msg;
	    }
	    */
	    
	    Yii::app()->user->setFlash('popuptext2',  'qweqwewe');
	    
	    $filterChain->run();
	}
	
	
	///Всплывающее окно для сообщения после регистрации
	public function actionPopupFlash(){
	    
	    
	    if($this->hasFlashC('popuptext')){
	        $msg = $this->getFlashC('popuptext', true);

	        $this->renderPartial('common/popuptxt', array('msg'=>$msg));
	        exit();
	    }
	    else{
	        //echo 'qweqweew';
	        throw new CHttpException(404,'nomessage');
	    }
	    exit();
	}
	

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex() 
	{
	     
	    //$this->setFlashC('popuptext', 'это тестовое сохранение');
	    
	    
	    
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
		$lng = Yii::app()->language;

        // Вытаскивем группы каталога
        $models = $this->getMainCategs(Yii::app()->params['main_page']);
        
		$this->render('index/'.$lng, array('models'=>$models));
	}
	
	
	public function getMainCategs($params){
	    $criteria=new CDbCriteria;
	    $criteria->order = ' t.sort_category ';
	    $criteria->limit= $params->limit;
	    $criteria->condition =" t.parent = :parent AND t.show_category = 1 ";
	    $criteria->params=array(':parent'=>$params->root_dir);
	    $models = Categories::model()->findAll($criteria);//</strong>
	    return $models;
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError() {
    
	    if($error=Yii::app()->errorHandler->error)
	    {
	        if(Yii::app()->request->isAjaxRequest)
	            echo $error['message'];
	            else {
	                //$this->render('pages/404', array('error'=>$error) );
	                $this->render('error',array ('error'=>$error) );
	            }
	    }
	}

	/**
	 * Displays the contact page
	 */
	public function actionContact()
	{
	    $model=new ContactFormChemimart;
		if(isset($_POST['ContactFormChemimart']))
		{
		    
		    if(is_array(Yii::app()->params['adminEmail'])==false) $admin_mail ==(array)Yii::app()->params['adminEmail'];
		    else $admin_mail = Yii::app()->params['adminEmail'];
		    
		    //print_r($admin_mail);
		    //exit();
		    
		   
		    
			$model->attributes=$_POST['ContactFormChemimart'];
			
			if($model->validate())
			{    
			    $body = "From: ".$model->name.'<br>';
			    $body.= "Company: ".$model->company.'<br>';
			    $body.= "Phone: ".$model->tel.'<br>';
			    $body.= "Message: ".$model->body.'<br>';
			    
				$headers= 'Content-type: text/html; charset=utf-8' . "\r\n";
				$headers.="From: {$model->email}\r\nReply-To: {$model->email}";
				foreach ($admin_mail as $amail) {
				    @mail($amail,'contact request',$body,$headers);
				}
				@mail(Yii::app()->params['contactEmail'],'contact request',$body,$headers);
				Yii::app()->user->setFlash('contact',Yii::t('site','Thank you for contacting us. We will respond to you as soon as possible.'));
				$this->refresh();
			}
		}
		$this->render('contact',array('model'=>$model));
	}

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
	    $urlReferrer = Yii::app()->request->urlReferrer;
	    
	    $return = Yii::app()->getRequest()->getParam('return', null);
	    
	    if($return==1 && $urlReferrer!=null) {
	        $returnUrl = Yii::app()->request->urlReferrer;
	    }

	    $model=new LoginForm;
	    if(isset($returnUrl)) $model->returnUrl = $returnUrl;

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid

		
			if($model->validate()){
			    $model->authenticate(null, null);
			    if(isset($model->returnUrl) && trim($model->returnUrl)!='') $this->redirect($model->returnUrl);
			    else $this->redirect(Yii::app()->user->returnUrl);
				Yii::app()->die;
			}
		}
		// display the login form
		//$this->render('login',array('model'=>$model));
		$this->rendrView('login',array('model'=>$model));
	}
	
	////Всплывающее окно для логина
	public function actionRequestlogin(){
	    $model=new LoginForm;
	    $this->rendrView('loginpopup', array('model'=>$model), true);
	}
	

	
	private function rendrView($view, $params, $renderPartial=false){
	    if($renderPartial==true)$this->renderPartial($view, $params);
	    else $this->render($view, $params);
	}
	
	public function actionRegister() {
	    $contact=new RegisterFormLight('register');
	    if(isset($_POST['RegisterFormLight']))
	    {
	        
	        $contact->attributes=$_POST['RegisterFormLight'];
	        if($contact->validate())
	        {
	            
	            try {
	                $client =  $contact->registerUser();
	                $this->notifyAdminOnNewUser($contact);
	                $this->notifyUserOnRegister($client);
	               
	                try { ///////////И пробуем сразу логинить человека
	     	                $model = new LoginForm();
        	                $model->username = $client->login;
        	                $model->password = $client->client_password;
        	                if($model->validate()){
        	                    $model->authenticate(null, null);
        	                    //if(isset($model->returnUrl) && trim($model->returnUrl)!='') $this->redirect($model->returnUrl);
        	                    //else $this->redirect(Yii::app()->user->returnUrl);
        	                    //Yii::app()->die;
        	                    ///////////////////////Тут наверное нужно на какую-то страницу перекидывать где сообщать что человек был 
        	                    /////////////////////// Зарегистрирован. Пока что просто сообщение.
        	                    $msg = $this->renderPartial('registerrequest/registerWelcomeNotify', array('client'=>$client), true);
        	                    //Yii::app()->user->setFlash('register_request',  $msg);
        	                    //////Есл нужно передать в попап, то так не срабатывает, поскольку окшко попап вызыввеися после
        	                    //////ajax запросов для корзины, которые и очищают эту переменную https://www.yiiframework.com/doc/api/2.0/yii-web-session#setFlash()-detail
        	                    //Yii::app()->user->setFlash('popuptext',  $msg);
        	                    $this->setFlashC('popuptext',  $msg);
        	                    
        	                    
        	                    $this->redirect('/');
        	                    exit();
        	                }
	                
	                } catch (Exception $e) {
	                    
	                }
	                
	                
	                
	            } catch (Exception $e) {
	                print_r($e);
	                exit();
	            }
	            
		            
	            $msg = $this->renderPartial('registerrequest/registerWelcomeNotify', array('client'=>$client), true);
	            
	            
	            Yii::app()->user->setFlash('register_request', $msg);

	            $contact=new RegisterFormLight('register');////////Обнуляем
	            //$this->redirect(Yii::app()->createUrl('chemimart/register'));
	            //exit();
	            //$this->refresh();
	            //echo 'registering'
	            
	        }
	    }
	    
	    
	    //print_r($contact);
	    //exit();
	    
	    
	    $params=array();
	    $params['contact']=$contact;
	    $this->render('register', $params);
	}///////public function actionRegister() {
	
	
	
	/**
	 *Метод добавлен для регистрации пользователей сделавших заказ (которые не были залогинены)
	 *сюда быдет редирект после совершения заказа
	 */
	public function actionRegisterfull($client, $order){
	    $order_id = (int)base64_decode($order);
	    $client_id = (int)base64_decode($client);
	    //$Order = Orders::model()->findByPk(){
	    if(is_numeric($order_id) && is_numeric($client_id)){
	        $order=Orders::model()->findByPk($order_id);
	        $client = Clients::model()->findByPk($client_id);
	        if($order!=null && $client!=null){
	            ///Смотрим какой статус и заказа
	            if($order->order_status==1 && $client->status==0){
	                ///Смотрим принадлежит ли заказ пользователю
	                if($order->id_client==$client_id){
	                    
	                    $form = new RegisterFormLight('afterorder');///////запрос полей для пегистрации после создания заказа
	                    $form->setAttributes($client->getAttributes());
	                    
	                    if(isset($_POST['RegisterFormLight']))
	                    {
	                        $form->attributes=$_POST['RegisterFormLight'];
	                        if($form->validate())
	                        {
	                            if($form->updateUser($client_id)==true){
	                                
	                                $this->notifyAdminOnOrderReg($client, $order);
	                                $this->notifyUserOnRegister($client);
	                                /*
	                                Yii::app()->user->setFlash('registerfull',
	                                    Yii::t('site','Thank you for finalizing registration. We will respond to you as soon as possible.'));
	                                /////////////И редиректим куда нить
	                                $this->redirect("/chemimart/page/?view=message&type=registerfull");
	                                */
	                                $model = new LoginForm();
	                                $model->username = $client->login;
	                                $model->password = $client->client_password;
	                                if($model->validate()){
    	                                $model->authenticate(null, null);
    	                                $msg = $this->renderPartial('registerrequest/registerWelcomeNotify', array('client'=>$client), true);
    	                                $this->setFlashC('popuptext',  $msg);
    	                                $this->redirect('/');
    	                                exit();
	                                }
	                            }
	                        }
	                    }
	                    $render_params= array('form'=>$form, 'client'=>$client);
	                    $this->render('registerfull', $render_params);
	                }
	                else throw new CHttpException(404,'order does not belongs to client');
	            }
	            else {
	                if($client->status==1) throw new CHttpException(404,'This is afterorder registration form (for new users only).<br>
Probably your client account was already submited.<br>
You see this error message if you have tried to make new order, without signing in to existing account 
(neither the less you request was composed and sent to us)');
	                else throw new CHttpException(404,'Not new order or not new client.<br> Your account may already exist but was not verified');
	            }
	        }
	        else throw new CHttpException(404,'order or user not found');
	        
	    }
	    else throw new CHttpException(404,'Incorrect values');
	    
	}
	

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}
	
	/**
	 * Метод отправки сообщения админу с уведомлением, что пользователь заполнил форму регистрационных
	 * данных после оформления заказа
	 */
	public function notifyAdminOnOrderReg($client, $order){
	    $headers="From: ".Yii::app()->params['infoEmail']."\r\n";
	    $headers.='Content-type: text/html; UTF-8' . "\r\n";
	    
	    $admin_mail = Yii::app()->params['adminEmail']; ////////По умолчанию массив
	    if(is_array(Yii::app()->params['adminEmail'])==false) $admin_mail =(array)Yii::app()->params['adminEmail'];
	    $msg = 'User '.$client->first_name.' '.$client->second_name.' has filled up registration form after submiting 
order # '.Yii::app()->params['cart']['order_nuber_template'].date('Y', strtotime($order->recept_date)).'-'.$order->id; 
	    
	    //print_r($msg);
	    //exit();
	    
	    foreach ($admin_mail as $mailto) {
	        @mail($mailto,
	            iconv( "UTF-8", "CP1251",'New user data was provided after order'), iconv( "UTF-8", "CP1251", $msg ), $headers);
	    }
	}
	
	function notifyAdminOnNewUser($contact){
	    $headers="From: ".Yii::app()->params['infoEmail']."\r\n";
	    $headers.='Content-type: text/html; UTF-8' . "\r\n";
	    
	    $admin_mail = Yii::app()->params['adminEmail']; ////////По умолчанию массив
	    if(is_array(Yii::app()->params['adminEmail'])==false) $admin_mail =(array)Yii::app()->params['adminEmail'];
	    $msg = 'New client '. $contact->first_name.' has filled up registration form';
	    
	    //print_r($msg);
	    //exit();
	    
	    foreach ($admin_mail as $mailto) {
	        @mail($mailto,
	            iconv( "UTF-8", "CP1251",'New client has requested membership'), iconv( "UTF-8", "CP1251", $msg ), $headers);
	    }
	}
	
	public function notifyUserOnRegister(Clients $client){
	    $headers="From: ".Yii::app()->params['infoEmail']."\r\n";
	    $headers.='Content-type: text/html; UTF-8' . "\r\n";
	   
	    
	    $msg = $this->renderPartial('registerrequest/registerWelcomeletter', array('client'=>$client), true);
	    
	    //echo $msg;
	    //exit();
	    
	    @mail($client->client_email, iconv( "UTF-8", "CP1251",'Welcome to Chemimart'), iconv( "UTF-8", "CP1251",  $msg), $headers);

	}
	
	
	
	
	
	
	/**
	 *Отправка обратного звонка с главной формы
	 */
	public function actionOrdercallback(){
	    
	    
	   // if(Yii::app()->request->isAjaxRequest && trim($this->browser['agent'])!=''){
	    $payload= Yii::app()->getRequest()->getParam('json', NULL);
	    
	    
	    
	    if($payload!=null){

	       
    	    try {
    	       $form_data = (object)json_decode($payload);
    	       //print_r($payload);
    	       //echo '|||';
    	       //print_r($form_data);
    	       
    	       $tel= $form_data->contact_tel;
    	       $name = $form_data->contact_name;
    	       $message = $form_data->contact_message;
    	       if(isset($form_data->contact_email)) $email =  $form_data->contact_email;
    	       else $email = NULL;
    	    
        	   // if(Yii::app()->request->isAjaxRequest){
        	        $headers="From: ".Yii::app()->params['infoEmail']."\r\n";
        	        $headers.='Content-type: text/html; UTF-8' . "\r\n";
        	        
        	        $admin_mail = Yii::app()->params['adminEmail']; ////////По умолчанию массив
        	        if(is_array(Yii::app()->params['adminEmail'])==false) $admin_mail =(array)Yii::app()->params['adminEmail'];
        	        
        	        foreach ($admin_mail as $mailto) {
        	            //@mail($mailto, iconv( "UTF-8", "CP1251",'Backcall request'), iconv( "UTF-8", "CP1251","Backcall request #$tel from $name. Message: ".htmlspecialchars($message)), $headers);
        	            @mail($mailto, 'Backcall request', "Backcall request #$tel from $name. Message: ".htmlspecialchars($message), $headers);
        	        }
        	        
        	        $respond = $this->renderPartial('callrequest',array(), true);
        	        
        	        $output = array('message'=>$respond);
        	        
        	        $show_json = json_encode($output , JSON_FORCE_OBJECT);
        	        if ( json_last_error_msg()=="Malformed UTF-8 characters, possibly incorrectly encoded" ) {
        	            $show_json = json_encode($API_array, JSON_PARTIAL_OUTPUT_ON_ERROR );
        	        }
        	        if ( $show_json !== false ) {
        	            echo($show_json);
        	        } else {
        	            die("json_encode fail: " . json_last_error_msg());
        	        }
        	        
        	        
        	        
        	    //}
    	    
    	    
    	    } catch (Exception $e) {
    	        print_r($e);  
    	    }
	  
    	    
	    }
	    else{ ///////Рендерим форму
	        
	        $this->renderPartial('callbackform');
	    }
	    
	    
	 //   }
	
	}
	
	
	/**
	 *Отправка getin touch c формы на странице services
	 */
	public function actionGetintouch(){
	    
	    
	    // if(Yii::app()->request->isAjaxRequest && trim($this->browser['agent'])!=''){
	    $payload= Yii::app()->getRequest()->getParam('json', NULL);
	    
	    
	    
	    if($payload!=null){
	        
	        
	        try {
	            $form_data = (object)json_decode($payload);
	            //print_r($payload);
	            //echo '|||';
	            //print_r($form_data);
	            
	            $tel= $form_data->contact_tel;
	            $name = $form_data->contact_name;
	            $message = $form_data->contact_message;
	            $email =  $form_data->contact_email;
	            
	            // if(Yii::app()->request->isAjaxRequest){
	            $headers="From: ".Yii::app()->params['infoEmail']."\r\n";
	            $headers.='Content-type: text/html; UTF-8' . "\r\n";
	            
	            $admin_mail = Yii::app()->params['adminEmail']; ////////По умолчанию массив
	            if(is_array(Yii::app()->params['adminEmail'])==false) $admin_mail =(array)Yii::app()->params['adminEmail'];
	            
	            foreach ($admin_mail as $mailto) {
	                //@mail($mailto, iconv( "UTF-8", "CP1251",'Backcall request'), iconv( "UTF-8", "CP1251","Backcall request #$tel from $name. Message: ".htmlspecialchars($message)), $headers);
	                @mail($mailto, 'New mail from services', "GetInTouch request #$tel from $name"."(".@$email."). Message: ".htmlspecialchars($message), $headers);
	            }
	            
	            $respond = $this->renderPartial('getintouch',array(), true);
	            
	            $output = array('message'=>$respond);
	            
	            $show_json = json_encode($output , JSON_FORCE_OBJECT);
	            if ( json_last_error_msg()=="Malformed UTF-8 characters, possibly incorrectly encoded" ) {
	                $show_json = json_encode($API_array, JSON_PARTIAL_OUTPUT_ON_ERROR );
	            }
	            if ( $show_json !== false ) {
	                echo($show_json);
	            } else {
	                die("json_encode fail: " . json_last_error_msg());
	            }
	            
	            
	            
	            //}
	            
	            
	        } catch (Exception $e) {
	            print_r($e);
	        }
	        
	        
	    }
	    else{ ///////Рендерим форму
	        
	       // $this->renderPartial('callbackform');
	    }
	    
	    
	    //   }
	    
	}
	
	
	
	public function actionRemind() {////////////////////////////Напоминание пароля
	    
	    //print_r($_POST);
	    /*
	     if(Yii::app()->request->isAjaxRequest == false) exit();
	     $email =  htmlspecialchars(trim(Yii::app()->getRequest()->getParam('email', NULL)));
	     $client_by_login = User::model()->findByAttributes(array('login'=>$email));
	     if ($client_by_login != NULL) {////////
	     CHtml::mailto('Ваш пароль: '.$client_by_login->client_password, $email);
	     echo "Пароль выслан";
	     }////////if ($client_by_login != NULL) {
	     
	     */
	    
	    $form = new RemindForm();
	    
	    # Проверяем были отправлены данные с формы или нет
	    if (!empty($_POST['RemindForm']))
	    {
	        //echo '1<br>';
	        # Если данные отправлены - заполняем атрибуты в моделе
	        $form->attributes=$_POST['RemindForm'];
	        # В поле verifyCode передаем значение которое ввёл пользователь
	        $form->verifyCode = $_POST['RemindForm']['verifyCode'];
	        
	        # Пробуем пройти валидацию
	        if($form->validate()) {
	            //exit("Валидация прошла успешно");
	            //echo '2<br>';
	            //////////////////ищем пользователя
	            $user = Clients::model()->findByAttributes(array('login'=>$form->email));
	            if (isset($user)) {
	                //echo 'найден по логину<br>';
	                $r = $form->remindpassword($user);
	                //echo 'r = '.$r.'<br>';
	                $succeess_msg = "New password was sent";
	                if ($r==1) {
	                    //$form->addErrors(array('findit'=>'Пароль выслан, проверьте вашу почту'));
	                    Yii::app()->user->setFlash('contact','New password was sent. Check your email box');
	                    $this->refresh();
	                    //echo 'ewrwer';
	                    exit();
	                }
	            }//////////////if (isset($user)) {
	            elseif(isset($user)==false) {
	                $user = Clients::model()->findByAttributes(array('client_email'=>$form->email));
	                if (isset($user)) {
	                    //echo 'найден по email<br>';
	                    
	                    $r = $form->remindpassword($user);
	                    if ($r==1) {
	                        // $form->addErrors(array('findit'=>'Пароль выслан, проверьте вашу почту'));
	                        Yii::app()->user->setFlash('contact','New password was sent. Check your email box');
	                        $this->refresh();
	                        exit();
	                        
	                    }	///////////if ($r==1) {
	                    elseif ($r==2)  $form->addErrors(array('findit'=>'Email was not provided'));
	                }/////////if (isset($user)) {
	                else {//else 1
	                    //echo 'не найден по мылу<br>';
	                    $form->addErrors(array('didntfind'=>'Login provided was not found'));
	                    //exit();
	                }	////////////
	            }//////////////elseif (isset($user)==false) {
	            
	        } else {
	            # Ошибка в валидации.
	            # На экран будут отправлены ошибки
	        }
	    }
	    
	    # Выводим страницу с формой на экран
	    $this->render('remind', array('form' => $form));
	    
	    
	}
	
}