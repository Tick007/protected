<?php

class SiteController extends Controller
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>isset(Yii::app()->params['captcha'])?Yii::app()->params['captcha']:array(
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
			'CheckBrouser +index, contact, page, login, contact, map, error, register',
			'CheckAuthority +admin',
			'CheckPath +index',
			'SetTheme +index, contact, page, map, login, register',
		);
	}
	
	
	public function  filterCheckPath($filterChain)	{//////////Проверка, был ли передан файл 
			$host_in =  $_SERVER['HTTP_HOST']; 
			preg_match('@^(?:www.)?([^/]+)@i', $host_in, $matches);
			$host = $matches[1];
			//print_r($matches);
			//echo 'host_in = '.$host_in.'<br>';
			//echo 'host = '.$host;
			//exit();		
			
			$uri = Yii::app()->getRequest()->requestUri;
			//echo $uri.'<br>';
			if ($uri== "/index.php" OR ( ($host != $host_in) AND trim($host) <>'' )){
					$host=str_replace('www.', '', $host_in);
					$this->redirect('http://'.$host, true, 301);
					exit();
			}
			if ($uri== "/site/index/" OR $uri== "/site/index"){
					//echo 'ertert';
					$this->redirect('http://'.$host.'/', true, 301);
					exit();
			}
			$filterChain->run();
		}
	
	public function  filterCheckAuthority($filterChain)	{//////////Проверка, был ли передан файл
			//echo var_dump(Yii::app()->user->checkAccess('Правка товаров'));
			if (Yii::app()->user->checkAccess('Правка товаров') ) $filterChain->run();
			else {
			throw new CHttpException(404,'Страница не доступна');
			}
		}
	
		/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		

		
		
			$this->layout="main";
		//$connection = Yii::app()->db;
		//	$chapter = 1;
	//		$query_inclusion="SELECT theme_files.file, theme_files.name  FROM theme_chapters_files  JOIN theme_files ON theme_chapters_files.file_id = theme_files.id 
//			WHERE theme_chapters_files.theme_id = ".Yii::app()->GP->GP_theme."  AND theme_chapters_files.chapter_id = $chapter AND file_enabled = 1 AND location='C' ORDER BY theme_chapters_files.sort ";
//			$command=$connection->createCommand($query_inclusion)	;
//			$dataReader=$command->query();
//			while(($row=$dataReader->read())!==false) {
			/*
			$file_to_incl = ('Vitrina');
			Yii::import('components\$file_to_incl');
			$SM = new $file_to_incl;
			$models = $SM->Draw();
			//$fname=basename($file_to_incl);
			*/
			/*
				$ProductList=new Product;
				$ProductList->product_vitrina = 1;
				$ProductList->creteria = " AND parent_categories.show_category = 1";
				$ProductList->ExecuteObject(); ////////////перенес из констракта в отдельный метод
				$ProductList->offset = 0;
				$ProductList->limit = 10;
				$models = $ProductList->run_query();
		        //print_r($models);
//				}
				
				
				
				$LastIncomeList=new Product;
				$LastIncomeList->orderby = "products.id DESC";
				$LastIncomeList->creteria = " AND parent_categories.show_category = 1";
				$LastIncomeList->ExecuteObject(); ////////////перенес из констракта в отдельный метод
				$LastIncomeList->offset = 0;
				$LastIncomeList->limit = 25;
							
				$income_list= $LastIncomeList->run_query();
				//$LastIncomeList->order_by = NULL;
				*/
				////////////////////Смотрим последние поступления
				/*
				$criteria=new CDbCriteria;
				 $criteria->select=array( 't.*',  'picture_product.picture AS icon' );
				 $criteria->condition="t.product_price>0  AND product_visible=1";
				  $criteria->order=" t.id DESC ";
				 $criteria->join ="
				LEFT JOIN ( SELECT id, product, picture FROM picture_product WHERE is_main=1) picture_product ON picture_product.product = t.id  ";
				 $criteria->addCondition("t.product_visible = 1");
				 $criteria->limit  = 12;
			 	$income_list = Products::model()->with('belong_category')->findAll($criteria);
				*/
			
			/*
				$criteria=new CDbCriteria;
				$criteria->select=array( 't.*',  'picture_product.picture AS icon' );
				$criteria->condition="t.product_price>0  AND product_visible=1";
				$criteria->order=" t.id DESC ";
				$criteria->join ="
					LEFT JOIN ( SELECT id, product, picture FROM picture_product WHERE is_main=1) picture_product ON picture_product.product = t.id  ";
				$criteria->addCondition("t.product_visible = 1");
				$criteria->limit  = 12;
				$income_list = Products::model()->with('belong_category')->findAll($criteria);
			
				
				*/
				$params = array();
				if(isset($models))$params['models'] = $models;
				if(isset($models))$params['income_list'] = $income_list;
				
				
				
				$this->render('index',$params); 
	}

	/**
	 * Displays the contact page
	 */
	public function actionContact()
	{
		$layout= Yii::app()->getRequest()->getParam('layout', NULL);
		
		if($layout!=null && trim($layout)!=''){
			$layout = trim($layout);
			//$layout_file = Yii::app()->theme->basePath.'\\views\\layouts\\'.$layout.'.php'; //windows
			$layout_file = Yii::app()->theme->basePath.'/views/layouts/'.$layout.'.php'; ///linux
			//echo $layout_file;
			if(file_exists($layout_file) && is_file($layout_file)){
				$this->layout = $layout;
			}
		}
		
		$contact=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			
			//print_r($contact->attributes);
		//	if ($_POST['ContactForm']['email']=='телефонный звонок') $_POST['ContactForm']['email']=Yii::app()->params['infoEmail'];
				if (isset( $_POST['ContactForm']['email']) && $_POST['ContactForm']['email']=='телефонный звонок') $_POST['ContactForm']['email']=NULL;
			$contact->attributes=$_POST['ContactForm'];
			if($contact->validate())
			{
				
				//print_r($contact->attributes);
				//exit();
				//$headers="From: {$contact->email}\r\nReply-To: {$contact->email}";
				$headers="From: ".Yii::app()->params['infoEmail']."\r\n";
				$headers.='Content-type: text/html; charset=windows-1251' . "\r\n";
				$subject = '';
				if(isset($contact->name)) $subject.=$contact->name.' ';
				$subject =  $subject.=$contact->subject;
				$message = 'Сообщение:<br/>'.$contact->name.'<br/> '.$contact->body.'<br/> '.$contact->email.'<br/> '.$contact->subject; 
				
				mail(Yii::app()->params['infoEmail'], iconv( "UTF-8", "CP1251",$subject), iconv( "UTF-8", "CP1251",  $message),$headers);
				//mail(Yii::app()->params['supportEmail'], iconv( "UTF-8", "CP1251",$subject), iconv( "UTF-8", "CP1251", $message),$headers);
				
				Yii::app()->user->setFlash('contact','Спасибо что обратились. Мы свяжемся с вами в ближайшее время.');
				$this->refresh();
			}
		}
		$this->render('contact',array('contact'=>$contact));
	}

	public function actionOrdercallback(){
		$tel= Yii::app()->getRequest()->getParam('tel', NULL);
		if(Yii::app()->request->isAjaxRequest){
				$headers="From: ".Yii::app()->params['infoEmail']."\r\n";
				$headers.='Content-type: text/html; charset=windows-1251' . "\r\n";
				@mail(Yii::app()->params['infoEmail'], iconv( "UTF-8", "CP1251",'Заказ обратного звонка'), iconv( "UTF-8", "CP1251","Запрос звонка на номер $tel"),$headers);
				echo '<div class="ordered">
					<p>Ваша заявка на обратный звонок отправлена. </p>
					<p>Оператор  свяжется с Вами <br>
					  в ближайшее время. </p>
					<p>Спасибо.</p>
					</div>';
			}
	}
	

	/**
	 * Displays the login page
	 	  */
	public function actionRegister() {
	$form=new RegisterForm;
	
		if(isset($_POST['RegisterForm']))
		{
			$form->attributes=$_POST['RegisterForm'];
			// validate user input and redirect to previous page if valid
			//if($form->validate())	$this->redirect("http://".$_SERVER['HTTP_HOST']."/index.php?r=privateroom");
			if($form->validate())	$this->redirect("http://".$_SERVER['HTTP_HOST']);
			//$form->validate();
			//echo $form->validate();
		}
			//echo "weqeqwe";
			
		
	$this->render('register',array('form'=>$form));
	}///////public function actionRegister() {
	
	
    
	
	public function actionLogin()
	{
		
	    ///////////Для авторизации из PopUp окон (или слоев) без перехода на строницу логина
	    if(Yii::app()->request->isAjaxRequest){
	        
	        $form=new LoginForm;
	        // collect user input data
	        if(isset($_POST['LoginForm']))
	        {
	            
	            $form->attributes=$_POST['LoginForm'];
	            if($form->validate()){
	                $form->authenticate(null, null);
	                if(isset(Yii::app()->user->enterUrl) && trim(Yii::app()->user->enterUrl)!='') {
	                    $res['redirect'] = Yii::app()->user->enterUrl;

	                }
	                $res['auth_result']=true;
                    echo json_encode($res);
                    Yii::app()->end();
	            }
	            else {
	                echo CActiveForm::validate($form); ///////В таком виде html форме ответ уже понятен
	                Yii::app()->end();
	            }
	           
	            
	        }
	        
	    }
	    else{
    		$form=new LoginForm;
    		// collect user input data
    		if(isset($_POST['LoginForm']))
    		{
    			$form->attributes=$_POST['LoginForm'];
    			//$form->scenario='login';
    			// validate user input and redirect to previous page if valid
    			
    			if($form->validate()){
    				//echo Yii::app()->user->returnUrl.'<br>';
    				//echo '<pre>';
    				//print_r(Yii::app()->user);
    				$prev = Yii::app()->Request->getUrlReferrer();
    				if(isset(Yii::app()->user->enterUrl) && trim(Yii::app()->user->enterUrl)!='') $user_return = Yii::app()->user->enterUrl;
    				$user_return = Yii::app()->user->returnUrl;
    				//echo '</pre>';
    				//exit();
    				$compare_string = '/';
    				$compare_string2 = '/index.php';
    				//if($_SERVER['HTTP_HOST']=='yii-site') $compare_string = '/';
    				/* /////////////Орлов заброковал
    				if(trim($user_return)!=$compare_string && trim($user_return)!=$compare_string2) $this->redirect($user_return);
    				else $this->redirect($prev);
    				*/
    				$this->redirect($user_return);
    			}
    				
    		}
    
    		$this->render('login',array('form'=>$form));
	    }
	}///////////public function action

	public function actionLogincombined()
	{
			
		$form=new LoginForm;
		$regform=new RegisterForm('casaarte');
		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$form->attributes=$_POST['LoginForm'];
			//$form->scenario='login';
			// validate user input and redirect to previous page if valid
			if($form->validate())
				$this->redirect(Yii::app()->user->returnUrl);
		}
		
		if(isset($_POST['RegisterForm']))
		{
			$regform->attributes=$_POST['RegisterForm'];
			// validate user input and redirect to previous page if valid
			//if($form->validate())	$this->redirect("http://".$_SERVER['HTTP_HOST']."/index.php?r=privateroom");
			if($regform->validate())	$this->redirect("http://".$_SERVER['HTTP_HOST']);
			//$form->validate();
			//echo $form->validate();
		}
		

		$this->render('login',array('form'=>$form, 'regform'=>$regform));

	}///////////public function action



	/**
	 * Logout the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}
	
	public function actionNew(){
			
			$this->render('new');
	}
	
	public function actionigortest() {
		$form=new IgortestForm;
		$this->render('igortest', array('form'=>$form));
	}////////public function igortest() {
	
	public function actionvideostrov() {
		$form=new VideostrovForm;
		$this->render('videostrov', array('form'=>$form));
	}////////public function actionvideostrov() {
	
	public function actionAdmin() {///////////////////Вывод главной траницы администрирования
				$this->render('adminindex');
	}///////////////////public function actionAdmin() {/////////////////Вывод главной траницы администри
	
	public function actionMap(){
		/*
			$this->layout="main_index";
			$criteria=new CDbCriteria;
			$criteria->condition="t.section = 5 AND active 	= 1" ; 
			$criteria->order="t.title" ; 
			$models=Page::model()->findAll($criteria);*/
			//$this->layout="main_index";
			$criteria=new CDbCriteria;
			$criteria->condition="t.parent IN(:parent, 760, 761) AND t.show_category 	= 1" ; 
			$criteria->order="t.category_name, child_categories.sort_category" ;
			//$criteria->order="child_categories.sort_category" ;
			$criteria->params=array(':parent'=>Yii::app()->params['regions_root']);
			$models=Categories::model()->with('child_categories')->findAll($criteria);
			//echo count($models);
			
			///////////////Прогоняем области, создаем массив по первым буквам
			if(isset($models)) {
				for($i=0; $i<count($models); $i++ ) {
					//echo 	mb_substr($models[$i]->title, 0,1, 'utf-8');
					$models_by_name[mb_substr($models[$i]->category_name, 0,1, 'utf-8')][]=$models[$i];
				}
			}
			
			$this->render('map', array('models'=>$models, 'models_by_name'=>$models_by_name));
	}///////public function actionMap(){
		
	public function actionInstall(){///////////////Вывод яндекс карты с центрами установки
		$this->layout="main_index";
		//'install_centers'
		$criteria=new CDbCriteria;
		$criteria->condition="t.category_belong = ".Yii::app()->params['install_centers']." AND t.product_visible = 1 AND t.product_html_description<>''" ; 
		
		$models = Products::model()->findAll($criteria);
		$this->render('install', array('models'=>$models));
	}	
	
	
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
	
	
	public function actionCooperation() { ///////////что типа расширеной формы контактов
	$this->layout = 'main_index'; 
		$contact=new CooperationForm;
			if(isset($_POST['CooperationForm']))
			{
				
				$contact->attributes=$_POST['CooperationForm'];
				if($contact->validate())
				{
					//$headers="From: {$contact->email}\r\nReply-To: {$contact->email}";
					$headers="From: ".$contact->email."\r\n";
					$headers.='Content-type: text/html; charset=windows-1251' . "\r\n";
					$message=$this->renderPartial('cooperation/mailtext', array('contact'=>$contact, 'product'=>$product), true);
					$possible_ext=array('doc', 'docx', 'rtf', 'pdf');
					
					foreach($possible_ext as $extension) {
					$new_file_name = $_SERVER['DOCUMENT_ROOT'].Yii::app()->request->baseUrl.'/pictures/temp/'.md5(Yii::app()->session->sessionId).'.'.$extension;
                    if(file_exists($new_file_name) AND is_file($new_file_name))	 {
							
							$atchname =  $_SERVER['DOCUMENT_ROOT'].Yii::app()->request->baseUrl.'/pictures/temp/attachment.'.$extension;
							
							@rename($new_file_name, $atchname);
							
							$filename = $atchname;
							break;
						}
					}
					
					if(isset($filename)) $qqq = FHtml::XMail( Yii::app()->params['infoEmail'], Yii::app()->params['jobEmail'], 'На сайте оставленно резюме', $message, $filename);
					else @mail(Yii::app()->params['cooperationEmail'], iconv( "UTF-8", "CP1251",'Предложение о сотрудничестве'), iconv( "UTF-8", "CP1251", $message),$headers);
					
					
					@unlink($filename);
					Yii::app()->user->setFlash('vacancy','Ваша заявка отправлена.');
					$contact=new CooperationForm;////////\Обнуляем
					//$this->refresh();
				}
			}
			
			
			
			
		
			$params=array();
			$params['contact']=$contact;
			$this->render('cooperation', $params); 
	}////////////public function actionCooperation() {
		
		
		public function actionRequestregister() { ///////////Форма а ля запроса регистрации
		    
		    $contact=new RegisterrequestForm;
		    ///////////Для авторизации из PopUp окон (или слоев) без перехода на строницу логина
		    if(Yii::app()->request->isAjaxRequest){ /////////для ajax

		        if(isset($_POST['RegisterrequestForm']))
		        {
		        $contact->attributes=$_POST['RegisterrequestForm'];
		        if($contact->validate()){
		            /*
		            $contact->authenticate(null, null);
		            $res['auth_result']=true;
		            echo json_encode($res);
		            Yii::app()->end();*/
		            $this->sendRegisterRequest($contact);
		              
		            $res['request_result']='<div class="request_send"><strong>Благодарим за регистрацию</strong><br><br>
После подтверждения Ваших данных<br>на указанную почту придет<br>уведомление</div>';
		            echo json_encode($res);
		            Yii::app()->end();
		          
		        }
		        else {
		            echo CActiveForm::validate($contact); ///////В таком виде html форме ответ уже понятен
		            Yii::app()->end();
		        }
		        }
		        
		        
		    }
		    else{
        		if(isset($_POST['RegisterrequestForm']))
        		{
        	
        			$contact->attributes=$_POST['RegisterrequestForm'];
        			if($contact->validate())
        			{
        				//$headers="From: {$contact->email}\r\nReply-To: {$contact->email}";
        				//$headers="From: ".$contact->email."\r\n";
        				$this->sendRegisterRequest($contact);
        					
        	
        	   
            			Yii::app()->user->setFlash('register_request','Ваша заявка отправлена.');
            			$contact=new RegisterrequestForm;////////Обнуляем
            			$this->redirect(Yii::app()->createUrl('site/requestregister'));
            			exit();
        				//$this->refresh();
        			}
        		}
        			
        			
        			
        			
        	
        		$params=array();
        		$params['contact']=$contact;
        		$this->render('registerrequest', $params);
		
		}
		
	}////////////public function actionCooperation() {
		
	function sendRegisterRequest($contact){
	    $headers="From: $contact->name <info@".$_SERVER['HTTP_HOST'].">\r\n";
	    $headers.='Content-type: text/html; charset=windows-1251' . "\r\n";
	    $headers.='Reply-To:info@'.$_SERVER['HTTP_HOST']."\r\n";
	    $headers.="X-Mailer: PHP/" . phpversion();
	    if(isset($contact->brand) && trim($contact->brand)!='' && is_numeric($contact->brand)){
	        $brand_cat = Categories::model()->findByPk(trim($contact->brand));
	        if($brand_cat!=null){
	            $contact->brand = $brand_cat->category_name;
	        }
	    }
	    
	    //print_r($contact);
	    //exit();
	    
	    $message=$this->renderPartial('registerrequest/mailtext', array('contact'=>$contact), true);
	    $possible_ext=array('doc', 'docx', 'rtf', 'pdf');
	    
	    foreach($possible_ext as $extension) {
	        $new_file_name = $_SERVER['DOCUMENT_ROOT'].Yii::app()->request->baseUrl.'/pictures/temp/'.md5(Yii::app()->session->sessionId).'.'.$extension;
	        if(file_exists($new_file_name) AND is_file($new_file_name))	 {
	            
	            $atchname =  $_SERVER['DOCUMENT_ROOT'].Yii::app()->request->baseUrl.'/pictures/temp/attachment.'.$extension;
	            
	            @rename($new_file_name, $atchname);
	            
	            $filename = $atchname;
	            break;
	        }
	    }
	    
	    if(isset($filename)) $qqq = FHtml::XMail( Yii::app()->params['infoEmail'], Yii::app()->params['jobEmail'], 'Заявка на регистрацию', $message, $filename);
	    else {
	        
	        //ini_set("SMTP","extmail.novline.com" );
	        //ini_set('sendmail_from', 'tick007@yandex.ru');
	        
	        @mail(Yii::app()->params['infoEmail'], iconv( "UTF-8", "CP1251",'Заявка на регистрацию'), iconv( "UTF-8", "CP1251", $message),$headers);
	        
	        @mail(Yii::app()->params['adminEmail'], iconv( "UTF-8", "CP1251",'Заявка на регистрацию'), iconv( "UTF-8", "CP1251", $message),$headers);
	        
	        //@mail('vadim.sadchikov@novline.com', iconv( "UTF-8", "CP1251",'Заявка на регистрацию'), iconv( "UTF-8", "CP1251", $message),$headers);
	        
	        
	    }
	    @unlink($filename);
	}
	
	
}//////////class