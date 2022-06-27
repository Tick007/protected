<?php
class Category_categories_compability extends CActiveRecord {/////////////////////////
	

	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}


	public function tableName()
	{
		//return 'User';
		return 'category_categories_compability';
	}

	public function rules()
	{
		return array(
			// username and password are required
			// password needs to be authenticated
			
			//array('prefiks, name, groupe, type, ur_adress', 'required'),
			//array('product_name, category_belong, product_price', 'required'),
			//array('product_name, category_belong, price_type', 'required'),
			//array('product_name, category_belong, product_full_descr', 'required'),
			//array('product_name', 'length', 'min' => 5),
			//array('product_full_descr', 'length', 'min' => 100),
			//array('product_price, category_belong', 'numerical'),
			//array('category_belong', 'numerical'),
			//array('product_visible', 'boolean'),
			//array('product_full_descr', 'unique', 'className' => 'Products', 'on'=>'create'),
			//array('product_full_descr', 'exist'),
			array('minprice, maxprice, active, active_till_int, filters, tabname', 'exist'),
			array('minprice, maxprice, active, active_till_int, filters, tabname', 'safe'),
			//array('passcode2', 'compare', 'compareAttribute'=>'passcode'),
			//array('passcode', 'authenticate'), /////////////authenticate - проверяем при помощи соответствующей функции
			//array('newlogin', 'check_unique'), ////////////////////////////////Проверяем не занятли логин 
			//array('verifyCode', 'captcha', 'allowEmpty'=>!extension_loaded('gd')),
		);
	}


	public function relations()
	{
		return array(
		//'term_node'    => array(self::HAS_MANY, 'Term_node', 'nid'),
		//'term_node_belong'    => array(self::BELONGS_TO, 'Term_node', 'nid'),
		//'term_node_one'    => array(self::HAS_ONE, 'Term_node', 'nid'),
		//'files'=>array(self::MANY_MANY, 'Files', 'content_type_product(nid, field_ins_fid)'),
		//'uc_product_stock'=>array(self::MANY_MANY, 'Uc_product_stock', 'uc_products(nid, model)'),
		//'char_val'=>array(self::MANY_MANY, 'Characteristics_values', 'products(category_belong, id )'),/////////////////ytghfdbkmyj
		//'uc_product_stock' => array(self::HAS_ONE, 'Uc_product_stock', 'nid'),
		//	'node_revisions'    => array(self::HAS_MANY, 'Node_revisions', 'nid'),
		//	'node_revision_one'    => array(self::HAS_ONE, 'Node_revisions', 'nid'),
		//	'uc_products' => array(self::HAS_ONE, 'Uc_products', 'nid'),
			'compcategories'       => array(self::BELONGS_TO, 'Categories', 'compatible_category'),
			//'compprod' => array(self::BELONGS_TO, 'Products', 'product'),
		//'childs' => array(self::HAS_MANY, 'Catalog', 'parent'),
		);
	}
	
	
	
	public function trysave($needvalidate=false){
		
		//print_r($this->attributes);
		
		
		if($needvalidate==true) {
		}
		
		try {
					$this->save(false);
			} catch (Exception $e) {
					 echo 'Caught exception: ',  $e->getMessage(), "\n";
			}//////////////////////
	}
	
	public function filterize($filters) {/////////////сеарилизация данных фильтров с формы
		if(isset($filters) AND empty($filters)==false) {
			/*
			echo '<pre>';
			print_r($filters);
			echo '</pre>';
			*/
			
			
			/////////////Перебираем и выкидываем пустые
			foreach($filters as $key=>$val) {
				if(trim($val)) $newfilters[$key]=$val;
			}
			
			if(isset($newfilters)) return serialize($newfilters);
			else return NULL;
		}
		else return NULL;
	}//////////public function filterize($filters) {/////////////
	
	
}//////////////////// class
?>