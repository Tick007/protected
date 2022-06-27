<?
class Series extends CActiveRecord {/////////////////////////

public static function model($className=__CLASS__)
		{
			return parent::model($className);
		}


		public function tableName()
		{
			return 'series';
		}
	
		public function relations()
		{
					return array(
				//	'authassignment' => array(self::HAS_MANY, 'Authassignment', 'itemname'),
				//	'child_items'=>array(self::MANY_MANY, 'Authitem', 'AuthItemChild(parent, child)'),
					//'currencies'=> array(self::BELONGS_TO, 'Currencies', 'currency'),
				//	'products' => array(self::HAS_MANY, 'Price_list_products_list', 'pricelist_id'),
					);
		}


}//////////////////// class 
?>