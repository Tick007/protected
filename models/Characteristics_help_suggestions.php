<?
class Characteristics_help_suggestions extends CActiveRecord {/////////////////////////

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}


	public function tableName()
	{
		return 'characteristics_help_suggestions';
	}

	public function relations()
	{
		return array(
		//	'authassignment' => array(self::HAS_MANY, 'Authassignment', 'itemname'),
		//	'child_items'=>array(self::MANY_MANY, 'Authitem', 'AuthItemChild(parent, child)'),
		//'characteristic'=> array(self::BELONGS_TO, 'Characteristics', 'characteristics_id'),
		//	'products' => array(self::HAS_MANY, 'Price_list_products_list', 'pricelist_id'),
		'characteristic'=> array(self::BELONGS_TO, 'Characteristics', 'caract_id'),
		'characteristic_categories'=> array(self::BELONGS_TO, 'Characteristics_categories', 'characteristics_categories_id'),
		'user'=>array(self::BELONGS_TO, 'Clients', 'user_id'),

		);
	}


}//////////////////// class
?>