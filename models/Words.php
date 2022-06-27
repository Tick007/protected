<?php

/**
 * This is the model class for table "words".
 *
 * The followings are the available columns in table 'words':
 * @property integer $id
 * @property string $nom
 * @property string $gen
 * @property string $dat
 * @property string $acc
 * @property string $str
 * @property string $prep
 */
class Words extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Words the static model class
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
		return 'words';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
		array('nom, gen, dat, acc, str, prep', 'required'),
		array('nom, gen, dat, acc, str, prep', 'length', 'max'=>255),
		// The following rule is used by search().
		// Please remove those attributes that should not be searched.
		array('id, nom, gen, dat, acc, str, prep', 'safe', 'on'=>'search'),
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
			'nom' => 'Nom',
			'gen' => 'Gen',
			'dat' => 'Dat',
			'acc' => 'Acc',
			'str' => 'Str',
			'prep' => 'Prep',
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
		$criteria->compare('nom',$this->nom,true);
		$criteria->compare('gen',$this->gen,true);
		$criteria->compare('dat',$this->dat,true);
		$criteria->compare('acc',$this->acc,true);
		$criteria->compare('str',$this->str,true);
		$criteria->compare('prep',$this->prep,true);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
}