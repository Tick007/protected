<?
class PaymentFaces extends CActiveRecord{

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */

	 public function tableName()
	{
		return 'payment_faces';
	}
}////////class client  {

?>
