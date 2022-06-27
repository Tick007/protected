<?php

/**
 * This is the model class for table "s_products_descriptions_images".
 *
 * The followings are the available columns in table 's_products_descriptions_images':
 * @property integer $id
 * @property integer $description_id
 * @property string $image_type
 * @property string $ext
 * @property integer $sort
 * @property integer $active
 */
class SProductsDescriptionsImages extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return SProductsDescriptionsImages the static model class
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
		return 's_products_descriptions_images';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('description_id', 'required'),
			array('description_id, sort, active', 'numerical', 'integerOnly'=>true),
			array('image_type', 'length', 'max'=>5),
			array('ext', 'length', 'max'=>10),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, description_id, image_type, ext, sort, active, 	image_text', 'safe', 'on'=>'search'),
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
			'description_id' => 'Description',
			'image_type' => 'Image Type',
			'ext' => 'Ext',
			'sort' => 'Sort',
			'active' => 'Active',
			'image_text'=>'Описание для картинки',
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
		$criteria->compare('description_id',$this->description_id);
		$criteria->compare('image_type',$this->image_type,true);
		$criteria->compare('ext',$this->ext,true);
		$criteria->compare('sort',$this->sort);
		$criteria->compare('active',$this->active);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}