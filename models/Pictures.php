<?php

class Pictures extends CActiveRecord ///////////
{	

	private $connection;

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}


	public function tableName()
	{
		//return 'User';
		return 'pictures';
	}

	public function relations()
		{
					return array(
					'picture_product' => array(self::HAS_MANY, 'Picture_product', 'picture'),
					'picture_category' => array(self::HAS_MANY, 'Picture_category', 'picture'),
					//'child_items'=>array(self::MANY_MANY, 'Authitem', 'AuthItemChild(parent, child)'),
					//'authitemchild'=> array(self::BELONGS_TO, 'Authitemchild', 'name'),
					
					);
					
		}

	
}