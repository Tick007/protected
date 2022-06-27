<?
class Characteristics_categories extends CActiveRecord {/////////////////////////

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}


	public function tableName()
	{
		return 'characteristics_categories';
	}

	public function relations()
	{
		return array(
		//	'authassignment' => array(self::HAS_MANY, 'Authassignment', 'itemname'),
		//	'child_items'=>array(self::MANY_MANY, 'Authitem', 'AuthItemChild(parent, child)'),
		'characteristic'=> array(self::BELONGS_TO, 'Characteristics', 'characteristics_id'),
		//	'products' => array(self::HAS_MANY, 'Price_list_products_list', 'pricelist_id'),
		'suggestions' => array(self::HAS_MANY, 'Characteristics_help_suggestions', 'characteristics_categories_id'),
		'category'=> array(self::BELONGS_TO, 'Categories', 'categories_id'),

		);
	}


	public static function get_characteristics_categories_by_category($list){ 
			$criteria=new CDbCriteria;
			$criteria->condition = " t.categories_id IN (".implode(',',$list).") ";
			$grupp_characteristics =Characteristics_categories::model()->with('characteristic')->findAll($criteria);//
			//////////////перебираем
			if(isset($grupp_characteristics)) for($i=0; $i<count($grupp_characteristics); $i++) {
				$characteristics_categories[$grupp_characteristics[$i]->categories_id][] = $grupp_characteristics[$i];
			}
			if (isset($characteristics_categories)) return $characteristics_categories;
	}

}//////////////////// class
?>