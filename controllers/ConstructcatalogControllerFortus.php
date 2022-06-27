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
			'CheckKppKrepl +index,  group, getgroups2, info, getyears, getkpptypes', ////////////////выборки если есть тип крепления или тип замка
			'CheckBrouser +tools',
			'SetTheme +tools',
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
				'actions'=>(isset(Yii::app()->params['authenticated_catalog'])==true)? array('notexist') :array('group', 'index', 'getgroups', 'getyears', 'info',  'getgroups2', 'getyears', 'getkpptypes', 'search', 'error', 'zamki', 'tools', 'adv', 'gettext', 'get_typs_kpp'),
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
		
		
			///////////После переезда на новый сайт эта страница больше не работает
			$this->redirect(array('/site/page', 'view'=>'manuals'));
			exit();			
			
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
		
		///////////После переезда на новый сайт эта страница больше не работает
		$this->redirect(array('/site/page', 'view'=>'manuals'));
		exit();
		
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
					//print_r($this->cat->attributes);
					//exit();
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
				//echo $krepltype.'<br>';
				if(isset($krepltype) AND isset(Yii::app()->params['typs_krepleniya']) AND  isset(Yii::app()->params['typs_krepleniya'][$krepltype])) {
					$val = Yii::app()->params['typs_krepleniya'][$krepltype];
					//echo '$val = '.$val.'<br>';
					$criteria->addCondition("char_val.value = '$val'");
				}
				$criteria->order = "t.sort, t.id, t.product_name"; 
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

	public function get_product_attributes($product_id){
							$connection = Yii::app()->db;
							$query = "SELECT id_product, GROUP_CONCAT(CONCAT_WS( ';#;', value,id_caract ) ORDER BY characteristics.sort  SEPARATOR '#;#') AS  attribute_value2 FROM characteristics_values  JOIN characteristics ON characteristics.caract_id = characteristics_values.id_caract  WHERE id_product =:id_product  GROUP BY id_product ORDER BY characteristics.sort";////////////
							//echo $query;
							$command=$connection->createCommand($query)	;
							$command->params=array(':id_product'=>$product_id);
							$dataReader=$command->query();
							$records=$dataReader->readAll();////
							if (isset($records)) {
										for($i=0; $i<count($records); $i++) $products_attributes[$records[$i]['id_product']]=$records[$i]['attribute_value2'];
							}
							return $products_attributes;
	}
	
	
	
	///////////Переносим в FHTML
	/**
	 * @param string Строка содеожащая слепленные ид характеристик и их значения для конуретного товара
	 * @return Массив вида id char => Значение 
	 */
	/*
	public function make_attribute_array(string $product_attributes_merged){
		print_r('received in Controller: '.$product_attributes_merged);
		$attributes=explode('##', $product_attributes_merged);
		print_r($attributes);
		if (isset($attributes) && count($attributes)>0) {
			print_r('wqerwerwe<br>');
			for($k=0; $k<count($attributes);$k++) {
				//print_r($attributes[$k]);
				$char_id_val = explode ('::', $attributes[$k]);
				//if(isset($char_id_val[0]) AND $char_id_val[0]==1) echo $char_id_val[1];
				if(isset($char_id_val[0])) $attr_arr[ $char_id_val[0]]= $char_id_val[1];
			}
			//print_r($attr_arr);
			return $attr_arr;
		}
		return null;
	}
	*/

	public function actionInfo(){//////Карточка товара

		///////////После переезда на новый сайт эта страница больше не работает
		$this->redirect(array('/site/page', 'view'=>'manuals'));
		exit();
		
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
					
					
					$products_attributes = $this->get_product_attributes($this->product->id);
					
					/////////////////////////////////////////Вытаскиваем все характеристики, доступные для этой группы
					$characteristics_array = $this->get_characteristics($this->cat->category_id);
					/*
					$criteria=new CDbCriteria;
					//$criteria->condition = "t.caract_category 	 = :caract_category  OR t.is_main = 1  ";
					//$criteria->order = "characteristics_categories.sort, t.char_type , t.caract_name ";
					$criteria->params =  array('caract_category'=>$this->cat->category_id);
					$characteristics1 = Characteristics::model()->findAll($criteria);
					for ($i=0; $i<count($characteristics1); $i++) {
							$characteristics_array[$characteristics1[$i]->caract_id]=array('char_type'=>4,  'caract_name'=>$characteristics1[$i]->caract_name, 'is_main'=>$characteristics1[$i]->is_main);
					}//////////for ($i=0; $i<count($characteristics); $i++) {	
					*/
					
					
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
			
			$params =  array('product'=>$this->product, 'CAT'=>$this->cat, 
					'products_attributes'=>@$products_attributes, 'characteristics_array'=>@$characteristics_array, 
					'pictures'=>@$pictures, 'compabile'=>@$compabile, 'childs'=>@$childs);
			if(isset($paralelecategs) && $paralelecategs!=NULL) $params['paralelecategs'] = $paralelecategs;
			if(isset($alias) && $alias!=NULL) $params['alias'] = $alias;
			

			
			$this->render('info',$params);
	}///////public function actionInfi(){//////Карто

	public function get_characteristics($cat_id=null){
		$criteria=new CDbCriteria;
		if($cat_id!=null) $criteria->params =  array('caract_category'=>$cat_id);
		$characteristics1 = Characteristics::model()->findAll($criteria);
		for ($i=0; $i<count($characteristics1); $i++) {
			$characteristics_array[$characteristics1[$i]->caract_id]=array('char_type'=>4,  'caract_name'=>$characteristics1[$i]->caract_name, 'is_main'=>$characteristics1[$i]->is_main);
		}//////////for ($i=0; $i<count($characteristics); $i++) {
		return $characteristics_array;
	}
	
	
	/** Метод для приведения параметров товара к виду ид_параметра=>значение
	 * @param unknown $products_attributes
	 * @param unknown $group_characteristics
	 */
	public function combine_cahrs_values($products_attributes, $group_characteristics){
		//print_r($group_characteristics);
		$attrs = array();
		foreach ($products_attributes as $rec_id=>$attr_arr){
			$temp = explode('#;#', $attr_arr);
			if(is_array($temp)){
				for($i=0, $c=count($temp);$i<$c;$i++){
					$temp_internal = explode(';#;', $temp[$i]);
					if(is_array($temp_internal)){
						$attrs[$temp_internal[1]]=array('val'=>$temp_internal[0], 'type'=>$group_characteristics[$temp_internal[1]]['caract_name']);
					}
				}
			}
		}
		return $attrs;
	}
	
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
				//echo trim($krepltype);
				echo CHtml::tag('option', array('value'=>''), "не важно");
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
			
			//////////////////// ВВодим новый флаг для возврата результат поиска по ajax а не редирект
			$return_ajax = Yii::app()->getRequest()->getParam('return_ajax', NULL);
			
			//print_r($krepltype); 
			//exit();
			
			
			if(trim($krepltype)=='0' && ($searchgoods==NULL ||  trim($searchgoods)=='')) {
				//echo 'Необходимо выбрать тип устройства';
				//exit();
			}
			
			if(trim($krepltype)!='0' && $brand=='0' && ($searchgoods==NULL ||  trim($searchgoods)=='')) {
				echo 'Необходимо выбрать марку';
				exit();
			}
			
			
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
				else {
					$criteria->condition = " (t.product_name LIKE :searchgoods ) AND t.product_visible =1  ";
					$criteria->params=array(':searchgoods'=>'%'.$searchgoods.'%');
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
					
						if(isset($return_ajax) && $return_ajax==1) $this->renderPartial('products_search_ajax', array('models'=>$good, 'searchgoods'=>$searchgoods)); 
						else $this->redirect($url);
						exit();
					}
					elseif(count($good)>1) {//////////замок найден в нескольких машинах
					

						if(isset($return_ajax) && $return_ajax==1) $this->renderPartial('products_search_ajax', array('models'=>$good, 'searchgoods'=>$searchgoods));
						else $this->render('products_search', array('models'=>$good, 'searchgoods'=>$searchgoods));
						exit();
					}
				}
			}
				

				
			
			//print_r($_GET);
			//if(trim($year)=='' AND isset($model_groupe)) {/////////Это когда выбрана модель (и бренд)
			if(isset($model_groupe)) { /////////////TODO после переключения
					////////
			//echo '1';		
				
				$typ_krepleniya_rus = null;
				if(isset($krepltype) && $krepltype!='0'){
					$typ_krepleniya_rus = Yii::app()->params['typs_krepleniya'][$krepltype];
				}
				
				
				if(isset($return_ajax) && $return_ajax==1) {/////////Для нового сайта
					//echo '2';
					//exit();
					
				
					$criteria=new CDbCriteria;
					$criteria->select=array( 't.*',  'picture_product.picture AS icon' ,  'attribute_value AS attribute_value', 't.sort' );
					$criteria->condition ="t.category_belong = ".$model." && t.product_visible = 1";
					
					//Это хорошо работает но что делать если нужен список замков других типов без подзапроса ?
					/*if($typ_krepleniya_rus!=null && $krepltype!='0'){
						$criteria->condition.=" && id_product IN (SELECT id_product FROM characteristics_values WHERE value = '".$typ_krepleniya_rus."') ";
					}
					*/
					/////////////26.11.2018, добавил, что бы одиночный товар с годом не уплывал в др функции. И условие для входа в этот блок
					
					if(trim($year)!='' && $year!='0'){
						$criteria->condition.=" && id_product IN (SELECT id_product FROM characteristics_values WHERE value = '".$year."' AND id_caract='".Yii::app()->params['filters']['year']."') ";
					}
					
					
					$cjoin = "
			LEFT JOIN ( SELECT id, product, picture FROM picture_product WHERE is_main=1) picture_product ON picture_product.product = t.id
			LEFT JOIN (
					SELECT id_product, GROUP_CONCAT(characteristics_values.id_caract, '::',characteristics_values.value ORDER BY characteristics_values.id_caract  SEPARATOR '##' ) AS attribute_value
					FROM `characteristics_values` ";
					
					if(isset($krepltype)){ 
						//Тут нужно вытащить все id product c id_caract соответствующему $krepltype
						//print_r(Yii::app()->params['typs_krepleniya']);
						
						//echo $typ_krepleniya_rus;
						//$cjoin.=" WHERE id_product IN (SELECT id_product FROM characteristics_values WHERE value = '.$typ_krepleniya_rus.')";
					}
					
					$criteria->join = $cjoin." JOIN characteristics ON characteristics.caract_id = characteristics_values.id_caract
							GROUP BY id_product ORDER BY characteristics.sort) product_attribute ON product_attribute.id_product = t.id   ";
					
					//$criteria->order = "t.sort, t.product_name";
					$criteria->order = "t.id DESC" ; /// но тут не особо влияет, посколько во втюхе по ид пересортируетс
					

					$good = Products::model()->with('belong_category')->findAll($criteria);
					$params= array('models'=>$good, 'searchgoods'=>$searchgoods);
					if($typ_krepleniya_rus!=null) {
						$params['typ_krepleniya_rus']=$typ_krepleniya_rus;
					}
					
					
					
					$this->renderPartial('products_search_ajax', $params);

				}
				else{
				
					//echo 	'qweqweqwe';	
					$path_array=  array( 'path'=>$model_groupe->parent_categories->alias, 'alias'=>$model_groupe->alias);
					if(isset($krepltype))  $path_array['krepltype'] =  $krepltype;
					if(isset($locktype))  $path_array['locktype'] = $locktype;
					
					
					$url= urldecode(Yii::app()->createUrl('constructcatalog/group', $path_array));
					
					//echo $url;
					//exit();
					
					$this->redirect($url);
				}
					exit();
					
			}/////////searchif(trim($year)=='') {/////////Htlbh
			if(isset($brand) AND $brand>0 AND  (isset($model) AND $model==0)  ) {//////////выбрана только марка
				
				
				$brand_groupe = Categories::model()->with('parent_categories')->findByPk($brand);
				if(isset($brand_groupe)) {
					$path_array=  array(  'alias'=>$brand_groupe->alias);
					if(isset($krepltype))  $path_array['krepltype'] =  $krepltype;
					if(isset($locktype))  $path_array['locktype'] = $locktype;
					
					
					//echo $krepltype;
					//echo $locktype;
					$filters_caract_id = Yii::app()->params['filters']['kreplen'];
					$device  = Yii::app()->getRequest()->getParam('krepltype', NULL);
					if(isset($device) && $device!=NULL)$car_val = Yii::app()->params['typs_krepleniya'][$device];
					//echo '$filters_caract_id = '.$filters_caract_id.'<br>';
					//echo '$car_val = '.$car_val.'<br>';
					//exit();
					
					
					if(isset($return_ajax) && $return_ajax==1) {/////////Для нового сайта
						$criteria=new CDbCriteria;
						$criteria->select=array(
								't.id',
								't.product_name',
								'picture_product.picture AS icon',
								'product_attribute.attribute_value AS attribute_value',
								't.sort'
						);
						$criteria->condition ="belong_category.parent = ".$brand." && t.product_visible = 1";
						$criteria->join ="
			LEFT JOIN (	SELECT id_product, 
						GROUP_CONCAT(characteristics_values.id_caract, '::',characteristics_values.value
					ORDER BY characteristics_values.id_caract  SEPARATOR '##' ) AS attribute_value
					FROM `characteristics_values`  
					LEFT JOIN characteristics ON characteristics.caract_id = characteristics_values.id_caract 
					GROUP BY id_product ORDER BY characteristics.sort
					) product_attribute 
			ON product_attribute.id_product = t.id  
			LEFT JOIN ( SELECT id, product, picture FROM picture_product WHERE is_main=1) picture_product 
			ON picture_product.product = t.id ";
						$criteria->order = "t.sort, t.product_name";
						
						if($filters_caract_id !=NULL AND $car_val!=NULL){
							$criteria->condition.=" AND t.id IN (SELECT id_product FROM characteristics_values  WHERE
								id_caract = $filters_caract_id AND 	value = '$car_val') ";
							//print_r($criteria);
						}
						
						$good = Products::model()->with('belong_category')->findAll($criteria);
						$this->renderPartial('products_search_ajax', array('models'=>$good, 'searchgoods'=>$searchgoods, 'characts'=>$this->get_characteristics()));
						
					}
					else{
						$url= urldecode(Yii::app()->createUrl('constructcatalog/group', $path_array));
						//echo $url;
						$this->redirect($url);
					}
					
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
					
					
					/////////26.11.2018 - Убрал, что бы единственный ныйденный товар не редиректил на инфо
					/*
					if( count($models) ==1 AND isset($krepltype) AND $krepltype !='kpp') {///////Если один товар, то сразу на него переходим
							$path_array=  array( 'path'=>$model_groupe->parent_categories->alias, 'alias'=>$model_groupe->alias, 'id'=>$models[0]->id_product);
							if(isset($krepltype)) $path_array['krepltype'] =  $krepltype;
							if(isset($locktype))  $path_array['locktype'] = $locktype;
							$url= urldecode(Yii::app()->createUrl('constructcatalog/info', $path_array));
							$this->redirect($url);
							exit();
							
					}
					*/
					
					//echo '(count($models) = '.count($models);	
					//exit();
					
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
							
							
							/////для новой версии сайта для отдачи по ajax при запросе без типа коробки передач 
							//(все типы замков кроме на КПП)
							if(Yii::app()->request->isAjaxRequest)	{
								$this->ajaxRendernoKPP($models, $krepltype);
							}
							else $this->redirect($url);
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
								//exit();
								
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
											
											
											//////////////////Для новой версии сайта, когда нужно в форма вывести ответ
											//когда заполнены все поля на форме
											if(Yii::app()->request->isAjaxRequest)	{
												$this->ajaxRender($models[$k]->id_product, $krepltype);
											}
											else $this->redirect($url);
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
									
									//echo 'werwer';
									$this->redirect($url);
									exit();
								}
							
										
						}//////if(isset($products_list)) {
					}///////if(count($models)>0) {/////////
	}
	
	
	// Рендер результатов поиска для нового сайта для всплывающего окна (бренд, модель, год) для замков кроме КПП
	private function ajaxRendernoKPP($models, $krepltype){ ///////список характеристик, из которых нужно взять ид товаров,
	//  и сделать ещё один запрос к характеристика в соответствии с krepltype
		
		//print_r(count($models));
		
		//exit();
		
		foreach ($models as $model){
			$products_list[]=$model->id_product;
		}
		
		//print_r($products_list);
		//exit();
		
		$criteria=new CDbCriteria;
		$criteria->condition = " t.id_product IN (".implode(',', array_values($products_list)).") ";
		if(isset(Yii::app()->params['typs_krepleniya'][$krepltype])) {
			$criteria->condition.="   AND  t.value = :val";
			$criteria->params=array(':val'=>Yii::app()->params['typs_krepleniya'][$krepltype]);
		}
		$models=Characteristics_values::model()->with('products')->findAll($criteria);
		if($models!=NULL)foreach ($models as $model){
			if($model) $this->ajaxRender($model->id_product, $krepltype);
		}
	}
	
	// Рендер результатов поиска для нового сайта для всплывающего окна (бренд, модель, год) для замков КПП
	private function ajaxRender($product_id, $krepltype){
		
		$product = Products::model()->with('files')->findByPk($product_id);
		
		$products_attributes = $this->get_product_attributes($product_id);
		
		$criteria=new CDbCriteria;
		$criteria->params =  array('caract_category'=>$product->category_belong);
		$characteristics1 = Characteristics::model()->findAll($criteria);
		for ($i=0, $k=count($characteristics1); $i<$k; $i++) {
				$characteristics_array[$characteristics1[$i]->caract_id]=$characteristics1[$i]->caract_name;
		}//////////for ($i=0; $i<count($characteristics); $i++) {		

		$attr1= explode('#;#', $products_attributes[$product_id]);
		$products_attributes = NULL;
		$products_attributes['brand'] = $product->belong_category->parent_categories->category_name;
		$products_attributes['model'] = $product->belong_category->category_name;

		for($i=0, $k=count($attr1); $i<$k; $i++){
			$attr2 = explode(';#;',$attr1[$i]);
			$products_attributes[$attr2[1]]=array($characteristics_array[$attr2[1]]=>$attr2[0]);
		}
		
		$file = NULL;
			if (isset($product->files)) {
			for ($i=0; $i<count($product->files); $i++)  {
				if ($product->files[$i]->filetype1==1) {
					if (is_file($product->files[$i]->filepath) AND file_exists($product->files[$i]->filepath))	
					
					$file = '/'.$product->files[$i]->filepath;
				//	echo "<div style=\"margin-top:10px; position:absolute\">".CHtml::link('Инструкция', '/'.$product->files[$i]->filepath, array('target'=>'blank', 'style'=>'color: #993233;')).'</div>';
				}
			}
			}
		
		$products_attributes['mpu'] =$product->product_name;
		
		//////////////Проверка инструкций в других разделах замков кроме $krepltype
		$files_list = $this->checkOutherInstructions($product, $krepltype);
		
		$this->renderPartial('ajaxsearch', array('products_attributes'=>$products_attributes, 'file'=>$file, 'files_list'=>$files_list));
		
		
		
		
	}
	
	private function checkOutherInstructions($product, $krepltype){
		
		$criteria=new CDbCriteria;
		//$criteria->select = array('t.id_product');
		$criteria->distinct = true;
		$criteria->condition = " products.category_belong = :cat AND t.id_product <> :prod";
		//$criteria->group = "t.value_id"; AND  t.value <>  :val 
		$cparams= array(':cat'=>$product->category_belong,  
						':prod'=>$product->id
						);
		
		if(isset(Yii::app()->params['typs_krepleniya'][$krepltype])){
			$criteria->condition.=" AND  t.value <>  :val  ";
			$cparams[':val'] = Yii::app()->params['typs_krepleniya'][$krepltype];
		}
		
		$criteria->params=$cparams;
		$models=Characteristics_values::model()->with('products')->findAll($criteria);
		
		$prods_list= NULL; ////////////////Список найденных продуктов других типов отличных от krepltype
		$files_list = NULL;
		$flipped_typs_krepleniya = array_flip(Yii::app()->params['typs_krepleniya']);
		//echo '<pre>';
		//print_r($flipped_typs_krepleniya);
		//echo '</pre>';
		//echo '$flipped_typs_krepleniya[Рулевого вала] = '.$flipped_typs_krepleniya["Рулевого вала"];
		//exit();
		foreach ($models as $atr){
			//echo '<pre>';
			//print_r($atr->getAttributes());
			//var_dump($atr->value);
			//echo '</pre>';
			if(isset($atr->id_caract) && $atr->id_caract==11){ ///Смотрим тип креплен служебн
				//echo @$flipped_typs_krepleniya[$atr->value];
				 $val = (string)trim(($atr->value));
				if( isset($flipped_typs_krepleniya[$val])){
					//echo 'ee<br>';
					$prods_list[$atr->products->id]=array(
					'product'=>$atr->products,
					
					'name_char_id'=>$flipped_typs_krepleniya[$val],
					'zamok_type'=>$atr->value,
					);
				}
			} 
			//print_r($prods_list);
			
			if(isset($prods_list[$atr->products->id]) && isset(Yii::app()->params['filters'][$prods_list[$atr->products->id]['name_char_id']]) && Yii::app()->params['filters'][$prods_list[$atr->products->id]['name_char_id']]==$atr->id_caract){
				
				$prods_list[$atr->products->id]['kpp_type']=$val;
			}
			
		}
	
		if($prods_list!=NULL) { ///значит чего то нашли, нужно сформировать ссылки на инструкции
			//echo 'qweqwe';
			//print_r(array_keys($prods_list));
			$criteria=new CDbCriteria;
			$criteria->condition = "t.id IN (".implode(',', array_keys($prods_list)).")";
			$models = Products::model()->with('files')->findAll($criteria);
			
			//echo count($models); 
		
			foreach($models as $product){
				//echo 'p = '.$product->id.'<br>f='.count($product->files).'<br/>';
				for ($i=0; $i<count($product->files); $i++)  {
					//print_r($product->files[$i]->attributes);
					if ($product->files[$i]->filetype1==1) {
						if (is_file($product->files[$i]->filepath) AND file_exists($product->files[$i]->filepath))	
						$files_list[$prods_list[$product->id]['zamok_type']] = $product->files[$i]->filepath;
					}
				}
				//print_r($files_list);
			}
		}
		return $files_list ;
	}
	
	public function actionError() {
		
		
		

		//$this->layout="main_index";
		
		if($error=Yii::app()->errorHandler->error)
	    {
	    	if(Yii::app()->request->isAjaxRequest)
	    		echo $error['message'];
	    	else {
				
	        	
				$this->meta_mo_index = true;
				
	    	    //$httpVersion = Yii::app()->request->getHttpVersion();
	    	   
	    	    header("HTTP/1.1 404 Not Found");
	    	    //Yii::app()->response->statusCode = 404;
	    	    //throw new CHttpException(404,'В разработке');
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
		//$this->layout="main_index"; Временно убираем для нового фортуса 2
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
	
	public function actionGettext(){
		$page = Yii::app()->getRequest()->getParam('page', null);
		if($page != null) {
			//echo $page;
			$doc = $_SERVER['DOCUMENT_ROOT'].Yii::app()->theme->baseUrl.'/views/constructcatalog/index_texts/'.$page.'.php';
			//echo $doc;
			if(is_file($doc) && file_exists($doc)){
				$file_content = $this->renderPartial('index_texts/'.$page);
				echo $file_content;
			}
			else echo ' ';
		}
	}
	
	public function ActionGet_typs_kpp(){
		$device  = Yii::app()->getRequest()->getParam('krepltype', NULL);
		$brand  = Yii::app()->getRequest()->getParam('brand', NULL);
		if($device!=NULL) {
			if(isset(Yii::app()->params['typs_krepleniya'][$device])){
				//echo Yii::app()->params['typs_kpp'][$device];
				///Нужно выбрать все товары у которых тип замка автомобильный (id_caract = 12)
				$filters_caract_id = Yii::app()->params['filters']['kreplen'];
				$criteria=new CDbCriteria;
				$criteria->condition = " t.id_caract = :id_caract AND t.value=:value";
				$criteria->distinct = true;
				$criteria->params=array(':id_caract'=>$filters_caract_id, ':value'=>Yii::app()->params['typs_krepleniya'][$device]);
				$models = Characteristics_values::model()->findAll($criteria);
				
				if($models!=NULL){ ///////////////Что то нашли, теперь нужно вернуть марки машин, т.е. паренты групп товаров найденных ID
					 for($i=0, $k=count($models); $i<$k;$i++){
					 	$products[]=$models[$i]->id_product;
					 }
					 if($products!=NULL && empty($products)==false){
					 	$criteria=new CDbCriteria;
						$criteria->condition = " t.id IN (".implode(',', $products).") AND t.product_visible = 1";
						$prods = Products::model()->with('belong_category')->findAll($criteria);
						if($prods!=NULL) {////////
							//echo '<br>1:';
							//print_r(count($prods));
							//echo '<br>';
							foreach ($prods as $product){ /////Косталь для не включения категории ид=2 (Марки)
								if($product->belong_category->parent!=2) $cats[]=$product->belong_category->parent;
							}
							if($cats!=NULL && empty($cats)==false){ ///////////////Ну и теперь вытаскиваем  марки машин
								//echo '<br>2:';
								//print_r($cats);
								$criteria=new CDbCriteria;
								$criteria->distinct = true;
								$criteria->condition = " t.category_id IN (".implode(',', $cats).")";
								$categories = Categories::model()->findAll($criteria);
								if($categories!=NULL){
									$brands_list = CHtml::tag('option', array('value'=>0), 'выбор...');
									//$brands_list = '';
									foreach ($categories as $category){
										if($brand!=NULL AND $brand==$category->category_id)$brands_list.=CHtml::tag('option', array('value'=>$category->category_id, 'selected' => 'selected'), $category->category_name);
										else $brands_list.=CHtml::tag('option', array('value'=>$category->category_id), $category->category_name);
									}
								}
							}
						}
					 }
				}
				else {
					$brands_list=CHtml::tag('option', array('value'=>''), "не найдено");
				}
				
				echo $brands_list;
			}
			elseif($device ==0 ){
				echo  CHtml::tag('option', array('value'=>''), "Выберете тип замка");
			}
		
		}
	}
	
}//////////////class