<?php

///Добавлено в офисе
class TransferController extends Controller
{
	const PAGE_SIZE=10;
	var $categories_list;
	var $sootnosh; 
	/**
	 * @var string specifies the default action to be 'list'.
	 */
	public $defaultAction='content';  

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
				'actions'=>array('list'),
				'users'=>array('*'),
			),
			
		);
	}

	/**
	 * Shows a particular model.
	 */


	public function actionIndex(){
		//echo '1<br>';
		$this->render('index');
	}
	
	public function init(){
		$this->layout="admin";
	}


	public function actionTaxonomy(){
		
		$connection=new CDbConnection;
		$connection->username=Yii::app()->params['db3']['username'];
		$connection->password = Yii::app()->params['db3']['password'];
		$connection->connectionString = Yii::app()->params['db3']['connectionString']; 
		$connection->charset = Yii::app()->params['db3']['charset'];  
		$connection->enableProfiling = Yii::app()->params['db3']['enableProfiling'];  
		$connection->enableParamLogging = Yii::app()->params['db3']['enableParamLogging'];  
		$connection->emulatePrepare = Yii::app()->params['db3']['emulatePrepare'];  
		$connection->init();
		
		/*
		
		*/
		$query="SELECT parent_data.tid as parent_tid, parent_data.name AS parent, child_data.tid as child_tid, child_data.name AS child, parent_data.filepath
FROM  `term_hierarchy` 
JOIN (SELECT term_data.name, term_data.tid,  uc_catalog_images.filepath FROM term_data JOIN term_hierarchy ON term_hierarchy.tid = term_data.tid  LEFT JOIN uc_catalog_images ON uc_catalog_images.tid = term_data.tid  WHERE term_hierarchy.parent = 9) parent_data ON parent_data.tid = term_hierarchy.parent
LEFT JOIN term_data child_data ON child_data.tid = term_hierarchy.tid
 ";
			$command=$connection->createCommand($query)	;
			$dataReader=$command->query();
			$models=$dataReader->readAll();
	
			$criteria=new CDbCriteria;
			//$criteria->condition="t.parent = 2";
			$categories = Categories::model()->findAll($criteria);
			if (isset($categories)) {
				for($i=0; $i<count($categories); $i++) {
					$this->categories_list[strtolower($categories[$i]->category_name)] = array('category_id'=>$categories[$i]->category_id,
					'category_name'=>$categories[$i]->category_name,
					'parent'=>$categories[$i]->parent,
					);
				}
			}
		
		
		
		//print_r($categories_list);
		
		$this->render('taxonomy', array('models'=>$models, 'categories_list'=>$this->categories_list));
	}
	
	public function create_category($create_category, $category_parent){
		//echo 'ewrwerwer';
		//echo $create_category;
		$qqq = strtolower($create_category);
		if(isset($this->categories_list[$qqq]) == false) {
		
		$new_cat = new Categories();
		$new_cat->category_name = $create_category;
		$new_cat->parent = $category_parent;
		$new_cat->show_category=1;
		$new_cat->alias = preg_replace('/[^a-z0-9_]/','',strtolower( str_replace(" ", "_",FHtml::translit($create_category)))); 
		//$new_cat->path = $CAT->path = serialize(FHtml::get_productiya_path($CAT->category_id));
		try {
												$new_cat->save();
											} catch (Exception $e) {
										if (stristr($e->errorInfo[2],'Duplicate')) {/////////Добавляем к имени контрагента ид
												$new_cat->alias =preg_replace('/[^a-z0-9_]/','',strtolower( str_replace(" ", "_",FHtml::translit($create_category))))."_".microtime(); 
												try {
												$new_cat->save();
												} catch (Exception $e) {
													echo 'Ошибка сохранения группы: ',  $e->getMessage(), "\n";
												}////////////} catch (Exception $e) {
										}/////////if (stristr($e->errorInfo[2],'Duplicate')) {/////////Добав
										
										}///////////////////	
			if(isset($new_cat->category_id)) {
				$new_cat->path =  serialize(FHtml::get_productiya_path($new_cat->category_id));
				try {
						$new_cat->save();
						$this->categories_list[strtolower($create_category)] = array('category_id'=>$new_cat->category_id,
						'category_name'=>$create_category,
						'parent'=>$category_parent,
						);
						return $new_cat->category_id;
					} catch (Exception $e) {
						echo 'Ошибка сохранения группы 2: ',  $e->getMessage(), "\n";
					}//
			}///////if(isset($new_cat->category_id)) {
		}///////if(isset($categories_list[$qqq]) == false) {
		else return $this->categories_list[$qqq]['category_id'];
	}///////public function create_category(){
		
	public function create_category_child($create_category, $parent_name){
		$qqq = strtolower($parent_name);
		if(isset($this->categories_list[$qqq]) == true) {
				$parent = $this->categories_list[$qqq]['category_id'];
				$qqq = strtolower($create_category);
				if(isset($this->categories_list[$qqq]) == false) {
						$new_cat = new Categories();
						$new_cat->category_name = $create_category;
						$new_cat->parent = $parent;
						$new_cat->show_category=1;
						$new_cat->alias = preg_replace('/[^a-z0-9_]/','',strtolower( str_replace(" ", "_",FHtml::translit($create_category)))); 
						//$new_cat->path = $CAT->path = serialize(FHtml::get_productiya_path($CAT->category_id));
						try {
																$new_cat->save();
															} catch (Exception $e) {
														if (stristr($e->errorInfo[2],'Duplicate')) {/////////Добавляем к имени контрагента ид
																$new_cat->alias =preg_replace('/[^a-z0-9_]/','',strtolower( str_replace(" ", "_",FHtml::translit($create_category))))."_".microtime(); 
																try {
																$new_cat->save();
																} catch (Exception $e) {
																	echo 'Ошибка сохранения группы: ',  $e->getMessage(), "\n";
																}////////////} catch (Exception $e) {
														}/////////if (stristr($e->errorInfo[2],'Duplicate')) {/////////Добав
														
														}///////////////////	
							if(isset($new_cat->category_id)) {
								$new_cat->path =  serialize(FHtml::get_productiya_path($new_cat->category_id));
								try {
										$new_cat->save();
										$this->categories_list[strtolower($create_category)] = array('category_id'=>$new_cat->category_id,
										'category_name'=>$create_category,
										'parent'=>$parent,
										);
									} catch (Exception $e) {
										echo 'Ошибка сохранения группы 2: ',  $e->getMessage(), "\n";
									}//
							}///////if(isset($new_cat->category_id)) {
				}/////////if(isset($this->categories_list[$qqq]) == false) {
		}///////////if(isset($this->categories_list[$qqq]) == true) {
	}///////public function create_category(){	
	
	public function actionProducts(){
			$connection=new CDbConnection;
		$connection->username=Yii::app()->params['db3']['username'];
		$connection->password = Yii::app()->params['db3']['password'];
		$connection->connectionString = Yii::app()->params['db3']['connectionString']; 
		$connection->charset = Yii::app()->params['db3']['charset'];  
		$connection->enableProfiling = Yii::app()->params['db3']['enableProfiling'];  
		$connection->enableParamLogging = Yii::app()->params['db3']['enableParamLogging'];  
		$connection->emulatePrepare = Yii::app()->params['db3']['emulatePrepare'];  
		$connection->init();
		
		/*
		
		*/
		$query="SELECT products.model, term_data.name as cat_name, products.title  FROM term_node JOIN term_data ON term_data.tid = term_node.tid JOIN  (SELECT uc_products.model, uc_products.nid, node.title FROM uc_products JOIN node ON node.nid =uc_products.nid ) products ON products.nid = term_node.nid
 ";
			$command=$connection->createCommand($query)	;
			$dataReader=$command->query();
			$models=$dataReader->readAll();
	
			$criteria=new CDbCriteria;
			//$criteria->condition="t.parent = 2";
			$categories = Categories::model()->findAll($criteria);
			if (isset($categories)) {
				for($i=0; $i<count($categories); $i++) {
					$this->categories_list[strtolower($categories[$i]->category_name)] = array('category_id'=>$categories[$i]->category_id,
					'category_name'=>$categories[$i]->category_name,
					'parent'=>$categories[$i]->parent,
					);
				}
			}
			
			$this->actionRenameproducts();
			
			$this->render('products', array('models'=>$models,'categories_list'=>$this->categories_list ));
	}
	
	public function createproduct($cat, $prod_name, $char_val, $parametrs=NULL){///////////создание товарв
		//echo $prod_name.'<br>';
		//print_r($parametrs);
		//echo '<br>';
		$new = 0;
		$product = Products::model()->findByAttributes(array('product_name'=>trim($prod_name), 'category_belong'=>$cat));
		if(isset($product)==false) {
			$new = 1;
			$product = new Products();
			$product->product_name = trim($prod_name);
			$product->category_belong = $cat;
			if(isset($parametrs) AND $parametrs!=NULL) {//////////////Параметры полученные из XML
				if(isset($parametrs['article'])) $product->product_article = $parametrs['article'];
				if(isset($parametrs['description'])) $product->product_full_descr = htmlspecialchars_decode($parametrs['description']);
				if(isset($parametrs['short'])) $product->product_short_descr = htmlspecialchars_decode($parametrs['short']);
				if(isset($parametrs['nds'])) $product->nds_out = $parametrs['nds'];
				if(isset($parametrs['product_price'])) $product->product_price = $parametrs['product_price'];
				if(isset($parametrs['product_short_descr'])) $product->product_short_descr = $parametrs['product_short_descr'];
			//	if(isset($parametrs['measure'])) $product->measure = $parametrs['measure'];
				if(isset($parametrs['measure'])) $product->measure =1;

				
			}
			try {
									$product->save();
									
									} catch (Exception $e) {
										echo 'Ошибка сохранения тов: ',  $e->getMessage(), "\n";
										echo '<pre>';
										print_r($product->attributes);
										echo '</pre>';
									}//
									
			//var_dump($product->id);
			//var_dump(empty($char_val)==false);
			//var_dump(is_array($char_val)==true);
									
			if (isset($product->id) AND isset($char_val) AND empty($char_val)==false AND is_array($char_val)==true) {
			
				foreach($char_val as $id=>$val) {	
					
					//echo $id.' - '.$val.'<br>';
					
					$CV = new Characteristics_values;
					$CV->id_caract = $id;
					$CV->id_product = $product->id;
					$CV->value = htmlspecialchars(trim($val));
					try {
										$CV->save();
										//print_r($CV->attributes);
										} catch (Exception $e) {
											echo 'Ошибка сохранения парам: ',  $e->getMessage(), "\n";
											
											print_r($CV->attributes);
											echo '<br>';
											print_r($CV->products->attributes );
											echo '<br>';
											
											exit();
										}//
				}
				//return 		$product->product_name.',  '.$CV->value;	
			}
			
			
		}///////if(isset($product)==false) {
		
		//print_r($char_val);
		//echo '<br>exit<br>';
		//exit();
		
		return array($product->id, $new);
		
	}/////////function
	
	
	public function mb_str_replace($find,$replace,&$str) 
	{ 
			$i = mb_strpos($str,$find, 0,"UTF-8"); 
			if ($index===false) {return;} 
			$str = mb_substr($str, 0,$i).$replace.mb_substr($str, $i+mb_strlen($find,"UTF-8"),mb_strlen($str,"UTF-8")); 
			$this->mb_str_replace($find,$replace,$str); 
	} 
	
	
	public function createGetCharacteristic($charact_name, $cat = NULL){ ////////////Создает/находит ид характеристики
		$charact_name =htmlspecialchars(trim($charact_name));
		$c = Characteristics::model()->findByAttributes(array('caract_name'=>$charact_name));
		if(isset($c)==false) {
			$c=new Characteristics;
			$c->caract_name = $charact_name;
			try {
					$c->save();
					$caract_id = $c->caract_id;
				} catch (Exception $e) {
					echo 'Ошибка сохранения характеристики: ',  $e->getMessage(), "\n";
					echo '<br>';
					print_r($c->attributes);
					echo '<br>';
					exit();
				}////////////} catch (Exception $e) {
		
		}
		else $caract_id = $c->caract_id;
		return $caract_id;

	}
	
	
	public function linkCaracteristicToGroup($caract_id, $cat)
	{
				//////////////////Смотрим принадлежность к группе
		$c_c = Characteristics_categories::model()->findByAttributes(array('characteristics_id'=>$caract_id, 'categories_id'=>$cat));
		if(isset($c_c)==false){
			$c_c=new Characteristics_categories;
			$c_c->characteristics_id = $caract_id;
			$c_c->categories_id = $cat;
			try {
					$c_c->save();
				
				} catch (Exception $e) {
					echo 'Ошибка подвязки характеристики: ',  $e->getMessage(), "\n";
				}////////////} catch (Exception $e) {
		}
	}
	 
	public function checkBrandDescr($caract_id, $char_descr){
		try {
				$c = Characteristics::model()->findByPk($caract_id);
		} catch (Exception $e) {
					echo 'Ошибка нахождения характеристики: ',  $e->getMessage(), "\n";
					var_dump($caract_id);
					//print_r($c->attributes);
					exit();
				}////////////} catch (Exception $e) {
		if(isset($c) AND $c->char_descr==NULL) {
			$c->char_descr = $char_descr;
			try {
					$c->save();
				} catch (Exception $e) {
					echo 'Ошибка сохранения описания характеристики: ',  $e->getMessage(), "\n";
				}////////////} catch (Exception $e) {
		}
	}
	
	public function actionReadXLS(){
		$transfer = Yii::app()->getRequest()->getParam('transfer');
		$customer = Yii::app()->getRequest()->getParam('customer', NULL); ///////Идентификатор магазина/базы (наюоры полей)
		$from = Yii::app()->getRequest()->getParam('from', NULL);
		//$handle = fopen("E:\old disk\work\yii-site\html\themes\casaarte\db]Wesco.xlsx", "r");
		//$folder = "C:\wwwroot\casaarte\\";
		$folder = "C:\chemimart\\";
		
		//$name = $folder."Wesco.xls";
		$name = $folder."table_list_100.xls";
		//$name = $folder."all2000.xls";
			
			require_once 'excel_reader2.php';
		//print_r($attributes);

		$data = new Spreadsheet_Excel_Reader($name);
		

		$num_of_rows =($data->rowcount($sheet_index=0))-1 ;
		//echo 	$this->temp_folder.$attributes['tempfile'].'<br>';
		//echo $num_of_rows;
		
		$criteria=new CDbCriteria;
			//$criteria->condition="t.parent = 2";
			$categories = Categories::model()->findAll($criteria);
			if (isset($categories)) {
				for($i=0; $i<count($categories); $i++) {
					$this->categories_list[strtolower($categories[$i]->category_name)] = array('category_id'=>$categories[$i]->category_id,
					'category_name'=>$categories[$i]->category_name,
					'parent'=>$categories[$i]->parent,
					);
				}
			}
		
		if($transfer==1) {
			
				/*
				$c_brand_id = $this->createGetCharacteristic("Бренд", $cat); 
				$c_made_id = $this->createGetCharacteristic("Страна производства", $cat); 
				$c_material_id = $this->createGetCharacteristic("Материал", $cat); 
				$c_collection_id = $this->createGetCharacteristic("Коллекция", $cat); 
				$c_size_id = $this->createGetCharacteristic("Размер, см.", $cat); 
				$c_vol_id = $this->createGetCharacteristic("Литраж, объем, мл.", $cat); 
				$c_color_id = $this->createGetCharacteristic("Цвет", $cat); 
				$c_color_id = $this->createGetCharacteristic("Гарантийный срок в годах", $cat); 
				*/
		      
		    

		    if($customer=='chemimart'){
				//////////chemimart
				$chars = array(
				    "Molecular Formula"=>6,
				    "Molecular Weight"=>7,
				    "CAS"=>5,
				    "Availability"=>10,
				    "Purity"=>9,
				    "Catalog Number"=>1,
				);
		    }
		    else{
		        ///хз чей
		        $chars = array(
		            "Бренд"=>5,
		            "Страна производства"=>4,
		            "Материал"=>6,
		            "Коллекция"=>7,
		            "Размер, см."=>8,
		            "Литраж, объем, мл."=>9,
		            "Цвет"=>10,
		            "Гарантийный срок в годах"=>11
		            
		        );
		    }
				
				foreach ($chars as $char_name=>$column_num){
					$chars_columns[$this->createGetCharacteristic($char_name)]=$column_num;
				}
				
 			 //for($i=6; $i<$num_of_rows; $i++) {
				 if($from!=NULL) $start_from = $from;
				 else $start_from  = 3;
			for($i=$start_from; $i<=$num_of_rows+1; $i++) { 
			 		//($create_category, $category_parent){
						$gr1= $data->val($i, 19);
						if(trim($gr1)!='')$gr1_id = $this->create_category($gr1, Yii::app()->params['main_tree_root']);
						
						$gr2= $data->val($i, 20);
						if(trim($gr2)!='')$gr2_id = $this->create_category($gr2, $gr1_id); 
						
						$gr3= $data->val($i, 21);
						if(trim($gr3)!='') $cat = $this->create_category($gr3, $gr2_id);
						//else $cat = $gr2_id;
						else $cat = $gr1_id;
						
						
						
						
						//$this->checkBrandDescr();

						
						////Блок при наличии таблицы characteristics_categories. Есть например на петмарките.
						if($customer=='chemimart'){
						    foreach ($chars_columns as $caract_id=>$column_num){
						        if(trim($data->val($i, $column_num))!=''){
						            if($column_num==1) $char_val[$caract_id] = str_replace ('AC', 'CM' , trim($data->val($i, $column_num)));
						            else $char_val[$caract_id] = trim($data->val($i, $column_num));
						        }
						    }
						}
						else{
    						foreach ($chars_columns as $caract_id=>$column_num){
    							$this->linkCaracteristicToGroup($caract_id, $cat); 
    							if(trim($data->val($i, $column_num))!='') $char_val[$caract_id] = trim($data->val($i, $column_num));
    							if($caract_id==5 AND trim($data->val($i, 14))!='') $this->checkBrandDescr($caract_id, $data->val($i, 14));
    						}
						}
						
			
						$images = array();
						
						if($customer=='chemimart'){
						    $parametrs = array();
						    $prod_name =  $data->val($i, 4);
						    $package = $data->val($i, 11);
						    $price_code = str_replace ('AC', 'CM' , $data->val($i, 1));
						    $parametrs['article']= $data->val($i, 5);
						    $parametrs['nds'] = 0.18;
						    if(trim($data->val($i, 12))!=NULL)$parametrs['product_price'] = (str_replace(' ', '', str_replace(',', '.', $data->val($i, 12))))*1.3;
						    $parametrs['description'] = $data->val($i, 8);
						    //if(trim($data->val($i, 22))!=NULL)$parametrs['product_short_descr'] = $data->val($i, 22);
						    $fname = trim($folder.$data->val($i, 1).'.jpg');
						    if(file_exists($fname) AND is_file($fname) ) {
						        $images[]=$fname;
						    }
						}
						else{
    						
    						$parametrs = array();
    						$prod_name =  $data->val($i, 2);
    						$parametrs['article'] = $data->val($i, 1);
    						$parametrs['nds'] = 0.18;
    						if(trim($data->val($i, 12))!=NULL)$parametrs['product_price'] = str_replace(' ', '', str_replace(',', '.', $data->val($i, 12))); 
    						$parametrs['description'] = $data->val($i, 13);
    						if(trim($data->val($i, 22))!=NULL)$parametrs['product_short_descr'] = $data->val($i, 22);
    						//$parametrs['short'] = $data->val($i, 13);
    						
    						$picture_folder = $folder.$data->val($i, 5).'\\';
    						for($k=15; $k<19; $k++) {
    						    if(trim($data->val($i, $k)!='') AND trim($data->val($i, $k))!='0'){
    						        $fname = $picture_folder.$data->val($i, $k).'.jpg';
    						        if(file_exists($fname) AND is_file($fname) ) {
    						            $images[]=$fname;
    						        }
    						    }
    						}
						}
						
						
						//print_r($images);
						//$this->save_product_images2(0, $images);
						//exit();	
						
									
						
						$prod = $this->createproduct($cat, $prod_name, $char_val, $parametrs);
						$prod_id = $prod[0];
                    						
						if(isset($prod_id) AND $customer=='chemimart' AND isset($package) AND trim($package)!=''){ ////////Сохраняем цены в формате chemimart
						    $this->setChemimartProductPrice($prod_id, $package,$price_code,$parametrs['product_price']);
						}
						
						
						
						if(isset($prod_id) AND empty($images)==false AND $prod[1]==1) $this->save_product_images2($prod_id, $images);
		
						
						
			 }

		}
		elseif($transfer=='fixupdate'){

		    
		    if($from!=NULL) $start_from = $from;
		    else $start_from  = 3;
		    
		    
		    
		    for($i=$start_from; $i<=$num_of_rows+1; $i++) { 
		        $prod_name =  $data->val($i, 4);
    		    $prod = $this->createproduct(11, $prod_name, null, null);
    		    
    		    if($prod!=null && isset($prod[0])){
    		        print_r($prod);
    		        echo ' - ';
    		        $p = Products::model()->findByPk($prod[0]);
    		        echo $p->product_article.' - '.$data->val($i, 5);
    		        try {
                        $p->product_article = $data->val($i, 5);
                        $p->save();
                        echo '....updated<br>';
                    } catch (Exception $e) {
                        echo '....failed<br>';
                    }
    		    }
		    }
		}
		
		
		
		$this->render('xls/preview', array('data'=>$data));
		
	}
	
	public function actionRead1CXML(){
		//echo '1<br>';
		//$handle = fopen("C:\\wwwroot\\example.xml", "r");
		$handle = fopen("C:\\wwwroot\\CMLCatalog.xml", "r");
		//$handle = fopen("E:\\work\\example.xml", "r");
		//$handle = fopen("E:\\work\\CMLCatalog.xml", "r");
		//$handle = fopen("C:\\wwwroot\\1.txt", "r");
		//var_dump($handle);
		//stream_set_timeout($fp, 2);
		//echo 'werwer';
		
		if ($handle) {
		$contents = '';
		while (!feof($handle)) {
		  $contents .=  fread($handle, 18192);

		}
		fclose($handle);
		//echo "$contents";
		 //$contents = '<Наименование><![CDATA[Звуковые платы]]></Наименование>';
		//$contents1= mb_replace($unparsed, '&lt;!\[CDATA\[.*?\]\]&gt;', '', 's')
		//$contents1 = preg_replace("&lt;!\[CDATA\[.*?\]\]&gt;", '', $contents);
		//$contents2 =  preg_replace ("&lt;!\[CDATA\[.*?\]\]&gt;","", $contents );
		// = str_replace(']]>', '', $contents1);
		 //$contents = str_replace('<!--', '', $contents2);
		 //echo $contents;
		 //exit();
		  echo '<pre>';
		// print_r($contents);
		  echo '</pre>';
		  
		// $contents1 = preg_replace('/^\s*<!\[CDATA\[([\s\S]*)\]\]>\s*\z/', '', $contents);
		 //$s = preg_replace('~<!\[CDATA\[\w*|\w*\]\]>~', '',$contents); 
		
		try {
					$xml = new SimpleXMLElement($contents);
					} catch (Exception $e) {
					 echo 'Caught exception:  Ссылка: '.$rabota_links[$s].' ',  $e->getMessage(), "\n";
					}///////////////////		 
			if (is_object($xml)) {
						
			
			echo '<pre>';
			//print_r($xml);
			echo '</pre>';
			
				//	if(isset($xml['Классификатор'])) print_r($xml['Классификатор']);
				echo '<pre>';
				//print_r($xml);
				echo '</pre>';		
				$xml_groups = $xml->Классификатор->Группы;
				$xml_products = $xml->Каталог->Товары;
				
				
				$i=0;
				if (isset($xml_groups)) foreach ($xml_groups->Группа as $qqq=>$group) {
					echo '<pre>';
					//print_r($group);
					echo '</pre>';
					$one_c_gr[$i]=array('id' => (string)$group->Ид, 'name'=>(string)$group->Наименование);
					//print_r($group->Наименование[0]);
					
				
					if(isset($group->Группы)) {
						$childs = array();
						 foreach($group->Группы->Группа as $qqq=>$child){
						$childs[]=array('id'=>(string)$child->Ид, 'name'=>(string)$child->Наименование);
				
					}////////if(isset($group->Группы)) foreac
					if(isset($childs) AND empty($childs)==false) $one_c_gr[$i]['childs'] = $childs;
					}//////////////////////////////////////
					$i++;
				}
				
				echo '<pre>';
					//print_r($one_c_gr);
					echo '</pre>';
				
				
				if(isset($xml_products)) {//////
						echo '<pre>';
						//print_r($xml_products);
						echo '</pre>';
						//->attributes['НаименованиеПолное']
						$i=0;
						foreach($xml_products->Товар as $qqq => $product) {
								$m = (array)$product->БазоваяЕдиница;
								$one_c_product[$i] = array('id'=>(string)$product->Ид,
									'shtrihcode'=>(string)$product->Штрихкод,
									'article'=>(string)$product->Артикул,
									'name'=>(string)$product->Наименование,
									'description'=>(string)htmlspecialchars($product->Описание),
									'category_belongs'=>(string)$product->Группы->Ид,
									'trademark'=>(string)$product->ТорговаяМарка,
									'nds'=>((float)$product->СтавкиНалогов->СтавкаНалога->Ставка/100),
									'measure'=>$m['@attributes']['НаименованиеПолное'],
								);
						if(isset($product->Картинка)){
							$images=array();
							for ($k=0; $k<count($product->Картинка); $k++) {
								$images[]=(string)$product->Картинка[$k];
							}
							if (isset($images) AND empty($images)==false) $one_c_product[$i]['images']=$images;
						}		
						
						$i++;		
						}////////////	foreach($xml_products as $qqq => $product) {
							
						echo '<pre>';	
						//print_r($one_c_product);	
						echo '</pre>';
				}//////////////$xml_products
				
			}///////if (is_object($xml)) {
		}/////handle
		
		$transfer = Yii::app()->getRequest()->getParam('transfer');
		
		
		if(isset($transfer)) {///////////Пишем в БД
			if(isset($one_c_gr)) for($i=0; $i<count($one_c_gr); $i++) {
				$xml_id = $one_c_gr[$i]['id'];
				
				$CAT=Categories::model()->findByAttributes(array('category_name'=>$one_c_gr[$i]['name']));
				if(isset($CAT)==false) {
					 $native_id = $this->create_category($one_c_gr[$i]['name'], Yii::app()->params['main_tree_root']);
					 $one_c_gr[$i]['native_id'] = 	 $native_id;
					 ////////////////////Делаем соответствие между старыми и новыми ид
					 
				}
				else {
					$native_id=$CAT->category_id;
					$one_c_gr[$i]['native_id']=$native_id;
				}
				$gr_xnm_trade_x[$xml_id]=$native_id;
				
				if(isset($native_id) AND isset($one_c_gr[$i]['childs'])) {
					for($k=0; $k<count($one_c_gr[$i]['childs']); $k++) {
						$CHILD_CAT = Categories::model()->findByAttributes(array('category_name'=>$one_c_gr[$i]['childs'][$k]['name'], 'parent'=>$native_id));
						if(isset($CHILD_CAT)==false) {
							$native_child_id = $this->create_category($one_c_gr[$i]['childs'][$k]['name'], $native_id);
							 $one_c_gr[$i]['childs'][$k]['native_id'] = $native_child_id;
						}
						else {
							$native_child_id = $CHILD_CAT->category_id;
							 $one_c_gr[$i]['childs'][$k]['native_id'] = $native_child_id;
						}
						$gr_xnm_trade_x[$one_c_gr[$i]['childs'][$k]['id']]=$native_child_id ;
					}//////for($k=0; $k<count($one_c_gr[$i]['childs']); $k++) {
				}///////if(isset($native_id) AND isset($one_c_g
				
			}/////////if(isset($one_c_gr)) for($i=0; $i<count($one_c_gr); $i++) {
			echo '<pre>';
			//print_r($gr_xnm_trade_x);
			echo '</pre>';
				
				
			//print_r($one_c_product);
			
			
					
			///////////////Теперь товары
			
			if(isset($one_c_product)) {
			
			$start = Yii::app()->getRequest()->getParam('start', 0);
			$end = Yii::app()->getRequest()->getParam('end', count($one_c_product));
			//$needed_cat=array(205,199,206,200,201,202,204,207, 182,183,184,185, 181, 180);
			//$needed_cat=array(179, 191, 198, 165, 257, 234, 325, 154, 497, 297, 392, 315, 319, 431, 219, 144, 186, 208, 255, 312, 339, 354, );
			//$needed_cat=array(193,194,195.196,197,  203, 166, 167, 168, 169, 170, 171, 172, 173, 174, 175, 176, 177, 178);
				//$needed_cat=array(195,196,  258, 259, 260, 261, 262, 263, 264, 265, 266, 267, 268, 269, 270, 271, 272, 273, 274, 275, 276, 277, 278, 279, 280, 281, 282, 283, 284, 285, 286, 287, 288, 289, 290, 291, 292, 293, 294, 295, 296);
				
				/*
				Игорь, привет! 
Заливай товар следующей последовательностью:
 
Аксессуары для мобильных телефонов
Домашняя электроника
Мультимедиа
Компьютерные аксессуары 
Совместимые расходные материалы
Оригинальные расходные материалы
Цифровое фото/видео оборудование
Комплектующие
Системы Видеонаблюдения

Сетевое и коммуникационное оборудование
Игровые устройства
Устройства печати
Устройства ввода, мыши, манипуляторы
Сувениры и Бизнес-подарки
Карты памяти, USB носители
Кабели, адаптеры, разъемы и розетки
Автомобильные аксессуары
Оптические носители, дискеты
Портативные навигационные устройства
Телефония
Офисное и Демооборудование
Прогр. Обеспечение

Калькуляторы
Бумага

Изделия из бумаги для офиса
Канцтовары для офиса
Письменные и чертежные принадлежности

Папки, файлы
Школьная канцелярия
Аксессуары для бытовой техники
Устройства связи

Элементы питания стандартные

				*/
				//$needed_cat=array(155,156,157,158,159,160,161,162, 163, 236, 237, 237, 239, 240, 241, 242, 243, 244, 245, 246, 247, 248, 249, 250, 251, 252, 253, 254, 327, 328, 329, 330, 331,332,333, 334, 335, 336, 337, 338);
				//$needed_cat=array(498, 499, 500, 298, 299, 300, 301, 302, 303, 304, 305, 306, 307, 308, 309, 310, 311, 393, 394, 395, 316, 317, 318, 320, 321, 322, 323, 324, 433,434, 435, 436, 437, 438, 439, 440, 442, 442, 443, 444, 445,446,447,448, 220, 221,222,223,224,225,226,227,228,229,230,231,232,233, 145,146,147,148,149,150,151,152,153); //////////////по Кабели, адаптеры, разъемы и розетки
				
				//$needed_cat=array(
				$no_needed_cat = array(498, 499, 500, 298, 299, 300, 301, 302, 303, 304, 305, 306, 307, 308, 309, 310, 311, 393, 394, 395, 316, 317, 318, 320, 321, 322, 323, 324, 433,434, 435, 436, 437, 438, 439, 440, 442, 442, 443, 444, 445,446,447,448, 220, 221,222,223,224,225,226,227,228,229,230,231,232,233, 145,146,147,148,149,150,151,152,153, 155,156,157,158,159,160,161,162, 163, 236, 237, 237, 239, 240, 241, 242, 243, 244, 245, 246, 247, 248, 249, 250, 251, 252, 253, 254, 327, 328, 329, 330, 331,332,333, 334, 335, 336, 337, 338, 195,196,  258, 259, 260, 261, 262, 263, 264, 265, 266, 267, 268, 269, 270, 271, 272, 273, 274, 275, 276, 277, 278, 279, 280, 281, 282, 283, 284, 285, 286, 287, 288, 289, 290, 291, 292, 293, 294, 295, 296, 193,194,195.196,197,  203, 166, 167, 168, 169, 170, 171, 172, 173, 174, 175, 176, 177, 178, 179, 191, 198, 165, 257, 234, 325, 154, 497, 297, 392, 315, 319, 431, 219, 144, 186, 208, 255, 312, 339, 354, 205,199,206,200,201,202,204,207, 182,183,184,185, 181, 180 );
				

			
			
			
			
				for($i=$start; $i<$end; $i++) {
						//if(isset($one_c_product[$i]) AND isset($gr_xnm_trade_x[$one_c_product[$i]['category_belongs']]) AND in_array( $gr_xnm_trade_x[$one_c_product[$i]['category_belongs']] ,$needed_cat) ) {
							//HP-C6570C
						if(isset($one_c_product[$i]) AND isset($gr_xnm_trade_x[$one_c_product[$i]['category_belongs']]) AND !in_array( $gr_xnm_trade_x[$one_c_product[$i]['category_belongs']] ,$no_needed_cat) ) {
								$native_id = $this->createproduct($gr_xnm_trade_x[$one_c_product[$i]['category_belongs']], $one_c_product[$i]['name'], array(684=>$one_c_product[$i]['trademark']), $one_c_product[$i]);
								if (isset($native_id)) {
									$one_c_product['native_id'] = $native_id;
									if(isset($one_c_product[$i]['images']))$this->save_product_images($native_id, $one_c_product[$i]['images']);
								}
						}
				}///////for($i=0; $i<count(); $i++) {
				//createproduct($cat, $prod_name, $char_val)
			}////////if(isset($one_c_product)) {
			
				
				
		}//////////	if(isset($transfer)) {////////
		
		$this->render('1cxml', array('one_c_product'=>@$one_c_product, 'one_c_gr'=>@$one_c_gr, 'gr_xnm_trade_x'=>@$gr_xnm_trade_x));
	}/////////public function actionRead1CXML(){
	
	public function actionGrabfullburokrat(){
		$this->render('burfullxml');
	}
	
	private function download_image($product_id, $image, $k) {
		
		$fp = @fopen($image, 'rb');
					if(isset($fp) AND empty($fp)==false){
							//$dest_file =  "E:\\temp\\temp_".$product_id."_".$k;
							$dest_file =  "c:\\temp\\temp_".$product_id."_".$k;
							

							@unlink($dest_file);
							$fd = fopen($dest_file, 'x');
							if ($fp && $fd) {
							while (!feof($fp)) {
							$st = fread($fp, 44096);
							fwrite($fd, $st);
							}
							}
							@fclose($fp);
							@fclose($fd);
							
							$size = getimagesize($dest_file);
							//print_r($size);
											//echo $downloaded_file['tmp_name'];
							 if ($size[2] == "1")$extension = "gif";
							 if ($size[2] == "2")$extension = "jpg";
							 if ($size[2] == "3")	$extension = "png";
							 
							 $file_size = $size[0]*$size[1];
							 $sizes[$file_size]=array('img'=>$k, 'file'=>$dest_file, 'ext'=>$extension);
							// rename ( string $oldname , string $newname [, resource $context ] )
							if(isset($extension)) {
								//rename($dest_file, $dest_file.'.'.$extension);
							}
							
							if(isset($sizes[$file_size])) return array('size'=>$file_size, 'arr'=>$sizes[$file_size]);
							else  return NULL;
					}/////////////if(isset($fp) AND empty($fp)==false){
		
	}//////////private function download_image($product_id, $image) {
	
	public function save_product_images($product_id, $images){/////////////////Сохранение картинок по ссылками
			//print_r($images);
			$dest_location = $_SERVER['DOCUMENT_ROOT'].'\\pictures\\add\\';
			if(isset($images)) for($k=0; $k<count($images); $k++) {
				if(strstr($images[$k], 'bigimage')) $fdownload = $this->download_image($product_id, $images[$k], $k); 
				if (isset($fdownload) AND $fdownload!= NULL)  $sizes[$fdownload['size']]=$fdownload['arr'];
			}//////////////if(isset($images)) for($k=0; $k<coun
			
			if(isset($sizes)==false) {//////Смотрим на средние фотки
				if(isset($images)) for($k=0; $k<count($images); $k++) {
					if(strstr($images[$k], 'ibb')) $fdownload = $this->download_image($product_id, $images[$k], $k); 
					if (isset($fdownload) AND $fdownload!= NULL)  $sizes[$fdownload['size']]=$fdownload['arr'];
				}/////////if(isset($images)) for($k=0; $k<count($images); $k++) { второй проход
			}/////////if(isset($sizes)==false) {
			
			echo '<pre>';
			print_r($sizes);
			echo '</pre>';
			
			//echo '<br>';
			//$bigest_file = max($sizes);
			$k=1;
			foreach($sizes as $size=>$img){
				
						///////////Делаем запись в таблицу файлов pictures
						$PICTURE = new Pictures;
						$PICTURE->ext =$img['ext'];
						try {
								$PICTURE->save();
								if(isset($PICTURE->id)) {
									$db_filename = $PICTURE->id.'.'.$img['ext'];
									$PP = new  Picture_product;
									$PP->product = $product_id;
									$PP->picture = $PICTURE->id;
									//if($img==$bigest_file)  $PP->is_main = 1;
									if($k==1)  $PP->is_main = 1;
									try {
										$PP->save();
										//echo $img['file'].'<br>';
										//echo $dest_location.$db_filename.'- нов карт<br>';
										@unlink($dest_location.$db_filename);
										rename($img['file'], $dest_location.$db_filename);
										@unlink($img['file']);
										
										////////////И делаем миниатюру
										$SRC = $PICTURE;
										$key = $SRC->id;
										$srctfile =  Yii::app()->request->baseUrl."/pictures/add/".$key.'.'.$SRC->ext;
										//echo $srctfile;
										if (file_exists($_SERVER['DOCUMENT_ROOT'].'/'.$srctfile)) {
												$resize_to = "height=200";
												$outfldr = Yii::app()->request->baseUrl."/pictures/add/icons";
												$iconfile =  Yii::app()->request->baseUrl.'/pictures/add/icons/'.$key.'.png';		
												@unlink($_SERVER['DOCUMENT_ROOT'].Yii::app()->request->baseUrl.'/'.$iconfile);
												$f=fopen("http://".Yii::app()->params['apache_auth'].$_SERVER['HTTP_HOST'].Yii::app()->request->baseUrl."/workflow/make_mini.php?create=1&$resize_to&imgname=$srctfile&outfldr=$outfldr", 'r');/////Создаем таким образом миниатюру
												//echo "http://".$_SERVER['HTTP_HOST'].Yii::app()->request->baseUrl."/workflow/make_mini.php?create=1&$resize_to&imgname=$srctfile&outfldr=$outfldr";		
												fclose($f);
										}/////////if (file_exists($_SERVER['DOCUMENT_ROOT'].'/'.$srctfile)) {
										unset ($SRC);
										
									} catch (Exception $e) {
										 echo 'Caught exception: ',  $e->getMessage(), "\n";
									}//////////////////////
								}
							
						} catch (Exception $e) {
								 echo 'Caught exception: ',  $e->getMessage(), "\n";
						}//////////////////////

			//	}/////////if($sizes[$i]==$bigest_file) {////////копируем
				/*
				else {////////////удаляем
					@unlink($img['file']);
				}/////////////////else {////////////удаляем
				*/
				$k++;
			}///////foreach($sizes as $size=>$img){
			
			
	}////////////////public function save_product_images($product_id, $images){//
	
	public function save_product_images2($product_id, $images){/////////////////Сохр
	
		$dest_location = $_SERVER['DOCUMENT_ROOT'].'\\pictures\\add\\';
		for($i=0; $i<count($images); $i++) {
			$size = getimagesize($images[$i]);
			//print_r($size);
			
							//echo $downloaded_file['tmp_name'];
			 if ($size[2] == "1")$extension = "gif";
			 if ($size[2] == "2")$extension = "jpg";
			 if ($size[2] == "3")	$extension = "png";
			 $file_size = $size[0]*$size[1];
			 $img=array('img'=>$i, 'file'=>null, 'ext'=>$extension);
		///////////Делаем запись в таблицу файлов pictures

			 
						$PICTURE = new Pictures;
						$PICTURE->ext =$img['ext'];
						try {
								$PICTURE->save();
								if(isset($PICTURE->id)) {
									$db_filename = $PICTURE->id.'.'.$img['ext'];
									$PP = new  Picture_product;
									$PP->product = $product_id;
									$PP->picture = $PICTURE->id;
									//if($img==$bigest_file)  $PP->is_main = 1;
									if($i==0)  $PP->is_main = 1;
									try {
										$PP->save();
										//echo $img['file'].'<br>';
										//echo $dest_location.$db_filename.'- нов карт<br>';
										@unlink($dest_location.$db_filename);
										//rename($img['file'], $dest_location.$db_filename);
										//@unlink($img['file']);
										copy($images[$i], $dest_location.$db_filename);
										
										////////////И делаем миниатюру
										$SRC = $PICTURE;
										$key = $SRC->id;
										$srctfile =  Yii::app()->request->baseUrl."/pictures/add/".$key.'.'.$SRC->ext;
										
										if (file_exists($_SERVER['DOCUMENT_ROOT'].'/'.$srctfile)) {
												$resize_to = "height=300";
												$outfldr = Yii::app()->request->baseUrl."/pictures/add/icons";
												$iconfile =  Yii::app()->request->baseUrl.'/pictures/add/icons/'.$key.'.png';		
												@unlink($_SERVER['DOCUMENT_ROOT'].Yii::app()->request->baseUrl.'/'.$iconfile);
												
												$fff= "http://".Yii::app()->params['apache_auth'].$_SERVER['HTTP_HOST'].Yii::app()->request->baseUrl."/pictures/make_mini.php?create=1&$resize_to&imgname=$srctfile&outfldr=$outfldr";

												$f=fopen($fff, 'r');/////Создаем таким образом миниатюру
												//echo "http://".$_SERVER['HTTP_HOST'].Yii::app()->request->baseUrl."/workflow/make_mini.php?create=1&$resize_to&imgname=$srctfile&outfldr=$outfldr";		
												fclose($f);
												
												
												$resize_to = "height=150";
												$outfldr = Yii::app()->request->baseUrl."/pictures/add/icons_small";
												$iconfile =  Yii::app()->request->baseUrl.'/pictures/add/icons_small/'.$key.'.png';		
												@unlink($_SERVER['DOCUMENT_ROOT'].Yii::app()->request->baseUrl.'/'.$iconfile);
												$f=fopen("http://".Yii::app()->params['apache_auth'].$_SERVER['HTTP_HOST'].Yii::app()->request->baseUrl."/pictures/make_mini.php?create=1&$resize_to&imgname=$srctfile&outfldr=$outfldr", 'r');/////Создаем таким образом миниатюру
												//echo "http://".$_SERVER['HTTP_HOST'].Yii::app()->request->baseUrl."/workflow/make_mini.php?create=1&$resize_to&imgname=$srctfile&outfldr=$outfldr";		
												fclose($f);
												
												
										}/////////if (file_exists($_SERVER['DOCUMENT_ROOT'].'/'.$srctfile)) {
										unset ($SRC);
										
									} catch (Exception $e) {
										 echo 'Caught exception: ',  $e->getMessage(), "\n";
									}//////////////////////
								}
							
						} catch (Exception $e) {
								 echo 'Caught exception: ',  $e->getMessage(), "\n";
								// exit();
						}//////////////////////

			//	}/////////if($sizes[$i]==$bigest_file) {////////копируем
				/*
				else {////////////удаляем
					@unlink($img['file']);
				}/////////////////else {////////////удаляем
				*/

				}
	}///////////	public function save_product_images2($product_id, $images){//////////////
	
	
	private function setChemimartProductPrice($prod_id, $package,$article, $price){
	    
	    /*
	     mg 2
	     g 7
	     
	     
	     */
	    $units = 'g';
	    $unit_code = 7;
	    $volume = trim(str_replace('g', '', $package));
	    
	    if(strpos($package,'mg')){
	        $volume = trim(str_replace('mg', '', $package));
	        $units = 'mg';
	        $unit_code = 2;
	    }

	    if( $units == 'mg') $code=trim(str_replace('-', '', $article)).'.'.sprintf('%04d', $volume);
	    if( $units == 'g') $code=trim(str_replace('-', '', $article)).'.'.sprintf('%04d', $volume*1000);
	    
	    $pvar = new PriceVariations();
	    $pvar->volume = $volume;
	    $pvar->code = $code ;
	    $pvar->price = $price;
	    $pvar->active = 1;
	    $pvar->product = $prod_id;
	    
	    try {
	        $pvar->save();
	        $prod=Products::model()->findByPk($prod_id);
	        if($prod!=null){
    	        $prod->measure = $unit_code;
    	        try {
    	            $prod->save();
    	        } catch (Exception $e) {
    	        }
	        }
	        
	    } catch (Exception $e) {
	        echo 'Caught exception: ',  $e->getMessage(), "\n";
	        exit();
	    }//////////////////////
	    
	}
	
	
	public function actionRenameproducts(){
		//echo 'werwe<br>';
		$data = new Spreadsheet_Excel_Reader('c:\wwwroot\articles.xls');
		echo '<pre>';
		//print_r($data);
		echo '</pre>';
		//echo $data->dump(true,true);
		if (isset($data)) {
				
				$products = Products::model()->findAll();
				$products_list = CHtml::listdata($products, 'product_name', 'id');
				
				$cels = $data->sheets[0]['cells'];
				//print_r($cels);
				for ($i=1;$i<=count($cels); $i++) {
					if ($cels[$i][2]!='' AND $cels[$i][3]!='') {
						//print_r($cels[$i]);
						
						//echo $cels[$i][2].' - '.$cels[$i][3];
						$this->sootnosh['CRT '.$cels[$i][2]]=$cels[$i][3];
					//	if (isset($products_list['CRT '.$cels[$i][2]])) {
							//$PROD = Products::model()->findByPk($products_list['CRT '.$cels[$i][2]])	;	
						//	if (isset($PROD)) {
								//$PROD->product_name = $cels[$i][3];
								//$PROD->save();
					//		}
						//	echo ' есть';
						//}
					//	echo '<br>';
					}
					print_r($this->sootnosh);
				}
				
		}
			
	}
	
	
	
}
