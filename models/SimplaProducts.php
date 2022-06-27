<?php

/**
 * This is the model class for table "s_products".
 *
 * The followings are the available columns in table 's_products':
 * @property integer $id
 * @property string $url
 * @property integer $brand_id
 * @property string $name
 * @property string $annotation
 * @property string $body
 * @property integer $visible
 * @property integer $position
 * @property string $meta_title
 * @property string $meta_keywords
 * @property string $meta_description
 * @property string $created
 * @property integer $featured
 * @property string $external_id
 * @property string $video
 * @property string $review_name
 * @property string $complect
 * @property string $review_360
 * @property integer $warranty_card
 * @property string $expiration_date
 * @property string $supply_date
 */
class SimplaProducts extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return SimplaProducts the static model class
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
		return 's_products';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, annotation, body, meta_title, meta_keywords, meta_description, external_id, video, review_name, complect, review_360, expiration_date, supply_date', 'required'),
			
			array('brand_id, visible, position, featured, warranty_card', 'numerical', 'integerOnly'=>true),
			array('url', 'length', 'max'=>255),
			array('name, meta_title, meta_keywords, meta_description, review_name, complect', 'length', 'max'=>500),
			array('external_id', 'length', 'max'=>36),
			array('created', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, url, brand_id, name, annotation, body, visible, position, meta_title, meta_keywords, meta_description, created, featured, external_id, video, review_name, complect, review_360, warranty_card, expiration_date, supply_date', 'safe', 'on'=>'search'),
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
				'variant'=>array(self::HAS_ONE, 'SimplaVariants', 'product_id'),
				'descriptions'=>array(self::HAS_MANY, 'SProductsDescriptions', 'product_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'url' => 'Url',
			'brand_id' => 'Brand',
			'name' => 'Name',
			'annotation' => 'Annotation',
			'body' => 'Body',
			'visible' => 'Visible',
			'position' => 'Position',
			'meta_title' => 'Meta Title',
			'meta_keywords' => 'Meta Keywords',
			'meta_description' => 'Meta Description',
			'created' => 'Created',
			'featured' => 'Featured',
			'external_id' => 'External',
			'video' => 'Video',
			'review_name' => 'Review Name',
			'complect' => 'Complect',
			'review_360' => 'Review 360',
			'warranty_card' => 'Warranty Card',
			'expiration_date' => 'Expiration Date',
			'supply_date' => 'Supply Date',
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
		$criteria->compare('url',$this->url,true);
		$criteria->compare('brand_id',$this->brand_id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('annotation',$this->annotation,true);
		$criteria->compare('body',$this->body,true);
		$criteria->compare('visible',$this->visible);
		$criteria->compare('position',$this->position);
		$criteria->compare('meta_title',$this->meta_title,true);
		$criteria->compare('meta_keywords',$this->meta_keywords,true);
		$criteria->compare('meta_description',$this->meta_description,true);
		$criteria->compare('created',$this->created,true);
		$criteria->compare('featured',$this->featured);
		$criteria->compare('external_id',$this->external_id,true);
		$criteria->compare('video',$this->video,true);
		$criteria->compare('review_name',$this->review_name,true);
		$criteria->compare('complect',$this->complect,true);
		$criteria->compare('review_360',$this->review_360,true);
		$criteria->compare('warranty_card',$this->warranty_card);
		$criteria->compare('expiration_date',$this->expiration_date,true);
		$criteria->compare('supply_date',$this->supply_date,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}