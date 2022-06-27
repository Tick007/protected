<?
class Account_debet extends CActiveRecord{
	
	var $sum;

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */

	public function tableName()
	{
		return 'account_debet';
	}

	public function relations()
	{
		return array(

		'order' => array(self::BELONGS_TO, 'Orders', 'schet'),
			//		'debet' => array(self::HAS_ONE, 'Account_debet', 'schet'), 
		);
	}

}////////class client  {

?>
