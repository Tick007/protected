<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class RegisterForm extends CFormModel
{
	
	public $newlogin;
	public $passcode;
	public $passcode2;
	public $client_email;
	public $first_name;
	public $second_name;
	public $client_city;
	public $client_country;
	public $client_tels;
	
	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			// username and password are required
			// password needs to be authenticated
			
			array('newlogin, passcode, passcode2, client_email', 'required'),
			array('passcode2', 'compare', 'compareAttribute'=>'passcode'),
			//array('first_name, second_name', 'exist'),
			
			array('first_name,  second_name, client_country, client_city, client_tels', 'safe'),
		//	array('first_name, second_name, last_name, client_country', 'allowEmpty'=>true),
			array('passcode', 'authenticate'),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'newlogin'=>'Логин',
			'passcode'=>'Пароль',
			'passcode2'=>'Пароль повтор',
			'client_email'=>'Электронная почта',
			'first_name'=>'Имя',
			'second_name'=>'Фамилия',
			'client_country'=>'Старна',
			'client_tels'=>'Телефон для связи',
			'client_city'=>'Город',
		);
	}

	/**
	 * Authenticates the password.
	 * This is the 'authenticate' validator as declared in rules().
	 */
	public function authenticate($attribute,$params)
	{	
			
			//echo $this->hasErrors();
			
		if(!$this->hasErrors())  // we only want to authenticate when no input errors
		{
			$identity=new UserIdentity($this->newlogin,$this->passcode);
			$identity->authenticate();
			//echo "аутен: ".$identity->errorCode;
			///////////////1-пользователя нет
			////////////2 - неправильный пароль
			//////////// 0 -правильно
			
			switch($identity->errorCode)
			{
			/*
				case UserIdentity::ERROR_NONE:
					$duration=$this->rememberMe ? 3600*24*30 : 0; // 30 days
					Yii::app()->user->login($identity,$duration);
					break;
				case UserIdentity::ERROR_USERNAME_INVALID:
					$this->addError('username','Username is incorrect.');
					break;
				default: // UserIdentity::ERROR_PASSWORD_INVALID
					$this->addError('password','Password is incorrect.');
					break;
					*/
					case 1:
					$AR_Client =  new Clients;
					$AR_Client->login=$this->newlogin;
					$AR_Client->client_password=$this->passcode;
					$AR_Client->client_email = $this->client_email;
					$AR_Client->first_name = $this->first_name;
					$AR_Client->second_name = $this->second_name;
					$AR_Client->client_city = $this->client_city;
					$AR_Client->client_country = $this->client_country;
					$AR_Client->client_tels = $this->client_tels;
					$AR_Client->save();
					$identity=new UserIdentity($this->newlogin,$this->passcode);
					$identity->authenticate();
					$duration = 30;
					Yii::app()->user->login($identity,$duration);
					/////////////////////регистрируем нового
					break;
					case  0:
					$this->addError('login','Логин занят.');
					break;
					case 2:
					$this->addError('login','Логин занят.');
					break;
			}
			
		}
	}
	
	
	public function notListHtmlFields(){
	    return array();
	}
	

	
}
