<?
class Client_groups extends CActiveRecord{

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */

	public function tableName()
	{
		return 'client_groups';
	}

	public function relations()
	{
		return array(
		//	'authassignment' => array(self::HAS_MANY, 'Authassignment', 'itemname'),
		//	'child_items'=>array(self::MANY_MANY, 'Authitem', 'AuthItemChild(parent, child)'),
		//'authitemchild'=> array(self::BELONGS_TO, 'Authitemchild', 'name'),
				//	'kontragent'=> array(self::BELONGS_TO, 'Contr_agents', 'urlico'),
			
			
		);
	}


}////////class client  {

?>
