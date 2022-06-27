<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class LoginForm extends CFormModel
{
	public $username;
	public $password;
	public $returnUrl;
	public $rememberMe;

	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			// username and password are required
			array('username, password', 'required'),
			// password needs to be authenticated
			array('password', 'authenticate'),
			array('rememberMe, returnUrl', 'safe'),
			
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
		    'rememberMe'=>Yii::t(Yii::app()->theme->name, 'Remember me'),
		    'username'=>Yii::t(Yii::app()->theme->name, 'Username'),
		    'password'=>Yii::t(Yii::app()->theme->name, 'Password'),
		);
	}

	/**
	 * Authenticates the password.
	 * This is the 'authenticate' validator as declared in rules().
	 */
	public function authenticate($attribute,$params)
	{
		if(!$this->hasErrors())  // we only want to authenticate when no input errors
		{
			$identity=new UserIdentity($this->username,$this->password);
			$identity->authenticate();
			switch($identity->errorCode)
			{
				case UserIdentity::ERROR_NONE:
					//$duration=$this->rememberMe ? 3600*24*30 : 0; // 30 days
				    $duration=$this->rememberMe ? 3600*24*30 : 3600; // 30 days
					Yii::app()->user->login($identity,$duration);
					break;
				case UserIdentity::ERROR_USERNAME_INVALID:
					$this->addError('username','Username is incorrect.');
					break;
				case UserIdentity::ERROR_ACCOUNT_DISABLED:
				    $this->addError('username','Account is disabled.');
				    break;
				    
				default: // UserIdentity::ERROR_PASSWORD_INVALID
					$this->addError('password','Password is incorrect.');
					break;
			}
		}
	}
	

	
}
