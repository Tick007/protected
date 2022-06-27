<?
class RolesController extends Controller
//class RolesController extends CController
{
	const PAGE_SIZE=20;

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
					'CheckAuthorityCreateUsers + Createuser ',/////////////////////////проверка прав
					'CheckAuthorityCreateRoles + Addauthitem, Manageauthitems',
				);
		}
		
	 public function  filterCheckAuthorityCreateRoles($filterChain)	{//////////Проверка, был ли передан файл
			//echo var_dump(Yii::app()->user->checkAccess('Правка товаров'));
			if (Yii::app()->user->checkAccess('Create_Roles') ) $filterChain->run();
			else {
			throw new CHttpException(401,'У вас нет прав для управления пользователями');
			}
	}
	 
	 public function  filterCheckAuthorityUpdateUsers($filterChain)	{//////////Проверка, был ли передан файл
			//echo var_dump(Yii::app()->user->checkAccess('Правка товаров'));
			if (Yii::app()->user->checkAccess('Edit User') ) $filterChain->run();
			else {
			throw new CHttpException(401,'У вас нет прав для управления пользователями');
			}
	}
	
	public function  filterCheckAuthorityCreateUsers($filterChain)	{//////////Проверка, был ли передан файл
			//echo var_dump(Yii::app()->user->checkAccess('Правка товаров'));
			if (Yii::app()->user->checkAccess('Create User') ) $filterChain->run();
			else {
			throw new CHttpException(401,'У вас нет прав для управления пользователями');
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
				'actions'=>array('list', 'process', 'index', 'update', 'Createuser', 'Details', 'Usersave', 'Roleslist', 'Gettasklist', 'Addauthitem', 'Manageauthitems'),
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
        Yii::app()->layout = "admin";
    }
		
	public function filterIsFileUploaded($filterChain)	{//////////Проверка, был ли передан файл
			if (trim($_FILES['ExchangeFileForm']['name']['file_to_upload'])) $filterChain->run();
			else {
			$this->actionFileSelect();
			}
	}
	

	public function actionList() {///////////////Вывод списка ролей
		$criteria=new CDbCriteria;
		//$criteria->order = 'name';
		//$criteria->condition = 'term_hierarchy.parent=9';
		$models = Authitem::model()->with('authassignment', 'child_items')->findAll($criteria);//
		$this->render('list', array('models'=>$models));
	}
	
	public function actionIndex() {///////////////Вывод списка пользователей
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
				case 6:
					$sort_order = 'kontragent.name';
					break;

				default: 
					$sort_order='t.id';
				}
		$criteria->order=" $sort_order ";
		
		//echo $sort_order;
		
		$pages=new CPagination(User::model()->count($criteria));
		$pages->params=array('sort'=>$sort);
		$pages->pageSize=self::PAGE_SIZE;
		$pages->applyLimit($criteria);
		//$models = User::model()->with('authassignment', 'profile_values')->findAll($criteria);//
		
		
		$models = User::model()->with('authassignment', 'kontragent')->findAll($criteria);//
		
		
		$this->render('users', array('models'=>$models, 'pages'=>$pages, 'roles_list'=>$this->RolesList() ) );
	}
	
	private function RolesList() {
	////////////////Вытаскиваем список ролей
		$criteria=new CDbCriteria;
		$criteria->condition = 'type=2';
		$roles = Authitem::model()->findAll($criteria);
		$roles_list['']='Выбор';
		for ($i=0; $i<count($roles); $i++) $roles_list[$roles[$i]->name] =  $roles[$i]->name;
	return $roles_list;
	}
	
	public function actionUpdate() {////////////////Сохранение ролей и статуса пользователей из списка
			//print_r($_POST);
			if (isset($_POST['status']) OR isset ($_POST['itemname']) ){
			$statuses = $_POST['status'];
			$kontragent_id = $_POST[kontragent_id];
			$delete_urlico_link = $_POST[delete_urlico_link];
			$roles = $_POST['itemname'];
			$delete_user = $_POST['delete_user'];
					foreach($roles AS $uid=>$role) {
							//echo $uid.'<br>';
							if ($uid>0) {
							
							$attributes = array('userid'=>$uid);
							
							$USERAUTH = Authassignment::model()->findByAttributes($attributes);
							if (!isset($USERAUTH)) {/////////Если не найдено назначение пользователя
							$USERAUTH = new  Authassignment;
							$USERAUTH->userid = $uid;
							}////////	if isset($USERAUTH) {
							if(trim($role)!='') {
							$USERAUTH->itemname=$role;
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
							
							$USER = User::model()->findByPk($uid);
							if (isset($statuses[$uid])) $st = 1;
							else $st = 0;
							$USER->status=$st;
							
							//echo $kontragent_id[$uid];
							////////////////////Апдейтим юрлицо
							if (@!$delete_urlico_link[$uid]) $USER->urlico=$kontragent_id[$uid];
							else  $USER->urlico=0;
							
							if(is_object($USER) == true) try {
									$USER->save();
									} catch (Exception $e) {
									 echo 'Удаление пользователя n2: ',  $e->getMessage(), "\n";
									}/////				
							//print_r($USERAUTH);
							
							/////////////////Теперь удаляем
							if (isset($delete_user[$uid])) {
								$USER = User::model()->findByPk($uid);
								$USER->delete();
								
								$attributes = array('userid'=>$uid);
								$USERAUTH = Authassignment::model()->findByAttributes($attributes);
								
								try {
										if($USER !=NULL) $USER->delete();
										if($USERAUTH !=NULL)$USERAUTH->delete();
									} catch (Exception $e) {
									 echo 'Ошибка удаления пользователя: ',  $e->getMessage(), "\n";
									}/////	
								
							}///////////if (isset($delete_user[$uid])) {
							
							}/////////if ($uid>0) {
					}
			}//////if (isset($_POST['status']) OR isset ($_POST['itemname']) ){
			$this->actionIndex();////////Переходим к списку
	}
	
	
	public function actionCreateuser() {////////////Добавление нового пользователя с ролью User

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
					if(!trim($role)=='') $USERAUTH->itemname=$role;
									try {
											$USERAUTH->save();
											} catch (Exception $e) {
											 echo 'Сохранение роли пользователя: ',  $e->getMessage(), "\n";
											}/////

					//$this->actionIndex();////////Переходим к списку
					$this->redirect(Yii::app()->request->baseUrl."/roles/index", true, 301);
	}///////////////functiom

	public function actionDetails($msg=NULL){//////////Вывод всех полей пользователя
			$uid=Yii::app()->getRequest()->getParam('uid');
			//$USER = User::model()->with('authassignment', 'profile_values')->findByPk($uid);
			$USER = User::model()->with('authassignment')->findByPk($uid);
			//$FIELDS = Profile_fields::model()->findAll();
			Yii::app()->layout = "nomenu";
			
			$criteria=new CDbCriteria;
			$criteria->condition = 'id_client='.$uid;
			$orders_list=Orders::model()->findAll($criteria);
			
			$this->render('user_details_real', array('user'=>$USER, 'roles_list'=>$this->RolesList() , 'FIELDS'=>$FIELDS, 'msg'=>$msg, 'orders_list'=>$orders_list) );
			
	}
	
	public function actionUsersave(){/////////////Сохранение всех параметров пользователя
			//print_r($_POST);
			//echo '<br>';
			$uid = Yii::app()->getRequest()->getParam('uid');
			if (trim($uid)=='') {
					$this->actionIndex();
					exit();
			}///////////////////
			$USER = User::model()->findByPk($uid);
			
					/*		if (isset($_POST['status'])) $st = 1;
							else $st = 0;
							$USER->status=$st;
							$USER->name = $_POST['name'];
							if(trim($_POST['pass'])!='') $USER->pass = md5($_POST['pass']);
							$USER->mail = $_POST['mail'];
							*/
				//print_r($USER);
							
				if (@$USER != NULL) {			
							
							if (count($_POST['main_user_params'])>0) {
								$parametrs = $_POST['main_user_params'];
								//print_r($parametrs);
								//($parametrs[status]=='on') ? $parametrs[status] = 1 : $parametrs[status] = 0;
								if ($parametrs[client_password]==NULL OR trim($parametrs[client_password])=='')  $parametrs[client_password] = $USER->client_password;
								//print_r($parametrs);
								try {
										$USER->saveAttributes($parametrs);
										} catch (Exception $e) {
										$mess = $e->getMessage();
											if (!strstr($mess, 'Duplicate entry' ) ) echo 'Ошибка сохранения пользователя: ',  $e->getMessage(), "\n";
											else $msg="Пользователь с таким логином уже существует !!!! ";
										}/////		
							}/////if (count($_POST['parametrs'])>0) {				
					
							///////////////Вытаскиваем роль
							$Authassignment= Authassignment::model()->findByAttributes(array('userid'=>$uid));
							if ($Authassignment != NULL) {
									$Authassignment->itemname = trim(htmlspecialchars($_POST[user_role]));
									try {
										$Authassignment->save();
										} catch (Exception $e) {
										 echo 'Ошибка сохранения роли: ',  $e->getMessage(), "\n";
										}/////		
							}////////////////////if ($Authassignment != NULL) {
							
									
				if (isset($_POST['value'])) {/////Дополнительные поля пользователя
						foreach($_POST['value'] AS $fid=>$value) {
						
								$attributes = array('uid'=>$uid, 'fid'=>$fid);
								$Profile_values = Profile_values::model()->findByAttributes($attributes);
								if (!isset($Profile_values)) {/////////Если не найдено 
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
				
				}/////////////if ($USER != NULL) {			
			
	

			if ($_POST['save_close_user']) {
					//$this->actionIndex() ;/*CHtml::refresh(1, $url='/roles/');  Yii::app()->getRequest()->redirect('/roles/');*/
					if (trim($msg)=='') $msg="Сохранено.";
					$this->render('saved', array('msg'=>$msg, 'page'=>Yii::app()->getRequest()->getParam('page'), 'sort'=>Yii::app()->getRequest()->getParam('sort')));
			}//////////////if ($_POST['save_close_user']) {
			else $this->actionDetails($msg );
	}///////////////public function actionUsersave(){/////////////Сохранение в

	
	public function actionRoleslist() {/////////Вывод списка ролей
			
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
	
	public function actionAddauthitem(){////////Добавляем новый объект авторизации 
			//print_r($_POST);
			//$role_select = Yii::app()->getRequest()->getParam('role_select', 4);
			$auth_item_type = Yii::app()->getRequest()->getParam('auth_item_type', 4);
			$role_name = Yii::app()->getRequest()->getParam('role_name', NULL);
			$task_name= Yii::app()->getRequest()->getParam('task_name', NULL);
			$task_select = Yii::app()->getRequest()->getParam('task_select', NULL);
			$role_select = Yii::app()->getRequest()->getParam('role_select', NULL);
			$operation_name = Yii::app()->getRequest()->getParam('operation_name', NULL);
			$add_task = $_POST[add_task];
/*			
			echo 'auth_item_type = '.$auth_item_type.'<br>';
			echo 'task_select = '.$task_select.'<br>';
			echo 'role_select = '.$role_select.'<br>';
			echo 'operation_name = '.$operation_name.'<br>';
*/
			
			if ($auth_item_type >=0 AND  $auth_item_type <4) {//////////////Проверили вообще что добавляем
					if ($auth_item_type==2 AND trim($role_name) ) {////////Добавляем новую роль
							$Authitem = new Authitem();
							$Authitem->name = $role_name;
							$Authitem->type=2;
							try {
										$Authitem->save();
									} catch (Exception $e) {
									 	echo 'Caught exception: ',  $e->getMessage(), "\n";
									}/////	
					}/////////////////////////////////
					elseif($auth_item_type==1 AND trim($task_name) AND $role_select!=NULL) {////////Добавляем task для выбранной роли
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
					elseif($auth_item_type==0 AND $task_select!=NULL AND $role_select!=NULL AND trim($operation_name)) {////////Добавляем operation для выбранной роли и таска	$NEW_TASK = new Authitem();

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
	
	
	public function actionManageauthitems(){///////////////Обработка добавления роли из таблицы
			//print_r($_POST);
			$add_task = $_POST[add_task];
			$delete_from_role = $_POST[delete_from_role];
			$manage_operation= $_POST[manage_operation];
			$manage_role = $_POST[manage_role];
			//print_r($manage_role);
	
				if ( @$add_task !=NULL) {/////////Обработка добавления роли из таблицы
					//print_r($add_task);
					foreach ($add_task as $task=>$role):
						if ($role!='0')  {/////////Если для задания задана роль, то добавляем
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
				
				if ($delete_from_role!=NULL) {/////////Удаление заданий из роли
						//print_r($delete_from_role);
						foreach ($delete_from_role as $role=>$task_array):
								foreach ($task_array as $task=>$value):
										if ($value==1) {////////Удаялем
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
				
				if ($manage_operation != NULL) {//////////////Удаление операций, перенос в другие задания
						//print_r($manage_operation);
						foreach ($manage_operation as $task=>$operation_array):
								foreach ($operation_array as $operation=>$action):
										if ($action != '0') {
												if ($action == 'delete') {///////Удаляем операцию
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
												if ($action=='copy') {//////////////Копирование
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
				
				if ($manage_role !=NULL) {///////////Отработка операций над ролью
						foreach ($manage_role as $role=>$task):
								if ($task!='0' AND $task!='delete')  {/////////Если для задания задана роль, то добавляем
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
								if ($task=='delete') {///////
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
	
	
	public function actionGettasklist() {////////Вытаскиваем список операций для роли ( ajax по запросу элемента формы)
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
											 array('value'=>iconv("CP1251", "UTF-8",$value) ),iconv("CP1251", "UTF-8", $name),true);
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