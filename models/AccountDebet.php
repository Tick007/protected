<?php

/**
 * This is the model class for table "account_debet".
 *
 * The followings are the available columns in table 'account_debet':
 * @property integer $id
 * @property string $operation_datetime
 * @property double $money
 * @property integer $payment_system
 * @property string $crc
 * @property integer $order_id
 */
class AccountDebet extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return AccountDebet the static model class
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
		return 'account_debet';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('payment_system, order_id', 'numerical', 'integerOnly'=>true),
			array('money', 'numerical'),
			array('crc', 'length', 'max'=>256),
			array('operation_datetime', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, operation_datetime, money, payment_system, crc, order_id', 'safe', 'on'=>'search'),
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
	        'order'=> array(self::BELONGS_TO, 'Orders', 'order_id'),
	    );
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'operation_datetime' => 'Operation Datetime',
			'money' => 'Money',
			'payment_system' => 'Payment System',
			'crc' => 'Crc',
			'order_id' => 'Order',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('operation_datetime',$this->operation_datetime,true);
		$criteria->compare('money',$this->money);
		$criteria->compare('payment_system',$this->payment_system);
		$criteria->compare('crc',$this->crc,true);
		$criteria->compare('order_id',$this->order_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}