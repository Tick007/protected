<?php

class CatalogController extends Controller
{
	var $PAGE_SIZE=32;
	var $cat;
	var $nofollow; /////////////////Тэги для хакрытия страницы от индексации

	/**
	 * @var string specifies the default action to be 'list'.
	 */
	public $defaultAction='list';

	/**
	 * @var CActiveRecord the currently loaded data model instance.
	 */
	var $CAT;/////группа, которая заполняется при попадании в метод group
	var $PROD; //////////Товар, заполняется при info

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'check_category_existance + group, catgroup, info',
		    'check_product_existance +  info',
		    'HasJsFile + index, group, catgroup, info',
		    'CheckPathDetails +details',
			'CheckPathList +list',
			'CheckBrouser +index, error, group, info, search',
			'SetTheme +index, error, group, info, search',
			(isset(Yii::app()->params['use_long_urls']) AND Yii::app()->params['use_long_urls'] == true) ? 'CheckGroupPath +list   ' : 'EmptyFilter +list',
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
				'actions'=>array('list','show', 'group', 'vendor', 'info', 'sale', 'index', 'error', 'search'),
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

	
	public function filterCheck_category_existance($filterChain)	{//////////Если не был указан идентификатор партнера - то выдать 404 ошибкуr 
			$alias = Yii::app()->getRequest()->getParam('alias', null);
			
			if( $alias!=null && trim($alias)!='') {
				$cat = Categories::model()->with('page')->findByAttributes(array('alias'=>$alias));
				if($cat!=null) {
					$this->CAT = $cat;
					
					if($this->CAT->show_category==0){
						throw new CHttpException(404,'Category is disabled');
						exit();
					}
					else $filterChain->run();	
				}
				else {

					throw new CHttpException(404,'Группа не найдена');
					//exit();
				}
			}
			else {
				throw new CHttpException(404,'Не указана группа');
				exit();
			}
			
	}//////////public function filterCheck_category_existance($filterChain)	{/
	
	
	
	public function init(){
		//if(isset(Yii::app()->params['product_page_size'])) $this->PAGE_SIZE = Yii::app()->params['product_page_size'];
		
		
		
	}
	
	public function actionError() {
		
		$this->layout="main";
		if($error=Yii::app()->errorHandler->error)
	    {
	    	if(Yii::app()->request->isAjaxRequest)
	    		echo $error['message'];
	    	else {
				//$this->render('pages/404', array('error'=>$error) );
				$this->render('/common/error',array ('error'=>$error, 'tree'=> Categories::getCatalogTree()) );
			}
	    }
		}
	
	
	public function actionIndex(){
	    $params = array(':parent'=>Yii::app()->params['main_page']->root_dir);
		
		$dataProvider=new CActiveDataProvider('Categories', array(
				'criteria'=>array(
         			'condition'=>"t.parent = :parent  AND t.alias<>'' AND t.show_category=1 ",
					'order'=>'t.category_name',
		 			'params'=>$params,
					
    			),
				'pagination'=>array(
					'pageVar'=>'page',
					'pageSize'=>Yii::app()->request->getQuery('size', 100),				)
				
			)
		);

		
		$this->render('catgroup',array(
				'dataProvider'=>$dataProvider, 
				'tree'=> Categories::getCatalogTree(),
				
		));
	}
	
	
	public function actionGroup($alias) {
		/*
		echo 'werwer';
		
		print_r($this->CAT->products);
		
		echo $this->CAT->category_id.'<br>';
		
		$products=Products::model()->findAllByAttributes(array('category_belong'=>$this->CAT->category_id));
		
		
		echo count($products);
		*/
		
		//echo '1';

	    
		//////////Смотрим товары в смежных группах
		$categories_products = Categories_products::model()->findAllByAttributes(array('group'=>$this->CAT->category_id));
		

		//echo '2';
	
		
		if(count($this->CAT->products)>0 || count($categories_products)>0 || $this->CAT->show_category==1) {
			
		   // echo '3';

			
			
			$groups = NULL;
			///////////Рендерим частично группы
			if(count($this->CAT->child_categories)>0) {
				
			   // echo '4';
		
				////////////////////////////////////////////////////bsystem2  второй параметр заменил на false
				$groups = $this->catGroup($alias, false);
				//echo $groups;
			}
			
			//echo '5';
		
			
			$this->finalGroup($alias, $groups);////////////конечная группа{
		
		}
		else		
		{
			
		
		    //echo '6';

			
			if(count($this->CAT->child_categories)>0) { ///////////не Конечная группа
				

				
				//$products = $this->finalGroup($alias, true);
				$this->catGroup($alias);
			
			}
			
			//echo '7';
			
			
		}
		
		
	}
	
	
	/**
	 * Группа уровня списка товаров
	 */
	 
	 /////промежуточная группа
	private function catGroup($alias, $return_group_list=false){
		
	   /* 
        echo '3';
        exit();
        */
		
		
		$params = array(':parent'=>$this->CAT->category_id);
		
		$dataProvider=new CActiveDataProvider('Categories', array(
				'criteria'=>array(
         			'condition'=>"t.parent = :parent AND t.alias<>'' AND t.show_category=1 ",
					'order'=>'t.category_name',
		 			'params'=>$params,
					
    			),
				'pagination'=>array(
					'pageVar'=>'page',
					'pageSize'=>Yii::app()->request->getQuery('size', 100),				)
				
			)
		);
		

		
		//////////Это рендер только списка групп для передаче в файл с товарами
		if($return_group_list==true) return $this->renderPartial('catgroup_body',array(
				'dataProvider'=>$dataProvider, 
				'tree'=> Categories::getCatalogTree(),
				'alias'=>$alias,
		), true);
		
		else {
		    $this->render('catgroup',array(
		        'dataProvider'=>$dataProvider,
		        'tree'=> Categories::getCatalogTree(),
		        'alias'=>$alias,
		    ));
		    exit();
		}
				
		
	}
	
		
	
	
	/**
	 * Вывод выпадающих меню для брэдкрамбс по переданному alias группы
	 */
	public function getChilds($alias){
	if( $alias!=null && trim($alias)!='') {
		
				$cat = Categories::model()->with('childs')->findByAttributes(array('alias'=>$alias));
				if($cat!=null && count($cat->childs)>0) {
					$ul = '<ul>';
					foreach($cat->childs as $child) 
						if($child->show_category==1 && trim($child->alias)!='') $ul.='<li>'.CHtml::link($child->category_name, array('catalog/group',
								'alias'=>$child->alias)).'</li>';
					return $ul.'</ul>';
				}
				else {
					
				}
			}
			else {
				
			}
			
	}
	
	
	
	public function actionSearch(){
	   $this->finalGroup(null,null);
	}
	 
	private function finalGroup($alias, $prerendered_groups = NULL){
		
	
		$time1=microtime(true);
		
		$catcookie=Yii::app()->request->cookies['catDisplay'];
		if($catcookie!=null){
			$view=$catcookie->value;
		}
		else $view = 'list';
		
		
		
		if(isset($this->CAT)) $filters = $this->getGroupFilters($this->CAT->category_id);
		$form_filters = Yii::app()->request->getPost('ListForm', NULL);
		if($form_filters==NULL){
			$ListForm =  Yii::app()->request->getParam('ListForm', NULL); ////////////Смотрим, может оно есть в GETе
			if($ListForm!=NULL){ ////////Делаем редирект на пост - хаха. Так нельзя
				$form_filters = $ListForm ;
			}
		}
		//print_r($form_filters);
		$search = Yii::app()->getRequest()->getParam('search', null);
		
		if(($search=='' || trim($search)=='') && isset($this->CAT)==false){
		    
		    throw new CHttpException(404,'No search value and no category');
		    exit();
		}
		//var_dump($search);
		//exit();
		
		
		if(trim($search)!=''){
			$search_found_ids = Products::findProductsBywords($search, null);
			//print_r($rows);
		}
		//echo $search;
		$price_from = Yii::app()->getRequest()->getParam('price_from', null);
		$price_to = Yii::app()->getRequest()->getParam('price_to', null);
		//print_r($form_filters);
		///Смотрим есть ли фильтры с морды апдейтим филтры группы
				if(isset($form_filters) && isset($form_filters['cfid_arr']))foreach ($form_filters['cfid_arr'] as $caract_id=>$num_value_arr){
					if(isset($filters[$caract_id])){
						foreach ($num_value_arr as $num=>$checked) $filters[$caract_id]['checked'][$num]=$filters[$caract_id]['list'][$num];
					}
				}
				//echo '<pre>';
				//print_r($filters);
				//echo '</pre>';
				////Смотрим, есть ли какие-то фильтры и если да, то нужно вытащить ид товаров с этими фильтрами из characteristics_values
				if(isset($filters) && empty($filters)==false)foreach ($filters as $caract_id => $filter){
					if(isset($filter['checked'])) $search_crit[$caract_id]=" (t.id_caract = ".$caract_id." AND ( t.value= '".implode("' OR value='", $filter['checked'])."'))";
		
				}
				if(isset($search_crit)) {
					$conditions = array_values($search_crit);
					$cond = implode(' AND', $conditions);
					$criteria=new CDbCriteria;
					$criteria->select="t.id_product";
					$criteria->distinct = true;
					$criteria->distinct = true;
					$criteria->condition = $cond;
					$criteria->addCondition("products.category_belong = :category_belong", 'AND');
					$criteria->params = array(':category_belong'=>$this->CAT->category_id);
					$characteristics_values = Characteristics_values::model()->with('products')->findAll($criteria);
					if($characteristics_values!=null) {
						$filter_ids = CHtml::listData($characteristics_values, 'id_product', 'id_product');
					}
				}
				
		
		$time2=microtime(true);
		$execution_time[1] = $time2-$time1;
				
		/////////////Нужно смотреть в связянных группах
		if(isset($this->CAT)) $linked= Categories_products::model()->findAllByAttributes(array('group'=>$this->CAT->category_id));
				
		///////////////////////Обработка фильтров		
	
		
		//$params = array(':category_id'=>$this->CAT->category_id);
		$params=array();
		$condition = ' t.product_visible = 1 ';
		$criteria=new CDbCriteria;
		//$condition = '(t.category_belong = :category_id';
		if(isset($linked) && $linked!=null && count($linked)>0){
			foreach($linked as $lp){
				$linked_arr[]=$lp->product;
			}
			$condition.= ' OR t.id IN ('.implode(',',$linked_arr).')';
		}
		
		if(isset($filter_ids) && isset($form_filters['cfid_arr'])) {
			$condition.= " AND t.id IN (".implode(',', array_keys($filter_ids)).")" ;

		}
		else if(isset($form_filters['cfid_arr']) && isset($filter_ids)==false){//////т.е. id товаров по соответствующим критериям нет
			$condition.= " AND t.id=0";

		}
		if($search!=null){
			if(isset($search_found_ids) && is_array($search_found_ids)==true){
				$condition.= " AND (t.id IN (".implode($search_found_ids, ',').")) ";
			}
			else {//////////в ids уже должны сидеть все ид. Если там пусто, то при условии что ничего не нашли выведется всё из БД
			    ///////////////Что бы этого избежать нужно задать здесб нереаальное условие
			    $condition.= " AND false";
			}
			//$condition.= " AND (t.product_name LIKE '%".htmlspecialchars($search)."%' OR t.product_article LIKE '%".htmlspecialchars($search)."%') ";
		}
		elseif(isset($this->CAT)){
    		$params[':category_id']=$this->CAT->category_id;
    		$condition.= " AND (t.category_belong  = :category_id)";
		}
		if($price_from!=null && $price_from>1 && is_numeric($price_from)) {
			$condition.= " AND t.product_price >='".$price_from."' ";
		}
		
		if($price_to!=null && is_numeric($price_to)) {
			$condition.= " AND t.product_price <='".$price_to."' ";
		}
		//echo $condition;
		$criteria = array('params'=>$params);
		
		
		
		$criteria['condition']=$condition;
		
		
		///////////Так в принципе добавляется поиск
		//$criteria['condition'].=" AND t.product_name LIKE '%thio%'";

		
		
		$this->processPageRequest('page');
		
		$dataProvider=new CActiveDataProvider('Products', array(
				'criteria'=>$criteria,
				'pagination'=>array(
					'pageVar'=>'page',
				    'pageSize'=>Yii::app()->request->getQuery('size', isset(Yii::app()->params['catalog']->desktopItemsNum)?Yii::app()->params['catalog']->desktopItemsNum:100),	
					
				)
				
			)
		);
		
		
		$time3=microtime(true);
		$execution_time[2] = $time3-$time2;
		$execution_time['cont'] = $time3-$time1;
		

		$render_params = array(
				'dataProvider'=>$dataProvider,
				'tree'=> Categories::getCatalogTree(),
				'alias'=>$alias,
				
				'view'=>$view,
				'groups'=>$prerendered_groups,
				'execution_time'=>$execution_time,
		);
		if(isset($filters)) $render_params['filters']=$filters;
		if(isset($search) && trim($search)!='') $render_params['search']=$search;
		
		if (Yii::app()->request->isAjaxRequest) $this->renderPartial('groupAjax', $render_params);
		else {
			$this->render('group', $render_params);
		}
	}
	
	
	protected function processPageRequest($param='page')
	{
		if (Yii::app()->request->isAjaxRequest && isset($_POST[$param]))
			$_GET[$param] = Yii::app()->request->getPost($param);
	}
	
	
	/**
	 * Выборка фильтров для конечной группы
	 */
	private function getGroupFilters($category_id){
		$criteria=new CDbCriteria;
		$criteria->condition = " t.caract_category = :caract_category OR is_common=1  ";
		$criteria->distinct = true;
		$criteria->order = " t.sort";
		$criteria->params = array(':caract_category'=>$category_id);
		$filter_models = Characteristics::model()->findAll($criteria);
		if(isset($filter_models)){
			foreach ($filter_models as $characteristics){
				$characteristics_ids[] = $characteristics->caract_id;
				$characteristics_list_by_id[$characteristics->caract_id]=$characteristics->attributes;
			}
			if(isset($characteristics_ids)){ ////////////Выбираем значения по каждой группе
				$criteria=new CDbCriteria;
				$criteria->distinct = true;
				$criteria->condition = " t.id_caract IN (".implode(',', $characteristics_ids).") AND products.category_belong = :category_belong ";
				$criteria->params = array(':category_belong'=>$category_id);
				$characteristics_values = Characteristics_values::model()->with('products')->findAll($criteria);
				if(isset($characteristics_values) AND $characteristics_values!=null){
					foreach ($characteristics_values as $characteristics_value){
						if(isset($characteristics_list_by_id[$characteristics_value->id_caract])){
							if(@!in_array($characteristics_value->value, $characteristics_list_by_id[$characteristics_value->id_caract]['list']))$characteristics_list_by_id[$characteristics_value->id_caract]['list'][]=$characteristics_value->value;
						}
					}
					
				}
				
			}
		}
		if(isset($characteristics_list_by_id)) return $characteristics_list_by_id;	
		else return null;
	}
	
	public function getGroupImages($models){
		
		for($i=0, $c=count($models); $i<$c; $i++){
			$selected[]=$models[$i]->id;
	
		}
		$criteria=new CDbCriteria;
		$criteria->condition = " t.product IN (".implode(',', $selected).") AND t.is_main = 1";
		$picture_products = Picture_product::model()->findAll($criteria);
		if(isset($picture_products)) $pictures_list = CHtml::listdata($picture_products, 'picture', 'product');
		if(isset($pictures_list) AND empty($pictures_list)==false) {
			$criteria=new CDbCriteria;
			$criteria->condition = " t.id IN (".implode(',',  array_keys($pictures_list)).") ";
			$pictures = Pictures::model()->findAll($criteria);
			if(isset($pictures) AND empty($pictures)==false) {
				for ($i=0; $i<count($pictures); $i++) {
					if(isset($pictures_list[$pictures[$i]->id])) {
						$pict_ext[$pictures_list[$pictures[$i]->id]] = array('icon'=>$pictures[$i]->id, 'ext'=>$pictures[$i]->ext);
					}
				}
				
				//print_r($pict_ext);
				
			}///if(isset($pictures) AND empty($pictures)==false) {
		}///////if(isset($pictures_list) AND empty($p
		
		if(isset($pict_ext)) return($pict_ext);
		
	}
	
	public function Actioninfo(){
		
	    
		
		$hist = new MyShoppingCart2();
		$hist->addToHistory($this->PROD->id);
	
		//print_r($recently);
		$cart_products_var = Controller::getCokieProducts();
		if($cart_products_var!=null) $cart_products_variations = array_keys($cart_products_var);
		else $cart_products_variations=null;
		
		$render_params = array(
				'images'=>$this->getProductImages($this->PROD->id),
				'options'=>Products::getProductOptions($this->PROD->id),
				'compatible'=>Products::getCompatibleProducts($this->PROD->id),
				'recently'=>$this->getHistoryProducts($hist, $this->PROD->id),
		        'cart_products_variations'=>$cart_products_variations,
		);
		
		
		

		$this->render('info',$render_params );
	}
	
	
	
	
/* Перенес в контроллер
	private function getProductImages($product_id){
		$criteria=new CDbCriteria;
		$criteria->condition = " t.product = :product_id";
		$criteria->params= array(':product_id'=>$product_id);
		$picture_products = Picture_product::model()->findAll($criteria);
		if($picture_products!=null){
			foreach($picture_products as $picture){
				//print_r($picture->attributes);
				//echo '<br>';
				if($picture->is_main==1) $images['main']=$picture->attributes;
				else $images['not_main'][]=$picture->attributes;
				//$images['all'][]=$picture->attributes;
			}
		}
		$picture_products = NULL;
		if(isset($images)) return $images;
		else return null;
	}
	*/
	
}///////class
