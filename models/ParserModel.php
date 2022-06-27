<?php

class ParserModel extends CActiveRecord
{
	const STATUS_FAILURE = 0; 
	const STATUS_SUCCESS = 1;
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return 'parser';
	}
	
	public function primaryKey()
	{
		return 'key';
	}
}