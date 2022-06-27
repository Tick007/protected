<?php

class AdmindocsController extends CController //////////////Главный контроллер администрирования товаров
{
	const PAGE_SIZE=10;/////////////////Количество записей на страницу
	var $errordescr;

	/**
	 * @var string specifies the default action to be 'list'.
	 */
	public $defaultAction='list';

	/**
	 * @var CActiveRecord the currently loaded data model instance.
	 */
	private $_model;


	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'CheckAuthority +pricelist, list, updatedocument',
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
				'actions'=>array('doc', 'list', 'storelist', 'updatedocument', 'kdetails'),
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

	public function actionList() {///////////////Вывод списка пользователей
		//print_r($_POST);
		if (isset($_POST['create_price'])) {///////////создание прайслиста
		//echo "ewrewr";
				$doc_type = Yii::app()->getRequest()->getParam('new_doc_type', NULL);	
				$user = Yii::app()->user->id;
				$D = new Documents ;
				$D->date_dt = date("Y-m-d H:i:s");
				$D->user = $user;
				//$PL->currency = 2;
				$D->doc_status=0;
				$D->doc_type = $doc_type;
				try {
						$D->save();
						} catch (Exception $e) {
						 echo 'Caught exception: ',  $e->getMessage(), "\n";
						}///////////////////
		}/////////////////if (isset($_POST['create_price'])) {///////////создание пр	
		//print_r($_POST);
		$date_from_value  = Yii::app()->getRequest()->getParam('date_from_value', NULL);
		$date_to_value  = Yii::app()->getRequest()->getParam('date_to_value', NULL);
		$date_from_arr = split("/", $date_from_value );
		$date_from_sql = $date_from_arr[2].'-'.$date_from_arr[0].'-'. $date_from_arr[1];
		//echo '<br>'.$date_from_sql.'<br>';
		$date_to_arr = split("/", $date_to_value );
		$date_to_sql = $date_to_arr[2].'-'.$date_to_arr[0].'-'. $date_to_arr[1];
		//echo '<br>'.$date_to_sql.'<br>';
		
		
		$criteria=new CDbCriteria;
		if (trim($date_from_value) AND trim($date_to_value) ) {
			$criteria->order = 't.date_dt DESC';
			$criteria->condition = " t.date_dt >= :date_from AND t.date_dt  <= :date_to";
			$criteria->params=array(':date_from'=>$date_from_sql, ':date_to'=>$date_to_sql);
		}/////////////if (isset($date_from_value) AND isset($date_from_value)isset($date_from_value) ) {
			else $criteria->order = 't.id DESC';
			
		
		$pages=new CPagination(Documents::model()->count($criteria)); 
		$pages->pageSize=self::PAGE_SIZE;
		$pages->applyLimit($criteria);
		
		$models = Documents::model()->with('tablepart', 'doctype', 'kontragent', 'store', 'store_ca')->findAll($criteria);
		
		$doc_types = Document_types::model()->findAll();
		$doc_type_list[0]='...';
		for ($i=0; $i<count($doc_types); $i++)  $doc_type_list[$doc_types[$i]->id]=$doc_types[$i]->type;
		
		
		$this->render('list', array('models'=>$models, 'date_to_value'=>$date_to_value, 'date_from_value'=>$date_from_value, 'pages'=>$pages, 'doc_type_list'=>$doc_type_list) );
	}
	
	
	
	public function actionUpdatedocument(){//////////////////Операции с документом
			$doc_id = Yii::app()->getRequest()->getParam('id', NULL);	
			
			$DOCUMENT = Documents::model()->findbyPk($doc_id);
			if ($DOCUMENT !=NULL AND $DOCUMENT->doc_status!=2) {///////////Если документ найден, то сохраняем
					
					$DOCUMENT->kontragent_id = Yii::app()->getRequest()->getParam('kontragent_id', NULL);	
					$DOCUMENT->store_id = Yii::app()->getRequest()->getParam('store_id', NULL);	
					$DOCUMENT->store_id_ca = Yii::app()->getRequest()->getParam('store_id_ca', NULL);	
					
					try {
						$DOCUMENT->save();
						} catch (Exception $e) {
						 echo 'Caught exception: ',  $e->getMessage(), "\n";
						}///////////////////
			}///////////////if ($DOCUMENT !=NULL) {///////////Если документ найден, то сохраняем
			
			
					if (isset($_POST['add_product']) AND trim($_POST['add_product']) AND @$DOCUMENT->doc_status <2) {///////////Добавляем продукт
		
		$new_row = new Document_table_part;
		$new_row->product_id = $_POST['add_product'];
		$new_row->doc_id = $doc_id;
		try {
						$new_row->save(false);
						} catch (Exception $e) {
						 echo 'Caught exception: ',  $e->getMessage(), "\n";
						}//////////////////////
		
		}//////////////f (isset($_POST['add_product'])) {///////////Доба
		
		
		if (isset($_POST['products_list_price']['price'])) {///////////Перебираем товары
				$products_list_price = $_POST['products_list_price']['price'];
				foreach ($products_list_price as $model_id =>$price):
						
						$row = Document_table_part::model()->with('product')->findbyPk($model_id);
						if (!$_POST['del_product'][$model_id]) {
								$price_no_nds = round($price/(1+$row->product->nds_out),3);
								$row->price_no_nds =$price_no_nds;
								$row->num =  $_POST['products_list_price']['num'][$model_id];
								try {
									$row->save();
									} catch (Exception $e) {
									 echo 'Caught exception: ',  $e->getMessage(), "\n";
									}//////////////////////
							}/////////////if (!$_POST['del_product'][$model_id]) {
							else if ($_POST['del_product'][$model_id]) try {
									$row->delete(false);
									} catch (Exception $e) {
									 echo 'Caught exception: ',  $e->getMessage(), "\n";
									}/////////////////////
							
				endforeach;
		}/////////////if (isset($_POST['products_list_price'])) {///////////Перебираем товары
			
			
			
			if (isset($_POST['apply'])) {///////////////Проведение документа
					$DD = new Documents_details($doc_id);
					if ($DOCUMENT->doc_type == 1) {
					$errors = $DD->ProvedeniePrihod();
					if (trim($errors)== '') $DOCUMENT->doc_status = 2;
					else {
					$this->render('errors', array('errors'=>$errors, 'doc'=>$doc, 'doc_id'=>$doc_id));
					exit;
					}/////////else {
					}
					
					if ($DOCUMENT->doc_type == 2 OR $DOCUMENT->doc_type == 3)   {
					$errors = $DD->ProvedenieRashod_Trasfer();
					if (trim($errors)== '') $DOCUMENT->doc_status = 2;
					else {
					$this->render('errors', array('errors'=>$errors, 'doc'=>$doc, 'doc_id'=>$doc_id));
					exit;
					}
					//else echo $errors;
					}////////////////////if ($DOCUMENT->doc_type == 2 OR $DOCUMENT->doc_type == 3)   {
					
					try {
						$DOCUMENT->save();
						} catch (Exception $e) {
						 echo 'Caught exception: ',  $e->getMessage(), "\n";
						}///////////////////
			}/////////////////////if (isset($_POST['apply'])) {///////////////Проведение документа
			
			if (isset($_POST['abortapply'])) {///////////////Проведение документа
					$DD = new Documents_details($doc_id);
					$errors = $DD->CancelProvedenie();
					if (trim($errors)== '') $DOCUMENT->doc_status = 1;
					else {
					$this->render('errors', array('errors'=>$errors, 'doc'=>$doc, 'doc_id'=>$doc_id));
					exit;
					}
					try {
						$DOCUMENT->save();
						} catch (Exception $e) {
						 echo 'Caught exception: ',  $e->getMessage(), "\n";
						}///////////////////
			}/////////////////////if (isset($_POST['apply'])) {///////////////Проведение документа
			
			$this->redirect(Yii::app()->request->baseUrl."/admindocs/doc/$doc_id", true, 301);
	}///////////////////////////public function actionUpdatedocument(){//////////////////Операции
	
	public function actionDoc() {////////Вывод содержимого прайслиста
		//print_r($_POST);
		$doc_id = Yii::app()->getRequest()->getParam('id', NULL);	
		$doc = Documents::model()->findByPk($doc_id);
		////////////////////Вытфскиваем склады
		$criteria=new CDbCriteria;
		//$criteria->order = 't.date_dt DESC';
		$criteria->condition = " kontragent_id = :kontragent_id";
		$criteria->params=array(':kontragent_id'=>Yii::app()->GP->GP_self_contragent);
		$stores = Stores::model()->findAll($criteria);
		for($i=0; $i < count($stores); $i++) $stores_list[$stores[$i]->id]=$stores[$i]->name;
		//print_r($stores);
		
		$ca_id = $doc->kontragent_id;
		if ($ca_id>0) {
			$criteria=new CDbCriteria;
			$criteria->condition = "kontragent_id = :ca_id";
			$criteria->params=array(':ca_id'=>$ca_id);
			$data = Stores::model()->findAll($criteria);
			
			for ($i=0; $i<count($data); $i++)  $ca_stores_list[$data[$i]->id]=$data[$i]->name;//////////
			//print_r($ca_stores_list);
		}///////if ($ca_id>0) {
		
		$contr_agents = Contr_agents ::model()->findAll();
		for ($i=0; $i<count($contr_agents); $i++) $contr_agent_list[$contr_agents[$i]->id]=$contr_agents[$i]->name;
		
		$criteria=new CDbCriteria;
		$criteria->condition = " doc_id = :doc_id";
		$criteria->params=array(':doc_id'=>$doc_id);
		$models = Document_table_part::model()->with('product')->findAll($criteria);
		
				
		$this->render('document', array('doc'=>$doc, 'doc_id'=>$doc_id, 'models'=>$models,
		'contr_agent_list'=>$contr_agent_list, 'stores_list'=>$stores_list, 'ca_stores_list'=>$ca_stores_list) );	
	}///////////////////////////////////////////////////
	
	public function actionStorelist(){////////////вывод списка складов для ajax запроса
			$ca_id = Yii::app()->getRequest()->getParam('kontragent_id', NULL);	
			//$role_select = Yii::app()->getRequest()->getParam('role_select', 0);
			$criteria=new CDbCriteria;
			$criteria->condition = "kontragent_id = :ca_id";
			$criteria->params=array(':ca_id'=>$ca_id);
			$data = Stores::model()->findAll($criteria);
			for ($i=0; $i<count($data); $i++)  $task_list[$data[$i]->id]=$data[$i]->name;///////////Список брендов для списка выбора
			//print_r($brands_list);
			//$data=CHtml::listData($data,'id','name');
			//print_r($data);
			//$data=CHtml::listData($brands_list,'id','name');
			$data = $task_list;
			
			if (count($data)) {
									/*
									echo CHtml::tag('option',
								   //array('value'=>$value),CHtml::encode($name),true);
									  array('value'=>0),iconv("CP1251", "UTF-8", 'выбор'),true);
									  */
					foreach($data as $value=>$name)
					{
						echo CHtml::tag('option',
								   array('value'=>$value),CHtml::encode($name),true);
									 //array('value'=>$value),iconv("CP1251", "UTF-8", $name),true);
									// array('value'=>iconv("UTF-8", "CP1251",$value) ),iconv("UTF-8", "CP1251", $name),true);
					}
			}
			else {
					echo CHtml::tag('option',
								   array('value'=>$value),CHtml::encode($name),true);
									 // array('value'=>'0'),iconv("UTF-8", "CP1251", 'нет вариантов'),true);
			}
	
	}//////////////public function actionStorelist(){////////////вывод списка складов для a
	
	public function actionKdetails(){////////////вытаскиваем наименование контрагента по id Ajax
			$ca_id = Yii::app()->getRequest()->getParam('kontragent_id', NULL);	
			$K=Contr_agents::model()->model()->findByPk($ca_id);
			//echo CHtml::tag('kontragent', array(), iconv("CP1251", "UTF-8",$K->name) );
			echo iconv("CP1251", "UTF-8", $K->name);
	}////////////////////public function actionKdetails(){////////////вытаскиваем наименование контрагента по id
}////////////////////class
