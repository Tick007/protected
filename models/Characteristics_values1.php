<?php

class Characteristics_values extends CActiveRecord ///////////
{	

	private $connection;
	public $head_images; /////////////////////Тут будет храниться массив с фоками модели и бренда

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}


	public function tableName()
	{
		//return 'User';
		return 'characteristics_values';
	}

	public function relations()
	{
		return array(
		'characteristics' => array(self::BELONGS_TO, 'Characteristics', 'id_caract'), 
		'products'    => array(self::BELONGS_TO, 'Products', 'id_product'),//////////////////////////только для типа 9 (товар)
		//'ma_soun' => array(self::BELONGS_TO, 'Ma_soun', 'value'),
		);
	}

	function init()
	{
		$this->connection = Yii::app()->db; 
		return parent::init();
	}
	

	
}
