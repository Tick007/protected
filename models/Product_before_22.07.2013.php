<?php

class Product extends CFormModel {
		var $connection;
		var $query;
	
		var $query1; ////////////для выборки складов
		var $stores_id;////////Массив для хранения id складов
		var $stores_names;////////Массив для хранения имен складов
		
		var $command;
		var $dataReader;
		var $row;
		//public $item_list;
		public $count_alias = "id_count";
		public $offset;
		public $limit;
		var $result;
		public $group_caract_main_param; ////////////Массив ИД характеристики главного параметра для группы
		public $main_param_name;//////массив имен главного параметра
		
		public $num_of_rows;///////////Число строк в выполненном запросе
		
		
		public $show_group; ///////////Группа товара

		private $r;
		public $pd;
		private $page; ////////////Для перехода по страницам
		
		public $out_mode;////////////////Режим вывода - список/мал картинки/большие картинки
		public $sort_order ;
		
		public $cfid;
		public $cfv;
		public $cfid_arr;
		
		public $sub_cat_ids; ///////////////массив Идентификаторов характеристик для группы
		public $sub_cat_names;
		public $char_values_list;
		public $charact_list;
		
		public $product_vitrina;
		public $orderby; 
		public $creteria;
		public $filters;
		//public $id;
		public $temp_table_name;//////////////имя временной таблицы товаров
		public $ostatki_temp_table_name;/////////////имя временной таблицы остатков
		public $char_val_temp_table_name;/////////////имя временной таблицы для значений характеристик


	/**
	 * Returns the static model of the specified AR class.
	 * @return CActiveRecord the static model class
	 */
	 

	 
	function __construct($sgp=NULL){ /////////////добавил специально чтобы вытаскивать товары задавая группу вручную (ольгин сайт)
	
			
			//print_r($this->filters);
			//echo $this->elements['main_parametr1'];
			if($sgp==NULL) $sgp = Yii::app()->getRequest()->getParam('id', NULL);
			
			$alias = Yii::app()->getRequest()->getParam('alias');
			
			$sort = Yii::app()->getRequest()->getParam('sort', 3);
			
			
			
			
			switch ($sort) {
				case '2':
					$sort_order = '  products.product_price';
					break;
				case '2d':
					$sort_order = ' products.product_price DESC';
					break;
				
								
				case '1':
					$sort_order = 'products.product_name';
					break;
				case '1d':
					$sort_order = 'products.product_name DESC';
					break;
					
				
					
				default: 
					$sort_order=Yii::app()->params['default_products_sort'];
				}
				
			$this->orderby =$sort_order;//////Изначально группируем услуги сначала с ценой, потом цена "от", потом без цены.
			


			if ($sgp==NULL AND trim($alias)) {
				$cat = Categories::model()->findbyAttributes(array('alias'=>$alias));
			}//////////if ($show_group==NULL AND trim($alias)) {
			if 	(isset($cat->category_id)) {
				$sgp = $cat->category_id;
			}
			else {
				$cat = Categories::model()->findbyPk($alias);
					if 	(isset($cat->category_id)) $sgp = $cat->category_id;
				}
			
						
			if (is_numeric(trim(htmlspecialchars($sgp)))==true) $this->show_group = $sgp;
			//echo 'qqqq = '.$this->show_group;
			//echo "Версия 1.0.13.<br>";
			$this->connection = Yii::app()->db;
			/////////////////////////////Инициализируем склады
			 $this->query1= "SELECT id, name FROM stores WHERE kontragent_id = ".Yii::app()->GP->GP_self_contragent." AND show_in_html=1  ORDER BY is_main DESC";
			$this->command=$this->connection->createCommand($this->query1);
			$this->dataReader=$this->command->query();
			while(($this->row=$this->dataReader->read())!==false) {
			$this->stores_id[]=$this->row['id'];
			$this->stores_names[]=$this->row['name'];
			
			}
			
			
			
						/*
			if (isset($_GET['show_group']) AND is_numeric(trim(htmlspecialchars($_GET['show_group'])))==true) $this->show_group=trim(htmlspecialchars($_GET['show_group']));
			*/
			//print_r($_GET);
			$pd = Yii::app()->getRequest()->getParam('pd', NULL);
			if ($pd==NULL AND Yii::app()->controller->id=='adminproducts') $pd = Yii::app()->getRequest()->getParam('id', NULL);
			
			
			if (isset($pd) AND is_numeric(trim(htmlspecialchars($pd)))==true) $this->pd=trim(htmlspecialchars($pd));
			
			while (list($key, $val) = each($_GET)) {
			if (@isset($_GET[$key]) AND isset($this->$key)  ) $this->$key = addslashes(substr(trim(htmlspecialchars($_GET[$key])),0,255));
			//else $$key=NULL;	
			}
			
			//print_r($_POST);
			
			$ListForm = Yii::app()->getRequest()->getParam('ListForm') ;	
			
			//print_r($ListForm);
			
			if(isset($ListForm)) {
				foreach($ListForm as $name=>$value)//////////////Получаем все параметры с формы (POST)
				{
					if(isset($value)) $this->$name=$value;
					//echo $name.' = '.$value.'<br>';
					//print_r($value);
				}
			}
			
			$this->filters = Yii::app()->getRequest()->getParam('filters');
			
			$debug = Yii::app()->getRequest()->getParam('debug');
			if(isset($debug) ) {
				print_r($this->filters);
				echo '<br>';
			}
			
			//////////////////////////////////////////////////////////////////////////Это характеристики пришедшии через пост

			$cid = 		Yii::app()->getRequest()->getParam('cid') ;		
			$pid = 		Yii::app()->getRequest()->getParam('pid') ;	
						
			if (isset($cid) AND isset($pid) ) {
					$this->cfid_arr[$cid]=array($pid=>1);
			}///////////////////
			
			//////////////////Теперь смотрим передан ли филтр как строка
			//$show_group = Yii::app()->getRequest()->getParam('id') ;	
			$vendor=Yii::app()->getRequest()->getParam('vendor') ;	
			//echo $vendor;
			//$vendor= iconv("UTF-8", "CP1251", $vendor);
			//print_r($this->cfid_arr);
			
			//exit;
			//if(isset($_POST['out_mode']))  $this->out_mode = $_POST['out_mode'];

			//$show_group = Yii::app()->getRequest()->getParam('id') ;
			
			if(isset($this->pd) AND (!isset($this->show_group) OR !trim($this->show_group) )){
			 $this->show_group = $this->get_product_belong_to();
			 
			Yii::app()->GP->GP_sg =  $this->show_group;
			}
				//echo $this->show_group;

				
			//$this->out_mode = Yii::app()->GP->GP_list_view;
			$this->CheckView();
			//$this->sort_order = 0;
			
			//echo '1';
			
			if(isset(Yii::app()->params['use_temp_tables']) AND Yii::app()->params['use_temp_tables']==true) {
			//echo '2';
			
			$connection=Yii::app()->db;
			$this->temp_table_name = "products_".$this->show_group;
			
			
			
			
			$query="SHOW COLUMNS FROM products ";//////////////////Создаем временную таблицу для группы
			$command=$connection->createCommand($query)	;
			$dataReader=$command->query();
			$products_columns = $dataReader->readAll();
			
			$query="SHOW COLUMNS FROM ostatki_trigers ";/////////////
			$command=$connection->createCommand($query)	;
			$dataReader=$command->query();
			$ostatki_columns = $dataReader->readAll();	
			
			
			$query="SHOW COLUMNS FROM characteristics_values ";//////////////
			$command=$connection->createCommand($query)	;
			$dataReader=$command->query();
			$char_val_columns = $dataReader->readAll();	
				
			
			$creation_query = $this->get_temp_table('products', $this->temp_table_name, $connection, $products_columns);
			
			
			//echo $creation_query;
			//exit();
			/////////Первый запрос на вытаскивание товаров в категории и кладование их во временную таблицу poducts_номер группы
			
			
			$extra_query="SHOW TABLE STATUS LIKE  '$this->temp_table_name' ";
			$command=$connection->createCommand($extra_query)	;
			$extradataReader=$command->query();	
			$extra_res = $extradataReader->readAll();
			$created_ago = microtime(true) - strtotime($extra_res[0]['Create_time']);
			$record = $extra_res[0]['Rows'];
			if($record == 0 OR ($record >0 AND $created_ago>86400) ) {
			
			
					$query = $creation_query."
					INSERT INTO ".$this->temp_table_name."  (id,	product_name,	product_article,	category_belong	,product_visible,	new_product,	product_html_title,	product_vitrina,	product_sellout,	product_price,	product_price_old,	product_price_vip,	number_in_store,	product_visible_for_xml,	product_made_in,	product_dlina,	product_shirina,	product_visota,	product_ves,	product_size,	product_color,	product_sort,	product_manufacture,	product_warranty,	measure,	nds_out	,guid1,	product_parent_id,	product_vitrina_sort,	product_new,	product_new_sort,	product_sellout_sort,	sellout_price,	sellout_active_till_int,	product_html_keywords,	product_html_description,	contragent_id) SELECT products.id,	product_name,	product_article,	 category_belong	,product_visible,	new_product,	product_html_title,	product_vitrina,	product_sellout,	product_price,	product_price_old,	product_price_vip,	number_in_store,	product_visible_for_xml,	product_made_in,	product_dlina,	product_shirina,	product_visota,	product_ves,	product_size,	product_color,	product_sort,	product_manufacture,	product_warranty,	measure,	nds_out	,guid1,	product_parent_id,	product_vitrina_sort,	product_new,	product_new_sort,	product_sellout_sort,	sellout_price,	sellout_active_till_int,	product_html_keywords,	product_html_description,	contragent_id FROM products";
		if($this->show_group>0)  $query.=" WHERE category_belong= ".$this->show_group;
					$query.=" UNION
						 SELECT products.id,	product_name,	product_article,		category_belong	,product_visible,	new_product,	product_html_title,	product_vitrina,	product_sellout,	product_price,	product_price_old,	product_price_vip,	number_in_store,	product_visible_for_xml,	product_made_in,	product_dlina,	product_shirina,	product_visota,	product_ves,	product_size,	product_color,	product_sort,	product_manufacture,	product_warranty,	measure,	nds_out	,guid1,	product_parent_id,	product_vitrina_sort,	product_new,	product_new_sort,	product_sellout_sort,	sellout_price,	sellout_active_till_int,	product_html_keywords,	product_html_description,	contragent_id FROM products  JOIN categories_products ON categories_products.product = products.id ";
		if($this->show_group>0)  $query.=" WHERE categories_products.group = ".$this->show_group; 
				
			}
				 
			$command=$connection->createCommand($query)	;
			$dataReader=$command->query();
			
			if(isset($debug) ) {
				print_r($query);
				echo '<br>';
				echo '<br>';
				echo '<br>';
			}
			
			//echo $query;
			//exit();
			
					
			
			//////////////////////теперь нужно как то ограничить склады
			if(isset($this->stores_id)){
				
				
				
				$this->ostatki_temp_table_name = "ostatki_trigers_".$this->show_group;
				$creation_query = $this->get_temp_table('ostatki_trigers', $this->ostatki_temp_table_name, $connection, $ostatki_columns);
				
				$query=$creation_query." 

				INSERT INTO  ".$this->ostatki_temp_table_name." (	id, 	tovar, 	store, 	quantity, 	store_price ) SELECT id, 	tovar, 	store, 	quantity, 	store_price FROM ostatki_trigers WHERE ostatki_trigers.tovar IN(SELECT id FROM ".$this->temp_table_name.") ";
				//echo  $query;
				//exit();
				$command=$connection->createCommand($query)	;
				$dataReader=$command->query();
				//$prods = $dataReader->readAll();
				//print_r($prods);
			}
			
			
			///////////Временная таблица для опций
			$this->char_val_temp_table_name = 'characteristics_values_'.$this->show_group;
			$creation_query = $this->get_temp_table('characteristics_values', $this->char_val_temp_table_name, $connection, $char_val_columns);
			
			
			$extra_query="SHOW TABLE STATUS LIKE  '$this->char_val_temp_table_name' ";
			$command=$connection->createCommand($extra_query)	;
			$extradataReader=$command->query();	
			$extra_res = $extradataReader->readAll();
			$created_ago = microtime(true) - strtotime($extra_res[0]['Create_time']);
			$record = $extra_res[0]['Rows'];
			if($record == 0 OR ($record >0 AND $created_ago>86400) ) {
				//echo '<br>пора обнавляь<br>';
			
			$query=$creation_query."
			DELETE FROM  ".$this->char_val_temp_table_name." ;
			 INSERT INTO  ".$this->char_val_temp_table_name." (value_id,	id_caract,	id_product,	value ) SELECT value_id,	id_caract,	id_product,	value FROM characteristics_values WHERE id_product IN(SELECT id FROM ".$this->temp_table_name.") ";
				//echo  $query;
				//exit();
			
			
			}
			
			
			
			
				$command=$connection->createCommand($query)	;
				$dataReader=$command->query();	
			
			
			}///////////if(isset(Yii::app()->['params']['use_temp_tables']) AND Yii::app()->['params']['use_temp_tables']==true) {
			else 	{
				$this->temp_table_name = ' (SELECT * FROM products ';
				if($this->show_group>0)  $this->temp_table_name.=" WHERE category_belong= ".$this->show_group;
				$this->temp_table_name.=" UNION
				 SELECT products.* FROM products  JOIN categories_products ON categories_products.product = products.id ";
				if($this->show_group>0)  $this->temp_table_name.="  WHERE categories_products.group = ".$this->show_group; 
				$this->temp_table_name.="  ) ";
				$this->char_val_temp_table_name = ' characteristics_values ';
				$this->ostatki_temp_table_name = ' ostatki_trigers ';
			}
			
			
			////////////////Вытаскиваем характеристики товаров
			if (isset($this->show_group)) $this->char_list();
			//print_r($this->charact_list);
			
				
			
			$debug = Yii::app()->getRequest()->getParam('debug');
			if(isset($debug) ) {
				echo "filter1<br>";
				print_r($this->filters);
				echo '<br>';
			}
			
			
			}//////////////////	public function __construct(){
			
		
		function get_temp_table($source_table, $temp_table_name, $connection, $columns){
			
		
			$creation_query = "
			CREATE TABLE IF NOT EXISTS `".$temp_table_name."` (";
			foreach($columns as $row){
				 if($row['Type']!='text'){
					$creation_query.= " ".$row['Field']." ".$row['Type']." ";
					if($row['Null']=='NO') $creation_query.=" NOT NULL ";
					elseif($row['Null']=='YES'){
						if($row['Default'] !=NULL) $creation_query.=" DEFAULT ".$row['Default'];
						else $creation_query.=" DEFAULT NULL ";
					}
					$creation_query.=" ".strtoupper($row['Extra']);
					$creation_query.=", ";
				}
			}
			
			if($source_table!='characteristics_values') $creation_query.= " UNIQUE KEY `id` (`id`) ";
			else $creation_query.= " UNIQUE KEY `value_id` (`value_id`) ";
			
			//$creation_query.= " ) ENGINE=MEMORY  DEFAULT CHARSET=utf8  AUTO_INCREMENT=1 ;
			
			$creation_query.= " ) ENGINE=MyISAM  DEFAULT CHARSET=utf8  AUTO_INCREMENT=1 ;
			
					
			
			
			
			
			";//;TRUNCATE TABLE $temp_table_nameALTER TABLE $temp_table_name MAX_ROWS=4294967295 ;DELETE FROM $temp_table_name;
			return $creation_query;
		}
		
		function ExecuteObject(){
			
		//$time1=microtime(true);	
			
		//echo "mp1 = ".$this->main_parametr1."<br>";
		$connection=Yii::app()->db;
		$temp_table_name = $this->temp_table_name;
		$ostatki_temp_table_name = $this->ostatki_temp_table_name;
		
		//exit();
		////////////////для выборки в соответствии с поиском
		$search = Yii::app()->getRequest()->getParam('search', NULL);	
	
		if ($search != NULL AND trim($search)<>'') {////////////
				$search_words=explode(' ', $search);
				$rows =Yii::app()->cache->get(trim($search.'products'));
				//print_r($rows);
		}//////////////////////////
		
		///////////////Вытаскиваем список общих характеристик 
		
		

		
		$criteria=new CDbCriteria;
		//$criteria->order = 'title';
		$criteria->condition = 'is_common = 1';
		$common_char_list = Characteristics::model()->findAll($criteria);//
		for($i=0; $i<count($common_char_list); $i++)  $common_char_arr[]=$common_char_list[$i]->caract_id;////
		//print_r($common_char_arr);
		//print_r(count($common_char_list));
		
		//$time2=microtime(true);
		//echo '2.3.1 - '. ($time2- $time1);
	//	echo '<br>'		;
		
		
		//$this->query="SELECT DISTINCT products.id, products.product_article,  product_attribute.attribute_value AS product_attributes,  parent_categories.category_name  AS sgr, products.product_html_description AS product_html_description ,  products.product_sellout, products.product_new,  number_in_store, ";///////////убрал product_attribute.attribute_value AS product_attributes,
		$this->query="SELECT DISTINCT products.id, products.product_article,  parent_categories.category_name  AS sgr, products.product_html_description AS product_html_description ,  products.product_sellout, products.product_new,  number_in_store, ";
		if (isset($this->pd) AND is_numeric(trim(htmlspecialchars($this->pd)))==true ) $this->query.=" CONCAT_WS(',', products.product_name , product_attribute.attribute_value) AS product_name, ";
		else $this->query.=" products.product_name, ";
	//	  $this->query.=" categories.category_name  AS gr, price_list.price_with_nds, round(products.product_price*(1+products.nds_out),3) AS price_card, 	  price_list2.price_with_nds AS price_with_nds2, products.category_belong";
	$this->query.=" categories.alias, categories.path, categories.category_name  AS gr, price_list.price_with_nds, round(products.product_price,2) AS price_card, 	  price_list2.price_with_nds AS price_with_nds2, products.category_belong, products.product_html_description, products.product_price_old ";
		for($k=0;$k<count($this->stores_id);$k++) {
		$kk=$k+1;
		$this->query.=", store".$kk.".quantity AS prihod_store".$kk.",  0 AS rashod_store".$kk."  ";
		$ostatki_parts[] =  " IFNULL(store".$kk.".quantity,0) ";
		}
		//$this->query.=", (".implode('+',$ostatki_parts).") as ostatki_in_store " ;
		 if(isset($ostatki_parts)) $this->query.=", ".implode('+',$ostatki_parts)." AS trigers_ostatki " ;
		  $this->query.=" , categories.alias AS catalias ";
		// $this->query.="	, picture_product.picture AS icon "; ////////Жутко тормозит с картинками
		
		 // $this->query.=
		 /*"FROM  /* (SELECT products.id, products.product_article,  products.category_belong, products.product_price, products.nds_out, products.product_visible, products.product_name , products.product_parent_id, products.product_vitrina, products.product_html_description, products.product_sellout, products.product_new, number_in_store FROM $temp_table_name products  WHERE products.category_belong = ".$this->show_group /*."
			 UNION
			 SELECT products.id, products.product_article,  categories_products.group AS category_belong, products.product_price, products.nds_out, products.product_visible, products.product_name , products.product_parent_id, products.product_vitrina, products.product_html_description, products.product_sellout, products.product_new, number_in_store FROM $temp_table_name products  JOIN categories_products ON categories_products.product = products.id WHERE categories_products.group = ".$this->show_group."
			  .") products ";*/
		  $this->query.= " FROM  $temp_table_name products ";
		  
		//  $this->query.=" LEFT JOIN ( SELECT id, product, picture FROM picture_product WHERE is_main=1) picture_product ON picture_product.product = products.id  "; ////////////////Жутко тормозит с картинками
		  $this->query.="JOIN categories  ON categories.category_id = products.category_belong ";
		  $this->query.=" LEFT JOIN  categories AS parent_categories ON categories.parent = parent_categories.category_id  ";
		  
		//	 LEFT JOIN  ";
		  for($k=0;$k<count($this->stores_id);$k++) {
			$kk=$k+1;
			$this->query.=" LEFT JOIN  ";
			$this->query.= "  ".$this->ostatki_temp_table_name." ";
			$this->query.= "   store".$kk;
			if ($k>0) $this->query.= " ON  (products.id = store$kk.tovar AND store$kk.store = ".$this->stores_id[$k]." )"; 
			if ($k==0)$this->query.= "  ON ( products.id = store1.tovar AND store$kk.store = ".$this->stores_id[$k]." )";
			if (($k+1)<count($this->stores_id)) $query1.=" LEFT JOIN ";
		}//////////  for($k=0;$k<count($stores_id);$k++) {
		//////////////////////////////////////////////////////////////////////Для элементов формы
		//print_r($this->cfid_arr);
		
		//$time2=microtime(true);
		//echo '2.3.2 - '. ($time2- $time1);
		//echo '<br>'			;
				
		if (isset($this->cfid_arr)) { /////////////////Массив параметров
		//echo '<br>$this->cfid_arr = ';
		//print_r($this->cfid_arr);
		//echo '<br>';
		//print_r($this->char_values_list);
		//echo '<br>';
			foreach($this->cfid_arr as $name=>$values_id_list)//////////
				{		
						//$name = id характеристики
						/*
						public $sub_cat_ids; ///////////////массив Идентификаторов характеристик для группы
		public $sub_cat_names;
		public $char_values_list;
						*/
						//if(!isset($this->sub_cat_ids)  )  $this->char_list();
						//echo '$this->sub_cat_ids = ';
						//print_r($this->sub_cat_ids);
						//echo '<br>';
						$num = array_search($name, $this->sub_cat_ids); //////////порядковый номер в массиве id характеристик
						//echo '$num = '.$num.'<br>'; 
						//echo '$this->char_values_list = ';
						//print_r($this->char_values_list);
						//echo '<br>';
						$list_caracteristic_values = $this->char_values_list[$name];
						//echo '<br>';
						//print_r($values_id_list);
						//echo 'ewrwerw = <br>';
						//print_r($list_caracteristic_values);

																								
						// $this->query.= " JOIN (SELECT products.id FROM characteristics INNER JOIN (".$this->char_val_temp_table_name." INNER JOIN ";  
						// $this->query.= " $temp_table_name products ON ".$this->char_val_temp_table_name.".id_product = products.id) ON characteristics.caract_id = ".$this->char_val_temp_table_name.".id_caract  GROUP BY products.id, ".$this->char_val_temp_table_name.".value, products.category_belong, characteristics.caract_id HAVING ( ";
						$this->query.= "  JOIN ( SELECT id_product FROM  $this->char_val_temp_table_name characteristics_values WHERE ( ";
						$sch=0;
						$vsego = count($values_id_list);
						//print_r($list_caracteristic_values);
						//print_r($values_id_list );
						foreach ($values_id_list as $k=>$v) {
								$sch++;
								$this->query.= "(characteristics_values.value ='".$list_caracteristic_values[$k]."' AND  characteristics_values.id_caract = $name )";
								if ($sch<$vsego ) $this->query.= " OR ";
						}
						//$this->query.=" AND ((products.category_belong)=".$this->show_group.")";//////устарело
						//$this->query.="  AND (characteristics.caract_id)=(".$name.")  ";
						//if (!in_array($name, $common_char_arr)) $this->query.="  AND (characteristics.caract_id)=(".$name.")  "; ////т.е. если категория не общая то фильтруем по ней, иначе - нет, т.е. шерстим всме группы
						//if (!in_array($name, $common_char_arr)) $this->query.="  AND ( characteristics_values.id_caract)=(".$name.")  "; ////т.е. если категория не общая то фильтруем по ней, иначе - нет, т.е. шерстим всме группы
						$this->query.=" ) ) main_parametr_$name$k ON main_parametr_$name$k.id_product = products.id ";
						//																}
				}
			}/////////if (isset($this->cfid_arr)) { //////////////
		////////////////////////////////////////////////////////////////////////////////////////////////
		
		//$time2=microtime(true);
		//echo '2.3.3 - '. ($time2- $time1);
		//echo '<br>'			;
		
			$this->query.= "LEFT  JOIN (SELECT price_list_header.creation_dt, price_list_products_list.product_id, price_list_products_list.price_with_nds, price_list_header.price_type, dates_products.cr_dt
			FROM price_list_products_list
			JOIN price_list_header ON price_list_header.id = price_list_products_list.pricelist_id
			JOIN (
			
			SELECT MAX( price_list_header.creation_dt ) AS cr_dt, price_list_products_list.product_id
			FROM price_list_products_list
			JOIN price_list_header ON price_list_header.id = price_list_products_list.pricelist_id
			WHERE price_list_header.price_type =1 AND price_list_header.status = 1 AND price_list_header.currency = ".Yii::app()->GP->GP_shop_currency." 	GROUP BY price_list_products_list.product_id
			)dates_products ON dates_products.cr_dt = price_list_header.creation_dt
			WHERE price_list_header.price_type =1  AND price_list_header.currency =".Yii::app()->GP->GP_shop_currency."
			AND dates_products.product_id = price_list_products_list.product_id) price_list ON products.id=price_list.product_id ";
			
			$this->query.= " LEFT  JOIN (SELECT price_list_header.creation_dt, price_list_products_list.product_id, price_list_products_list.price_with_nds, price_list_header.price_type, dates_products.cr_dt
			FROM price_list_products_list
			JOIN price_list_header ON price_list_header.id = price_list_products_list.pricelist_id
			JOIN (
			
			SELECT MAX( price_list_header.creation_dt ) AS cr_dt, price_list_products_list.product_id
			FROM price_list_products_list
			JOIN price_list_header ON price_list_header.id = price_list_products_list.pricelist_id
			WHERE price_list_header.price_type =2 AND price_list_header.status = 1  AND price_list_header.currency =".Yii::app()->GP->GP_shop_currency."	GROUP BY price_list_products_list.product_id
			)dates_products ON dates_products.cr_dt = price_list_header.creation_dt
			WHERE price_list_header.price_type =2  AND price_list_header.currency =".Yii::app()->GP->GP_shop_currency." 
			AND dates_products.product_id = price_list_products_list.product_id) price_list2 ON products.id=price_list2.product_id ";
			
		//	$time2=microtime(true);
		//echo '2.3.4 - '. ($time2- $time1);
		//echo '<br>'			;
			
			 if (isset($this->pd) AND is_numeric(trim(htmlspecialchars($this->pd)))==true ) {////для подчиненных товаров
			 $this->query.= " LEFT JOIN (SELECT id_product, GROUP_CONCAT( value) AS  attribute_value
			FROM ".$this->char_val_temp_table_name." characteristics_values JOIN (SELECT * FROM products WHERE product_parent_id >0) child_products ON child_products.id = characteristics_values.id_product GROUP BY id_product ORDER BY characteristics_values.value_id) product_attribute ON product_attribute.id_product = products.id ";
			}///////if (isset($this->pd) AND is_numeric(trim(htmlspecialchars($this->pd)))==true ) {////для
			else {
			// $this->query.= " LEFT JOIN (SELECT id_product, GROUP_CONCAT(CONCAT_WS( ';;', value,id_caract ) SEPARATOR '#') AS  attribute_value 			FROM  ".$this->char_val_temp_table_name." characteristics_values GROUP BY id_product ORDER BY characteristics_values.value_id) product_attribute ON product_attribute.id_product = products.id "; 	//////////////////слишком затратный JOIN даже при относительно большом числе записей
			}
		
			$this->query.= "  WHERE products.product_visible = 1 AND categories.show_category =1 ";
			
			
		//	$time2=microtime(true);
		//echo '2.3.5 - '. ($time2- $time1);
		//echo '<br>'			;
		
		
		if(isset(Yii::app()->params['dont_show_price_null']) AND Yii::app()->params['dont_show_price_null']==true)	$this->query.= " AND products.product_price>0 ";
		///////////////////////Обрезаем по остаткам
		if(isset(Yii::app()->params['dont_show_ostatki_null']) AND Yii::app()->params['dont_show_ostatki_null']==true) $this->query.= " AND (".implode('+',$ostatki_parts).")>0 ";//////////this->query.=", ".implode('+',$ostatki_parts)." AS trigers_ostatki 
		
			if (isset($rows) AND isset($search) AND trim($search)!='' AND empty($rows)==false) $this->query.= "  AND products.id IN (".implode(',', $rows).") ";
			if(isset($search) AND  isset($rows) AND empty($rows)==true) $this->query.= "  AND  (products.product_article LIKE '%".htmlspecialchars(strip_tags($search))."%' OR products.product_name LIKE '%".htmlspecialchars(strip_tags($search))."%' ) ";
			//if (isset($this->show_group)) $this->query.= "  AND (categories.category_id=".intval($this->show_group)." OR categories_products.group = ".intval($this->show_group).") ";
			//if (isset($this->show_group)) $this->query.= "  AND categories.category_id=".intval($this->show_group);///////////не нужно, т.к. используется временные таблицы
			
		//	$time2=microtime(true);
		//echo '2.3.6 - '. ($time2- $time1);
		//echo '<br>'			;
			
		
			//echo $this->query;
			////////////////////// это для вывода либо подчиненных товаров, либо таблички с этим с остатками
			if (isset($this->pd) AND is_numeric(trim(htmlspecialchars($this->pd)))==true ) {////для подчиненных товаров
			if ($this->check_for_child_products($this->pd)) $this->query.= " AND products.product_parent_id = ".$this->pd." AND products.product_visible = 1 ";
			else $this->query.= " AND products.id = ".$this->pd." ";
			}///////if (isset($_GET("details")) AND is_numeric(trim(htnlspecialchars($_GET("details"))))) {
			else $this->query.= " AND products.product_parent_id = 0";
			if(isset($this->product_vitrina) ) $this->query.= " AND products.product_vitrina = 1 ";
			
			
		//	$time2=microtime(true);
		//echo '2.3.7 - '. ($time2- $time1);
		//echo '<br>'			;
			
			////////////////////////////Фильтры по новинкам, распродаже и т.д.
			//$this->filters = Yii::app()->getRequest()->getParam('filters'); /////////////перенес в констракт, что бы можно было вручную экземпляру класса присваивать
			
			$debug = Yii::app()->getRequest()->getParam('debug');
			if(isset($debug) ) {
				echo "filter2<br>";
				print_r($this->filters);
				echo '<br>';
			}
			
			 if(isset($this->filters)) { 
			
				 //print_r($this->filters);
				//exit();
				 if(isset($this->filters['is_sellout']) OR isset($this->filters['is_new'])) {
					$this->query.= " AND (";
					if(isset($this->filters['is_sellout'])) $filter_arr[]=  " products.product_sellout = 1";
					if(isset($this->filters['is_new']))  $filter_arr[]= " products.product_new = 1";
					if(isset($filter_arr)) $this->query.=implode(' OR ', $filter_arr);
					$this->query.= ")";
				 }
				
				if(isset($this->filters['price_from']) AND trim($this->filters['price_from'])!='' AND is_numeric($this->filters['price_from'])==true)  $this->query.= "  AND products.product_price >=".$this->filters['price_from'];
				if(isset($this->filters['price_to']) AND trim($this->filters['price_to'])!='' AND is_numeric($this->filters['price_to'])==true)  $this->query.= "  AND products.product_price <=".$this->filters['price_to'];
				
				//if(isset($this->filters['products_ids_arr']) AND empty($this->filters['products_ids_arr'])==false) $this->query.= "  AND products.id IN (".implode(',',array_values($this->filters['products_ids_arr'])).")";
			}
			
		//	$time2=microtime(true);
		//echo '2.3.8 - '. ($time2- $time1); 
		//echo '<br>'			;
			
			
			if(isset($this->creteria)) $this->query.= $this->creteria;
			//echo $this->query;
			if(isset($this->orderby)) $this->query.=" ORDER BY ". $this->orderby;
			else $this->query.=" ORDER BY products.product_name ";
			//$this->query="SELECT  id, product_name FROM products LIMIT :offset,:limit";
			
			$page = Yii::app()->getRequest()->getParam('page');
			
			if (!isset($this->pd) OR is_numeric(trim(htmlspecialchars($this->pd)))==false )  $this->query.=" LIMIT :offset,:limit";
			//$this->check_for_main_parametr();
			//echo $this->query.'<br>';
		//	exit();
			$debug = Yii::app()->getRequest()->getParam('debug');
			if(isset($debug)) echo $this->query;
		
		//$time2=microtime(true);
		//echo '2.3.9 - '. ($time2- $time1);
		//echo '<br>'		;
		
	}//////////////////	function ExecuteObject(){///constuct

	public function run_query() {
		
			//$temp_table_name = $this->temp_table_name;
			//$ostatki_temp_table_name = $this->ostatki_temp_table_name;
		

				
		
			//$result=Yii::app()->db->createCommand($this->query)	;
			//echo $this->order_by;
			$result = $this->connection->createCommand($this->query);
			$result->bindValue(':offset', $this->offset);
			$result->bindValue(':limit', $this->limit);
			//$result->bindValue(':orderby', $this->orderby);///////////// ХЗ почему но не работает
			//$result->bindParam(':orderby', $this->orderby,PDO::PARAM_STR);
			//echo $this->query.'<br>';
			try{
				$ret = $result->query();
				} catch (Exception $e) {
							
						
						if($e->errorInfo[0]=='42S02') {//////////Не существует временная таблица, редиректим тудаже
							$url=Yii::app()->request->url;
							//print_r($url);
							Yii::app()->request->redirect($url,  true, 301);
							exit();
						}
						else echo 'Ошибка'.$role.' ',  $e->getMessage(), "\n";
						
						
					}/////
			
			//print_r($ret);
			//exit();
			
			$this->num_of_rows = $ret->getRowCount();
			
			
			/////////////////Прибераемся
			//$query = "DROP TABLE IF EXISTS  ".$this->temp_table_name."; DROP TABLE IF EXISTS ".$this->ostatki_temp_table_name."; DROP TABLE IF EXISTS  ".$this->char_val_temp_table_name.";";
			
			//$query = "TRUNCATE TABLE  ".$this->temp_table_name."'; TRUNCATE TABLE  ".$this->ostatki_temp_table_name."; TRUNCATE TABLE  ".$this->char_val_temp_table_name.";";
			
			$command=$this->connection->createCommand($query)	;
			//$dataReader=$command->query();
			
			return $ret;
			
			
	}
	
	public function get_stores_names () {
	return $this->stores_names;
	}
	
	public function get_stores_id () {
	return $this->stores_id;
	}
	
	function get_product_belong_to () {
				$query = "SELECT category_belong FROM products WHERE id = ".$this->pd;
				$this->command=$this->connection->createCommand($query)	;
				$this->dataReader=$this->command->query();
				$row=$this->dataReader->read();
				return $row['category_belong'];
	}////////////////function get_product_belong_to ($good_details, $cn) {
	
	public function get_characterictics() {///////Вывод в описание товара на витрине
			if (isset($this->pd)  )  {
				/*
				$query = "SELECT caract_id, caract_name, caract_mesuare, value, id_product, value_id  FROM characteristics JOIN characteristics_values ON 
				characteristics_values.id_caract=characteristics.caract_id		  
				WHERE 
				characteristics_values.id_product=".$this->pd." AND (characteristics.caract_category = (SELECT category_belong
				FROM  products 
				WHERE id = ".$this->pd.") OR characteristics.caract_category = 0 OR characteristics.is_common=1)   AND characteristics.caract_id NOT IN (160,161,162,163,164,165,166,167)
				ORDER BY characteristics.caract_category, caract_id" ;
				echo $query;
				*/
				$query = "SELECT caract_id, caract_name, caract_mesuare, value, id_product, value_id  FROM characteristics JOIN characteristics_values ON 
				characteristics_values.id_caract=characteristics.caract_id		  
				WHERE 
				characteristics_values.id_product=".$this->pd."  AND characteristics.caract_id NOT IN (160,161,162,163,164,165,166,167)
				ORDER BY characteristics.caract_name, caract_id" ;
				$this->command=$this->connection->createCommand($query)	;
				$dataReader=$this->command->query();
				$rows=$dataReader->readAll();
				//print_r($rows);
				 if (count($rows) > 0) return $rows;
				 else return NULL;
			}///////iif (isset($this->pd)  )  {
			else return NULL;
	}////////////////public function get_characterictics() {
	
	public function check_for_child_products($tovar_id) {
	$query = "SELECT products.id FROM  ".$this->temp_table_name." products LEFT JOIN measures ON products.measure = measures.id  JOIN (SELECT * FROM ".$this->temp_table_name." products ) parent_products ON parent_products.id = products.product_parent_id 
	   LEFT JOIN (SELECT id_product, GROUP_CONCAT( value) AS  attribute_value
	FROM ".$this->char_val_temp_table_name."  GROUP BY id_product) product_attribute ON product_attribute.id_product = products.id";
	$query.= " WHERE products.product_parent_id = $tovar_id";
	
	//echo $query;
	
		 $this->command=$this->connection->createCommand($query)	;
		$dataReader=$this->command->query();
		$rows=$dataReader->readAll();
	 if (count($rows) > 0) return 1;
	 else return NULL;
	}


	
		public function CountingQuery(){
			//$temp_table_name = $this->temp_table_name;
			//$ostatki_temp_table_name = $this->ostatki_temp_table_name;
			
			$query =  "SELECT COUNT(products.id) as id_count ";
			for($k=0;$k<count($this->stores_id);$k++) {
				$kk=$k+1;
				$query.=", store".$kk.".quantity AS prihod_store".$kk.",  0 AS rashod_store".$kk."  ";
				$ostatki_parts[] =  " IFNULL(store".$kk.".quantity,0) ";
			}
			//$this->query.=", (".implode('+',$ostatki_parts).") as ostatki_in_store " ;
			 if(isset($ostatki_parts)) $query.=", ".implode('+',$ostatki_parts)." AS trigers_ostatki " ;
			$query.=  " FROM  ". $this->temp_table_name." products";
			
			 for($k=0;$k<count($this->stores_id);$k++) {
				$kk=$k+1;
				$query.=" LEFT JOIN  ";
				$query.= "  ".$this->ostatki_temp_table_name." ";
				$query.= "   store".$kk;
				if ($k>0) $query.= " ON  (products.id = store$kk.tovar AND store$kk.store = ".$this->stores_id[$k]." )"; 
				if ($k==0)$query.= "  ON ( products.id = store1.tovar AND store$kk.store = ".$this->stores_id[$k]." )";
				
			}//////////  for($k=0;$k<count($stores_id);$k++) {
			/*
			
			*/
			////////////////////////////////////////////////////////////////////////////////////////////////////
					if (isset($this->cfid_arr)) { /////////////////Массив параметров
					/*
					print_r($this->cfid_arr);
					echo '<br>';
					echo '<pre>';
					print_r($this->char_values_list);
					echo '</pre><br>';
					*/
			foreach($this->cfid_arr as $name=>$values_id_list)//////////
				{		
						//$num = array_search($name, $this->sub_cat_ids); //////////порядковый номер в массиве id характеристик
				
						$list_caracteristic_values =$this->char_values_list[$name];
						//echo $name.' ';////////////////тут номерхарактеристики товара
						//foreach ($values_id_list as $k=>$v) {
						//																		echo $list_caracteristic_values[$k].' ';//////////////k - по этому номеру можно определить словосочетание полученное в запросе метода values_list
						// $query.= " JOIN (SELECT products.id FROM characteristics INNER JOIN (characteristics_values INNER JOIN products ON characteristics_values.id_product = products.id) ON characteristics.caract_id = characteristics_values.id_caract  						GROUP BY products.id, characteristics_values.value, products.category_belong, characteristics.caract_id HAVING ( ";
						$query.= "  JOIN ( SELECT id_product FROM  ".$this->char_val_temp_table_name." characteristics_values WHERE ( ";
						$sch=0;
						$vsego = count($values_id_list);
						foreach ($values_id_list as $k=>$v) {
								$sch++;
								//$query.= "((characteristics_values.value) ='".$list_caracteristic_values[$k]."') ";
								$query.= "(characteristics_values.value ='".$list_caracteristic_values[$k]."' AND   characteristics_values.id_caract = $name )";
								if ($sch<$vsego ) $query.= " OR ";
						}
						//$query.=" AND ((products.category_belong)=".$this->show_group.") AND ((characteristics.caract_id)=(".$name."))) 
						//$query.="  AND ((characteristics.caract_id)=(".$name."))) 
						$query.="   )) main_parametr_$name$k ON main_parametr_$name$k.id_product = products.id ";
						//if(isset($this->product_vitrina) ) $query.= " AND products.product_vitrina = 1 ";
						//																}
				}
			}/////////if (isset($this->cfid_arr)) { //////////////
			
			////////////////////////////////////////////////////////////////////////////////////////////////////////////
			$query.=  "WHERE products.product_visible = 1 ";
			
			if(isset(Yii::app()->params['dont_show_price_null']) AND Yii::app()->params['dont_show_price_null']==true)	$query.= " AND products.product_price>0 ";
		//
		//var_dump(Yii::app()->params['dont_show_ostatki_null']);
		if(isset(Yii::app()->params['dont_show_ostatki_null']) AND Yii::app()->params['dont_show_ostatki_null']==true) $query.= " AND (".implode('+',$ostatki_parts).")>0 ";//////////this->query.=", ".implode('+',$ostatki_parts)." AS trigers_ostatki 
		
			 //AND products.number_in_store>0 AND products.product_price>0 AND  products.product_parent_id = 0 ";
			// if(empty($this->filters)==true) $this->filters = Yii::app()->getRequest()->getParam('filters');

			

			// var_dump($this->filters);
			 if(isset($this->filters) ) { 
			
				 //print_r($this->filters);
				 //exit();
				 if(isset($this->filters['is_sellout']) OR isset($this->filters['is_new'])) {
					$query.= " AND (";
					if(isset($this->filters['is_sellout'])) $filter_arr[]= " products.product_sellout = 1";
					if(isset($this->filters['is_new']))  $filter_arr[]= " products.product_new = 1";
					if(isset($filter_arr))$query.=implode(' OR ', $filter_arr);
					$query.= ")";
				 }
				
				if(isset($this->filters['price_from']) AND trim($this->filters['price_from'])!='' AND is_numeric($this->filters['price_from'])==true) $query.= "  AND products.product_price >=".$this->filters['price_from'];
				if(isset($this->filters['price_to']) AND trim($this->filters['price_to'])!='' AND is_numeric($this->filters['price_to'])==true) $query.= "  AND products.product_price <=".$this->filters['price_to'];
				
			}
			
			 
	//if (isset($this->show_group)) $query.= "  AND products.category_belong=".intval($this->show_group); не используется, т.к. идёт выборка из временной таблицы 
			////////////////////////////Фильтры по новинкам, распродаже и т.д.
	//echo 		$query;
	
	return $query;
	}


	public function tableName()
	{
		//return 'User';
		return 'products';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('username, password, email', 'required'),
			array('username, password, email', 'length', 'max'=>128),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'Id',
			'product_name' => 'Номенклатура',

		);
	}
	
	public function CheckView(){ ////////////Проверяем режим вывода товаров
					 $session=new CHttpSession;
			    	 $session->open();
					  if (isset($this->out_mode) AND $this->out_mode<>'') {
					  $session['VIEW']=$this->out_mode;  //////////////// get session variable 'name1'
					  }////// if (Yii::app()->user->isGuest AND $this->no_register) {
					  elseif(isset($session['VIEW']))  {
					  $this->out_mode = $session['VIEW'];
					   }
					  else $this->out_mode = Yii::app()->GP->GP_list_view;
					  $session->close();
			}////////////public function CheckView(){ ////////////Прове
	
	public function char_list() {/////////////////////Список характеристик группы для фильтров

			$sub_cat_ids=NULL;
			$sub_cat_names=NULL;

	 		$this->connection = Yii::app()->db;
			if(isset(Yii::app()->params['group_characteristics_mode']) AND Yii::app()->params['group_characteristics_mode']=='multi'){
				$query = "SELECT caract_id, caract_name FROM characteristics JOIN  characteristics_categories ON  characteristics_categories.characteristics_id  = characteristics.caract_id ";
				$query.= " WHERE  ( characteristics_categories.categories_id = ".$this->show_group." AND  characteristics.is_main=1 )";
			$query.= " GROUP BY caract_id, caract_name  ORDER BY characteristics.caract_id";
			//echo $query;
			}
			else {
				$query = "SELECT caract_id, caract_name FROM characteristics ";
				//$query.= " WHERE  is_main=1 AND  caract_category =".$this->show_group;
				//$query.= " WHERE  (is_main=1 AND  caract_category =".$this->show_group.") OR (is_main=1 AND is_common=1)";
				$query.= " WHERE  ( caract_category =".$this->show_group." AND is_main=1) OR (is_common=1 AND caract_category =0)";
				$query.= " GROUP BY caract_id, caract_name  ORDER BY characteristics.caract_id";
			}
			////////GROUP_CONCAT(  //////////меняет порядок выборки
			//echo $query.'<br>';
			$this->command=$this->connection->createCommand($query);
			$this->dataReader=$this->command->query();
			//echo 'qqqqqqqqq<br>';
			while(($this->row=$this->dataReader->read())!==false) {
				//echo '<br>row = ';
				//print_r($this->row);
				//echo '<br>';
				$this->sub_cat_ids[]=$this->row['caract_id'];
				$this->sub_cat_names[]=$this->row['caract_name'];
				$sub_cat_ids[]=$this->row['caract_id'];
				$sub_cat_names[]=$this->row['caract_name'];
				//$this->char_values_list[]=explode("#", $this->row['val_list']);
				//print_r($this->list_of_distinct_values($this->row['caract_id']));
				//echo '<br>$this->row[caract_id] = '.$this->row['caract_id'].'<br>list_of_distinct_values = ';
				//print_r($this->list_of_distinct_values($this->row['caract_id']));
				//echo '<br>';
				$this->char_values_list[$this->row['caract_id']] = $this->list_of_distinct_values($this->row['caract_id']);
				//print_r($this->char_values_list);
	 		}
			
			if (count($sub_cat_ids)) $this->charact_list = array_combine($sub_cat_ids, $sub_cat_names);
			////print_r($this->charact_list);
	 }///////////////////////////////char_list
	 
	 private function list_of_distinct_values($caract_id) {
		 
		 	//echo $this->temp_table_name;
			
		 
	 		$caract_list = NULL;
	 		$query = "SELECT DISTINCT characteristics_values.value, characteristics_values.id_caract
			FROM characteristics_values
			JOIN ".$this->temp_table_name." products ON products.id = characteristics_values.id_product
			WHERE characteristics_values.value <>  '' 	AND characteristics_values.id_caract =$caract_id ORDER BY characteristics_values.value ";
			//echo $query.'<br>';
			$connection = Yii::app()->db;
			$command=$connection->createCommand($query);
			$dataReader=$command->query();
			while(($row=$dataReader->read())!==false) {
			$caract_list[]=$row['value'];
			}
			//print_r($caract_list);
			return $caract_list;
	 }////////////function list_of_distinct_values($caract_id) {
		
		function additional_pictures() {
		
		  $query = "SELECT picture_product.id, pictures.id, pictures.ext, pictures.type FROM 
  pictures JOIN picture_product ON picture_product.picture = pictures.id WHERE picture_product.product =".$this->pd." AND picture_product.is_main <>1";
  	$result = $this->connection->createCommand($query);
	//echo $query;
	return $result->query()->readAll();
	
		}///////////////function additional_pictures() {
		
		public function compability_list(){
		$query="SELECT products_compability.id, products_compability.compatible, products_compability.active, products.product_name,  products.product_article FROM products_compability JOIN products ON products.id=products_compability.compatible WHERE product = ".$this->pd;
		$result = $this->connection->createCommand($query);
		return $result->query()->readAll();
	
		}
		
}