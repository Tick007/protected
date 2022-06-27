<?php

class ProductController extends CController
{
	var $PAGE_SIZE=50;
	

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
			'check_category_existance + list',
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
				'actions'=>array('list','show', 'details', 'vendor'),
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
	
	public function filterCheck_category_existance($filterChain)	{//////////Если не был указан идентификатор партнера - то выдать 404 ошибку
			
			$show_group = Yii::app()->getRequest()->getParam('id', NULL);
			//echo $show_group;
			//if ($show_group==NULL)  throw new CHttpException(404,'Группа в каталоге не существует.');
			//else {//1
					$cat = Categoriestradex::model()->findbyPk($show_group);
					//print_r($cat);
					if (isset($show_group) AND $cat==NULL) throw new CHttpException(404,'Группа в каталоге не существует.');
					else $filterChain->run();//////////////Вызов остальных фильтров (если они есть)
			//	}////////else {//1
		}
	
	public function actionShow()
	{
		$this->render('show',array('model'=>$this->loadUser()));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'show' page.
	 */
/*
	public function actionCreate()
	{
		$model=new User;
		if(isset($_POST['User']))
		{
			$model->attributes=$_POST['User'];
			if($model->save())
				$this->redirect(array('show','id'=>$model->id));
		}
		$this->render('create',array('model'=>$model));
	}
	*/
	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'show' page.
	 */
	 /*
	public function actionUpdate()
	{
		$model=$this->loadUser();
		if(isset($_POST['User']))
		{
			$model->attributes=$_POST['User'];
			if($model->save())
				$this->redirect(array('show','id'=>$model->id));
		}
		$this->render('update',array('model'=>$model));
	}
	*/
	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'list' page.
	 */
	 
	 public function get_productiya_path($parent){
				///////////////////////////////Вычисляем путь по дереву продукции
				$Path = Categoriestradex::model()->findbyPk($parent);
				$parent_id = $Path->parent;
				/////////Yii::app()->params->drupal_vars['taxonomy_catalog_level'] - нулевой уровень каталога
				$path_text = CHtml::link($Path->category_name, Yii::app()->request->baseUrl.'/product/'.$Path->category_id, $htmlOptions=array ('encode'=>false));
				while ($parent_id>0) {
				
				$Path = Categoriestradex::model()->findbyPk($parent_id);
				$parent_id = $Path->parent;
				//if (trim($parent_id )=='')$parent_id =9;
				$path_text=CHtml::link($Path->category_name, Yii::app()->request->baseUrl.'/product/'.$Path->category_id, $htmlOptions=array ('encode'=>false)).' -> '.$path_text;
				}///////while
				$path_text= CHtml::link('Каталог', '/product/', $htmlOptions=array ('encode'=>true)).' -> '.$path_text;
				return $path_text;
	}/////////////
	 
	 public function Add_To_Cart() {
	 		if (isset($_POST['add_to_basket']) AND is_numeric($_POST['add_to_basket'])) {//////////////////Добавление в корзину
			$tovar_id=intval(trim($_POST['add_to_basket']));
			$MyBasket = new MyShoppingCart($tovar_id);
			}
			
			$cookie=Yii::app()->request->cookies['YiiCart'];
			if (isset($cookie)){
			 $value=$cookie->value;
			//echo "Сейчас установленные куки ".$value."/<br>";
	 		}
			//else echo "Нет куки<br>";
		}
	 
	public function actionDelete()
	{
		if(Yii::app()->request->isPostRequest)
		{
			// we only allow deletion via POST request
			$this->loadUser()->delete();
			$this->redirect(array('list'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	public function actionDetails() {
	$cookie=Yii::app()->request->cookies['YiiCart'];
	if (isset($cookie)){
	 $value=$cookie->value;
	//echo "Ранее установленные куки ".$value."/<br>";
	}
	// else echo "Нет куки<br>";
	
	$pd = Yii::app()->getRequest()->getParam('pd', NULL);
	
	if (isset($pd) AND is_numeric(trim(htmlspecialchars($pd)))==true ) {
	//echo "1<br>";
	$this->Add_To_Cart();
	//else echo "Нет куки<br>";
	
		$ProductList=new Product;
		$ProductList->ExecuteObject(); ////////////перенес из констракта в отдельный метод
		$models = $ProductList->run_query();

		
		$pd = Yii::app()->getRequest()->getParam('pd');
		
		$product = Products::model()->findByPk($pd);
		$path_text = $this->get_productiya_path ($product->category_belong);
		
		//if (count($models)>0)
		//foreach($models as $n=>$next) $product_name[]=$next['product_name'];
		//print_r($product_name);
		
		/////////////////////////////////Вытаскиваем совместимые товары
			$criteria=new CDbCriteria;
			$criteria->order = ' t.product ';
			$criteria->condition = "compprod.product_visible=1 AND t.product = ".$pd;
			$compabile= Products_compability::model()->with('compprod')->findAll($criteria);//
			
			
		///////////////////Вытаскиваем обратно совместимые товары /*
			$criteria=new CDbCriteria;
			$criteria->order = ' t.product ';
			$criteria->condition = "backcompprod.product_visible=1 AND t.compatible = ".$pd;
			$back_compabile= Products_compability::model()->with('backcompprod')->findAll($criteria);//
		
		
		
		 $this->render('details',array('models'=>$models,'attributes'=>$ProductList->attributeLabels(), 'stores_names'=>$ProductList->get_stores_names(), 'stores_id'=>$ProductList->get_stores_id(), /*'show_group'=>$ProductList->get_product_belong_to($_GET['good_details']) */'characterictics'=>$ProductList->get_characterictics() , 'sg'=>$ProductList->show_group, 'additional_pictures'=>$ProductList->additional_pictures() /*, 'compability_list'=>$ProductList->compability_list()*/, 'product'=>$product, 'path_text'=>$path_text, 'compabile'=>$compabile, 'back_compabile'=>$back_compabile ));
		//else {///////////if (count($product_name)>0) $
		
		//}////////////else {///////////if (count($product_name)>0) $
		
	}///////if (isset($_GET("details")) AND is_numeric(trim(htnlspecialchars($_GET("details"))))) {
	else throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	
	
	
	}//////////public function actionDetails() {

	public function actionList()
	{
	//print_r($_POST);
	//echo '<br>';
	//print_r($_GET);

$show_group = Yii::app()->getRequest()->getParam('id') ;	

$ProductList=new Product;
$this->Add_To_Cart();///////////////Добавление в корзину
$ProductList->ExecuteObject(); ////////////перенес из констракта в отдельный метод

	


//echo $show_group;
//exit();

if(isset($show_group)) 
{
$ProductList->show_group=$show_group;
$criteria=new CDbCriteria();
$result=Yii::app()->db->createCommand($ProductList->CountingQuery());
$count=$result->queryRow();  

  
//Создаем CPagination на основе кол-ва записей:
$pages=new CPagination($count[$ProductList->count_alias]);
$pages->pageSize=$this->PAGE_SIZE;
$pages->applyLimit($criteria);
$ProductList->offset = $pages->currentPage*$pages->pageSize;
$ProductList->limit = $pages->pageSize;
$models = $ProductList->run_query();

$path_text = $this->get_productiya_path ($ProductList->show_group);

//echo 'число товаров = '.$ProductList->num_of_rows.'<br>';
//print_r($_POST);

////////////////Вытаскиваем статические прайс листы
$criteria=new CDbCriteria;
$criteria->order = ' t.id DESC ';
$criteria->condition = " picture_category.category_belong = ".$show_group;
$gruppa_files = Pictures::model()->with('picture_category')->findAll($criteria);//

$group_obj = Categoriestradex::model()->findByPk($ProductList->show_group);


$criteria=new CDbCriteria;
$criteria->condition='t.parent=:parent AND t.show_category=:show_category ';
$criteria->params=array(':parent'=>$show_group, 'show_category'=>1);
$subgroups=Catalog::model()->findAll($criteria);
//print_r($subgroups);


if($ProductList->num_of_rows > 0 OR isset($_POST['yt3'])  ) $this->render('list',array('models'=>$models,'pages'=>$pages, 'attributes'=>$ProductList->attributeLabels(),  'sg'=>$ProductList->show_group,'stores_names'=>$ProductList->get_stores_names(), 'stores_id'=>$ProductList->get_stores_id(), 'view' =>$ProductList->out_mode, 'sort_order'=>$ProductList->sort_order, 'charact_list'=>$ProductList->charact_list, 'char_values_list'=>$ProductList->char_values_list, 'cfid_arr'=>$ProductList->cfid_arr, 'path_text'=>$path_text, 'gruppa_files'=>$gruppa_files, 'group_obj'=>$group_obj, 'subgroups'=>$subgroups));
else {/////////if($ProductList->num_of_rows>0) $th
		$criteria=new CDbCriteria;
		$criteria->condition='t.parent=:parent AND t.show_category=:show_category ';
		$criteria->order = 't.category_name ';
		$criteria->params=array(':parent'=>$ProductList->show_group, 'show_category'=>1);
		$models=Catalog::model()->with('childs')->findAll($criteria);
		//echo count($models);
		//exit();
		$this->render('main',array(
		'models'=>$models, 'path_text'=>$path_text,  'gruppa_files'=>$gruppa_files
		));
}/////////////////if($ProductList->num_of_rows>0) $th

	}/////////////////if(isset($show_group)) 
	
	else {
			///////////Подгружаеи данные своей модели.
			$criteria=new CDbCriteria;
			$criteria->condition='t.parent=:parent AND t.show_category=:show_category ';
			$criteria->params=array(':parent'=>0, 'show_category'=>1);
	
			//if (isset($_POST['section_id'])) $models=Page::model()->findByAttributes(array('section'=>$_POST['section_id']));
			//else 
			$models=Catalog::model()->with('childs')->findAll($criteria);
	
			$this->render('main',array(
				'models'=>$models,  'gruppa_files'=>$gruppa_files

			));
	}//////////////////////////////else {
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$this->processAdminCommand();

		$criteria=new CDbCriteria;

		$pages=new CPagination(User::model()->count($criteria));
		$pages->pageSize=self::PAGE_SIZE;
		$pages->applyLimit($criteria);

		$sort=new CSort('User');
		$sort->applyOrder($criteria);

		$models=User::model()->findAll($criteria);

		$this->render('admin',array(
			'models'=>$models,
			'pages'=>$pages,
			'sort'=>$sort,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the primary key value. Defaults to null, meaning using the 'id' GET variable
	 */
	public function loadUser($id=null)
	{
		if($this->_model===null)
		{
			if($id!==null || isset($_GET['id']))
				$this->_model=User::model()->findbyPk($id!==null ? $id : $_GET['id']);
			if($this->_model===null)
				throw new CHttpException(404,'The requested page does not exist.');
		}
		return $this->_model;
	}

	/**
	 * Executes any command triggered on the admin page.
	 */
	protected function processAdminCommand()
	{
		if(isset($_POST['command'], $_POST['id']) && $_POST['command']==='delete')
		{
			$this->loadUser($_POST['id'])->delete();
			// reload the current page to avoid duplicated delete actions
			$this->refresh();
		}
	}
	
	public function actionVendor() {/////////////////выбор групп товаров по производителю
			//print_r($_GET);
			$vendor_arr=array_keys($_GET);
			$vendor=$vendor_arr[0];
			$vendor= iconv("UTF-8", "CP1251", $vendor);
			
			$query = "SELECT categories.category_id, categories.category_name FROM products JOIN categories ON categories.category_id = products.category_belong JOIN characteristics_values ON characteristics_values.id_product = products.id WHERE characteristics_values.id_caract = 175 AND characteristics_values.value = '$vendor'";
			//echo $query ;
			$connection = Yii::app()->db;
			$command=$connection->createCommand($query)	;
			$dataReader=$command->query();
			$rows=$dataReader->readAll();
			//return $row['category_belong'];
			//print_r($rows);
			/*
			$criteria=new CDbCriteria;
			//$criteria->order = ' belong_category.category_name';
			//$criteria->select=array(' belong_category.category_name');
			//$criteria->distinct = true;
			//$criteria->condition = " char_val.id_caract = :id_caract AND char_val.value = :value";
			$criteria->params=array(':value'=>$vendor, ':id_caract'=>175);
			$models = Products::model()->with('belong_category', 'char_val')->findAll($criteria);//
			//print_r($models);
			echo count($models)."<br>";
			*/
			
			//////////////////Теперь для каждой категори нужно вытащить массив значений на катагорию 175
			$gr_arr_id=NULL;
			$gr_arr_name=NULL;
			$gr_arr_parent = NULL;
			for($i=0; $i<count($rows); $i++) {
					
					//echo $rows[$i]->id.'<br>';
					if (@!in_array($rows[$i][category_id], $gr_arr_id) ) {
							
							$criteria=new CDbCriteria;
							$criteria->select = "value";
							$criteria->distinct=true;
							$criteria->join=" JOIN products ON products.id = t.id_product";
							$criteria->condition = " t.value <>  '' AND products.category_belong =:cat AND t.id_caract = :id_caract" ;
							$criteria->params=array(':cat'=>$rows[$i][category_id], ':id_caract'=>175);
							$values = Characteristics_values::model()->findAll($criteria);
							//print_r($values);
							//echo $rows[$i][category_id];
							$key_val=NULL;
							for ($k=0; $k<count($values); $k++) {//////////Составляем массив ключей
									//echo $values[$k]->value.'<br>';
									$key_val[]=strtolower($values[$k]->value);
							}////////////////////
							
							$param_key = array_search(strtolower($vendor), $key_val);
							//print_r($key_val);
							//echo '<br>';
							//echo $vendor.' '.$param_key.'<br>';
							
							$gr_arr_id[]=$rows[$i][category_id];
							$gr_arr_name[] = $rows[$i][category_name];
							$model = Catalog::model()->with('parmodel')->findbyPk($rows[$i][category_id]);
							$gr_arr_parent[] = $model->parmodel->category_name;
							$gr_arr_list_vals[] = $param_key; 
							
					}
			}///////////for($i=0; $i<count($models); $i++) {
			//print_r($gr_arr_list_vals);
			
			$this->render('vendors',array('models'=>$models, 'gr_arr_id'=>$gr_arr_id, 'gr_arr_name'=>$gr_arr_name, 'vendor'=>$vendor, 'gr_arr_parent'=>$gr_arr_parent,'gr_arr_list_vals'=>$gr_arr_list_vals ));
	}///////////////////
	
}
