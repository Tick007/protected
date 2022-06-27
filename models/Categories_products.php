<?php

class Categories_products extends CActiveRecord ///////////
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
		return 'categories_products';
	}

	public function relations()
		{
					return array(
					//'linked_products' => array(self::HAS_MANY, 'Products', 'id'),
					//'child_items'=>array(self::MANY_MANY, 'Authitem', 'AuthItemChild(parent, child)'),
					'category'=> array(self::BELONGS_TO, 'Categories', 'group'),
					'product'=> array(self::BELONGS_TO, 'Products', 'product'),
					);
		}

	
}