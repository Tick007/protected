<?
class Price_list_products_list extends CActiveRecord {/////////////////////////

public static function model($className=__CLASS__)
		{
			return parent::model($className);
		}


		public function tableName()
		{
			return 'price_list_products_list';
		}
	
		public function relations()
		{
					return array(
				//	'authassignment' => array(self::HAS_MANY, 'Authassignment', 'itemname'),
				//	'child_items'=>array(self::MANY_MANY, 'Authitem', 'AuthItemChild(parent, child)'),
					//'authitemchild'=> array(self::BELONGS_TO, 'Authitemchild', 'name'),
					'product'=> array(self::BELONGS_TO, 'Products', 'product_id'),
					
					);
		}


}//////////////////// class 
?>