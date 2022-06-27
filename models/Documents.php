<?
class Documents  extends CActiveRecord {/////////////////////////

public static function model($className=__CLASS__)
		{
			return parent::model($className);
		}


		public function tableName()
		{
			return 'documents';
		}
	
		public function relations()
		{
					return array(
				//	'authassignment' => array(self::HAS_MANY, 'Authassignment', 'itemname'),
				//	'child_items'=>array(self::MANY_MANY, 'Authitem', 'AuthItemChild(parent, child)'),
					'store'=> array(self::BELONGS_TO, 'Stores', 'store_id'),
					'store_ca'=> array(self::BELONGS_TO, 'Stores', 'store_id_ca'),
					'kontragent' =>array(self::BELONGS_TO, 'Contr_agents', 'kontragent_id'),
					'doctype'=> array(self::BELONGS_TO, 'Document_types', 'doc_type'),
					'tablepart' => array(self::HAS_MANY, 'Document_table_part', 'doc_id'),
					);
		}


}//////////////////// class 
?>