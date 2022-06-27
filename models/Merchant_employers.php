<?php

class Merchant_employers extends CActiveRecord ///////////
{

	var $ca_name;

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}


	public function tableName()
	{
		return 'merchant_employers';
	}


	public function relations()
	{
		return array(
		//'categories_products'=> array(self::HAS_MANY, 'Categories_products', 'product'),
		//'child_items'=>array(self::MANY_MANY, 'Authitem', 'AuthItemChild(parent, child)'),
		//'authitemchild'=> array(self::BELONGS_TO, 'Authitemchild', 'name'),
		'contragent'    => array(self::BELONGS_TO, 'Contr_agents', 'contr_agent_id'),
		'client'=>array(self::BELONGS_TO, 'Clients', 'client_id'),
		//'char_val'			 =>array(self::HAS_MANY, 'Characteristics_values', 'id_product'),
		);
	}


}