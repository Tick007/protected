<?php

class Goods extends CActiveRecord
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
		return 'goods';
	}

	function __construct(){
	$this->connection = Yii::app()->db; 
	
	}///////////function __construct(){
	

	
}