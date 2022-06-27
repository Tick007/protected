<?php

/**
 * This is the model class for table "s_purchases".
 *
 * The followings are the available columns in table 's_purchases':
 * @property integer $id
 * @property integer $order_id
 * @property integer $product_id
 * @property integer $variant_id
 * @property string $product_name
 * @property string $variant_name
 * @property double $price
 * @property integer $amount
 * @property string $sku
 */
class SimplaPurchases extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return SimplaPurchases the static model class
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
		return 's_purchases';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('variant_name, sku', 'required'),
			array('order_id, product_id, variant_id, amount', 'numerical', 'integerOnly'=>true),
			array('price', 'numerical'),
			array('product_name, variant_name, sku', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, order_id, product_id, variant_id, product_name, variant_name, price, amount, sku', 'safe', 'on'=>'search'),
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
		
				's_order'    => array(self::BELONGS_TO, 'SimplaOrders', 'order_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'order_id' => 'Order',
			'product_id' => 'Product',
			'variant_id' => 'Variant',
			'product_name' => 'Product Name',
			'variant_name' => 'Variant Name',
			'price' => 'Price',
			'amount' => 'Amount',
			'sku' => 'Sku',
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
		$criteria->compare('order_id',$this->order_id);
		$criteria->compare('product_id',$this->product_id);
		$criteria->compare('variant_id',$this->variant_id);
		$criteria->compare('product_name',$this->product_name,true);
		$criteria->compare('variant_name',$this->variant_name,true);
		$criteria->compare('price',$this->price);
		$criteria->compare('amount',$this->amount);
		$criteria->compare('sku',$this->sku,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}