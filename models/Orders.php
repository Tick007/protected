<?php
class Orders extends CActiveRecord{
		
		public $table_part_array;
		
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}


	 public function tableName()
	{
		return 'orders';
	}

		public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.OrderStatuses
		return array(
				'client'=> array(self::BELONGS_TO, 'Clients', 'id_client'),
				'PaymentMethod'=> array(self::BELONGS_TO, 'PaymentMethod', 'payment'),
				'PaymentFace'=> array(self::BELONGS_TO, 'PaymentFaces', 'payment_face'),
				'OrderStatus'=> array(self::BELONGS_TO, 'OrderStatuses', 'order_status'),
				'OrderContent'=>array(self::HAS_MANY, 'OrderContent', 'id_order'),
				'kontragent'=> array(self::BELONGS_TO, 'Contr_agents', 'contragent_id'),
				'documents'=>array(self::HAS_MANY, 'Documents', 'order_id'),
				'payments'=>array(self::HAS_MANY, 'Account_debet', 'schet'),
				'product_region'=> array(self::BELONGS_TO, 'Products_regions', 'delivery'),
				'host'=> array(self::BELONGS_TO, 'Hosts', 'host_id'),
		    
		    
    		    //'clients'=> array(self::BELONGS_TO, 'Clients', 'client_id'),
    		    //'paymentmethod'=> array(self::BELONGS_TO, 'PaymentMethod', 'payment_m_id'),
    		    'debet'=>array(self::HAS_ONE, 'AccountDebet', 'order_id')
		    
		);
	}
	
	
				public static function order_contents_short($order_id){
				$query = "SELECT GROUP_CONCAT( contents_name
				SEPARATOR  '; ' ) AS qqq, id_order
				FROM contents
				WHERE id_order =".$order_id."
				GROUP BY id_order";
				$connection = Yii::app()->db;
				$command=$connection->createCommand($query)	;
				$dataReader=$command->query();
				$row=$dataReader->read();
	 			return $row['qqq'];
				}////public static order_contents_short($order_id){
				
				
				
				public static function order_contents_short_pricevol($order_id){
				    $query = "SELECT GROUP_CONCAT( contents_name, ', ',  price_volume
				SEPARATOR  '; ' ) AS qqq, id_order
				FROM contents
				WHERE id_order =".$order_id."
				GROUP BY id_order";
				    $connection = Yii::app()->db;
				    $command=$connection->createCommand($query)	;
				    $dataReader=$command->query();
				    $row=$dataReader->read();
				    return $row['qqq'];
				}////public static order_contents_short($order_id){
				
				public function get_currency_code() {
				$Curr =  Currencies::model()->find('currency_id=:ID ', array(':ID'=>$this->currency ));
				return $Curr->currency_code;
				//return $this->id;
				}
				
				public function get_payment_face () {
				$PF =  PaymentFaces::model()->find('face_id=:ID ', array(':ID'=>$this->payment_face ));
				if(isset($PF)) return $PF->face;
				else return null;
				}
				
				public function get_payment_method () {
				$PM =  PaymentMethod::model()->find('payment_method_id=:ID ', array(':ID'=>$this->payment ));
				if($PM!=null) return $PM->payment_method_name;
				else return 'undefined';
				}
				
				public function get_order_status () {
				$STATUS =  OrderStatuses::model()->find('id=:ID ', array(':ID'=>$this->order_status ));
				return $STATUS->description;
				}
				
				public function get_order_client_info () {
					$client =  Clients::model()->find('id=:ID ', array(':ID'=>$this->id_client ));
					if($client!=null){
						$fio['fio'] = trim($client->second_name.' '.$client->first_name.' '.$client->last_name);
						$fio['email'] = $client->client_email;
						$fio['phone'] = $client->client_tels;
						return $fio;
					}
					else return null;
				}
				
				public static function getSumm($order_id){
				    $sum = 0;
				    $order = Orders::model()->with('OrderContent')->findByPk($order_id);
				    if(is_null($order)==false){
				        if(isset($order->OrderContent) && is_null($order->OrderContent)==false && is_array($order->OrderContent)==true){
				            foreach ($order->OrderContent as $rec) {
				                $sum+= $rec->contents_price*$rec->quantity;
				            }
				        }
				    }
				    return $sum;
				}
				

}////////class 

?>
