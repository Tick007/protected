<?
class Hosts extends CActiveRecord{

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */

	public function tableName()
	{
		return 'hosts';
	}

	public function relations()
		{
					return array(

					'order'=>array(self::HAS_MANY, 'Orders', 'host_id'),

					
					);
		}

		
	public static  function getHostId(){
		$host_id = 0;
		$name = trim(str_replace('www.', '', $_SERVER['HTTP_HOST']));
		
		$HOST = Hosts::model()->findByAttributes(array('name'=>$name));
		if($HOST!=null) {
			$host_id  = $HOST->id;
		}
		return $host_id;
	}

}////////class client  {

?>
