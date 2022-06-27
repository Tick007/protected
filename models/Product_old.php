<?php

class Product extends CFormModel {
		var $connection;
		var $query;
	
		var $query1; ////////////��� ������� �������
		var $stores_id;////////������ ��� �������� id �������
		var $stores_names;////////������ ��� �������� ���� �������
		
		var $command;
		var $dataReader;
		var $row;
		//public $item_list;
		public $count_alias = "id_count";
		public $offset;
		public $limit;
		var $result;
		public $group_caract_main_param; ////////////������ �� �������������� �������� ��������� ��� ������
		public $main_param_name;//////������ ���� �������� ���������
		
		public $num_of_rows;///////////����� ����� � ����������� �������
		
		
		public $show_group; ///////////������ ������

		private $r;
		private $pd;
		private $page; ////////////��� �������� �� ���������
		
		public $out_mode;////////////////����� ������ - ������/��� ��������/������� ��������
		public $sort_order ;
		
		public $cfid;
		public $cfv;
		public $cfid_arr;
		
		public $sub_cat_ids; ///////////////������ ��������������� ������������� ��� ������
		public $sub_cat_names;
		public $char_values_list;
		public $charact_list;
		
		public $product_vitrina;
		public $orderby; 
		public $creteria;
		//public $id;
		


	/**
	 * Returns the static model of the specified AR class.
	 * @return CActiveRecord the static model class
	 */
	 

	 
	function __construct(){
	
			//echo $this->elements['main_parametr1'];
			$sgp = Yii::app()->getRequest()->getParam('id');
			if (is_numeric(trim(htmlspecialchars($sgp)))==true) $this->show_group = $sgp;
			//$this->show_group
			//echo "������ 1.0.13.<br>";
			$this->connection = Yii::app()->db;
			/////////////////////////////�������������� ������
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
			if(isset($_POST['ListForm'])) {
				foreach($_POST['ListForm'] as $name=>$value)//////////////�������� ��� ��������� � ����� (POST)
				{
					if(isset($value)) $this->$name=$value;
					//echo $name.' = '.$value.'<br>';
					//print_r($value);
				}
			}
			
			//////////////////////////////////////////////////////////////////////////��� �������������� ��������� ����� ����
			
			if (isset($_GET['cid']) AND isset($_GET['pid']) ) {
					$cid = $_GET['cid'];
					$pid = $_GET['pid'];
					$this->cfid_arr[$cid]=array($pid=>1);
			}///////////////////
			
			//////////////////������ ������� ������� �� ����� ��� ������
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
		
		///////////////����������� ������ ����� �������������
		
		$criteria=new CDbCriteria;
		//$criteria->order = 'title';
		$criteria->condition = 'is_common = 1';
		$common_char_list = Characteristics::model()->findAll($criteria);//
		for($i=0; $i<count($common_char_list); $i++)  $common_char_arr[]=$common_char_list[$i]->caract_id;////
		//print_r($common_char_arr);
		//print_r(count($common_char_list));
		
		
		$this->query="SELECT DISTINCT products.id, products.product_article, parent_categories.category_name  AS sgr,  ";
		if (isset($this->pd) AND is_numeric(trim(htmlspecialchars($this->pd)))==true ) $this->query.=" CONCAT_WS(',', products.product_name , product_attribute.attribute_value) AS product_name, ";
		else $this->query.=" products.product_name, ";
	//	  $this->query.=" categories.category_name  AS gr, price_list.price_with_nds, round(products.product_price*(1+products.nds_out),3) AS price_card, 	  price_list2.price_with_nds AS price_with_nds2, products.category_belong";
	$this->query.=" categories.category_name  AS gr, price_list.price_with_nds, round(products.product_price,2) AS price_card, 	  price_list2.price_with_nds AS price_with_nds2, products.category_belong";
		for($k=0;$k<count($this->stores_id);$k++) {
		$kk=$k+1;
		$this->query.=", store".$kk.".quantity AS prihod_store".$kk.",  0 AS rashod_store".$kk."  ";
		}
		
		  $this->query.="FROM  (SELECT products.id, products.product_article,  products.category_belong, products.product_price, products.nds_out, products.product_visible, products.product_name , products.product_parent_id, products.product_vitrina FROM products 
			 UNION
			 SELECT products.id, products.product_article,  categories_products.group AS category_belong, products.product_price, products.nds_out, products.product_visible, products.product_name , products.product_parent_id, products.product_vitrina FROM products  JOIN categories_products ON categories_products.product = products.id
			 ) products ";
		  
		  
		  
		  $this->query.="JOIN categories  ON categories.category_id = products.category_belong ";
		  $this->query.=" LEFT JOIN  categories AS parent_categories ON categories.parent = parent_categories.category_id 
		   
			 LEFT JOIN  ";
		  for($k=0;$k<count($this->stores_id);$k++) {
		$kk=$k+1;
					   $this->query.=" (SELECT parent_categories.category_name AS sgr, products.product_name, ostatki.quantity, categories.category_name AS gr, products.id
			FROM products
			LEFT JOIN (
			SELECT quantity, store, tovar
			FROM ostatki_trigers
			WHERE store = ".$this->stores_id[$k];
			$this->query.=") ostatki ON ostatki.tovar = products.id
			JOIN categories  ON categories.category_id = products.category_belong 
			JOIN  categories AS  parent_categories ON categories.parent = parent_categories.category_id";
			//if (isset($this->show_group)) $this->query.= "  AND categories.category_id=".intval($this->show_group);
			$this->query.=" WHERE ostatki.store = ".$this->stores_id[$k];
			#if (@$sgroup ) $query1.= "  AND parent_categories.category_id=$sgroup ";
			#if (@$group ) $query1.= "  AND categories.category_id=$group ";
			$this->query.= " GROUP BY products.product_name, products.id   ";
			$this->query.= "  ) store".$kk;
			if ($k>0) $this->query.= " ON products.id = store$kk.id "; 
			if ($k==0)$this->query.= "  ON products.id = store1.id "; 
			if (($k+1)<count($this->stores_id)) $query1.=" LEFT JOIN ";
			}//////////  for($k=0;$k<count($stores_id);$k++) {
		//////////////////////////////////////////////////////////////////////��� ��������� �����
		//print_r($this->cfid_arr);
		
				
		if (isset($this->cfid_arr)) { /////////////////������ ����������
			foreach($this->cfid_arr as $name=>$values_id_list)//////////
				{		
						//$name = id ��������������
						/*
						public $sub_cat_ids; ///////////////������ ��������������� ������������� ��� ������
		public $sub_cat_names;
		public $char_values_list;
						*/
						//if(!isset($this->sub_cat_ids)  )  $this->char_list();
						$num = array_search($name, $this->sub_cat_ids); //////////���������� ����� � ������� id �������������
				
						$list_caracteristic_values = $this->char_values_list[$num];

																								
						 $this->query.= " JOIN (SELECT products.id FROM characteristics INNER JOIN (characteristics_values INNER JOIN ";  
						 $this->query.= " (SELECT products.id, products.product_article, products.category_belong, products.product_price, products.nds_out, products.product_visible, products.product_name , products.product_parent_id, products.product_vitrina FROM products UNION SELECT products.id, products.product_article, categories_products.group AS category_belong, products.product_price, products.nds_out, products.product_visible, products.product_name , products.product_parent_id, products.product_vitrina FROM products JOIN categories_products ON categories_products.product = products.id) ";
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
						if (!in_array($name, $common_char_arr)) $this->query.="  AND (characteristics.caract_id)=(".$name.")  "; ////�.�. ���� ��������� �� ����� �� ��������� �� ���, ����� - ���, �.�. ������� ���� ������
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
			
			if (isset($this->pd) AND is_numeric(trim(htmlspecialchars($this->pd)))==true ) {////��� ����������� �������
			 $this->query.= " LEFT JOIN (SELECT id_product, GROUP_CONCAT( value) AS  attribute_value
			FROM characteristics_values JOIN (SELECT * FROM products WHERE product_parent_id >0) child_products ON child_products.id = characteristics_values.id_product GROUP BY id_product ORDER BY characteristics_values.value_id) product_attribute ON product_attribute.id_product = products.id ";
			}///////if (isset($this->pd) AND is_numeric(trim(htmlspecialchars($this->pd)))==true ) {////��� ����������� �������
		
			$this->query.= "  WHERE products.product_visible = 1 AND categories.show_category =1 ";
			//if (isset($this->show_group)) $this->query.= "  AND (categories.category_id=".intval($this->show_group)." OR categories_products.group = ".intval($this->show_group).") ";
			if (isset($this->show_group)) $this->query.= "  AND categories.category_id=".intval($this->show_group);
			
			//echo $this->query;
			////////////////////// ��� ��� ������ ���� ����������� �������, ���� �������� � ���� � ���������
			if (isset($this->pd) AND is_numeric(trim(htmlspecialchars($this->pd)))==true ) {////��� ����������� �������
			if ($this->check_for_child_products($this->pd)) $this->query.= " AND products.product_parent_id = ".$this->pd." AND products.product_visible = 1 ";
			else $this->query.= " AND products.id = ".$this->pd." ";
			}///////if (isset($_GET("details")) AND is_numeric(trim(htnlspecialchars($_GET("details"))))) {
			else $this->query.= " AND products.product_parent_id = 0";
			if(isset($this->product_vitrina) ) $this->query.= " AND products.product_vitrina = 1 ";
			if(isset($this->creteria)) $this->query.= $this->creteria;
			//echo $this->query;
			if(isset($this->orderby)) $this->query.=" ORDER BY ". $this->orderby;
			else $this->query.=" ORDER BY products.product_name ";
			//$this->query="SELECT  id, product_name FROM products LIMIT :offset,:limit";
			if (!isset($this->pd) OR is_numeric(trim(htmlspecialchars($this->pd)))==false ) $this->query.=" LIMIT :offset,:limit";
			//$this->check_for_main_parametr();
	}//////////////////	function ExecuteObject(){///constuct

	public function run_query() {
			//$result=Yii::app()->db->createCommand($this->query)	;
			//echo $this->order_by;
			$result = $this->connection->createCommand($this->query);
			$result->bindValue(':offset', $this->offset);
			$result->bindValue(':limit', $this->limit);
			//$result->bindValue(':orderby', $this->orderby);///////////// �� ������ �� �� ��������
			//$result->bindParam(':orderby', $this->orderby,PDO::PARAM_STR);
			//echo $this->query.'<br>';
			$ret = $result->query();
			
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
	
	public function get_characterictics() {///////����� � �������� ������ �� �������
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
			$query =  "SELECT COUNT(products.id) as id_count FROM products ";
			////////////////////////////////////////////////////////////////////////////////////////////////////
					if (isset($this->cfid_arr)) { /////////////////������ ����������
			foreach($this->cfid_arr as $name=>$values_id_list)//////////
				{		
						$num = array_search($name, $this->sub_cat_ids); //////////���������� ����� � ������� id �������������
				
						$list_caracteristic_values =$this->char_values_list[$num];
						//echo $name.' ';////////////////��� ������������������� ������
						//foreach ($values_id_list as $k=>$v) {
						//																		echo $list_caracteristic_values[$k].' ';//////////////k - �� ����� ������ ����� ���������� �������������� ���������� � ������� ������ values_list
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
			$query.=  "WHERE products.product_visible = 1 AND
			 products.product_parent_id = 0 ";
	if (isset($this->show_group)) $query.= "  AND products.category_belong=".intval($this->show_group);
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
			'product_name' => '������������',

		);
	}
	
	public function CheckView(){ ////////////��������� ����� ������ �������
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
			}////////////public function CheckView(){ ////////////�����
	
	public function char_list() {/////////////////////������ ������������� ������ ��� ��������

			$sub_cat_ids=NULL;
			$sub_cat_names=NULL;

	 		$this->connection = Yii::app()->db;
			$query = "SELECT caract_id, caract_name FROM characteristics ";
			//$query.= " WHERE  is_main=1 AND  caract_category =".$this->show_group;
			//$query.= " WHERE  (is_main=1 AND  caract_category =".$this->show_group.") OR (is_main=1 AND is_common=1)";
			$query.= " WHERE  ( caract_category =".$this->show_group." AND is_main=1) OR (is_common=1 AND caract_category =0)";
			$query.= " GROUP BY caract_id, caract_name  ORDER BY characteristics.caract_id";
			////////GROUP_CONCAT(  //////////������ ������� �������
			//echo $query.'<br>';
			$this->command=$this->connection->createCommand($query);
			$this->dataReader=$this->command->query();
			while(($this->row=$this->dataReader->read())!==false) {
			$this->sub_cat_ids[]=$this->row['caract_id'];
			$this->sub_cat_names[]=$this->row['caract_name'];
			$sub_cat_ids[]=$this->row['caract_id'];
			$sub_cat_names[]=$this->row['caract_name'];
			//$this->char_values_list[]=explode("#", $this->row['val_list']);
			$this->char_values_list[] = $this->list_of_distinct_values($this->row['caract_id']);
	 		}
			if (count($sub_cat_ids)) $this->charact_list = array_combine($sub_cat_ids, $sub_cat_names);
			//print_r($this->charact_list);
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
			return $caract_list;
	 }////////////function list_of_distinct_values($caract_id) {
		
		function additional_pictures() {
		
		  $query = "SELECT picture_product.id, pictures.id, pictures.ext, pictures.type FROM 
  pictures JOIN picture_product ON picture_product.picture = pictures.id WHERE picture_product.product =".$this->pd;
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