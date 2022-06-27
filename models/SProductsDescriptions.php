<?php

/**
 * This is the model class for table "s_products_descriptions".
 *
 * The followings are the available columns in table 's_products_descriptions':
 * @property integer $id
 * @property integer $product_id
 * @property string $block_type
 * @property string $title
 * @property string $description
 * @property integer $active
 * @property integer $noindex
 */
class SProductsDescriptions extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return SProductsDescriptions the static model class
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
		return 's_products_descriptions';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('product_id, title, description', 'required'),
			array('product_id, active, noindex', 'numerical', 'integerOnly'=>true),
			array('block_type', 'length', 'max'=>10),
			array('title', 'length', 'max'=>1024),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, product_id, block_type, title, description, active, noindex', 'safe', 'on'=>'search'),
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
				'texts' =>array(self::HAS_MANY, 'SProductsDescriptionsTexts',  'description_id'),
				'images'=>array(self::HAS_MANY, 'SProductsDescriptionsImages', 'description_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'product_id' => 'Product',
			'block_type' => 'Тип блока',
			'title' => 'Заголовок',
			'description' => 'Содержание',
			'active' => 'Включен',
			'noindex' => 'Noindex',
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
		$criteria->compare('product_id',$this->product_id);
		$criteria->compare('block_type',$this->block_type,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('active',$this->active);
		$criteria->compare('noindex',$this->noindex);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function descrtypes(){
		
	}
}










