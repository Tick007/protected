<?
class GP  extends CComponent {
				  
public $GP_self_contragent;
public $GP_proizvodstvo; 
public $GP_spisanie; 
public $GP_productor; 
public $GP_developer; 
public $GP_sklad_rezerv; 
public $GP_uchet_currency; 
public $GP_shop_currency; 
public $GP_theme; 
public $GP_enable_discounts; 
public $GP_list_view; 
public $GP_enable_send_mail; 
public $GP_main_mail; 
public $GP_company_tel; 
public $GP_ostatki_mode; 
public $GP_cat_gl;
public $GP_sg;////////////show_group
public $GP_company_name;
public $GP_theme_path;
public $GP_shop_currency_code;
public $GP_show_prices;

var $connection;
var $query;
var $command;
var $dataReader;
var $row;

	public function init()
	{
			$this->connection = Yii::app()->db;
			$this->query="SELECT  self_contragent, ca_proizvodstvo, ca_spisanie, productor, developer, sklad_rezerv, uchet_currency, currency, theme, 			enable_discounts, list_view, enable_send_mail, main_mail, company_tel, ostatki_mode, cat_gl, company_name, theme_details.path AS theme_path, currencies.currency_code AS GP_shop_currency_code, show_prices FROM general_settings LEFT JOIN theme_details ON general_settings.theme = theme_details.id   LEFT JOIN currencies ON currencies.currency_id = general_settings.currency  WHERE setting_id=1";
			$this->command=$this->connection->createCommand($this->query)	;
			$this->dataReader=$this->command->query();
			$this->row=$this->command->queryRow();
			//print_r($this->row);
			 $this->GP_self_contragent = $this->row['self_contragent']; 
			 $this->GP_proizvodstvo = $this->row['ca_proizvodstvo']; 
			 $this->GP_spisanie = $this->row['ca_spisanie']; 
			 $this->GP_productor = $this->row['productor'];  
			 $this->GP_developer = $this->row['developer'];  
			 $this->GP_sklad_rezerv = $this->row['sklad_rezerv'];  
			 $this->GP_uchet_currency = $this->row['uchet_currency'];  
			 $this->GP_shop_currency = $this->row['currency'];  
			 $this->GP_theme = $this->row['theme'];  
			 $this->GP_enable_discounts = $this->row['enable_discounts'];  
			 $this->GP_list_view = $this->row['list_view'];  
			 $this->GP_enable_send_mail = $this->row['enable_send_mail'];  
			 $this->GP_main_mail = $this->row['main_mail'];  
			 $this->GP_company_tel = $this->row['company_tel'];  
			 $this->GP_ostatki_mode = $this->row['ostatki_mode'];  
			 $this->GP_cat_gl = $this->row['cat_gl']; 
			 $this->GP_company_name = $this->row['company_name']; 
			 $this->GP_theme_path = $this->row['theme_path'];
			 $this->GP_shop_currency_code = $this->row['GP_shop_currency_code'];
			 $this->GP_show_prices = $this->row['show_prices'];
	}
	
	public function getproductname($product_id) {
			$model=Products::model()->findbyPk($product_id);
			if (isset($model->id))return $model->product_name;
	}
	
	public function  get_currency_rate() {
		/////////GP_shop_currency
		$this->connection = Yii::app()->db;
		$this->query = "SELECT currency_exchrate_list.rate
		FROM currency_exchrate_list
		JOIN currency_exchrate_header ON currency_exchrate_list.exchrate_header_id = currency_exchrate_header.id
		JOIN (
		
		SELECT MAX( currency_exchrate_header.creation_dt ) AS cr_dt, currency_exchrate_list.currency_id
		FROM currency_exchrate_list
		JOIN currency_exchrate_header ON currency_exchrate_header.id = currency_exchrate_list.exchrate_header_id
		WHERE currency_exchrate_list.currency_id =". $this->GP_shop_currency;
		$this->query.=" AND currency_exchrate_header.status =1
		GROUP BY currency_exchrate_list.currency_id
		)dates ON dates.cr_dt = currency_exchrate_header.creation_dt
		WHERE currency_exchrate_list.currency_id = ". $this->GP_shop_currency;
		$this->command=$this->connection->createCommand($this->query)	;
		$this->dataReader=$this->command->query();
		$row=$this->dataReader->read();
		return $row['rate'];
	}//////////////////public function  get_currency_rate() {
	
	public function get_actual_retail($price_type , $delivery_method) {///////////Розничная цена
			$query= "SELECT price_list_products_list.price_with_nds, price_list_header.creation_dt
			FROM price_list_products_list
			JOIN price_list_header ON price_list_header.id = price_list_products_list.pricelist_id
			WHERE price_list_header.status =1
			AND price_list_header.currency = ".Yii::app()->GP->GP_shop_currency."
			AND price_list_products_list.product_id =  $delivery_method
			AND price_list_header.id AND price_list_header.price_type =$price_type 
			ORDER BY price_list_header.creation_dt DESC 
			LIMIT 1 ";
			$this->command=$this->connection->createCommand($query)	;
		$this->dataReader=$this->command->query();
		$row=$this->dataReader->read();
		if (isset($row['price_with_nds']) AND $row['price_with_nds']>0) return $row['price_with_nds'];
		else {
			$prod = Products::model()->findByPk($delivery_method);
			if(isset($prod)) {
				 if($prod->product_sellout==1 AND trim($prod->sellout_price)!='') return $prod->sellout_price;
				 else return  $prod->product_price;
			}
			else return 0;
		}
		
	}//////////////////public function get_actual_retail($price_type , $delivery_method) 

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
	
	public function	get_description($product_id) {////////////НТМЛ описание товарв
		$query = "SELECT product_full_descr FROM products WHERE  id = $product_id";
		 $this->command=$this->connection->createCommand($query)	;
		$dataReader=$this->command->query();
		$row=$dataReader->read();
	 	return $row['product_full_descr'];
	}////////////public function	get_description($product_id) {

	function get_sql_date($date_value, $time){//////////    SQL
	$date_value=str_replace ("/", ".",$date_value);
			$date_value=str_replace (",", ".",$date_value);
			$first_dot=strpos($date_value, ".");
			$last_dot=strrpos($date_value, ".");
			//echo"first_dot = $first_dot<br>";
			$day=substr($date_value,($first_dot+1), ($last_dot-$first_dot-1));
			$month=substr($date_value,0,$first_dot);
			//$month=substr($date_value,3,2);
			$year=substr($date_value,($last_dot+1), 4);
			 if(strlen($day)==1) $day="0".$day;
			 if(strlen($month)==1) $ms="0".$month;
			if(strlen($year)==2) $year="20".$year;
			
			if (@checkdate ( $month, $day, $year))return "$year-$month-$day $time";
			else return NULL;
	}//////////function get_sql_date($date_value){

}//////class GP extends CDbConnection {
?>