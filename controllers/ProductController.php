<?php

class ProductController extends Controller
{
	var $PAGE_SIZE=32;
	var $cat;
	var $nofollow; /////////////////Тэги для хакрытия страницы от индексации
	var $CAT;

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
			'CheckBrouser +index, list, details, error',
			'check_category_existance + list, details',
			'CheckPathDetails +details',
			'CheckPathList +list',
			(isset(Yii::app()->params['use_long_urls']) AND Yii::app()->params['use_long_urls'] == true) ? 'CheckGroupPath +list   ' : 'EmptyFilter +list',
		
			'SetTheme +index, list, details, error',
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
				'actions'=>array('list','show', 'details', 'vendor', 'novinki', 'sale', 'index'),
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
	
	public function init(){
		if(isset(Yii::app()->params['product_page_size'])) $this->PAGE_SIZE = Yii::app()->params['product_page_size'];
		
		
		
	}
	
	public function  filterCheckPathDetails($filterChain)	{///////
		$income_get = $_GET;
		//if (count($income_get)>1) throw new CHttpException(404,'Ошибка');
		//else
		/*
		print_r($_GET);
		echo $this->action->id;
			exit();
		*/
		 $filterChain->run();
	}////public function  filterCheckPath($filterChain)	{///////
	
	public function  filterCheckPathList($filterChain)	{///////
		//print_r($_GET);
		//exit();
		
		$income_get = $_GET;
		/*
		if (count($income_get)>0) {
				foreach()
		}
		*/
		//if (count($income_get)>3) throw new CHttpException(404,'Ошибка');
		//else $filterChain->run();
		 $filterChain->run();
	}////public function  filterCheckPath($filterChain)	{///////
	
	public function filterCheck_category_existance($filterChain)	{//////////Если не был указан идентификатор партнера - то выдать 404 ошибкуr 
			
			
				$show_group = Yii::app()->getRequest()->getParam('id') ;	
				$product_id = Yii::app()->getRequest()->getParam('pd') ;	
				$alias = Yii::app()->getRequest()->getParam('alias');
				$search = Yii::app()->getRequest()->getParam('search');	


			/*
			if ($show_group==NULL AND trim($alias)) {
				$cat = Categories::model()->findbyAttributes(array('alias'=>$alias));
			}//////////if ($show_group==NULL AND trim($alias)) {
			if 	(isset($cat->category_id)) {
				$filterChain->run();
				exit();
			}
			*/
			
			
			
			
			//echo '1<br>';
			//echo $this->action->id.'<br>';
			//echo $alias.'<br>';
			//exit();
			
			if( isset($alias) AND  is_numeric($alias) == true) {
				
				
				
				$show_group	 = $alias;
				unset($alias);
				$cat = Categories::model()->findByPk($show_group);
				
				

				
				if (isset($cat->alias) AND trim($cat->alias)!='' ) {
					

					
					$path_array = array('alias'=>$cat->alias);
					if(isset($search) AND trim($search)) $path_array['search']=htmlspecialchars($search);
					$url = Yii::app()->createUrl('/product/list', $path_array);
					$this->redirect($url, true, 301);	
					exit();
				}
				if (isset($cat)==false) {
					 throw new CHttpException(404,'Нет группы');
					 exit();
				}
			}
			if (isset($show_group)==false AND isset($alias) AND $alias!='details') {
					
					//echo $alias;
				
					$cat = Categories::model()->findbyAttributes(array('alias'=>trim($alias), 'show_category'=>1));
					if(isset($cat)==false){
						

					
						throw new CHttpException(404,'Группа не найдена');
						exit();
					}
					
			}//////////if ($show_group==NULL AND trim($alias)) {
			elseif(isset($show_group) AND is_numeric($show_group)==true) {
				
				//echo 'werwer<br>';
				
				$cat = Categories::model()->findbyPk($show_group);
				if (isset($cat)==false) throw new CHttpException(404,'Группа в каталоге не существует.');
				else $filterChain->run();//////////////Вызов остальных фильтров (если они есть)
				exit();
			}

			
			if(isset($product_id)) { ////////////////////////////////Далее товар проверяется
				$prod = Products::model()->findByPk(htmlspecialchars($product_id));
				if((isset($prod) AND  $prod->product_visible 	==0 ) OR isset($prod)==false OR (isset($prod) AND isset($prod->belong_category)  AND  $prod->belong_category->show_category==0)) {
					// echo '23423';
					//throw new CHttpException(410,'Карточка товара выключена или не существует');
					//exit();
					
				}
			}
			
			if(isset($cat)) {
				
				
				
				if(isset($cat) AND $cat->show_category!=1) {////////////////Проверка не выключена ли группа
					 throw new CHttpException(404,'Группа отключена');
					 exit();
				}
				
				$this->cat = $cat;
				$filterChain->run();	
			}
			elseif(isset($prod) AND isset($cat)==false) {
					//$path_array = array('alias'=>$prod->belong_category->category_id, 'pd'=>$prod->id);
					//if(isset($search) AND trim($search)) $path_array['search']=htmlspecialchars($search);
					//$url = Yii::app()->createUrl('/product/details', $path_array);
					//$this->redirect($url, true, 301);	
					//echo $url;
					$cat = Categories::model()->findbyPk($prod->belong_category->category_id);
					if(isset($cat)) {
						$this->cat = $cat;
						$filterChain->run();	
					}
					else throw new CHttpException(404,'Ошибочная ссылка');
					
			}
			else {
						if(isset(Yii::app()->params['main_tree_root'])) {
							$this->cat = Categories::model()->findbyPk(Yii::app()->params['main_tree_root']);
							$filterChain->run();	
						}
						else throw new CHttpException(404,'Группа не найдена');
				}
			
	}//////////public function filterCheck_category_existance($filterChain)	{/
	
	
	public function  filterCheckGroupPath($filterChain)	{/////проверка соответствия группы тому пути в урлу по которому она вызывается
	
	
	
									$path = Yii::app()->getRequest()->getParam('path');	
									/* Это часть использовалась на этапе отработки, когда я пытался сделать без переменной path в правилах urlmanager
									///////////Проверяем, присутствуют ли в пути все родители
									$request = Yii::app()->getRequest();
									$qqq = $request->pathInfo;
									//echo $qqq.'<br>';
									//exit();
									if (isset($kladr)) $qqq = str_replace("r_".$kladr."/", '',  $qqq);
									if (isset($kladr)) $qqq = str_replace("r-".$kladr."/", '',  $qqq);
									//echo $qqq.'<br>';
									$qqq = str_replace("/".$this->CAT->alias, '',  $qqq);
									//echo $qqq;
									*/
									//echo 'path income ='.$path.'<br>';
									$qqq= $path;
									$parents = explode('/', $qqq );
	if(isset($this->cat->path)){
									$cat_path = array_values(unserialize($this->cat->path));
									//echo 'pathgroup = ';
									//print_r(unserialize($this->cat->path));
									//echo '<br>';
									
									for ($k=1;$k<count($cat_path)-1; $k++ ) $cat_aliases[] = str_replace('/', '', $cat_path[$k]);
									
									//print_r($cat_aliases);
									//echo '<br>';
									//print_r($parents);
									//exit();
									if (isset($cat_aliases) AND Yii::app()->request->isAjaxRequest == false ) {////////Только если длина пути позволяет сравнивать.Аджаксам оставляем право обращаться без длинного пути
											$compare_paths = ( $cat_aliases==$parents);
											if ($compare_paths==false AND (isset($path) AND trim($path)!='' )) throw new CHttpException(404,'Неверный адрес: не совпадает путь');
											else {
												$filterChain->run();
												exit();
											}
									}//////if (isset($cat_aliases)) {///
	}
									$filterChain->run();
		}//////////public function  filterCheckGroupPath($filterChain)	{//
	
	/*
	public function actionShow()
	{
		$this->render('show',array('model'=>$this->loadUser()));
	}
	*/

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
	 
	 public function get_title_path($parent){
				///////////////////////////////Вычисляем путь по дереву продукции
				$Path = Categoriestradex::model()->findbyPk($parent);
				$parent_id = $Path->parent;
				/////////Yii::app()->params->drupal_vars['taxonomy_catalog_level'] - нулевой уровень каталога
				$path_text = $Path->category_name;
				while ($parent_id>0) {
				
				$Path = Categoriestradex::model()->findbyPk($parent_id);
				$parent_id = $Path->parent;
				//if (trim($parent_id )=='')$parent_id =9;
				$path_text=$Path->category_name.' -> '.$path_text;
				}///////while
				
				return $path_text;
	}/////////////
	 
	 
	 
	 public function get_productiya_path_simple($parent){
				///////////////////////////////Вычисляем путь по дереву продукции
				
				$action = $this->action->id;
				$page = Yii::app()->getRequest()->getParam('page', 1);	
				
				
				$Path = Categoriestradex::model()->findbyPk($parent);
				$parent_id = $Path->parent;
				/////////Yii::app()->params->drupal_vars['taxonomy_catalog_level'] - нулевой уровень каталога
				if ($this->action->id=='list' AND $page==1) $path_text = '<span>'.$Path->category_name.'</span>';
				
				else {		
							if (isset($Path->alias) AND trim($Path->alias)!='') {
								if(isset(Yii::app()->params['use_long_urls']) AND Yii::app()->params['use_long_urls']==true) {
									 $url=urldecode(Yii::app()->createUrl('/product/list', array( 'alias'=>$Path->alias, 'path'=>FHtml::urlpath($Path->path))));
								}
								else $url=urldecode(Yii::app()->createUrl('/product/list', array( 'id'=>$Path->category_id)));
							}
							else $url=urldecode(Yii::app()->createUrl('/product/list', array( 'id'=>$Path->category_id)));
							
							$path_text = CHtml::link($Path->category_name, $url);
							
							if($action=='details' OR ($this->action->id=='list' AND $page>2)) $path_text.= ' -> '.CHtml::link('Назад', 'javascript:history.back()');
					}
				if(isset(Yii::app()->params['main_tree_root']))  $qqq = Yii::app()->params['main_tree_root'];
				else $qqq = 0;	
				while ($parent_id>$qqq) {
				
				$Path = Categoriestradex::model()->findbyPk($parent_id);
				$parent_id = $Path->parent;
				//if (trim($parent_id )=='')$parent_id =9;
				if (isset($Path->alias) AND trim($Path->alias)!='') {

					if(isset(Yii::app()->params['use_long_urls']) AND Yii::app()->params['use_long_urls']==true)  {
						if($Path->parent == Yii::app()->params['main_tree_root'] ) $url=urldecode(Yii::app()->createUrl('/product/list', array('alias'=>$Path->alias ) ) );
						else $url=urldecode(Yii::app()->createUrl('/product/list', array('alias'=>$Path->alias, 'path'=>FHtml::urlpath($Path->path) ) ) );
					}
					else  $url=urldecode(Yii::app()->createUrl('/product/list', array( 'id'=>$Path->category_id)));
				}
				else $url=urldecode(Yii::app()->createUrl('/product/list', array('id'=>$Path->category_id)));
				
				//print_r($url);
				//echo '<br>';
				
				$path_text=CHtml::link($Path->category_name, $url).' -> '.$path_text;
				
				}///////while
				/*
				$path_text= CHtml::link(Yii::app()->params->coreElementDescr, '/', $htmlOptions=array ('encode'=>true, 'title'=>'Пневмоинструмент')).' -> '.CHtml::link('Каталог', '/product/', $htmlOptions=array ('encode'=>true)).' -> '.$path_text;
				*/
				
				
				if($action=='list' AND $page>1) {
					//	echo '<pre>';
						//print_r($path_text);
					//	echo '</pre>';
				}
				
				return $path_text;
	}/////////////
	 
	 
	 public function get_productiya_path($parent){
				///////////////////////////////Вычисляем путь по дереву продукции
				
				$action = $this->action->id;
				$page = Yii::app()->getRequest()->getParam('page', 1);	
				
				
				$Path = Categoriestradex::model()->findbyPk($parent);
				$parent_id = $Path->parent;
				/////////Yii::app()->params->drupal_vars['taxonomy_catalog_level'] - нулевой уровень каталога
				if ($this->action->id=='list' AND $page==1) $path_text = $Path->category_name;
				
				else {		
							if (isset($Path->alias) AND trim($Path->alias)!='') {
								if(isset(Yii::app()->params['use_long_urls']) AND Yii::app()->params['use_long_urls']==true) {
									 $url=urldecode(Yii::app()->createUrl('/product/list', array( 'alias'=>$Path->alias, 'path'=>FHtml::urlpath($Path->path))));
								}
								else $url=urldecode(Yii::app()->createUrl('/product/list', array( 'alias'=>$Path->alias)));
							}
							else $url=urldecode(Yii::app()->createUrl('/product/list', array( 'id'=>$Path->category_id)));
							
							$path_text = CHtml::link($Path->category_name, $url);
							
							if($action=='details' OR ($this->action->id=='list' AND $page>2)) $path_text.= ' -> '.CHtml::link('Назад', 'javascript:history.back()');
					}
				if(isset(Yii::app()->params['main_tree_root']))  $qqq = Yii::app()->params['main_tree_root'];
				else $qqq = 0;	
				while ($parent_id>$qqq) {
				
				$Path = Categoriestradex::model()->findbyPk($parent_id);
				$parent_id = $Path->parent;
				//if (trim($parent_id )=='')$parent_id =9;
				if (isset($Path->alias) AND trim($Path->alias)!='') {

					if(isset(Yii::app()->params['use_long_urls']) AND Yii::app()->params['use_long_urls']==true)  {
						if($Path->parent == Yii::app()->params['main_tree_root'] ) $url=urldecode(Yii::app()->createUrl('/product/list', array('alias'=>$Path->alias ) ) );
						else $url=urldecode(Yii::app()->createUrl('/product/list', array('alias'=>$Path->alias, 'path'=>FHtml::urlpath($Path->path) ) ) );
					}
					else  $url=urldecode(Yii::app()->createUrl('/product/list', array('alias'=>$Path->alias)));
				}
				else $url=urldecode(Yii::app()->createUrl('/product/list', array('id'=>$Path->category_id)));
				
				//print_r($url);
				//echo '<br>';
				
				$path_text=CHtml::link($Path->category_name, $url).' -> '.$path_text;
				
				}///////while
				/*
				$path_text= CHtml::link(Yii::app()->params->coreElementDescr, '/', $htmlOptions=array ('encode'=>true, 'title'=>'Пневмоинструмент')).' -> '.CHtml::link('Каталог', '/product/', $htmlOptions=array ('encode'=>true)).' -> '.$path_text;
				*/
				
				
				if($action=='list' AND $page>1) {
					//	echo '<pre>';
						//print_r($path_text);
					//	echo '</pre>';
				}
				
				return $path_text;
	}/////////////
	 
	
	 

	public function actionDetails() {
		
		
		
		
	$time1 = microtime(true);	
		
	$cookie=Yii::app()->request->cookies['YiiCart'];
	if (isset($cookie)){
	 $value=$cookie->value;
	//echo "Ранее установленные куки ".$value."/<br>";
	}
	// else echo "Нет куки<br>";
	
		$pd = Yii::app()->getRequest()->getParam('pd');
		$alias = Yii::app()->getRequest()->getParam('alias', NULL);
		
	/*	
	echo 'После кукис: ';	
	$tme2 = microtime(true);	
	echo $tme2 - $time1;
	echo '<br>';
			*/
	
	
	
	$client_discount = FHtml::getClientDiscount();
		
	if (isset($pd) AND is_numeric(trim(htmlspecialchars($pd)))==true ) {
		
		/*
	$criteria=new CDbCriteria;
	$criteria->condition = " t.id = $pd";
	$criteria->select=array( 't.*',  'picture_product.picture AS icon' , 'picture_product.ext AS ext');
		//$criteria->together = true;
	$criteria->join =" LEFT JOIN ( SELECT product, picture, pictures.ext as ext FROM picture_product  JOIN pictures ON pictures.id= picture_product.picture  WHERE is_main=1 ) picture_product ON picture_product.product = t.id  ";
	$product = Products::model()->with('belong_category')->find($criteria);	
	
	*/
	
	$product = Products::model()->with('belong_category')->findByPk($pd);
	
	if((isset($product) AND $product->product_visible!=1) OR isset($product)==false OR (isset($product->belong_category) AND $product->belong_category->show_category!=1)) {////////////////Проверка не выключен ли товар
		 throw new CHttpException(404,'Товар отключен');
		 exit();
	}
	else {/////////////Вытаскиваем основную инфу
		$product->belong_category = Categories::model()->findByPk($product->category_belong);
		$picture_product = Picture_product::model()->findAllByAttributes(array('product'=>$pd, 'is_main'=>1));
		if(isset($picture_product) AND isset($picture_product[0])) {
			$picture = Pictures::model()->findByPk($picture_product[0]->picture);
			if(isset($picture)) {
				$product->icon = $picture->id;
				$product->ext = $picture->ext;
			}
		}
	}
	
	
	//$time2=microtime(true);
	//echo '2 - '.($time2- $time1);
	//echo '<br>';
		
	//echo "1<br>";
	$this->Add_To_Cart();
	//else echo "Нет куки<br>";
	/*
	echo 'После выборки товара: ';
		$tme2 = microtime(true);	
	echo $tme2 - $time1;
	echo '<br>';
	*/
	
	
		$ProductList=new Product;
		
		//$time2=microtime(true);
		//echo '2.3 - '. ($time2- $time1);
		//echo '<br>'		;
		/*
	echo 'После инициализации продакт: ';	
		$tme2 = microtime(true);	
	echo $tme2 - $time1;
	echo '<br>';
	
		*/
		
		
		$ProductList->ExecuteObject(); ////////////перенес из констракта в отдельный метод
		
		//$time2=microtime(true);
		//echo '2.4 - '. ($time2- $time1);
		//echo '<br>'		;
		/*
	echo 'После выполнения продакт: ';		
		$tme2 = microtime(true);	
	echo $tme2 - $time1;
	echo '<br>';
		*/
		$models = $ProductList->run_query();



		//$time2=microtime(true);
		//echo '2.5 - '. ($time2- $time1);
		//echo '<br>';

		if (isset($product->category_belong) AND ((isset($alias) == false AND isset(Yii::app()->params['use_long_urls']) AND Yii::app()->params['use_long_urls']==true )OR @$alias == 'details' )) { ///////////////Делаем только при длинных урлах. При заданных коротких путь типа http://yii-site/product/29936.html - корректен и ведет к товару. Специально для GYDZ
				$cat = Categories::model()->findByPk($product->category_belong);
				if (isset($cat->alias) AND trim($cat->alias)!='' )	 {
							if(isset(Yii::app()->params['use_long_urls']) AND Yii::app()->params['use_long_urls']==true ) $url = urldecode(Yii::app()->createUrl('/product/details', array('alias'=>$cat->alias, 'pd'=>$product->id, 'path'=>FHtml::urlpath($cat->path))));
							else 	$url = Yii::app()->createUrl('/product/details', array('alias'=>$cat->alias, 'pd'=>$product->id));
							
							$this->redirect($url, true, 301);	
							exit();
					}//////////if (isset($cat->alias))	 {
		}////////////if (isset($product->category_belong)) {
		

		//$time2=microtime(true);
		//echo '3 - '. ($time2- $time1);
		//echo '<br>';
		
		//$path_text = $this->get_productiya_path ($product->category_belong);
		
		//if (count($models)>0)
		//foreach($models as $n=>$next) $product_name[]=$next['product_name'];
		//print_r($product_name);
		
		/////////////////////////////////Вытаскиваем совместимые товары
			$criteria=new CDbCriteria;
			$criteria->order = ' t.product ';
		/*
			$criteria->select=array( 't.*',  'picture_product.picture AS icon', ' picture_product.ext AS ext' );
					$criteria->join ="
				LEFT JOIN ( SELECT picture_product.id, product, picture, pictures.ext  FROM picture_product  JOIN pictures ON pictures.id =picture_product.picture WHERE is_main=1 ) picture_product ON picture_product.product = t.compatible  ";
				*/
			
			$criteria->distinct = true;
			$criteria->condition = "compprod.product_visible=1  AND t.product = ".$pd;
			if($_SERVER['HTTP_HOST']!='pnevmoinstrument.ru') $criteria->addCondition("compprod.product_price>0");
			$compabile= Products_compability::model()->with('compprod')->findAll($criteria);//
			if(isset($compabile)) {////////То смотрим их картинки

				$comp_list_ids = array_keys(CHtml::listdata($compabile, 'product', 'product'));
				if(isset($comp_list_ids) AND empty($comp_list_ids)==false AND is_array($comp_list_ids)  ) {
						$criteria=new CDbCriteria;
						$criteria->condition = " t.product IN (".implode(',',  $comp_list_ids).") AND t.is_main = 1";
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
									if(isset($pict_ext))  {
									//	print_r($pict_ext);
										for($i=0; $i<count($compabile); $i++) {
											if(isset($pict_ext[$compabile[$i]->product])) {
												$compabile[$i]->icon = $pict_ext[$compabile[$i]->product]['icon'];
												$compabile[$i]->ext = $pict_ext[$compabile[$i]->product]['ext'];
											}
										}
									}///////if(isset($pict_ext))  {
								}
							}///if(isset($pictures) AND empty($pictures)==false) {
						}///////if(isset($pictures_list) AND empty($p
				}//////if(isset($comp_list_ids) AND empty($comp_list_ids)==false AND
				
			}
			
			
			$all_categs=Categories::model()->findAll();
			$all_categs_list  = CHtml::listdata($all_categs, 'category_id', 'alias');
		/*
	echo 'После Вытаскиваем совместимые товары: ';			
			$tme2 = microtime(true);	
	echo $tme2 - $time1;
	echo '<br>';
	*/
			
		///////////////////Вытаскиваем обратно совместимые товары /*
			$criteria=new CDbCriteria;
			$criteria->distinct = true;
			$criteria->order = ' t.product ';
			$criteria->condition = "backcompprod.product_visible=1  AND backcompprod.product_price>0  AND t.compatible = ".$pd;
			$back_compabile= Products_compability::model()->with('backcompprod')->findAll($criteria);//
		
		
	//	$time2=microtime(true);
//	echo '4- '.($time2- $time1);
//	echo '<br>';
	/*
	echo 'После Вытаскиваем обратно совместимые товары : ';					
			$tme2 = microtime(true);	
	echo $tme2 - $time1;
	echo '<br>';
	*/
			
			//////////////////////////Вытаскиваем совместимые категории
			$criteria=new CDbCriteria;
			$criteria->order = ' t.id ';
			$criteria->condition =" t.category_id = ".$this->cat->category_id." AND t.active = 1 AND t.products <>'' ";
			$compatible_categories= Category_categories_compability::model()->with('compcategories')->findAll($criteria);//</strong>
			if(isset($compatible_categories)) {/////////////Разбиваем по принадлежности к группе и вытаскиваем товары
				for($i=0; $i<count($compatible_categories); $i++) {
					//echo $compatible_categories[$i]->compcategories->category_name.'->'.$compatible_categories[$i]->tabname.'<br>';
					//print_r(unserialize($compatible_categories[$i]->products));
					//echo '<br>';
				     $criteria=new CDbCriteria;
					 $criteria->select=array( 't.*',  'picture_product.picture AS icon' );
					 $criteria->condition="t.id IN (".implode(',' , unserialize($compatible_categories[$i]->products)).") ";
					 $criteria->join =" LEFT JOIN ( SELECT id, product, picture FROM picture_product WHERE is_main=1) picture_product ON picture_product.product = t.id  ";
					 $criteria->addCondition("t.product_visible = 1");
					 $criteria->addCondition("t.product_price > 0 ");
					 if(isset($limit) AND $limit!=NULL)  $criteria->limit = $limit;
					 if (isset($group) AND $group!=NULL) {
							$criteria->addCondition( "t.caract_category 	 = :caract_category  ");
							$criteria->params =  array('caract_category'=>$group);
					}
					$criteria->limit=31;
					// $criteria->params=array(':id_caract'=>$this->year_char_id);
					// $compatible_categories[$i]->compcategories->products_special = Products::model()->findAll($criteria);
					 $compatible_categories_products[$i] = Products::model()->findAll($criteria);
					// echo $i.'- '.count($compatible_categories[$i]->compcategories->products_special).'<br>';
					 
				}///////////	for($i=1; $i<count($compatible_categories); $i++) {
			}////////////if(isset($compatible_categories)) {////////

/*	
	$tme2 = microtime(true);	
	echo $tme2 - $time1;
	echo '<br>';
	*/	
	
	
		 $params = array('models'=>$models,'attributes'=>$ProductList->attributeLabels(), 'stores_names'=>$ProductList->get_stores_names(), 
		 		'stores_id'=>$ProductList->get_stores_id(), /*'show_group'=>$ProductList->get_product_belong_to($_GET['good_details']) */
		 		'characterictics'=>$ProductList->get_characterictics() , 'sg'=>$ProductList->show_group,
		 		'additional_pictures'=>$ProductList->additional_pictures() /*, 'compability_list'=>$ProductList->compability_list()*/,
		 		'product'=>$product, 'path_text'=>@$path_text, 'compabile'=>$compabile, 'back_compabile'=>$back_compabile, 'all_categs_list'=>$all_categs_list, 
		 		'compatible_categories'=>@$compatible_categories, 'compatible_categories_products'=>@$compatible_categories_products,
		 		'char_values_list' => $ProductList->char_values_list);
		 if($client_discount != NULL)  $params['client_discount'] = $client_discount;
		 
		 
		 $this->render('details', $params);
		//else {///////////if (count($product_name)>0) $
		
		//}////////////else {///////////if (count($product_name)>0) $
		
	}///////if (isset($_GET("details")) AND is_numeric(trim(htnlspecialchars($_GET("details"))))) {
	else throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	
	
	$tme2 = microtime(true);	
	//echo $tme2 - $time1;
	//echo '<br>';
	
	}//////////public function actionDetails() {

public function actionList()
	{
	$time1=microtime(true);
	//print_r($_POST);
	
	//print_r($_GET);
	//echo '<br>';
	
	//print_r($this->cat->attributes);
	

	$filters = Yii::app()->getRequest()->getParam('filters');
	$show_group = Yii::app()->getRequest()->getParam('id') ;	
	$alias = Yii::app()->getRequest()->getParam('alias');
	$page = Yii::app()->getRequest()->getParam('page', 1);
	$vendor = Yii::app()->getRequest()->getParam('vendor');


	$cat = $this->cat;

if 	(isset($cat->category_id)) {
		//echo '2<br>';
			$show_group = $cat->category_id;
			//echo $show_group;
}
else {
		$cat = Categories::model()->findbyPk($alias);
		if 	(isset($cat->category_id)) $show_group = $cat->category_id;
				if (isset($cat->alias) AND trim($cat->alias) !='') {
						$url = Yii::app()->createUrl('/product/list', array('alias'=>$cat->alias));
						$this->redirect($url, true, 301);	
						exit();
				}////////if (isset($cat->alias)) {
		} 

$group_obj = Categories::model()->with('page')->findByPk($show_group);
		
//exit();
$oridinal_id = Yii::app()->getRequest()->getParam('id');
if(isset($oridinal_id)==false AND isset($this->cat))$ProductList=new Product($cat->category_id);

else $ProductList=new Product;

$this->Add_To_Cart();///////////////Добавление в корзину

if(isset($show_group)) 
{
$ProductList->show_group=$show_group;

if (!isset(Yii::app()->params['mobile_theme']) || (isset(Yii::app()->params['mobile_theme'])==true && Yii::app()->theme->name != Yii::app()->params['mobile_theme']) ){ ////////////затычка, что бы этот код не работал в моб версии

		$criteria=new CDbCriteria();
		$query = $ProductList->CountingQuery();
		//echo $query.'<br>';
		$result=Yii::app()->db->createCommand($query);
		$count=$result->queryRow();  
		
		$execution_time['theme'] = Yii::app()->theme->name;
		$execution_time['mobile_theme'] = Yii::app()->params['mobile_theme'];
		
		$execution_time['counting'] = 'erewr';
		$pages=new CPagination($count['id_count']);
		
}
else { ////////////Для моб версии нам нужно просто посчитать быстро
	
	$criteria = new CDbCriteria();
	$criteria->condition = "t.category_belong = ".(int)$show_group.' AND t.product_visible = 1'	;
	$count=Products::model()->count($criteria);
	$ProductList->num_of_rows = $count;
	$pages=new CPagination($count);
	
}


//echo  "count = ";
//print_r($count);
//echo "<br>";

$time10=microtime(true);
$execution_time[0] = $time10-$time1;




$pages->pageSize=$this->PAGE_SIZE;

//echo '$page = '.$page;
//$pages->setCurrentPage($page) ;

//echo 'currentPage = '.$pages->currentPage;
//echo '$pages->pageSize = '.$pages->pageSize;

$pages->applyLimit($criteria);
$ProductList->offset = $pages->currentPage*$pages->pageSize;
$ProductList->limit = $pages->pageSize;

$time2=microtime(true);
$execution_time[1] = $time2-$time10;
//echo '$ProductList->offset = '.$ProductList->offset.'<br>';
//echo '$ProductList->limit  = '.$ProductList->limit .'<br>';


	if (!isset(Yii::app()->params['mobile_theme']) || (isset(Yii::app()->params['mobile_theme'])==true && Yii::app()->theme->name != Yii::app()->params['mobile_theme']) ){ ////////////затычка, что бы этот код не работал в моб версии
		$ProductList->ExecuteObject(); ////////////перенес из констракта в отдельный метод
		$models = $ProductList->run_query();
		$execution_time['query'] = 'query';
	}
	
$time3=microtime(true);
$execution_time[2] = $time3-$time2;	
	////////////////Вытаскиваем статические прайс листы
	$criteria=new CDbCriteria;
	$criteria->order = ' t.id DESC ';
	$criteria->condition = " picture_category.category_belong = ".$show_group;
	$gruppa_files = Pictures::model()->with('picture_category')->findAll($criteria);//


//echo '$show_group = ' .$show_group;
//exit();
//print_r($group_obj->attributes);
//exit();

$criteria=new CDbCriteria;
$criteria->condition='t.parent=:parent AND t.show_category=:show_category ';
$criteria->params=array(':parent'=>$show_group, 'show_category'=>1);
$subgroups=Catalog::model()->findAll($criteria);
//print_r($subgroups);

//echo $ProductList->num_of_rows ;




/////////////Выбираем атрибуты товаров в списке
if(isset($models)){ 
	//print_r($models);
	foreach($models as $n=>$product){
		$product_ids[]=$product["id"];
		$model_overrun[] = $product;
	}
	if(empty($product_ids)==false){
		
		$criteria=new CDbCriteria;
		$criteria->condition='t.id_product IN ('.implode(',', $product_ids).') ';
		//$criteria->params=array(':idlist'=>implode(',', $product_ids));
		$pr_attrs=Characteristics_values::model()->findAll($criteria);
	
		if($pr_attrs!=null){
			foreach ($pr_attrs as $char_var) {
				$product_attributes_new[$char_var->id_product][$char_var->id_caract]=$char_var->value;
			}
			//echo '<pre>';
			//print_r($product_attributes_new);
			//echo '</pre>';
		}
	}
}

if($ProductList->num_of_rows > 0 OR isset($_POST['ListForm']) OR isset($filters)  ) {
	
	
$params=array('pages'=>$pages, 
		'attributes'=>$ProductList->attributeLabels(),  
		'sg'=>$ProductList->show_group,
		'stores_names'=>$ProductList->get_stores_names(),
		'stores_id'=>$ProductList->get_stores_id(), 
		'view' =>$ProductList->out_mode,
		'sort_order'=>$ProductList->sort_order, 
		'charact_list'=>$ProductList->charact_list,
		'char_values_list'=>$ProductList->char_values_list, 
		'cfid_arr'=>$ProductList->cfid_arr,
		'title_path'=>$this->get_title_path($ProductList->show_group),
		'gruppa_files'=>$gruppa_files,
		'group_obj'=>$group_obj, 
		'subgroups'=>$subgroups, 
		'show_group'=>$show_group);
if(isset($models)) $params['models']=$model_overrun;
if(isset($path_text)) $params['path_text'] = $path_text;
if(isset($product_attributes_new)) $params['product_attributes_new']=$product_attributes_new;
if(isset($product_ids)) $params['product_ids']=$product_ids;
if(isset($vendor)) $params['vendor']=$vendor;
$time4=microtime(true);
$execution_time[3] = $time4-$time3;
$execution_time[4] = $time4-$time1;
$params['execution_time'] = $execution_time ;

$this->render('list',$params);
}
else {/////////if($ProductList->num_of_rows>0) $th




		$criteria=new CDbCriteria;
		$criteria->condition='t.parent=:parent AND t.show_category=:show_category ';
		$criteria->order = 't.sort_category';
		$criteria->params=array(':parent'=>$ProductList->show_group, 'show_category'=>1);
		$models=Categories::model()->with('childs')->findAll($criteria);
		//echo count($models);
		//exit();
		
		//////////////////////И перебираем категории для нахождения количества товаров
			if(isset($models) AND empty($models)==false) {
				$ids_list=CHtml::listdata($models, 'category_id', 'category_id');
				
				/*
				$criteria=new CDbCriteria;				
				$criteria->select=array("COUNT(t.id) AS product_count", 't.category_belong');
				$criteria->condition="t.category_belong IN (".implode(',', array_values($ids_list)).")";
				$criteria->group="category_belong";
				$products = Products::model()->findAll($criteria);*/
				
				$connection = Yii::app()->db;
				$query = "SELECT COUNT(t.id) AS product_count,
					t.category_belong
					FROM products t WHERE t.category_belong IN (".implode(',', array_values($ids_list)).") GROUP BY t.category_belong
					UNION
					SELECT
					 COUNT(categories_products.product) AS product_count,
					categories_products.group AS category_belong
				   FROM categories_products  WHERE categories_products.group  IN (".implode(',', array_values($ids_list)).") GROUP BY categories_products.group 	";
				$command=$connection->createCommand($query)	;
				$dataReader=$command->query();
				$records=$dataReader->readAll();////
				
				//echo $query;
				//print_r($records);
									
				if(isset($records)) for($i=0; $i<count($records); $i++)$products_num[$records[$i]['category_belong']] = $records[$i]['product_count'];
			}		

		
		$params = array('show_group'=>$show_group, 'models'=>$models,  
				'gruppa_files'=>$gruppa_files, 'group_obj'=>@$group_obj, 'title_path'=>$this->get_title_path($ProductList->show_group), 
				'products_num'=>@$products_num,
		);
		if(isset($path_text)) $params['path_text'] = $path_text;
		$params['group_obj']= $group_obj;
		

		$this->render('main', $params);
}/////////////////if($ProductList->num_of_rows>0) $th

	}/////////////////if(isset($show_group)) 
	
	else {
			///////////Подгружаеи данные своей модели.
			$criteria=new CDbCriteria;
			$criteria->condition='t.parent=:parent AND t.show_category=:show_category ';
			$criteria->params=array(':parent'=>0, 'show_category'=>1);
	
			//if (isset($_POST['section_id'])) $models=Page::model()->findByAttributes(array('section'=>$_POST['section_id']));
			//else 
			$models=Categories::model()->with('childs')->findAll($criteria);
			
	
			$this->render('main',array(
				'models'=>$models,  'gruppa_files'=>@$gruppa_files, 'title_path'=>'Каталог продукции',  'group_obj'=>@$group_obj ));
	}//////////////////////////////else {
	}

	/**
	 * Manages all models.
	 */
	public function actionIndex()
	{
		
		
		
		
		$criteria=new CDbCriteria;
		$criteria->condition='t.parent=:parent AND t.show_category=:show_category ';
		$criteria->order = 't.sort_category';
		$criteria->params=array(':parent'=>0, 'show_category'=>1);
		$models=Categories::model()->with('childs')->findAll($criteria);
		//echo count($models);
		//exit();
		$this->render('main',array('models'=>$models));
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
			/*
			$vendor_arr=array_keys($_GET);
			$vendor=$vendor_arr[0];
			$vendor= iconv("UTF-8", "CP1251", $vendor);
			*/
			
			$vendor = Yii::app()->getRequest()->getParam('alias', NULL);	
			$vendor = str_replace('_', ' ', urldecode($vendor));
			

$query = "SELECT DISTINCT categories.category_id, categories.category_name,  categories.path,  categories.alias  FROM products
		JOIN categories ON categories.category_id = products.category_belong JOIN characteristics_values ON characteristics_values.id_product = products.id
		WHERE categories.show_category = 1 AND characteristics_values.id_caract = ".Yii::app()->params['vendor_char_id']." AND characteristics_values.value = '$vendor'";
			//echo $query.'<br>' ;
			$connection = Yii::app()->db;
			$command=$connection->createCommand($query)	;
			$dataReader=$command->query();
			$rows=$dataReader->readAll();
			//return $row['category_belong'];
			//echo '<pre>';
			//print_r($rows);
			//echo '</pre>';
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
					
					$gr_alias[$rows[$i]['category_id']] = $rows[$i]['alias'];
					$gr_path[$rows[$i]['category_id']] = $rows[$i]['path'];
					//echo $rows[$i]['category_id'].' '.$rows[$i]['category_name'].'<br>';
					if (@!in_array($rows[$i][category_id], $gr_arr_id) ) {
							
							$criteria=new CDbCriteria;
							$criteria->select = "value";
							$criteria->distinct=true;
							$criteria->join=" JOIN products ON products.id = t.id_product JOIN characteristics c ON c.caract_id = t.id_caract";
							$criteria->condition = " t.value <>  '' AND products.category_belong =:cat AND t.id_caract = :id_caract" ;
							//$criteria->order = " t.value ";
							$criteria->order = " c.sort ";
							$criteria->params=array(':cat'=>$rows[$i]['category_id'], ':id_caract'=>Yii::app()->params['vendor_char_id']);
							$values = Characteristics_values::model()->findAll($criteria);
							//echo '<pre>';
							//print_r($values);
							//echo '</pre>';
							//echo '<pre>';
							//echo $rows[$i][category_id];
							//echo '</pre>';
							
							$key_val=NULL;
							for ($k=0; $k<count($values); $k++) {//////////Составляем массив ключей
									//echo $values[$k]->value.'<br>';
									$key_val[]=strtolower($values[$k]->value);
							}////////////////////
							
							
							$param_key = array_search(strtolower($vendor), $key_val);
							//echo $rows[$i]['category_name'].': ';
							//print_r($key_val);
							//echo '<br>';
							//echo $vendor.' '.$param_key.'<br>';
							//exit();
							
							$gr_arr_id[]=$rows[$i]['category_id'];
							$gr_arr_name[] = $rows[$i]['category_name'];
							$model = Catalog::model()->with('parmodel')->findbyPk($rows[$i]['category_id']);
							//if($model!=null ){
								if($model!=null && $model->parmodel!=null) $gr_arr_parent[] = $model->parmodel->category_name;
								else  $gr_arr_parent[]=null;
								$gr_arr_list_vals[] = $param_key;
								
							//}
							
							
					}
			}///////////for($i=0; $i<count($models); $i++) {
			//print_r($gr_arr_list_vals);
			//exit();
			
			if(isset($rows) && count($rows)>0 ){
				$this->render('vendors',array('models'=>@$models,
						'gr_arr_id'=>$gr_arr_id, 
						'gr_arr_name'=>$gr_arr_name, 
						'vendor'=>$vendor, 
						'gr_arr_parent'=>$gr_arr_parent,
						'gr_arr_list_vals'=>$gr_arr_list_vals, 
						'gr_alias'=>$gr_alias, 
						'gr_path'=>$gr_path 
						
				));
			}
			else {
					 throw new CHttpException(404,'Нет товаров с вендером');
					 exit();
			}
	}///////////////////
	
	public function actionNovinki(){/////////////Список новинок
			
			$criteria=new CDbCriteria;
 $criteria->select=array( 't.*',  'picture_product.picture AS icon' );
 $criteria->condition="t.product_new =1 ";
  $criteria->order=" t.product_new_sort ";
 $criteria->join ="
LEFT JOIN ( SELECT id, product, picture FROM picture_product WHERE is_main=1) picture_product ON picture_product.product = t.id  ";
 $criteria->addCondition("t.product_visible = 1");
// if($limit!=NULL)  $criteria->limit = $limit;
 if ($group!=NULL) {
$criteria->condition = "t.caract_category 	 = :caract_category  ";
$criteria->params =  array('caract_category'=>$group);
}
// $criteria->params=array(':id_caract'=>$this->year_char_id);
 $products = Products::model()->with('char_val', 'belong_category')->findAll($criteria);

if (isset($products)) {
		

	
		for ($i=0; $i<count($products); $i++) $productslist[]=$products[$i]->id;
		if (isset($productslist) AND is_array($productslist)==true AND empty($productslist)==false) {
		//print_r($productslist);
		$connection = Yii::app()->db;
		$query = "SELECT id_product, GROUP_CONCAT(CONCAT_WS( ';#;', value,id_caract ) ORDER BY characteristics.sort  SEPARATOR '#;#') AS  attribute_value2 FROM characteristics_values JOIN characteristics ON characteristics.caract_id = characteristics_values.id_caract WHERE id_product IN (".implode(',', $productslist).")  GROUP BY id_product  ";////////////
		//echo $query;
		$command=$connection->createCommand($query)	;
		$dataReader=$command->query();
		$records=$dataReader->readAll();////

					if (isset($records)) {
								for($i=0; $i<count($records); $i++) $products_attributes[$records[$i]['id_product']]=$records[$i]['attribute_value2'];
										}
				
										//print_r($products_attributes);
					 }
						
						
					
			
			/////////////////////////////////////////Вытаскиваем все характеристики, доступные для этой группы
			$criteria=new CDbCriteria;
			//$criteria->order = "characteristics_categories.sort, t.char_type , t.caract_name ";
			$characteristics1 = Characteristics::model()->findAll($criteria);
			for ($i=0; $i<count($characteristics1); $i++) {
					$characteristics_array[$characteristics1[$i]->caract_id]=array('char_type'=>4,  'caract_name'=>$characteristics1[$i]->caract_name, 'is_main'=>$characteristics1[$i]->is_main);
			}//////////for ($i=0; $i<count($characteristics); $i++) {
			
 $this->render('novinki', array('characteristics_array'=>@$characteristics_array, 'products_attributes'=>@$products_attributes, 'products'=>@$products, 'title'=>$title));	
}/////////if (isset($products)) {

			

	
	}/////////func
	
	
	public function actionSale(){/////////////Список новинок

			$criteria=new CDbCriteria;
 $criteria->select=array( 't.*',  'picture_product.picture AS icon' );
 $criteria->condition="t.product_sellout =1 ";
  $criteria->order=" t.product_sellout_sort ";
 $criteria->join ="
LEFT JOIN ( SELECT id, product, picture FROM picture_product WHERE is_main=1) picture_product ON picture_product.product = t.id  ";
 $criteria->addCondition("t.product_visible = 1");
// if($limit!=NULL)  $criteria->limit = $limit;
 if (isset($group) &&  $group!=NULL) {
$criteria->condition = "t.caract_category 	 = :caract_category  ";
$criteria->params =  array('caract_category'=>$group);
}
// $criteria->params=array(':id_caract'=>$this->year_char_id);
 $products = Products::model()->with('char_val', 'belong_category')->findAll($criteria);

if (isset($products)) {
		

	
		for ($i=0; $i<count($products); $i++) $productslist[]=$products[$i]->id;
		if (isset($productslist) AND is_array($productslist)==true AND empty($productslist)==false) {
		//print_r($productslist);
		$connection = Yii::app()->db;
		$query = "SELECT id_product, GROUP_CONCAT(CONCAT_WS( ';#;', value,id_caract ) ORDER BY characteristics.sort  SEPARATOR '#;#') AS  attribute_value2 FROM characteristics_values JOIN characteristics ON characteristics.caract_id = characteristics_values.id_caract WHERE id_product IN (".implode(',', $productslist).")  GROUP BY id_product  ";////////////
		//echo $query;
		$command=$connection->createCommand($query)	;
		$dataReader=$command->query();
		$records=$dataReader->readAll();////

					if (isset($records)) {
								for($i=0; $i<count($records); $i++) $products_attributes[$records[$i]['id_product']]=$records[$i]['attribute_value2'];
										}
				
										//print_r($products_attributes);
					 }
						
						
					
			
			/////////////////////////////////////////Вытаскиваем все характеристики, доступные для этой группы
			$criteria=new CDbCriteria;
			//$criteria->order = "characteristics_categories.sort, t.char_type , t.caract_name ";
			$characteristics1 = Characteristics::model()->findAll($criteria);
			for ($i=0; $i<count($characteristics1); $i++) {
					$characteristics_array[$characteristics1[$i]->caract_id]=array('char_type'=>4,  'caract_name'=>$characteristics1[$i]->caract_name, 'is_main'=>$characteristics1[$i]->is_main);
			}//////////for ($i=0; $i<count($characteristics); $i++) {
			
 $this->render('sale', array('characteristics_array'=>@$characteristics_array, 'products_attributes'=>@$products_attributes, 'products'=>@$products, 'title'=>@$title));	
}/////////if (isset($products)) {

			

	
	}
	
	
	public function group_products($products, $category_id=NULL){
		
		$debug = Yii::app()->getRequest()->getParam('debug');
		
		//echo 'products = '.count($products).'<br>';
		//echo 'category_id = '.$category_id.'<br>';
	

		if(count($products)==0 AND $category_id!=NULL) {///////////Это значит что товары прикреплены не к текущей группе а через categories_products
			///////////Вибраем товары 
			$connection = Yii::app()->db;
				$query = "SELECT  `product` FROM  `categories_products` WHERE  `group` = $category_id 	";
				$command=$connection->createCommand($query)	;
				$dataReader=$command->query();
				$records=$dataReader->readAll();////
				if(isset($records)) for($i=0; $i<count($records);$i++) {
					$prod_ids[] = $records[$i]['product'];
				}
				
			if (isset($prod_ids)){
			
				$criteria=new CDbCriteria;
				$criteria->condition="t.id IN (".implode(',',$prod_ids)." ) ";
				$products = Products::model()->findAll($criteria);
			
			}
		//echo $criteria->condition.'<br>';
		}
		
		
		
		
		if(isset($products) AND empty($products)==false) for($i=0; $i<count($products); $i++) {
										//echo 'wer';
										//print_r($next->products[$i]->ostatki);
				if(isset($products[$i]->ostatki)) {
					for($k=0; $k<count($products[$i]->ostatki); $k++) {
						if(isset($debug)) echo $products[$i]->ostatki[$k]->quantity.' - '.$products[$i]->ostatki[$k]->store_price.' - '.$products[$i]->product_name.'<br>';
						if($products[$i]->ostatki[$k]->quantity>0 AND ( $products[$i]->ostatki[$k]->store_price>0 OR $products[$i]->product_price>0 ))@$tovs[$products[$i]->id]=$tovs[$products[$i]->id]+$products[$i]->ostatki[$k]->quantity;
					}/////////for($k=0; $k<

					
				}
				
														
			}
			
			if(isset($debug) AND isset($tovs)) print_r($tovs);
			
			if (isset($tovs)) return $tovs;
			
	}////////public function group_products($products){
	
	
	public function get_child_groups($models){//////////Список групп детей
		foreach($models as $category) {
			$tovs = array();
			//echo $category->category_id.' - '.$category->category_name;
			
			//echo 'via = '.count($category->products_via_category).'<br>';
			
			//if(isset($category->products) )
			if(count($category->products)>0) $tovs=$this->group_products($category->products);
			else $tovs=$this->group_products($category->products, $category->category_id);
			if ( isset($category->child_categories) AND empty($category->child_categories)==false) {
				$tovs=$this->get_child_groups($category->child_categories); 
				//echo $category->category_name.'-qqqq<br>';
			}
			//echo count($category->products);
			//echo ' - ';
			//echo count($tovs);
			//echo '<br>';
			if(empty($tovs)==false) {
				if(isset($alltovs)) $alltovs=$alltovs + $tovs;
				else $alltovs = $tovs;
			}
		}
		return $alltovs;
	}//////////public function get_child_groups($models){////
	
	
	
}///////class
