<?php

class Picture_category extends CActiveRecord ///////////
{	

	private $connection;

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}


	public function tableName()
	{
		//return 'User';
		return 'picture_category';
	}

	public function relations()
		{
					return array(
					//'picture' => array(self::HAS_ONE, 'Pictures', 'picture'),
					//'child_items'=>array(self::MANY_MANY, 'Authitem', 'AuthItemChild(parent, child)'),
					//'authitemchild'=> array(self::BELONGS_TO, 'Authitemchild', 'name'),
					);
		}
	
	
}