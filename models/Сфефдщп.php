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
		
		
		public $show_group; ///////////Группа товара
		
		private $main_parametr1;//////////Это элемент списка на форме.
		private $main_parametr2;//////////Это элемент списка на форме.
		private $main_parametr3;//////////Это элемент списка на форме.
		public $main_parametr_value; /////////их массив
		
		
		private $r;
		private $pd;
		private $page; ////////////Для перехода по страницам
		
		public $out_mode;////////////////Режим вывода - список/мал картинки/большие картинки
		public $sort_order ;
	/**
	 * The followings are the available columns in table 'User':
	 * @var integer $id
	 * @var string $username
	 * @var string $password
	 * @var string $email
	 */

	/**
	 * Returns the static model of the specified AR class.
	 * @return CActiveRecord the static model class
	 */
	 

	 
	function __construct(){
	
			//echo $this->elements['main_parametr1'];
			
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
			if (isset($_GET['pd']) AND is_numeric(trim(htmlspecialchars($_GET['pd'])))==true) $this->pd=trim(htmlspecialchars($_GET['pd']));
			*/
			while (list($key, $val) = each($_GET)) {
			if (@isset($_GET[$key]) ) $this->$key = addslashes(substr(trim(htmlspecialchars($_GET[$key])),0,255));
			//else $$key=NULL;	
			}
			
			if(isset($_POST['ListForm'])) {
				foreach($_POST['ListForm'] as $name=>$value)//////////////Получаем все параметры с формы (POST)
				{
					if(trim($value)) $this->$name=$value;
					//echo $name.' = '.$value.'<br>';
				}
			}
			
			//if(isset($_POST['out_mode']))  $this->out_mode = $_POST['out_mode'];

			if(isset($this->pd) AND (!isset($this->show_group) OR !trim($this->show_group) )){
			 $this->show_group = $this->get_product_belong_to();
			Yii::app()->GP->GP_sg =  $this->show_group;
			}
				//echo $this->show_group;
			$this->main_parametr_value[0]=$this->main_parametr1;
			$this->main_parametr_value[1]=$this->main_parametr2;
			$this->main_parametr_value[2]=$this->main_parametr3;
				
			//$this->out_mode = Yii::app()->GP->GP_list_view;
			$this->CheckView();
			//$this->sort_order = 0;
			}//////////////////	public function __construct(){
			
		
	
		function ExecuteObject(){
		//echo "mp1 = ".$this->main_parametr1."<br>";
		
		$this->query="SELECT products.id, parent_categories.category_name  AS sgr,  ";
		if (isset($this->pd) AND is_numeric(trim(htmlspecialchars($this->pd)))==true ) $this->query.=" CONCAT_WS(',', products.product_name , product_attribute.attribute_value) AS product_name, ";
		else $this->query.=" products.product_name, ";
		  $this->query.=" categories.category_name  AS gr, price_list.price_with_nds, round(products.product_price*(1+products.nds_out),3) AS price_card, 	  price_list2.price_with_nds AS price_with_nds2, products.category_belong";
		for($k=0;$k<count($this->stores_id);$k++) {
		$kk=$k+1;
		$this->query.=", store".$kk.".quantity AS prihod_store".$kk.",  0 AS rashod_store".$kk."  ";
		}
		
		
		 $this->query.="FROM  products
		   JOIN  categories  ON categories.category_id = products.category_belong 
		   JOIN  categories AS parent_categories ON categories.parent = parent_categories.category_id 
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
			$this->query.= " GROUP BY products.product_name, products.id  ORDER BY products.product_name,  products.id ";
			$this->query.= "  ) store".$kk;
			if ($k>0) $this->query.= " ON products.id = store$kk.id "; 
			if ($k==0)$this->query.= "  ON products.id = store1.id "; 
			if (($k+1)<count($this->stores_id)) $query1.=" LEFT JOIN ";
			}//////////  for($k=0;$k<count($stores_id);$k++) {
		//////////////////////////////////////////////////////////////////////Для элементов формы
		if (isset($this->show_group) AND isset($this->main_parametr1)) $this->query.= " JOIN (
			SELECT products.id FROM characteristics INNER JOIN (characteristics_values INNER JOIN products ON characteristics_values.id_product = products.id) ON characteristics.caract_id = characteristics_values.id_caract 
			GROUP BY products.id, characteristics_values.value, products.category_belong, characteristics.caract_id HAVING 
			(((characteristics_values.value)='".$this->main_parametr1."') AND ((products.category_belong)=".$this->show_group.") AND ((characteristics.caract_id)=(
			SELECT caract_id
			FROM `characteristics` 
			WHERE caract_category = ".$this->show_group."
			AND is_main = 1))) 
			) main_parametr_car ON main_parametr_car.id = products.id ";
			
					if (isset($this->show_group) AND isset($this->main_parametr2)) $this->query.= " JOIN (
			SELECT products.id FROM characteristics INNER JOIN (characteristics_values INNER JOIN products ON characteristics_values.id_product = products.id) ON characteristics.caract_id = characteristics_values.id_caract 
			GROUP BY products.id, characteristics_values.value, products.category_belong, characteristics.caract_id HAVING 
			(((characteristics_values.value)='".$this->main_parametr2."') AND ((products.category_belong)=".$this->show_group.") AND ((characteristics.caract_id)=(
			SELECT caract_id
			FROM `characteristics` 
			WHERE caract_category = ".$this->show_group."
			AND is_main2 = 1))) 
			) main_parametr_car2 ON main_parametr_car2.id = products.id ";
			
					if (isset($this->show_group) AND isset($this->main_parametr3)) $this->query.= " JOIN (
			SELECT products.id FROM characteristics INNER JOIN (characteristics_values INNER JOIN products ON characteristics_values.id_product = products.id) ON characteristics.caract_id = characteristics_values.id_caract 
			GROUP BY products.id, characteristics_values.value, products.category_belong, characteristics.caract_id HAVING 
			(((characteristics_values.value)='".$this->main_parametr3."') AND ((products.category_belong)=".$this->show_group.") AND ((characteristics.caract_id)=(
			SELECT caract_id
			FROM `characteristics` 
			WHERE caract_category = ".$this->show_group."
			AND is_main3 = 1))) 
			) main_parametr_car3 ON main_parametr_car3.id = products.id ";

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
			}///////if (isset($this->pd) AND is_numeric(trim(htmlspecialchars($this->pd)))==true ) {////для подчиненных товаров
		
			$this->query.= "  WHERE products.product_visible = 1 ";
			if (isset($this->show_group)) $this->query.= "  AND categories.category_id=".intval($this->show_group);
		//echo $this->query;
		
			////////////////////// это для вывода либо подчиненных товаров, либо таблички с этим с остатками
			if (isset($this->pd) AND is_numeric(trim(htmlspecialchars($this->pd)))==true ) {////для подчиненных товаров
			if ($this->check_for_child_products($this->pd)) $this->query.= " AND products.product_parent_id = ".$this->pd." AND products.product_visible = 1 ";
			else $this->query.= " AND products.id = ".$this->pd." ";
			}///////if (isset($_GET("details")) AND is_numeric(trim(htnlspecialchars($_GET("details"))))) {
			else $this->query.= " AND products.product_parent_id = 0";
			
			//echo $this->query;
			//$this->query="SELECT  id, product_name FROM products LIMIT :offset,:limit";
			if (!isset($this->pd) OR is_numeric(trim(htmlspecialchars($this->pd)))==false ) $this->query.=" LIMIT :offset,:limit";
			$this->check_for_main_parametr();
	}//////////////////	function ExecuteObject(){///constuct
	
	public function main_parametr_data($main_id){/////////Возвращаем список значений для списка главного параметра
		//echo "$main_id<br>";
		//($this->group_caract_main_param);
			if (isset($this->group_caract_main_param[$main_id-1])) {
			$qqq="SELECT characteristics_values.value FROM characteristics_values GROUP BY characteristics_values.value, characteristics_values.id_caract HAVING (((characteristics_values.id_caract)= ".$this->group_caract_main_param[$main_id-1]." ))";
			//echo "$qqq<br>";
			$this->command=$this->connection->createCommand($qqq)	;
			$dataReader=$this->command->query();
			//$data = $this->dataReader->readAll();
			while(($row=$dataReader->read())!==false) $data[]=$row['value'];
			return $data;
			}/////////if (isset($this->group_caract_main_param)) {
			else return NULL;
			
	}//////////public function main_parametr_data(){/////////Возвращаем список значений для списка главного параметра
	
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
	
	public function get_characterictics() {
			if (isset($this->pd)  )  {
				$query = "SELECT caract_id, caract_name, caract_mesuare, value, id_product, value_id  FROM characteristics JOIN characteristics_values ON 
				characteristics_values.id_caract=characteristics.caract_id		  
				WHERE 
				characteristics_values.id_product=".$this->pd." AND (characteristics.caract_category = (SELECT category_belong
				FROM  products 
				WHERE id = ".$this->pd.") OR characteristics.caract_category = 0 )   AND characteristics.caract_id NOT IN (160,161,162,163,164,165,166,167)
				ORDER BY characteristics.caract_category, caract_id" ;
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
	
	public function check_for_main_parametr() {
			$query = NULL;
			for ($main_id=1;$main_id<=3;$main_id++) {
			if(isset($this->show_group) AND $show_group=intval(htmlspecialchars($this->show_group))) {
			$query = "SELECT characteristics.caract_id, characteristics.caract_name
			FROM characteristics
			WHERE (((characteristics.caract_category)=$show_group) AND ((characteristics.";
			if ($main_id==1) $query.="is_main";
			if ($main_id==2) $query.="is_main2";
			if ($main_id==3) $query.="is_main3";
			$query.=")=1))";
				
			$this->command=$this->connection->createCommand($query)	;
			$this->dataReader=$this->command->query();
			$row=$this->dataReader->read();
			//echo "row  = ".$row[0].;
			//print_r($row);
			$this->group_caract_main_param[$main_id-1] = $row['caract_id'];
			//$this->main_param_name1 = $row['caract_name'];
			//if($main_id==1) $this->main_param_name[0] = $row['caract_name'];
			//if($main_id==2) $this->main_param_name[1] = $row['caract_name'];
			//if($main_id==3) $this->main_param_name[2] = $row['caract_name'];
			$this->main_param_name[$main_id-1] = $row['caract_name'];
			}/////////////for ($main_id=1;$main_id<=3;$main_id++) {
			//return $this->group_caract_main_param;
			
			}
	}
	
	
	public function run_query() {
			$result=Yii::app()->db->createCommand($this->query)	;
			$result->bindValue(':offset', $this->offset);
			$result->bindValue(':limit', $this->limit);
			return $result->query();
	}
	
	public function CountingQuery(){
			$query =  "SELECT COUNT(products.id) as id_count FROM products ";
			////////////////////////////////////////////////////////////////////////////////////////////////////
			if ( isset($this->show_group) AND isset($this->main_parametr1)) $query.= " JOIN (
			SELECT products.id FROM characteristics INNER JOIN (characteristics_values INNER JOIN products ON characteristics_values.id_product = products.id) ON characteristics.caract_id = characteristics_values.id_caract 
			GROUP BY products.id, characteristics_values.value, products.category_belong, characteristics.caract_id HAVING 
			(((characteristics_values.value)='".$this->main_parametr1."') AND ((products.category_belong)=".$this->show_group.") AND ((characteristics.caract_id)=(
			SELECT caract_id
			FROM `characteristics` 
			WHERE caract_category = ".$this->show_group."
			AND is_main = 1))) 
			) main_parametr_car ON main_parametr_car.id = products.id ";
			
			if (isset($this->show_group) AND isset($this->main_parametr2)) $query.= " JOIN (
			SELECT products.id FROM characteristics INNER JOIN (characteristics_values INNER JOIN products ON characteristics_values.id_product = products.id) ON characteristics.caract_id = characteristics_values.id_caract 
			GROUP BY products.id, characteristics_values.value, products.category_belong, characteristics.caract_id HAVING 
			(((characteristics_values.value)='".$this->main_parametr2."') AND ((products.category_belong)=".$this->show_group.") AND ((characteristics.caract_id)=(
			SELECT caract_id
			FROM `characteristics` 
			WHERE caract_category = ".$this->show_group."
			AND is_main2 = 1))) 
			) main_parametr_car2 ON main_parametr_car2.id = products.id ";
			
					if (isset($this->show_group) AND isset($this->main_parametr3)) $query.= " JOIN (
			SELECT products.id FROM characteristics INNER JOIN (characteristics_values INNER JOIN products ON characteristics_values.id_product = products.id) ON characteristics.caract_id = characteristics_values.id_caract 
			GROUP BY products.id, characteristics_values.value, products.category_belong, characteristics.caract_id HAVING 
			(((characteristics_values.value)='".$this->main_parametr3."') AND ((products.category_belong)=".$this->show_group.") AND ((characteristics.caract_id)=(
			SELECT caract_id
			FROM `characteristics` 
			WHERE caract_category = ".$this->show_group."
			AND is_main3 = 1))) 
			) main_parametr_car3 ON main_parametr_car3.id = products.id ";
			
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
			'product_name' => 'Номенклатура',

		);
	}
	
	public function CheckView(){ ////////////Проверяем режим вывода товаров
					 $session=new CHttpSession;
			    	 $session->open();
					  if (isset($this->out_mode)) {
					  $session['VIEW']=$this->out_mode;  //////////////// get session variable 'name1'
					  }////// if (Yii::app()->user->isGuest AND $this->no_register) {
					  elseif(isset($session['VIEW']))  {
					  $this->out_mode = $session['VIEW'];
					   }
					  else $this->out_mode = Yii::app()->GP->GP_list_view;
					  $session->close();
			}////////////public function CheckView(){ ////////////Прове
	
}