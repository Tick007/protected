<?php

class World_adres_cities extends CActiveRecord ///////////
{
	var $region_alias;
	var $region_name;

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}


	public function tableName()
	{
		//return 'User';
		return 'world_adres_cities';
	}

	public function relations()
	{
		return array(
		//'picture_product' => array(self::HAS_MANY, 'Picture_product', 'picture'),
		//'picture_category' => array(self::HAS_MANY, 'Picture_category', 'picture'),
		//'child_items'=>array(self::MANY_MANY, 'Authitem', 'AuthItemChild(parent, child)'),
		'region'=> array(self::BELONGS_TO, 'World_adres_regions', 'region_id'),
		'country'=>array(self::BELONGS_TO, 'World_adres_countries', 'country_id'),
		'metro_lines' => array(self::HAS_MANY, 'Metro_lines', 'world_kladr_city_id'),
			
		);
			
	}


}