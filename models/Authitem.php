<?php
class Authitem extends CActiveRecord {/////////////////////////

public static function model($className=__CLASS__)
		{
			return parent::model($className);
		}


		public function tableName()
		{
			//return 'User';
			return 'AuthItem';
		}
	
		public function relations()
		{
					return array(
					'authassignment' => array(self::HAS_MANY, 'Authassignment', 'itemname'),
					'operations'=>array(self::MANY_MANY, 'Authitem', 'AuthItemChild(parent, child)'),
					'roles'=>array(self::MANY_MANY, 'Authitem', 'AuthItemChild(child, parent)'),
					'authitemchild'=> array(self::BELONGS_TO, 'Authitemchild', 'name'),
					'child_items'=>array(self::MANY_MANY, 'Authitem', 'AuthItemChild(parent, child)'),
					);
		}


}//////////////////// class 
?>