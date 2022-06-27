<?php

class World_adres_regions extends CActiveRecord ///////////
{


	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}


	public function tableName()
	{
		//return 'User';
		return 'world_adres_regions';
	}

	public function relations()
	{
		return array(
		'cities' => array(self::HAS_MANY, 'World_adres_cities', 'region_id'),
		//'picture_category' => array(self::HAS_MANY, 'Picture_category', 'picture'),
		//'child_items'=>array(self::MANY_MANY, 'Authitem', 'AuthItemChild(parent, child)'),
		'countries'=> array(self::BELONGS_TO, 'World_adres_countries', 'country_id'),
			
		);
			
	}


}