<?php
class Clients extends CActiveRecord{

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */

	public function tableName()
	{
		return 'clients';
	}

	public function relations()
		{
					return array(
				//	'authassignment' => array(self::HAS_MANY, 'Authassignment', 'itemname'),
				//	'child_items'=>array(self::MANY_MANY, 'Authitem', 'AuthItemChild(parent, child)'),
					//'authitemchild'=> array(self::BELONGS_TO, 'Authitemchild', 'name'),
					'kontragent'=> array(self::BELONGS_TO, 'Contr_agents', 'urlico'),
					'authassignment' => array(self::HAS_ONE, 'Authassignment', 'userid'),
					'inbox' => array(self::HAS_MANY, 'Message', 'from_user'),
					'authentications'=>array(self::HAS_MANY, 'Authentications', 'user_id'),
					'city'=> array(self::BELONGS_TO, 'World_adres_cities', 'client_city'),
					'card' => array(self::HAS_ONE, 'ClientCards', 'client_id'),
					'orders' => array(self::HAS_MANY, 'Orders', 'id_client'),
					);
		}
		
		


}////////class client  {

?>
