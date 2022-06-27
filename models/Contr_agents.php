<?
class Contr_agents extends CActiveRecord {/////////////////////////

public static function model($className=__CLASS__)
		{
			return parent::model($className);
		}


		public function tableName()
		{
			return 'contr_agents';
		}
	
		public function relations()
		{
					return array(
				//	'authassignment' => array(self::HAS_MANY, 'Authassignment', 'itemname'),
				//	'child_items'=>array(self::MANY_MANY, 'Authitem', 'AuthItemChild(parent, child)'),
				//	'currencies'=> array(self::BELONGS_TO, 'Currencies', 'currency'),
					'stores' => array(self::HAS_MANY, 'Stores', 'kontragent_id'),
					);
		}


}//////////////////// class 
?>