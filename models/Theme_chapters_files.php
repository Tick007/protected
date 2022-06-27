<?
class   Theme_chapters_files  extends CActiveRecord {/////////////////////////

public static function model($className=__CLASS__)
		{
			return parent::model($className);
		}


		public function tableName()
		{
			return 'theme_chapters_files';
		}
	
		public function relations()
		{
					return array(
				//	'authassignment' => array(self::HAS_MANY, 'Authassignment', 'itemname'),
				//	'child_items'=>array(self::MANY_MANY, 'Authitem', 'AuthItemChild(parent, child)'),
					//'currencies'=> array(self::BELONGS_TO, 'Currencies', 'currency'),
				//	'products' => array(self::HAS_MANY, 'Price_list_products_list', 'pricelist_id'),
					'theme_details'=> array(self::BELONGS_TO, 'Theme_details', 'theme_id'),
					'theme_files'=> array(self::BELONGS_TO, 'Theme_files', 'file_id'),
					'theme_chapters'=> array(self::BELONGS_TO, 'Theme_chapters', 'chapter_id'),
					
					);
		}


}//////////////////// class 
?>