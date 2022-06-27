<?php

class Profile_values extends CActiveRecord
{	


	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}


	public function tableName()
	{
		//return 'User';
		return 'profile_values';
	}



}