<?php

class Ostatki_trigers extends CActiveRecord ///////////
{	

	private $connection;

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}


	public function tableName()
	{
		//return 'User';
		return 'ostatki_trigers';
	}

	public function relations()
		{
					return array(
					//'picture_product' => array(self::HAS_MANY, 'Picture_product', 'picture'),
					//'picture_category' => array(self::HAS_MANY, 'Picture_category', 'picture'),
					//'child_items'=>array(self::MANY_MANY, 'Authitem', 'AuthItemChild(parent, child)'),
					//'authitemchild'=> array(self::BELONGS_TO, 'Authitemchild', 'name'),
					
					);
					
		}

	public static function clear_ostatki_store($store_id){
		
		$query = "DELETE FROM ostatki_trigers WHERE store = ".$store_id;
		$connection=Yii::app()->db;
		$command=$connection->createCommand($query)	;
		$dataReader=$command->query(); ////
		
	}////////public static function clear_ostatki_store($store_id){
	
}