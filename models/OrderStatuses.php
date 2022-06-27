<?
class OrderStatuses extends CActiveRecord{

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */

	 public function tableName()
	{
		return 'order_statuses';
	}
}////////class client  {

?>
