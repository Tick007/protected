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
		private $pd;
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
		


	/**
	 * Returns the static model of the specified AR class.
	 * @return CActiveRecord the static model class
	 */
	 

	 
	function __construct(){
	
	
			
			//print_r($this->filters);
			//echo $this->elements['main_parametr1'];
			$sgp = Yii::app()->getRequest()->getParam('id', NULL);
			
			$alias = Yii::app()->getRequest()->getParam('alias');
			
			$sort = Yii::app()->getRequest()->getParam('sort', 2);
			
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
			//$this->show_group
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
			
			if(isset($ListForm)) {
				foreach($ListForm as $name=>$value)//////////////Получаем все параметры с формы (POST)
				{
					if(isset($value)) $this->$name=$value;
					//echo $name.' = '.$value.'<br>';
					//print_r($value);
				}
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
			if (isset($this->show_group)) $this->char_list();
			}//////////////////	public function __construct(){
			
		
	
		function ExecuteObject(){
		//echo "mp1 = ".$this->main_parametr1."<br>";
		$connection=Yii::app()->db;
		
		$query="SHOW COLUMNS FROM products ";//////////////////Создаем временную таблицу для группы
		$command=$connection->createCommand($query)	;
		$dataReader=$command->query();
		$prod_columns = $dataReader->readAll();
		//print_r($prod_columns);
		$temp_table_name = "products_".$this->show_group;
		$creation_query = "CREATE TABLE IF NOT EXISTS `".$temp_table_name."` (";
		foreach($prod_columns as $row) {
			$creation_query.= " ".$row['Field']." ".$row['Type']." ";
			if($row['Null']=='NO') $creation_query.=" NOT NULL ";
			elseif($row['Null']=='YES'){
				if($row['Default'] !=NULL) $creation_query.=" DEFAULT ".$row['Default'];
				else $creation_query.=" DEFAULT NULL ";
			}
			$creation_query.=" ".strtoupper($row['Extra']);
			$creation_query.=", ";
		}
		$creation_query.= "
		UNIQUE KEY `id` (`id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8  AUTO_INCREMENT=1 ;
		DELETE FROM $temp_table_name;
		";
		//echo $creation_query;
		//exit();
		/////////Первый запрос на вытаскивание товаров в категории
		$query = $creation_query."
		INSERT INTO $temp_table_name  (id,	product_name,	product_article,	product_short_descr	,product_full_descr,	category_belong	,product_visible,	new_product,	product_html_title,	product_vitrina,	product_sellout,	product_price,	product_price_old,	product_price_vip,	number_in_store,	product_visible_for_xml,	product_made_in,	product_dlina,	product_shirina,	product_visota,	product_ves,	product_size,	product_color,	product_sort,	product_manufacture,	product_warranty,	measure,	nds_out	,guid1,	product_parent_id,	product_vitrina_sort,	product_new,	product_new_sort,	product_sellout_sort,	sellout_price,	sellout_active_till_int,	product_html_keywords,	product_html_description,	contragent_id) SELECT id,	product_name,	product_article,	product_short_descr	,product_full_descr,	category_belong	,product_visible,	new_product,	product_html_title,	product_vitrina,	product_sellout,	product_price,	product_price_old,	product_price_vip,	number_in_store,	product_visible_for_xml,	product_made_in,	product_dlina,	product_shirina,	product_visota,	product_ves,	product_size,	product_color,	product_sort,	product_manufacture,	product_warranty,	measure,	nds_out	,guid1,	product_parent_id,	product_vitrina_sort,	product_new,	product_new_sort,	product_sellout_sort,	sellout_price,	sellout_active_till_int,	product_html_keywords,	product_html_description,	contragent_id FROM products WHERE category_belong= ".$this->show_group;
		
		$command=$connection->createCommand($query)	;
		$dataReader=$command->query();
		//if($dataReader->rowCount>0) $prods = $dataReader->readAll();
		//
		
		//if(isset($prods))for($i=0; $i<count($prods);$i++) $prod_list[]=$prods[$i]['id'];
		//print_r($prod_list);
		//echo $query;
		//exit();
		
		
		////////////////для выборки в соответствии с поиском
		$search = Yii::app()->getRequest()->getParam('search', NULL);	
	
		if ($search != NULL AND trim($search)<>'') {////////////
				$search_words=explode(' ', $search);
				$rows =Yii::app()->cache->get(trim($search.'products'));
		}//////////////////////////
		
		///////////////Вытаскиваем список общих характеристик 
		
		$criteria=new CDbCriteria;
		//$criteria->order = 'title';
		$criteria->condition = 'is_common = 1';
		$common_char_list = Characteristics::model()->findAll($criteria);//
		for($i=0; $i<count($common_char_list); $i++)  $common_char_arr[]=$common_char_list[$i]->caract_id;////
		//print_r($common_char_arr);
		//print_r(count($common_char_list));
		
		
		$this->query="SELECT DISTINCT products.id, products.product_article,  product_attribute.attribute_value AS product_attributes,  parent_categories.category_name  AS sgr, products.product_html_description AS product_html_description ,  products.product_sellout, products.product_new,  number_in_store, ";
		if (isset($this->pd) AND is_numeric(trim(htmlspecialchars($this->pd)))==true ) $this->query.=" CONCAT_WS(',', products.product_name , product_attribute.attribute_value) AS product_name, ";
		else $this->query.=" products.product_name, ";
	//	  $this->query.=" categories.category_name  AS gr, price_list.price_with_nds, round(products.product_price*(1+products.nds_out),3) AS price_card, 	  price_list2.price_with_nds AS price_with_nds2, products.category_belong";
	$this->query.=" categories.category_name  AS gr, price_list.price_with_nds, round(products.product_price,2) AS price_card, 	  price_list2.price_with_nds AS price_with_nds2, products.category_belong, products.product_html_description";
		for($k=0;$k<count($this->stores_id);$k++) {
		$kk=$k+1;
		$this->query.=", store".$kk.".quantity AS prihod_store".$kk.",  0 AS rashod_store".$kk."  ";
		$ostatki_parts[] =  " IFNULL(store".$kk.".quantity,0) ";
		}
		 //$this->query.=", (".implode('+',$ostatki_parts).") as ostatki_in_store " ;
		 $this->query.=", ".implode('+',$ostatki_parts)." as trigers_ostatki " ;
		  $this->query.=" , categories.alias AS catalias ";
		// $this->query.="	, picture_product.picture AS icon "; ////////Жутко тормозит с картинками
		
		  $this->query.="FROM  (SELECT products.id, products.product_article,  products.category_belong, products.product_price, products.nds_out, products.product_visible, products.product_name , products.product_parent_id, products.product_vitrina, products.product_html_description, products.product_sellout, products.product_new, number_in_store FROM $temp_table_name products  WHERE products.category_belong = ".$this->show_group."
			 UNION
			 SELECT products.id, products.product_article,  categories_products.group AS category_belong, products.product_price, products.nds_out, products.product_visible, products.product_name , products.product_parent_id, products.product_vitrina, products.product_html_description, products.product_sellout, products.product_new, number_in_store FROM $temp_table_name products  JOIN categories_products ON categories_products.product = products.id WHERE categories_products.group = ".$this->show_group."
			 ) products ";
		  
		  
		//  $this->query.=" LEFT JOIN ( SELECT id, product, picture FROM picture_product WHERE is_main=1) picture_product ON picture_product.product = products.id  "; ////////////////Жутко тормозит с картинками
		  $this->query.="JOIN categories  ON categories.category_id = products.category_belong ";
		  $this->query.=" LEFT JOIN  categories AS parent_categories ON categories.parent = parent_categories.category_id  ";
		  
		//	 LEFT JOIN  ";
		  for($k=0;$k<count($this->stores_id);$k++) {
		$kk=$k+1;
			$this->query.=" LEFT JOIN  ";
			/*		   
			$this->query.=" SELECT parent_categories.category_name AS sgr, products.product_name, ostatki.quantity, categories.category_name AS gr, products.id
			FROM products
			LEFT JOIN ( SELECT quantity, store, tovar FROM ostatki_trigers WHERE store = ".$this->stores_id[$k].=")  ostatki ON ostatki.tovar = products.id 
			JOIN categories  ON categories.category_id = products.category_belong 
			LEFT JOIN  categories AS  parent_categories ON categories.parent = parent_categories.category_id";
			//if (isset($this->show_group)) $this->query.= "  AND categories.category_id=".intval($this->show_group);
			$this->query.=" WHERE ostatki.store = ".$this->stores_id[$k];
			#if (@$sgroup ) $query1.= "  AND parent_categories.category_id=$sgroup ";
			#if (@$group ) $query1.= "  AND categories.category_id=$group ";
			$this->query.= " GROUP BY products.product_name, products.id   ";
			
			*/
			
			$this->query.= " ostatki_trigers ";
			
			$this->query.= "   store".$kk;
			if ($k>0) $this->query.= " ON  (products.id = store$kk.tovar AND store$kk.store = ".$this->stores_id[$k]." )"; 
			if ($k==0)$this->query.= "  ON ( products.id = store1.tovar AND store$kk.store = ".$this->stores_id[$k]." )";
			if (($k+1)<count($this->stores_id)) $query1.=" LEFT JOIN ";
			}//////////  for($k=0;$k<count($stores_id);$k++) {
		//////////////////////////////////////////////////////////////////////Для элементов формы
		//print_r($this->cfid_arr);
		
				
		if (isset($this->cfid_arr)) { /////////////////Массив параметров
		//echo '<br>$this->cfid_arr = ';
		//print_r($this->cfid_arr);
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

																								
						 $this->query.= " JOIN (SELECT products.id FROM characteristics INNER JOIN (characteristics_values INNER JOIN ";  
						 $this->query.= " (SELECT products.id, products.product_article, products.category_belong, products.product_price, products.nds_out, products.product_visible, products.product_name , products.product_parent_id, products.product_vitrina, products.product_html_description FROM $temp_table_name WHERE products.category_belong = ".$this->show_group." UNION SELECT products.id, products.product_article, categories_products.group AS category_belong, products.product_price, products.nds_out, products.product_visible, products.product_name , products.product_parent_id, products.product_vitrina, products.product_html_description FROM $temp_table_name products WHERE categories_products.group = ".$this->show_group." JOIN categories_products ON categories_products.product = products.id) ";
						 $this->query.= " products ON characteristics_values.id_product = products.id) ON characteristics.caract_id = characteristics_values.id_caract 
						GROUP BY products.id, characteristics_values.value, products.category_belong, characteristics.caract_id HAVING ( ";
						$sch=0;
						$vsego = count($values_id_list);
					//	print_r($list_caracteristic_values);
						foreach ($values_id_list as $k=>$v) {
								$sch++;
								$this->query.= "((characteristics_values.value) ='".$list_caracteristic_values[$k]."') ";
								if ($sch<$vsego ) $this->query.= " OR ";
						}
						$this->query.=" AND ((products.category_belong)=".$this->show_group.")";
						//$this->query.="  AND (characteristics.caract_id)=(".$name.")  ";
						if (!in_array($name, $common_char_arr)) $this->query.="  AND (characteristics.caract_id)=(".$name.")  "; ////т.е. если категория не общая то фильтруем по ней, иначе - нет, т.е. шерстим всме группы
						$this->query.=" ) ) main_parametr_$name$k ON main_parametr_$name$k.id = products.id ";
						//																}
				}
			}/////////if (isset($this->cfid_arr)) { //////////////
		////////////////////////////////////////////////////////////////////////////////////////////////
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
			
			 if (isset($this->pd) AND is_numeric(trim(htmlspecialchars($this->pd)))==true ) {////для подчиненных товаров
			 $this->query.= " LEFT JOIN (SELECT id_product, GROUP_CONCAT( value) AS  attribute_value
			FROM characteristics_values JOIN (SELECT * FROM products WHERE product_parent_id >0) child_products ON child_products.id = characteristics_values.id_product GROUP BY id_product ORDER BY characteristics_values.value_id) product_attribute ON product_attribute.id_product = products.id ";
			}///////if (isset($this->pd) AND is_numeric(trim(htmlspecialchars($this->pd)))==true ) {////для
			else {
			 $this->query.= " LEFT JOIN (SELECT id_product, GROUP_CONCAT(CONCAT_WS( ';;', value,id_caract ) SEPARATOR '#') AS  attribute_value
			FROM characteristics_values GROUP BY id_product ORDER BY characteristics_values.value_id) product_attribute ON product_attribute.id_product = products.id ";
			}
		
			$this->query.= "  WHERE products.product_visible = 1 AND categories.show_category =1 ";
		if(isset(Yii::app()->params['dont_show_price_null']) AND Yii::app()->params['dont_show_price_null']==true)	$this->query.= " AND products.product_price>0 ";
		if(isset(Yii::app()->params['dont_show_ostatki_null']) AND Yii::app()->params['dont_show_ostatki_null']==true) $this->query.= " AND products.number_in_store>0 ";
		
			if (isset($rows)) $this->query.= "  AND products.id IN (".implode(',', $rows).") ";
			//if (isset($this->show_group)) $this->query.= "  AND (categories.category_id=".intval($this->show_group)." OR categories_products.group = ".intval($this->show_group).") ";
			if (isset($this->show_group)) $this->query.= "  AND categories.category_id=".intval($this->show_group);
			
			
			
			//echo $this->query;
			////////////////////// это для вывода либо подчиненных товаров, либо таблички с этим с остатками
			if (isset($this->pd) AND is_numeric(trim(htmlspecialchars($this->pd)))==true ) {////для подчиненных товаров
			if ($this->check_for_child_products($this->pd)) $this->query.= " AND products.product_parent_id = ".$this->pd." AND products.product_visible = 1 ";
			else $this->query.= " AND products.id = ".$this->pd." ";
			}///////if (isset($_GET("details")) AND is_numeric(trim(htnlspecialchars($_GET("details"))))) {
			else $this->query.= " AND products.product_parent_id = 0";
			if(isset($this->product_vitrina) ) $this->query.= " AND products.product_vitrina = 1 ";
			
			////////////////////////////Фильтры по новинкам, распродаже и т.д.
			$this->filters = Yii::app()->getRequest()->getParam('filters');
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
				
				if(isset($this->filters['price_from']))  $this->query.= "  AND products.product_price >=".$this->filters['price_from'];
				if(isset($this->filters['price_to']))  $this->query.= "  AND products.product_price <=".$this->filters['price_to'];
			}
			
			if(isset($this->creteria)) $this->query.= $this->creteria;
			//echo $this->query;
			if(isset($this->orderby)) $this->query.=" ORDER BY ". $this->orderby;
			else $this->query.=" ORDER BY products.product_name ";
			//$this->query="SELECT  id, product_name FROM products LIMIT :offset,:limit";
			
			$page = Yii::app()->getRequest()->getParam('page');
			
			if (!isset($this->pd) OR is_numeric(trim(htmlspecialchars($this->pd)))==false )  $this->query.=" LIMIT :offset,:limit";
			//$this->check_for_main_parametr();
			//echo $this->query;
			//exit();
	}//////////////////	function ExecuteObject(){///constuct

	public function run_query() {
			//$result=Yii::app()->db->createCommand($this->query)	;
			//echo $this->order_by;
			$result = $this->connection->createCommand($this->query);
			$result->bindValue(':offset', $this->offset);
			$result->bindValue(':limit', $this->limit);
			//$result->bindValue(':orderby', $this->orderby);///////////// ХЗ почему но не работает
			//$result->bindParam(':orderby', $this->orderby,PDO::PARAM_STR);
			//echo $this->query.'<br>';
			$ret = $result->query();
			
			
			//print_r($ret);
			//exit();
			
			$this->num_of_rows = $ret->getRowCount();
			
			
			
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
	$query = "SELECT products.id FROM  products LEFT JOIN measures ON products.measure = measures.id  JOIN (SELECT * FROM products) parent_products ON parent_products.id = products.product_parent_id 
	   LEFT JOIN (SELECT id_product, GROUP_CONCAT( value) AS  attribute_value
	FROM `characteristics_values`  GROUP BY id_product) product_attribute ON product_attribute.id_product = products.id";
	$query.= " WHERE products.product_parent_id = $tovar_id";
		 $this->command=$this->connection->createCommand($query)	;
		$dataReader=$this->command->query();
		$rows=$dataReader->readAll();
	 if (count($rows) > 0) return 1;
	 else return NULL;
	}


	
		public function CountingQuery(){
			$query =  "SELECT COUNT(products.id) as id_count FROM  (SELECT products.id, products.product_article,  products.category_belong, products.product_price, products.nds_out, products.product_visible, products.product_name , products.product_parent_id, products.product_vitrina, products.product_html_description, products.product_sellout, products.product_new, number_in_store FROM products 
			 UNION
			 SELECT products.id, products.product_article,  categories_products.group AS category_belong, products.product_price, products.nds_out, products.product_visible, products.product_name , products.product_parent_id, products.product_vitrina, products.product_html_description, products.product_sellout, products.product_new, number_in_store FROM products  JOIN categories_products ON categories_products.product = products.id
			 ) products ";
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
						 $query.= " JOIN (SELECT products.id FROM characteristics INNER JOIN (characteristics_values INNER JOIN products ON characteristics_values.id_product = products.id) ON characteristics.caract_id = characteristics_values.id_caract 
						GROUP BY products.id, characteristics_values.value, products.category_belong, characteristics.caract_id HAVING ( ";
						$sch=0;
						$vsego = count($values_id_list);
						foreach ($values_id_list as $k=>$v) {
								$sch++;
								$query.= "((characteristics_values.value) ='".$list_caracteristic_values[$k]."') ";
								if ($sch<$vsego ) $query.= " OR ";
						}
						$query.=" AND ((products.category_belong)=".$this->show_group.") AND ((characteristics.caract_id)=(".$name."))) 
						) main_parametr_$name$k ON main_parametr_$name$k.id = products.id ";
						//if(isset($this->product_vitrina) ) $query.= " AND products.product_vitrina = 1 ";
						//																}
				}
			}/////////if (isset($this->cfid_arr)) { //////////////
			
			////////////////////////////////////////////////////////////////////////////////////////////////////////////
			$query.=  "WHERE products.product_visible = 1";
			
			if(isset(Yii::app()->params['dont_show_price_null']) AND Yii::app()->params['dont_show_price_null']==true)	$query.= " AND products.product_price>0 ";
		//
		if(isset(Yii::app()->params['dont_show_ostatki_null']) AND Yii::app()->params['dont_show_ostatki_null']==true) $query.= " AND products.number_in_store>0 ";
		
			 //AND products.number_in_store>0 AND products.product_price>0 AND  products.product_parent_id = 0 ";
			 $this->filters = Yii::app()->getRequest()->getParam('filters');

			// var_dump($this->filters);
			 if(isset($this->filters)) { 
			
				 //print_r($this->filters);
				 //exit();
				 if(isset($this->filters['is_sellout']) OR isset($this->filters['is_new'])) {
					$query.= " AND (";
					if(isset($this->filters['is_sellout'])) $filter_arr[]= " products.product_sellout = 1";
					if(isset($this->filters['is_new']))  $filter_arr[]= " products.product_new = 1";
					if(isset($filter_arr))$query.=implode(' OR ', $filter_arr);
					$query.= ")";
				 }
				
				if(isset($this->filters['price_from'])) $query.= "  AND products.product_price >=".$this->filters['price_from'];
				if(isset($this->filters['price_to'])) $query.= "  AND products.product_price <=".$this->filters['price_to'];
				
			}
			
			 
	if (isset($this->show_group)) $query.= "  AND products.category_belong=".intval($this->show_group);
			////////////////////////////Фильтры по новинкам, распродаже и т.д.
			
	
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
	 		$caract_list = NULL;
	 		$query = "SELECT DISTINCT characteristics_values.value, characteristics_values.id_caract
			FROM characteristics_values
			JOIN products ON products.id = characteristics_values.id_product
			WHERE characteristics_values.value <>  '' AND products.category_belong = ".$this->show_group." 
			AND characteristics_values.id_caract =$caract_id";
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