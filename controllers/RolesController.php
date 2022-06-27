<?
class RolesController extends Controller
//class RolesController extends CController
{
	const PAGE_SIZE=30;

	public $defaultAction='index';
	private $page;


	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function filters()
	{
		return array(
					'accessControl',
					'CheckAuthorityUpdateUsers + Update, Details, Usersave ',/////////////////////////проверка прав
					'CheckAuthorityCreateUsers + Createuser , searchusers',/////////////////////////проверка прав
					'CheckAuthorityCreateRoles + Addauthitem, Manageauthitems',
		);
	}

	public function  filterCheckAuthorityCreateRoles($filterChain)	{
		//////////Проверка, был ли передан файл
		//echo var_dump(Yii::app()->user->checkAccess('Правка товаров'));
		if (Yii::app()->user->checkAccess('Create_Roles') ) $filterChain->run();
		else {
			throw new CHttpException(401,'У вас нет прав для управления пользователями 1');
		}
	}

	public function  filterCheckAuthorityUpdateUsers($filterChain)	{
		//////////Проверка, был ли передан файл
		//echo var_dump(Yii::app()->user->checkAccess('Правка товаров'));
		//var_dump (Yii::app()->user->checkAccess('Edit User')) ;
		//exit();
		if (Yii::app()->user->checkAccess('Edit User') ) $filterChain->run();
		else {
			throw new CHttpException(401,'У вас нет прав для управления пользователями 2');
		}
	}

	public function  filterCheckAuthorityCreateUsers($filterChain)	{
		//////////Проверка, был ли передан файл
		//echo var_dump(Yii::app()->user->checkAccess('Правка товаров'));
		if (Yii::app()->user->checkAccess('Create User') ) $filterChain->run();
		else {
			throw new CHttpException(401,'У вас нет прав для управления пользователями 3');
		}
	}

	public function accessRules()
	{
		return array(
		/*
			array('allow',  //////////////Разрешили только авторизованным
		'actions'=>array('FromCrone'),
		'users'=>array('*'),
		'ips'=>array('127.0.0.1', '89.111.176.234', '89.31.82.3' ),
		),
		*/

		array('allow',  //////////////Разрешили только авторизованным
				'actions'=>array('list', 'process', 'index', 'update', 'Createuser', 'Details', 'Usersave', 'Roleslist', 'Gettasklist', 'Addauthitem', 'Manageauthitems', 'searchusers'),
				'users'=>array('@'),
		// 'expression'=>'Yii::app()->user->getState("role")==User::ROLE_ADMIN',
		),
			
		array('allow',  //////////////Разрешили только авторизованным
				'actions'=>array('process'),
				'users'=>array('@'),
		),


		array('deny', // deny all users
			'users'=>array('*'),
		),
		);
	}

	public function init() {
		$this->layout='admin';
	}

	public function filterIsFileUploaded($filterChain)	{
		//////////Проверка, был ли передан файл
		if (trim($_FILES['ExchangeFileForm']['name']['file_to_upload'])) $filterChain->run();
		else {
			$this->actionFileSelect();
		}
	}


	public function actionList() {

		//

		///////////////Вывод списка ролей
		$criteria=new CDbCriteria;
		//$criteria->order = 'name';
		//$criteria->condition = 'term_hierarchy.parent=9';
		$models = Authitem::model()->with('authassignment', 'child_items')->findAll($criteria);//
		$this->render('list', array('models'=>$models));
	}

	public function actionIndex() {

		//var_dump (Yii::app()->user->checkAccess('Edit User')) ;

		
		
		///////////////Вывод списка пользователей
		$this->layout='admin';
		$sort=Yii::app()->getRequest()->getParam('sort', 0);
		$criteria=new CDbCriteria;
		switch ($sort) {
			case 1:
				$sort_order = 't.second_name';
				break;
			case 2:
				$sort_order = 't.first_name';
				break;
			case 3:
				$sort_order = 't.login';
				break;
			//case 6:
			//	$sort_order = 'kontragent.name';
			//	break;
			case '4d':
				$sort_order = 't.id DESC';
				break;
			case '5':
				$sort_order = 't.last_vizit';
				break;
			case '5d':
				$sort_order = 't.last_vizit DESC';
				break;

			default:
				$sort_order='t.id';
		}
		$criteria->order=" $sort_order ";

		//echo $sort_order;

		$rec_num = Yii::app()->getRequest()->getParam('rec_num', self::PAGE_SIZE);
		$pages=new CPagination(User::model()->count($criteria));
		$pages->params=array('sort'=>$sort);
		if ($rec_num != self::PAGE_SIZE) $pages->params['rec_num']=$rec_num;
		$pages->pageSize=$rec_num;
		$pages->applyLimit($criteria);
		//$models = User::model()->with('authassignment', 'profile_values')->findAll($criteria);//

		//$models = Clients::model()->with('authassignment', 'kontragents')->findAll($criteria);//
		$models = Clients::model()->with('authassignment')->findAll($criteria);//

	//	$ugr = Client_groups::model()->findAll();
		//if (isset($ugr)) $client_groups=CHtml::listdata($ugr, 'id', 'name');
		//$client_groups = array(0=>'Нет') + $client_groups;
		//asort($client_groups);
		//print_r($client_groups);
		
	
		
		$this->render('users', array('models'=>$models, 'pages'=>$pages, 'roles_list'=>$this->RolesList(), 'client_groups'=>@$client_groups ) );
	}
	
	
	public function actionSearchusers(){
			$search = Yii::app()->getRequest()->getParam('search') ;
			if (isset($search) AND trim($search)!='' 	 AND strlen(trim($search))>2)    {
			$criteria=new CDbCriteria;
			$criteria->addCondition("t.login  LIKE  '%$search%'  OR t.first_name   LIKE  '%$search%'  OR t.second_name LIKE  '%$search%'   OR t.client_email LIKE  '%$search%' ");
			$models = User::model()->with('authassignment')->findAll($criteria);//
			if (isset($models)) {
					
					ob_start();
					$this->renderPartial('partialusers', array('models'=>$models, 'roles_list'=>$this->RolesList() ));
					$view = ob_get_contents();
					ob_end_clean();
					echo $view ;
					
			}
			else  echo 'n/a';
			}
			else echo 'n/a';
	}//////public function actionSearchusers(){

	private function RolesList() {
		////////////////Вытаскиваем список ролей
		$criteria=new CDbCriteria;
		$criteria->condition = 'type=2';
		$roles = Authitem::model()->findAll($criteria);
		$roles_list['']='...select';
		for ($i=0; $i<count($roles); $i++) $roles_list[$roles[$i]->name] =  $roles[$i]->name;
		return $roles_list;
	}

	public function actionUpdate() {
		////////////////Сохранение ролей и статуса пользователей из списка
		//print_r($_POST);
		if (isset($_POST['status']) OR isset ($_POST['itemname']) ){
			$statuses = @$_POST['status'];
			$kontragent_id = @$_POST['kontragent_id'];
			$kontragent_id = str_replace('/', '', $kontragent_id);
			$client_vip = Yii::app()->getRequest()->getParam('client_vip');	
			$data = @$_POST['data'];
			//exit();
			$delete_urlico_link = @$_POST['delete_urlico_link'];
			$roles = @$_POST['itemname'];
			$delete_user = @$_POST['delete_user'];

			//////////////Добавление контрагента пользователям
			foreach($kontragent_id as $userid=>$contragent) {
						if ($contragent>0) {
							$new_me = new Merchant_employers;
							$new_me->client_id = $userid;
							$new_me->contr_agent_id= $contragent;
							try {
								$new_me->save();
							} catch (Exception $e) {
								echo 'Сохранение в Merchant_employers: ',  $e->getMessage(), "\n";
							}/////
						}//if ($contragen>0) {
				}///foreach($kontragent_id as $
			
			if (isset($client_vip)) foreach($client_vip AS $uid=>$c_vip) {
				if (isset($uid)) {
					$CL = Clients::model()->findByPk($uid);
					if (isset($CL)) {
						$CL->client_vip = $c_vip;
						try {
										$CL->save();
									} catch (Exception $e) {
										echo 'Ошибка задания группы пользователя: ',  $e->getMessage(), "\n";
									}/////
					}
				}
			}//////////
			
			foreach($roles AS $uid=>$role) {
				//echo $uid.'<br>';
				if ($uid>0) {

					$attributes = array('userid'=>$uid);

					$USERAUTH = Authassignment::model()->findByAttributes($attributes);
					if (!isset($USERAUTH)) {
						/////////Если не найдено назначение пользователя
						$USERAUTH = new  Authassignment;
						$USERAUTH->userid = $uid;
					}////////	if isset($USERAUTH) {
					if(trim($role)!='') {
						$USERAUTH->itemname=$role;
						if (isset($data[$uid])) $USERAUTH->data =(string)$data[$uid];
						else $USERAUTH->data = NULL;
						try {
							$USERAUTH->save();
						} catch (Exception $e) {
							echo 'Обновление роли пользователя: role='.$role.' ',  $e->getMessage(), "\n";
						}/////
					}///////////if(trim($role)!='') {

					else if(trim($role)=='' AND $USERAUTH->isNewRecord==false)  try {
						$USERAUTH->delete();
					} catch (Exception $e) {
						echo 'Удаление роли пользователя: role='.$role.' ',  $e->getMessage(), "\n";
					}/////

					/* ////закоментировал 03.12.2019, потому что кнопка сохранить  (в списке http://yii-site/roles/update?page=) затирает статус пользователя
					$USER = User::model()->findByPk($uid);
					if (isset($statuses[$uid])) $st = 1;
					else $st = 0;
					if($USER!=null) $USER->status=$st;

					if(is_object($USER) == true) try {
						$USER->save();
					} catch (Exception $e) {
						echo 'Сохранение пользователя n2: ',  $e->getMessage(), "\n";
					}/////
					*/
					//print_r($USERAUTH);

					/////////////////Теперь удаляем
					//print_r($delete_urlico_link);
					//exit();
					if(isset($delete_urlico_link)) foreach($delete_urlico_link as $userid=>$ca_arr) {
								//////////////////Удаляем контрагентов от пользователя
							foreach ($ca_arr as $ca=>$del) {
							if ($del==1) {
								$attributes = array('client_id'=>$userid, 'contr_agent_id'=>$ca);
								$merch = Merchant_employers::model()->findByAttributes($attributes);
								if ($merch!=NULL) {
									try {
										$merch->delete();
									} catch (Exception $e) {
										echo 'Ошибка удаления связи с организацией: ',  $e->getMessage(), "\n";
									}/////
								}//////////if ($merch!=NULL) {
							}////del
							}///////foreach ($ca_arr as $ca=>$del)
					}///////////fforeach($delete_urlico_link[$uid] as $ca=>$val) {//////////////////Уда

					if (isset($delete_user[$uid])) {
						$USER = User::model()->findByPk($uid);
						if($USER!=null) $USER->delete();

						$attributes = array('userid'=>$uid);
						$USERAUTH = Authassignment::model()->findByAttributes($attributes);

						try {
							if($USER !=NULL) $USER->delete();
							if($USERAUTH !=NULL)$USERAUTH->delete();
							if (isset($merch)) $merch->delete();
						} catch (Exception $e) {
							echo 'Ошибка удаления пользователя: ',  $e->getMessage(), "\n";
						}/////

						$attributes = array('client_id'=>$uid);
						$merchs = Merchant_employers::model()->findAllByAttributes($attributes);
						if (isset($merchs)) {
							for ($k=0; $k<count($merchs); $k++) {
							
							
								try {
									$merchs[$k]->delete();
									
								} catch (Exception $e) {
									echo 'Ошибка удаления связи с организацией при удалении пользователя: ',  $e->getMessage(), "\n";
								}/////
							}//////////for ($k=0; $k<count($merch); $k++) {
						}////if (isset($merch)) {

					}///////////if (isset($delete_user[$uid])) {

				}/////////if ($uid>0) {
			}
		}//////if (isset($_POST['status']) OR isset ($_POST['itemname']) ){
		$this->actionIndex();////////Переходим к списку
	}


	public function actionCreateuser() {
		////////////Добавление нового пользователя с ролью User
/*
		$USER = new User();
		$USER->login=time()."New User";
		//$USER->pass=md5("1234");
		$USER->client_password = "1234";
			
		//$USER->created=time();
		try {
			$USER->save();
		} catch (Exception $e) {
			echo 'Сохранение пользователя: ',  $e->getMessage(), "\n";
		}/////
		$USERAUTH = new  Authassignment;
		$USERAUTH->itemname="User";
		$USERAUTH->userid=$USER->id;
		if(isset($role) AND !trim($role)=='') $USERAUTH->itemname=$role;
		try {
			$USERAUTH->save();
		} catch (Exception $e) {
			echo 'Сохранение роли пользователя: ',  $e->getMessage(), "\n";
		}/////

		//$this->actionIndex();////////Переходим к списку
		$this->redirect(Yii::app()->request->baseUrl."/roles/index", true, 301);
		*/
	    

	    
		$form=new  RegisterFormLight;
		$form_parametrs = Yii::app()->getRequest()->getParam('main_user_params');
		$save_close_user = Yii::app()->getRequest()->getParam('save_close_user', null);
		$user_role = Yii::app()->getRequest()->getParam('user_role');
		
		if (isset($form_parametrs)) {
			$form->setAttributes($form_parametrs, false);
			$qqq = $form->validate();
			
			if ($qqq == true) { ///////////////Проверка, верны ли данные формы
				
				
					$USER = new User;
					$USER->status=1;
					$USER->login = microtime();
					$USER->save();
					try {
						$USER->saveAttributes($form_parametrs);
						Yii::app()->user->setFlash('user_message','<h2>Создан новый пользователь</h2>');
						if (isset($USER->id) AND isset($user_role) AND empty($user_role)==false AND trim($user_role)!='') {
								$USERAUTH = new  Authassignment;
								$USERAUTH->itemname=$user_role;
								$USERAUTH->userid=$USER->id;
								try {
									$USERAUTH->save();
								} catch (Exception $e) {
									echo 'Сохранение роли пользователя: ',  $e->getMessage(), "\n";
								}/////
						}///////if (isset($user_role) AND empty($user_role)==false AND trim($user_role)!='') {
						
						if(isset($_POST['save_close_user'])) $this->redirect(Yii::app()->request->baseUrl."/roles/index/".$USER->id, true, 301);
						else $this->redirect(Yii::app()->request->baseUrl."/roles/details/".$USER->id, true, 301);
						exit();
					} catch (Exception $e) {
						$mess = $e->getMessage();
						if (!strstr($mess, 'Duplicate entry' ) ) echo 'Ошибка сохранения пользователя: ',  $e->getMessage(), "\n";
						else $msg="Пользователь с таким логином уже существует !!!! ";
					}/////

				
			}/////////if ($qqq == true) { //////
			
			
		}////////if (isset($form_parametrs)) {
		//exit();
		if (isset($user_role)) $form['authassignment'] = $user_role;
		
		else $this->render('user_details_real', array('form'=>$form, 'roles_list'=>$this->RolesList() ) );
		
	}///////////////functiom

	public function actionDetails(){
	    
	    if(isset($_POST['saveuser']) || isset($_POST['save_close_user'])) {
	       if(isset($_POST['save_close_user'])) $this->actionUsersave(true);
	       if(isset($_POST['saveuser'])) $this->actionUsersave(false);
	    }
	    
			$uid=Yii::app()->getRequest()->getParam('uid', Yii::app()->getRequest()->getParam('id'));
			$emptytemplate=Yii::app()->getRequest()->getParam('emptytemplate', null);
			
			if($emptytemplate==1) $this->layout="empty";
			
			if (isset(Yii::app()->urlManager->urlSuffix) AND trim(Yii::app()->urlManager->urlSuffix)!='') $uid = str_replace(Yii::app()->urlManager->urlSuffix, '', $uid);
			
			if (isset($uid)) $USER = Clients::model()->with('authassignment')->findByPk($uid);
			$form=new  RegisterFormLight;
			
			if (isset($_POST)==false OR empty($_POST)==true) {
					$form->setAttributes($USER->attributes, false);
					if (isset($USER->authassignment)) $form->authassignment = $USER->authassignment->itemname;
			}
			
			$criteria=new CDbCriteria;
			$criteria->condition = 'id_client='.$uid;
			$orders_list=Orders::model()->findAll($criteria);
			
			$this->render('user_details_real', array('user'=>$USER,'form'=>$form, 'roles_list'=>$this->RolesList(), 'orders_list'=>$orders_list ) );
		
			
	}/////////public function actionDetails(){

	public function actionUsersave($closeWindow=false){
		/////////////Сохранение всех параметров пользователя
		/*
		print_r($_POST);
		echo '<br>';
		exit();
		*/
		
		$uid = str_replace('/', '', Yii::app()->getRequest()->getParam('id',  Yii::app()->getRequest()->getParam('uid') ) );
		if (trim($uid)=='') {
			$this->actionIndex();
			exit();
		}///////////////////
		
		$emptytemplate=Yii::app()->getRequest()->getParam('emptytemplate', null);

	
		
		if (isset($uid)) $USER = User::model()->with('card')->findByPk($uid);
		$form_parametrs = Yii::app()->getRequest()->getParam('main_user_params');
		//$auto_alias = Yii::app()->getRequest()->getParam('auto_alias');
		//if(trim($form_parametrs['client_password']=='')) unset($form_parametrs['client_password']);
				
		if(!isset($form_parametrs['status']))  $form_parametrs['status']=0;
	
		
		$form=new RegisterFormLight;
		$form->setAttributes($form_parametrs, false);
		if (isset($USER->id)) $form->userid = $USER->id;
		$qqq = $form->validate();
		
		if ($qqq == true) { ///////////////Проверка, верны ли данные формы
		    
		    
		    /////////////Т.е. если прилетает не пустой статус
		    ////////////// Нужно проверить предыдущее значение, и если оно "0", то слать письмо пользователю об изменении статуса
		    if(isset($USER)){
		        if ($USER->status==0 && isset($form_parametrs['status'])){
		            //$this->reportUserStatusChange($form_parametrs);
		        }
		    }
		    
			
			if (isset($USER)) {
				$form_parametrs['id'] = $USER->id;
				try {
					$USER->saveAttributes($form_parametrs);
					Yii::app()->user->setFlash('user_message','<h2>Saved</h2>');
				} catch (Exception $e) {
					$mess = $e->getMessage();
					if (!strstr($mess, 'Duplicate entry' ) ) echo 'Ошибка сохранения пользователя: ',  $e->getMessage(), "\n";
					else $msg="Пользователь с таким логином уже существует !!!! ";

				}/////
			}/////////if (isset($USER)) {
			
		}/////////if ($qqq == true) { //////
		else {
			print_r($form->errors);
			var_dump($form->hasErrors());
			exit();
		}
			
		if (@$USER != NULL) {
			/*	
			if (count($_POST['main_user_params'])>0) {
				$parametrs = $_POST['main_user_params'];
				//print_r($parametrs);
				//($parametrs[status]=='on') ? $parametrs[status] = 1 : $parametrs[status] = 0;
				if ($parametrs['client_password']==NULL OR trim($parametrs['client_password'])=='')  $parametrs['client_password'] = $USER->client_password;
				//print_r($parametrs);
				try {
					$USER->saveAttributes($parametrs);
					Yii::app()->user->setFlash('user_message','<h2>Сохранено</h2>');
				} catch (Exception $e) {
					$mess = $e->getMessage();
					if (!strstr($mess, 'Duplicate entry' ) ) echo 'Ошибка сохранения пользователя: ',  $e->getMessage(), "\n";
					else $msg="Пользователь с таким логином уже существует !!!! ";
				}/////
			}/////if (count($_POST['parametrs'])>0) {
			*/	
			//echo $_POST['user_role'];
			///////////////Вытаскиваем роль
			$Authassignment= Authassignment::model()->findByAttributes(array('userid'=>$uid));
			if ($Authassignment == NULL)  {
				$Authassignment = new Authassignment;
				$Authassignment->userid = $uid;
			}
			$Authassignment->itemname = trim(htmlspecialchars($_POST['user_role']));
			try {
				$Authassignment->save();
				$form->authassignment = $Authassignment->itemname;
			} catch (Exception $e) {
				echo 'Ошибка сохранения роли: ',  $e->getMessage(), "\n";
			}/////
			//	}////////////////////if ($Authassignment != NULL) {


			if (isset($_POST['value'])) {
				/////Дополнительные поля пользователя
				foreach($_POST['value'] AS $fid=>$value) {

					$attributes = array('uid'=>$uid, 'fid'=>$fid);
					$Profile_values = Profile_values::model()->findByAttributes($attributes);
					if (!isset($Profile_values)) {
						/////////Если не найдено
						$Profile_values = new  Profile_values;
						$Profile_values->uid = $uid;
						$Profile_values->fid = $fid;
					}///////if (!isset($Profile_values)) {/////
					$Profile_values->value=$value;
					try {
						$Profile_values->save();
					} catch (Exception $e) {
						echo 'Caught exception: ',  $e->getMessage(), "\n";
					}/////
				}/////////foreach($_POST['values'] AS $fid=>$vulue) {
			}///////////////////////////////////////////////////////////////////////////////////if (isset($_POST['value'])) {/////Допол/

			//////////Скидочная карта
			if(isset($_POST['ClientCards'])){
				
				//print_r($_POST['ClientCards']);
				//exit();
				if(isset($USER->card) && $USER->card!=NULL && $_POST['ClientCards']['type']){
					$USER->card->type = $_POST['ClientCards']['type'];
					$USER->card->save();
				}
				elseif(isset($_POST['ClientCards']['add']) && $_POST['ClientCards']['type']){
					//.1.Чистим таблицу client_cards на данного пользователя
					ClientCards::model()->deleteAllByAttributes(['client_id' => $USER->id]);
					if($_POST['ClientCards']['type']!=''){
						$CC = new ClientCards();
						$CC->client_id = $USER->id;
						$CC->type = $_POST['ClientCards']['type'];
						
						$criteria=new CDbCriteria;
						$criteria->order = 'number DESC';
						$cn = ClientCards::model()->find($criteria);
						if($cn!=NULL){
							$CC->number = $cn->number+1;
						}
						$CC->save();
					}
				}
				if(isset($_POST['ClientCards']['delete'])){
					ClientCards::model()->deleteAllByAttributes(['client_id' => $USER->id]);
				}
			}
			
			
		}/////////////if ($USER != NULL) {
			
		
		
		

		if (isset($_POST['save_close_user'])) {
			//$this->actionIndex() ;/*CHtml::refresh(1, $url='/roles/');  Yii::app()->getRequest()->redirect('/roles/');*/
			$msg="Saved.";
			$this->render('saved', array('msg'=>$msg, 'page'=>Yii::app()->getRequest()->getParam('page'), 'sort'=>Yii::app()->getRequest()->getParam('sort')));
			exit();
		}//////////////if ($_POST['save_close_user']) {
		//else $this->actionDetails(@$msg);
		//$this->render('user_details_real', array('user'=>$USER, 'form'=>$form, 'roles_list'=>$this->RolesList() ) );
		//$this->actionIndex();
		$redir_params = array('id'=>$USER->id);
		if(isset($emptytemplate) && $emptytemplate==1){
		    $redir_params['emptytemplate']=1;
		}
 
		if($closeWindow==true){
		    echo'<script>
                window.close();
            </script>';
		    exit();
		}
		else{
		    $redir_url = Yii::app()->createUrl("/roles/details", $redir_params);
		
    		$this->redirect($redir_url, true, 301);
    		exit();
		}
	}///////////////public function actionUsersave(){/////////////Сохранение в

	///////////Уведомление пользователя о смене статууса
	function reportUserStatusChange($client){
	    $headers="From: ".Yii::app()->params['infoEmail']."\r\n";
	    $headers.='Content-type: text/html; UTF-8' . "\r\n";
	    
	    
	    $msg = $this->renderPartial('notify/status_change', array('client'=>(object)$client), true);
	    
	    //echo $msg;
	    //exit();
	    try {
	       mail($client['client_email'], iconv( "UTF-8", "CP1251",'Client status change notification'), iconv( "UTF-8", "CP1251",  $msg), $headers);
	    } catch (Exception $e) {
	        print_r($e);
	        exit();
	    }
	}

	public function actionRoleslist() {
		/////////Вывод списка ролей
			
		$criteria=new CDbCriteria;
		$criteria->condition = "t.type=2 ";
		$roles = Authitem::model()->with('child_items')->findAll($criteria);
		//$this->render('roleslist', array('roles'=>$roles));
			
		$criteria=new CDbCriteria;
		$criteria->condition = "t.type=1 AND t.name <> 'User Manager'";
		$tasks = Authitem ::model()->with('operations', 'roles')->findAll($criteria);////////////////выбор задач
			
			
			
		$criteria=new CDbCriteria;
		$criteria->condition = "t.type=2 AND t.name<> 'Copied' ";
		$allroles= Authitem ::model()->findAll($criteria);//////////////Выбор всех ролей
		//for ($i=0; $i<count($allroles); $i++) $allroleslist[$allroles[$i]->name]=$allroles[$i]->name;
			
		$this->render('authrights', array('tasks'=>$tasks, 'allroles'=>$allroles, 'roles'=>$roles));
	}////////public function actionRoleslist {/////////Вывод списко ролей/////////////

	public function actionAddauthitem(){
		////////Добавляем новый объект авторизации
		//print_r($_POST);
		//$role_select = Yii::app()->getRequest()->getParam('role_select', 4);
		$auth_item_type = Yii::app()->getRequest()->getParam('auth_item_type', 4);
		$role_name = Yii::app()->getRequest()->getParam('role_name', NULL);
		$task_name= Yii::app()->getRequest()->getParam('task_name', NULL);
		$task_select = Yii::app()->getRequest()->getParam('task_select', NULL);
		$role_select = Yii::app()->getRequest()->getParam('role_select', NULL);
		$operation_name = Yii::app()->getRequest()->getParam('operation_name', NULL);
		$add_task = @$_POST['add_task'];
		/*
			echo 'auth_item_type = '.$auth_item_type.'<br>';
		echo 'task_select = '.$task_select.'<br>';
		echo 'role_select = '.$role_select.'<br>';
		echo 'operation_name = '.$operation_name.'<br>';
		*/
			
		if ($auth_item_type >=0 AND  $auth_item_type <4) {
			//////////////Проверили вообще что добавляем
			if ($auth_item_type==2 AND trim($role_name) ) {
				////////Добавляем новую роль
				$Authitem = new Authitem();
				$Authitem->name = $role_name;
				$Authitem->type=2;
				try {
					$Authitem->save();
				} catch (Exception $e) {
					echo 'Caught exception: ',  $e->getMessage(), "\n";
				}/////
			}/////////////////////////////////
			elseif($auth_item_type==1 AND trim($task_name) AND $role_select!=NULL) {
				////////Добавляем task для выбранной роли
				$NEW_TASK = new Authitem();
				$NEW_TASK->name = $task_name;
				$NEW_TASK->type=1;
				try {
					$NEW_TASK->save();
				} catch (Exception $e) {
					echo 'Caught exception: ',  $e->getMessage(), "\n";
				}/////
				$Authitemchild = new Authitemchild();
				$Authitemchild->parent = 	$role_select;
				$Authitemchild->child = $task_name;
				try {
					$Authitemchild->save();
				} catch (Exception $e) {
					echo 'Caught exception: ',  $e->getMessage(), "\n";
				}/////
			}///////////////elseif($auth_item_type==1 AND trim($task_name) AND $role_select!=NULL) {////////Добав
			elseif($auth_item_type==0 AND $task_select!=NULL AND $role_select!=NULL AND trim($operation_name)) {
				////////Добавляем operation для выбранной роли и таска	$NEW_TASK = new Authitem();

				$NEW_OPERATION = new Authitem();
				$NEW_OPERATION->name = $operation_name;
				$NEW_OPERATION->type=0;
				try {
					$NEW_OPERATION->save();
				} catch (Exception $e) {
					echo 'Caught exception: ',  $e->getMessage(), "\n";
				}/////
				$Authitemchild = new Authitemchild();
				$Authitemchild->parent = 	$task_select;
				$Authitemchild->child = $operation_name;
				try {
					$Authitemchild->save();
				} catch (Exception $e) {
					echo 'Caught exception: ',  $e->getMessage(), "\n";
				}/////

			}///////////elseif($auth_item_type==0 AND $task_select!=NULL AND $role_select!=NULL AND trim($operation_name)) {////////Добавл

		}	/////////////if ($role_select >=0 AND  $role_select <4) {//////////////Проверили вообще что добавляе
			

			
			
		$this->actionRoleslist();
	}/////////////////public function actionAddauthitem(){////////Добавляем


	public function actionManageauthitems(){
		///////////////Обработка добавления роли из таблицы
		//print_r($_POST);
		$add_task = @$_POST['add_task'];
		$delete_from_role = @$_POST['delete_from_role'];
		$manage_operation= @$_POST['manage_operation'];
		$manage_role = @$_POST['manage_role'];
		//print_r($manage_role);

		if ( @$add_task !=NULL) {
			/////////Обработка добавления роли из таблицы
			//print_r($add_task);
			foreach ($add_task as $task=>$role):
			if ($role!='0')  {
				/////////Если для задания задана роль, то добавляем
				$Authitemchild = new Authitemchild();
				$Authitemchild->parent = 	$role;
				$Authitemchild->child = $task;
				try {
					$Authitemchild->save();
				} catch (Exception $e) {
					echo 'Ошибка установки подчиненности 1 : ',  $e->getMessage(), "\n";
				}/////
				////echo $task.' -> '.$role;
			}/////////if ($role!='0')  {
			endforeach;
		}////////////if ( @$add_task !=NULL) {/////////Обр

		if ($delete_from_role!=NULL) {
			/////////Удаление заданий из роли
			//print_r($delete_from_role);
			foreach ($delete_from_role as $role=>$task_array):
			foreach ($task_array as $task=>$value):
			if ($value==1) {
				////////Удаялем
				$Authitemchild = NULL;
				$Authitemchild = Authitemchild::model()->findByAttributes(array('parent'=>$role, 'child'=>$task) );
				if ($Authitemchild !=NULL)   try {
					$Authitemchild->delete();
				} catch (Exception $e) {
					echo 'Ошибка удаления подчиненности  : ',  $e->getMessage(), "\n";
				}/////
			}/////////if ($value==1) {////////Удаялем
			endforeach;
			endforeach;
		}//////////////if ($delete_from_role!=NULL) {/////////Удаление заданий из роли

		if ($manage_operation != NULL) {
			//////////////Удаление операций, перенос в другие задания
			//print_r($manage_operation);
			foreach ($manage_operation as $task=>$operation_array):
			foreach ($operation_array as $operation=>$action):
			if ($action != '0') {
				if ($action == 'delete') {
					///////Удаляем операцию
					$Authitem = Authitem::model()->findByPk($operation);
					if ($Authitem != NULL ) try {
						$Authitem->delete();
					} catch (Exception $e) {
						echo 'Ошибка удаления операции  : ',  $e->getMessage(), "\n";
					}/////

				}/////////if ($action == 'delete') {
				if ($action == 'delete_link') {
					$Authitemchild = NULL;
					$Authitemchild = Authitemchild::model()->findByAttributes(array('parent'=>$task, 'child'=>$operation) );
					if ($Authitemchild !=NULL)   try {
						$Authitemchild->delete();
					} catch (Exception $e) {
						echo 'Ошибка удаления подчиненности  : ',  $e->getMessage(), "\n";
					}/////
				}////////////
				if ($action=='copy') {
					//////////////Копирование
					$Authitemchild = new Authitemchild();
					$Authitemchild->parent = 	'Copied';
					$Authitemchild->child = $operation;
					//echo $operation;
					try {
						$Authitemchild->save();
					} catch (Exception $e) {
						echo 'Ошибка установки подчиненности 2 : ',  $e->getMessage(), "\n";
					}/////
				}//////////////////if ($action='copy') {//////////////Коп
				else {
					$Authitemchild = Authitemchild::model()->findByAttributes(array('parent'=>$task, 'child'=>$operation) );
					if ($Authitemchild !=NULL)  {
						$Authitemchild->parent=$action;
						try {
							$Authitemchild->save();
						} catch (Exception $e) {
							echo 'Ошибка переноса операции  : ',  $e->getMessage(), "\n";
						}/////
					}////////////if ($Authitemchild !=NULL)  {
				}///////else {
			}////////////if ($action != '0') {
			endforeach;
			endforeach;
		}///////////////if ($manage_operation != NULL) {//////////////Удаление операций, перенос в другие задания

		if ($manage_role !=NULL) {
			///////////Отработка операций над ролью
			foreach ($manage_role as $role=>$task):
			if ($task!='0' AND $task!='delete')  {
				/////////Если для задания задана роль, то добавляем
				$Authitemchild = new Authitemchild();
				$Authitemchild->parent = 	$role;
				$Authitemchild->child = $task;
				try {
					$Authitemchild->save();
				} catch (Exception $e) {
					echo 'Ошибка установки подчиненности 3 : ',  $e->getMessage(), "\n";
				}/////
				////echo $task.' -> '.$role;
			}////////if ($task!='0' AND $task!='delete')  {/////////Если
			if ($task=='delete') {
				///////
				$Authitem = Authitem::model()->findByPk($role);
				if ($Authitem != NULL ) try {
					$Authitem->delete();
				} catch (Exception $e) {
					echo 'Ошибка удаления операции  : ',  $e->getMessage(), "\n";
				}/////
			}//////////if ($task!='delete') {
			endforeach;
		}////////////////////////


		$this->actionRoleslist();
	}//////////public function actionManageauthitems(){///////////////Обработка добавл


	public function actionGettasklist() {
		////////Вытаскиваем список операций для роли ( ajax по запросу элемента формы)
		$role_select = Yii::app()->getRequest()->getParam('role_select', 0);
		$criteria=new CDbCriteria;
		$criteria->condition = "parent= '$role_select' ";
		$data = Authitemchild::model()->findAll($criteria);
		for ($i=0; $i<count($data); $i++)  $task_list[$data[$i]->child]=$data[$i]->child;///////////Список брендов для списка выбора
		//print_r($brands_list);
		//$data=CHtml::listData($data,'id','name');
		//print_r($data);
		//$data=CHtml::listData($brands_list,'id','name');
		$data = $task_list;
			
		if (count($data)) {
			echo CHtml::tag('option',
			//array('value'=>$value),CHtml::encode($name),true);
			array('value'=>0),iconv("CP1251", "UTF-8", 'выбор'),true);
			foreach($data as $value=>$name)
			{
				echo CHtml::tag('option',
				//array('value'=>$value),CHtml::encode($name),true);
				//array('value'=>$value),iconv("CP1251", "UTF-8", $name),true);
				// array('value'=>iconv("CP1251", "UTF-8",$value) ),iconv("CP1251", "UTF-8", $name),true);////для сайта в кирилице
				array('value'=>$value), $name,true);
			}
		}
		else {
			echo CHtml::tag('option',
			//array('value'=>$value),CHtml::encode($name),true);
			array('value'=>'0'),iconv("CP1251", "UTF-8", 'нет вариантов'),true);
		}
	}////////////////////public function actionGettasklist() {////
}//////////class
?>