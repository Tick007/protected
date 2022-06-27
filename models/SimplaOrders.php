<?php

/**
 * This is the model class for table "s_orders".
 *
 * The followings are the available columns in table 's_orders':
 * @property string $id
 * @property string $ddelivery_id
 * @property string $delivery_pack
 * @property string $delivery_info
 * @property integer $cash_update
 * @property integer $delivery_id
 * @property integer $delivery_pvz_id
 * @property integer $delivery_cur_id
 * @property double $delivery_price
 * @property integer $payment_method_id
 * @property integer $paid
 * @property string $payment_date
 * @property integer $closed
 * @property string $date
 * @property string $datemod
 * @property integer $user_id
 * @property string $name
 * @property string $treking1
 * @property string $treking2
 * @property string $treking_forw
 * @property string $delivery_name
 * @property string $address
 * @property string $phone
 * @property string $email
 * @property string $comment
 * @property integer $status
 * @property string $url
 * @property string $payment_details
 * @property string $ip
 * @property double $total_price
 * @property double $total_price_discount
 * @property string $note
 * @property double $discount
 * @property double $coupon_discount
 * @property string $coupon_code
 * @property string $discount_info
 * @property integer $separate_delivery
 * @property string $modified
 * @property string $whose_order
 * @property string $city_id
 * @property integer $predzakaz
 * @property integer $cfl
 * @property string $courier
 * @property string $chstart
 * @property string $chstop
 * @property integer $type_ks
 * @property string $chdate
 * @property string $ks_time
 * @property string $ks_manager
 * @property integer $ks_status
 * @property integer $ks_is_check
 * @property integer $id_market
 * @property string $status_market
 * @property string $substatus_market
 * @property string $firstname
 * @property string $lastname
 * @property string $surname
 * @property string $flat
 * @property string $house
 * @property string $street
 * @property integer $postalcode
 * @property string $geolocation
 * @property integer $postamat
 */
class SimplaOrders extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return SimplaOrders the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 's_orders';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('delivery_pack, delivery_info, payment_date, closed, datemod, treking1, treking2, treking_forw, delivery_name, address, email, comment, payment_details, ip, total_price, total_price_discount, note, discount, coupon_discount, coupon_code, discount_info, modified, whose_order, city_id, courier, ks_time, ks_manager, id_market, substatus_market, firstname, lastname, surname, flat, house, street, geolocation', 'required'),
			array('cash_update, delivery_id, delivery_pvz_id, delivery_cur_id, payment_method_id, paid, closed, user_id, status, separate_delivery, predzakaz, cfl, type_ks, ks_status, ks_is_check, id_market, postalcode, postamat', 'numerical', 'integerOnly'=>true),
			array('delivery_price, total_price, total_price_discount, discount, coupon_discount', 'numerical'),
			array('ddelivery_id, chdate, ks_time', 'length', 'max'=>20),
			array('name, treking1, treking2, treking_forw, delivery_name, phone, email, url, coupon_code, whose_order, ks_manager, status_market, substatus_market, firstname, lastname, surname, flat, house, street', 'length', 'max'=>255),
			array('comment, note', 'length', 'max'=>1024),
			array('ip', 'length', 'max'=>15),
			array('chstart, chstop', 'length', 'max'=>5),
			array('date', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, ddelivery_id, delivery_pack, delivery_info, cash_update, delivery_id, delivery_pvz_id, delivery_cur_id, delivery_price, payment_method_id, paid, payment_date, closed, date, datemod, user_id, name, treking1, treking2, treking_forw, delivery_name, address, phone, email, comment, status, url, payment_details, ip, total_price, total_price_discount, note, discount, coupon_discount, coupon_code, discount_info, separate_delivery, modified, whose_order, city_id, predzakaz, cfl, courier, chstart, chstop, type_ks, chdate, ks_time, ks_manager, ks_status, ks_is_check, id_market, status_market, substatus_market, firstname, lastname, surname, flat, house, street, postalcode, geolocation, postamat', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'ddelivery_id' => 'Ddelivery',
			'delivery_pack' => 'Delivery Pack',
			'delivery_info' => 'Delivery Info',
			'cash_update' => 'Cash Update',
			'delivery_id' => 'Delivery',
			'delivery_pvz_id' => 'Delivery Pvz',
			'delivery_cur_id' => 'Delivery Cur',
			'delivery_price' => 'Delivery Price',
			'payment_method_id' => 'Payment Method',
			'paid' => 'Paid',
			'payment_date' => 'Payment Date',
			'closed' => 'Closed',
			'date' => 'Date',
			'datemod' => 'Datemod',
			'user_id' => 'User',
			'name' => 'Name',
			'treking1' => 'Treking1',
			'treking2' => 'Treking2',
			'treking_forw' => 'Treking Forw',
			'delivery_name' => 'Delivery Name',
			'address' => 'Address',
			'phone' => 'Phone',
			'email' => 'Email',
			'comment' => 'Comment',
			'status' => 'Status',
			'url' => 'Url',
			'payment_details' => 'Payment Details',
			'ip' => 'Ip',
			'total_price' => 'Total Price',
			'total_price_discount' => 'Total Price Discount',
			'note' => 'Note',
			'discount' => 'Discount',
			'coupon_discount' => 'Coupon Discount',
			'coupon_code' => 'Coupon Code',
			'discount_info' => 'Discount Info',
			'separate_delivery' => 'Separate Delivery',
			'modified' => 'Modified',
			'whose_order' => 'Whose Order',
			'city_id' => 'City',
			'predzakaz' => 'Predzakaz',
			'cfl' => 'Cfl',
			'courier' => 'Courier',
			'chstart' => 'Chstart',
			'chstop' => 'Chstop',
			'type_ks' => 'Type Ks',
			'chdate' => 'Chdate',
			'ks_time' => 'Ks Time',
			'ks_manager' => 'Ks Manager',
			'ks_status' => 'Ks Status',
			'ks_is_check' => 'Ks Is Check',
			'id_market' => 'Id Market',
			'status_market' => 'Status Market',
			'substatus_market' => 'Substatus Market',
			'firstname' => 'Firstname',
			'lastname' => 'Lastname',
			'surname' => 'Surname',
			'flat' => 'Flat',
			'house' => 'House',
			'street' => 'Street',
			'postalcode' => 'Postalcode',
			'geolocation' => 'Geolocation',
			'postamat' => 'Postamat',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('ddelivery_id',$this->ddelivery_id,true);
		$criteria->compare('delivery_pack',$this->delivery_pack,true);
		$criteria->compare('delivery_info',$this->delivery_info,true);
		$criteria->compare('cash_update',$this->cash_update);
		$criteria->compare('delivery_id',$this->delivery_id);
		$criteria->compare('delivery_pvz_id',$this->delivery_pvz_id);
		$criteria->compare('delivery_cur_id',$this->delivery_cur_id);
		$criteria->compare('delivery_price',$this->delivery_price);
		$criteria->compare('payment_method_id',$this->payment_method_id);
		$criteria->compare('paid',$this->paid);
		$criteria->compare('payment_date',$this->payment_date,true);
		$criteria->compare('closed',$this->closed);
		$criteria->compare('date',$this->date,true);
		$criteria->compare('datemod',$this->datemod,true);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('treking1',$this->treking1,true);
		$criteria->compare('treking2',$this->treking2,true);
		$criteria->compare('treking_forw',$this->treking_forw,true);
		$criteria->compare('delivery_name',$this->delivery_name,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('phone',$this->phone,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('comment',$this->comment,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('url',$this->url,true);
		$criteria->compare('payment_details',$this->payment_details,true);
		$criteria->compare('ip',$this->ip,true);
		$criteria->compare('total_price',$this->total_price);
		$criteria->compare('total_price_discount',$this->total_price_discount);
		$criteria->compare('note',$this->note,true);
		$criteria->compare('discount',$this->discount);
		$criteria->compare('coupon_discount',$this->coupon_discount);
		$criteria->compare('coupon_code',$this->coupon_code,true);
		$criteria->compare('discount_info',$this->discount_info,true);
		$criteria->compare('separate_delivery',$this->separate_delivery);
		$criteria->compare('modified',$this->modified,true);
		$criteria->compare('whose_order',$this->whose_order,true);
		$criteria->compare('city_id',$this->city_id,true);
		$criteria->compare('predzakaz',$this->predzakaz);
		$criteria->compare('cfl',$this->cfl);
		$criteria->compare('courier',$this->courier,true);
		$criteria->compare('chstart',$this->chstart,true);
		$criteria->compare('chstop',$this->chstop,true);
		$criteria->compare('type_ks',$this->type_ks);
		$criteria->compare('chdate',$this->chdate,true);
		$criteria->compare('ks_time',$this->ks_time,true);
		$criteria->compare('ks_manager',$this->ks_manager,true);
		$criteria->compare('ks_status',$this->ks_status);
		$criteria->compare('ks_is_check',$this->ks_is_check);
		$criteria->compare('id_market',$this->id_market);
		$criteria->compare('status_market',$this->status_market,true);
		$criteria->compare('substatus_market',$this->substatus_market,true);
		$criteria->compare('firstname',$this->firstname,true);
		$criteria->compare('lastname',$this->lastname,true);
		$criteria->compare('surname',$this->surname,true);
		$criteria->compare('flat',$this->flat,true);
		$criteria->compare('house',$this->house,true);
		$criteria->compare('street',$this->street,true);
		$criteria->compare('postalcode',$this->postalcode);
		$criteria->compare('geolocation',$this->geolocation,true);
		$criteria->compare('postamat',$this->postamat);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}