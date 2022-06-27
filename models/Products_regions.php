<?php

class Products_regions extends CActiveRecord
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
		return 'products_regions';
	}

	public function relations()
	{
		return array(
		//'cities' => array(self::HAS_MANY, 'World_adres_cities', 'city'),
		//'picture_category' => array(self::HAS_MANY, 'Picture_category', 'picture'),
		//'child_items'=>array(self::MANY_MANY, 'Authitem', 'AuthItemChild(parent, child)'),
		'products'=> array(self::HAS_MANY, 'Products', 'product'),
		'productmodel'=> array(self::BELONGS_TO, 'Products', 'product'),	
		'gorod'=> array(self::BELONGS_TO, 'World_adres_cities', 'city'),	
		);
			
	}

	

	
}