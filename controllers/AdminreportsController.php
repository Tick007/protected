<?php

class AdminreportsController extends CController //////////////Главный контроллер администрирования товаров
{
	const PAGE_SIZE=10;/////////////////Количество записей на страницу

	/**
	 * @var string specifies the default action to be 'list'.
	 */
	public $defaultAction='sales';

	/**
	 * @var CActiveRecord the currently loaded data model instance.
	 */
	private $_model;


	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			//'CheckAuthority +pricelist, list, updatedocument',
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
				'actions'=>array('sales', 'subgroups', 'movement', 'stores_series', 'stores'),
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
	
	public function  filterCheckAuthority($filterChain)	{//////////Проверка, был ли передан файл
			//echo var_dump(Yii::app()->user->checkAccess('Правка товаров'));
			if (Yii::app()->user->checkAccess('Edit Documents') ) $filterChain->run();
			else {
			throw new CHttpException(401,'У вас нет прав на работу с документами');
			}
		}
		
	public function init() {
        Yii::app()->layout = "admin";
    }
	
	
	private function save_report_settings($report_type, &$parametrs, $name_to_save) {//////////Сохранение настроек для отчета
			$SS = new Sotrudniki_settings;
			$SS->sotrudnik_id = Yii::app()->user->id;
			$SS->settings_type = $report_type;
			$SS->title = trim(htmlspecialchars($name_to_save));
			try {
						$SS->save();
						} catch (Exception $e) {
						 echo 'Caught exception: ',  $e->getMessage(), "\n";
						}///////////////////
			$setings_id = (int)$SS->id;
			//echo $setings_id;
			if ($setings_id>0) {
				foreach($parametrs as $parametr_name=>$value):
					if (trim($value)!='')	{
								$SV = new Sotrudniki_setting_values;
								$SV->setting_id = $setings_id;
								$SV->parametr_name = $parametr_name;
								($value=='on') ? $SV->parametr_value = 1 : $SV->parametr_value = $value;
								try {
									$SV->save();
									} catch (Exception $e) {
									 echo 'Caught exception: ',  $e->getMessage(), "\n";
									}///////////////////
						}//////if (trim($value)!='')	{
					endforeach;
			}//////////////if ($setings_id>0) {
	}/////////public function save_report_settings($report_type) {//////////Сохранение настроек для отчета
	
	private function read_report_settings($preset_id){
			$settings = Sotrudniki_settings::model()->with('params')->findByPk($preset_id);
			if ($settings !=NULL) {
					if ($preset_id != $settings->id)  {
						throw new CHttpException(404,'Это не ваша настройка');
						exit();
						}
			return $settings->params;
			}/////////if ($settings !=NULL) {
	}////////////
	
		private function delete_report_settings($preset_id) {//////////Сохранение настроек для отчета
			$settings = Sotrudniki_settings::model()->with('params')->findByPk($preset_id);
			if ($settings !=NULL) {
					if ($preset_id != $settings->id)  {
						throw new CHttpException(404,'Это не ваша настройка');
						exit();
						}
			}/////////if ($settings !=NULL) {
			
			if ($settings != NULL) {
				$sotr_vals = $settings->params;
				for ($i=0; $i<count($sotr_vals); $i++) {
								$SV = Sotrudniki_setting_values::model()->findByPk($sotr_vals[$i]->id);
								if ($SV != NULL) {
										try {
											$SV->delete(false);
											} catch (Exception $e) {
											 echo 'Caught exception: ',  $e->getMessage(), "\n";
											}///////////////////
								}////////		if ($SV != NULL) {	
					}////////////for ($i=0; $i<count(); $i++) {
					
					try {
					$settings->delete(false);
					} catch (Exception $e) {
					 echo 'Caught exception: ',  $e->getMessage(), "\n";
					}///////////////////
					
			}//////////	if ($settings != NULL) {
			
			
			
	}/////////public function save_report_settings($report_type) {//////////Сохранение настроек для отчета
	
	public function actionSales(){/////////////Отчет продажи по партиям
			//print_r($_POST);
			
			$preset_id = Yii::app()->getRequest()->getParam('id', NULL);
			$sgroup = Yii::app()->getRequest()->getParam('sgroup', NULL);
			$delpreset	= Yii::app()->getRequest()->getParam('delpreset', NULL);
			
			if ((int)$delpreset>0)	 $this->delete_report_settings($delpreset);

			if (count($_POST['parametrs'])>0 AND @!$preset_id) {
					$parametrs = $_POST['parametrs'];
					
			}/////if (count($_POST['parametrs'])>0) {
			else if ($preset_id>0) {
					$params= $this->read_report_settings($preset_id);
					for ($i=0; $i<count($params); $i++) {
							$parametrs[$params[$i]->parametr_name]=$params[$i]->parametr_value;
					}///////////for ($i=0; $i<count($params); $i++) {
					
					//print_r($parametrs);
					//echo $sgroup;
			}
					$sgroup = $parametrs[sgroup];
					$group = $parametrs[group];

if (isset($_POST['save_settings_as']) AND trim($_POST['save_settings_as'])!='' ) $this->save_report_settings (1, $parametrs, $_POST['save_settings_as']);
			
			//1.//////Главные группы
			$criteria=new CDbCriteria;
			$criteria->order = 't.category_name';
			$criteria->condition = " parent = :parent  ";
			$criteria->params=array(':parent'=>0);
			$maingroups = Categoriestradex::model()->findAll($criteria);
			
				
			 if((int)$sgroup>0) {
						$subgroups=Categoriestradex::model()->findAll('parent=:parent',  
						//array(':parent'=>(int) $_POST['models_list']  ));
						array(':parent'=>(int)$sgroup ));
						}
						
			/////////////////////////вытаскиваем список настроек с параметрами
			$criteria=new CDbCriteria;
			$criteria->order = 't.title ';
			$criteria->condition = " t.sotrudnik_id = :user  AND  t.settings_type = :settings_type";
			$criteria->params=array(':user'=>Yii::app()->user->id,':settings_type'=>1 );//////1 джя оборота
			$presets = Sotrudniki_settings ::model()->findAll($criteria);			
	
			if (isset($_POST['build'])) {
			////////////////////Основнй запрос
			$report = new Report;
			$report->parametrs=$parametrs;
			$rows = $report	->sales();
			}
			
			$this->render('sales', array('maingroups'=>$maingroups, 'subgroups'=>$subgroups, 'rows'=>$rows, 'parametrs'=>$parametrs, 'presets'=>$presets));
	}//////////////////public function actionSales(){/////////////Отчет продажи по партиям
	
	
	public function actionMovement(){/////////////Движение партий по складам
	
	//print_r($_POST);
	
			$preset_id = Yii::app()->getRequest()->getParam('id', NULL);
			$sgroup = Yii::app()->getRequest()->getParam('sgroup', NULL);
			$delpreset	= Yii::app()->getRequest()->getParam('delpreset', NULL);
			$add_product= Yii::app()->getRequest()->getParam('add_product', NULL);
			
			if ((int)$delpreset>0)	 $this->delete_report_settings($delpreset);

			if (count($_POST['parametrs'])>0 AND @!$preset_id) {
					$parametrs = $_POST['parametrs'];
					
			}/////if (count($_POST['parametrs'])>0) {
			else if ($preset_id>0) {
					$params= $this->read_report_settings($preset_id);
					for ($i=0; $i<count($params); $i++) {
							$parametrs[$params[$i]->parametr_name]=$params[$i]->parametr_value;
					}///////////for ($i=0; $i<count($params); $i++) {
					
					//print_r($parametrs);
					//echo $sgroup;
			}
					$sgroup = $parametrs[sgroup];
					$group = $parametrs[group];
					
			if (@$add_product!=NULL) {///////////////Добавляем товар
					if ($parametrs[goodlist]==NULL) $parametrs[goodlist]=$add_product;
					else if (@$parametrs[goodlist] != NULL) {
						$list = explode('#', $parametrs[goodlist]);
						if (in_array($add_product,$list) ==false ) $parametrs[goodlist]=$parametrs[goodlist].'#'.$add_product;
					}/////////////else if (@$parametrs[goodlist] != NULL) {
			}////////////if (@$add_product!=NULL) {///////////////Добавляем товар
			
			if (@$_POST[del_usluga] != NULL) {/////////////Удаление товаров из списка
					//print_r($_POST[del_usluga]);
					//echo '<br>';
					$p= explode('#', $parametrs[goodlist]);
					//print_r($p);
					//echo '<br>';
					$n=array_diff ($p, $_POST[del_usluga]);
					//print_r($n);
					$parametrs[goodlist]=implode('#', $n);
			}//////////////////if (@$del_usluga !=NULL) {/////////////Удаление товаров из списка
			

if (isset($_POST['save_settings_as']) AND trim($_POST['save_settings_as'])!='' ) $this->save_report_settings (2, $parametrs, $_POST['save_settings_as']);
			
			//1.//////Главные группы
			$criteria=new CDbCriteria;
			$criteria->order = 't.category_name';
			$criteria->condition = " parent = :parent  ";
			$criteria->params=array(':parent'=>0);
			$maingroups = Categoriestradex::model()->findAll($criteria);
			
				
			 if((int)$sgroup>0) {
						$subgroups=Categoriestradex::model()->findAll('parent=:parent',  
						//array(':parent'=>(int) $_POST['models_list']  ));
						array(':parent'=>(int)$sgroup ));
						}
						
			/////////////////////////вытаскиваем список настроек с параметрами
			$criteria=new CDbCriteria;
			$criteria->order = 't.title ';
			$criteria->condition = " t.sotrudnik_id = :user  AND  t.settings_type = :settings_type";
			$criteria->params=array(':user'=>Yii::app()->user->id,':settings_type'=>2 );//////1 дdb;tybt gfhnbq gj crkflfv
			$presets = Sotrudniki_settings ::model()->findAll($criteria);		
			
			
			if (isset($_POST['build'])) {
			////////////////////Основнй запрос
			$report = new Report;
			//print_r($parametrs);
			$report->parametrs=$parametrs;
			$rows = $report	->movement();
			$rows2 = $report	->movement2();
			}
			
			$this->render('movement', array('maingroups'=>$maingroups, 'subgroups'=>$subgroups, 'rows'=>$rows, 'rows2'=>$rows2, 'parametrs'=>$parametrs, 'presets'=>$presets));
	}////////////////Движение партий по складам
	
	public function actionStores_series(){/////////////Партии остатков на складах
			
			$preset_id = Yii::app()->getRequest()->getParam('id', NULL);
			$sgroup = Yii::app()->getRequest()->getParam('sgroup', NULL);
			$delpreset	= Yii::app()->getRequest()->getParam('delpreset', NULL);
			
			if ((int)$delpreset>0)	 $this->delete_report_settings($delpreset);

			if (count($_POST['parametrs'])>0 AND @!$preset_id) {
					$parametrs = $_POST['parametrs'];
					
			}/////if (count($_POST['parametrs'])>0) {
			else if ($preset_id>0) {
					$params= $this->read_report_settings($preset_id);
					for ($i=0; $i<count($params); $i++) {
							$parametrs[$params[$i]->parametr_name]=$params[$i]->parametr_value;
					}///////////for ($i=0; $i<count($params); $i++) {
					
					//print_r($parametrs);
					//echo $sgroup;
			}
					$sgroup = $parametrs[sgroup];
					$group = $parametrs[group];

if (isset($_POST['save_settings_as']) AND trim($_POST['save_settings_as'])!='' ) $this->save_report_settings (3, $parametrs, $_POST['save_settings_as']);
			
			//1.//////Главные группы
			$criteria=new CDbCriteria;
			$criteria->order = 't.category_name';
			$criteria->condition = " parent = :parent  ";
			$criteria->params=array(':parent'=>0);
			$maingroups = Categoriestradex::model()->findAll($criteria);
			
				
			 if((int)$sgroup>0) {
						$subgroups=Categoriestradex::model()->findAll('parent=:parent',  
						//array(':parent'=>(int) $_POST['models_list']  ));
						array(':parent'=>(int)$sgroup ));
						}
						
			/////////////////////////вытаскиваем список настроек с параметрами
			$criteria=new CDbCriteria;
			$criteria->order = 't.title ';
			$criteria->condition = " t.sotrudnik_id = :user  AND  t.settings_type = :settings_type";
			$criteria->params=array(':user'=>Yii::app()->user->id,':settings_type'=>3 );//////1 джя оборота
			$presets = Sotrudniki_settings ::model()->findAll($criteria);		
			
			if (isset($_POST['build'])) {
					////////////////////Основнй запрос
			$report = new Report;
			//print_r($parametrs);
			$report->parametrs=$parametrs;
			$rows = $report	->stores_series();
			}
			
			$criteria=new CDbCriteria;
		//$criteria->order = 't.date_dt DESC';
			$criteria->condition = " kontragent_id = :kontragent_id";
			$criteria->params=array(':kontragent_id'=>Yii::app()->GP->GP_self_contragent);
			$stores = Stores::model()->findAll($criteria);
			for($i=0; $i < count($stores); $i++) $stores_list[$stores[$i]->id]=$stores[$i]->name;
			
			$this->render('stores_series', array('maingroups'=>$maingroups, 'subgroups'=>$subgroups, 'rows'=>$rows, 'rows2'=>$rows2, 'parametrs'=>$parametrs, 'presets'=>$presets, 'stores_list'=>$stores_list));
	}///////////////////public function actionStores_series(){/////////////Партии остатков на складах
	
	public function actionStores (){///////////////////Остатки
			$preset_id = Yii::app()->getRequest()->getParam('id', NULL);
			$sgroup = Yii::app()->getRequest()->getParam('sgroup', NULL);
			$delpreset	= Yii::app()->getRequest()->getParam('delpreset', NULL);
			$add_product= Yii::app()->getRequest()->getParam('add_product', NULL);
			
			if ((int)$delpreset>0)	 $this->delete_report_settings($delpreset);

			if (count($_POST['parametrs'])>0 AND @!$preset_id) {
					$parametrs = $_POST['parametrs'];
					
			}/////if (count($_POST['parametrs'])>0) {
			else if ($preset_id>0) {
					$params= $this->read_report_settings($preset_id);
					for ($i=0; $i<count($params); $i++) {
							$parametrs[$params[$i]->parametr_name]=$params[$i]->parametr_value;
					}///////////for ($i=0; $i<count($params); $i++) {
					
					//print_r($parametrs);
					//echo $sgroup;
			}
					$sgroup = $parametrs[sgroup];
					$group = $parametrs[group];


if (@$add_product!=NULL) {///////////////Добавляем товар
					if ($parametrs[goodlist]==NULL) $parametrs[goodlist]=$add_product;
					else if (@$parametrs[goodlist] != NULL) {
						$list = explode('#', $parametrs[goodlist]);
						if (in_array($add_product,$list) ==false ) $parametrs[goodlist]=$parametrs[goodlist].'#'.$add_product;
					}/////////////else if (@$parametrs[goodlist] != NULL) {
			}////////////if (@$add_product!=NULL) {///////////////Добавляем товар
			
			if (@$_POST[del_usluga] != NULL) {/////////////Удаление товаров из списка
					//print_r($_POST[del_usluga]);
					//echo '<br>';
					$p= explode('#', $parametrs[goodlist]);
					//print_r($p);
					//echo '<br>';
					$n=array_diff ($p, $_POST[del_usluga]);
					//print_r($n);
					$parametrs[goodlist]=implode('#', $n);
			}//////////////////if (@$del_usluga !=NULL) {/////////////Удаление товаров из списка


if (isset($_POST['save_settings_as']) AND trim($_POST['save_settings_as'])!='' ) $this->save_report_settings (4, $parametrs, $_POST['save_settings_as']);
			
			//1.//////Главные группы
			$criteria=new CDbCriteria;
			$criteria->order = 't.category_name';
			$criteria->condition = " parent = :parent  ";
			$criteria->params=array(':parent'=>0);
			$maingroups = Categoriestradex::model()->findAll($criteria);
			
				
			 if((int)$sgroup>0) {
						$subgroups=Categoriestradex::model()->findAll('parent=:parent',  
						//array(':parent'=>(int) $_POST['models_list']  ));
						array(':parent'=>(int)$sgroup ));
						}
						
			/////////////////////////вытаскиваем список настроек с параметрами
			$criteria=new CDbCriteria;
			$criteria->order = 't.title ';
			$criteria->condition = " t.sotrudnik_id = :user  AND  t.settings_type = :settings_type";
			$criteria->params=array(':user'=>Yii::app()->user->id,':settings_type'=>4 );//////1 джя оборота
			$presets = Sotrudniki_settings ::model()->findAll($criteria);		
			
			/////////////////////////////Инициализируем склады
			$criteria=new CDbCriteria;
			$criteria->order = 't.name ';
			$criteria->condition = " kontragent_id = :kontragent_id";
			$criteria->params=array(':kontragent_id'=>Yii::app()->GP->GP_self_contragent );////
			$stores = Stores ::model()->findAll($criteria);	
			for ($i=0; $i<count($stores); $i++){
					$stores_id[]=$stores[$i]->id;
					$stores_names[]=$stores[$i]->name;
			}//////////	for ($i=0; $i<count($stores); $++){
			$parametrs[stores_id] = $stores_id;
			$parametrs[stores_names] = $stores_names;
			/*
			$query1= "SELECT id, name FROM stores WHERE kontragent_id = ".Yii::app()->GP->GP_self_contragent." AND show_in_html=1  ORDER BY is_main DESC";
			$connection =   Yii::app()->db;		
			$command=$connection->createCommand($query1)	;
			$dataReader=$command->query();
			while(($row=$dataReader->read())!==false) {
			$stores_id[]=$row['id'];
			$stores_names[]=$row['name'];
			}
			*/
			//$this->parametrs[stores_id]=$stores_id;
			//$this->parametrs[stores_names]=$stores_names;
			//////////////////////////////////////////////////
			
			if (isset($_POST['build'])) {
					////////////////////Основнй запрос
			$report = new Report;
			//print_r($parametrs);
			$report->parametrs=$parametrs;
			$rows = $report	->stores();
			}
	
			$this->render('stores', array('maingroups'=>$maingroups, 'subgroups'=>$subgroups, 'rows'=>$rows, 'rows2'=>$rows2, 'parametrs'=>$parametrs, 'presets'=>$presets, 'stores_list'=>$stores_list));
	}///////////////////public function actionStores (){///////////////////Остатки
	
	public function actionSubgroups (){////////////Отдаем список групп для заданной ajax запросу
			
			//$sgroup = Yii::app()->getRequest()->getParam('parametrs[sgroup]', NULL);	
			$receive =  Yii::app()->getRequest()->getParam('parametrs', NULL) ;
			$sgroup = $receive[sgroup];
			
			//echo 'sgroup = '.$sgroup;
			 if((int)$sgroup>0) {
						$mydata=Categoriestradex::model()->findAll('parent=:parent', 
						//array(':parent'=>(int) $_POST['models_list']  ));
						array(':parent'=>(int)$sgroup ));
						//$data=array('0'=>'все');
						$data['0']='все';
						for ($i=0; $i<count($mydata); $i++) $data[$mydata[$i]->category_id]=$mydata[$i]->category_name;
						//$data=CHtml::listData( $data,'category_id','category_name');
						//$data=array_unshift ($data, array('все') );
						//print_r($data);
						//$qqq= array(
						//		"0"=>'все'
						//);
						//$data=array_merge($qqq, $data);
						print_r($data);
				}
				else $data=array('0'=>'Выберете супергруппу');
				
				foreach($data as $value=>$name)
				{
					echo CHtml::tag('option',
							   array('value'=>$value),CHtml::encode($name),true);
							      //array('value'=>$value),iconv("CP1251", "UTF-8", $name),true);
				}
				
				exit();
	}////////////////public function actionSubgroups (){////////////Отдаем спи
	
	
	
	
}
