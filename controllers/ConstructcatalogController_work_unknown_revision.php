<?php

class ConstructcatalogController extends Controller
{
	var $PAGE_SIZE=20;
	var $cat;
	var $nofollow; /////////////////Тэги для хакрытия страницы от индексации
	var $year_char_id = 1; ///// идентификатор характеристики
	var $product;
	public $krepl_prod_ids; ///////////Товары для фортуса - ид товаров  из значений характеристик
	public $krepl_categ_ids; ///////////Группы для фортуса -по  ид товаров  из значений характеристик
	
	
	/**
	 * @var string specifies the default action to be 'list'.
	 */
	//public $defaultAction='group';

	/**
	 * @var CActiveRecord the currently loaded data model instance.
	 */
	private $_model;

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'check_category_existance + group, info, group2',
			'checkProductExist + info',
			'CheckPathSuffix + group, info, group2 ',
			'CheckKppKrepl +index,  group, getgroups2, info, getyears, getkpptypes' ////////////////выборки если есть тип крепления или тип замка
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
				'actions'=>(isset(Yii::app()->params['authenticated_catalog'])==true)? array('notexist') :array('group', 'index', 'getgroups', 'getyears', 'info',  'getgroups2', 'getyears', 'getkpptypes', 'search', 'error', 'zamki', 'tools', 'adv'),
			),

			
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>(isset(Yii::app()->params['authenticated_catalog'])==true)? Yii::app()->params['authenticated_catalog']: array('notexist'),
				'users'=>array('@'),
			),
			
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('@'),
			),
			array('deny',  // deny all userss
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Shows a particular model.
	 	  
	 */
	 
	 
	public function filterCheckKppKrepl($filterChain){
		
		
		
			//$kpp = Yii::app()->getRequest()->getParam('kpp');
			$krepltype  = Yii::app()->getRequest()->getParam('krepltype'); //////kpp, val, hood
			$locktype  = Yii::app()->getRequest()->getParam('locktype'); /////auto moto garage
			
			$kpp_char_id = Yii::app()->params['filters']['kpp'];
			$krepl_char_id = Yii::app()->params['filters']['kreplen'];
			if(isset(Yii::app()->params['typs_krepleniya'][$krepltype]))$krepl_val = Yii::app()->params['typs_krepleniya'][$krepltype];////////////значение для отбора из таблицы значений по пришедшему типу крепления
			
			$locktype = Yii::app()->getRequest()->getParam('locktype');
			$typs_krepleniya=Yii::app()->params['typs_krepleniya'];
			//print_r($_GET); 
			//exit();
			
			if(isset($locktype)) {
			//	if($locktype!='auto') {
			//		throw new CHttpException(404,'В разработке');
			//		exit();
			//	}
			}
			
			
			
			if(isset($krepltype)) {/////////////для фортуса
			
						
			
				if(isset($krepl_val)) {///////////Продолжаем, только если определены значения по пришедшему типу крепления
						/* ////////////////////////////////////////////Убрал в соответствии с заявкой
						if($krepltype=='headlamp') {
							$filterChain->run();
							exit();
						}
						*/
						
						//echo '$krepl_val='.$krepl_val.'<br>';
						//echo '$krepl_char_id = '.$krepl_char_id.'<br>';
						
						$criteria=new CDbCriteria;
						//$criteria->condition="t.id_caract  = :id_caract ";
						//$criteria->params=array(':id_caract'=>$krepl_char_id);
						$criteria->condition="t.id_caract  = :id_caract  AND  t.value=:val";
						$criteria->params=array(':id_caract'=>$krepl_char_id, ':val'=>$krepl_val);
						$models=Characteristics_values::model()->findAll($criteria);
						
						if(isset($models)) $this->krepl_prod_ids=array_values(CHtml::listdata($models, 'id_product', 'id_product'));
						
						//print_r($this->krepl_prod_ids);
						//exit();
						if(isset($this->krepl_prod_ids) AND empty($this->krepl_prod_ids)==false ) {//////////Вытаскиваем группы, т.е. модели
							$criteria=new CDbCriteria;
							$criteria->select="t.category_belong AS attribute_value ";
							$criteria->distinct=true;
							$criteria->condition="t.id IN (".implode(',',$this->krepl_prod_ids).") AND t.product_visible=1";
							$models1=Products::model()->findAll($criteria);
							if(isset($models1) AND empty($models1)==false) $this->krepl_categ_ids=array_values(CHtml::listdata($models1, 'attribute_value', 'attribute_value'));
							else{
								throw new CHttpException(404,'В разработке');
							}
							
						}
						else {
							//echo 'werewr ';

							if($krepltype !='hood')	throw new CHttpException(404,'В разработке');
							else {
								$this->layout = "main_index";
								$this->render('temp/hood');
								exit();
							}
						}
						
						//echo '<br>';
						//print_r( $this->krepl_categ_ids);
						$filterChain->run();
						exit();
				}
				else {
					//throw new CHttpException(404,'В разработке');
					//exit();
				}
				
				
			}
			$filterChain->run();
		
		
	}/////////public function filterCheckKppKrepl($filterChain){
	
	public function  filterCheckProductExist($filterChain)	{////	
			
			//print_r($_GET);
			//exit();
			$product_id = Yii::app()->getRequest()->getParam('id', NULL);	
			if (is_numeric($product_id)==false) throw new CHttpException(404,'Карточка не существует 1');
			else {/////////else1
				//$PRODUCT = Products::model()->with('contr_agent', 'kladr', 'char_val')->findByPk($product_id);
				$criteria=new CDbCriteria;
				 $criteria->select=array( 't.*',  'picture_product.picture AS icon, ext ' );
				 $criteria->condition=" t.id  = :id ";
				 $criteria->join ="
	LEFT JOIN ( SELECT picture_product.product, picture_product.picture, pictures.ext AS ext FROM picture_product JOIN pictures ON pictures.id =picture_product.picture 	  WHERE is_main=1) picture_product ON picture_product.product = t.id  ";
				 $criteria->addCondition("t.product_visible = 1");
				 $criteria->params=array(':id'=>$product_id);
				$PRODUCT = Products::model()->find( $criteria);
				//$PRODUCT = Products::model()->findByPk($product_id);
								if ($PRODUCT==NULL) throw new CHttpException(404,'Карточка не существует 2');
								else {///////////if3
												$this->product = $PRODUCT;
												$filterChain->run();
										}////////////else {///////////if3
					}///////////////////else1
					//
		}///////////////public function  filterCheckProductExist($filterChain)	{///////Если пе
	
	
	public function actionIndex(){/////////Вывод гланой каталога
			
			$locktype  = Yii::app()->getRequest()->getParam('locktype'); /////auto moto garage
			$krepltype  = Yii::app()->getRequest()->getParam('krepltype'); //////kpp, val, hood
			

			
			/*  /////////////////////////////////////////////////////////////////////////////Убираю, в соответствии с заявкой
			if(isset($krepltype) AND $krepltype=='headlamp') {
				$this->layout="main_index";
				$this->render('headlamp');
				exit();
			}
			*/
			/*
			else if(isset($krepltype) AND $krepltype=='sparetire') {
				$this->layout="main_index";
				$this->render('sparetire');
			}
			*/
			
			
	
			$criteria=new CDbCriteria;
			$criteria->order = 't.category_name';
			$criteria->condition = " t.parent = ".Yii::app()->params['main_tree_root']."  AND t.alias IS NOT NULL AND t.show_category = 1";
			
			if(isset($this->krepl_categ_ids) AND empty($this->krepl_categ_ids)==false) {
				$criteria->addCondition("child_categories.category_id IN (".implode(',', $this->krepl_categ_ids ).")");
			}
			
			$models = Categories::model()->with('child_categories')->findAll($criteria);//
			
			if(isset($locktype) AND $locktype=='moto') {
				$this->layout="main_index";
				$this->render('moto', array('models'=>$models));
			}
			elseif(isset($locktype) AND $locktype=='outher') {
				$this->layout="main_index";
				if(isset($krepltype) AND $krepltype == 'garage' )$this->render('garage', array('models'=>$models));
				//if(isset($krepltype) AND $krepltype == 'garage' )$this->render('garage', array('models'=>$models));
				elseif(isset($krepltype) AND $krepltype == 'pricep' )$this->render('pricep', array('models'=>$models));
			}
			else $this->render('index', array('models'=>$models, 'krepltype'=>$krepltype));
	}////////////public function actionIndex(){
	
	
	
	public function filterCheck_category_existance($filterChain)	{//////////Если не был указан идентификатор партнера - то выдать 404 ошибкуr 
			//print_r($_POST);

			$alias = Yii::app()->getRequest()->getParam('alias');

			if (isset($alias) AND trim($alias)) {
				$this->cat = Categories::model()->with('page', 'parent_categories')->findbyAttributes(array('alias'=>trim($alias)));
			}//////////if ($show_group==NULL AND trim($alias)) {
			if 	(isset($this->cat->category_id)) {
				$filterChain->run();
			}
			else {
					$this->render('error');
					throw new CHttpException(404,'Группа не найдена');
					exit();	
			}
			
		}///////////	public function filterCheck_category_existance($filterChain)	
	

	
	public function actionZamki(){
		
		$this->layout="main_index";
		$this->render('multlock');
	} //////public function actionMultlock(){
	 
	public function actionGroup() {
		//echo 'ewrerwrew';
		
		$krepltype  = Yii::app()->getRequest()->getParam('krepltype');
		$locktype = Yii::app()->getRequest()->getParam('locktype');
		$alias = Yii::app()->getRequest()->getParam('alias');
		$path = Yii::app()->getRequest()->getParam('path');
		
		//echo $this->cat->category_name;
		
		//print_r($_GET);
		
		if(isset(Yii::app()->params['authenticated_brands'])) {
			if(array_key_exists($alias, Yii::app()->params['authenticated_brands'])){
				//print_r(Yii::app()->user);
				if (Yii::app()->user->isGuest==true) 
				{
					Yii::app()->user->returnUrl = Yii::app()->request->url;
					$this->redirect(array('/site/login'));
					Yii::app()->user->returnUrl = Yii::app()->request->url;
					exit();
				}
				else{
					$un = Yii::app()->user->getName();
					if($un != Yii::app()->params['authenticated_brands'][$alias]) {
						Yii::app()->user->logout();
						Yii::app()->user->returnUrl = Yii::app()->createUrl('constructcatalog/group', array('alias'=>$alias));
						$this->redirect(array('/site/login'));
						Yii::app()->user->returnUrl = Yii::app()->createUrl('constructcatalog/group', array('alias'=>$alias));
						exit();
					}
				}
			}
		}
	
		
				$this->Add_To_Cart();
		//exit();	
			//////////////Вытаскиваем список групп для 1го  дерева
					$criteria=new CDbCriteria;
					$criteria->order = 't.sort_category';
					//$criteria->order = 't.category_name';
					$criteria->condition = " t.parent =  :root AND t.show_category = 1  ";
					if(isset($this->krepl_categ_ids) AND empty($this->krepl_categ_ids)==false) {
						$criteria->addCondition("t.category_id IN (".implode(',', $this->krepl_categ_ids ).")");
					}
					$criteria->params=array(':root'=>$this->cat->category_id);
					$models = Categories::model()->with('child_categories')->findAll($criteria);//
					
					////////////////////////Дописка для реиркарниронного b-systems: что бы работал соответствующий шаблон, если он существует 
					if(isset($alias)){
						$template = $_SERVER['DOCUMENT_ROOT'].'/themes/'.Yii::app()->theme->name.'/views/layouts/'.	$alias.'.php';
					//	echo $template;
						if(file_exists($template) && is_file($template)) {
						//	echo '<br>exist';
							$this->layout= $alias;
		
						}
					}
							
			if (isset($models)==true AND count($models)>0) {
					$this->render('group', array('CAT'=>$this->cat, 'models'=>@$models));
					exit();
			}
			else {
				
				//////////////Смотрим связянные с группой ид товаров
				$criteria=new CDbCriteria;
				$criteria->condition="t.group = :group";
				$criteria->params=array(':group'=>$this->cat->category_id);
				$categories_products = Categories_products::model()->findAll($criteria);
				if (isset($categories_products)) for ($k=0; $k<count($categories_products); $k++) $cp[]=$categories_products[$k]->product;
				
				
				
				
				/////////////////////Выборка прямо включенных в категорию
				$criteria=new CDbCriteria;
				$criteria->select=array( 't.*',  'picture_product.picture AS icon' ,  'attribute_value AS attribute_value', 't.sort' );
				$criteria->join ="
		LEFT JOIN ( SELECT id, product, picture FROM picture_product WHERE is_main=1) picture_product ON picture_product.product = t.id
		LEFT JOIN (
				SELECT id_product, GROUP_CONCAT(characteristics_values.id_caract, '::',characteristics_values.value ORDER BY characteristics_values.id_caract  SEPARATOR '##' ) AS attribute_value
				FROM `characteristics_values` JOIN characteristics ON characteristics.caract_id = characteristics_values.id_caract
						GROUP BY id_product ORDER BY characteristics.sort DESC)product_attribute ON product_attribute.id_product = t.id   ";
				$criteria->condition = " t.category_belong =  :cat  ";
				if (isset($cp))$criteria->addCondition("(t.product_visible = 1 AND t.id IN (".implode(',', $cp).") )", 'OR');
				$criteria->addCondition("t.product_visible = 1");
				if(isset($krepltype) AND isset(Yii::app()->params['typs_krepleniya']) AND  isset(Yii::app()->params['typs_krepleniya'][$krepltype])) {
					$val = Yii::app()->params['typs_krepleniya'][$krepltype];
					$criteria->addCondition("char_val.value = '$val'");
				}
				$criteria->order = "t.id DESC, t.product_name"; 
				$criteria->params=array(':cat'=>$this->cat->category_id);
				$models = Products::model()->with('files', 'char_val')->findAll($criteria);
				
				
				
				////////////////////////Дописка для реиркарниронного b-systems: что бы работал соответствующий шаблон, если он существует 
					if(isset($path)){
						$template = $_SERVER['DOCUMENT_ROOT'].'/themes/'.Yii::app()->theme->name.'/views/layouts/'.	$path.'.php';
						//echo $template;
						if(file_exists($template) && is_file($template)) {
							$this->layout= $path;
							
							////////////Смотрим список параллельных директорий
							if(isset($this->cat) && isset($this->cat->parent_categories)) {
								$paralelecategs =  $this->getBrothers();
							}
							
						}
					}
				
				
				$params =  array('CAT'=>$this->cat, 'models'=>@$models);
				if(isset($paralelecategs) && $paralelecategs!=NULL) $params['paralelecategs'] = $paralelecategs;
				if(isset($alias) && $alias!=NULL) $params['alias'] = $alias;
				if(isset($path) && $path!=NULL) $params['path'] = $path;
				$this->render('products', $params );
				
				////////////////Выборка включенных в категорию через  дерево 1
				
				
				exit();
			}
	}////////public function actionGroup() {
		

	private function getBrothers(){
		$paralelecategs = NULL;
		////////////Смотрим список параллельных директорий
							if(isset($this->cat) && isset($this->cat->parent_categories)) {
								$criteria=new CDbCriteria;
								$criteria->order = 't.sort_category';
								//$criteria->order = 't.category_name';
								$criteria->condition = " t.parent =  :root AND t.show_category = 1  ";
								$criteria->params=array(':root'=>$this->cat->parent);
								$paralelecategs = Categories::model()->findAll($criteria);
								 
							}
		return 		$paralelecategs;		
	}

	public function actionInfo(){//////Карточка товара

		$alias = Yii::app()->getRequest()->getParam('alias');
		$path = Yii::app()->getRequest()->getParam('path');
		
		
		if(isset(Yii::app()->params['authenticated_brands'])) {
			if(array_key_exists($alias, Yii::app()->params['authenticated_brands'])){
				if (Yii::app()->user->isGuest==true) 
				{
					$this->redirect(array('/site/login'));
					exit();
				}
			}
		}
		

		if(isset($path)){
						$template = $_SERVER['DOCUMENT_ROOT'].'/themes/'.Yii::app()->theme->name.'/views/layouts/'.	$path.'.php';
						//echo $template;
						if(file_exists($template) && is_file($template)) {
							$this->layout= $path;
							if(isset($this->cat) && isset($this->cat->parent_categories)) {
								$paralelecategs =  $this->getBrothers();
							}
						}
					}


			$this->Add_To_Cart();
			
			if (isset($this->product)) {
					$connection = Yii::app()->db;
					$query = "SELECT id_product, GROUP_CONCAT(CONCAT_WS( ';#;', value,id_caract ) ORDER BY characteristics.sort  SEPARATOR '#;#') AS  attribute_value2 FROM characteristics_values  JOIN characteristics ON characteristics.caract_id = characteristics_values.id_caract  WHERE id_product =:id_product  GROUP BY id_product ORDER BY characteristics.sort";////////////
					//echo $query;
					$command=$connection->createCommand($query)	;
					$command->params=array(':id_product'=>$this->product->id);
					$dataReader=$command->query();
					$records=$dataReader->readAll();////
					if (isset($records)) {
								for($i=0; $i<count($records); $i++) $products_attributes[$records[$i]['id_product']]=$records[$i]['attribute_value2'];
					}
								
					/////////////////////////////////////////Вытаскиваем все характеристики, доступные для этой группы
					$criteria=new CDbCriteria;
					//$criteria->condition = "t.caract_category 	 = :caract_category  OR t.is_main = 1  ";
					//$criteria->order = "characteristics_categories.sort, t.char_type , t.caract_name ";
					$criteria->params =  array('caract_category'=>$this->cat->category_id);
					$characteristics1 = Characteristics::model()->findAll($criteria);
					for ($i=0; $i<count($characteristics1); $i++) {
							$characteristics_array[$characteristics1[$i]->caract_id]=array('char_type'=>4,  'caract_name'=>$characteristics1[$i]->caract_name, 'is_main'=>$characteristics1[$i]->is_main);
					}//////////for ($i=0; $i<count($characteristics); $i++) {			
					
					
					///////////////Вытаскиваем дополнительные фотографии
					$criteria=new CDbCriteria;
					$criteria->condition="t.product = :product AND t.is_main = 0";
					$criteria->params=array(':product'=>$this->product->id);
					$pictures=Picture_product::model()->with('img')->findAll($criteria);
					
					
					/////////////////////////////////Вытаскиваем совместимые товары
					$criteria=new CDbCriteria;
					$criteria->select=array( 't.*',  'picture_product.picture AS icon', ' picture_product.ext AS ext' );
					$criteria->join ="
				LEFT JOIN ( SELECT picture_product.id, product, picture, pictures.ext  FROM picture_product  JOIN pictures ON pictures.id =picture_product.picture WHERE is_main=1 ) picture_product ON picture_product.product = t.compatible  ";
					$criteria->order = ' t.product ';
					$criteria->condition = "compprod.product_visible=1 AND t.product = ".$this->product->id;
					$compabile= Products_compability::model()->with('compprod')->findAll($criteria);//
					
					//////////////////////////////////Вытаскиваем товары детей (дети):
					
					$criteria=new CDbCriteria;
					$criteria->select=array( 't.*',  'picture_product.picture AS icon', ' picture_product.ext AS ext' );
					$criteria->join ="
				LEFT JOIN ( SELECT picture_product.id, product, picture, pictures.ext  FROM picture_product  JOIN pictures ON pictures.id =picture_product.picture WHERE is_main=1 ) picture_product ON picture_product.product = t.id  ";
					$criteria->order = ' t.product_name ';
					$criteria->condition = "t.product_visible=1 AND t.product_parent_id = ".$this->product->id;
					$childs= Products::model()->with('belong_category')->findAll($criteria);//
					//echo count($childs);
					
			}////if (isset($this->product)) {
			
			$params =  array('product'=>$this->product, 'CAT'=>$this->cat, 'products_attributes'=>@$products_attributes, 'characteristics_array'=>@$characteristics_array, 'pictures'=>@$pictures, 'compabile'=>@$compabile, 'childs'=>@$childs);
			if(isset($paralelecategs) && $paralelecategs!=NULL) $params['paralelecategs'] = $paralelecategs;
			if(isset($alias) && $alias!=NULL) $params['alias'] = $alias;
			
			$this->render('info',$params);
	}///////public function actionInfi(){//////Карто

	public function actionGetgroups(){////////Аджакс функция для выборке детей
			//print_r($_POST);
			$brand = Yii::app()->getRequest()->getParam('brand');
			if (isset($brand)) {
			  	   $criteria=new CDbCriteria;
					$criteria->order = 't.sort_category';
					$criteria->condition = " t.parent =  :root AND t.show_category = 1  ";
					$criteria->params=array(':root'=>$brand);
					$models = Categories::model()->with('child_categories')->findAll($criteria);//

					/*////////////Это классика
					$models_list=CHtml::tag('option', array('value'=>0), iconv( "UTF-8", "CP1251", "Модель"));
					for($k=0; $k<count($models); $k++)
							{
								$models_list.=CHtml::tag('option', array('value'=>$models[$k]->category_id),CHtml::encode(iconv( "UTF-8", "CP1251", $models[$k]->category_name)),true);
							}
					echo CJSON::encode(array(
							  'models'=>$models_list
							));
					*/		
					//////////Для cusel:
					$cusel='';//////'<span val="4">Слон</span><span val="5">Жираф африканский</span>';
				if (isset($models) AND count($models) >0 ) $cusel="<span val=\"0\">Выберите модель</span>";
				else $cusel="<span val=\"0\">Нет вариантов</span>";
				for($k=0; $k<count($models); $k++) {
					$cusel.="<span val=\"";
					$path_array=  array( 'path'=>FHtml::urlpath($models[$k]->path), 'alias'=>$models[$k]->alias);
					$url= urldecode(Yii::app()->createAbsoluteUrl('constructcatalog/group', $path_array));
					$cusel.=$url;
					$cusel.="\">".$models[$k]->category_name."</span>";
				}/////////for($k=0; $k<count($models); $k++) {
				$models_list = iconv( "UTF-8", "CP1251", $cusel);
				echo CJSON::encode(array(
							  'models'=>$models_list
							));
					
			}//////////
	}//////public function actionGetgroups(){////////Ад
	
	public function actionGetgroups2(){////////Аджакс функция для выборке детей
			//print_r($_POST);
			
			//print_r($this->krepl_categ_ids);
			
			$brand = Yii::app()->getRequest()->getParam('brand');
			if (isset($brand)) {
			  	   $criteria=new CDbCriteria;
					$criteria->order = 't.category_name';
					$criteria->condition = " t.parent =  :root AND t.show_category = 1  ";
					if(empty($this->krepl_categ_ids)==false)  $criteria->addCondition('t.category_id IN ('.implode(',', $this->krepl_categ_ids).')');
					$criteria->params=array(':root'=>$brand);
					$models = Categories::model()->with('child_categories', 'parent_categories')->findAll($criteria);//

					////////////Это классика
					//$models_list=CHtml::tag('option', array('value'=>0), iconv( "UTF-8", "CP1251", "Модель"));
					$models_list=CHtml::tag('option', array('value'=>0), "выбор...");
					for($k=0; $k<count($models); $k++)
							{
								$path_array=  array( 'path'=>$models[$k]->parent_categories->alias, 'alias'=>$models[$k]->alias);
							//$url= urldecode(Yii::app()->createAbsoluteUrl('constructcatalog/group', $path_array));
								$models_list.=CHtml::tag('option', array('value'=>$models[$k]->category_id),CHtml::encode(iconv( "UTF-8", "CP1251", $models[$k]->category_name)),true);
							}
					/*echo CJSON::encode(array(
							  'models'=>$models_list
							));
						*/
						echo 	$models_list;
			}//////////
	}//////public function actionGetgroups(){////////Ад

	public function actionGetyears(){////////Аджакс функция для выборке детей
			//print_r($_POST);
			//$brand = Yii::app()->getRequest()->getParam('brand');
			$model = Yii::app()->getRequest()->getParam('model');
			$year_char_id = Yii::app()->params['filters']['year'];
			if (isset($model)) {
					
					$criteria=new CDbCriteria;
					$criteria->distinct=true;
					$criteria->select=array('value AS value');
					$criteria->condition = " products.category_belong  =  :model  AND products.product_visible = 1 AND t.id_caract = :caract_id ";
					$criteria->params=array(':model'=>$model, ':caract_id'=>$year_char_id);
					$criteria->group="value";
					if(empty($this->krepl_prod_ids)==false)  $criteria->addCondition('products.id IN ('.implode(',', $this->krepl_prod_ids).')');
					$models=Characteristics_values::model()->with('products')->findAll($criteria);
					//print_r($models);
					////////////Это классика
					//$models_list=CHtml::tag('option', array('value'=>0), iconv( "UTF-8", "CP1251", "Модель"));
					$models_list=CHtml::tag('option', array('value'=>''), "выбор года");
					for($k=0; $k<count($models); $k++)
							{
									
								
								$models_list.=CHtml::tag('option', array('value'=>$models[$k]->value),CHtml::encode(iconv( "UTF-8", "CP1251", $models[$k]->value)),true);
							}
					/*echo CJSON::encode(array(
							  'models'=>$models_list
							));
						*/
						echo 	$models_list;
			}//////////
	}//////public function actionGetgroups(){////////Ад	
	
	public function actionGetkpptypes(){////
			$model = Yii::app()->getRequest()->getParam('model');
			$year = Yii::app()->getRequest()->getParam('year');
			$year_char_id = Yii::app()->params['filters']['year'];
			$kpp_char_id = Yii::app()->params['filters']['kppfrontend'];
			$krepltype  = Yii::app()->getRequest()->getParam('krepltype');
			$locktype = Yii::app()->getRequest()->getParam('locktype');
			
			
			if(isset($krepltype) AND ($krepltype=='val' OR $krepltype=='hood')) { //////////Т.е. запрос по замкам капота или вала
				echo trim($krepltype);
				exit();
			}
			
			///////////////Снаачала нужно с параметром год отобрать товары, а потом с их ID сделать 2ю выборку
			//print_r($_POST);
			
					$criteria=new CDbCriteria;
					$criteria->condition = " products.category_belong  =  :model  AND products.product_visible = 1 AND t.id_caract = :caract_id AND t.value = :value";
					$criteria->params=array(':model'=>$model, ':caract_id'=>$year_char_id, ':value'=>$year);
					$models=Characteristics_values::model()->with('products')->findAll($criteria);
			
					//echo count($models);
					//exit();		
					
					if(count($models)>0) {////////////Второй запрос, опции с этими ид товаров
						$products_list=CHtml::listdata($models, 'id_product', 'id_product');
						//print_r($products_list);
						unset($models);
						if(isset($products_list)) {
								
								$criteria=new CDbCriteria;
								$criteria->select=array('t.value');
								$criteria->distinct=true;
								$criteria->condition = " t.id_product IN (".implode(',', array_values($products_list)).") AND products.product_visible = 1 AND t.id_caract = :caract_id ";
								$criteria->params=array(':caract_id'=>$kpp_char_id);
								$criteria->group="t.value";
								$models=Characteristics_values::model()->with('products')->findAll($criteria);
								//print_r($criteria);
								
								
								$model_groupe = Categories::model()->with('parent_categories')->findByPk($model);
								
								if(isset($models)) {
									$models_list=CHtml::tag('option', array('value'=>''), "выбор КПП");
									for($k=0; $k<count($models); $k++)
									{
										
										//$path_array=  array( 'path'=>$model_groupe->parent_categories->alias, 'alias'=>$model_groupe->alias, 'id'=>$models[$k]->id_product);
										//$url= urldecode(Yii::app()->createAbsoluteUrl('constructcatalog/info', $path_array));
										//$models_list.=CHtml::tag('option', array('value'=>$url),CHtml::encode($models[$k]->value),true);
										$models_list.=CHtml::tag('option', array('value'=>$models[$k]->value),CHtml::encode($models[$k]->value),true);
									}
								}
								else $models_list=CHtml::tag('option', array('value'=>''), "не найдено");
							/*echo CJSON::encode(array(
									  'models'=>$models_list
									));
								*/
								echo 	$models_list;
										
						}//////if(isset($products_list)) {
					}///////if(count($models)>0) {/////////
						
			
		
	}/////public function actionGetkpptypes(){
	
	
	public function actionSearch(){///////////Промежуточная страница для редиректа на товар
			$model = Yii::app()->getRequest()->getParam('model');
			$year = Yii::app()->getRequest()->getParam('year');
			$brand = Yii::app()->getRequest()->getParam('brand');
			$kpp = Yii::app()->getRequest()->getParam('kpp');
			$year_char_id = Yii::app()->params['filters']['year'];
			$kpp_char_id = Yii::app()->params['filters']['kppfrontend'];
			$searchgoods = Yii::app()->getRequest()->getParam('search', NULL);	
			$krepltype  = Yii::app()->getRequest()->getParam('krepltype');
			$locktype = Yii::app()->getRequest()->getParam('locktype');
			//print_r($_GET); 
			//exit();
			$typs_krepleniya=Yii::app()->params['typs_krepleniya'];
			
			if(isset($model))$model_groupe = Categories::model()->with('parent_categories')->findByPk($model);
			
			
			
			//Если есть  $searchgoods то
			if (strlen($searchgoods)>2) {
				$searchgoods = htmlspecialchars($searchgoods);
				//echo $searchgoods;
				//$role_select = Yii::app()->getRequest()->getParam('role_select', 0);
				$criteria=new CDbCriteria;
				if(isset($krepltype) AND $krepltype=='kpp') {
					$criteria->condition = " (t.product_name LIKE :searchgoods OR t.product_name LIKE :searchgoods_mtl ) AND t.product_visible =1  ";
					$criteria->params=array(':searchgoods'=>$searchgoods.'%', ':searchgoods_mtl'=>'MTL '.$searchgoods.'%');
				}
				elseif(isset($krepltype) AND $krepltype=='val') {
					$criteria->condition = " (t.product_name LIKE :searchgoods OR t.product_name LIKE :searchgoods_csl ) AND t.product_visible =1  ";
					$criteria->params=array(':searchgoods'=>$searchgoods.'%', ':searchgoods_csl'=>'CSL '.$searchgoods.'%');
				}
				$criteria->select=array( 't.*',  'picture_product.picture AS icon' ,  'attribute_value AS attribute_value', 't.sort' );
				$criteria->join ="
		LEFT JOIN ( SELECT id, product, picture FROM picture_product WHERE is_main=1) picture_product ON picture_product.product = t.id
		LEFT JOIN (
				SELECT id_product, GROUP_CONCAT(characteristics_values.id_caract, '::',characteristics_values.value ORDER BY characteristics_values.id_caract  SEPARATOR '##' ) AS attribute_value
				FROM `characteristics_values` JOIN characteristics ON characteristics.caract_id = characteristics_values.id_caract
						GROUP BY id_product ORDER BY characteristics.sort)product_attribute ON product_attribute.id_product = t.id   ";
				$criteria->order = "t.sort, t.product_name"; 		
						
				$good = Products::model()->with('belong_category')->findAll($criteria);
				if(isset($good)) {
					
					
					
					if(count($good)==1) {
						

						
						
						
						$path_array=  array( 'path'=>$good[0]->belong_category->parent_categories->alias, 'alias'=>$good[0]->belong_category->alias, 'id'=>$good[0]->id);
						if(isset($krepltype))  $path_array['krepltype'] =  $krepltype;
						if(isset($locktype))  $path_array['locktype'] = $locktype;
						$url= urldecode(Yii::app()->createUrl('constructcatalog/info', $path_array));
					
						$this->redirect($url);
						
						
						exit();
					}
					elseif(count($good)>1) {//////////замок найден в нескольких машинах
					

						$this->render('products_search', array('models'=>$good, 'searchgoods'=>$searchgoods));
						exit();
					}
				}
			}
				
			
			//print_r($_GET);
			if(trim($year)=='' AND isset($model_groupe)) {/////////Это когда выбрана модель (и бренд)
					////////
					//echo 	'qweqweqwe';	
					$path_array=  array( 'path'=>$model_groupe->parent_categories->alias, 'alias'=>$model_groupe->alias);
					if(isset($krepltype))  $path_array['krepltype'] =  $krepltype;
					if(isset($locktype))  $path_array['locktype'] = $locktype;
					
					
					$url= urldecode(Yii::app()->createUrl('constructcatalog/group', $path_array));
					
					//echo $url;
					$this->redirect($url);
					exit();
					
			}/////////searchif(trim($year)=='') {/////////Htlbh
			if(isset($brand) AND $brand>0 AND  (isset($model) AND $model==0) ) {//////////выбрана только марка
				$brand_groupe = Categories::model()->with('parent_categories')->findByPk($brand);
				if(isset($brand_groupe)) {
					$path_array=  array(  'alias'=>$brand_groupe->alias);
					if(isset($krepltype))  $path_array['krepltype'] =  $krepltype;
					if(isset($locktype))  $path_array['locktype'] = $locktype;
					
					
					$url= urldecode(Yii::app()->createUrl('constructcatalog/group', $path_array));
					//echo $url;
					$this->redirect($url);
					exit();
				}///////if(isset($brand_groupe)) {
				
			}////////	if(isset($brand) AND $brand>0 AND  (isset($model) A
			
			
			///////////////Снаачала нужно с параметром год отобрать товары, а потом с их ID сделать 2ю выборку
			//exit();
			
			
					$criteria=new CDbCriteria;
					$criteria->condition = " products.category_belong  =  :model  AND products.product_visible = 1 AND t.id_caract = :caract_id AND t.value = :value";
					$params = array(':model'=>$model, ':caract_id'=>$year_char_id, ':value'=>$year);
					$criteria->params=$params;
					$models=Characteristics_values::model()->with('products')->findAll($criteria);
			
					//echo count($models);
					//exit();		
					if( count($models) ==1 AND isset($krepltype) AND $krepltype !='kpp') {///////Если один товар, то сразу на него переходим
							$path_array=  array( 'path'=>$model_groupe->parent_categories->alias, 'alias'=>$model_groupe->alias, 'id'=>$models[0]->id_product);
							if(isset($krepltype)) $path_array['krepltype'] =  $krepltype;
							if(isset($locktype))  $path_array['locktype'] = $locktype;
							$url= urldecode(Yii::app()->createUrl('constructcatalog/info', $path_array));
							$this->redirect($url);
							exit();
							
					}
					
					if(count($models)>0) {////////////Второй запрос, опции с этими ид товаров
						
						$products_list=CHtml::listdata($models, 'id_product', 'id_product');
						
						if(isset($kpp)==false OR trim($kpp)=='') { ////////////Значит выбрали год но не выбрали тип КПП. Т.е. нужно вернуть тип список товаров.
							//$criteria=new CDbCriteria;
							//$criteria->condition="t.id IN(".implode(',', array_keys($products_list) ).")";
							//$good = Products::model()->with('belong_category')->findAll($criteria);
							//$this->render('products_search', array('models'=>$good, 'searchgoods'=>$searchgoods));
							$path_array=  array( 'path'=>$model_groupe->parent_categories->alias, 'alias'=>$model_groupe->alias);
							if(isset($krepltype)) $path_array['krepltype'] =  $krepltype;
							if(isset($locktype))  $path_array['locktype'] = $locktype;
							$path_array['year'] =  serialize($year);
							$url= urldecode(Yii::app()->createUrl('constructcatalog/group', $path_array));
							//echo $url;
							$this->redirect($url);
							exit();
						}
						
						
						unset($models);
						if(isset($products_list)) {
							//print_r($products_list);
								
								$criteria=new CDbCriteria;
								if(isset($kpp) AND trim($kpp)) { //////////для замков кпп
								
									$criteria->condition = " t.id_product IN (".implode(',', array_values($products_list)).") AND products.product_visible = 1 AND t.id_caract = :caract_id ";
									$criteria->params=array(':caract_id'=>$kpp_char_id);
								}/////////
								else { /////////для вал и капота
									$criteria->condition = " t.id_product IN (".implode(',', array_values($products_list)).") AND products.product_visible = 1  ";
								}
								
								$models=Characteristics_values::model()->with('products')->findAll($criteria);
								
								
			
								//echo count($models);
								
								
								if(isset($models)) {
									
									
									for($k=0; $k<count($models); $k++)
									{
										//echo $models[$k]->id_product . ' -> '.$models[$k]->value.'<br>';
																				
										$path_array=  array( 'path'=>$model_groupe->parent_categories->alias, 'alias'=>$model_groupe->alias, 'id'=>$models[$k]->id_product);
										if(isset($krepltype)) $path_array['krepltype'] =  $krepltype;
										if(isset($locktype))  $path_array['locktype'] = $locktype;
										$url= urldecode(Yii::app()->createUrl('constructcatalog/info', $path_array));
										//$models_list.=CHtml::tag('option', array('value'=>$url),CHtml::encode($models[$k]->value),true);
										//$models_list.=CHtml::tag('option', array('value'=>$models[$k]->value),CHtml::encode($models[$k]->value),true);
										if(isset($kpp) AND $models[$k]->value==$kpp) {
											
											$this->redirect($url);
											exit();
										}
										elseif(isset($krepltype) AND isset($typs_krepleniya[$krepltype])) {
											if($models[$k]->value==$typs_krepleniya[$krepltype]) {
												$this->redirect($url);
												exit();
											}
											
										}
									}
									//если товар найти не удалось, идем в группу
									$path_array=  array( 'path'=>$model_groupe->parent_categories->alias, 'alias'=>$model_groupe->alias);
									if(isset($krepltype)) $path_array['krepltype'] =  $krepltype;
									if(isset($locktype))  $path_array['locktype'] = $locktype;
									$url= urldecode(Yii::app()->createUrl('constructcatalog/group', $path_array));
									$this->redirect($url);
									exit();
								}
							
										
						}//////if(isset($products_list)) {
					}///////if(count($models)>0) {/////////
	}
	
	public function actionError() {
		
		
		

		$this->layout="main_index";
		
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
	
	
	public function actionAdv(){ ///////////Подраздел реклама
	
			$this->layout="main_index";
		$id = Yii::app()->getRequest()->getParam('id');
		
		if(isset(Yii::app()->params['adv_root'])) {
			
			$group = Categories::model()->findByPk(Yii::app()->params['adv_root']);
			
			if(isset($id) AND is_numeric($id)==true) {
					$criteria=new CDbCriteria;
					$criteria->condition = " t.id = $id";
					$criteria->select=array( 't.*',  'picture_product.picture AS icon' , 'picture_product.ext AS ext');
						//$criteria->together = true;
					$criteria->join =" LEFT JOIN ( SELECT product, picture, pictures.ext as ext FROM picture_product  JOIN pictures ON pictures.id= picture_product.picture  WHERE is_main=1 ) picture_product ON picture_product.product = t.id  ";
					$product = Products::model()->with('belong_category')->find($criteria);	
					
					///////////Выбираем фотки товара
					$connection = Yii::app()->db;
					$query = "SELECT picture_product.id, pictures.id, pictures.ext, pictures.type FROM 
  pictures JOIN picture_product ON picture_product.picture = pictures.id WHERE picture_product.product =".$id." ORDER BY picture_product.id ";
  					$result = $connection->createCommand($query);
					$additional_pictures = $result->query()->readAll();
					if(isset($product)) $this->render('adv/adv',array ('product'=>$product, 'additional_pictures'=>@$additional_pictures) );
			}
			else {
				$criteria=new CDbCriteria;
				$criteria->select=array( 't.*',  'CONCAT_WS(".", picture_product.picture, picture_product.ext) AS icon', 'picture_product.picture AS icon_id' , 'picture_product.comments AS attribute_value');
				$criteria->condition="t.category_belong = :tools_root ";
				$criteria->order=" t.sort, t.product_name ";
				$criteria->join ="
				LEFT JOIN ( SELECT picture_product.id, product, picture, ext, comments FROM picture_product JOIN pictures ON pictures.id=picture_product.picture WHERE is_main=1) picture_product ON picture_product.product = t.id  ";
				$criteria->addCondition("t.product_visible = 1");
				$criteria->params =  array(':tools_root'=>Yii::app()->params['adv_root']);
				// $criteria->params=array(':id_caract'=>$this->year_char_id);
				 $products = Products::model()->with()->findAll($criteria);
				 
				 $this->render('adv/list',array ('products'=>@$products, 'group'=>@$group) );
			}
			
			//echo count($products);
		}////////if(isset(Yii::app()->params['tools_root'])) {
	
	
		
	}///////// function actionAdv(){ ///////////Подраздел реклама
	
	public function actionTools(){ ///////////Подраздел инструменты
		$this->layout="main_index";
		$id = Yii::app()->getRequest()->getParam('id');
		
		if(isset(Yii::app()->params['tools_root'])) {
			
			$group = Categories::model()->findByPk(Yii::app()->params['tools_root']);
			
			if(isset($id) AND is_numeric($id)==true) {
					$criteria=new CDbCriteria;
					$criteria->condition = " t.id = $id";
					$criteria->select=array( 't.*',  'picture_product.picture AS icon' , 'picture_product.ext AS ext');
						//$criteria->together = true;
					$criteria->join =" LEFT JOIN ( SELECT product, picture, pictures.ext as ext FROM picture_product  JOIN pictures ON pictures.id= picture_product.picture  WHERE is_main=1 ) picture_product ON picture_product.product = t.id  ";
					$product = Products::model()->with('belong_category')->find($criteria);	
					if(isset($product)) $this->render('tools/tool',array ('product'=>$product) );
			}
			else {
				$criteria=new CDbCriteria;
				$criteria->select=array( 't.*',  'CONCAT_WS(".", picture_product.picture, picture_product.ext) AS icon', 'picture_product.picture AS icon_id' , 'picture_product.comments AS attribute_value');
				$criteria->condition="t.category_belong = :tools_root ";
				$criteria->order=" t.sort, t.product_name ";
				$criteria->join ="
				LEFT JOIN ( SELECT picture_product.id, product, picture, ext, comments FROM picture_product JOIN pictures ON pictures.id=picture_product.picture WHERE is_main=1) picture_product ON picture_product.product = t.id  ";
				$criteria->addCondition("t.product_visible = 1");
				$criteria->params =  array(':tools_root'=>Yii::app()->params['tools_root']);
				// $criteria->params=array(':id_caract'=>$this->year_char_id);
				 $products = Products::model()->with()->findAll($criteria);
				 
				 $this->render('tools/list',array ('products'=>@$products, 'group'=>@$group) );
			}
			
			//echo count($products);
		}////////if(isset(Yii::app()->params['tools_root'])) {
	
		
	}///////////
	
}//////////////class