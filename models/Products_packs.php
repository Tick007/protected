<?
class Products_packs extends CActiveRecord {/////////////////////////

var $ext;
var $icon;


public static function model($className=__CLASS__)
		{
			return parent::model($className);
		}


		public function tableName()
		{
			//return 'User';
			return 'products_packs';
		}
	

		 public function relations()
		{
		return array(
			'packed'  => array(self::BELONGS_TO, 'Products', 'included'),
			///'backcompprod' => array(self::BELONGS_TO, 'Products', 'product'),
			//'childs' => array(self::HAS_MANY, 'Catalog', 'parent'),
			);
		}

}//////////////////// class 
?>