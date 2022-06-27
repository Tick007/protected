<?php

class AdminproductsController extends Controller //////////////Главный контроллер администрирования товаров
{
	const PAGE_SIZE=10;

	/**
	 * @var string specifies the default action to be 'list'.
	 */
	public $defaultAction='list';
	var $faletype1 = array('1'=>'Инструкция', '2'=>'Презентация', 'Руководство пользователя');

	/**
	 * @var CActiveRecord the currently loaded data model instance.
	 */
	private $_model;


	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'CheckAuthority +list, group, product, updategroup, updategrouplist, product_update_main, 
				product_update_img, product_update_charact, create, update, update_trigers, manage_childs, 
				product_files, updatepacks, creategroup, add_category_param, updatevideo, administration',
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
				'actions'=>array('create','update', 'group', 'updategroup', 'show', 'list', 'updategrouplist', 
						'product', 'product_update_main', 'product_update_img', 'product_update_charact', 'update_trigers',
						'product_update_charact', 'addcompatible', 'manage_childs', 'product_files', 'updatepacks',
						'add_category_param', 'creategroup', 'updatevideo', 'administration'),
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
	

	
	public function init() {
        Yii::app()->layout = "admin";
    }
	
	public function get_productiya_path($parent){
				
				
				//var_dump($parent);
				//exit();
				///////////////////////////////Вычисляем путь по дереву продукции
				$Path = Categories::model()->findbyPk($parent);
				
				
				
				if($Path!=NULL) {
					$parent_id = $Path->parent;
					
					
					/////////Yii::app()->params->drupal_vars['taxonomy_catalog_level'] - нулевой уровень каталога
					$path_text = CHtml::link($Path->category_name, array('/adminproducts/','group'=>$Path->category_id), $htmlOptions=array ('encode'=>false));
	
					while ($parent_id>0) {
					
					$Path = Categoriestradex::model()->findbyPk($parent_id);
					$parent_id = $Path->parent;
					//if (trim($parent_id )=='')$parent_id =9;
					$path_text=CHtml::link($Path->category_name, array(Yii::app()->request->baseUrl.'/adminproducts/','group'=>$Path->category_id), $htmlOptions=array ('encode'=>false)).' -> '.$path_text;
					}///////while
					$path_text= CHtml::link('Список групп', Yii::app()->request->baseUrl.'/adminproducts/', $htmlOptions=array ('encode'=>false)).' -> '.$path_text;
					return $path_text;
				}		
				else return "path not found. Adminproducts 87";
	}/////////////

	public function actionList() {///////////////Вывод списка пользователей
	
	
		$criteria=new CDbCriteria;
		$criteria->order = 'category_name';
		$criteria->condition = " t.parent = 0 ";
		//$criteria->params = array(':SearchValue' => '%'.$search_field.'%'  );
		
		$gruppy = Categories::model()->findAll($criteria);//
		
		
		$this->render('list', array('gruppy'=>$gruppy) );
	}
	
	public function actionGroup() {/////////////Вывод информации о директории, списка подкатегорий, списка товаров
			//print_r ($_POST);
			//echo 'werwer';
			
			$sel_product = Yii::app()->getRequest()->getParam('sel_product');/////Выбранные товары
			$transfer_to_group = Yii::app()->getRequest()->getParam('transfer_to_group');//
			$new_link_group = Yii::app()->getRequest()->getParam('new_link_group');//
			$delete_products = Yii::app()->getRequest()->getParam('delete_products', NULL);//
			
			$gr_id = Yii::app()->getRequest()->getParam('id', NULL);
			
			if(isset($sel_product) AND isset($gr_id) AND isset($new_link_group) AND $new_link_group>0) {
				$this->link_products_to_category($sel_product, $new_link_group);
			}
			
			if(isset($sel_product) AND isset($gr_id) AND isset($transfer_to_group) AND $transfer_to_group>0) {
				$this->change_products_category($sel_product, $transfer_to_group);
			} 
			
			if(isset($sel_product) AND $delete_products!=NULL){
				
				foreach ($sel_product as $product_id => $prod_num) {
					//echo $product_id.'<br>';
					Products::DeleteProduct($product_id);
				}
				
				
				//xit();
			}

			
			$char_filter = Yii::app()->getRequest()->getParam('char_filter');
			if (intval($gr_id) ) {
					$path_text = $this->get_productiya_path ($gr_id);
					
					
					if (isset($_GET['copy'])) {
					//echo $_GET['copy'];
					$source_id=$_GET['copy'];
					$source=Products::model()->findByPk($source_id);
						  if($source!=NULL) {
						  		$destanation = new Products;
								try {
									$destanation->save();
									} catch (Exception $e) {
									 echo 'Ошибка сохранения: ',  $e->getMessage(), "\n";
									}//////////////////////
								$destanation_id = $destanation->id;
								$attributes = $source->attributes;
								$attributes['id']=$destanation_id;
								try {
									$destanation->saveAttributes($attributes);
									} catch (Exception $e) {
									 echo 'Ошибка копирования: ',  $e->getMessage(), "\n";
									}//////////////////////
								///////////////////////теперь смотрим значения характеристики
								$criteria=new CDbCriteria;
								//$criteria->order = 'caract_id';
								$criteria->condition = " id_product = :id_product";
								$criteria->params=array(':id_product'=>$source_id);
								$Vals = Characteristics_values::model()->findAll($criteria);
								for($i=0; $i<count($Vals); $i++) {
										$NewVal = new Characteristics_values;
										//print_r($NewVal);
										try {
											$NewVal->isNewRecord=true;
											$NewVal->save(false);
											} catch (Exception $e) {
											 echo 'Ошибка начального сохранения параметра: ',  $e->getMessage(), "\n";
											}/////////////////////
										$NewVal->id_caract = $Vals[$i]->id_caract;
										$NewVal->value = $Vals[$i]->value;
										$NewVal->id_product = $destanation_id;
										//print_r($NewVal->attributes);
										try {
											$NewVal->save();
											} catch (Exception $e) {
											 echo 'Ошибка сохранения параметра: ',  $e->getMessage(), "\n";
											}/////////////////////
								}/////////////for($i=0; $i<count($Vals); $i++) {
								
								
								////////////Параметр второй группы
								$categories_products = Categories_products::model()->findAllByAttributes(array('product'=>$source_id));
								if (isset($categories_products)) {
									for($h=0; $h<count($categories_products); $h++) {
										$cp = new Categories_products;
										$cp->group = $categories_products[$h]->group;
										$cp->product = $destanation_id;
										$cp->type = $categories_products[$h]->type;
										try {
												$cp->save();
											} catch (Exception $e) {
												 echo 'Caught exception: ',  $e->getMessage(), "\n";
											}//////////////////////
									}//////for($h=0; $h<count($categories_products); $h++) {
								}////////if (isset($categories_products)) {
								
								
								////////////Смотрим комплекты/////////////////
								$criteria=new CDbCriteria;
								$criteria->order = ' t.product ';
								
									if (isset(Yii::app()->urlManager->urlSuffix) AND trim(Yii::app()->urlManager->urlSuffix)!='') {
								$source_id = str_replace(Yii::app()->urlManager->urlSuffix, '', $source_id);
								}
								
								$criteria->condition = " t.product = ".$source_id;
								
								$packs = Products_packs::model()->findAll($criteria);
								if (isset($packs)) {
								for($k=0; $k<count($packs); $k++) {
								$Ps = new Products_packs;
								$Ps->isNewRecord=true;
								$Ps->product = $destanation_id;
								$Ps->included = $packs[$k]->included;
								$Ps->sort = $packs[$k]->sort;
								try {
														$Ps->save(false);
														} catch (Exception $e) {
														 echo 'Ошибка записи комплектного товара: ',  $e->getMessage(), "\n";
														}/////////////////////
						//}//////////////if (isset($pr_id) AND isset($add_product)) {
								
								}////////////////for($k=0; $k<count($compabile); $k++) {
								}///////////////if (isset($compabile)) {
								
								
								////////////////////////Смотрим ссылающиеся товары
								
								$criteria=new CDbCriteria;
								$criteria->order = ' t.product ';
								$criteria->condition = " t.product = ".$source_id;
								$compabile= Products_compability::model()->with('compprod')->findAll($criteria);//
								
								if (isset($compabile)) {
								for($k=0; $k<count($compabile); $k++) {
								
								
								//if (isset($pr_id) AND isset($add_product) AND $add_product>0) {
								$PC = new Products_compability;
								$PC->isNewRecord=true;
								$PC->product = $destanation_id;
								$PC->compatible = $compabile[$k]->compatible;
								try {
														$PC->save(false);
														} catch (Exception $e) {
														 echo 'Ошибка записи совместимого товара: ',  $e->getMessage(), "\n";
														}/////////////////////
						//}//////////////if (isset($pr_id) AND isset($add_product)) {
								
								}////////////////for($k=0; $k<count($compabile); $k++) {
								}///////////////if (isset($compabile)) {
								
								/////копируем главные картинки 
								$file_jpg = $_SERVER['DOCUMENT_ROOT'].Yii::app()->request->baseUrl.'/pictures/img/'.$source_id.'.jpg';
								if (file_exists($file_jpg)) copy($file_jpg, $_SERVER['DOCUMENT_ROOT'].Yii::app()->request->baseUrl.'/pictures/img/'.$destanation_id.'.jpg');
								
								$file_png = $_SERVER['DOCUMENT_ROOT'].Yii::app()->request->baseUrl.'/pictures/img/'.$source_id.'.png';
								if (file_exists($file_png)) copy($file_png, $_SERVER['DOCUMENT_ROOT'].Yii::app()->request->baseUrl.'/pictures/img/'.$destanation_id.'.png');
								
								/////////
								$file_jpg = $_SERVER['DOCUMENT_ROOT'].Yii::app()->request->baseUrl.'/pictures/img_med/'.$source_id.'.jpg';
								if (file_exists($file_jpg)) copy($file_jpg, $_SERVER['DOCUMENT_ROOT'].Yii::app()->request->baseUrl.'/pictures/img_med/'.$destanation_id.'.jpg');
								
								$file_png = $_SERVER['DOCUMENT_ROOT'].Yii::app()->request->baseUrl.'/pictures/img_med/'.$source_id.'.png';
								if (file_exists($file_png)) copy($file_png, $_SERVER['DOCUMENT_ROOT'].Yii::app()->request->baseUrl.'/pictures/img_med/'.$destanation_id.'.png');
								
								/////////
								$file_jpg = $_SERVER['DOCUMENT_ROOT'].Yii::app()->request->baseUrl.'/pictures/img_small/'.$source_id.'.jpg';
								if (file_exists($file_jpg)) copy($file_jpg, $_SERVER['DOCUMENT_ROOT'].Yii::app()->request->baseUrl.'/pictures/img_small/'.$destanation_id.'.jpg');
								
								$file_png = $_SERVER['DOCUMENT_ROOT'].Yii::app()->request->baseUrl.'/pictures/img_small/'.$source_id.'.png';
								if (file_exists($file_png)) copy($file_png, $_SERVER['DOCUMENT_ROOT'].Yii::app()->request->baseUrl.'/pictures/img_small/'.$destanation_id.'.png');
								
						  }
						  
	///////////////ДОполнительные изображения				
	$criteria=new CDbCriteria;
	$criteria->condition = "t.product = ".$source_id;;				
	$picture_product = Picture_product::model()->with('img')->findAll($criteria);		
	
	if (isset($picture_product)) {
			for ($k=0; $k<count($picture_product); $k++) {
				$imgfile=$_SERVER['DOCUMENT_ROOT']."/pictures/add/".$picture_product[$k]->picture.'.'.$picture_product[$k]->img->ext;
				//echo $imgfile.'<br>';
				if (is_file($imgfile) AND file_exists($imgfile)) {
					$NP = new Pictures;
					$NP->ext = $picture_product[$k]->img->ext;
					$NP->type = $picture_product[$k]->img->type;
					$NP->comments = $picture_product[$k]->img->comments;
					try {
						$NP->save(false);
						} catch (Exception $e) {
						 echo 'Ошибка Копирования картинки: ',  $e->getMessage(), "\n";
						}////////////////////
					if(isset($NP->id)) {
						//echo $_SERVER['DOCUMENT_ROOT']."/pictures/add/".$NP->id.'.'.$NP->ext.'<br>';
						@unlink($_SERVER['DOCUMENT_ROOT']."/pictures/add/".$NP->id.'.'.$NP->ext);
						 if (copy($imgfile, $_SERVER['DOCUMENT_ROOT']."/pictures/add/".$NP->id.'.'.$NP->ext) )       {
							 $PP = new Picture_product;
							 $PP->product =$destanation_id;
							 $PP->picture = $NP->id;
							 $PP->is_main = $picture_product[$k]->is_main;
							 $PP->is_vitrina =  $picture_product[$k]->is_vitrina;
							 $PP->is_sellout = $picture_product[$k]->is_sellout;
							 
							 try {
								$PP->save(false);
								} catch (Exception $e) {
								 echo 'Ошибка Копирования картинки: ',  $e->getMessage(), "\n";
								}////////////////////
							if (isset($PP->id)) {
								@unlink($_SERVER['DOCUMENT_ROOT']."/pictures/add/icons/".$NP->id.'.png');
								$fpngi = $_SERVER['DOCUMENT_ROOT']."/pictures/add/icons/".$picture_product[$k]->picture.'.png';
								if(is_file($fpngi) AND file_exists($fpngi) )copy($fpngi, $_SERVER['DOCUMENT_ROOT']."/pictures/add/icons/".$NP->id.'.png');
								@unlink( $_SERVER['DOCUMENT_ROOT']."/pictures/add/icons_small/".$NP->id.'.png');
								$fpngis = $_SERVER['DOCUMENT_ROOT']."/pictures/add/icons_small/".$picture_product[$k]->picture.'.png';
								if(is_file($fpngis) AND file_exists($fpngis) ) copy($fpngis, $_SERVER['DOCUMENT_ROOT']."/pictures/add/icons_small/".$NP->id.'.png');
							} //////if (isset($PP->id)) {
				}//////// if (copy($imgfileg, $_SERV
						 
					}	
				}/////////if (is_file($imgfile) AND file_exists($imgfile)) {
			}/////for ($k=0; $k<count($p
	}///////if (isset($pict_prod)) {
	
	//////////////////////////////////Цены priceVariations (добавлено для chemimart)
	if(isset($source->prices)){
	    foreach ($source->prices as $pricevar) {
	        $pvar = new PriceVariations();
            $pvar->volume = $pricevar->volume;
            $pvar->code = $pricevar->code;
            $pvar->price = $pricevar->price;
            $pvar->active = $pricevar->active;
            $pvar->product = $destanation->id;
            
            try {
                $pvar->save();
            } catch (Exception $e) {
                echo 'Caught exception: ',  $e->getMessage(), "\n";
                exit();
            }//////////////////////
	    }
	}
					
//exit();
}////////////if (isset($_GET['copy'])) {					
					
					
/////////////////////////////////////////////////////////////////////////////////////Характеристики группы
					$time1= microtime(true);
					
					$criteria=new CDbCriteria;
					$criteria->order = 'caract_id';
					$criteria->condition = " caract_category = :caract_category OR is_common = 1";
					$criteria->params=array(':caract_category'=>$gr_id);
					
					if(isset(Yii::app()->params['group_characteristics_mode']) AND Yii::app()->params['group_characteristics_mode']=='multi') {
						$criteria=new CDbCriteria;
						$criteria->order = 'characteristics_categories.sort';
						$criteria->condition = " characteristics_categories.categories_id = :caract_category ";
						$criteria->params=array(':caract_category'=>$gr_id);
						$grupp_characteristics = Characteristics::model()->with('characteristics_categories')->findAll($criteria);//
					}
					else $grupp_characteristics = Characteristics::model()->findAll($criteria);//
					
					//echo '<pre>';
					//print_r(count($grupp_characteristics));
					//echo $gr_id;
					//echo '</pre>';
					
					//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////Сама группа///////////////////////
					$gruppa = Categories::model()->findbyPk($gr_id);//
					
					
					$criteria=new CDbCriteria;
					$criteria->order = ' t.id DESC ';
					$criteria->condition = " picture_category.category_belong = ".$gr_id;
					$gruppa_files = Pictures::model()->with('picture_category')->findAll($criteria);//

					
					//$path_text = 'dsfsdf';
					/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////Подгруппы
					
					$criteria=new CDbCriteria;
					$criteria->order = 'category_name';
					$criteria->condition = " t.parent = :parent ";
					$criteria->params=array(':parent'=>$gr_id);
					$gruppy = Categoriestradex::model()->findAll($criteria);//
					
					
					
					///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////Выбираем товары
					$criteria=new CDbCriteria;
					$params=array(':parent'=>$gr_id);
					$criteria->order = 'product_name';
					$criteria->condition = " t.category_belong = :parent ";
					//echo $char_filter;
					if (isset($char_filter) AND trim($char_filter) !='' AND $char_filter !='0' )  {
						//echo 'qweweq';
						$criteria->addCondition("char_val.value = :char_filter");
						$params[':char_filter']=$char_filter; 
					}
					$criteria->params = $params;
					$goods1 = Products::model()->with('char_val')->findAll($criteria);// это товары у которых непосредственно указанно что они входят в данную категорию
					if (isset($goods1)) $goods1_list = CHtml::listdata($goods1, 'id', 'id');
					//print_r( $goods1_list);
					
					
					
					///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////Выбираем линкованные товары
					$criteria=new CDbCriteria;
					//$criteria->order = 'product_name';
					$criteria->condition = " categories_products.group = :parent ";
					$params=array(':parent'=>$gr_id);
					if (isset($goods1_list)) {
							$qqq = array_keys($goods1_list);
							if (isset($qqq) AND empty($qqq)==false) $criteria->addCondition("t.id NOT IN (".implode(',', $qqq).")");
					}
					
					if (isset($char_filter) AND trim($char_filter) !='' AND $char_filter !='0' )  {
						$criteria->addCondition("char_val.value = :char_filter");
						$params[':char_filter']=$char_filter; 
					}
					$criteria->params = $params;
					$goods2= Products::model()->with('categories_products', 'char_val')->findAll($criteria);// это товары у которых непосредственно указанно что они входят в данную категорию	
					$goods = array_merge ($goods1, $goods2);
					
					/////Смотрим фотки в новом стиле 14.11.2019
					if(isset($goods) && empty($goods)==false) {
					    $selected =  $goods1_list = array_keys (CHtml::listdata($goods, 'id', 'id'));
					    
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
					    
					    //if(isset($pict_ext))print_r($pict_ext);
					    //else echo 'no pictures';
					}
					
					
					
					
					
					
					if (isset($goods)) {
						for ($i=0; $i<count($goods); $i++) $productslist[]=$goods[$i]->id;
								if (isset($productslist)) {
								//print_r($productslist);
								$connection = Yii::app()->db;
								$query = "SELECT id_product, GROUP_CONCAT(CONCAT_WS( ';#;', value,id_caract ) ORDER BY characteristics.sort SEPARATOR '#;#') AS  attribute_value2 FROM characteristics_values JOIN characteristics ON characteristics.caract_id = characteristics_values.id_caract WHERE id_product IN (".implode(',', $productslist).")  GROUP BY id_product ORDER BY   characteristics_values.value_id";////////////
								//echo $query;
								$command=$connection->createCommand($query)	;
								$dataReader=$command->query();
								$records=$dataReader->readAll();////
						
											if (isset($records)) {
        											    for($i=0; $i<count($records); $i++) {
        											        $products_attributes[$records[$i]['id_product']]=$records[$i]['attribute_value2'];
        											    }
												}
																
																//echo '<br>';
																//print_r($products_attributes);
											 }
					}/////////
					
					
					
					
					if (isset(Yii::app()->params['products_char_filtr']) AND isset($productslist)) {//////Смотрим список значений для заданого для фильтрации параметра в группе	
							if (isset(Yii::app()->params['products_char_filtr'][$gr_id]) && $filtr_char_id = Yii::app()->params['products_char_filtr'][$gr_id]) {
									//echo $filtr_char_id.'<br>';
									$criteria=new CDbCriteria;
									$criteria->condition = " t.id_caract = :id_caract ";
									$criteria->params=array(':id_caract'=>$filtr_char_id);
									$criteria->addCondition("t.id_product IN (".implode(',', $productslist).")");
									$model_filter_values = Characteristics_values::model()->findAll($criteria);
									//print_r($model_filter_values);
									if (isset($model_filter_values)) {
											$group_filter_values = CHtml::listdata($model_filter_values, 'value', 'value');
											//if (isset($group_filter_values)) print_r($group_filter_values);
									}
							}
					}
					
					
					
					
					$criteria=new CDbCriteria;
					$criteria->order = ' t.category_name ';
					$criteria->condition = " 	t.parent = 0";
					$all_groups = Categories::model()->findAll($criteria);//
					
					
					
					
					//////////Список типов параметров
					$char_types = Characteristics_types::model()->findAll();
					for ($i=0; $i<count($char_types); $i++) $all_char_types[$char_types[$i]->id]=$char_types[$i]->type;
					
					///////////////Получаем спкок страниц доступных для связывания с группой
					$criteria=new CDbCriteria;
					$criteria->order = ' t.title ';
					$criteria->condition = " 	t.section = 3";
					$data=Page::model()->findAll($criteria);//
					
					$linked_pages =CHtml::listData($data,'id','title');
					$linked_pages[0]='Выбор';
					ksort($linked_pages);
					//print_r($linked_pages);
					//exit();
					$this->render('list', array('gruppy'=>$gruppy, 
							'path_text'=>$path_text, 
							'grupp_characteristics'=>$grupp_characteristics, 
							'gruppa'=>$gruppa, 
							'goods'=>$goods, 
							'all_groups'=>$all_groups, 
							'gruppa_files'=>$gruppa_files, 
							'linked_pages'=>$linked_pages, 
							'all_char_types'=>@$all_char_types, 
							'group_filter_values'=>@$group_filter_values, 
							'products_attributes'=>@$products_attributes, 
							'filtr_char_id'=>@$filtr_char_id,
					        'pict_ext'=>isset($pict_ext)?$pict_ext:null,
							)
					 );
			}/////////if (intval($gr_id) ) {
			else $this->redirect(Yii::app()->request->baseUrl.'/adminproducts/', true, 301);
			
	}
	
	public function actionProduct_files(){	
		$id = Yii::app()->getRequest()->getParam('id');
		$group = Yii::app()->getRequest()->getParam('group');
		$filetype1 = Yii::app()->getRequest()->getParam('filetype1', NULL);
		
		if($filetype1!=null){/////////////Сохранение старый
			/////Array ( [633] => 1 [634] => 1 )
			if(is_array($filetype1)){
				foreach ($filetype1 as $file_id=>$file_type){
					//echo $file_id.'=>'.$file_type.'<br>';
					$F = Files::model()->findByPk($file_id);
					if($F != null){
						$F->filetype1 = $file_type;
						try {
							$F->save();
						} catch (Exception $e) {
							print_r($e);
							exit();
						}
					}
				}
			}

		}
		
		if (isset($_FILES) ) {//////////
					// if (Yii::app()->user->checkAccess('upload_files') ) {	 
						if( isset($_FILES['imgfile'])) $dowloaded_files = $_FILES['imgfile'];
						
						 if (isset($_POST['imgdel']) ) {/////////////Удаление файла
							foreach ($_POST['imgdel'] as $key => $value ) {
									//echo $key.' - '.$value.'<br>';
									$FILE= Files::model()->findbyPk($key);	
									$path_to_del = $_SERVER['DOCUMENT_ROOT'].'/'.$FILE->filepath;
									//echo 	$path_to_del ;
									@unlink($path_to_del);
									@$FILE->delete();
							}	////////////foreach ($_POST['del_link'] as $key => $value ) {
						}////////////////if (isset($_POST['del_link']) ) {/////////////Удаление связи
						 
						if($_FILES['create_new_file']) {/////////////добавляем новый файл
						 	//print_r($_FILES['create_new_file']);
							$dowloaded_files = $_FILES['create_new_file'];
							if (trim($dowloaded_files['tmp_name'])) {//////////////если файл был передан
								
					
								
								$FILE=new Files;
								$FILE->product= $id;
								$FILE->save();	
								$path_to_del=$_SERVER['DOCUMENT_ROOT'].'/pictures/files/'.$FILE->id.'_'.$dowloaded_files['name'];
								
								$upname = preg_replace('/[^a-z0-9_.]/','',strtolower( str_replace(" ", "_",trim(FHtml::translit( $dowloaded_files['name'])))));
								/////
								$path_to_del=$_SERVER['DOCUMENT_ROOT'].'/pictures/files/'.$FILE->id.'_'.$upname ;
								
								//$path_to_del = FHtml::translit(iconv("CP1251", "UTF-8", $path_to_del)); 
								//$path_to_del = preg_replace('/[^a-z0-9_.]/','',strtolower( str_replace(" ", "_",trim(FHtml::translit( $path_to_del)))));

								//echo $path_to_del;
								
								//echo $dowloaded_files['tmp_name'].' '.file_exists($dowloaded_files['tmp_name']).'<br>';
								//echo $path_to_del.' '.file_exists($path_to_del).'<br>';
								//exit();
								
								@unlink($path_to_del); /////
								if (move_uploaded_file($dowloaded_files['tmp_name'], $path_to_del)) {
								 
										$FILE->filename = $FILE->id.'_'.preg_replace('/[^a-z0-9_.]/','',strtolower( str_replace(" ", "_",trim(FHtml::translit( $dowloaded_files['name'])))));
										//$FILE->filepath = 'pictures/files/'.$FILE->id.'_'.FHtml::translit(iconv("CP1251", "UTF-8", $dowloaded_files['name']));
										$FILE->filepath = 'pictures/files/'.$FILE->id.'_'.preg_replace('/[^a-z0-9_.]/','',strtolower( str_replace(" ", "_",trim(FHtml::translit($dowloaded_files['name'])))));
										
										
										
										$FILE->filemime = $dowloaded_files['type'];
										$FILE->filesize = $dowloaded_files['size'];
										$FILE->status = 1;
										$FILE->timestamp = time();
										$FILE->user_id = Yii::app()->user->id;
										/*
										echo '<pre>';
										echo print_r($FILE->attributes);
										echo '</pre>';
										*/
										//exit();
										
										
										$FILE->save();	
										
										
									
									}//////////////if (move_uploaded_file($dowloaded_files['tmp_name'], $path_to_del)) {
									else 	$FILE->delete();
							}/////////if (trim($dowloaded_files['tmp_name'][$i])) {///////
						 }
						 
						 
						 
				//	}///////////// if (Yii::app()->user->checkAccess('upload_files') ) {
				}//////////////////if (isset($_POST['imgfile'])) {	
	
		//$this->redirect(Yii::app()->request->baseUrl.'/adminproducts/products', true);
			$url = Yii::app()->createUrl('adminproducts/product', array('id'=>$id, 'group'=>$group, 'activetab'=>'tab9'));
			$this->redirect($url, true);		
		
	}////public function actionProduct_files(){	
	
	public function actionUpdategroup(){////Обновление группы и её характеристик
	$gr_id = Yii::app()->getRequest()->getParam('id', NULL);
	$CAT =  Categories::model()->findbyPk($gr_id);//
	//print_r($_POST);
	//exit();
	
	//echo $gr_id.'<br>';
	
	$delete_category = Yii::app()->getRequest()->getParam('delete_category');
	
	
	if (isset($CAT) AND isset($_POST) AND empty($_POST)==false) {///////////if (isset($CAT)) {
		
		if(isset($delete_category)==false) { 
		
		
			if (isset($_POST['new_good'])) {
			    

			    
					$Prod = new Products();
					$Prod->category_belong = $gr_id;
					$Prod->product_name = 'Новый товар';
					try {
							$Prod->save();
						} catch (Exception $e) {
						 echo 'Caught exception: ',  $e->getMessage(), "\n";
						 exit();
						}//////////////////////
			}//////////////////////
			
			$CAT->category_name = trim(Yii::app()->getRequest()->getParam('category_name', 'noname'));
			
			if ($CAT->alias==NULL AND $CAT->category_name != NULL) $CAT->alias=preg_replace('/[^a-z0-9_]/','',strtolower( str_replace(" ", "_",FHtml::translit($CAT->category_name)))); 
			if (@$_POST['auto_alias']) $CAT->alias=preg_replace('/[^a-z0-9_]/','',strtolower( str_replace(" ", "_",trim(FHtml::translit($CAT->category_name))))); 
			elseif (!@$_POST['auto_alias']) $CAT->alias = @trim(htmlspecialchars($_POST['alias']));
			
			///////////////////////////////////Вызываем функцию для получекния полного пути
			$CAT->path = serialize(FHtml::get_productiya_path($CAT->category_id));
			
			//print_r(unserialize($CAT->path));
			//exit();
			
			
			//$page = Yii::app()->getRequest()->getParam('page', NULL);
			$CAT->linked_page = Yii::app()->getRequest()->getParam('linked_page', NULL);
			$CAT->title = trim(Yii::app()->getRequest()->getParam('title',  NULL));
			$CAT->keywords = trim(Yii::app()->getRequest()->getParam('keywords', NULL));
			$CAT->description = trim(Yii::app()->getRequest()->getParam('description',  NULL));
			$CAT->parent = Yii::app()->getRequest()->getParam('category_parent', 0);
			$CAT->linked_page = Yii::app()->getRequest()->getParam('linked_page', NULL);
			
			$show_category = Yii::app()->getRequest()->getParam('show_category', NULL);
			if ($show_category==NULL) $show_category = 0;
			else $show_category = 1;
			$CAT->show_category = $show_category;
			
			$archive = Yii::app()->getRequest()->getParam('archive', NULL);
			if ($archive==NULL) $archive = 0;
			else $archive = 1;
			$CAT->archive = $archive;
			
			try {
						$CAT->save();
				} catch (Exception $e) {
						 echo 'Caught exception: ',  $e->getMessage(), "\n";
				}//////////////////////
			
			//////////////////////////////////////////////////////Параметры (характеристики) группы /////////////////////////////////////////////////////
				
				
				$qqq = (isset(Yii::app()->params['group_characteristics_mode']) AND Yii::app()->params['group_characteristics_mode']=='multi');
				//var_dump($qqq); 
				//exit();
				
				if(isset(Yii::app()->params['group_characteristics_mode']) AND Yii::app()->params['group_characteristics_mode']=='multi') {///////////НОвый вариант, когда одна опция во множестве групп
					$CAT->parent = Yii::app()->getRequest()->getParam('category_parent', 0);
					$show_category = Yii::app()->getRequest()->getParam('show_category');
					$show_category_name = Yii::app()->getRequest()->getParam('show_category_name');
					if (isset($show_category)) $show_category = 1;
					else $show_category = 0;
					if (isset($show_category_name)) $show_category_name = 1;
					else $show_category_name = 0;
					
					$CAT->show_category_name = $show_category_name;
					$CAT->show_category = $show_category;
					$show_children_as_one = Yii::app()->getRequest()->getParam('show_children_as_one', NULL);
					if ($show_children_as_one==NULL) $show_children_as_one = 0;
					else $show_children_as_one = 1;
					
					$show_children_as_products = Yii::app()->getRequest()->getParam('show_children_as_products', NULL);
					if ($show_children_as_products==NULL) $show_children_as_products = 0;
					else $show_children_as_products = 1;
					
					
					$CAT->show_children_as_products = $show_children_as_products;
					
					try {
								$CAT->save();
								if ( (@$_POST['path_update']) OR (isset($old_alias) AND  $old_alias!= $CAT->alias) ) {
									$CAT->move($CAT->parent);
									//exit();
									Yii::app()->user->setFlash('path_change', 'Изменен алаис(<strong>'.@$old_alias.'</strong> -> <strong>'.$CAT->alias.'</strong>) для группы '.$CAT->category_name.' и путь у всех вложенных групп.');
								}
						} catch (Exception $e) {
								 echo 'Caught exception: ',  $e->getMessage(), "\n";
						}//////////////////////
						
						
					///////////////////////////////////Вызываем функцию для получекния полного пути
					$CAT->path = serialize(FHtml::get_productiya_path($CAT->category_id));
					try {
								$CAT->save();
						} catch (Exception $e) {
								 echo 'Caught exception: ',  $e->getMessage(), "\n";
						}//////////////////////
					
					/////////////////////////////Сортировка
					$sort_in_gr = Yii::app()->getRequest()->getParam('sort_in_gr', NULL);
					$sort_table = Yii::app()->getRequest()->getParam('sort_table', NULL);
					$parent = Yii::app()->getRequest()->getParam('parent');
					
		
					if ($sort_in_gr != NULL AND is_array($sort_in_gr)) {
							foreach ($sort_in_gr as $ccid=>$sort_val) {
									if (is_numeric($ccid)==true) {
											$characteristics_categories = Characteristics_categories::model()->findByPk($ccid);
											if ($characteristics_categories != NULL) {
													$characteristics_categories->sort = $sort_val;
													$characteristics_categories->sort_table = $sort_table[$ccid];
													try {
																$characteristics_categories->save();
														} catch (Exception $e) {
																 echo 'Ошибка сохранения сортировки параметра: ',  $e->getMessage(), "\n";
														}//////////////////////
											}////////if ($characteristics_categories != NULL) {
									}///////////////	if (is_numeric($ccid)=true) {
							}////////foreach ($sort_in_gr as $characteristics_categories_id=>$sort_val) {
							//print_r($sort_in_gr);
					}//////////////////////
					
					if(isset($parent)) {
							foreach ($parent as $ccid=>$parent_val) {
									$characteristics_categories = Characteristics_categories::model()->findByPk($ccid);
									if (isset($characteristics_categories)) {
													$characteristics_categories->parent = $parent_val;
													try {
																$characteristics_categories->save();
														} catch (Exception $e) {
																 echo 'Ошибка сохранения родителя параметра для группы: ',  $e->getMessage(), "\n";
														}//////////////////////	
									}////////	if (isset($characteristics_categories)) {
							}/////////foreach ($parent as $ccid=>$parent_val) {
					}//////if(isset($parent)) {
				
				if (isset($_POST['caract_name'])) {	
				
					$models = Characteristics_categories::model()->findAllbyAttributes(array('categories_id'=>$gr_id));
					$some_key_in_gr = Yii::app()->getRequest()->getParam('some_key_in_gr', NULL);
					$is_argument = Yii::app()->getRequest()->getParam('is_argument', NULL);
					$table_form_name = Yii::app()->getRequest()->getParam('table_form_name', NULL);
					$product_frontend_name = Yii::app()->getRequest()->getParam('product_frontend_name', NULL);
					
					for ($i=0; $i<count($models); $i++) {
						//echo $models[$i]->id.'<br>';
						$characteristics_categories = Characteristics_categories::model()->findByPk($models[$i]->id);
						if (isset($characteristics_categories)) {
								if (isset( $some_key_in_gr[$characteristics_categories->id]))$characteristics_categories->some_key = $some_key_in_gr[$characteristics_categories->id];
								else $characteristics_categories->some_key=NULL;
								
								if (isset($is_argument[$characteristics_categories->id]) AND isset($seted_argument)==false){/////////////////////только 1 что бы был
										$characteristics_categories->is_argument= 1;
										$seted_argument = 1;
								}
								else $characteristics_categories->is_argument=NULL;
								
								if (isset($table_form_name[$characteristics_categories->id]))$characteristics_categories->table_form_name=1;
								else $characteristics_categories->table_form_name=NULL;
								
								if (isset($product_frontend_name[$characteristics_categories->id])) $characteristics_categories->product_frontend_name=1;
								else $characteristics_categories->product_frontend_name=NULL;
								
								
									try {
																$characteristics_categories->save();
														} catch (Exception $e) {
																 echo 'Ошибка сохранения сортировки параметра: ',  $e->getMessage(), "\n";
														}//////////////////////
						}///////////if (isset($characteristics_categories)) {
					}//////for ($i=0; $i<count($models); $i++) {
					/*
					$some_key_in_gr = Yii::app()->getRequest()->getParam('some_key_in_gr', NULL);
					if ($some_key_in_gr != NULL AND is_array($some_key_in_gr)) {
							foreach ($some_key_in_gr as $ccid=>$some_key_val) {
									//echo $ccid."=>".$some_key_val;
									if (is_numeric($ccid)==true) {
											$characteristics_categories = Characteristics_categories::model()->findByPk($ccid);
											if ($characteristics_categories != NULL) {
													$characteristics_categories->some_key = $some_key_val;
													try {
																$characteristics_categories->save();
														} catch (Exception $e) {
																 echo 'Ошибка сохранения сортировки параметра: ',  $e->getMessage(), "\n";
														}//////////////////////
											}////////if ($characteristics_categories != NULL) {
									}///////////////	if (is_numeric($ccid)=true) {
							}////////foreach ($sort_in_gr as $characteristics_categories_id=>$sort_val) {
							//print_r($sort_in_gr);
					}//////////////////////
					*/
					//exit();
					
					//////////////////////////////////////////////////////Параметры (характеристики) группы /////////////////////////////////////////////////////
					$criteria=new CDbCriteria;
					$criteria->order = 'caract_id';
					$criteria->condition = " characteristics_categories.categories_id = :caract_category ";
					$criteria->params=array(':caract_category'=>$gr_id);
					$grupp_characteristics = Characteristics::model()->with('characteristics_categories')->findAll($criteria);//
					//print_r($_POST);
					for ($i=0; $i<count($grupp_characteristics); $i++) {
							$CHAR_CAT = Characteristics_categories::model()->findbyPk($grupp_characteristics[$i]->characteristics_categories[0]->id);///////////////////////////////Удаление характеристики совсем
							
							/*
							$qqq = Yii::app()->getRequest()->getParam('del_car');
							print_r($qqq);
							echo '<pre>';
							print_r($_POST);
							echo '</pre>';
							exit();
							*/
							
							if (@array_key_exists($grupp_characteristics[$i]->characteristics_categories[0]->id, $_POST['del_car']) ){
									   try {
											$CHAR_CAT->delete();
											} catch (Exception $e) {
													 echo 'Caught exception: ',  $e->getMessage(), "\n";
											}//////////////////////
							
							}//////////if (in_array($grupp_characteristics[$i]->caract_id, $_POST['del_car']) {
							elseif(isset($_POST['caract_name'][$grupp_characteristics[$i]->caract_id]))  {
									//echo  $_POST['caract_name'][$grupp_characteristics[$i]->caract_id].'<br>';
									$CHAR = Characteristics::model()->findbyPk($grupp_characteristics[$i]->caract_id);
									$CHAR->caract_name = @$_POST['caract_name'][$grupp_characteristics[$i]->caract_id];
									$CHAR->caract_mesuare = @$_POST['caract_mesuare'][$grupp_characteristics[$i]->caract_id];
		//							$CHAR->char_descr  = @$_POST['char_descr'][$grupp_characteristics[$i]->caract_id];
									$is_common =@$_POST['is_common'][$grupp_characteristics[$i]->caract_id];
									if (isset($is_common)==false) $is_common = 0;
									$CHAR->is_common = $is_common;
									
									$char_type = $_POST['char_type'][$grupp_characteristics[$i]->caract_id];
									if ($char_type==NULL) $char_type = 0;
									$CHAR->char_type = $char_type;
									
									
									//echo $grupp_characteristics[$i]->caract_id.'<br>';
									//echo $char_type.'<br>';
									
									//print_r($_POST);
									//echo '<br><br>';
		
									if (($char_type==3 OR $char_type==13) AND isset($_POST['value_list'][$grupp_characteristics[$i]->caract_id])) {/////////////////СОхранияем только если задано что список
									//echo 'werwer';
									//exit();
											$values_arr = $_POST['value_list'][$grupp_characteristics[$i]->caract_id];
											$num = count($values_arr);
											
											//print_r($values_arr);
											
											$new_arr=NULL;
											for($k=0; $k<$num; $k++) if (@$values_arr[$k] != NULL  AND  @trim($values_arr[$k])!='')  {
													$new_arr[] =  $values_arr[$k];
											}
											
											//echo '<br>';
											//print_r($new_arr);
											//exit();
											
											if (isset($new_arr)) {
													$value_list =  implode("#", $new_arr);
													$CHAR->value_list = $value_list ;
											}////////////f (isset($value_list)) {
											else 	$CHAR->value_list = NULL;
									}/////////if ($char_type==3 AND isset($_POST['val
									
									$is_main = @$_POST['is_main'][$grupp_characteristics[$i]->caract_id];
									if ($is_main==NULL) $is_main = 0;
									$CHAR->is_main = $is_main;
									
									
									$values_arr = NULL;
									$value_lis = NULL;
									try {
											$CHAR->save();
									} catch (Exception $e) {
											echo 'Caught exception: ',  $e->getMessage(), "\n";
									}///////////////////
							}///else  {/if (in_array($grupp_characteristics[$i]->caract_id, $_POST['del_car']) {
					}////////////////for ($i=0; $i<count($grupp_characteristics); $i++) {
						
						
				}////////$_POST['caract_name']		
				
				
				
				
				
				
				
			
			}//////////['group_characteristics_mode']=='multi') {///////////Н
			else {/////кЛАССИЧЕСКИЙ ВАРИАНТ ОДНА ОПЦИЯ К ОДНОЙ ГРУППЕ
				
	
				
			    if(isset($_POST['caract_name']) && empty($_POST['caract_name'])==false) foreach ($_POST['caract_name'] as $char_id => $char_name):
						$CHAR = Characteristics::model()->findbyPk($char_id);//
						if ($CHAR != NULL) {
						
							if (@array_key_exists($char_id, $_POST['del_car']) ){
									try {
									$CHAR->delete();
									} catch (Exception $e) {
											 echo 'Caught exception: ',  $e->getMessage(), "\n";
									}//////////////////////
								 }//////if (@array_key_exists($char_id, $_POST['del_car']) ){
							else {///if1
								$CHAR->caract_name = $char_name;
								$is_common = @$_POST['is_common'][$char_id];
								if ($is_common==NULL) $is_common = 0;
								$CHAR->is_common = $is_common;
								
								$is_main = @$_POST['is_main'][$char_id];
								if ($is_main==NULL) $is_main = 0;
								$CHAR->is_main = $is_main;
								
								$sort = @$_POST['sort'][$char_id];
								if ($sort==NULL) $sort = 0;
								$CHAR->sort = $sort;
								
								try {
										$CHAR->save();
								} catch (Exception $e) {
										echo 'Caught exception: ',  $e->getMessage(), "\n";
								}///////////////////
							}/////////else {///if1
						}//////if ($CHAR != NULL) {
					endforeach;
				}//////////кЛАССИЧЕСКИЙ ВАРИАНТ ОДНА ОПЦИЯ К ОДНОЙ ГРУППЕ	
		}////////////////if(isset($delete_category)==false) { 
		elseif(isset($delete_category)==true) {////////////////////Удаление и переход к вашестоящей
			$parent = $CAT->parent;
			//$CAT->move($CAT->parent)
			
			
			
			$CAT->deleteChildCategories();
			$CAT->delete();
			$this->redirect(Yii::app()->request->baseUrl."/adminproducts/group/$parent", true, 301);
			exit();
		}//////////
		
			
	}///////////if (isset($CAT)) {/////////////////////
	
	if (isset($_POST['add_char'])) {////////////////////////////////////////////////////////Добавляем новый параметр в группе///////////////////////////////////////////
	if(isset(Yii::app()->params['group_characteristics_mode']) AND Yii::app()->params['group_characteristics_mode']=='multi') {
		if ( $_POST['add_char']!=0 AND trim($_POST['add_char'])!='') {////////////////////////////////////////////////////////Добавляем новый параметр в группе ///////////////////////////////////////////
			$add_char_count = Yii::app()->getRequest()->getParam('add_char_count');
					if (isset($add_char_count)) {
						for ($i=0; $i<$add_char_count; $i++) {	
						$NEW_CHAR	= new Characteristics;
						$NEW_CHAR->caract_category = $gr_id;
						$NEW_CHAR->char_type =$_POST['add_char'];
						try {
									$NEW_CHAR->save();
									} catch (Exception $e) {
											echo 'Caught exception: ',  $e->getMessage(), "\n";
									}///////////////////
						if ($NEW_CHAR!=NULL) {/////////////Пишем в таблицу связи групп и характеристик
								$CC = new Characteristics_categories;
								$CC->characteristics_id = $NEW_CHAR->caract_id;
								$CC->categories_id = $gr_id;
								try {
											$CC->save();
									} catch (Exception $e) {
											echo 'Caught exception: ',  $e->getMessage(), "\n";
									}///////////////////
						}//////////////////////if ($NEW_CHAR!=NULL) {/////////////Пише
					}
				}
			}///////////////if (isset($_POST['add_char'])) {///////////////////////////////Добавляем новый параметр в группе//////////////////////
	}//////////'group_characteristics_mode']=='multi') {
	else {////////////кЛАССИЧЕСКИЙ ВАРИАНТ ОДНА ОПЦИЯ К ОДНОЙ ГРУППЕ
				$NEW_CHAR	= new Characteristics;
				$NEW_CHAR->caract_category = $gr_id;
				$NEW_CHAR->is_common=0;
				try {
									$NEW_CHAR->save();
							} catch (Exception $e) {
									echo 'Caught exception: ',  $e->getMessage(), "\n";
							}///////////////////
	}/////////else {
	}///////////////if (isset($_POST['add_char'])) {///////////////////////////////Добавляем новый параметр в группе//////////////////////
	
		if (isset($_POST['add_existing_characteristic'])) {/////////////////////Добавление массовое из уже существующих
			//print_r($_POST['add_existing_characteristic']);
			//exit();
					//for ($i=0; $i<count($_POST['add_existing_characteristic']); $i++) {
						
					///////	$gr_id	
						
					foreach ($_POST['add_existing_characteristic'] AS $sourse_rg=>$chars_arrrrr){
						
							foreach	($_POST['add_existing_characteristic'][$sourse_rg] AS $charact_id=>$val) {
										$criteria=new CDbCriteria;
										$criteria->condition = " t.categories_id = :gr_id  AND t.characteristics_id = :charact_id";
										$criteria->params  = array(':gr_id'=>$sourse_rg	, ':charact_id'=>$charact_id);
										$SC = Characteristics_categories::model()->find($criteria);
										
										$CC = new Characteristics_categories;
										//$CC->characteristics_id = $_POST['add_existing_characteristic'][$i];
										$CC->characteristics_id = $charact_id;
										$CC->categories_id = $gr_id;
										if (isset($SC)){
											 $CC->sort = $SC->sort;
											$CC->sort_table =$SC->sort_table;
											$CC->char_descr =$SC->char_descr;
											$CC->char_descr_title = $SC->char_descr_title;
											$CC->use_category_descr =$SC->use_category_descr;
											$CC->is_argument = $SC->is_argument;
											$CC->table_form_name = $SC->table_form_name;
											$CC->product_frontend_name = $SC->product_frontend_name;
										}//////if (isset($SC)){
										try {
													$CC->save();
											} catch (Exception $e) {
													echo 'Caught exception: ',  $e->getMessage(), "\n";
											}///////////////////
							}/////////////foreach	($_POST['add_existing_characteristic'] AS $charact_id=>$val) {
					}/////////foreach ($_POST['add_existing_characteristic'] AS $sourse_rg=>$char
	}/////////////////////////////if (isset($_POST[add_existing_characteristic])) {/////////////////////Добавление массовое 
	
	
	/////////////////////////////// /////////////////////////////////// ///////////////////////////////////
	if (isset($_FILES)) {//////////Загрузка главной картинки
	//print_r($_FILES);
					if (isset($_FILES['addfile'])){
							$downloaded_file = $_FILES['addfile'];
							if (trim($downloaded_file['tmp_name'])) {//////////////если файл был передан
							
							//////////////////////Делаем соответствующие записи
							$NEW_PICT = new Pictures;
							$NEW_PICT->type=2; ///////////////////////файл с прайсом для группы
							try {
									$NEW_PICT->save();
							} catch (Exception $e) {
									 echo 'Caught exception: ',  $e->getMessage(), "\n";
							}//////////////////////			
							
							//$size = getimagesize($new_file_name);
							//if ($size[2] == "1"){$extension = "gif";} 
							//if ($size[2] == "2"){$extension = "jpg";} 
							//if ($size[2] == "3"){$extension = "png";} 
							$filenameparts = explode('.', $downloaded_file['name']);
							$extension = 	$filenameparts[count($filenameparts)-1];		
										
							$new_file_name = $_SERVER['DOCUMENT_ROOT'].Yii::app()->request->baseUrl.'/prices/'.$NEW_PICT->id.'.'.$extension;
							//$new_file_name2 = $_SERVER['DOCUMENT_ROOT'].Yii::app()->request->baseUrl.'/pictures/add/'.$NEW_PICT->id.".";
							move_uploaded_file($downloaded_file['tmp_name'], $new_file_name);
							
							  //print_r($size);
							  if ($extension) {
							  			$NEW_PICT->ext=$extension;
										$NEW_PICT->description=$downloaded_file['name'];
										try {
												$NEW_PICT->save();
										} catch (Exception $e) {
												 echo 'Caught exception: ',  $e->getMessage(), "\n";
										}//////////////////////	
										
										if (file_exists($new_file_name)) {///////////Если существующий файл существует
													//@unlink ($new_file_name2.$extension);
													//rename ($new_file_name, $new_file_name2.$extension);
													
													$PICT_PROD = new Picture_category;
													$PICT_PROD->category_belong = $gr_id;
													$PICT_PROD->picture = $NEW_PICT->id;
													
													try {
															$PICT_PROD->save();
													} catch (Exception $e) {
															 echo 'Caught exception: ',  $e->getMessage(), "\n";
													}
													
											}/////////////////////////////////
								   }////////if ($extension) {
								  // else {/////////Если тип файла не  совпадает с одним из трех
									//			@unlink($new_file_name);
									//	}/////////////////// else {/////////Если тип файла не  сов
							}/////////////if (isset($_FILES['addfile'])){
						}////////if (isset($dowloaded_files['tmp_name'])) {
	}////////////////////////if (isset($_FILES)) {//////////
	
	
	if (isset($_POST['delete_file'])) {//////////////Удаление прикрепленных файлов
			//print_r($_POST['delete_file']);
			foreach($_POST['delete_file'] as $file_id=>$val) {
				//$criteria=new CDbCriteria;
				$params=array('picture'=>$file_id, 'category_belong'=>$gr_id);
				$picture = Pictures::model()->findbyPk($file_id);
				if ($picture !=NULL) {
				
					$picture_category = Picture_category::model()->findByAttributes($params);
					 try {
								$picture_category->delete(false);
								
								} catch (Exception $e) {
										echo 'Caught exception: ',  $e->getMessage(), "\n";
								}///////////////////
					@unlink($_SERVER['DOCUMENT_ROOT'].Yii::app()->request->baseUrl.'/prices/'.$file_id.'.'.$picture->ext);
					 try {
								$picture->delete(false);
								
								} catch (Exception $e) {
										echo 'Caught exception: ',  $e->getMessage(), "\n";
								}///////////////////
					
				}///////////////if ($picture !=NULL) {
			}////////////foreach($_POST['delete_file'] as $file->id) {
	}/////////////if (isset($_POST['delete_file'])) {//////////////Удаление прикрепленных файлов
	
	
	
	if (isset($_POST['delete_link'])) {////Удаление связи с файлом в текущей группе
			foreach($_POST['delete_link'] as $file_id=>$val) {
				$params=array('picture'=>$file_id, 'category_belong'=>$gr_id);
				$picture_category = Picture_category::model()->findByAttributes($params);
					 try {
							$picture_category->delete(false);
							
							} catch (Exception $e) {
									echo 'Caught exception: ',  $e->getMessage(), "\n";
							}///////////////////
			}/////////foreach($_POST['delete_link'] as $file_id=>$val) {
	}////////////if (isset($_POST['delete_link'])) {//
	
	////////////////////////////// /////////////////////////////////// ///////////////////////////////////
	
	//////////////////////////////////////////////////////////////////////////////Создание подчиненной группы
	if(isset($_POST['new_sub'])) {

			$NGR = new Categoriestradex;
			$NGR->category_name = 'Новая категория';
			$NGR->parent = $gr_id;
					  try {
									$NGR->save();
							} catch (Exception $e) {
									echo 'Caught exception: ',  $e->getMessage(), "\n";
									exit();
							}///////////////////
	}//////////if($_POST['new_sub']) {
	
			if(isset($_POST['del_logo'])) {/////////////////////Удаление картинки
						$new_file_name = $_SERVER['DOCUMENT_ROOT'].Yii::app()->request->baseUrl."/pictures/group_ico/".$CAT->category_id.'.png';	
						@unlink($new_file_name);
						$new_file_name = $_SERVER['DOCUMENT_ROOT'].Yii::app()->request->baseUrl."/pictures/group_ico/".$CAT->category_id.'.jpg';	
						@unlink($new_file_name);
						
				}///////////////if(isset($_POST[del_logo])) {/////////////////////Удалени
	
	if(isset($_FILES['logo'])) {//////////////Логотип компании
					$downloaded_file = $_FILES['logo'];
							if (trim($downloaded_file['tmp_name'])) {//////////////если файл был передан
							
									$size = getimagesize($downloaded_file['tmp_name']);
									//echo $downloaded_file['tmp_name'];
									 if ($size[2] == "1")$extension = "gif";
									 if ($size[2] == "2")$extension = "jpg";
									 if ($size[2] == "3")	$extension = "png";
											
											$pic_id_name =  $CAT->category_id.'.'.$extension;	
											//echo '<pre>';
											//print_r($size);
											//echo '</pre>';
											if (isset(Yii::app()->params['group_logo_x_limit'])) $limit_x = Yii::app()->params['group_logo_x_limit'];
											else $limit_x=140;
											if (isset(Yii::app()->params['group_logo_y_limit'])) $limit_y = Yii::app()->params['group_logo_y_limit'];
											else $limit_y=140;
											
											if ($size[0]<=$limit_x  AND $size[1]<=$limit_y) {
												$new_file_name = $_SERVER['DOCUMENT_ROOT'].Yii::app()->request->baseUrl."/pictures/group_ico/".$pic_id_name;
												//echo $new_file_name;
												@unlink($new_file_name);
												@move_uploaded_file($downloaded_file['tmp_name'], $new_file_name);
												//$contragent->image = $pic_id_name;
												
											}///////////
											else {
													$err =  "Логотип должнен быть не более $limit_x х $limit_y пикселей";
													Yii::app()->user->setFlash('grouplogoerror', $err);
													//exit();
											}
											//echo $new_file_name;
										
							}//////////////if (trim($downloaded_file['tmp_name'])) {//////////////если файл был пере
			}///////	if(isset($_FILES[logo])) {
	
		if (isset($_POST['apply_group_params'])) $this->redirect(Yii::app()->createUrl("/adminproducts/group", array('id'=>$gr_id, 'open_params'=>1)), true, 301);
	else 	 $this->redirect(Yii::app()->request->baseUrl."/adminproducts/group/$gr_id", true, 301);
	 exit;
	}///////////public function actionUpdategroup(){
	
	
	public function actionUpdategrouplist(){//////////////////Обновление статусов групп из списка/////////////////////////////////////////////////////
			$gr_id = Yii::app()->getRequest()->getParam('id', NULL);
			
			if(isset($_POST['new_main_category'])) {/////////////////Создание новой группы
					$NEW_GR =  new Categoriestradex;
					if (isset($gr_id) AND intval($gr_id)) $NEW_GR->parent = $gr_id;
					else $NEW_GR->parent = 0;
					$NEW_GR->category_name= 'Новая группа';
					$NEW_GR->show_category = 0;
					try {
									$NEW_GR->save();
							} catch (Exception $e) {
									echo 'Caught exception: ',  $e->getMessage(), "\n";
							}///////////////////
			}////////////if(isset($_POST['new_main_category'])) {////////////////
			
			$criteria=new CDbCriteria;
			$criteria->order = 'category_name';
			if (@isset($gr_id) AND @intval($gr_id)>0) $criteria->condition = " t.parent = ".$gr_id;
			else $criteria->condition = " t.parent = 0 ";
			//$criteria->params = array(':SearchValue' => '%'.$search_field.'%'  );
			$gruppy = Categoriestradex::model()->findAll($criteria);//
					
			for($i=0; $i<count($gruppy); $i++) {
					$GR = Categoriestradex::model()->findbyPk($gruppy[$i]->category_id);
					if (isset ($GR)) {
							if (isset($_POST['show_category'][$gruppy[$i]->category_id])) $show_category = 1;
							else $show_category = 0;
							$GR->show_category = $show_category;
							
							if (isset($_POST['sort_category'][$gruppy[$i]->category_id])) $sort_category = $_POST['sort_category'][$gruppy[$i]->category_id];
							else $sort_category = 0;
							$GR->sort_category = $sort_category;
							try {
									$GR->save();
								} catch (Exception $e) {
									echo 'Caught exception: ',  $e->getMessage(), "\n";
								}///////////////////
					}
			}	/////////////for($i=0; $i<count($gruppy); $++) {			
				
			if (@isset($gr_id) AND @intval($gr_id)>0) $this->redirect(Yii::app()->request->baseUrl."/adminproducts/group/$gr_id", true, 301);
			else  $this->redirect(Yii::app()->request->baseUrl."/adminproducts/", true, 301);
			exit;
	}////////////////////////////////////////////////////////////////////Обновление статусов групп из списка/////////////////////////////////////////////////////
	
	public function actionProduct() { ///////////////////////////////////////////Вывод инфо о товаре//////////////////////////////////////////
	/////////////id - идентификатор товара
	//print_r($_GET);
	//echo '<br>';
	//echo 'werwer';
			//$this->layout="admin";
			$time1=microtime(true);
			
	
	
	//echo 'group = '.Yii::app()->getRequest()->getParam('group');
			$char_filter = Yii::app()->getRequest()->getParam('char_filter');
			$pr_id = Yii::app()->getRequest()->getParam('id');
			$gr_id = Yii::app()->getRequest()->getParam('group');
			$path_text = $this->get_productiya_path ($gr_id);
			$child_id = Yii::app()->getRequest()->getParam('child_id');
			
			if(isset(Yii::app()->urlManager->urlSuffix)) $gr_id=str_replace(Yii::app()->urlManager->urlSuffix, '', $gr_id);
			//print_r(Yii::app()->urlManager);
			
			///////////Идентификатор выбранного дочернего товара
			
			$criteria=new CDbCriteria;
			$criteria->order = 'product_name';
			$criteria->condition = " category_belong = ".$gr_id;
			
			if (isset($char_filter) AND trim($char_filter) !='' AND $char_filter !='0' )  {
						//echo 'qweweq';
						$criteria->addCondition("char_val.value = :char_filter");
						$params[':char_filter']=$char_filter; 
					}
			if (isset($params)) $criteria->params = $params;
			
			$products_in_gr = Products::model()->with('char_val', 'card_prices')->findAll($criteria);//
			if (isset($products_in_gr)) $goods1_list = CHtml::listdata($products_in_gr, 'id', 'id');
			
			/*
			$time2=microtime(true);
			echo ($time2- $time1);
			echo '<br>';
			*/
			
			
			$criteria=new CDbCriteria;
			//$criteria->order = 'product_name';
			$criteria->condition = " categories_products.group = :parent ";
			$params=array(':parent'=>$gr_id);
			
			if (isset($char_filter) AND trim($char_filter) !='' AND $char_filter !='0' )  {
						//echo 'qweweq';
						$criteria->addCondition("char_val.value = :char_filter");
						$params[':char_filter']=$char_filter; 
					}
			
			if (isset($goods1_list)) {
							$qqq = array_keys($goods1_list);
							if (isset($qqq) AND empty($qqq)==false) $criteria->addCondition("t.id NOT IN (".implode(',', $qqq).")");
					}		
			
			 $criteria->params = $params;
			
			$goods2= Products::model()->with('categories_products', 'char_val')->findAll($criteria);// это товары у которых непосредственно указанно что они входят в данную категорию	
			$products_in_gr = array_merge ($products_in_gr, $goods2);
			/*
			$time2=microtime(true);
			echo ($time2- $time1);
			echo '<br>';
			*/
			
			
			$product = Products::model()->with('files')->findbyPk($pr_id);//
			
			
			
			
			$measures = Measures::model()->findAll();
			
			$criteria=new CDbCriteria;
			$criteria->order = ' t.category_name ';
			$criteria->condition = " 	t.parent = :root";
			//if (isset(Yii::app()->params['main_tree_root'])) $root = Yii::app()->params['main_tree_root'];
			//else 
			$root = 0;
			$criteria->params=array(':root'=>$root);
			$all_groups = Categories::model()->with('child_categories')->findAll($criteria);//
			
			//$criteria=new CDbCriteria;
			//$criteria->order = ' t.id DESC ';
			//$criteria->condition = " picture_product.product = ".$pr_id;
			//$linked_pictures = Pictures::model()->with('picture_product')->findAll($criteria);//
			$criteria=new CDbCriteria;
			$criteria->order = ' t.id DESC ';
			$criteria->condition = " t.product = ".$pr_id;
			$linked_pictures = Picture_product::model()->with('img')->findAll($criteria);//
			
			/////////////////////////////////Вытаскиваем совместимые товары
			$criteria=new CDbCriteria;
			$criteria->order = ' t.product ';
			$criteria->condition = " t.product = ".$pr_id;
			$compabile= Products_compability::model()->with('compprod')->findAll($criteria);//
			
			/////////////////////Вытаскиваем совместимые группы
			$criteria=new CDbCriteria;
			$criteria->order = ' t.product ';
			$criteria->condition = " t.product = ".$pr_id;
			$compabile_categories= Products_categories_compability ::model()->with('compcategories')->findAll($criteria);//
			
			
			///////////////////////Выбираем комплекты
			$criteria=new CDbCriteria;
			$criteria->order = ' t.product ';
			$criteria->condition = " t.product = ".$pr_id;
			$packs= Products_packs::model()->with('packed')->findAll($criteria);//
			
			//echo 'werwer';
			
				if (isset(Yii::app()->params['second_tree_root'])) {
					//////////////Вытаскиваем список групп для 2го  дерева
					$criteria=new CDbCriteria;
					$criteria->order = 't.sort_category';
					$criteria->condition = " t.parent =  :root AND t.show_category = 1  ";
					$criteria->params=array(':root'=>Yii::app()->params['second_tree_root']);
					$second_tree = Categories::model()->with('child_categories')->findAll($criteria);//
					$second_tree_list=CHtml::listData($second_tree,'category_id','category_name');
								
					$second_tree_list = array('0'=>"...выберети тип продукции")+$second_tree_list;
					
				/*	
					$time2=microtime(true);
			echo ($time2- $time1);
			echo '<br>';
			*/
					
					//////////Выесняем в какую вторую группу входит товар
					$criteria=new CDbCriteria;
					
					$categories_products_second = Categories_products::model()->findAllByAttributes(array('product'=>$pr_id, 'type'=>2));
					if (isset($categories_products_second)) for($j=0; $j<count($categories_products_second); $j++) $second_tree_list_array[]=$categories_products_second[$j]->group;
					
				}//////////////////
			
				if (isset(Yii::app()->params['main_tree_root'])) {
					//////////////Вытаскиваем список групп для 1го  дерева
					$criteria=new CDbCriteria;
					$criteria->order = 't.sort_category';
					$criteria->condition = " t.parent =  :root AND t.show_category = 1  ";
					
					$criteria->params=array(':root'=>Yii::app()->params['main_tree_root']);
					$first_tree = Categories::model()->with('child_categories')->findAll($criteria);//
					//$main_tree_list=CHtml::listData($first_tree,'category_id','category_name');
					for ($i=0; $i<count($first_tree); $i++) {
						$main_tree_list[$first_tree[$i]->category_id]=$first_tree[$i]->category_name;
						if (isset($first_tree[$i]->child_categories)) {
								for ($k=0; $k<count($first_tree[$i]->child_categories); $k++) {
										$main_tree_list[$first_tree[$i]->child_categories[$k]->category_id]='---'.$first_tree[$i]->child_categories[$k]->category_name;
								}///////////for ($k=0; $k<count($fi
						}///////	if (isset($first_tree[$i
					}/////////
					$main_tree_list = array_merge(array('0'=>"...выберите марку"),$main_tree_list);
					//print_r($second_tree_list);
			
					//////////Выесняем в какую вторую группу входит товар
					$criteria=new CDbCriteria;
					
					$categories_products_main = Categories_products::model()->with('category')->findAllByAttributes(array('product'=>$pr_id, 'type'=>1));
				}//////////////////	if (isset(Yii::app()->params['main_tree_root'])) {
				
				///////////////////////////////////Вытаскивем параметры для данной группы(характеристики)
			if(isset(Yii::app()->params['group_characteristics_mode']) AND Yii::app()->params['group_characteristics_mode']=='multi') {
				$criteria=new CDbCriteria;
				$criteria->condition = "characteristics_categories.categories_id = :gr_id ";
				if(isset(Yii::app()->params['bad_parametrs']) AND empty(Yii::app()->params['bad_parametrs'])==false) $criteria->condition.= " AND t.caract_id NOT IN(".implode(',', Yii::app()->params['bad_parametrs']).")"; 
				$criteria->params=array(':gr_id'=>$gr_id);
				$parametrs = Characteristics::model()->with('characteristics_categories')->findAll($criteria);//
			}
			else {
					$criteria=new CDbCriteria;
					$criteria->condition = "( caract_category = ".$gr_id." OR is_common = 1 )";
					if (isset( $second_tree_list_array) AND empty($second_tree_list_array)==false) $criteria->addCondition("caract_category IN (".implode(',', $second_tree_list_array).")", ' OR '); 
					$parametrs = Characteristics::model()->findAll($criteria);//

			}
			/*
			$time2=microtime(true);
			echo 'деревья: ';
			echo ($time2- $time1);
			echo '<br>';
			*/
					
			
			
			
			///////////////////////////////Вытаскиваем список параметров заданных для данного товара
			$criteria=new CDbCriteria;
			//$criteria->order = ' t.id DESC ';
			$criteria->condition = " values.id_product = ".$pr_id;
			if(isset(Yii::app()->params['bad_parametrs']) AND empty(Yii::app()->params['bad_parametrs'])==false) $criteria->condition.= " AND t.caract_id NOT IN(".implode(',', Yii::app()->params['bad_parametrs']).")"; 
			// " AND t.caract_id !=725";
			$parametrs_product = Characteristics::model()->with('values')->findAll($criteria);//
			 for($i=0; $i<count($parametrs_product);$i++) $parametrs_id_arr[]=$parametrs_product[$i]->caract_id;
			
			/*
			$time2=microtime(true);
			echo 'характеристики: ';
			echo ($time2- $time1);
			echo '<br>';
			*/
					
			
			if (isset($parametrs_id_arr)) {
			///////////////////////Выбираем список возможных значений для параметров	
			$criteria=new CDbCriteria;
			$criteria->distinct = true;
			//$criteria->group = "  value ";
			$criteria->condition = " id_caract IN(".implode(',', $parametrs_id_arr).') ';
			if(isset(Yii::app()->params['group_characteristics_mode']) AND Yii::app()->params['group_characteristics_mode']=='multi') $criteria->condition.= ' AND t.id_product IN('.implode(',', array_keys(CHtml::listdata($products_in_gr, 'id', 'id'))).') ';
			if(isset(Yii::app()->params['bad_parametrs']) AND empty(Yii::app()->params['bad_parametrs'])==false) $criteria->condition.= " AND id_caract NOT IN(".implode(',', Yii::app()->params['bad_parametrs']).")"; //  AND id_caract != 725';
			$criteria->order = "t. value ";
			$parametrs_values = Characteristics_values::model()->findAll($criteria);//
			}////////if (isset($parametrs_id_arr)) {
			else $parametrs_id_arr=NULL;
			/*
			$time2=microtime(true);
			echo 'характеристики 2: ';
			echo ($time2- $time1);
			echo '<br>';
			*/
			
			
			//////////////////////Вытаскиваем список складов
			$criteria=new CDbCriteria;
			if(isset(Yii::app()->params['self_contragent']))$criteria->condition = "t.kontragent_id = ".Yii::app()->params['self_contragent'];
			else $criteria->condition = "t.kontragent_id = 1";
			$stores=Stores::model()->findAll($criteria);
			$stores_list[0]='...выбор склада';
			for ($i=0; $i<count($stores); $i++) $stores_list[$stores[$i]->id]=$stores[$i]->name;
			/////////////////////////////////////////////////////////////////////////////////////////////////////////////
			
			///////////////////Вытаскиваем остатки из тригееров
			$criteria=new CDbCriteria;
			$criteria->condition = "t.tovar = :tovar";
			$criteria->params=array(':tovar'=>$pr_id);
			$triggers = Ostatki_trigers::model()->findAll($criteria);
			/*
			$time2=microtime(true);
			echo 'остатки: ';
			echo ($time2- $time1);
			echo '<br>';
			*/
			
			////////////////////////////////////////////////////////////////////
			
			
			
			$childs_id_modwls = Products::model()->findAllByAttributes(array('product_parent_id'=>$pr_id));
			if(isset($childs_id_modwls))$childs_id = CHtml::listdata($childs_id_modwls, 'id', 'id');
			
			if(isset($childs_id) AND empty($childs_id)==false) {
				
					$criteria=new CDbCriteria;
					$criteria->select=array( 't.*',  'attribute_value AS attribute_value');
					//$criteria->together = true;
					/*
					$criteria->join ="JOIN (
		
		SELECT * 
		FROM  products WHERE 
		)parent_products ON parent_products.id = t.product_parent_id
		LEFT JOIN (
		*/
		$criteria->join =" LEFT JOIN (
		SELECT id_product, GROUP_CONCAT( value ) AS attribute_value
		FROM `characteristics_values`  WHERE characteristics_values.id_product IN(".implode(',', $childs_id).") 
		GROUP BY id_product
		)product_attribute ON product_attribute.id_product = t.id
		";
					$criteria->condition = "t.id IN(".implode(',', $childs_id).")";
					//$criteria->params=array(':tovar'=>$pr_id);
					
					$childs = Products::model()->findAll($criteria); ///////////////Список детей
					
			}
			///////////////////////Вытаскиваем список подчиненных товаров
			/*
			$time2=microtime(true);
			echo 'подчиненные: ';
			echo ($time2- $time1);
			echo '<br>';
			*/
			
			
			/////////////////////////////////\/Вытаскиваем список подчиненных товаров конец
			
			/////////////////////////////Список параметров для выбранного подчиненного
			if ($child_id>0) {
			$criteria=new CDbCriteria;
			//$criteria->order = ' t.id DESC ';
			$criteria->condition = " values.id_product = ".$child_id;
			$childs_params = Characteristics::model()->with('values')->findAll($criteria);//
			 for($i=0; $i<count($childs_params);$i++) $child_parametrs_id_arr[]=$childs_params[$i]->caract_id;
			
			if (isset($child_parametrs_id_arr)) {
			///////////////////////Выбираем список возможных значений для параметров	
			$criteria=new CDbCriteria;
			$criteria->distinct = true;
			//$criteria->group = "  value ";
			$criteria->condition = " id_caract IN(".implode(',', $child_parametrs_id_arr).') ';
			$child_parametrs_values = Characteristics_values::model()->findAll($criteria);//
			}////////if (isset($parametrs_id_arr)) {
			else $child_parametrs_id_arr=NULL;
			
			}//////////////////if ($child_id>0) {//////////Список параметров для выбранного подчиненного
			
			/*
			$time2=microtime(true);
			echo 'конец: ';
			echo ($time2- $time1);
			echo '<br>';
			*/
			
			
			//$this->layout="admin";
			//echo 'ewrwer';
			//exit();
			
			//$this->render('test');
			
			$currencies = CHtml::listdata(Currencies::model()->findAll(), 'currency_id', 'currency_code');
			$currencies[0]='Не задано';

			
			$this->render('product-main', array(
			    'product'=>$product, 
			    'products_in_gr'=>$products_in_gr, 
			    'group'=>$gr_id,'path_text'=>$path_text, 
			    'measures'=>$measures, 
			    'all_groups'=>$all_groups , 
			    'linked_pictures'=>$linked_pictures, 
			    'parametrs'=>$parametrs, 
			    'parametrs_product'=>$parametrs_product,
			    'parametrs_values'=>@$parametrs_values, 
			    'compabile'=>$compabile,  
			    'stores_list'=>$stores_list, 
			    'triggers'=>$triggers, 
			    'childs'=>@$childs, 
			    'child_id'=>$child_id , 
			    'childs_params'=>@$childs_params, 
			    'child_parametrs_values'=>@$child_parametrs_values, 
			    'second_tree_list'=>@$second_tree_list, 
			    'categories_products_second'=>@$categories_products_second, 
			    'main_tree_list'=>@$main_tree_list, 
			    'categories_products_main'=>@$categories_products_main, 
			    'packs'=>@$packs, 'compabile_categories'=>@$compabile_categories, 
			    'currencies'=>$currencies		    
			) );
			
	}////////////////////////////////////////////////////////////////////////////////Вывод инфо о товаре//////////////////////////////////////////
	
	public function actionProduct_update_main() {/////////////////Обновление основных параметров товара со вью product-descr
	$gr_id = Yii::app()->getRequest()->getParam('group');
	$pr_id = Yii::app()->getRequest()->getParam('id');
	$char_filter = Yii::app()->getRequest()->getParam('char_filter');
	$activetab = Yii::app()->getRequest()->getParam('activetab');
	
	
	//print_r($_POST);
	//exit();
	
	$del_second_linked_tree =  Yii::app()->getRequest()->getParam('del_second_linked_tree');
	$del_main_linked_tree =  Yii::app()->getRequest()->getParam('del_main_linked_tree');
	
	$product_card_prices = Yii::app()->getRequest()->getParam('CardPrices', NULL); 

	$price_variations = Yii::app()->getRequest()->getParam('priceVar', NULL); 

	
	$product = Products::model()->findbyPk($pr_id);//
	if(isset($_POST['save_main_parametrs'])) {////////////Обновление данных на первой вкладке
			if (isset($product)) {
					$product->product_name = Yii::app()->getRequest()->getParam('product_name', NULL);
					$product->product_article = Yii::app()->getRequest()->getParam('product_article', NULL);
					
					//echo Yii::app()->getRequest()->getParam('product_article', NULL);
					//exit();
					
					$product->category_belong = Yii::app()->getRequest()->getParam('category_belong', $gr_id);
					//$product->product_vitrina = Yii::app()->getRequest()->getParam('product_vitrina', 0);
					if (Yii::app()->getRequest()->getParam('product_visible')) $product_visible=1;
					else $product_visible=0;
					$product->product_visible = $product_visible;
					
					if (Yii::app()->getRequest()->getParam('product_new')) $product_new=1;
					else $product_new=0;
					$product->product_new = $product_new;
					
					$sellout_active_till = Yii::app()->getRequest()->getParam('sellout_active_till', NULL);
					//echo $vitrina_active_till;
					if (isset($sellout_active_till)) {//////////////////Дата, до которой будет отображаться объявление на витрине
							if (trim($sellout_active_till)<>'') {
									$sellout_active_till_arr = split("-", $sellout_active_till );
									$sellout_active_till_int = mktime(23, 59, 59, $sellout_active_till_arr[1],$sellout_active_till_arr[0], $sellout_active_till_arr[2] ); ///////////////////////md
							} 
							else $sellout_active_till_int = NULL;
					}
					else $sellout_active_till_int = NULL;
					$product->sellout_active_till_int = $sellout_active_till_int;
					
					if (Yii::app()->getRequest()->getParam('product_vitrina')) $product_vitrina=1;
					else $product_vitrina=0;
					
					$product->product_vitrina_sort = Yii::app()->getRequest()->getParam('product_vitrina_sort', NULL);
					$product->product_new_sort = Yii::app()->getRequest()->getParam('product_new_sort', NULL);
					$product->product_sellout_sort = Yii::app()->getRequest()->getParam('product_sellout_sort', NULL);
					
					$product->vitrina_key_1 = Yii::app()->getRequest()->getParam('vitrina_key_1', NULL);
					$product->vitrina_key_1_sort  = Yii::app()->getRequest()->getParam('vitrina_key_1_sort', NULL);
					$product->vitrina_key_1_price = Yii::app()->getRequest()->getParam('vitrina_key_1_price', NULL);
					
					$product->sort = Yii::app()->getRequest()->getParam('sort', NULL);
					
				
					if (Yii::app()->getRequest()->getParam('product_sellout')) $product_sellout=1;
					else $product_sellout=0;
					$product->product_sellout = $product_sellout;
					$product->product_vitrina = $product_vitrina;
					$product->product_price	 = Yii::app()->getRequest()->getParam('product_price', 99999);
					$product->product_price_recomended	 = Yii::app()->getRequest()->getParam('product_price_recomended', NULL);
					$product->product_price_old	 = Yii::app()->getRequest()->getParam('product_price_old', NULL);
					$product->number_in_store= Yii::app()->getRequest()->getParam('number_in_store', 0);
					
										
		
					$product->sellout_price = Yii::app()->getRequest()->getParam('sellout_price', NULL);
					$product->measure = Yii::app()->getRequest()->getParam('measure', NULL);
					$product->nds_out = Yii::app()->getRequest()->getParam('nds_out', '0.18');
					
					 try {
					 		//echo 'qweqwe';
					 		//print_r($product->attributes());
					 	//echo $product->product_name;
					 	
					 	//exit();
								$product->save();
								
								///////////Обновляем цены по дисконтным картам
								
								
							} catch (Exception $e) {
								 echo 'Caught exception: ',  $e->getMessage(), "\n";
								 exit();
							}//////////////////////
					
					if($product_card_prices!=NULL) $this->updateDiscountCardPrices($product->id, $product_card_prices);
							
					$category_belong2 = Yii::app()->getRequest()->getParam('category_belong2', NULL);
					if ($category_belong2 != NULL AND $category_belong2 !=0 ) {		
				
							
							$cp = new Categories_products;
							$cp->group = $category_belong2;
							$cp->product = $pr_id;
							$cp->type = 2;
							try {
									$cp->save();
								} catch (Exception $e) {
									 echo 'Caught exception: ',  $e->getMessage(), "\n";
								}//////////////////////
					}/////if ($category_belong2 != NULL) {		
					
					
					$category_belong_main = Yii::app()->getRequest()->getParam('category_belong_main', NULL);
					if ($category_belong_main != NULL AND $category_belong_main !=0 ) {		
				
							
							$cp = new Categories_products;
							$cp->group = $category_belong_main;
							$cp->product = $pr_id;
							$cp->type =1;
							try {
									$cp->save();
								} catch (Exception $e) {
									 echo 'Caught exception: ',  $e->getMessage(), "\n";
								}//////////////////////
					}/////if ($category_belong2 != NULL) {		
					
					
					if (isset($del_second_linked_tree)) {//////////Удаление из связанных групп
							foreach($del_second_linked_tree as $key=>$value) {
								$cp = Categories_products::model()->findByPk($key);
								if (isset($cp)) try {
									$cp->delete();
								} catch (Exception $e) {
									 echo 'Caught exception: ',  $e->getMessage(), "\n";
								}//////////////////////
							}//////foreach($del_secon
					}//////if (isset($del_second_linked_tree)) {//////////
					
					if (isset($del_main_linked_tree)) {//////////Удаление из связанных групп
							foreach($del_main_linked_tree as $key=>$value) {
								$cp = Categories_products::model()->findByPk($key);
								if (isset($cp)) try {
									$cp->delete();
								} catch (Exception $e) {
									 echo 'Caught exception: ',  $e->getMessage(), "\n";
								}//////////////////////
							}//////foreach($del_secon
					}//////if (isset($del_second_linked_tree)) {//////////
					
					///////////////Обработка дополнительных цен
					if($price_variations!=NULL){
					    /*
					    echo '<pre>';
					    print_r($price_variations);
					    echo '</pre>';
					    exit();
					    */
					    
					    $price_arr_for_lowest_price=array();
					    
					    foreach ($price_variations as $pricevar) {
					        $price_arr_for_lowest_price[]=$pricevar['price'];
					        $pvar = PriceVariations::model()->findByPk($pricevar['id']);
					        if($pvar!=null && $pvar->product==$product->id){
					            if(isset($pricevar['delete'])) { //////Если есть checkbox, значит удаляем запись
					                
					                try {
					                    $pvar->delete();
					                } catch (Exception $e) {
					                    echo 'Caught exception: ',  $e->getMessage(), "\n";
					                    exit();
					                }//////////////////////
					            }
					            else{
					                $pvar->volume = $pricevar['volume'];
					                $pvar->code = $pricevar['code'];
					                $pvar->price = $pricevar['price'];
					                $pvar->active = isset($pricevar['active'])?1:0;
					                
					                //print_r($pvar->getAttributes());
					                try {
					                    $pvar->save();
					                    
					                } catch (Exception $e) {
					                    echo 'Caught exception: ',  $e->getMessage(), "\n";
					                    exit();
					                }//////////////////////
					                
					            }
					            //echo $pricevar['price'].'<br>';
					        }
					    }
					    if(empty($price_arr_for_lowest_price)==false){
					        $product->product_price_old = $product->product_price;
					        $product->product_price = min($price_arr_for_lowest_price);
					        try {
					            $product->save();
					        } catch (Exception $e) {
					            echo 'Caught exception: ',  $e->getMessage(), "\n";
					            exit();
					        }//////////////////////
					    }
					    
					   // exit();
					    
					}

			}/////////if (isset($product)) {
	$activetab = "tab1";
	}/////////////////if(isset($_POST['save_main_parametrs'])) {
	if(isset($_POST['save_html_parametrs'])) {////////////Обновление данных на  вкладке  html
	if (isset($product)) {
					$product->product_full_descr= Yii::app()->getRequest()->getParam('product_full_descr', NULL);
					$product->product_short_descr= Yii::app()->getRequest()->getParam('product_short_descr', NULL);
					$product->product_html_title = Yii::app()->getRequest()->getParam('product_html_title', NULL);
					$product->product_html_keywords =  Yii::app()->getRequest()->getParam('product_html_keywords', NULL);
					$product->product_html_description =  Yii::app()->getRequest()->getParam('product_html_description', NULL);
					
					
	
					try {
							$product->save();
						} catch (Exception $e) {
							 echo 'Caught exception: ',  $e->getMessage(), "\n";
						}//////////////////////
		}////////////if (isset($product)) {
	$activetab = "tab4";
	}/////////////////////////if(isset($_POST['save_html_parametrs'])) {////////////Обновление данных на  вкладке  html
	
	//$this->redirect(Yii::app()->request->baseUrl."/adminproducts/product/$pr_id/?group=$gr_id&activetab=$activetab", true, 301);
	$url = Yii::app()->createUrl('adminproducts/product', array('id'=>$pr_id, 'group'=>$gr_id, 'char_filter'=>$char_filter, 'activetab'=>$activetab));
	$this->redirect($url, true);	
	
	}////////////////////////////////public function actionProduct_update_main() {/////////////////Обновление основных параметров товара со вью product-descr
	
	private function create_mini($outfldr, $pr_id, $resize_to){
			$outfldr = Yii::app()->request->baseUrl.'/'.$outfldr;
			$filename_gif = $_SERVER['DOCUMENT_ROOT'].Yii::app()->request->baseUrl.'/pictures/img/'.$pr_id.'.gif';
			$filename_jpg = $_SERVER['DOCUMENT_ROOT'].Yii::app()->request->baseUrl.'/pictures/img/'.$pr_id.'.jpg';
			$filename_png = $_SERVER['DOCUMENT_ROOT'].Yii::app()->request->baseUrl.'/pictures/img/'.$pr_id.'.png';
			$exist_gif = file_exists($filename_gif);
			$exist_jpg = file_exists($filename_jpg);
			$exist_png= file_exists($filename_png);
			if ($exist_gif==false AND $exist_jpg==false AND $exist_png==false) {//////
					
			}//////////if ($exist_gif==false AND $exist_jpg==false AND $exist_png==false) {//////
			else {////if ($exist_gif==false AND $exist_jpg==false AND $exist_png==false) {//////
					if ($exist_png==true) {
						$srctfile = Yii::app()->request->baseUrl.'/pictures/img/'.$pr_id.'.png';
						//$dstfile = $outfldr.'/'.$pr_id.'.png';
					}
					elseif($exist_jpg==true) {
						$srctfile = Yii::app()->request->baseUrl.'/pictures/img/'.$pr_id.'.jpg';
						//$dstfile = $outfldr.'/'.$pr_id.'.jpg';
					}
					elseif($exist_gif==true) {
						$srctfile = Yii::app()->request->baseUrl.'/pictures/img/'.$pr_idd.'.gif';
						//$dstfile = $outfldr.'/'.$pr_id.'.gif';
					}
					 
					//echo "http://".$_SERVER['HTTP_HOST']."/workflow/make_mini.php?create=1&height=100&imgname=$srctfile&outfldr=$outfldr";
					//echo "http://".$_SERVER['HTTP_HOST'].Yii::app()->request->baseUrl."/workflow/make_mini.php?create=1&$resize_to&imgname=$srctfile&outfldr=$outfldr";
					$fn = "http://".Yii::app()->params['apache_auth'].$_SERVER['HTTP_HOST'].Yii::app()->request->baseUrl."/pictures/make_mini.php?create=1&$resize_to&imgname=$srctfile&outfldr=$outfldr";
					$f=fopen($fn, 'r');/////Создаем таким образом миниатюру
					fclose($f);
					//echo $fn;
					//exit();
			}//////////else {////if ($exist_gif==false AND $exist_jpg==false AND $exist_png==false) {////
	}//////////////private function create_mini($outfldr){
	
	public function actionProduct_update_img(){///////////////Обработка загрузки картинок
	$gr_id = Yii::app()->getRequest()->getParam('group');
	$pr_id = Yii::app()->getRequest()->getParam('id');
	//print_r($_FILES);
	if(isset($_POST['process_images'])) { ////////////Обработка формы загрузки основных фотографий
			if (isset($_POST['del_img'])) {////////////Удаление главной фотографии
					@unlink($_SERVER['DOCUMENT_ROOT'].Yii::app()->request->baseUrl.'/pictures/img/'.$pr_id.'.jpg');
					@unlink($_SERVER['DOCUMENT_ROOT'].Yii::app()->request->baseUrl.'/pictures/img/'.$pr_id.'.gif');
					@unlink($_SERVER['DOCUMENT_ROOT'].Yii::app()->request->baseUrl.'/pictures/img/'.$pr_id.'.png');
			}////////////////////////////////////if (isset($_POST['del_img'])) {////////////Удаление главной фотографии
			if (isset($_POST['del_img_med'])) {////////////Удаление главной фотографии
					@unlink($_SERVER['DOCUMENT_ROOT'].Yii::app()->request->baseUrl.'/pictures/img_med/'.$pr_id.'.jpg');
					@unlink($_SERVER['DOCUMENT_ROOT'].Yii::app()->request->baseUrl.'/pictures/img_med/'.$pr_id.'.gif');
					@unlink($_SERVER['DOCUMENT_ROOT'].Yii::app()->request->baseUrl.'/pictures/img_med/'.$pr_id.'.png');
			}////////////////////////////////////if (isset($_POST['del_img'])) {////////////Удаление главной фотографии
			if (isset($_POST['del_img_small'])) {////////////Удаление главной фотографии
					@unlink($_SERVER['DOCUMENT_ROOT'].Yii::app()->request->baseUrl.'/pictures/img_small/'.$pr_id.'.jpg');
					@unlink($_SERVER['DOCUMENT_ROOT'].Yii::app()->request->baseUrl.'/pictures/img_small/'.$pr_id.'.gif');
					@unlink($_SERVER['DOCUMENT_ROOT'].Yii::app()->request->baseUrl.'/pictures/img_small/'.$pr_id.'.png');
			}////////////////////////////////////if (isset($_POST['del_img'])) {////////////Удаление главной фотографии
			
			if (isset($_POST['create_img_med'])) {//////Создание большой иконки
					$this->create_mini("pictures/img_med", $pr_id, "width=200");
			}////////////////////////////if (isset($_POST['create_img_med'])) {
			
			if (isset($_POST['create_img_small'])) {//////Создание большой иконки
					$this->create_mini("pictures/img_small", $pr_id, "height=60");
			}////////////////////////////if (isset($_POST['create_img_med'])) {
			
			if (isset($_FILES) && isset($_FILES['fileimg'])) {//////////Загрузка главной картинки
							$downloaded_file = $_FILES['fileimg'];
							if (trim($downloaded_file['tmp_name'])) {//////////////если файл был передан
							$new_file_name = $_SERVER['DOCUMENT_ROOT'].Yii::app()->request->baseUrl.'/pictures/img/'.$pr_id.'.tmp';
							$new_file_name2 = $_SERVER['DOCUMENT_ROOT'].Yii::app()->request->baseUrl.'/pictures/img/'.$pr_id.".";
							move_uploaded_file($downloaded_file['tmp_name'], $new_file_name);
							$size = getimagesize($new_file_name);
							 if ($size[2] == "1"){$extension = "gif";} 
							 if ($size[2] == "2"){$extension = "jpg";} 
							 if ($size[2] == "3"){$extension = "png";} 
							  //print_r($size);
							  if ($extension) {
										if (file_exists($new_file_name)) {///////////Если существующий файл существует
													@unlink ($new_file_name2.$extension);
													rename ($new_file_name, $new_file_name2.$extension);
											}/////////////////////////////////
								   }////////if ($extension) {
								   else {/////////Если тип файла не  совпадает с одним из трех
												@unlink($new_file_name);
										}/////////////////// else {/////////Если тип файла не  сов
						}////////if (isset($dowloaded_files['tmp_name'])) {
				}////////////////////////if (isset($_FILES)) {//////////
	$activetab = "tab2";
	}/////////////////////////if(isset($_POST['process_images'])) { ////////////Обработка формы загрузки основных фотографий
	else if(isset($_POST['process_additional_images'])) { ////////////Обработка формы загрузки доп фотографий
	//print_r($_POST);
	//echo count($_POST['create_icon']);
	if(isset($_POST['delete_icon'])) {
			if(count($_POST['delete_icon'])>0) {//
					foreach ($_POST['delete_icon'] as $key=>$value) {////////Удаление картинок
							$SRC = Pictures::model()->findbyPk($key);
									if (isset($SRC)) {
									$srctfile =  'pictures/add/'.$key.'.'.$SRC->ext;		
									$iconfile =  'pictures/add/icons/'.$key.'.png';		
									$iconsmall =  'pictures/add/icons_small/'.$key.'.png';		
									@unlink($_SERVER['DOCUMENT_ROOT'].Yii::app()->request->baseUrl.'/'.$srctfile);
									@unlink($_SERVER['DOCUMENT_ROOT'].Yii::app()->request->baseUrl.'/'.$iconfile);
									@unlink($_SERVER['DOCUMENT_ROOT'].Yii::app()->request->baseUrl.'/'.$iconsmall);
									$SRC->delete();
									$atr=array('picture'=>$key);
									$PICT_PROD = Picture_product::model()->findByAttributes($atr);//
									$PICT_PROD->delete();
							}///////////if (isset($SRC)) {
					}//////////////foreach ($_POST['delete_icon'] as $key=>$value) {////////Удаление картинок
			}////////////if(count($_POST['delete_icon'])>0) {//
	}///////////if(isset($_POST['delete_icon'])) {
	
	if (isset($_POST['comments'])){//////////////////////Комментарии к картинкам
			foreach ($_POST['comments'] as $key=>$value) {
					$PICT = Pictures::model()->findByPk($key);
					if(isset($PICT->id)) {
							if (trim($value)) $PICT->comments= trim($value);
							else  $PICT->comments= NULL;
							try {
									$PICT->save();
									} catch (Exception $e) {
									 echo 'Ошибка сохранения описания картинки: ',  $e->getMessage(), "\n";
									}//////////////////////
					}//////////if(isset($PICT->id)) {
			}//////////////foreach ($_POST['comments'] as $key=>$value) {
	}///////////////////////if (isset($_POST['comments'])){//////////////////////Комментарии к 
		
	//////////////////////Назначение главной фотографии
			$main_icon = Yii::app()->getRequest()->getParam('main_icon', NULL);	
			if ( $main_icon != NULL) {
					//print_r($main_icon);
					
					$connection=Yii::app()->db;
					$query="UPDATE picture_product SET is_main = 0 WHERE product =  $pr_id";
					$command=$connection->createCommand($query)	;
					$dataReader=$command->query();
					//$qqq = $dataReader->readAll();
					unset($qqq);
					$command->reset();
					$query="UPDATE picture_product SET is_main = 1 WHERE id = $main_icon";
					$command=$connection->createCommand($query)	;
					$dataReader=$command->query();
					//$qqq = $dataReader->readAll();
					unset($qqq);
					//
			}//////////////////////;if ($main_icon != NULL) {	
		$vitrina_icon = Yii::app()->getRequest()->getParam('vitrina_icon', NULL);	
		if ( $vitrina_icon != NULL) {
					//print_r($main_icon);
					
					$connection=Yii::app()->db;
					$query="UPDATE picture_product SET is_vitrina = 0 WHERE product =  $pr_id";
					$command=$connection->createCommand($query)	;
					$dataReader=$command->query();
					//$qqq = $dataReader->readAll();
					unset($qqq);
					$command->reset();
					$query="UPDATE picture_product SET is_vitrina = 1 WHERE id = $vitrina_icon";
					$command=$connection->createCommand($query)	;
					$dataReader=$command->query();
					//$qqq = $dataReader->readAll();
					unset($qqq);
					//
			}//////////////////////;if ($main_icon != NULL) {	
			$sellout_icon = Yii::app()->getRequest()->getParam('sellout_icon', NULL);	
			if ( $sellout_icon != NULL) {
					//print_r($main_icon);
					
					$connection=Yii::app()->db;
					$query="UPDATE picture_product SET is_sellout = 0 WHERE product =  $pr_id";
					$command=$connection->createCommand($query)	;
					$dataReader=$command->query();
					//$qqq = $dataReader->readAll();
					unset($qqq);
					$command->reset();
					$query="UPDATE picture_product SET is_sellout = 1 WHERE id = $sellout_icon";
					$command=$connection->createCommand($query)	;
					$dataReader=$command->query();
					//$qqq = $dataReader->readAll();
					unset($qqq);
					//
			}//////////////////////;if ($main_icon != NULL) {	
			
			$vitrina_key_1 = Yii::app()->getRequest()->getParam('vitrina_key_1', 0);	
			if ( $vitrina_key_1 != NULL && trim($vitrina_key_1)!='') {
					//print_r($main_icon);
					
					$connection=Yii::app()->db;
					$query="UPDATE picture_product SET vitrina_key_1 = 0 WHERE product =  $pr_id";
					$command=$connection->createCommand($query)	;
					$dataReader=$command->query();
					//$qqq = $dataReader->readAll();
					unset($qqq);
					$command->reset();
					$query="UPDATE picture_product SET vitrina_key_1 = 1 WHERE id = $vitrina_key_1";
					$command=$connection->createCommand($query)	;
					$dataReader=$command->query();
					//$qqq = $dataReader->readAll();
					unset($qqq);
					//
			}//////////////////////;if ($main_icon != NULL) {	
			
			$new_icon = Yii::app()->getRequest()->getParam('new_icon', NULL);	
			if ( $new_icon != NULL) {
					//print_r($main_icon);
					
					$connection=Yii::app()->db;
					$query="UPDATE picture_product SET is_new = 0 WHERE product =  $pr_id";
					$command=$connection->createCommand($query)	;
					$dataReader=$command->query();
					//$qqq = $dataReader->readAll();
					unset($qqq);
					$command->reset();
					$query="UPDATE picture_product SET is_new = 1 WHERE id = $new_icon";
					$command=$connection->createCommand($query)	;
					$dataReader=$command->query();
					//$qqq = $dataReader->readAll();
					unset($qqq);
					//
			}//////////////////////;if ($main_icon != NULL) {	
			
		
		
	//print_r($_POST);
	//exit();
	if( isset($_POST['create_icon']) AND count($_POST['create_icon'])>0) {/////////Пересоздаем иконки
			foreach ($_POST['create_icon'] as $key=>$value) {
				$SRC = Pictures::model()->findbyPk($key);
				if($SRC!=null){
					$srctfile =  Yii::app()->request->baseUrl."/pictures/add/".$key.'.'.$SRC->ext;
					//echo $srctfile;
					//exit();
					if (file_exists($_SERVER['DOCUMENT_ROOT'].'/'.$srctfile)) {
							
							/////////////////////////////////Большая иконка
							$resize_to = "height=180";
							$outfldr = Yii::app()->request->baseUrl."/pictures/add/icons";
							$iconfile =  Yii::app()->request->baseUrl.'/pictures/add/icons/'.$key.'.png';		
							@unlink($_SERVER['DOCUMENT_ROOT'].Yii::app()->request->baseUrl.'/'.$iconfile);
							$f=fopen("http://".Yii::app()->params['apache_auth'].$_SERVER['HTTP_HOST'].Yii::app()->request->baseUrl."/pictures/make_mini.php?create=1&$resize_to&imgname=$srctfile&outfldr=$outfldr", 'r');/////Создаем таким образом миниатюру
							//echo "http://".$_SERVER['HTTP_HOST'].Yii::app()->request->baseUrl."/workflow/make_mini.php?create=1&$resize_to&imgname=$srctfile&outfldr=$outfldr";		
							fclose($f);
							
							//////////////////Маленкая
							$resize_to = "height=100";
							$outfldr = Yii::app()->request->baseUrl."/pictures/add/icons_small";
							$iconfile =  Yii::app()->request->baseUrl.'/pictures/add/icons_small/'.$key.'.png';
							@unlink($_SERVER['DOCUMENT_ROOT'].Yii::app()->request->baseUrl.'/'.$iconfile);
							$url = "http://".Yii::app()->params['apache_auth'].$_SERVER['HTTP_HOST'].Yii::app()->request->baseUrl."/pictures/make_mini.php?create=1&$resize_to&imgname=$srctfile&outfldr=$outfldr";
							//echo $url;
							//exit();
							$f=fopen($url, 'r');/////Создаем таким образом миниатюру
							//echo "http://".$_SERVER['HTTP_HOST'].Yii::app()->request->baseUrl."/workflow/make_mini.php?create=1&$resize_to&imgname=$srctfile&outfldr=$outfldr";
							fclose($f);
							
					}/////////if (file_exists($_SERVER['DOCUMENT_ROOT'].'/'.$srctfile)) {
					unset ($SRC);
			     }
			}////////////////	foreach ($_POST['create_icon'] as $key=>$value) {
	}/////////////////////if(count($_POST['create_icon'])>0) {/////////Пересоздаем иконки
	if (isset($_FILES) AND isset($_FILES['addfileimg'])) {//////////Загрузка главной картинки
		//print_r($_FILES);
							$downloaded_file = $_FILES['addfileimg'];
							if (trim($downloaded_file['tmp_name'])) {//////////////если файл был передан
							
							//////////////////////Делаем соответствующие записи
							$NEW_PICT = new Pictures;
							$NEW_PICT->type=1;
							try {
									$NEW_PICT->save();
							} catch (Exception $e) {
									 echo 'Caught exception: ',  $e->getMessage(), "\n";
							}//////////////////////			
										
							$new_file_name = $_SERVER['DOCUMENT_ROOT'].Yii::app()->request->baseUrl.'/pictures/add/'.$NEW_PICT->id.'.tmp';
							$new_file_name2 = $_SERVER['DOCUMENT_ROOT'].Yii::app()->request->baseUrl.'/pictures/add/'.$NEW_PICT->id.".";
							move_uploaded_file($downloaded_file['tmp_name'], $new_file_name);
							$size = getimagesize($new_file_name);
							
							 if ($size[2] == "1"){$extension = "gif";} 
							 if ($size[2] == "2"){$extension = "jpg";} 
							 if ($size[2] == "3"){$extension = "png";} 
							
							//print_r($size);
							//echo "wrwerwer<br>";
							//echo count($size[1]==0);
							
							if (count($size[1]==0)) {
								//echo  $downloaded_file['name'];
								$parts=explode(".", $downloaded_file['name']);
								$extension=$parts[count($parts)-1];
							}
							
							//echo 'ext = '.$extension;
							
							
							  if ($extension) {
							  			$NEW_PICT->ext=$extension;
										try {
												$NEW_PICT->save();
										} catch (Exception $e) {
												 echo 'Caught exception: ',  $e->getMessage(), "\n";
										}//////////////////////	
										
										if (file_exists($new_file_name)) {///////////Если существующий файл существует
													@unlink ($new_file_name2.$extension);
													rename ($new_file_name, $new_file_name2.$extension);
													
													$PICT_PROD = new Picture_product;
													$PICT_PROD->product = $pr_id;
													$PICT_PROD->picture = $NEW_PICT->id;
													try {
															$PICT_PROD->save();
													} catch (Exception $e) {
															 echo 'Caught exception: ',  $e->getMessage(), "\n";
													}
													
											}/////////////////////////////////
								   }////////if ($extension) {
								   else {/////////Если тип файла не  совпадает с одним из трех
												@unlink($new_file_name);
										}/////////////////// else {/////////Если тип файла не  сов
						}////////if (isset($dowloaded_files['tmp_name'])) {
	}////////////////////////if (isset($_FILES)) {//////////
	
	$activetab = "tab3";
	}//////////////////////////////////else if(isset($_POST['process_additional_images'])) { ////////////Обрабо
	$this->redirect(Yii::app()->request->baseUrl."/adminproducts/product/$pr_id/?group=$gr_id&activetab=$activetab", true, 301);
	}///////////////////////////////public function actionProduct_update_img(){///////////////Обработка загрузки картинок
	
	
	/**
	 * Обновление списка видеороликов, подвязанных к товару
	 */
	public function actionUpdatevideo (){
		
		$product_id = json_decode(Yii::app()->getRequest()->getParam('product_id', null));
		$html = Yii::app()->getRequest()->getParam('html', null);
		$radio_status = Yii::app()->getRequest()->getParam('radio_status', null);
		$video_id = Yii::app()->getRequest()->getParam('video_id', 0); 
		
		/*
		echo '$product_id = '.$product_id.'<br>';
		echo '$html = '.$html.'<br>';
		echo '$radio_status = '.$radio_status.'<br>';
		echo '$video_id = '.$video_id.'<br>';
		*/
		
		if($video_id>0 && $product_id>0){
			$model = Products_video::model()->findByPk($video_id);	
			if($model!=null){
				$old_html = $model->html;
				$model->html=$html;
				if($radio_status!=null && $radio_status=='checked'){
					$this->setVideoNotMain($product_id);
					$model->is_main=1;
				}
				else $model->is_main=0;
				
				try {
					$model->save();
					
					if($old_html!=$html){
						/////значит нужно дать сигнал на обновления видео на форме
						echo json_encode('ok');
					}
					else echo json_encode('nochange');
					
				} catch (Exception $e) {
					echo 'Созранение видеоролика: ',  $e->getMessage(), "\n";
				}
			}
		}
	}
	
	
	/**Присваиваем всем видео заданного товара статус не главное. Должна предшествовать установке is_main
	 * @param unknown $product_id
	 */
	private function setVideoNotMain($product_id){
		//$sql = "UPDATE roducts_video SET is_main = 0 WHERE product = ".htmlspecialchars($product_id);
		//$connection=Yii::app()->db;
		//$command=$connection->createCommand($sql);
		$command = Yii::app()->db->createCommand();
		$command->update('products_video', array(
		    'is_main'=>0), 'product=:product', array(':product'=>$product_id));
	}
	public function actionUpdatepacks (){
		//print_r($_POST);
		//print_r($_GET);
		
		$gr_id = Yii::app()->getRequest()->getParam('group');
		$pr_id = Yii::app()->getRequest()->getParam('id');
		$child_id = Yii::app()->getRequest()->getParam('child_id');
		$del_packed = Yii::app()->getRequest()->getParam('del_packed');
		$productpack = Yii::app()->getRequest()->getParam('productpack');
		$packsort = Yii::app()->getRequest()->getParam('packsort');
		if (isset($pr_id)) $product = Products::model()->findByPk($pr_id);
		if (isset($child_id)) $child = Products::model()->findByPk($child_id);
		if (isset($product->id) AND isset($child->id)) {/////Добавление
			$prod_pack = new  Products_packs;
			$prod_pack->product = $product->id;
			$prod_pack->included = $child->id;
			try {
					$prod_pack->save();
					unset($prod_pack);
				} catch (Exception $e) {
					 echo 'Caught exception: ',  $e->getMessage(), "\n";
				}
		}//////if (isset($product->id
		if (isset($del_packed) AND empty($del_packed)==false) {//////удаление
			foreach($del_packed AS $product_to_del=>$val) {
				//echo $product_to_del;
				$prod_pack = Products_packs::model()->findByPk($product_to_del);
				if (isset($prod_pack->id)) {
					try {
							$prod_pack->delete();
							unset($prod_pack);
						} catch (Exception $e) {
							 echo 'Caught exception: ',  $e->getMessage(), "\n";
						}
				}
			}
		}/////if (isset($del_packed) AND empty($del_pa
		if (isset($productpack) AND empty($productpack)==false) {//////
			foreach($productpack AS $product_id=>$product_price) {
				$packprod = Products::model()->findByPk($product_id);
				if (isset($packprod->id)) {
					$packprod->product_price = $product_price;
					try {
							$packprod->save();
							unset($packprod);
						} catch (Exception $e) {
							 echo 'Caught exception: ',  $e->getMessage(), "\n";
						}
				}
			}
		}///////if (isset($productpackd) AND 
		if (isset($packsort) AND empty($packsort)==false) {
			foreach($packsort AS $pack_id=>$pack_sort) {
				$pack = Products_packs::model()->findByPk($pack_id);
				if (isset($pack->id)) {
					$pack->sort = $pack_sort;
					try {
							$pack->save();
							unset($pack);
						} catch (Exception $e) {
							 echo 'Caught exception: ',  $e->getMessage(), "\n";
						}
				}
			}	////////foreach($pack	
		}////////if (isset($packsort) AND e
		//exit();
		$url = Yii::app()->createUrl('adminproducts/product', array('id'=>$pr_id, 'group'=>$gr_id, 'activetab'=>'tab10'));
			$this->redirect($url, true);
	}
	
	public function actionManage_childs(){//////////Операции с дочерними товарами
	$gr_id = Yii::app()->getRequest()->getParam('group');
	$pr_id = Yii::app()->getRequest()->getParam('id');
	$child_id = Yii::app()->getRequest()->getParam('child_id');///////////////Идентификатор выбранного подчиненного товара
	
	
	
	if (isset($_POST['child_item_id'])) {///////////////////Перебор всех child-ов и изменение статуса
			//print_r($_POST);
			foreach($_POST['child_item_id'] as $child_item_id=>$val):
					$item=Products::model()->findByPk($child_item_id);
					if($item != NULL) {
							if (@$_POST['child_product_visible'][$child_item_id]) $visible = 1;
							else $visible = 0;
							$item->product_visible = $visible;
							try 	{
									$item->save();
									} catch (Exception $e) {
									 echo 'Ошибка сохранения статуса дочернего товара в процедуре actionManage_childs(): ',  $e->getMessage(), "\n";
									}/////////////////
					}////////////////if($item != NULL) {
			endforeach;				
			
	}//////////////if (isset($_POST['child_item_id'])) {///////////////////Перебор всех child-ов и изм
	
	
	if (isset($_POST['create_new_child_tovar']) AND $pr_id>0) {/////////////////Создаем новый товар
			$source_id=$pr_id;
			$source=Products::model()->findByPk($source_id);
				  if($source!=NULL) {
						$destanation = new Products;
						try {
							$destanation->save();
							} catch (Exception $e) {
							 echo 'Ошибка сохранения: ',  $e->getMessage(), "\n";
							}//////////////////////
						$destanation_id = $destanation->id;
						
						$attributes = $source->attributes;
						$attributes[id]=$destanation_id;
						$attributes[product_parent_id] = $pr_id;
						
						try {
							$destanation->saveAttributes($attributes);
							} catch (Exception $e) {
							 echo 'Ошибка копирования: ',  $e->getMessage(), "\n";
							}//////////////////////
					}///////////if($source!=NULL) {
	}//////////////////if (isset($_POST['create_new_child_tovar'])) {/////////////////Создаем новый товар
	
	
	if (isset($_POST['delete_child_product_rel'])) {///////////////////////Удаление связи с товаром
	//print_r($_POST);
			foreach($_POST['delete_child_product_rel'] as $key=>$val):
					$prod = Products::model()->findByPk($key);
					if ($prod != NULL) {
							$prod->product_parent_id = 0;
							try {
							$prod->save();
							} catch (Exception $e) {
							 echo 'Ошибка сохранения в процедуре actionManage_childs(): ',  $e->getMessage(), "\n";
							}//////////////////////
					}///////////if ($prod != NULL) {
			endforeach;
	} /////////////////////////////////////////////////////Удаление связи с товаром
	
	if (isset($_POST['id_child'])) {///////////////добавление связи по ид
	$id_child = Yii::app()->getRequest()->getParam('id_child');
	if ($id_child>0 AND $pr_id>0) {/////////
			$child = Products::model()->findByPk($id_child);
			if ($child!=NULL) {
					$prod = Products::model()->findByPk($pr_id);
					if ($prod != NULL) {
							$child->product_parent_id = $pr_id;
							try {
							$child->save();
							} catch (Exception $e) {
							 echo 'Ошибка указания дочернего товара actionManage_childs(): ',  $e->getMessage(), "\n";
							}//////////////////////		
					}//////////////////////if ($prod != NULL) {
			}////////////if ($child!=NULL) {
	}//////////if ($id_child>0) {
	
	}/////////////if (isset($_POST['id_child'])) {///////////////добавление связи по ид
	
	
	if (isset($_POST['add_param'])  AND $child_id>0) {/////////////////Добавляем параметры главного товара дочернему
	
			foreach($_POST['add_param'] as $caract_id=>$val):
					$this->savecharval($caract_id, $child_id, '');		
			endforeach;
	
	}//////////////////////if (isset($_POST['add_param'])) {/////////////////Добавляем параметры главного товара дочернему
	
	if (isset($_POST['del_param']) AND $child_id>0) {///////////////////Удаление параметра из принадлежащих дочернему
			foreach ($_POST['del_param'] as $key=>$val) $ppp[]=$key;
			$charact_id = implode(',', $ppp);
			//echo $charact_id;
			$query="DELETE from characteristics_values WHERE id_caract IN ( ".$charact_id.") AND id_product=".$child_id ;		
			$connection = Yii::app()->db;
			$command=$connection->createCommand($query)	;
			//$query2 = "DELETE FROM "
			//echo $query;
			$dataReader=$command->query();
	}////////////////////////if (isset($_POST['del_param']) AND $child_id>0) {///////////////////Удаление параметра из принадлежащих дочернему
	
	
	if (isset($_POST['car_val']))	{
			//print_r($_POST);
			foreach($_POST['car_val'] as $charact_id=>$charact_value) {///////////////Обновление параметров
					if (isset($_POST['new_value'][$charact_id])) {///////////////
							$new_val = $_POST['new_value'][$charact_id];
							if (trim($new_val)=='') $new_val = $charact_value;
							
							if (trim($new_val)!='') {
									$atr=array('id_caract'=>$charact_id, 'id_product'=>$child_id);
									//print_r($atr);
									$char_val = Characteristics_values::model()->findByAttributes($atr);
									if ($char_val != NULL) {
											$char_val->value = $new_val;
											try {
												$char_val->save();
												} catch (Exception $e) {
												 echo 'Ошибка сохранения параметра дочернего товара actionManage_childs(): ',  $e->getMessage(), "\n";
												}////////////////
									}/////////////
							}/////////if (trim($charact_value)!='') {
					}	///////////////////////
					
			}////////////foreach($_POST['car_val'] as $charact_id=>$charact_value) {
			

						
	}///////////////if (isset($_POST['car_val']))	{
	
	
	$activetab = "tab8";
	$this->redirect(Yii::app()->request->baseUrl."/adminproducts/product/$pr_id/?group=$gr_id&activetab=$activetab&child_id=$child_id", true, 301);
	}///////////////////public function actionManage_childs(){//////////Операции с дочерними товарами
	
	public function actionUpdate_trigers(){////////////////////////Обновление остатков в тригерах
	$gr_id = Yii::app()->getRequest()->getParam('group');
	$pr_id = Yii::app()->getRequest()->getParam('id');
	$delete_triger = Yii::app()->getRequest()->getParam('delete_triger'); /////////Остатки кого удаляем
	$parse_url= Yii::app()->getRequest()->getParam('parse_url', NULL);
	$hobby_king_id =  Yii::app()->getRequest()->getParam('hobby_king_id', NULL);
	$hobbyking_url = Yii::app()->getRequest()->getParam('hobbyking_url', NULL);
	$hobbyking_prod_id = Yii::app()->getRequest()->getParam('hobbyking_prod_id', NULL);
	$currency = Yii::app()->getRequest()->getParam('currency', NULL);
	
	//print_r($_POST);
	
	$prod = Products::model()->findByPk($pr_id);
	//if(isset($prod) && $prod !=NULL) $prod -> parse_url = $parse_url;
	$prod -> parse_url = $parse_url;
	$prod -> hobby_king_id = $hobby_king_id;
	$prod ->save();
	
	
	
	
	
	/////////////Добавляем склад
	$store_doc = Yii::app()->getRequest()->getParam('store_doc', NULL);
	if ($store_doc!=NULL AND $store_doc>0) {
	$atr=array('tovar'=>$pr_id, 'store'=>$store_doc);
	$TRIGER = 	Ostatki_trigers::model()->findByAttributes($atr);
	if ($TRIGER == NULL) $TRIGER = new Ostatki_trigers;
	$TRIGER->tovar = $pr_id;
	$TRIGER->store = $store_doc;
	$TRIGER->quantity = 1;
	try {
		$TRIGER->save();
		} catch (Exception $e) {
		 echo 'Ошибка сохранения: ',  $e->getMessage(), "\n";
		}//////////////////////
	}///////if ($store_doc!=NULL) {
	
	
	
	
	///////////////////Обновляем остатки по существующим складам
	if (isset($_POST['quantity'])) {
		
			
		
			foreach ($_POST['quantity'] AS $rec_id => $quant):
			
			$TRIGER = 	Ostatki_trigers::model()->findByPk($rec_id);
			if ($TRIGER !=NULL) {
					///////Если нет в массиве н
						$TRIGER->quantity=$quant;
						if(isset($_POST['store_price'])) $TRIGER->store_price = $_POST['store_price'][$rec_id];
						else  $TRIGER->store_price = NULL;
						
						if(isset($currency[$rec_id])) {
							 $TRIGER->currency = $currency[$rec_id];
						}
						
						////////////////Смотрим остатки и цену на хобби кинге (смотрим API по ид товара)
						if($hobbyking_prod_id!=NULL && isset($hobbyking_prod_id[$rec_id]) && trim($prod -> parse_url)!='')
						{
							

							

							$pat = '/__([0-9]{1,10})__/';
							preg_match_all($pat,$prod -> parse_url,$matches);
							if(isset($matches) && isset($matches[1]) && isset($matches[1][0])) try {
							
								$parser = new HobbykingParser();
								$parser->init();
								$data = $parser->commandItemId($matches[1][0]);
								

								
								if(isset($data['result']['price']))$TRIGER->store_price = $data['result']['price'];
								if(isset($data['result']['kolich']))$TRIGER->quantity = $data['result']['kolich'];
								if(isset($data['result']['cur'])){
									$currency = Currencies::model()->findByAttributes(array('currency_code'=>strtoupper($data['result']['cur'])));
									if(isset($currency) && $currency!=NULL) {
										$TRIGER->currency = $currency->currency_id;
									}
								}
								
								} catch (Exception $e) {
								throw new CHttpException(500,'Не удалось подключиться/вытащить с API по ID, '.$e->getMessage());
							}
						}
						
						////////////////Смотрим остатки и цену на хобби кинге (парсим страницу)
						if($hobbyking_url!=NULL && isset($hobbyking_url[$rec_id]) && trim($prod -> parse_url)!=''){
							//echo 'нужно смотреть';
							try {
								$parser = new HobbykingParser();
								$parser->init();
								$data = $parser->commandItem(trim($prod -> parse_url), false, false);
								if(isset($data['result']['price']))$TRIGER->store_price = $data['result']['price'];
								if(isset($data['result']['kolich']))$TRIGER->quantity = $data['result']['kolich'];
								if(isset($data['result']['cur'])){
									$currency = Currencies::model()->findByAttributes(array('currency_code'=>strtoupper($data['result']['cur'])));
									if(isset($currency) && $currency!=NULL) {
										$TRIGER->currency = $currency->currency_id;
									}
								}
								//print_r($data);
							} catch (Exception $e) {
								throw new CHttpException(500,'Не удалось подключиться/распарсить, '.$e->getMessage());
							}
														
						}
						
						if(isset($delete_triger) AND isset($delete_triger[$rec_id])==true) $TRIGER->delete();
						else	try {
							//print_r($TRIGER->attributes);
							//exit();
							$TRIGER->save();
							} catch (Exception $e) {
							 echo 'Ошибка сохранения: ',  $e->getMessage(), "\n";
							 var_dump($TRIGER->attributes);
							}//////////////////////
					
			}/////if ($TRIGER !=NULL) {
			
			
			endforeach;
	}////////////////if (isset($_POST['quantity'])) {
	
	
	$activetab = "tab7";
	$this->redirect(Yii::app()->request->baseUrl."/adminproducts/product/$pr_id/?group=$gr_id&activetab=$activetab", true, 301);
	}///////////////////////	public function actionUpdate_trigers(){////////////////////////Обновление остатков в тригерах

	public function actionProduct_update_charact(){///////////Управление характеристиками товаров
	$gr_id = Yii::app()->getRequest()->getParam('group');
	$pr_id = Yii::app()->getRequest()->getParam('id');
	//error_reporting(E_ERROR);
	//print_r($_POST);
	if (isset($_POST['car_val']))	{
			foreach($_POST['car_val'] as $charact_id=>$charact_value) {///////////////Обновление и удаление параметров
					
					//$criteria=new CDbCriteria;
					//$criteria->group = "  value ";
					//$criteria->condition = " id_caract = ".$charact_id." AND id_product=".$pr_id ;
					//$parametrs_values = Characteristics_values::model()->findAll($criteria);//
					//$parametrs_values->deleteAll();
			$query="DELETE from characteristics_values WHERE id_caract = ".$charact_id." AND id_product=".$pr_id ;		
			$connection = Yii::app()->db;
			$command=$connection->createCommand($query)	;
			$dataReader=$command->query();
			
			if (!@$_POST['del_param'][$charact_id]) {
					if(isset($_POST['new_value'][$charact_id]) AND trim($_POST['new_value'][$charact_id])) $charact_value = $_POST['new_value'][$charact_id];
					$this->savecharval($charact_id, $pr_id, $charact_value);			
			}
					
			}////////////foreach($_POST['car_val'] as $charact_id=>$charact_value) {
			
			///////////////////////////////////Вытаскивем параметры для данной группы(характеристики)
			//$criteria=new CDbCriteria;
			//$criteria->distinct = true;
			//$criteria->order = ' t.id DESC ';
			//$criteria->condition = " caract_category = ".$gr_id." OR is_common = 1 ";
			//$parametrs = Characteristics::model()->findAll($criteria);//
			//echo $_POST['add_params'];
						
	}///////////////if (isset($_POST['car_val']))	{
	//echo "ewrwer<br>";
	//print_r($_POST['add_char']);
	if (@isset($_POST['add_params'])) {
	//echo "ewrwer<br>";
					for ($i=0; $i<count($_POST['add_char']); $i++) {
							$this->savecharval($_POST['add_char'][$i], $pr_id, '');			
					}/////////////for ($i=0; $i<count($_POST['add_char']); $i++) {
			}/////////if (@isset($_POST['add_params'])) {
		
	$activetab = "tab5";
	$this->redirect(Yii::app()->request->baseUrl."/adminproducts/product/$pr_id/?group=$gr_id&activetab=$activetab", true, 301);
	}//////////public function actionProduct_update_charact(){
		

	private function savecharval($id_caract, $product_id, $value){
	//echo "werwerwe<br>";
						$connection = Yii::app()->db;
						$query = "INSERT INTO `characteristics_values` (
						`value_id` ,
						`id_caract` ,
						`id_product` ,
						`value`
						)
						VALUES (
						NULL ,  '".$id_caract."',  '".$product_id."',  '".htmlspecialchars_decode($value)."'
						);";
						////////GROUP_CONCAT(  //////////меняет порядок выборки
						//echo $query;
						$command=$connection->createCommand($query);
						$dataReader=$command->query();
	}//////////private function savecharv
	
	public function actionAddcompatible(){/////////////////добавление совместимых товаров
			$gr_id = Yii::app()->getRequest()->getParam('group');
			$pr_id = Yii::app()->getRequest()->getParam('id');
			$activetab = 'tab6';
			$add_product = Yii::app()->getRequest()->getParam('add_product');
			$add_category = Yii::app()->getRequest()->getParam('add_category');
			$date_to_value  = Yii::app()->getRequest()->getParam('date_to_value', NULL);
			
			if (isset($pr_id) AND isset($add_product) AND $add_product>0) {
					$PC = new Products_compability;
					$PC->isNewRecord=true;
					$PC->product = $pr_id;
					$PC->compatible = $add_product;
					try {
											$PC->save(false);
											} catch (Exception $e) {
											 echo 'Ошибка записи совместимого товара: ',  $e->getMessage(), "\n";
											}/////////////////////
			}//////////////if (isset($pr_id) AND isset($add_product)) {
			if (isset($add_category) AND $add_category>0) {//////////////Группы
					$CC = new Products_categories_compability ;
					$CC->isNewRecord=true;
					$CC->product = $pr_id;
					$CC->compatible_category = $add_category;
					try {
											$CC->save(false);
											} catch (Exception $e) {
											 echo 'Ошибка записи совместимой категории: ',  $e->getMessage(), "\n";
											}/////////////////////
			}//////////////////////if (isset($add_category) AND $add_category>0) {//////////////Группы
			
			
			////////////////////////////////удаление совместимых товаров
			//del_product[1]
			if (isset($_POST['savecomp']) AND isset($_POST['del_product'])) {
					foreach($_POST['del_product'] AS $key=>$val) :
							$PC = Products_compability::model()->findByPk($key);
							try {
											$PC->delete();
											} catch (Exception $e) {
											 echo 'Ошибка удаления: ',  $e->getMessage(), "\n";
											}/////////////////////
					endforeach;
			}//////////////if (isset($_POST['savecomp']) AND isset($_POST['del_product'])) {
			
			if (isset($_POST['savecomp']) AND isset($_POST['del_product_category'])) {
					foreach($_POST['del_product_category'] AS $key=>$val) :
							$CC = Products_categories_compability::model()->findByPk($key);
							try {
											$CC->delete();
											} catch (Exception $e) {
											 echo 'Ошибка удаления: ',  $e->getMessage(), "\n";
											}/////////////////////
					endforeach;
			}//////////////if (isset($_POST['savecomp']) AND isset($_POST['del_product'])) {
			
			if (isset($date_to_value)) {//////////////////Дата, до которой будет отображаться объявление
					foreach($date_to_value as $key => $dateval) {
							if (trim($dateval)<>'') {
									$date_to_arr = split("-", $dateval );
									$date_to_int = mktime(23, 59, 59, $date_to_arr[1],$date_to_arr[0], $date_to_arr[2] ); ///////////////////////mdy
									$CC = Products_categories_compability::model()->findByPk($key);
									$CC->active_till_int = $date_to_int;
									try {
													$CC->save();
													} catch (Exception $e) {
													 echo 'Ошибка сохранения: ',  $e->getMessage(), "\n";
													}/////////////////////
							}
					}////////////foreach($date_to_value as $rec_id => $date) {
 			}/////////////////if (isset($date_to_value)) {
			
			
			$this->redirect(Yii::app()->request->baseUrl."/adminproducts/product/$pr_id/?group=$gr_id&activetab=$activetab", true, 301);
	}////////////////////////public function actionAddcompatible(){/////////////////добавление совместимых товаров
	
	public  function actionCreategroup(){ //////////////Создание новой группы
		$parent_id = Yii::app()->getRequest()->getParam('id');
		$model= new CategoryForm($parent_id);
		
		$form_parametrs = Yii::app()->getRequest()->getParam('CategoryForm');
		
		if (isset($form_parametrs)) {
			$model->setAttributes($form_parametrs, false);
			if (trim($model->alias)=='' AND trim($model->category_name)!='') $model->alias = preg_replace('/[^a-z0-9_-]/','',strtolower( str_replace(" ", "_",FHtml::translit(trim($model->category_name)))));
			
			$qqq = $model->validate();
			
			if ($qqq == true) { 
				//echo 'true';
				//print_r($model);
				$cat = new Categories;
				$cat->category_name = $model->category_name;
				$cat->alias = $model->alias;
				$cat->title = $model->title;
				$cat->h1 = $model->h1;
				$cat->sort_category = $model->sort_category;
				$cat->show_category = $model->show_category;
				$cat->keywords = $model->keywords;
				if (isset($parent_id)) $cat->parent = $parent_id;
				$cat->description = $model->description;
				
				
				try {
							$cat->save();
					} catch (Exception $e) {
							 echo 'Caught exception: ',  $e->getMessage(), "\n";
					}//////////////////////
				
				if (isset($cat)) {
						$cat->path = serialize(FHtml::get_productiya_path($cat->category_id));
						try {
									$cat->save();
							} catch (Exception $e) {
									 echo 'Caught exception: ',  $e->getMessage(), "\n";
							}//////////////////////
					
					//if (isset($parent_id)) {
						$url  = Yii::app()->createUrl('adminproducts/group', array('id'=>$cat->category_id, 'open_params'=>1));
					//}
					//else $url  = Yii::app()->createUrl('adminproducts/list', array('open_params'=>1));
					
					$this->redirect($url,  true);
					
				}////////if (isset($cat)) {
			}/////if ($qqq == true) {
			//else echo "false";	 
		}////	if (isset($form_parametrs)) {
	
		
		
		$form = new CForm($model->GetStructure(), $model);
		$this->render('category_form', array('form'=>$form));
	}//////////public  function actionCreategroup(){ //////////////Соз
	
	public function actionAdd_category_param(){///добавление новой опции к группе
			if(Yii::app()->request->isAjaxRequest == false) exit();
			$id = Yii::app()->getRequest()->getParam('groupe');
			
			
			$char_types = Characteristics_types::model()->findAll();
			$response['char_types'] = CHtml::listdata($char_types, 'id', 'type');
			
			$new_char = new Characteristics;
			$new_char->caract_name = 'Новая опция';
			try {
						$new_char->save();
						if(isset($new_char->caract_id)) {
						
								$new_char_cat = new Characteristics_categories;
								$new_char_cat->characteristics_id = $new_char->caract_id;
								$new_char_cat->categories_id = $id;
								try {
											$new_char_cat->save();
											if(isset($new_char_cat->id)) {
													$response['char_id']=$new_char->caract_id;
													$response['char_categ_id']=$new_char_cat->id;
													echo json_encode($response);	
											}
									} catch (Exception $e) {
											 echo 'Caught exception: ',  $e->getMessage(), "\n";
									}//////////////////////
						}
				} catch (Exception $e) {
						 echo 'Caught exception: ',  $e->getMessage(), "\n";
				}//////////////////////
			
			
	}//////////////////////////////////////////////////////////////////////////////////////

	function change_products_category($products_list, $new_category) { //////Перенос товаров массово в другую группу
			/*
			print_r($products_list);
			echo '<br>Новая группа = ';
			echo $new_category;
			echo '<br>';
			*/
			
			
			if($new_category>0) {
			$connection=Yii::app()->db;
					$query="UPDATE products SET category_belong = $new_category  WHERE id IN (".implode(',', array_keys($products_list)).")";
					$command=$connection->createCommand($query)	;
					$dataReader=$command->query();
					//$qqq = $dataReader->readAll();
			}
			else echo 'Новая группа не задана';
			
	}//////////function change_products_category($products_li

	public function link_products_to_category($products_list, $new_category) { ///Массовое создание ссылок на товары в другой категории
		//print_r($products_list);
		//exit();
		foreach($products_list AS $pr_id => $fff){
			$cp = new Categories_products;
			$cp->group = $new_category;
			$cp->product = $pr_id;
			$cp->type =1;
			try {
					$cp->save();
				} catch (Exception $e) {
					 echo 'Caught exception: ',  $e->getMessage(), "\n";
				}//////////////////////
		}
	}///	public function link_products_to
	
	private function updateDiscountCardPrices($product_id, $product_card_prices){
		//echo '$product_id = '.$product_id.'<br>';
		//print_r($product_card_prices);
		
		$CC = new ClientCards();
		//Достаем типы цен:
		$card_types = FHtml::enum($CC, 'type');
			
		foreach ($card_types AS $card_type){
			$criteria=new CDbCriteria;
			$criteria->condition = 't.product_id = :pid AND cardtype=:cardtype';
			$criteria->params=array(':pid'=>$product_id, ':cardtype'=>$card_type);
			$model = ProductCardPrices::model()->find($criteria);//
			if(isset($product_card_prices[$card_type]) && trim($product_card_prices[$card_type])!='' && trim($product_card_prices[$card_type])!='0'){
				if ($model==NULL) {
					$model=new ProductCardPrices;
					$model->product_id = $product_id;
					$model->cardtype = $card_type;
				}
				if(isset($product_card_prices[$card_type])){
					$model->price = $product_card_prices[$card_type];
				}
				try {
					$model->save();	
				} catch (Exception $e) {
					
				}
			}
			else{
				if($model!=NULL) $model->delete();
			}
			
		}
		

		
		//exit();
	}
	
	public function actionAdministration() {///////////////////Вывод главной траницы администрирования
	    $this->layout="main";
	    $this->render('adminindex');
	}///////////////////public function actionAdmin() {/////////////////Вывод главной траницы администри
	

}

