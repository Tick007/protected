<?php
class Authitemchild extends CActiveRecord {/////////////////////////

public static function model($className=__CLASS__)
		{
			return parent::model($className);
		}


		public function tableName()
		{
			//return 'User';
			return 'AuthItemChild';
		}
	
		public function relations()
		{
					return array(
					//'categories_products'=> array(self::HAS_MANY, 'Categories_products', 'product'),
					//'child_items'=>array(self::MANY_MANY, 'Authitem', 'AuthItemChild(parent, child)'),
					//'authitemchild'=> array(self::BELONGS_TO, 'Authitemchild', 'name'),
					//'roles'         => array(self::BELONGS_TO, 'Authitem', 'parent'),
					//'operations' =>array(self::HAS_MANY, 'Authitem', 'name'),
					);
		}



}//////////////////// class 
?>