<?php

/**
 * This is the model class for table "s_variants".
 *
 * The followings are the available columns in table 's_variants':
 * @property string $id
 * @property double $merlion_price
 * @property integer $merlion_count
 * @property integer $product_id
 * @property string $sku
 * @property string $name
 * @property double $price
 * @property double $compare_price
 * @property integer $stock
 * @property integer $position
 * @property string $attachment
 * @property string $external_id
 */
class SimplaVariants extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return SimplaVariants the static model class
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
		return 's_variants';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('product_id, sku, name, price, position, attachment, external_id', 'required'),
			array('merlion_count, product_id, stock, position', 'numerical', 'integerOnly'=>true),
			array('merlion_price, price, compare_price', 'numerical'),
			array('sku, name, attachment', 'length', 'max'=>255),
			array('external_id', 'length', 'max'=>36),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, merlion_price, merlion_count, product_id, sku, name, price, compare_price, stock, position, attachment, external_id', 'safe', 'on'=>'search'),
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
			'merlion_price' => 'Merlion Price',
			'merlion_count' => 'Merlion Count',
			'product_id' => 'Product',
			'sku' => 'Sku',
			'name' => 'Name',
			'price' => 'Price',
			'compare_price' => 'Compare Price',
			'stock' => 'Stock',
			'position' => 'Position',
			'attachment' => 'Attachment',
			'external_id' => 'External',
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
		$criteria->compare('merlion_price',$this->merlion_price);
		$criteria->compare('merlion_count',$this->merlion_count);
		$criteria->compare('product_id',$this->product_id);
		$criteria->compare('sku',$this->sku,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('price',$this->price);
		$criteria->compare('compare_price',$this->compare_price);
		$criteria->compare('stock',$this->stock);
		$criteria->compare('position',$this->position);
		$criteria->compare('attachment',$this->attachment,true);
		$criteria->compare('external_id',$this->external_id,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}