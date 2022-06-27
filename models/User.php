<?php

class User extends CActiveRecord
{

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}


	public function tableName()
	{
		//return 'User';
		return 'clients';
	}


	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('login ', 'required'),
			array('login, client_password, client_email', 'length', 'max'=>128),
		);
	}


//'profile_values' => array(self::HAS_MANY, 'Profile_values', 'uid'),

	public function relations()
		{
					return array(
				//	'authassignment' => array(self::HAS_MANY, 'Authassignment', 'itemname'),
				//	'child_items'=>array(self::MANY_MANY, 'Authitem', 'AuthItemChild(parent, child)'),
					//'authitemchild'=> array(self::BELONGS_TO, 'Authitemchild', 'name'),
					'kontragent'=> array(self::BELONGS_TO, 'Contr_agents', 'urlico'),
					'authassignment' => array(self::HAS_ONE, 'Authassignment', 'userid'), 
					'inbox' => array(self::HAS_MANY, 'Message', 'from_user'),
					'authentications'=>array(self::HAS_MANY, 'Authentications', 'user_id'),
					'city'=> array(self::BELONGS_TO, 'World_adres_cities', 'client_city'),
					'card' => array(self::HAS_ONE, 'ClientCards', 'client_id'),
					
					);
		}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'Id',
			'username' => 'Username',
			'password' => 'Password',
			'email' => 'Email',
		);
	}
	
	
}