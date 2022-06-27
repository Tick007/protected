<?
class Sotrudniki_settings extends CActiveRecord {/////////////////////////

public static function model($className=__CLASS__)
		{
			return parent::model($className);
		}


		public function tableName()
		{
			return 'sotrudniki_settings';
		}
	
		public function relations()
		{
					return array(
				//	'authassignment' => array(self::HAS_MANY, 'Authassignment', 'itemname'),
				//	'child_items'=>array(self::MANY_MANY, 'Authitem', 'AuthItemChild(parent, child)'),
					//'currencies'=> array(self::BELONGS_TO, 'Currencies', 'currency'),
					'params' => array(self::HAS_MANY, 'Sotrudniki_setting_values', 'setting_id'),
					);
		}


}//////////////////// class 
?>