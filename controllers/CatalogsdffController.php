<?php

class CatalogController extends Controller
{
	var $PAGE_SIZE=20;
	var $cat;
	var $nofollow; /////////////////Тэги для хакрытия страницы от индексации
	var $year_char_id = 1; ///// идентификатор характеристики
	var $product;
	
	
	/**
	 * @var string specifies the default action to be 'list'.
	 */
	public $defaultAction='list';

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
			'CheckPathSuffix + group, info, group2 '
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
				'actions'=>array('group', 'group2', 'getgroups', 'getyears', 'info'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update'),
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
	
	
	
	public function filterCheck_category_existance($filterChain)	{//////////Если не был указан идентификатор партнера - то выдать 404 ошибкуr 
			

			$alias = Yii::app()->getRequest()->getParam('alias');

			if (isset($alias) AND trim($alias)) {
				$this->cat = Categories::model()->with('page')->findbyAttributes(array('alias'=>trim($alias)));
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
	

	 
	 
	public function actionGroup() {
		
	//	print_r($_GET);
		
				$this->Add_To_Cart();
		//exit();	
			//////////////Вытаскиваем список групп для 1го  дерева
					$criteria=new CDbCriteria;
					$criteria->order = 't.sort_category';
					$criteria->condition = " t.parent =  :root AND t.show_category = 1  ";
					$criteria->params=array(':root'=>Yii::app()->params['main_tree_root']);
					$first_tree = Categories::model()->with('child_categories')->findAll($criteria);//
					if (isset($first_tree)) {
							$brand_list=CHtml::listData($first_tree,'category_id','category_name');
							/*
							for ($i=0; $i<count($first_tree); $i++) {
								$main_tree_list[$first_tree[$i]->category_id]=$first_tree[$i]->category_name;
								if (isset($first_tree[$i]->child_categories)) {
										for ($k=0; $k<count($first_tree[$i]->child_categories); $k++) {
												$main_tree_list[$first_tree[$i]->child_categories[$k]->category_id]='---'.$first_tree[$i]->child_categories[$k]->category_name;
										}///////////for ($k=0; $k<count($fi
										
								}///////	if (isset($first_tree[$i
							}/////////
							*/
							//$brand_list = array('0'=>"...выберете брэнд")+$brand_list;
							$brand_list = array('0'=>"Марка")+$brand_list;
					
					}////////	if (isset($first_tree)) {
						
					$brand = Yii::app()->getRequest()->getParam('brand');
			if (isset($brand)) {
					   $criteria=new CDbCriteria;
						$criteria->order = 't.sort_category';
						$criteria->condition = " t.parent =  :root AND t.show_category = 1  ";
						$criteria->params=array(':root'=>$brand);
						$models = Categories::model()->with('child_categories')->findAll($criteria);//
						$models_list = CHtml::listData($models,'category_id','category_name');
					//	if (isset($models_list)) $models_list = array('0'=>'...выберети модель')+$models_list;
					if (isset($models_list)) $models_list = array('0'=>'Модель')+$models_list;
				}////if (isset($brand)) {
					
			$model = Yii::app()->getRequest()->getParam('model');
			$group = Yii::app()->getRequest()->getParam('group');
			

			
			if (isset($brand)  AND isset($model) AND isset($group)) {
					$criteria=new CDbCriteria;
					//$criteria->order = 't.sort_category';
					$criteria->condition = " t.type = 2 AND t.group = :group ";
					$criteria->params=array(':group'=>$group);
					$models = Categories_products::model()->findAll($criteria);//
					//$products_1_array = CHtml::listData($models,'group','product');
					if (isset($models)) for ($i=0; $i<count($models); $i++) $products_1[]=$models[$i]->product;
					//print_r($products_1);
					
					$criteria=new CDbCriteria;
					//$criteria->order = 't.sort_category';
					$criteria->condition = " t.type = 1 AND t.group = :group ";
					$criteria->params=array(':group'=>$model);
					$models = Categories_products::model()->findAll($criteria);//
					if (isset($models)) for ($i=0; $i<count($models); $i++) $products_2[]=$models[$i]->product;
					
					//if (isset($products_2))print_r($products_2);
					
					if (isset($products_2) AND isset($products_1)) {
					$rezult=array_intersect ($products_1, $products_2);
					}
					
					if (isset($rezult) AND empty($rezult)==false) {
						 $criteria=new CDbCriteria;
						 $criteria->condition="t.id_caract = :id_caract AND t.id_product IN (".implode(',', $rezult).")";
						  $criteria->distinct = true;
						 $criteria->params=array(':id_caract'=>$this->year_char_id);
						 $models = Characteristics_values::model()->findAll($criteria);
						 if (isset($models))  {
								
								$years_list1 = array();
								for($k=0; $k<count($models); $k++)
									{
										if (array_key_exists($models[$k]->value,  $years_list1)==false ) $years_list1[$models[$k]->value] = $models[$k]->value_id;
									}
									
									$years_list = array_flip($years_list1);
									//$years_list = array('0'=>'выбор года') + $years_list;
									$years_list = array('0'=>'Год') + $years_list;
																 
						} ///// if (isset($models))  {

							
					}///////////////

			$year= Yii::app()->getRequest()->getParam('year'); ///тут судуи ид из char_values
			if (isset($year)) {
					$CV = Characteristics_values::model()->findByPk($year);
					if (isset($CV)) $filtr_value = $CV->value;
			}///if (isset($year)) {

			if (isset($rezult)) {///////////Вытаскиваем сами товары
					 $criteria=new CDbCriteria;
						 $criteria->select=array( 't.*',  'picture_product.picture AS icon' );
						 $criteria->condition="t.id IN (".implode(',', $rezult).")";
						 $criteria->join ="
			LEFT JOIN ( SELECT id, product, picture FROM picture_product WHERE is_main=1) picture_product ON picture_product.product = t.id  ";
						 $criteria->addCondition("t.product_visible = 1");
						 $criteria->addCondition("char_val.value = :filtr_value");
						 $criteria->params=array(':filtr_value'=>$filtr_value);
						 $criteria->order = "t.product_sellout DESC, t.product_sellout_sort, t.product_price, t.product_name";
						// $criteria->params=array(':id_caract'=>$this->year_char_id);
						 $products = Products::model()->with('char_val')->findAll($criteria);
						 $pages=new CPagination(Products::model()->with('char_val')->count($criteria)); //////////////// 6 мс
						//$pages->params=array('sort'=>$sort);
						$pages->pageSize=$this->PAGE_SIZE;
						$pages->applyLimit($criteria);
						
						if (isset($products)) {
							for ($i=0; $i<count($products); $i++) $productslist[]=$products[$i]->id;
							if (isset($productslist)) {
							//print_r($productslist);
							$connection = Yii::app()->db;
							$query = "SELECT id_product, GROUP_CONCAT(CONCAT_WS( ';#;', value,id_caract )   ORDER BY characteristics.sort SEPARATOR '#;#') AS  attribute_value2 FROM characteristics_values   JOIN characteristics ON characteristics.caract_id = characteristics_values.id_caract  WHERE id_product IN (".implode(',', $productslist).")  GROUP BY id_product ORDER BY characteristics_values.value_id";////////////
							//echo $query;
							$command=$connection->createCommand($query)	;
							$dataReader=$command->query();
							$records=$dataReader->readAll();////
				
										if (isset($records)) {
													for($i=0; $i<count($records); $i++) $products_attributes[$records[$i]['id_product']]=$records[$i]['attribute_value2'];
										}
				
										//print_r($products_attributes);
							
							
							///////////////////Смотрим цены у подчиненных
							//echo 'ewrewr';
							$criteria=new CDbCriteria;
							$criteria->select="t.product_parent_id,  SUM(t.product_price) AS attribute_value";
							$criteria->group="t.product_parent_id";
							$criteria->condition="t.product_parent_id IN (".implode(',', $productslist).")";
							$criteria->addCondition("t.product_visible = 1");
							$prices = Products::model()->findAll($criteria);
							if (isset($prices)) $prices_by_childs = CHtml::listdata($prices, 'product_parent_id',  'attribute_value');
							
							}///if (isset($productslist)) {
						}/////if (isset($products)) {
						
						
					}////////if (isset($rezult)) {
					
			
			/////////////////////////////////////////Вытаскиваем все характеристики, доступные для этой группы
			$criteria=new CDbCriteria;
			$criteria->condition = "t.caract_category 	 = :caract_category  ";
			//$criteria->order = "characteristics_categories.sort, t.char_type , t.caract_name ";
			$criteria->params =  array('caract_category'=>$this->cat->category_id);
			$characteristics1 = Characteristics::model()->findAll($criteria);
			for ($i=0; $i<count($characteristics1); $i++) {
					$characteristics_array[$characteristics1[$i]->caract_id]=array('char_type'=>4,  'caract_name'=>$characteristics1[$i]->caract_name, 'is_main'=>$characteristics1[$i]->is_main);
			}//////////for ($i=0; $i<count($characteristics); $i++) {
					
			}//////if (isset($brand)  AND isset($model) AND isset($group)) {
					
					
							
			$this->render('group', array('CAT'=>$this->cat, 'brand_list'=>@$brand_list, 'models_list'=>@$models_list, 'years_list'=>@$years_list, 'products'=>@$products, 'products_attributes'=>@$products_attributes, 'characteristics_array'=>@$characteristics_array, 'prices_by_childs'=>@$prices_by_childs));
	}////////public function actionGroup() {
		
		
	public function actionGroup2() {
			
			$this->Add_To_Cart();
			
			$filter_group = Yii::app()->getRequest()->getParam('filter_group');//////////группа фильтра
			$filter = Yii::app()->getRequest()->getParam('filter');////сам фильтр
			
								/////////////////////////////////////////Вытаскиваем все характеристики, доступные для этой группы
			$criteria=new CDbCriteria;
			$criteria->condition = "t.caract_category 	 = :caract_category OR t.is_common = 1  ";
			//$criteria->order = "characteristics_categories.sort, t.char_type , t.caract_name ";
			$criteria->params =  array('caract_category'=>$this->cat->category_id);
			$characteristics1 = Characteristics::model()->findAll($criteria);
			for ($i=0; $i<count($characteristics1); $i++) {
					$characteristics_array[$characteristics1[$i]->caract_id]=array('char_type'=>4,  'caract_name'=>$characteristics1[$i]->caract_name, 'is_main'=>$characteristics1[$i]->is_main);
					if ($characteristics1[$i]->is_main==1) $filter_characteristics[]=$characteristics1[$i]->caract_id;
			}//////////for ($i=0; $i<count($characteristics); $i++) {
					
					//print_r($characteristics_array);
					//echo "<br>";
					if ((isset($filter_characteristics) AND empty($filter_characteristics)==false)  ) {
					//print_r($filter_characteristics);	
					//echo '<br>';
					/////////////смотрим уникальные значения
					$criteria=new CDbCriteria;
					$criteria->select=array( 't.id_caract, t.value' );
					$criteria->distinct=true;
					$criteria->condition="t.id_caract  IN (".implode(',', $filter_characteristics ).")";
					$criteria->order = "t.value";
					$filter_values=Characteristics_values::model()->findAll($criteria);	
					//echo count($filter_values).'<br>';
					if (isset($filter_values)) $filter_list=CHtml::listData($filter_values,'value','value');
					}
					//print_r($filter_list);
					
						
				    if(isset($filter)) {
							$filter_hex = explode('s', $filter);
							if (isset($filter_hex)) {
								$filter_word = '';
								for($i=0; $i<count($filter_hex); $i++) {
									$filter_word.=chr(hexdec($filter_hex[$i]));
								}
								//echo $word;
							}//////if (isset($filter_hex)) {
							
					}	/////////// if(isset($filter)) {
					
					
					if (isset($filter_word) AND isset($filter_group)) {////////////Смотрим товары по фильтру
					     $criteria=new CDbCriteria;
						 $criteria->select="t.id_product";
						 $criteria->condition="t.value = :value AND t.id_caract=:id_caract";
						 $criteria->distinct = true;
						 $criteria->params=array(':value'=>$filter_word, 'id_caract'=>$filter_group );
						 $models = Characteristics_values::model()->findAll($criteria);
						 if (isset($models)) for ($k=0; $k<count( $models); $k++)  $filter_products[]= $models[$k]->id_product;
						// print_r($filter_products);
					}/////////if (isset($filter_word)) {////////////См
					

				if ((isset($filter_products) OR isset($filter_values)==false) OR ($this->cat->category_id > 5 )) {		
					 $criteria=new CDbCriteria;
					 $criteria->select=array( 't.*',  'picture_product.picture AS icon' );
					 $criteria->condition="t.category_belong  = :group";
					 $criteria->join ="
		LEFT JOIN ( SELECT id, product, picture FROM picture_product WHERE is_main=1) picture_product ON picture_product.product = t.id  ";
					 $criteria->addCondition("t.product_visible = 1");
					if (isset($filter_products)) $criteria->addCondition("t.id IN ( ".implode(',',$filter_products )." )");
					$criteria->order = "t.product_sellout DESC, t.product_sellout_sort, t.product_price, t.product_name";
					 //$criteria->addCondition("char_val.value = :filtr_value");
					 $criteria->params=array(':group'=>$this->cat->category_id);
					// $criteria->params=array(':id_caract'=>$this->year_char_id);
					 $products = Products::model()->with('char_val',  'belong_category')->findAll($criteria);
					 $pages=new CPagination(Products::model()->with('char_val')->count($criteria)); //////////////// 6 мс
					//$pages->params=array('sort'=>$sort);
					$pages->pageSize=$this->PAGE_SIZE;
					$pages->applyLimit($criteria);
					
					if (isset($products)) {
						for ($i=0; $i<count($products); $i++) $productslist[]=$products[$i]->id;
						if (isset($productslist)) {
						//print_r($productslist);
						$connection = Yii::app()->db;
						$query = "SELECT id_product, GROUP_CONCAT(CONCAT_WS( ';#;', value,id_caract ) ORDER BY characteristics.sort  SEPARATOR '#;#') AS  attribute_value2 FROM characteristics_values  JOIN characteristics ON characteristics.caract_id = characteristics_values.id_caract  WHERE id_product IN (".implode(',', $productslist).")  GROUP BY id_product ORDER BY characteristics_values.value_id";////////////
						//echo $query;
						$command=$connection->createCommand($query)	;
						$dataReader=$command->query();
						$records=$dataReader->readAll();////
			
									if (isset($records)) {
												for($i=0; $i<count($records); $i++) $products_attributes[$records[$i]['id_product']]=$records[$i]['attribute_value2'];
									}
			
									//print_r($products_attributes);
							///////////////////Смотрим цены у подчиненных
							//echo 'ewrewr';
							$criteria=new CDbCriteria;
							$criteria->select="t.product_parent_id,  SUM(t.product_price) AS attribute_value";
							$criteria->group="t.product_parent_id";
							$criteria->condition="t.product_parent_id IN (".implode(',', $productslist).")";
							$criteria->addCondition("t.product_visible = 1");
							$prices = Products::model()->findAll($criteria);
							if (isset($prices)) $prices_by_childs = CHtml::listdata($prices, 'product_parent_id',  'attribute_value');
							//print_r($prices_by_childs);			
						 }///////////if (isset($productslist)) {
					}
				}////////if (isset($filter_products)) {		
						

			$this->render('group_filter_select', array('CAT'=>$this->cat, 'products'=>@$products, 'products_attributes'=>@$products_attributes, 'characteristics_array'=>@$characteristics_array, 'filter_values'=>@$filter_values, 'filter'=>@$filter, 'prices_by_childs'=>@$prices_by_childs));
	}////////public function actionGroup() {	
		

	public function actionInfo(){//////Карточка товара
			
			$this->Add_To_Cart();
			
			if (isset($this->product)) {
					$connection = Yii::app()->db;
					$query = "SELECT id_product, GROUP_CONCAT(CONCAT_WS( ';#;', value,id_caract ) ORDER BY characteristics.sort  SEPARATOR '#;#') AS  attribute_value2 FROM characteristics_values  JOIN characteristics ON characteristics.caract_id = characteristics_values.id_caract  WHERE id_product =:id_product  GROUP BY id_product ORDER BY characteristics_values.value_id";////////////
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
			
			$this->render('info', array('product'=>$this->product, 'CAT'=>$this->cat, 'products_attributes'=>@$products_attributes, 'characteristics_array'=>@$characteristics_array, 'pictures'=>@$pictures, 'compabile'=>@$compabile, 'childs'=>@$childs));
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
					//$data = CHtml::listData($models,'category_id','category_name');
					//$data = array('0'=> iconv( "CP1251", "UTF-8", "...выберети модель") )+$data;
					//$select = CHtml::dropDownList('model',  NULL, $data, array('id'=>'model', 'class'=>'catalog_select'));
					//foreach($data as $value=>$name)
					//$models_list=CHtml::tag('option', array('value'=>0),CHtml::encode("Модель"),true);
					$models_list=CHtml::tag('option', array('value'=>0), iconv( "UTF-8", "CP1251", "Модель"));
					for($k=0; $k<count($models); $k++)
							{
								$models_list.=CHtml::tag('option', array('value'=>$models[$k]->category_id),CHtml::encode(iconv( "UTF-8", "CP1251", $models[$k]->category_name)),true);
							}
					echo CJSON::encode(array(
							  'models'=>$models_list
							));
			}//////////
	}//////public function actionGetgroups(){////////Ад
	
	
	public function actionGetyears(){///////
			//print_r($_POST);
			$brand = Yii::app()->getRequest()->getParam('brand');
			$model = Yii::app()->getRequest()->getParam('model');
			$group = Yii::app()->getRequest()->getParam('group');
			
			 //$models_list=CHtml::tag('option', array('value'=>0),CHtml::encode("...нет вариантов"),true);
			 $models_list=CHtml::tag('option', array('value'=>0),iconv( "UTF-8", "CP1251", "...нет вариантов")); 
									$notfound = CJSON::encode(array(
									  'models'=>$models_list
									));
			$models_list = NULL;
			
			if (isset($brand)  AND isset($model) AND isset($group)) {
				//echo 'werwer';
					$criteria=new CDbCriteria;
					//$criteria->order = 't.sort_category';
					$criteria->condition = " t.type = 2 AND t.group = :group AND product.product_visible = 1 ";
					$criteria->params=array(':group'=>$group);
					$models = Categories_products::model()->with('product')->findAll($criteria);//
					//$products_1_array = CHtml::listData($models,'group','product');
					if (isset($models)) for ($i=0; $i<count($models); $i++) $products_1[]=$models[$i]->product;
					//echo count($products_1).'<br>';
					
					$criteria=new CDbCriteria;
					//$criteria->order = 't.sort_category';
					$criteria->condition = " t.type = 1 AND t.group = :group AND product.product_visible = 1 ";
					$criteria->params=array(':group'=>$model);
					$models = Categories_products::model()->with('product')->findAll($criteria);//
					if (isset($models)) for ($i=0; $i<count($models); $i++) $products_2[]=$models[$i]->product;
					//echo count($products_2).'<br>';
					
					//if (isset($products_2))print_r($products_2);
					
					if (isset($products_2) AND isset($products_1)) {
					$rezult=array_intersect ($products_1, $products_2);
					}
					
					//print_r($rezult);
					
					if (isset($rezult) AND empty($rezult)==false) {
						 $criteria=new CDbCriteria;
						 $criteria->condition="t.id_caract = :id_caract AND t.id_product IN (".implode(',', $rezult).")";
						 $criteria->params=array(':id_caract'=>$this->year_char_id);
						 $models = Characteristics_values::model()->findAll($criteria);
						
						//echo '<br>'.count($models).'<br>';
						
						 if (isset($models))  {
							 
							 	$years_list1 = array();
								for($k=0; $k<count($models); $k++)
									{
										if (array_key_exists($models[$k]->value,  $years_list1)==false ) $years_list1[$models[$k]->value] = $models[$k]->value_id;
									}
									
									$years_list = array_flip($years_list1);
									//$years_list = array('0'=>'выбор года') + $years_list;
									$years_list = array('0'=>iconv( "UTF-8", "CP1251", 'Год')) + $years_list;
									
							 
								
								//for($k=0; $k<count($models); $k++)
								foreach ($years_list as $key=>$value)	
									{
										$models_list.=CHtml::tag('option', array('value'=>$key),CHtml::encode($value),true);
									}
							echo CJSON::encode(array(
									  'models'=>$models_list
									));
									 
						} ///// if (isset($models))  {
							else echo $notfound;
							
					}///////////////
					else echo  $notfound;
					
					
			}//////if (isset($brand)  AND isset($model) AND isset($group)) {
	}////////////
}
