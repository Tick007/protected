<?
class Products_compability extends CActiveRecord {/////////////////////////

var $ext;
var $icon;


public static function model($className=__CLASS__)
		{
			return parent::model($className);
		}


		public function tableName()
		{
			//return 'User';
			return 'products_compability';
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
			'compprod'       => array(self::BELONGS_TO, 'Products', 'compatible'),
			'backcompprod' => array(self::BELONGS_TO, 'Products', 'product'),
			//'childs' => array(self::HAS_MANY, 'Catalog', 'parent'),
			);
		}

}//////////////////// class 
?>