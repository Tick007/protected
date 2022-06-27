<?php
class OrderContent extends CActiveRecord{

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	 
	 public function tableName()
	{
		return 'contents';
	}


		public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.OrderStatuses
		return array(
				
				'belongs_product'=> array(self::BELONGS_TO, 'Products', 'contents_product'),
				'belongs_order'=> array(self::BELONGS_TO, 'Orders', 'id_order'),
				
		);
	}

}////////class client  {

?>
