<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class RegisterFormLight extends CFormModel  ////////////////Сокращенная форма регистрации для создания пользователей в админке
{
	
	public $login;
	public $client_password;
	public $verifyCode;
	public $client_email;
	public $accept_rules;
	public $first_name;
	public $second_name	;
	public $authassignment;
	public $client_tels;
	public $status;
	public $userid;
	public $client_street;
	public $client_city;
	public $client_country;
	public $client_post_index;
	public $urlico_txt;
	public $company_contact;
	public $client_passport;
	
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
			
			//array('login, client_password, client_email , first_name, second_name', 'required'),
		    array('client_password, client_email , first_name, login',  'required', 'on'=>'register'),
		    array('client_tels, client_passport,client_country,client_city,client_street,urlico_txt,company_contact', 'safe', 'on'=>'register'),
			//array('first_name, second_name', 'check_for_probel'),
		    array('login', 'check_unique', 'on'=>'register'), ////////////////////////////////Проверяем не занятли логин 
		   // array('verifyCode, client_password, client_email',  'required', 'on'=>'register'), //////Запрашиваем только когда вызвана форма с парамером register
		    array('verifyCode', 'captcha', 'allowEmpty'=>!extension_loaded('gd'), 'on'=>'register'),
		    
		    /////////////Дорегистрация после заказа
		    array('first_name,second_name, client_password, client_tels,client_email,urlico_txt, client_street, client_city, client_post_index,client_country,company_contact',  'required', 'on'=>'afterorder'),
		    array('client_passport',  'safe', 'on'=>'afterorder'),
		    
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
		    'verifyCode'=>Yii::t('сhemimart', 'Antispam'),
		    'login'=>Yii::t('сhemimart', 'Login'),
		    
			//'passcode2'=>'Пароль повтор',
			//'accept_rules'=>'Согласие с правилами проекта',
		    'first_name'=>Yii::t('сhemimart', 'Name'),
		    'second_name'=>Yii::t('сhemimart', 'Second name'),
		    'client_password'=>Yii::t('сhemimart', 'Password'),
		    'client_email'=>Yii::t('сhemimart', 'Email'),
		    
		    'client_tels'=>Yii::t('сhemimart', 'Tel'),
		    'status'=>Yii::t('сhemimart', 'Status'),
		    'client_tels'=>Yii::t('сhemimart', 'Phone'),
		    'client_country'=>Yii::t('сhemimart', 'Country'),
		    'client_city'=>Yii::t('сhemimart', 'City'),
		    'client_street'=>Yii::t('сhemimart', 'Street'),
		    'client_post_index'=>'Zip code',
		    'urlico_txt'=>'Company',
		    'company_contact'=>'Contact person',
		    'client_passport'=>'VAT registration number',
		    
			
		);
	}

	/**
	 * Authenticates the password.
	 * This is the 'authenticate' validator as declared in rules().
	 */
public function check_for_probel($attribute,$params){ /////////////Проверяем на наличие пробела
	//print_r($attribute);
	//echo '<br>';
	//print_r($this->$attribute);
	//echo '<br>';
	/*
	$subject = $this->$attribute;
	$pattern = '/^def/';
	preg_match($pattern, $subject, $matches);
	print_r($matches);
	*/
	if (empty($this->$attribute)==false) {
		
		$subject = $this->$attribute;
		//$pattern = "/[^(\w)|(\s)|(\w)|]/";
		$pattern = "/[^\w+]{0,}[\s]/";
		preg_match($pattern, $subject, $matches);
		//echo 'matches = ';
		//print_r($matches);
		$labels = $this->attributeLabels();
		
		
		if(isset($matches) AND empty($matches)==false)$this->addError('first_name_1','Field '.$labels[$attribute].' must not contain any blanks');
	}
}//////////////////check_for_probel

public function check_unique(){//////////////
		if (isset($this->userid) AND trim($this->userid)!='') { /////////Т.е. это проверка существующего пользователя
			$criteria=new CDbCriteria;
			$criteria->condition = "t.login = :login AND t.id <> :id";
			$criteria->params = array(':login'=>$this->login, ':id'=>$this->userid);
			$client_by_login = User::model()->find($criteria);
		}///////////////
		else 	$client_by_login = User::model()->findByAttributes(array('login'=>$this->login));
		if ($client_by_login != NULL) $this->addError('login','Login "'.$this->login.'" is busy');
}////////////////public function check_unique(){


public function notListHtmlFields(){
    if(isset(Yii::app()->params['notListHtmlFields'])) return Yii::app()->params['notListHtmlFields'];
    return array();
}

public  function afterValidate(){

}


public function registerUser() { 
    $client = new Clients();
    $client->first_name=                $this->first_name;
    $client->login=                     $this->login;
    $client->client_email=              $this->client_email;
    $client->client_tels=               $this->client_tels;
    $client->client_password =          $this->client_password;
    $client->client_street =            $this->client_street;
    $client->client_city =              $this->client_city;
    $client->client_post_index =        $this->client_post_index;
    $client->client_country =           $this->client_country;
    $client->urlico_txt =               $this->urlico_txt;
    $client->client_passport =          $this->client_passport;
    $client->urlico_txt.='/'.$this->company_contact;
    
    try {
        $client->save();
        return $client;
        
    } catch (Exception $e) {
        print_r($e);
    }
    
    
    
}

/**
 * Метод вызывается из Chemimart actionRegisterfull
 * Служит для до-регистрации незалогиненного пользователя после формирования заказа 
 */
public function updateUser($client_id){
    /*
    echo '<pre>';
    print_r($this);
    echo '</pre>';
    */
    $client = Clients::model()->findByPk($client_id);
    if($client!=null){
        $client->first_name = $this->first_name;
        $client->second_name = $this->second_name;
        $client->client_tels = $this->client_tels;
        $client->client_email = $this->client_email;
        $client->client_post_index = $this->client_post_index;
        $client->client_country = $this->client_country;
        $client->client_city = $this->client_city;
        $client->client_street = $this->client_street;
        $client->urlico_txt = $this->urlico_txt.' / '.$this->company_contact;
        $client->client_password = $this->client_password;
        try {
            $client->save();
            return true;
        } catch (Exception $e) {
            print_r($e);
        }
     return false;   
    }
}





	
}
