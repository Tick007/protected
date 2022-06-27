<?php

//////////////для тестирования JWT/REST. Не грузить с этим не сервер
//https://ru.wikipedia.org/wiki/JSON_Web_Token
//https://jwt.io/ проверка токенов
///https://css-tricks.com/creating-vue-js-component-instances-programmatically/ - програмная вставка компонентов VUE
////https://www.w3schools.com/howto/howto_js_draggable.asp  - draggable
/*
include ('d:\YandexDisk\wwroot\smotr\protected\components\ContProtocol.php'); // /////////Протокол контроллера
include ('d:\YandexDisk\wwroot\smotr\protected\components\websocketclient.php'); // /////////Протокол контроллера
include('C:\Users\Igor\vendor\firebase\php-jwt\src\JWT.php');
include('C:\Users\Igor\vendor\firebase\php-jwt\src\SignatureInvalidException.php');
use \Firebase\JWT\JWT;
*/
class NomenklaturaController extends Controller //////////////Главный контроллер администрирования товаров
{
	const PAGE_SIZE=30;
	var $product;

	/**
	 * @var string specifies the default action to be 'list'.
	 */
	public $defaultAction='index';

	/**
	 * @var CActiveRecord the currently loaded data model instance.
	 */
	private $_model;


	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations 
		);
	}

	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'list' and 'show' actions
				'actions'=>array('index', 'cat', 'searchgoodscheck', 'yml', 'getcities', 'getdelivery',
				    'getcitiesautocompl', 'getcitiesautocompl2', 'getgroupe', 'getgroupes', 'getjwt', 'jwtusers',
				    'smotrantenna', 'smotrantennaupdate', 'smotrconverterupdate', 'jwtorders', 'smotrmatrixupdate'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('pricelist', 'list', 'contragents', 'kontragent','getgroupsoptions' , 'updatekagent', 'searchgoods',
						'ajaxupload',  'searchchars', 'getcatchilds', 'indexgr', 'catcompatiblecat', 'getpricelistproducts', 'getregioncities',
				    'saveregprodprice', 'saveregprodlimits'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
			    'actions'=>array('admin','delete', 'addpricevar'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
	
	public function init() {
        Yii::app()->layout = "nomenu";
    }
	
	public function get_productiya_path($parent){
				///////////////////////////////Вычисляем путь по дереву продукции
				$Path = Categoriestradex::model()->findbyPk($parent);
				$parent_id = $Path->parent;
				/////////Yii::app()->->drupal_vars['taxonomy_catalog_level'] - нулевой уровень каталога
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


	public function actionYml(){////////////Вывод прайс листа для яндекса

/*
		$not_allowed_directories="74,186,187,188,189,190,485,486,487,488,489,490,179,180,181,182,183,184,185,362,363,364,365,366,367,368,369,370,371,372,373,374,375,376,377,378,379,380,381,382,383,384,191,192,193,194,195,196,197,385,386,387,388,389,390,391,144,145,146,147,148,149,150,151,152,153,356,357,358,359,360,361,392,296,396,397,398,399,400,401,402,403,404,405,406,407,408,409,410,411,412,413,414,415,416,417,219,220,221,222,223,224,225,226,227,228,229,230,231,232,233,505,2024,2022,2027,2030,2079,2025,2031,2032,2026,2023,2029,2028,208,209,210,211,212,213,214,215,216,217,218,234,235,236,237,238,239,240,241,242,243,244,245,246,247,248,249,250,251,252,253,254,154,155,156,157,158,159,160,161,162,163,164,339,340,341,342,343,344,345,346,347,450,451,452,453,454,455,456,457,458,459,460,461,462,463,464,465,466,467,468,469,470,471,418,419,420,421,422,423,424,425,426,427,428,429,430,255,256,354,355,478,479,480,481,482,483,484,297,298,299,300,301,302,303,304,305,306,307,308,309,310,311,257,258,497,498,499,500,257,258,259,260,261,262,263,264,265,266,267,268,269,270,271,272,273,274,275,276,277,278,279,280,281,282,283,284,285,286,287,288,289,290,291,292,293,294,295,296,297,431,432,433,434,435,436,437,438,439,440,441,442,443,444,445,446,447,448,449,312,313,314,319,320,321,322,323,324,315,316,317,318,494,495,496,325,326,327,328,329,330,331,332,333,334,335,336,337,338,472,473,474,475,476,477,501,502,503, 2277,2276,2266,2260,2256,2251,2251,2278,2273,2279,2261,2263,2257,2249,2252,2250,2255,2248,2254,2271,2258,2270,2268,2269,2272,2262,2275,2274,2264,2259,2267,2265,2253 ";
		*/
		
		
				$criteria=new CDbCriteria;
		$criteria->condition = " picture_product.is_main=1";
		$picture_models = Pictures::model()->with('picture_product')->findAll($criteria);
		if (isset($picture_models)) {
			for($i=0; $i<count($picture_models);$i++) $pictures_list[$picture_models[$i]->picture_product[0]->product]=array('pict_id'=>$picture_models[$i]->id, 'ext'=>$picture_models[$i]->ext);
			unset($picture_models);
		}
		
		
		$criteria=new CDbCriteria;
		//$criteria->select=array( 't.*',  'picture_product.picture AS icon' , 'picture_product.ext AS ext');
		//$criteria->condition = " t.product_visible = 1 AND  product_price>300 AND number_in_store>0";
		//$criteria->condition = "  t.product_visible = 1  AND  product_price>0  AND number_in_store > 0 ";
		if(isset(Yii::app()->params['yml_visible_condition']['products'])){
		    $criteria->condition = Yii::app()->params['yml_visible_condition']['products'];
		}
		else $criteria->condition = "  t.product_visible = 1  AND  product_price>0  AND number_in_store > 0 ";
		if(isset($not_allowed_directories)==true AND empty($not_allowed_directories)==false) $criteria->addCondition("t.category_belong NOT IN (".$not_allowed_directories.")");
	/*	$criteria->join ="
			LEFT JOIN ( SELECT product, picture, pictures.ext as ext FROM picture_product  JOIN pictures ON pictures.id= picture_product.picture  WHERE is_main=1 ) picture_product ON picture_product.product = t.id  "; 
			*/
		$products=Products::model()->with('belong_category')->findAll($criteria);
		

		
		
		$criteria=new CDbCriteria;
		$criteria->condition=" t.alias  IS NOT NULL AND t.alias <>'' AND t.path IS NOT NULL AND TRIM(t.path) <>'' AND t.show_category = 1  
        AND t.category_name<>'' ";
		if(isset(Yii::app()->params['yml_visible_condition']['categories'])) {
		    $criteria->condition.=Yii::app()->params['yml_visible_condition']['categories'];
		}
			if(isset($not_allowed_directories)==true AND empty($not_allowed_directories)==false)  $criteria->addCondition("t.category_id NOT IN (".$not_allowed_directories.")");
		$criteria->order="t.category_id";
		$categories = Categories::model()->findAll($criteria);
		$groups =CHtml::listData($categories,'category_id','alias');
		
		 $uri=Yii::app()->request->url;
		//echo $uri;
		//print_r($_GET);
		//exit();	

		//$ = array('products'=>@$products,  'groups'=>@$groups, 'categories'=>@$categories);
		//if(isset($pictures_list)) $['pictures_list'] = $pictures_list;
		

		if($uri == '/pricelist/mail.xml') $this->renderPartial('yandex/torgmail', array('products'=>@$products,  'groups'=>@$groups, 'categories'=>@$categories, 'pictures_list'=>$pictures_list) );
		else $this->renderPartial('yandex/yml1',  array('products'=>@$products,  'groups'=>@$groups, 'categories'=>@$categories, 'pictures_list'=>$pictures_list));
		
	}////////public function actionYml(){///////////

	public function actionIndex() {//////////
			$targetitem=Yii::app()->getRequest()->getParam('targetitem', NULL);	
			$targetform=Yii::app()->getRequest()->getParam('targetform', NULL);	
		
			$this->render('nomenklatura', array( 'targetform'=>$targetform, 'targetitem'=>$targetitem));
	}//////////public function actionIndex() {/////////
	
	public function actionContragents(){/////////////Вывод дерева контрагентов
			$targetitem=Yii::app()->getRequest()->getParam('targetitem', NULL);	
			$targetform=Yii::app()->getRequest()->getParam('targetform', NULL);	
			$group = 	Yii::app()->getRequest()->getParam('id', NULL);	
			
			
			
			//echo $group;
			
			if (is_numeric($group)==true) {
			
					if (isset($_POST[create_ca])) {////////////////////создание
							$newca = new Contr_agents;
							$newca->name=time();
							$newca->groupe = $group;
							try {
											$newca->save();
											} catch (Exception $e) {
											 echo 'Ошибка создание нового контагента. ',  $e->getMessage(), "\n";
											}/////
							if (isset($newca->id)) {/////////////добавляем склад
									$newstore = new Stores;
									$newstore->name = 'New store';
									$newstore->kontragent_id = $newca->id;
									try {
											$newstore->save();
											} catch (Exception $e) {
											 echo 'Ошибка создание склада контагента. ',  $e->getMessage(), "\n";
											}/////
							}///////////////////////////if (isset($newca->id)) {/////////////добавля
					}////////////////	if (isset($_POST[create_ca])) {////////////////////создание
			
					$criteria=new CDbCriteria;
					$criteria->order = 't.name';
					$criteria->condition = " t.groupe = :parent";
					$criteria->params= array(':parent'=>$group);
					$models= Contr_agents::model()->findAll($criteria);
			}///////////if (is_numeric($group)==true) {
			
			$render_params= array('targetform'=>$targetform, 'targetitem'=>$targetitem);
			if(isset($models) && $models!=null) $render_params['models']=$models;
			
			$this->render('contragents', $render_params);
	} ///////////public function actionContragents(){/////////////Вывод
	
	
	
	public function actionCat() {
		
			//print_r($_GET);
		
			$cat_id = Yii::app()->getRequest()->getParam('id', NULL);	
			$layout = Yii::app()->getRequest()->getParam('layout');	
			$targetitem=Yii::app()->getRequest()->getParam('targetitem', NULL);	
			$targetform=Yii::app()->getRequest()->getParam('targetform', NULL);	
			//echo $cat_id;
			//exit();
			
			$criteria=new CDbCriteria;
			$criteria->order = 't.product_name';
			$criteria->condition = " t.category_belong = ".$cat_id;
			//$criteria->params=array(':price_id'=>$price_id);
			$models= Products::model()->findAll($criteria);
			
			if(isset($layout) AND $layout=='admin') $this->layout='admin';
			
			$this->render('nomenklatura' , array('models'=>$models, 'targetform'=>$targetform, 'targetitem'=>$targetitem));
	}

	public function actionIndexgr() {/////////////////////Эта функция для вытаскивания групп
			$this->layout = "nomenu";

			//$clientScript=Yii::app()->clientScript;
			//$clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/highslide/highslide-with-html.js', CClientScript::POS_HEAD);
			
			$targetitem=Yii::app()->getRequest()->getParam('targetitem', NULL);	
			$targetform=Yii::app()->getRequest()->getParam('targetform', NULL);	
			$root=Yii::app()->getRequest()->getParam('root', NULL);	
			
			
			$criteria=new CDbCriteria;
			$criteria->order = 't.sort_category';
			$criteria->condition = " t.parent = 0 AND t.show_category =1 ";
			//$criteria->params=array(':price_id'=>$price_id);
			$models= Categories::model()->with('products')->findAll($criteria);
			
		
			$this->render('nomenklaturagrnew', array( 'targetform'=>$targetform, 'targetitem'=>$targetitem, 'models'=>$models ,'root'=>$root));
	}//////////public function actionIndex() {/////////


	public function actionKontragent(){////////////////Вывод формы контрагента
			$cat_id = Yii::app()->getRequest()->getParam('id', NULL);	
			
			$contragent = Contr_agents::model()->with('stores')->findByPk($cat_id);
			
			$criteria=new CDbCriteria;
			$criteria->condition = "t.parent = 0 ";
			$s_d = Contr_agents_groups::model()->with('child_categories')->findAll($criteria);
			$section_data[0]='...выбор отдела';
			for($i=0; $i<count($s_d); $i++) {
						$section_data[$s_d[$i]->group_id]=$s_d[$i]->group_name;		
						if (count($s_d[$i]->child_categories)) {
								$subotdel =  $s_d[$i]->child_categories;
								for($k=0; $k<count($subotdel); $k++) $section_data[$subotdel[$k]->group_id]='---'.$subotdel[$k]->group_name;	
						}
			}
			
			
			$this->render('kontragent_form', array('contragent'=>$contragent, 'contr_agents_groups'=>$section_data));
	}//////////////public function actionКontragent(){////////////////Вывод формы контрагента

	public function actionUpdatekagent(){//////////Сохранение контрагента
	$cat_id = Yii::app()->getRequest()->getParam('id', NULL);	
			//print_r($_POST);
	if ($cat_id>0) $contragent = Contr_agents::model()->with('stores')->findByPk($cat_id);
	if (@$contragent != NULL)  {//////////
			//$contragent->attributes=$_POST[parametrs];
			try {
						$contragent->saveAttributes($_POST[parametrs]);
						} catch (Exception $e) {
						 echo 'Caught exception: ',  $e->getMessage(), "\n";
						}///////////////////
			
			if (@$_POST[add_store]) {////////////Добавляем склад
					$new_store = new Stores;
					$new_store->name='Новый склад';
					$new_store->kontragent_id  =$cat_id ;
					try {
										$new_store->save();
										} catch (Exception $e) {
										 echo 'Caught exception: ',  $e->getMessage(), "\n";
										}//////////////////
			}////////////////////////if ($_POST[parametrs][add_store]) {////////////Добавляем скл
		
			//print_r($_POST[stores]);	
			if (count($_POST[stores][name])>0) {////////////Перебор складов
					//$name=$_POST[stores][name];
					//$store_adress = $_POST[stores][store_adress];
					//$show_in_html = $_POST[stores][show_in_html];
					//$delete_stores = $_POST[delete_stores];
					
					foreach($_POST[stores][name] as $store_id=>$store_name):
							//echo $store_id;
							$STORE = Stores::model()->findByPk($store_id);
							if (@$STORE != NULL AND !@$_POST[delete_stores][$store_id]) {///////////
									$STORE->name = $_POST[stores][name][$store_id];
									$STORE->store_adress= $_POST[stores][store_adress][$store_id];
									if (@$_POST[stores][show_in_html][$store_id])  $visib=1;
									else $visib=0;
									$STORE->show_in_html=$visib;
									try {
										$STORE->save();
										} catch (Exception $e) {
										 echo 'Caught exception: ',  $e->getMessage(), "\n";
										}///////////////////
							}/////////////if (@$STORE != NULL) {///////////
							else if( @$STORE != NULL AND @$_POST[delete_stores][$store_id]) {///////////////////Удаляем склад
									/////////////////////////Сначала смотрим если движения по складу
										$criteria=new CDbCriteria;
										$criteria->condition = " t.store_id = :store_id OR  t.store_id_ca=:store_id";
										$criteria->params= array(':store_id'=>$store_id);
										$SERIES=Series::model()->count($criteria);

										$criteria=new CDbCriteria;
										$criteria->condition = " t.store_id = :store_id OR  t.store_id_ca=:store_id";
										$criteria->params= array(':store_id'=>$store_id);
										$SERIES_MOVEMENT=Series_movement::model()->count($criteria);
										
										//echo "series = ".$SERIES.'<br>';
										//echo "series_movement = ".$SERIES_MOVEMENT.'<br>';
										//echo '<br>';
										//print_r($SERIES[0]->id);
									/////////////////////////
									$s=(int)$SERIES+(int)$SERIES_MOVEMENT;
									//echo $s.'<br>';
									//var_dump ($s);
									if (  $s == 0  OR $s=='' OR $s==NULL) try {
										$STORE->delete();
										} catch (Exception $e) {
										 echo 'Caught exception: ',  $e->getMessage(), "\n";
										}///////////////////
									elseif (  $s >0) {
										$msg= 'По складу '.$store_name.' есть движения<br>'.CHtml::link('Назад',Yii::app()->request->baseUrl."/nomenklatura/kontragent/$cat_id", $htmlOptions=array ('encode'=>false));
										$this->renderPartial('error', array('msg'=>$msg));
										exit();
										 }
									
							}///////////////////else if( @$STORE != NULL AND @$_POST[delete_stores][$store_id]) {

					endforeach;
			}	//////////////////////////////////
		
	}/////////////////	if (@$contragent != NULL)  {//////////
			$this->redirect(Yii::app()->request->baseUrl."/nomenklatura/kontragent/$cat_id", true, 301);
	}///////////////////public function actionUpdatekagent(){//////////Сохранение контрагента

	public function actionSearchgoodscheck(){///////Функция только возвращает true если найдено
		
		$krepltype  = Yii::app()->getRequest()->getParam('krepltype');
		$locktype = Yii::app()->getRequest()->getParam('locktype');
		
		$searchgoods = Yii::app()->getRequest()->getParam('search', NULL);	
		if (strlen($searchgoods)>2) {
			//echo $searchgoods;
			//$role_select = Yii::app()->getRequest()->getParam('role_select', 0);
			$criteria=new CDbCriteria;
			if(isset($krepltype) AND $krepltype=='kpp') {
				$criteria->condition = "t.product_name LIKE :searchgoods OR t.product_name LIKE :searchgoods_mtl  "; 
				$criteria->params=array(':searchgoods'=>$searchgoods.'%', ':searchgoods_mtl'=>'MTL '.$searchgoods.'%');
			}
			elseif(isset($krepltype) AND $krepltype=='val') {
				$criteria->condition = "t.product_name LIKE :searchgoods  OR  t.product_name LIKE :searchgoods_csl "; 
				$criteria->params=array(':searchgoods'=>$searchgoods.'%',  ':searchgoods_csl'=>'CSL '.$searchgoods.'%');
			}
			$good = Products::model()->with('belong_category')->find($criteria);
			if(isset($good)) echo 1;
			else echo 0;
		}
		
	}///////public function actionSearchgoodscheck(){///////

	public function actionSearchgoods(){////////////Аджаксовый поиск товаров
			//print_r($_GET);
			//print_r($_POST);
			$searchgoods = Yii::app()->getRequest()->getParam('search_item');	
			$id = Yii::app()->getRequest()->getParam('id');	
			$sortfield = Yii::app()->getRequest()->getParam('sortfield');	
			//$searchgoods =  iconv( "UTF-8", "CP1251", trim(htmlspecialchars($searchgoods)));
			if (isset($searchgoods) OR isset($id) ) {
				
				if (isset($searchgoods) AND strlen($searchgoods)>4) {
					//echo $searchgoods;
					//$role_select = Yii::app()->getRequest()->getParam('role_select', 0);
					$criteria=new CDbCriteria;
					$criteria->condition = "t.product_name LIKE :searchgoods OR t.product_article LIKE :searchgoods";
					//$criteria->params=array(':searchgoods'=>'%'.$searchgoods.'%', ':searchcat'=>$searchgoods.'%' );
					$criteria->params=array(':searchgoods'=>'%'.$searchgoods.'%' );
					if(isset($sortfield)) {
						if($sortfield == 'price') $criteria->order = "t.product_price";
						if($sortfield == 'pname') $criteria->order = "t.product_name";
					}
					else $criteria->order = "t.id";
					//$models = Products::model()->with('belong_category')->findAll($criteria);
					$models = Products::model()->findAll($criteria);
				}
				elseif(isset($id) AND is_numeric($id)==true){
					$criteria=new CDbCriteria;
					$criteria->condition = "t.category_belong = :category_belong";
					$criteria->params=array(':category_belong'=>$id);
					if(isset($sortfield)) {
						if($sortfield == 'price') $criteria->order = "t.product_price";
						if($sortfield == 'pname') $criteria->order = "t.product_name";
					}
					else $criteria->order = "t.id";
					$models = Products::model()->findAll($criteria);
				}


				
				
				if(isset($models)) {
					$models_ids=CHtml::listdata($models, 'id', 'id');
					
					//////Склады:
					$criteria=new CDbCriteria;
					$criteria->condition="t.kontragent_id  =  1 ";
					$criteria->order="t.id";
					$stores = Stores::model()->findAll($criteria);
					
					if(isset($models_ids) AND empty($models_ids)==false) {
						////Остатки по триггерам
						$criteria=new CDbCriteria;
						$criteria->condition="t.tovar IN (".implode(',', array_keys($models_ids)).")";
						$ostatki_models = Ostatki_trigers::model()->findAll($criteria);
						/////////Прогоняем
						if(isset($ostatki_models)) for($i=0; $i<count($ostatki_models); $i++) {
							$ostatki[$ostatki_models[$i]->tovar][$ostatki_models[$i]->store]=array('quantity'=>$ostatki_models[$i]->quantity, 'store_price'=>$ostatki_models[$i]->store_price);
						}
					}
					
					//print_r($models);
					
					$this->layout="empty";
					$params=array('models'=>$models, 'stores'=>$stores);
					if(isset($ostatki)) $params['ostatki']=$ostatki;
					if(isset($id)) $params['id']=$id;
					$this->render('searchgoods', $params);
					
					
				}
				else echo '<tr bgcolor=\"FFFFFF\"><td colspan=\"6\">Ничего не найденно</td></tr>';
			}//////////if ($searchgoods!='') {
			//else  echo iconv("CP1251", "UTF-8", "<tr bgcolor=\"FFFFFF\"><td colspan=\"6\">Поисковое выражение должно быть от 3 символов</td></tr>");
			else  echo "<tr bgcolor=\"FFFFFF\"><td colspan=\"6\">Поисковое выражение должно быть от 5 символов</td></tr>";
			
			
			
			
	}////////////////	public function actionSearchgoods(){////////////Аджаксовый поиск товаров
		
		
			public function actionAjaxupload(){//////////////Загрузка фоток из карточки товара
				
				$id =Yii::app()->getRequest()->getParam('id', NULL);
				$this->product=Products::model()->findByPk($id);
				
				//print_r($_FILES);
				//exit();
				
				
				
	
				if (isset($_FILES)) {//////////Загрузка картинки
		//print_r($_FILES);
				if (isset($_FILES['addfileimg'])) {
							$downloaded_file = $_FILES['addfileimg'];
							if (trim($downloaded_file['tmp_name'])) {//////////////если файл был передан
							
							$size = @getimagesize($downloaded_file['tmp_name']);
							 if ($size[2] == "1"){$extension = "gif";} 
							 if ($size[2] == "2"){$extension = "jpg";} 
							 if ($size[2] == "3"){$extension = "png";} 
							
							
							//print_r($size);
							//exit();
							 if (isset($extension)) {
							
							$NEW_PICT = new Pictures;
							$NEW_PICT->type=1;
							try {
									$NEW_PICT->save();
							} catch (Exception $e) {
									 echo 'Caught exception: ',  $e->getMessage(), "\n";
							}//////////////////////
							
							$new_file_name = $_SERVER['DOCUMENT_ROOT'].Yii::app()->request->baseUrl.'/pictures/temp/'.$NEW_PICT->id.'.tmp';
							//$new_file_name2 = $_SERVER['DOCUMENT_ROOT'].Yii::app()->request->baseUrl.'/pictures/add/'.$NEW_PICT->id.".";
							//echo  $new_file_name;
							@unlink($new_file_name);
							try {
							    move_uploaded_file($downloaded_file['tmp_name'], $new_file_name);
							} catch (Exception $e) {
							    print_r($e);
							    exit();
							}
							sleep(2);

							
							//print_r($size);
							//exit();
							if ($size[0]>5000 OR $size[0]>5000) {///////////////Принудительно уменьшаем до тысячи
									
									$srctfile =  '/pictures/temp/'.$NEW_PICT->id.'.tmp';
									//echo $srctfile.'<br>';
									//echo $_SERVER['DOCUMENT_ROOT'].$srctfile.'<br>';
									if (file_exists($_SERVER['DOCUMENT_ROOT'].$srctfile)) {
											if($size[0]>=$size[1]) $resize_to = "width=2000";
											elseif($size[1]>$size[0])$resize_to = "height=2000";
											$outfldr = Yii::app()->request->baseUrl."/pictures/add/";
											$iconfile =  Yii::app()->request->baseUrl.'/pictures/add/'.$NEW_PICT->id.'.'.$extension;	
											//echo 	$iconfile.'<br>';
											@unlink($_SERVER['DOCUMENT_ROOT'].$iconfile);
											//if($_SERVER['HTTP_HOST']=='uslugi.reghelp.ru') $f=fopen("http://1:2@".$_SERVER['HTTP_HOST'].Yii::app()->request->baseUrl."/pictures/make_mini.php?create=1&$resize_to&imgname=$srctfile&outfldr=$outfldr&force_file_name=".$NEW_PICT->id.'.'.$extension."&create_type=jpg", 'r');/////Создаем таким образом миниатюру
											//else
											//$url = "http://".Yii::app()->['apache_auth'].$_SERVER['HTTP_HOST'].Yii::app()->request->baseUrl."/pictures/make_mini.php?create=1&$resize_to&imgname=$srctfile&outfldr=$outfldr&force_file_name=".$NEW_PICT->id.'.'.$extension."&create_type=jpg";
											$url = "http://".$_SERVER['HTTP_HOST'].Yii::app()->request->baseUrl."/pictures/make_mini.php?create=1&$resize_to&imgname=$srctfile&outfldr=$outfldr&force_file_name=".$NEW_PICT->id.'.'.$extension."&create_type=jpg";
											echo $url;
											//ini_set('display_errors', 1);
											//error_reporting(E_ALL);
											
											
											$f=fopen($url, 'r', false);/////Создаем таким образом миниатюру
											// sleep(1)
											//echo $url;		
											fclose($f);
											$new_file_name2 = $_SERVER['DOCUMENT_ROOT'].$iconfile;
							}/////////if (file_exists($_SERVER['DOCUMENT_ROOT'].'/'.$srctfile)) {
							
							
							//exit();
							
							}/////////	if ($size[0]>1000 OR $size[0]>1000) {///////////////Принудительно уменьшаем до тысячи
							//exit();
							else {			
							$new_file_name2 = $_SERVER['DOCUMENT_ROOT'].Yii::app()->request->baseUrl.'/pictures/add/'.$NEW_PICT->id.".".$extension;			
							
					
							
					
							copy ($new_file_name, $new_file_name2);
							$size = getimagesize($new_file_name);
							
							//print_r($size);
							//exit();
							
							}
							@unlink ($new_file_name);
							
					
							
							 
										$NEW_PICT->ext=$extension;
										try {
												$NEW_PICT->save();
										} catch (Exception $e) {
												 echo 'Caught exception: ',  $e->getMessage(), "\n";
										}//////////////////////	
										
										if (file_exists($new_file_name2)) {///////////Если существующий файл существует
													//@unlink ($new_file_name2.$extension);
													//rename ($new_file_name, $new_file_name2.$extension);
													
													//echo $new_file_name2.$extension;
													
													////////////Считаем сколько по данному товару уже фоток, и если ноль, то проставляем для
													////////загруженной фотки признак главной
													$num_of_pict = Picture_product::model()->countByAttributes(array('product'=>$this->product->id, 'is_main'=>1));
											
													$PICT_PROD = new Picture_product;
													$PICT_PROD->product = $this->product->id;
													$PICT_PROD->picture = $NEW_PICT->id;
													if (isset($num_of_pict)==false OR $num_of_pict==0) $PICT_PROD->is_main=1;
													try {
															$PICT_PROD->save();
													} catch (Exception $e) {
															 echo 'Caught exception: ',  $e->getMessage(), "\n";
													}///////
												
													////////Создаем миниатюры
													$srctfile =  Yii::app()->request->baseUrl.'/pictures/add/'.$NEW_PICT->id.".".$extension;			
													$key = $NEW_PICT->id;
													//$resize_to = "height=150";
													if(isset(Yii::app()->params['icons_width']) && isset(Yii::app()->params['icons_width']['big'])) $resize_to = "width=".Yii::app()->params['icons_width']['big'];
													else $resize_to = "width=500";
													$outfldr = Yii::app()->request->baseUrl."/pictures/add/icons";
													$iconfile =  Yii::app()->request->baseUrl.'/pictures/add/icons/'.$key.'.png';		
													@unlink($_SERVER['DOCUMENT_ROOT'].Yii::app()->request->baseUrl.'/'.$iconfile);
													
													 $f=fopen("http://".Yii::app()->params['apache_auth'].$_SERVER['HTTP_HOST'].Yii::app()->request->baseUrl."/pictures/make_mini.php?create=1&$resize_to&imgname=$srctfile&outfldr=$outfldr", 'br');/////Создаем таким образом миниатюру
													if (!$f) echo "http://".$_SERVER['HTTP_HOST'].Yii::app()->request->baseUrl."/pictures/make_mini.php?create=1&$resize_to&imgname=$srctfile&outfldr=$outfldr<br>";		
													fclose($f);
													
													/////////////////И делаем маленькую иконку
													
													if(isset(Yii::app()->params['icons_width']) && isset(Yii::app()->params['icons_width']['small'])) $resize_to = "width=".Yii::app()->params['icons_width']['small'];
													$resize_to = "width=200";
													$outfldr = Yii::app()->request->baseUrl."/pictures/add/icons_small";
													$iconfile =  Yii::app()->request->baseUrl.'/pictures/add/icons_small/'.$key.'.png';		
													@unlink($_SERVER['DOCUMENT_ROOT'].Yii::app()->request->baseUrl.'/'.$iconfile);
													//if($_SERVER['HTTP_HOST']=='uslugi.reghelp.ru') $f=fopen("http://1:2@".$_SERVER['HTTP_HOST'].Yii::app()->request->baseUrl."/pictures/make_mini.php?create=1&$resize_to&imgname=$srctfile&outfldr=$outfldr", 'r');/////Создаем таким образом миниатюру
													//else
													 $f=fopen("http://".Yii::app()->params['apache_auth'].$_SERVER['HTTP_HOST'].Yii::app()->request->baseUrl."/pictures/make_mini.php?create=1&$resize_to&imgname=$srctfile&outfldr=$outfldr", 'br');/////Создаем таким образом миниатюру
													
														if (!$f) echo "http://".$_SERVER['HTTP_HOST'].Yii::app()->request->baseUrl."/pictures/make_mini.php?create=1&$resize_to&imgname=$srctfile&outfldr=$outfldr";		
													fclose($f);
											/*		
											$msg = "<tr>
		<td align=\"left\" valign=\"top\">".$key."</td>";
		$msg.="<td align=\"center\" valign=\"top\"><input value=\"".$key."\" type=\"radio\" name=\"main_icon\" id=\"main_icon\">		</td><td align=\"center\" valign=\"top\">".$NEW_PICT->ext;
		$msg.="</td><td align=\"center\" valign=\"top\"><textarea rows=\"3\" cols=\"30\" style=\"font-family:Tahoma\" name=\"comments[".$key."]\" id=\"comments_".$key."\"></textarea>		</td>";
		$msg.="<td align=\"center\" valign=\"top\"><img src=\"/pictures/add/icons/".$key.".png\" style=\"max-width:150px\"></td>
		<td align=\"center\" valign=\"top\"><input type=\"checkbox\" value=\"0\" name=\"delete_icon[".$key."]\" id=\"delete_icon_".$key."\">	&nbsp;	</td>";
		$msg.="<td align=\"center\" valign=\"top\"><input type=\"checkbox\" value=\"0\" name=\"create_icon[".$key."]\" id=\"create_icon_".$key."\">&nbsp;</td></tr>";
		*/
	
											//echo json_encode($msg);
											//echo $msg;
											//echo "<span><tr><td colspan=\"7\">werrweewr</td></tr></span>";		
											echo $key.'#'.$NEW_PICT->ext.'#'.$PICT_PROD->id;
											}/////if (file_exists($new_file_name2)) {///////////Если существующий фа
								   }/////// if (isset($extension)) {
								   else {/////////Если тип файла не  совпадает с одним из трех
												@unlink($new_file_name);
										}/////////////////// else {/////////Если тип файла не  сов
										
												
						}////////if (isset($dowloaded_files['tmp_name'])) {
			}/////////////if (isset($_FILES['addfileimg'])) {
	}////////////////////////if (isset($_FILES)) {//////////
	}/////public function actionAjaxupload(){//////////////Загрузка фоток из карточки товара
		
	
	public function actionSearchchars(){///Поиск опций по имени
		$searchchars = Yii::app()->getRequest()->getParam('q', NULL);	
		$searchchars = mb_strtolower(trim(htmlspecialchars($searchchars)), 'UTF-8');
		if (strlen($searchchars)>2) {
					
					//echo $searchchars;
					
					$criteria=new CDbCriteria;
					$criteria->addCondition("t.caract_name LIKE  '%$searchchars%' ");
					//$models = User::model()->findAll($criteria);//
					$models =  Characteristics::model()->findAll($criteria);
					
					
					
					
					if (isset($models) AND count($models)>0) {	
						$returnVal='';
						foreach($models as $num=>$me)
						{
						//	echo CHtml::tag('option',
						//			   array('value'=>iconv("CP1251", "UTF-8", $value)),CHtml::encode($name),true);
						 $returnVal .= $me->caract_name.' ('.$me->caract_id.')|'
														 .$me->caract_id."\n";
						}
						if (trim($returnVal)!='') echo $returnVal;			
					}///
					
		}/////////if (strlen($searchgoods)>2) {
	}//////////public function actionSearchchars(){//
		
		
	public function actionGetgroupsoptions(){//////Выборка опций предназначенных для группы
			//print_r($_POST);
			
			$category_id =Yii::app()->getRequest()->getParam('category_id');
			$search_option = Yii::app()->getRequest()->getParam('search_option');
			if (isset($category_id) OR isset ($search_option)) {
				//echo $category_id;
				
				$criteria=new CDbCriteria;
				if(isset($category_id)){
					$criteria->condition = "characteristics_categories.categories_id = :category_id";
					$criteria->params= array(':category_id'=>$category_id);
				}
				elseif(isset($search_option) AND strlen($search_option)>3) {
					$criteria->condition = "t.caract_name LIKE :search_option";
					$criteria->params= array(':search_option'=>'%'.mb_strtolower($search_option, 'UTF-8').'%');
				}
				else exit();
				
				$models=Characteristics::model()->with('characteristics_categories')->findAll($criteria);	
				
				/////////////////И выбираем  группы, которые присутствуют
				if(isset($models)) {
					$groups_ids = array();
					for ($k=0; $k<count($models); $k++) {
						if(isset($models[$k]->characteristics_categories)) $groups_ids= $groups_ids +CHtml::listdata($models[$k]->characteristics_categories, 'id', 'categories_id');
					}
					if (empty($groups_ids)==false) $ids = array_unique(array_values($groups_ids));
					if(isset($ids)) {
						//print_r($ids);
						$criteria=new CDbCriteria;
						$criteria->condition = "t.category_id IN (".implode(',', $ids).")";
						$category_models = Categories::model()->findAll($criteria);
						if(isset($category_models))$categories = CHtml::listdata($category_models, 'category_id', 'category_name');
						
						//print_r($categories);
					}
				}
				
				if (isset($models)) {
					$render_arr =  array('models'=>$models, 'category_id'=>$category_id);
					if(isset($categories)) $render_arr['categories']  = $categories;
					$this->renderPartial('treeservices/group_options_patial',$render_arr);		
				}
			
			}///if (isset($category_id)) {
			
			
	}///////public function actionGetgroupsoptions(){
		
	public function actionGetcatchilds() { ///////////Выборка подкатегорий по имени категории для карты (по области достаем города)
		$category_name =Yii::app()->getRequest()->getParam('reg');
		if(isset($category_name)) {
			$criteria=new CDbCriteria;
			$criteria->condition="parent_categories.category_name=:parent AND show_category 	= 1" ; 
			$criteria->order="t.category_name" ; 
			$criteria->params=array(':parent'=>trim($category_name));
			$models=Categories::model()->with('parent_categories')->findAll($criteria);
		
			if(isset($models))$this->renderPartial('category/childs', array('models'=>$models));
		}///////////if(isset($category_name)) {
	}//////////function	
	
	
	public function actionCatcompatiblecat($id){////////////Список связанный с горуппой других групп
		$this->layout="admin";
	//	print_r($_POST);
	//	echo '<br>';
		
		if(isset($_POST)) {///////////////////////Если что то приехало с формы
		
			$add_category = Yii::app()->getRequest()->getParam('add_category', NULL);
			$del_compat_category = Yii::app()->getRequest()->getParam('del_compat_category');
			$compat_category= Yii::app()->getRequest()->getParam('compat_category');
			
			if(trim($add_category)) {////Добавляем новую связанную категорию
					$CCC = new Category_categories_compability;
					$CCC->category_id = $id;
					$CCC->compatible_category = $add_category;
					$CCC->active = 0;
					try {
								$CCC->save(false);
						} catch (Exception $e) {
								 echo 'Caught exception: ',  $e->getMessage(), "\n";
						}//////////////////////
			}//////////if(trim($add_category)) {
				
			if(isset($del_compat_category))	foreach ($del_compat_category as $comp_cat_to_del=>$val) Category_categories_compability::model()->deleteByPk($comp_cat_to_del) ;
				
			if(isset($compat_category)) {////////////Обновляем значения
				foreach($compat_category AS $comp_id=>$comp_attrs) {

				$ccc = Category_categories_compability::model()->findByPk($comp_id);
					if(isset($ccc)) {
							if(isset($comp_attrs['active'])==false) $comp_attrs['active'] = 0;
							//echo '<br>';
							//print_r($ccc->filterize($comp_attrs['filters']));
							$comp_attrs['filters']=$ccc->filterize($comp_attrs['filters']);
							if(trim($comp_attrs['active_till_int'])) {/////////////////Преобразуем из формате datepickera в int
										$date_to_arr = split("-", $comp_attrs['active_till_int'] );
										if(isset($date_to_arr) AND isset($date_to_arr[0]) AND isset($date_to_arr[2]))$comp_attrs['active_till_int'] = mktime(23, 59, 59, $date_to_arr[1],$date_to_arr[0], $date_to_arr[2] ); ///////////////////////mdy
										else $comp_attrs['active_till_int'] = NULL;
							}
							else $comp_attrs['active_till_int'] = NULL;
							
							
							$ccc->products = Products::get_products_by_filters($comp_attrs, $ccc->compatible_category );///////////////////////////Выбираем товары и пишем их в таблицу
							
							$ccc->setAttributes($comp_attrs);
							$ccc->trysave();
							
							
							
					}////////if(isset($ccc)) {
				}/////////foreach($compat_category as $comp_id => $comp_attrs)
			}//////////if(isset($compat_category)) {////////////Обновляем значения		
				
		}////////////if(isset($_POST)) {/////////
		
	
		
		$CAT = Categories::model()->findByPk($id);
		$models = Category_categories_compability::model()->with('compcategories')->findAllByAttributes(array('category_id'=>$id));
		
		////////////////////Перебираем для получения спискатоваров
		for($i=0; $i<count($models); $i++) if($models[$i]->active==1 AND trim($models[$i]->products) ) {
				if(isset($allproducts)) $allproducts = array_merge($allproducts, unserialize($models[$i]->products));
				else $allproducts = unserialize($models[$i]->products);
		}////////////if($models[$i]->active==1 AND trim($models[$i]->products) ) {
			
		if(isset($allproducts)) {/////////////Вытаскиваем сами товары
		
					//print_r($allproducts);
		
					$criteria=new CDbCriteria;
					$criteria->condition="t.id IN (".implode(',', array_values($allproducts)).")" ; 
					$criteria->select=array( 't.*',  'picture_product.picture AS icon' );
					$criteria->join ="
					LEFT JOIN ( SELECT id, product, picture FROM picture_product WHERE is_main=1) picture_product ON picture_product.product = t.id  ";
					$products=Products::model()->findAll($criteria);
		}////////if(isset($allproducts)) {////////
		
		
		/////////////////////Характеристики групп
		if(isset(Yii::app()->params['group_characteristics_mode']) AND Yii::app()->params['group_characteristics_mode']=='multi' AND isset($models) AND empty($models)==false) $characteristics_categories = Characteristics_categories::get_characteristics_categories_by_category(array_values(CHtml::listdata($models, 'id', 'compatible_category')) ); 
		
		/////////////////Массивы значений характеристик в выбранных группах
		if(isset($characteristics_categories)) $values_list = Characteristics_values::get_products_values($characteristics_categories);
		/*
		echo '<pre>';	
		print_r($values_list);
		echo '</pre>';	
		*/
		
		$this->render('compatiblecat/list', array('models'=>@$models, 'CAT'=>$CAT, 'characteristics_categories'=>@$characteristics_categories, 'values_list'=>@$values_list , 'products'=>@$products));
	}////////////public function actionCatcompatiblecat($id){////////////Сп
		
		public function actionGetcities(){////////Достаем регионы по стране из нового справочника адресов
			 if (Yii::app()->request->isAjaxRequest) {
					$adres_country = Yii::app()->getRequest()->getParam('adres_country');
					$adres_region = Yii::app()->getRequest()->getParam('adres_region');
					$criteria=new CDbCriteria;
					//$criteria->condition = "t.country_id = :country_id";
					$criteria->condition = "t.region_id = :region_id";
					$criteria->params=array(':region_id'=>$adres_region);
					$criteria->order="t.sort, t.name";
					$models=World_adres_cities ::model()->findAll($criteria);
					if (isset($models)) {
							$returnVal='';
							$returnVal .= '<option>выбор...</option>';
							foreach($models as $num=>$region)
							{
							 if ($region->kladr_id!=NULL) $returnVal .= '<option value="'.$region->id.'">'.$region->name.'</option>';
							 else $returnVal .= '<option value="'.$region->id.'">'.$region->name.'</option>';
							}
							if (trim($returnVal)!='') echo $returnVal;	
					}
			}////// if (Yii::app()->request->isAjaxRequest) {
		}///////////public function actionGetregions(){////////Дос

		public function actionGetcitiesautocompl(){////
			if (Yii::app()->request->isAjaxRequest) {
				$query = Yii::app()->request->getParam('city_search', null);
				//echo $query.'<br>';
				$criteria=new CDbCriteria;
				//$criteria->condition = "t.country_id = :country_id";
				$criteria->condition = "t.name LIKE :reg AND t.country_id = 3159";
				$criteria->params=array(':reg'=>'%'.$query.'%');
				$criteria->order="t.sort, t.name";
				$criteria->limit = 10;
				$models=World_adres_cities::model()->with('region')->findAll($criteria);
				if($models!=null){
					$cities = '<ul>';
					foreach ($models as $model){
						$cities.='<li>'.CHtml::link($model->name.', '.$model->region->name, '#', array('rel'=>$model->id, 'class'=>'citylink',
								'onClick'=>"setCity(".$model->id.", this)"
						)).'</li>';
						
						
					}
					$cities.='</ul>';
					//echo json_encode($cities);
					echo $cities;
				}
				else echo 'Совпадений не найдено';
			}
		}
		
		public function actionGetcitiesautocompl2(){////
			if (Yii::app()->request->isAjaxRequest) {
				$query = Yii::app()->request->getParam('query', null);
				//echo $query.'<br>';
				$criteria=new CDbCriteria;
				//$criteria->condition = "t.country_id = :country_id";
				$criteria->condition = " (t.name LIKE :reg AND t.country_id = 3159) OR t.id = 15791539";
				$criteria->params=array(':reg'=>'%'.$query.'%');
				$criteria->order="t.sort, t.name";
				$criteria->distinct = true;
				$criteria->limit = 10;
				$models=World_adres_cities::model()->with('region')->findAll($criteria);
				$cities = array('query'=>'Unit');
				if($models!=null){
					//$cities = '<ul>';
					
					foreach ($models as $model){
					//	$cities.='<li>'.CHtml::link($model->name.', '.$model->region->name, '#', array('rel'=>$model->id, 'class'=>'citylink',
					//			'onClick'=>"setCity(".$model->id.", this)"
					//	)).'</li>';
						$cities['suggestions'][]=array('value'=>$model->name.', '.$model->region->name, 'data'=>$model->id);
						
					}
					//$cities.='</ul>';
					
					//echo $cities;
				}
				else $cities['suggestions'][]=array('value'=>'Совпадений не найдено', 'data'=>'');
				echo json_encode($cities);
				
			}
		}
		

		public function actionGetregioncities(){////////Достаем регионы по стране из нового справочника адресов
		
			 if (Yii::app()->request->isAjaxRequest) {
					$region = Yii::app()->getRequest()->getParam('region');
					$city = Yii::app()->getRequest()->getParam('city');
					$criteria=new CDbCriteria;
					//$criteria->condition = "t.country_id = :country_id";
					if($region>0) {
						 $criteria->condition = "t.region_id = :region_id";
						$criteria->params=array(':region_id'=>$region);
					}
					if($city>0){
						$criteria->condition = "t.id = :city_id";
						$criteria->params=array(':city_id'=>$city);
					}
					$criteria->order="t.sort, t.name";
					$models=World_adres_cities::model()->findAll($criteria);
					
					
					//////////Смотрим методы доставки
					$criteria=new CDbCriteria;
					$criteria->condition = "t.product_visible = 1 AND t.category_belong IN (".implode(',', Yii::app()->params['delivery_groups']).")";
					$criteria->order="t.category_belong, t.id";
					$products = Products::model()->findAll($criteria);
					
					
					/////////Смотрим цены доставки
					$prices = array();
					$delprices=Products_regions::model()->findAll();
					if(isset($delprices)) {
						for($i=0, $c=count($delprices); $i<$c; $i++) {
							$prices[$delprices[$i]->city][$delprices[$i]->product]=array('id'=>$delprices[$i]->id, 'price'=>$delprices[$i]->price, 'eprice'=>$delprices[$i]->eprice, 'freelimitcash'=>$delprices[$i]->freelimitcash,  'freelimitepay'=>$delprices[$i]->freelimitepay);
						}
					}
					
					if (isset($models)) {
							$this->renderPartial('treeservices/cities', array('cities'=>$models, 'products'=>$products, 'prices'=>$prices));
					}
			}////// if (Yii::app()->request->isAjaxRequest) {
		}///////////public function actionGetregions(){////////Дос
		
		public function actionSaveregprodlimits(){
			if (Yii::app()->request->isAjaxRequest) {
					$city = Yii::app()->getRequest()->getParam('city');
					//$record_id = Yii::app()->getRequest()->getParam('record', 0);
					$price = Yii::app()->getRequest()->getParam('price', 0);
					$type = Yii::app()->getRequest()->getParam('type');
					
					if(is_numeric($price)==true) {
						$rec = Products_regions::model()->findByAttributes(array('product'=>0, 'city'=>$city));
						if($rec==NULL) {
							$rec = new Products_regions;
							$rec->product = 0;
							$rec->city = $city;
						}
						$rec->$type = $price;
						try {
								$rec->save();
							} catch (Exception $e) {
								echo 'Ошибка сохранения лимитов бесплатной доставки. ',  $e->getMessage(), "\n";
							}/////
					}
					else echo "Только числа";
			}
		}
		
		public function actionSaveregprodprice(){
			 if (Yii::app()->request->isAjaxRequest) {
					$city = Yii::app()->getRequest()->getParam('city');
					$product = Yii::app()->getRequest()->getParam('product');
					$price = Yii::app()->getRequest()->getParam('price', 0);
					$type = Yii::app()->getRequest()->getParam('type');
					$freelimitcash = Yii::app()->getRequest()->getParam('freelimitcash', 0);
					if(is_numeric($price)==true OR $price == 0) {
						//if( $price==0)  $price=NULL;
						$criteria=new CDbCriteria;
						$criteria->condition = "t.city = :city AND t.product = :product";
						$criteria->params = array(':product'=>$product, ':city'=>$city);
						$rec = Products_regions::model()->find($criteria);
						if(isset($rec)) {
							$rec->$type  = $price;
							$rec->freelimitcash = $freelimitcash;
						}
						else{
							$rec = new Products_regions;
							$rec->$type = $price;
							$rec->product = $product;
							$rec->freelimitcash = $freelimitcash;
							$rec->city = $city;
						}
						try {
							$rec->save();
						} catch (Exception $e) {
							echo 'Ошибка сохранения цены. ',  $e->getMessage(), "\n";
						}/////
						
					}
					elseif($price!=0) echo "Только числа";
			 }
		}
		
		public function actionGetdelivery(){ ////////////ajax запрос доступных для города методов доставки из новой корзины
			if (Yii::app()->request->isAjaxRequest) {
					$city = Yii::app()->getRequest()->getParam('city');
					$current = Yii::app()->getRequest()->getParam('current', NULL); /////////текущее значение элемента управления
					$cart_sum = Yii::app()->getRequest()->getParam('cart_sum', NULL);///цена товаров в корзине
					
					
					
					//////////Смотрим способы оплаты и делаем массив продукт -> массив способов оплаты
					$service_payments_final = NULL;
					$criteria=new CDbCriteria;
					$criteria->condition = "t.enabled = 1";
					$pm_models = PaymentMethod::model()->findAll($criteria);
					if(isset($pm_models)){
						for($i=0, $c=count($pm_models); $i<$c; $i++){
							//print_r($pm_models[$i]->attributes);
							if(trim($pm_models[$i]->nomenklatura_list)) {
								$list_of_products = explode('#', $pm_models[$i]->nomenklatura_list);
								if(is_array($list_of_products)){
									for($k=0, $cc=count($list_of_products); $k<$cc; $k++) {
										$service_payments_final[$list_of_products[$k]][$pm_models[$i]->payment_method_id]=$pm_models[$i]->payment_method_name;
									}
								}
							}
						}
					}
					//echo '<pre>';
					//print_r($service_payments_final);
					//echo '</pre>';
					$delivery_methods_final = NULL;
					//////////Смотрим методы доставки
					$criteria=new CDbCriteria;
					$criteria->condition = "t.product_visible = 1 AND t.category_belong IN (".implode(',', Yii::app()->params['delivery_groups']).")";
					$criteria->order="t.category_belong, t.id";
					$products = Products::model()->findAll($criteria);
					if(isset($products)) for($i=0, $c=count($products); $i<$c; $i++) {
						 $delivery_methods[$products[$i]->id]= array('service'=>$products[$i]->product_name, 'descr'=>$products[$i]->product_short_descr, 'product_id'=>$products[$i]->id, 'html'=>$products[$i]->product_html_description);
						// $delivery_groups[$products[$i]->belong_category->category_name][$products[$i]->id] = array('service'=>$products[$i]->product_name, 'descr'=>$products[$i]->product_short_descr, 'product_id'=>$products[$i]->id);
					}
					//echo '<pre>';
					//print_r($delivery_groups);
					//echo '</pre>';
					
					$criteria=new CDbCriteria;
					$criteria->condition = "t.city = :city";
					$criteria->params = array(':city'=>$city);
					$models = Products_regions::model()->findAll($criteria);
					
					if(isset($models)) {
						for($i=0, $c=count($models); $i<$c; $i++) {
							if(isset($delivery_methods[$models[$i]->product])) {
								$delivery_methods_final[$models[$i]->productmodel->belong_category->category_name][$models[$i]->product] = $delivery_methods[$models[$i]->product];
								$delivery_methods_final[$models[$i]->productmodel->belong_category->category_name][$models[$i]->product]['category_id'] = $models[$i]->productmodel->category_belong;

								
								$delivery_methods_final[$models[$i]->productmodel->belong_category->category_name][$models[$i]->product]['prices']=array('price'=>$models[$i]->price, 'eprice'=>$models[$i]->eprice, 'freelimitcash'=>$models[$i]->freelimitcash, 'freelimitepay'=>$models[$i]->freelimitepay);
								$delivery_methods_final[$models[$i]->productmodel->belong_category->category_name][$models[$i]->product]['rec'] = $models[$i]->id;
								$delivery_methods_final[$models[$i]->productmodel->belong_category->category_name][$models[$i]->product]['city'] = $models[$i]->city;
							}
						}
						//echo '<pre>';
						//print_r($delivery_methods_final);
						//echo '</pre>';
						if(empty($delivery_methods_final)==true) {
							//////////////Добавляем единственные метод EMS
							$product = Products::model()->findByPk(Yii::app()->params['delivery_default']['product']);
							$delivery_methods_final['Курьерская доставка'][Yii::app()->params['delivery_default']['product']]=array(
							'service'=>$product->product_name, 
							'rec'=>'default',
							'city'=>$city,
							'descr'=>$product->product_short_descr , 
							'product_id'=>Yii::app()->params['delivery_default']['product'],
							'prices'=>array(
								'price'=>Yii::app()->params['delivery_default']['price'], 
								'eprice'=>Yii::app()->params['delivery_default']['eprice'], 
								'freelimitcash'=>Yii::app()->params['delivery_default']['freelimitcash'], 
								'freelimitepay'=>Yii::app()->params['delivery_default']['freelimitepay']
								)
							);
						} 
						$this->renderPartial('cart/delivery', array('models'=>$delivery_methods_final, 'current'=>$current, 'service_payments_final'=>$service_payments_final, 'cart_sum'=>$cart_sum));
					}
					else echo "Вариантов доставки для вашего города нет";
					
			}
		}
		
		
		public function actionAddpricevar(){
		    
		    //$output = array('new_line_id'=>'123', 'message'=>'added');
		    $product = Yii::app()->getRequest()->getParam('product', NULL);
		    if($product!=NULL && is_numeric($product)==true){
		        $pricevar = new PriceVariations();
		        $pricevar->product = $product;
		        try {
		            $pricevar->save();
		            $output = array('new_line_id'=>$pricevar->id, 'message'=>'added');
		        } catch (Exception $e) {
		            echo 'Caught exception: ',  $e->getMessage(), "\n";
		            exit();
		        }//////////////////////
		        
		        $show_json = json_encode($output , JSON_FORCE_OBJECT);
		        if ( json_last_error_msg()=="Malformed UTF-8 characters, possibly incorrectly encoded" ) {
		            $show_json = json_encode($API_array, JSON_PARTIAL_OUTPUT_ON_ERROR );
		        }
		        if ( $show_json !== false ) {
		            echo($show_json);
		        } else {
		            die("json_encode fail: " . json_last_error_msg());
		        }
		    }
		   
		    
		}
		
		/*
		 Метод для изучения vue.js. Сюда приходит кроссайтовый запрос с другого домена
		 * */
		public function actionGetgroupe(){
		    header("Content-Type: application/json");
		    //echo CJSON::encode('qweqweeqwe');
		    ////////////Вот ээти два должны присутствовать для кроссайт
            header("Access-Control-Allow-Origin:*") ;
            header("Access-Control-Request-Method: *");
		    $id = str_replace('?', '', Yii::app()->getRequest()->getParam('id',null));
		    if(is_numeric($id)){
		        $GR = Categories::model()->findByPk($id);
		        if($GR!=null) {
		            $aswer=array('answer'=>$GR->category_name, "forced"=>'false');
		            echo CJSON::encode($aswer);
		            
		        }
		        else{
		            $aswer=array('answer'=>'Not found');
		            echo CJSON::encode($aswer);
		        }
		    }
		    else {
		        $aswer=array('answer'=>'No valid ID', "forced"=>'false');
		        echo CJSON::encode($aswer);
		    }
		        
		    exit();
		}
		
		/*
		 * Метод для изучения vue.js. Просто отдаем список групп
		 */
		public function actionGetgroupes(){
		    header("Content-Type: application/json");
		    header("Access-Control-Allow-Origin:*") ;
		    header("Access-Control-Request-Method: *");
		    header("Access-Control-Allow-Headers: *");
		    
		    
		    $limit = str_replace('?', '', Yii::app()->getRequest()->getParam('id',null));
		    if(is_numeric($limit)==true){
		        $criteria=new CDbCriteria;
		        $criteria->limit = $limit;
		        $models = Categories::model()->findAll($criteria);
		    }
		    else $models = Categories::model()->findAll();
		    if($models!=null){
		        $json_arr = array();
		        foreach ($models as $model){
		            $json_arr[]=array('name'=>$model->category_name, 'id'=>$model->category_id);
		        }
		        echo CJSON::encode($json_arr);
		    }
		}
		
		
		//////////////////////////////////////Пример получения JWT токена
		public function actionGetjwt(){
		    header("Content-Type: application/json");
		    header("Access-Control-Allow-Origin:*") ;
		    header("Access-Control-Request-Method: *");
		    header("Access-Control-Allow-Headers: *");
		   // header("Access-Control-Allow-Headers: Content-Type, origin");
		    //echo 'wqewqe';
		    //print_r($_SERVER);
		    //exit();
		    //echo Yii::app()->getRequest()->getRequestType();
		    //exit();
		    $method = Yii::app()->getRequest()->getRequestType();
		    if($method=='POST'){
		        

		        
		        
    	        $key = "example_key";
    		    $key1 = "exdsfsdfample_key";
    		    $payload = array(
    		        "iss" => "http://example.org",
    		        "aud" => "http://example.com",
    		        "iat" => 1356999524,
    		        "nbf" => 1357000000
    		    );
    		    
    		    /**
    		     * IMPORTANT:
    		     * You must specify supported algorithms for your application. See
    		     * https://tools.ietf.org/html/draft-ietf-jose-json-web-algorithms-40
    		     * for a list of spec-compliant algorithms.
    		     */
    		    $jwt = array('token'=>JWT::encode($payload, $key));
    		    //echo '<br>$jwt  = <br>';
    		    //print_r($jwt );
    
    		    echo CJSON::encode( $jwt);
    		    //echo $jwt;
    		    exit();
		    }
		    else{
		        header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization');
		        throw new CHttpException(200);
		        //echo 
		    }
		}
		
		//////////Операции с пользователями
		public function actionJwtusers(){
		    header("Content-Type: application/json");
		    header("Access-Control-Allow-Origin:*") ;
		    header("Access-Control-Request-Method: *");
		    header("Access-Control-Allow-Headers: *");
		    $key = "example_key";
		    $key1 = "exdsfsdfample_key";
		    $allheaders = getallheaders();
		    $user_id = Yii::app()->getRequest()->getParam('id',null);
		    $method = Yii::app()->getRequest()->getRequestType() ;
		    try {
		        
		          
    		    if($allheaders && isset($allheaders['authorization'])){
    		        
    		       $jwt = $allheaders['authorization'];
    		        //print_r($allheaders);
    		        
    		        try {
    		            $decoded = JWT::decode($jwt, $key, array('HS256'));
    		            
    		            if($method=='GET' && $user_id==null){
        		            $models=Clients::model()->findAll();
        		            $clidata=array();
        		            if($models!=null){
        		                foreach ($models as $client) {
        		                    $clidata[]=array('id'=>$client->id, 'firstName'=>$client->first_name, 'lastName'=>$client->second_name,
        		                        'salary'=>$client->client_tels, 'position'=>$client->login);
        		                }
       
    		                }
    		            }
    		            elseif($method=='GET' && $user_id!=null && is_numeric($user_id)==true){
    		                $client=Clients::model()->with('orders')->findByPk($user_id);
    		                if($client!=null){
    		                    $clidata=array('id'=>$client->id, 'firstName'=>$client->first_name, 'lastName'=>$client->second_name,
    		                        'salary'=>$client->client_tels, 'position'=>$client->login);
    		                    if(isset($client->orders) && $client->orders!=null) {
    		                        foreach ($client->orders as $order) {
    		                            $ordlist[]=array('id'=>$order->id, 'recept_date'=>$order->recept_date, 'summa_pokupok'=>$order->summa_pokupok);
    		                        }
    		                        $clidata['orders']=$ordlist;
    		                    }
    		                    
    		                }
    		            }
    		            elseif($method=='POST' && $user_id!=null && is_numeric($user_id)==true){
    		              exit();
    		            }
    		            echo CJSON::encode($clidata);
    		            exit();
    		            
    		            
    		        } catch (Exception $e) {
    		            //print_r($e);
    		            //throw new CHttpException(500, 'Authorization error');
    		            //header("HTTP/1.0 500 Internal Server Error");
    		            echo CJSON::encode(array('error'=>'токен не расшифрован'));
    		            exit();
    		            
    		        }
    		        
    		        exit();
    		        
    		    }
    		    else{
    		        throw new CHttpException(403, 'no authorization');
    		        exit();
		      }
		    } catch (Exception $e) {
		        print_r($e);
		    }
		    
		    exit();
		    
		}
		
		public function actionJwtorders(){
		    header("Content-Type: application/json");
		    header("Access-Control-Allow-Origin:*") ;
		    header("Access-Control-Request-Method: *");
		    header("Access-Control-Allow-Headers: *");
		    $key = "example_key";
		    $key1 = "exdsfsdfample_key";
		    $allheaders = getallheaders();
		    $user_id = Yii::app()->getRequest()->getParam('id',null);
		    $method = Yii::app()->getRequest()->getRequestType() ;
		    try {
		        
		        
		        if($allheaders && isset($allheaders['authorization'])){
		            
		            $jwt = $allheaders['authorization'];
		            //print_r($allheaders);
		            
		            try {
		                $decoded = JWT::decode($jwt, $key, array('HS256'));
		                
		                if($method=='GET' && $user_id==null){
		                    $models=Orders::model()->findAll();
		                    $orddata=array();
		                    if($models!=null){
		                        foreach ($models as $order) {
		                            $orddata[]=array('id'=>$order->id, 'recept_date'=>$order->recept_date, 'id_client'=>$order->id_client);
		                        }
		                        
		                    }
		                }
		                elseif($method=='GET' && $user_id!=null && is_numeric($user_id)==true){
		                    $order=Orders::model()->with('OrderContent', 'client')->findByPk($user_id);
		                    if($order!=null){
		                       
		                        $orddata=array('id'=>$order->id, 
		                            'recept_date'=>$order->recept_date,
		                            'id_client'=>$order->id_client);
		                        if(isset($order->client) && $order->client!=null){
		                            $clname = $order->client->first_name." ".$order->client->last_name;
		                            $orddata['client_name']=$clname;
		                        }
		                        if(isset($order->OrderContent) && $order->OrderContent!=null){
		                            $cont = null;
		                            foreach ($order->OrderContent as $content) {
		                                if(isset($content->belongs_product) && $content->belongs_product!=null){
    		                                $cont[]=array('id'=>$content->contents_product,
    		                                    'product'=>$content->belongs_product->product_name,
    		                                    'price'=>$content->contents_price,
    		                                    'quantity'=>$content->quantity,
    		                                );
		                               }
		                            }
		                            if($cont!=null) $orddata['contents'] = $cont;
		                        }
		                       
		                        
		                    }
		                }
		                elseif($method=='POST' && $user_id!=null && is_numeric($user_id)==true){
		                    exit();
		                }
		                echo CJSON::encode($orddata);
		                exit();
		                
		                
		            } catch (Exception $e) {
		                //print_r($e);
		                //throw new CHttpException(500, 'Authorization error');
		                //header("HTTP/1.0 500 Internal Server Error");
		                echo CJSON::encode(array('error'=>'токен не расшифрован'));
		                exit();
		                
		            }
		            
		            exit();
		            
		        }
		        else{
		            throw new CHttpException(403, 'no authorization');
		            exit();
		        }
		    } catch (Exception $e) {
		        print_r($e);
		    }
		    
		    exit();
		}
		
		//////////Операции с антенной (для смотра)
		public function actionSmotrantenna(){
		    header("Content-Type: application/json");
		    header("Access-Control-Allow-Origin:*") ;
		    header("Access-Control-Request-Method: *");
		    header("Access-Control-Allow-Headers: *");
		    $key = "example_key";
		    $key1 = "exdsfsdfample_key";
		    $allheaders = getallheaders();
		    $id = Yii::app()->getRequest()->getParam('id',null);
		    $method = Yii::app()->getRequest()->getRequestType() ;
		    try {
		        
		        
		        if($allheaders && isset($allheaders['authorization'])){
		            
		            $ant = SmotrAntenna::model()->findByPk($id);
		            $d = $ant->getAttributes();
		            foreach ($d as $key=>$value) {
		                $dp = array(
		                    'nameParameter'=> '',
		                    'valueParameter'=>$value,
		                );
		                $data['deviceParameters'][$key]= $dp;
		            }
		            $data['title']= $d['title'];
		            $data['id']= $d['id'];
		            //$data['modes']=array('0'=>'Автомат', '1'=>'По программе' );
		            if($ant!=null)echo CJSON::encode($data);
		            exit();
		            
		        }
		        else{
		            throw new CHttpException(403, 'no authorization');
		            exit();
		        }
		    } catch (Exception $e) {
		        print_r($e);
		    }
		    
		    exit();
        }
        
        ///////////////Для изменения параметров
        public function actionSmotrantennaUpdate(){
            header("Content-Type: application/json");
            header("Access-Control-Allow-Origin:*") ;
            header("Access-Control-Request-Method: *");
            header("Access-Control-Allow-Headers: *");
            $key = "example_key";
            $key1 = "exdsfsdfample_key";
            $allheaders = getallheaders();
            $id = Yii::app()->getRequest()->getParam('id',null);
            $method = Yii::app()->getRequest()->getRequestType() ;
            try {
                
                
                if($allheaders && isset($allheaders['authorization'])){
                    
                    
                    

                    $jsondata = Yii::app()->request->getRawBody();
                    if($jsondata!=null) $devicedata = json_decode($jsondata);
                    //print_r($devicedata->workmode->id);
                    
                    $ant = SmotrAntenna::model()->findByPk($id);
                    //print_r($ant);
                    
                    if($ant!=null){
                        if(is_numeric($devicedata->workmode)==true && $devicedata->workmode>=0 && $devicedata->workmode < 2) {
                            $ant->workmode=$devicedata->workmode; 
                        }
                        if(isset($devicedata->angle) && is_numeric($devicedata->angle)==true && $devicedata->angle>=0) {
                            $ant->angle=$devicedata->angle;
                        }
                        if(isset($devicedata->azimut) && is_numeric($devicedata->azimut)==true && $devicedata->azimut>=0) {
                            $ant->azimut=$devicedata->azimut;
                        }
                         if($ant->save()) echo json_encode('saved');
                    }
                    
                    
                    
                    
                }
                else{
                    throw new CHttpException(403, 'no authorization');
                    exit();
                }
            } catch (Exception $e) {
                print_r($e);
            }
            
            exit();
        }
        
        
        /**Установка параметров матрицы
         *
         */
        public function actionSmotrmatrixupdate(){
            header("Content-Type: application/json");
            header("Access-Control-Allow-Origin:*") ;
            header("Access-Control-Request-Method: *");
            header("Access-Control-Allow-Headers: *");
            
            $key = "example_key";
            $key1 = "exdsfsdfample_key";
            $allheaders = getallheaders();
            $id = Yii::app()->getRequest()->getParam('id',null);
            $method = Yii::app()->getRequest()->getRequestType() ;
            
            $dstip = "10.10.0.161";
            $dstport = "1700";
            
            $frames=array();
            
            try {
                if($allheaders && isset($allheaders['authorization'])){
                    $jsondata = Yii::app()->request->getRawBody();
                    if($jsondata!=null) $devicedata = json_decode($jsondata);
                    
                    $i=1;
                    if(isset($devicedata)){
                        foreach ($devicedata as $key=>$output) {
                        //echo $key.": ";
                        //print_r($output);
                        
                        $cpr = new ContProtocol();
                        $cpr->matrixSetOutput($id, $i, $output->valueParameter);
                        $frames[]=$cpr->byteHeader;
                        
                        $i++;
                    }
                    

                        error_reporting(E_ERROR);
                        $fp = fsockopen("tcp://".$dstip, $dstport, $errno, $errstr, 1);
                        if (!$fp) {////////////нет соединения с контроллером
                            //echo "$errstr ($errno)<br />\n";
                            /////////////////// по WS кидаем команду в мой эмулятор арма
                            try {
                                // if($allheaders && isset($allheaders['authorization'])){
                                $wstip = "10.10.0.16";
                                $wstport = "8081";
                                
                                //print_r($devicedata);
                                
                                if($devicedata!=null){
                                    // if(isset($devicedata->state) ){
                                    /////////////////
                                    $newdata = array('devicecontrol'=>$id, 'devicedata'=>$devicedata);
                                    
                                    $wsc = new websocketclient();
                                    if( $wsc->websocket_open($wstip, $wstport) ) {
                                        $wsc->websocket_write(json_encode($newdata));
                                        //echo "Server responed with: " . $wsc->websocket_read($errstr);
                                        echo json_decode($wsc->websocket_read($errstr));
                                        exit();
                                    }
                                    
                                    // }
                                }
                                
                                //}
                                
                                
                            } catch (Exception $e) {
                                print_r($e);
                            }
                            
                        } else {
                            $i = 1;
                            foreach ($frames as  $frame) {
                                if(fwrite($fp, $frame)){
                                    time_nanosleep(0, 10000000);
                                    $data=fread($fp,2048);
                                    //echo $i . ' request, получено ' . mb_strlen($data) . " байт \n\r";
                                }
                                $i ++;
                                time_nanosleep(0, 10000000);
                            }
                            fclose($fp);
                        }
                        error_reporting(E_ALL);
                        
                        
                    
                  
                    
                }
                    
                }
            } catch (Exception $e) {
            }
            
            exit();
            
        }
        
        
        ///////////////Тест. Пробуем дать команду на обновление контролллера
        public function actionSmotrconverterupdate(){
            
            header("Content-Type: application/json");
            header("Access-Control-Allow-Origin:*") ;
            header("Access-Control-Request-Method: *");
            header("Access-Control-Allow-Headers: *");
            
            /*
            echo '333<pre>';
            print_r($_SERVER);
            echo '</pre>';
            
            if (isset($_SERVER['HTTP_ORIGIN'])) {
                // Decide if the origin in $_SERVER['HTTP_ORIGIN'] is one
                // you want to allow, and if so:
                header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
                header('Access-Control-Allow-Credentials: true');
                header('Access-Control-Max-Age: 86400');    // cache for 1 day
            }
            
            // Access-Control headers are received during OPTIONS requests
            if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
                
                if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
                    // may also be using PUT, PATCH, HEAD etc
                    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
                    
                    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
                        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
                        
                        exit(0);
            }
            */
            
            $key = "example_key";
            $key1 = "exdsfsdfample_key";
            $allheaders = getallheaders();
            $id = Yii::app()->getRequest()->getParam('id',null);
            $method = Yii::app()->getRequest()->getRequestType() ;
            
            $dstip = "10.10.0.161";
            $dstport = "1700";
            
            try {
                if($allheaders && isset($allheaders['authorization'])){
                $jsondata = Yii::app()->request->getRawBody();
                if($jsondata!=null) $devicedata = json_decode($jsondata);
                
                //print_r($devicedata);
                //exit();
                
                $frames=null;
                
                if(isset($devicedata->inpfreq)){
                    $cpr = new ContProtocol();
                    $cpr->dnSetInpFreqPrepare($id, $devicedata->inpfreq);
                    $frames[]=$cpr->byteHeader;
                }
                
                if(isset($devicedata->outfreq)){
                    $cpr = new ContProtocol();
                    $cpr->dnSetOutFreqPrepare($id, $devicedata->outfreq);
                    $frames[]=$cpr->byteHeader;
                }
                
                if(isset($devicedata->atten)){
                    $cpr = new ContProtocol();
                    $cpr->dnAttenPrepare($id, $devicedata->atten);
                    $frames[]=$cpr->byteHeader;
                }
                
                $pop_conn = socket_create(AF_INET, SOCK_STREAM, getprotobyname('tcp')); // открываем сокет
                
                try {
                    if (socket_connect($pop_conn, $dstip, $dstport)) {
                        
                        if($frames!=null)foreach ($frames as  $frame) {
                            echo $frame;
                            if(socket_write($pop_conn, $frame)){
                                time_nanosleep(0, 025000000);
                                $data = socket_read($pop_conn, 4048);
                            }
                        }


                    } else
                        echo 'Could not send query to controller....' . "\n\r";
                } catch (Exception $e) {
                    echo 'Could not send query to controller....' . "\n\r";
                }
                
                socket_close($pop_conn);
                
            }
                
            } catch (Exception $e) {
                print_r($e);
            }
            
            exit();
       }
       

        
}///////////////////


