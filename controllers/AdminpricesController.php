<?php

class AdminpricesController extends CController //////////////Главный контроллер администрирования товаров
{
	const PAGE_SIZE=10;/////////////////Количество записей на страницу
	var $NAME;
	var $PRODUCT;
	var $PRICE;
	var $ARTICLE;
	var $PRICE_MOD;
	var $STORE;
	public $temp_folder;
	public $sql_operations_limit;
	public $sql_operations_insert_limit;

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
			'CheckAuthority +pricelist, list, stepone, steptwo, steptree, loadpricelist, getpricelistproducts, updateposition, updatepricelistproducts, updateproductprice',
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
				'actions'=>array('pricelist', 'list', 'stepone', 'steptwo', 'steptree', 'loadpricelist', 'getpricelistproducts','updateposition', 'updatepricelistproducts', 'updateproductprice'),
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
			//$right =  iconv( "UTF-8", "cp1251", 'Изменение прайслистов');
			//$right =  iconv( "cp1251", "UTF-8", 'Изменение прайслистов');
			$right = 'Изменение прайслистов';
			//var_dump($right);
			if (Yii::app()->user->checkAccess($right) ) $filterChain->run();
			else {
			throw new CHttpException(401,'У вас нет прав');
			}
		}
		
	public function init() {
        Yii::app()->layout = "admin";
		$this->temp_folder = $_SERVER['DOCUMENT_ROOT'].'/pictures/temp/';
		$this->sql_operations_limit=2000;
		$this->sql_operations_insert_limit=1000;
		ini_set('max_execution_time', 300);
		ini_set("memory_limit","768M");
		//echo phpinfo();
    }
	
	
	public function actionStepone($id) {//////////Новый интерфейс загрузки прайслиста
		

		$stepone= new PriceListStepOneForm();
		
		$form_parametrs = Yii::app()->getRequest()->getParam('PriceListStepOneForm');
		
		///////Смотрим склады
		$criteria=new CDbCriteria;
		$criteria->condition = "t.kontragent_id = :cont_id AND t.id NOT IN(1,4)";
		$criteria->params = array(':cont_id'=>Yii::app()->params['self_contragent']);
		$stores = Stores::model()->findAll($criteria);
		$stores_list=array(''=>'...');
		if(isset($stores))$stores_list=$stores_list+CHtml::listdata($stores, 'id', 'name');
		
		
			if (isset($form_parametrs)) {
					$stepone->setAttributes($form_parametrs, false);
					$qqq = $stepone->validate();
					
					if ($qqq == true) { //////////Если все есть, то смотрим загруженный файл
						if(trim($stepone->downloadedname)!=''){//////Грузим файл во временное местоположение
														
							$stepone->movefiletotemrary($this->temp_folder);
							$tempfile = $stepone->tempfile;
							
							$tempfile_path = $this->temp_folder.$tempfile;
							
							if(file_exists($tempfile_path) AND is_file($tempfile_path)) {/////////Если все прошло нормально, то переходим к шагу 2
								$url =Yii::app()->createUrl('adminprices/steptwo', array('id'=>$id,  'attributes'=> urlencode(serialize($stepone->attributes) ) ));
								//echo $url;
								$this->redirect($url, true, 302);
								exit();
							}////////////if(file_exists($tempfile_path) AND is_file($tempfile_path)) {/////////
							
						}
					}///if ($qqq == true) { //////////Если все е
			}/////////if (isset($form_parametrs)) {
		
		
		
		
		$this->render('stepone', array('stores_list'=>$stores_list, 'id'=>$id, 'stepone'=>$stepone));
	}///////////public function actionStepone($id) {//////////Новы
	
	public function actionSteptwo($id) {//////////Новы
	
		//print_r($_GET);
		$attributes = unserialize(urldecode(Yii::app()->getRequest()->getParam('attributes')));
		$add_category = Yii::app()->getRequest()->getParam('add_category', NULL);
		$catrules = Yii::app()->getRequest()->getParam('catrules');
		$delcatrules = Yii::app()->getRequest()->getParam('delcatrules');
		//print_r($attributes);
		
		$store = Stores::model()->findByPk($attributes['store_id']);
		$plh = Price_list_header::model()->findByPk($id);
		
		$rules = Yii::app()->getRequest()->getParam('rules');
		if(isset($rules)) { //////////Обработка данных  переоценок для склада с формы
			
			$store->updatepricerules($rules);
		
		}////////if(isset($rules)) { //////////
		
		
		
		if(isset($catrules)) { //////////////Обновляем правила для категории
			$plh->updateCatRules($catrules);
		}//////////
		
		
		if(isset($delcatrules)) { ///////////////Переоцннки для групп
			$plh->deleteCatRules($delcatrules);
		}
		
		
		
		if(trim($add_category)) {////Добавляем новую связанную категорию
			if(trim($plh->catpricerules )==''	) $plh->catpricerules =serialize(array($add_category=>array()));
			$plh->appendNewCat($add_category);
		}
		
		if(trim($plh->catpricerules)!='')$catpricerule = $plh->selectrules();
		
		
		//////////////смотрим сколько строк в файле xls
		require_once 'excel_reader2.php';
		//print_r($attributes);
		$data = new Spreadsheet_Excel_Reader($this->temp_folder.$attributes['tempfile']);
		$num_of_rows =($data->rowcount($sheet_index=0))-1 ;
		//echo 	$this->temp_folder.$attributes['tempfile'].'<br>';
		//echo $num_of_rows;
		
		
		
		
		
		$this->render('steptwo', array('id'=>$id, 'attributes'=>$attributes, 'store'=>$store, 'attributes'=>$attributes, 'plh'=>$plh, 'num_of_rows'=>$num_of_rows));
	}
	
	public function actionLoadpricelist(){///////Копирование из xls в таблицы прайслиста
		//for($k=0; $k<10000000; $k++);
		//print_r($_POST);
		
		
		$this->PRICE = Yii::app()->getRequest()->getParam('price_col');/////////////Номер столбца с ценой
		$this->STORE = Yii::app()->getRequest()->getParam('store_col');
		$this->ARTICLE = Yii::app()->getRequest()->getParam('article_col');
		$step  = Yii::app()->getRequest()->getParam('step'); 
		$store_id= Yii::app()->getRequest()->getParam('store'); 
		$pricelist= Yii::app()->getRequest()->getParam('pricelist'); 
		$tempfile = Yii::app()->getRequest()->getParam('tempfile'); 
		$parts = Yii::app()->getRequest()->getParam('parts'); 
		
		
		
		$plh = Price_list_header::model()->findByPk($pricelist);
		if(trim($plh->catpricerules)) $catpricerules= unserialize($plh->catpricerules);

		if($step==1)  {
			$plh->clearpricelist($store_id); /////////////На первом шаге всё очищаем
		}


		
		$data = new Spreadsheet_Excel_Reader($this->temp_folder.$tempfile);
		
		
		$num_of_rows =($data->rowcount($sheet_index=0))-1 ;
		
		//echo  'sql_operations_limit = '.$this->sql_operations_limit;
		//echo 'step = '.$step;
		//$start_row = ($step-1)*$this->sql_operations_limit + 1;
		$end_row  =  $step*$this->sql_operations_limit;
		$start_row = $end_row - $this->sql_operations_limit+1;
		
		//echo 'start_row = '.$start_row;
		//echo 'end_row = '.$end_row;
		
		$store  = Stores::model()->findByPk($store_id);
		if(trim($store->pricerules)) $pricerules = unserialize($store->pricerules);
		
		
		
		for($i=$start_row+1; $i<=($end_row+1); $i++) {
			$number_in_store = (int)$data->val($i, $this->STORE);
			$product_art = $data->val($i, $this->ARTICLE);	
			if ($product_art!='') {
					//echo $i.': '.$data->val($i, 1).'<br>';	
					//echo $product_id.'<br>';
				$price_with_nds =  $data->val($i, $this->PRICE);
				$price_with_nds = str_replace(',', '.', $price_with_nds );
				$product_art = str_replace('(', '', $product_art);
				$product_art = str_replace("'", '', $product_art);
				
				$prod = Products::model()->findByAttributes(array('product_article'=>$product_art));
				if(isset($prod)) {
					//echo $prod->product_name;
					//echo $price_with_nds.' |';
					if (trim($plh->catpricerules) ) {/////Смотрим индивидуальные наценки группы
						
						if(isset($catpricerules[$prod->category_belong])) { 
							print_r($catpricerules[$prod->category_belong]);
							//echo $prod->product_name;
							$price_with_nds = $plh->getnewprice($price_with_nds, $prod->category_belong);
						}
						elseif(isset($pricerules)) $price_with_nds = $store->getnewprice($price_with_nds);////////;
					}
					elseif(isset($pricerules))  $price_with_nds = $store->getnewprice($price_with_nds);////////
					
					if(isset($prod->product_price_recomended) AND is_numeric($prod->product_price_recomended) AND $prod->product_price_recomended > $price_with_nds) $price_with_nds = $prod->product_price_recomended;////
					///////////////6) РРЦ ( рекоменованная розничная цена) - Если цена задана, то ниже этой цены товар стоит не должен. 
					
					
					//echo $price_with_nds.'    |';
					
					$pl = new Price_list_products_list();
					$pl->pricelist_id = $pricelist;
					$pl->product_id = $prod->id;
					if($prod->product_price_no_auto_update == 1) $pl->price_with_nds = $prod->product_price; 
					else $pl->price_with_nds = $price_with_nds;
					$pl->store = $number_in_store;
					
					
					 try {////////////////Добавляем только те товары у которых не стоит ключ запрета обновления
							$pl->save(false);
							//print_r($pl->attributes);
						} catch (Exception $e) {
						 	echo 'Caught exception: ',  $e->getMessage(), "\n";
						}//////////////////////
				}//////if(isset($prod)) {
				//$prod_update[$product_art] = array('price_with_nds'=>$price_with_nds, 'number_in_store'=>$number_in_store);
				//$prod_update_art_list[] = $product_art;
			}
		}//////	for($i=$start_row+1; $i<=($end_row+1); $i++) {
		
		if($step == $parts){
			 /////////удаляем файл
			$url = Yii::app()->createUrl('adminprices/steptree', array('id'=>$pricelist, 'store_id'=>$store_id, 'tempfile'=>$tempfile));
			echo json_encode('Предварительная загрузка прайслиста завершена. Вы можете либо перейти к '.CHtml::link('шагу 3', $url).' либо заново запустить первоначальную загрузку, изменив ценообразование');
		}
		
	}//////////////public function actionLoadpricelist(){///////Коп
	
	public function actionUpdatepricelistproducts(){///////////Обновление прайсов частями по аджакс
		$step  = Yii::app()->getRequest()->getParam('step'); 
		$store_id= Yii::app()->getRequest()->getParam('store'); 
		$pricelist= Yii::app()->getRequest()->getParam('pricelist'); 
		$tempfile = Yii::app()->getRequest()->getParam('tempfile'); 
		$parts = Yii::app()->getRequest()->getParam('parts'); 
		
		
		$num_of_rows = Price_list_header::get_positions_num($pricelist);
		
		$end_row  =  $step*$this->sql_operations_insert_limit;
		$start_row = $end_row - $this->sql_operations_insert_limit;
		
		//echo 'start_row = '.$start_row;
		//echo 'end_row = '.$end_row;
		
		$store  = Stores::model()->findByPk($store_id);

		if($step==1)  Ostatki_trigers::clear_ostatki_store($store_id);
		
		$connection=Yii::app()->db;
	$query = "SELECT * FROM price_list_products_list WHERE pricelist_id = ".$pricelist;
	$query.= " ORDER BY id LIMIT $start_row , ".$this->sql_operations_insert_limit;
	//echo $query;
	$command=$connection->createCommand($query)	;
	$dataReader=$command->query(); /////////////////После таког
	$models = 	$dataReader->readAll();
	if(isset($models)) { 
	//print_r($models);
		$product_updae_query='';
		$ostatki_insert_query='';
		for($i=0; $i<count($models); $i++) {
			$product_updae_query.= "UPDATE products SET product_price = '".$models[$i]['price_with_nds']."', number_in_store = ".$models[$i]['store']." WHERE id = ".$models[$i]['product_id'].'; ';
			$ostatki_insert_query.=" INSERT INTO ostatki_trigers (tovar, store, quantity, store_price) VALUES (".$models[$i]['product_id'].", $store_id,  ".$models[$i]['store'].",  '".$models[$i]['price_with_nds']."'  );  ";
		}
		//echo $product_updae_query;
		if(trim($product_updae_query)) {
			$connection=Yii::app()->db;
			$command=$connection->createCommand($product_updae_query)	;
			$dataReader=$command->query(); 
		}
		if(trim($ostatki_insert_query)) {
			//echo $ostatki_insert_query;
			$connection=Yii::app()->db;
			$command=$connection->createCommand($ostatki_insert_query)	;
			$dataReader=$command->query(); 
		}
		
		
	}
	
	
	$plh = Price_list_header::model()->findByPk($pricelist);
	if($step==$parts) {
		$plh->clearpricelist($store_id); //
		@unlink($this->temp_folder.$tempfile);
		echo json_encode('Загрузка новых цен о остатков завершена.  Документ прайс листа очищен, исходный документ xls удален.');
		
	}
		
	}/////public function updatepricelistproducts(){///////////О
	
	public function actionSteptree($id, $store_id, $tempfile) {/////////Интерфейс для просмотра предварительных результатов загрузки прайса
		
		$num_of_rows = Price_list_header::get_positions_num($id);
		//echo $num_of_rows;
		
		$this->render('steptree', array('id'=>$id, 'store_id'=>$store_id, 'num_of_rows'=>$num_of_rows, 'tempfile'=>$tempfile));
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
				$user = Yii::app()->user->id;
				$PL = new Price_list_header ;
				$PL->creation_dt = date("Y-m-d H:i:s");
				$PL->avtor = $user;
				$PL->currency = 2;
				$PL->status=0;
				try {
						$PL->save();
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
			$criteria->order = 't.creation_dt DESC';
			$criteria->condition = " t.creation_dt >= :date_from AND t.creation_dt  <= :date_to";
			$criteria->params=array(':date_from'=>$date_from_sql, ':date_to'=>$date_to_sql);
		}/////////////if (isset($date_from_value) AND isset($date_from_value)isset($date_from_value) ) {
			else $criteria->order = 't.id DESC';
			
		
		$pages=new CPagination(Price_list_header::model()->count($criteria)); 
		$pages->pageSize=self::PAGE_SIZE;
		$pages->applyLimit($criteria);
		
		$models = Price_list_header::model()->with('currencies')->findAll($criteria);
		
		$this->render('list', array('models'=>$models, 'date_to_value'=>$date_to_value, 'date_from_value'=>$date_from_value, 'pages'=>$pages) );
	}
	
	public function actionPricelist() {////////Вывод содержимого прайслиста
		//print_r($_FILES);
		$price_id = Yii::app()->getRequest()->getParam('id', NULL);	
		
		
		//if (isset($_POST['xlsprice'])) {
					if (isset($_FILES)) {//////////Загрузка главной картинки
					//print_r($_FILES);
							$downloaded_file = $_FILES['xlsprice'];
							//print_r($downloaded_file);
							//echo '<br>';
							if (trim($downloaded_file[tmp_name])) {//////////////если файл был передан
							 if ($downloaded_file['type']=='application/vnd.ms-excel' OR $downloaded_file['type']=='application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' ) {
								 error_reporting(E_ALL ^ E_NOTICE);
							 	require_once 'excel_reader2.php';
								$data = new Spreadsheet_Excel_Reader($downloaded_file['tmp_name']);
							 	
								
								$this->NAME = 1;
								$this->PRODUCT = Yii::app()->getRequest()->getParam('prod_col', NULL);/////////Номер столбца с ид товара
								$this->PRICE = Yii::app()->getRequest()->getParam('price_col', 3);/////////////Номер столбца с ценой
								$this->STORE = Yii::app()->getRequest()->getParam('store_col', 9);
								
								$this->ARTICLE = Yii::app()->getRequest()->getParam('article_col', NULL);
								$store_id = Yii::app()->getRequest()->getParam('store_id');
								$do_not_trancate = Yii::app()->getRequest()->getParam('do_not_trancate');
								

								if($this->ARTICLE!=NULL ) {//////////Удаляем старые позиции прайса
										$connection=Yii::app()->db;
										$query = "DELETE FROM price_list_products_list WHERE pricelist_id = ".$price_id;
										$command=$connection->createCommand($query)	;
										$dataReader=$command->query(); /////////////////После таког
										
										if(isset($store_id) AND isset($do_not_trancate)==false ) {//////////////Удаляем всё из тригеров остатков по складу, если не стоит ключ что не удалять
											$query = "DELETE FROM ostatki_trigers WHERE store = ".$store_id;
											$command=$connection->createCommand($query)	;
											$dataReader=$command->query(); ////
										}
										
								}////////////////

								$num_of_rows = $data->rowcount($sheet_index=0) ;
								//echo $num_of_rows;
								$updated_products_count = 0;
								for($i=2; $i<=$num_of_rows; $i++) {
								//\\echo $i.': '.$data->val($i, 1).'<br>';
								//echo $this->PRODUCT;
								$number_in_store = (int)$data->val($i, $this->STORE);
								if($this->PRODUCT!=NULL) $product_id = $data->val($i, $this->PRODUCT);
								if ($product_id>0) {
										//echo $i.': '.$data->val($i, 1).'<br>';	
										//echo $product_id.'<br>';
										$PROD=Products::model()->findByPk($product_id);
										if ($PROD != NULL) {
													$price_with_nds =  $data->val($i, $this->PRICE);
													
													//echo $price_with_nds.'<br>';
													$price_with_nds = str_replace(',', '.', $price_with_nds );
													
													$new_price_row = new Price_list_products_list;
													$new_price_row->product_id = $product_id;
													if ($price_with_nds!=NULL)$new_price_row->price_with_nds =  $price_with_nds;
													$new_price_row->pricelist_id = $price_id;
													try {
																	$new_price_row->save(false);
																	} catch (Exception $e) {
																	 echo 'Caught exception: ',  $e->getMessage(), "\n";
																	}//////////////////////
										}////////////if ($PROD != NULL) {
								} ////////if (isset($data->val($i, PRODUCT)) {
									
								if($this->ARTICLE!=NULL) $product_art = $data->val($i, $this->ARTICLE);	
								if ($product_art!='') {
										//echo $i.': '.$data->val($i, 1).'<br>';	
										//echo $product_id.'<br>';
									$price_with_nds =  $data->val($i, $this->PRICE);
									$price_with_nds = str_replace(',', '.', $price_with_nds );
									
									//if($this->PRICE_MOD!=NULL) {
										//$price_with_nds=round($this->PRICE_MOD*$price_with_nds, 2);	
										/*
										if($price_with_nds<=334) $price_with_nds = round($price_with_nds+100, 0);
										elseif($price_with_nds>334 AND $price_with_nds<=1000) $price_with_nds= round($price_with_nds*1.3, 0);
										elseif($price_with_nds>1000 AND $price_with_nds<=10000)$price_with_nds= round($price_with_nds*1.2, 0);
										else round($price_with_nds= $price_with_nds*1.15, 0);
										*/
										//до 300 накрутка 1,5 от 300 до 500 накрутка 1,3 свыше 500 до 10 000 накрутка 1,2 и свыше 10 тыс 1,1 
										
										if ($store_id==6) {
											////////Игорь, будет следующая накрутка до 5 000 руб 10% с выше 8% . Так же можно ли сделать чтобы с прайса ситилинк выводились товары с выше 500 руб? 
											/*
											if($price_with_nds<=5000) $price_with_nds= round($price_with_nds*1.1, 0);
											else $price_with_nds= round($price_with_nds*1.08, 0);
											*/
											//поменяй, пожалуйста, накрутку на ситилинк она теперь бдет следующая: до 1000 руб 30%, от 1000 до 2000 тыс 20%, свыше 2000 тыс до 5000 15% и все что свыше 10%.
											if($price_with_nds<=1000) $price_with_nds= round($price_with_nds*1.3, 0);
											elseif($price_with_nds>1000 AND $price_with_nds<=2000) $price_with_nds= round($price_with_nds*1.2, 0);
											elseif($price_with_nds>2000 AND $price_with_nds<=5000) $price_with_nds= round($price_with_nds*1.15, 0);
											elseif($price_with_nds>5000) $price_with_nds = round($price_with_nds= $price_with_nds*1.1, 0);
											
										}
										elseif($store_id==7){//////
										////////нам нужно создать склад 1Э  со следующей накруткой до 1000 30%, с 1000 до 3000 25%, с 3000 до 10000 тыс 20%, с 10000 до 15000 10%, с 15000  и выше 7%.
											if($price_with_nds<=1000) $price_with_nds= round($price_with_nds*1.3, 0);
											elseif($price_with_nds>1000 AND $price_with_nds<=3000) $price_with_nds= round($price_with_nds*1.25, 0);
											elseif($price_with_nds>3000 AND $price_with_nds<=10000) $price_with_nds= round($price_with_nds*1.20, 0);
											elseif($price_with_nds>10000 AND $price_with_nds<=15000) $price_with_nds = round($price_with_nds= $price_with_nds*1.1, 0);
											elseif($price_with_nds>15000) $price_with_nds = round($price_with_nds= $price_with_nds*1.07, 0);
										}
										else {
											/////////Необходимо изменить накрутку для бюрократа. До 1000 руб поставить до 1000 руб 30%, от 1000-3000 -25%, от  3000 до 10000 20, от 10000 10%
											
											if($price_with_nds<=1000) $price_with_nds = round($price_with_nds*1.3, 0);
											elseif($price_with_nds>1100 AND $price_with_nds<=3000) $price_with_nds= round($price_with_nds*1.25, 0);
											elseif($price_with_nds>3000 AND $price_with_nds<=10000) $price_with_nds= round($price_with_nds*1.2, 0);
											else round($price_with_nds= $price_with_nds*1.1, 0);
										}
								//	}
									
									$product_art = str_replace('(', '', $product_art);
									$product_art = str_replace("'", '', $product_art);
										
									$prod_update[$product_art] = array('price_with_nds'=>$price_with_nds, 'number_in_store'=>$number_in_store);
									$prod_update_art_list[] = $product_art;
									
									/*
										
										$PROD=Products::model()->findByAttributes(array('product_article'=>$product_art));
										
								
										if ($PROD != NULL) {
													
												
													/////////////Пока убрал сохранение позиций в прайслист
													$new_price_row = new Price_list_products_list;
													$new_price_row->product_id =$PROD->id;
													$new_price_row->store =  $number_in_store;
													if ($price_with_nds!=NULL)$new_price_row->price_with_nds =  $price_with_nds;
													$new_price_row->pricelist_id = $price_id;
													try {
																	$new_price_row->save(false);
																	
																	} catch (Exception $e) {
																	 echo 'Caught exception: ',  $e->getMessage(), "\n";
																	}//////////////////////
													
													
													
														
										}////////////if ($PROD != NULL) {
										*/	
								} ////////if (isset($data->val($i, PRODUCT)) {
									
									
								} /////////for($i=2; $i<=$num_of_rows; $i++) {
								
								
									
						}///////// if ($downloaded_file['tmp_name'][type]='application/vnd.ms-excel') {
							
						}////////if (isset($dowloaded_files['tmp_name'])) {
				}////////////////////////if (isset($_FILES)) {//////////'"
		//}
		unset($data);
		
		if(isset($prod_update_art_list) AND isset($prod_update)) {
			$criteria=new CDbCriteria;
			$criteria->condition = "t.product_article IN ('".implode("' , '" , $prod_update_art_list)."') ";
			$products=Products::model()->findAll($criteria);
			if(isset($products)) {
				$query="";
				foreach($products as $PROD) {
					/*
					if(isset($prod_update[$PROD->product_article])) {
					$PROD->product_price = $prod_update[$PROD->product_article]['price_with_nds'];
					$PROD->number_in_store = $prod_update[$PROD->product_article]['number_in_store'];
						try {
								$PROD->save(0);
								//$message = "";
								$updated_products_count++;
							} catch (Exception $e) {
										 echo 'Caught exception: ',  $e->getMessage(), "\n";
							}//////////////////////
					
					}*/
					$updated_products_count++;
					$query.=" UPDATE products SET  product_price = '".$prod_update[$PROD->product_article]['price_with_nds']."' ,  number_in_store= '".$prod_update[$PROD->product_article]['number_in_store']."'  WHERE id = ".$PROD->id."; " ;
					if(isset($store_id)) $query.=" INSERT INTO ostatki_trigers (tovar, store, quantity, store_price) VALUES (".$PROD->id.", ".$store_id.", '".$prod_update[$PROD->product_article]['number_in_store']."', '".$prod_update[$PROD->product_article]['price_with_nds']."'); ";
					
				}
				
				$connection=Yii::app()->db;
				
				$command=$connection->createCommand($query)	;
				$dataReader=$command->query(); /////////////////После такого обновления, MySQL уходит в оут, и нужно сделать редирект
				$url = Yii::app()->createUrl('adminprices', array('pricelist'=>$price_id));
				$this->redirect($url, true);		
				exit();
			}
		}
		
		
		if (isset($_POST['add_product']) AND trim($_POST['add_product'])) {///////////Добавляем продукт
		
		$new_price_row = new Price_list_products_list;
		$new_price_row->product_id = $_POST['add_product'];
		$new_price_row->pricelist_id = $price_id;
		try {
						$new_price_row->save(false);
						} catch (Exception $e) {
						 echo 'Caught exception: ',  $e->getMessage(), "\n";
						}//////////////////////
		
		}//////////////f (isset($_POST['add_product'])) {///////////Доба
		
		
		if ($_POST['apply'] AND isset($price_id)) {///////проведение
				$pricelist = Price_list_header::model()->findByPk($price_id);		
				$pricelist->status=1;
				try {
						$pricelist->save(false);
						} catch (Exception $e) {
						 echo 'Caught exception: ',  $e->getMessage(), "\n";
						}//////////////////////
		}///////////if ($_POST['apply']) {
		
		if ($_POST['abortapply'] AND isset($price_id)) {///////отмена проведения
				$pricelist = Price_list_header::model()->findByPk($price_id);		
				$pricelist->status=0;
				try {
						$pricelist->save(false);
						} catch (Exception $e) {
						 echo 'Caught exception: ',  $e->getMessage(), "\n";
						}//////////////////////
		}///////////if ($_POST['apply']) {
		
		
		
		
		if ($_POST['savepricelist'] AND isset($price_id) AND count($_POST['products_list_price'])>0) {/////////Сохранение прайс листа
				foreach($_POST['products_list_price'] as $id=>$price) {
						if (!$_POST['del_product'][$id]) {
								$position = Price_list_products_list::model()->findbyPk($id);
								$position->price_with_nds = $price;
								try {
									$position->save(false);
									} catch (Exception $e) {
									 echo 'Caught exception: ',  $e->getMessage(), "\n";
									}//////////////////////
						}//////if (!$_POST['del_product'][$id]) {
						else {
								$position = Price_list_products_list::model()->findbyPk($id);
								if ($position != NULL ) try {
									$position->delete();
									} catch (Exception $e) {
									 echo 'Caught exception: ',  $e->getMessage(), "\n";
									}//////////////////////
						}
				}//////////////foreach($_POST['products_list_price'] as $id=>$price) {
						
		}//////////////////
		
		///////Смотрим склады
		$criteria=new CDbCriteria;
		$criteria->condition = "t.kontragent_id = :cont_id AND t.id NOT IN(1,4)";
		$criteria->params = array(':cont_id'=>Yii::app()->params['self_contragent']);
		$stores = Stores::model()->findAll($criteria);
		if(isset($stores))$stores_list=CHtml::listdata($stores, 'id', 'name');
		
		
		$pricelist = Price_list_header::model()->with('currencies')->findByPk($price_id);
		$criteria=new CDbCriteria;
		$criteria->order = 't.id';
		$criteria->condition = " t.pricelist_id = :price_id";
		$criteria->params=array(':price_id'=>$price_id);
		$models = Price_list_products_list::model()->with('product')->findAll($criteria);
		
		$this->render('pricelist', array('pricelist'=>$pricelist, 'price_id'=>$price_id, 'models'=>$models, 'updated_products_count'=>$updated_products_count, 'prod_update'=>$prod_update, 'stores_list'=>@$stores_list) );	
	}///////////////////////////////////////////////////
	
	
public function actionGetpricelistproducts($id){ //////////Выборка позиций прайслиста id по категории
	//print_r($_POST);
	$cat = Yii::app()->getRequest()->getParam('cat');
	$store_id = Yii::app()->getRequest()->getParam('store_id');
	
	$criteria=new CDbCriteria;
	$criteria->order = 't.id';
	$criteria->condition = " t.pricelist_id = :price_id AND product.category_belong = :cat";
	$criteria->params=array(':price_id'=>$id, ':cat'=>$cat);
	$models = Price_list_products_list::model()->with('product')->findAll($criteria);
	
	//////////Смторим цены и остатки
	 if(empty($models)==false) for($i=0; $i<count($models); $i++) $prids[]=$models[$i]->product_id;
	if(isset($prids)) {
			//print_r($prids);
			//echo $store_id;
			$criteria=new CDbCriteria;
			$criteria->condition = "t.tovar IN (".implode(',', $prids).") AND store = :store_id ";
			$criteria->params=array(':store_id'=>$store_id);
			$criteria->order = 't.tovar';
			$ostatki_tmp = Ostatki_trigers::model()->findAll($criteria);
			
			
			//print_r(count($ostatki_tmp));
			if(isset($ostatki_tmp)) for($i=0; $i<count($ostatki_tmp); $i++) {
					
					//echo $ostatki_tmp[$i]->tovar.'<br>';
					$ostatki[$ostatki_tmp[$i]->tovar]=$ostatki_tmp[$i];
				}

			//print_r(count($ostatki));
	}
	
	
	$this->renderPartial('priceajaxproducts', array('models'=>@$models, 'ostatki'=>@$ostatki));
}//////////public function actionGe
	
	public function actionUpdateposition(){ /////////Обновление единичной позиции прайслиста
		$id = Yii::app()->getRequest()->getParam('id');
		$price = Yii::app()->getRequest()->getParam('price');
		
		if(isset($id) AND isset($price) AND is_numeric($price) AND is_numeric($id)) {
			$pricepos = Price_list_products_list::model()->findByPk($id);
			if(isset($pricepos)) {
				$pricepos->price_with_nds = $price;
				$pricepos->save();
			}
			
		}
	
	}/////////public function actionUpdateposition(){ /////////О
	
	
	public function actionUpdateproductprice(){ /////////Ajax oбновление товара
	
		//print_r($_POST);
		$checked = Yii::app()->getRequest()->getParam('checked');	
		$id = Yii::app()->getRequest()->getParam('id');
		$price = Yii::app()->getRequest()->getParam('price');
		$pricerrp = Yii::app()->getRequest()->getParam('pricerrp');
		$sellout_price = Yii::app()->getRequest()->getParam('sellout_price');
		$product_price_no_auto_update = Yii::app()->getRequest()->getParam('product_price_no_auto_update');
		
		if(isset($id)  AND is_numeric($id))  $PROD = Products::model()->findByPk($id);
		
		if(isset($PROD) AND isset($price) AND is_numeric($price)) $PROD->product_price = $price;
		if(isset($PROD) AND isset($checked)) $PROD->product_visible = $checked;
		if(isset($PROD) AND isset($pricerrp)) $PROD->product_price_recomended = $pricerrp;
		if(isset($PROD) AND isset($sellout_price)){
			 $PROD->sellout_price = $sellout_price;
			if( $sellout_price>0 ) $PROD->product_sellout = 1;
			else $PROD->product_sellout = 0;
		}

		if(isset($PROD) AND isset($product_price_no_auto_update)) $PROD->product_price_no_auto_update = $product_price_no_auto_update;
		
		if(isset($PROD)) $PROD->save();
		
		
	}/////////public function actionUpdateproductprice(){ /////////Ajax oбновлен товара
	
	
	public function get_tree_children($root, $id, $unic_cats ){
		
			//$plh = Price_list_header::model()->findByPk($id);
			//$unic_cats = $plh->getPriceProductsGroups();	
	
			$criteria=new CDbCriteria;
			$criteria->order = 't.sort_category, child_categories.sort_category';
			$criteria->condition = " t.parent = ".$root;
			if(isset($unic_cats) AND empty($unic_cats)==false) {
				//echo 'werwerwer';
				$criteria->condition.=" AND t.category_id IN (".implode(',',$unic_cats).")";
			}
			$criteria->addCondition("t.show_category = 1");
			
			
			$tree = Categories::model()->with('child_categories')->findAll($criteria);
			
			
			$items = array();
			$i = 0;
			foreach($tree as $item)
			{
				//$link_text = iconv("UTF-8", "CP1251", $item->category_name);
				$link_text = $item->category_name;
				//$id = $item->category_id;
				$items[$i] = ((count($item->child_categories)>0 ) ?$items[$i] = array('id'=>$item->category_id, 'text'=>$link_text  , 'expanded' => true, 'children'=>$this->get_tree_children($item->category_id, $id, $unic_cats)   )  : array('id'=>$item->category_id,  'text'=>CHtml::link($link_text, '#', array('class'=>'lastchild','onClick'=>"{showpriceproducts(".$item->category_id.")}") ) ) );
				$items[$i]['hasChildren'] = ((count($item->child_categories)>0 ) ? TRUE : FALSE);
					
				$i++;
			}
			
			return $items;
			
			//return array();
			
		
		
	}/////////private function get_tree_children($root, $id){
	
	
	
}
