<?php
class Authassignment extends CActiveRecord {/////////////////////////

public static function model($className=__CLASS__)
		{
			return parent::model($className);
		}


		public function tableName()
		{
			//return 'User';
			return 'AuthAssignment';
		}
	



}//////////////////// class 
?>