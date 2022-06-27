<?php

/**
 * ContactForm class.
 * ContactForm is the data structure for keeping
 * contact form data. It is used by the 'contact' action of 'SiteController'.
 */
class RegisterrequestForm extends CFormModel
{
	public $name;
	public $email;
	public $brand;
	public $dealerCode;
	public $verifyCode;
	//public $educationlist=array(0=>'', 1=>'Менее 1 года', 2=>'От 1 до 3х лет', 3=>'От 3х до 5 лет', 4=>'Более 5 лет');
	public $tel;
	public $city;
	public $company;
	public $propertyform;
	

	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			// name, email, subject and body are required
			array('brand, dealerCode, company,   city, name, tel,  email', 'required'),
			// email has to be a valid email address
			array('email', 'email'),
			// verifyCode needs to be entered correctly
			array('verifyCode', 'captcha', 'allowEmpty'=>!extension_loaded('gd')),
			//array('title, h1, children_option_name, search_keywords, description, keywords', 'safe'),
			//array('education', 'compare', 'compareValue' => 0, 'operator' => '>',    'message' => 'Укажите опыт торговли '),
			//array('body, expirience', 'checkchars')
		);
	}

	/**
	 * Declares customized attribute labels.
	 * If not declared here, an attribute would have a label that is
	 * the same as its name with the first letter in upper case.
	 */
	 public function checkchars($attribute){
		$this->$attribute = htmlspecialchars($this->$attribute);
	 }
	 
	public function attributeLabels()
	{
		return array(
			
			'brand'=>'Бренд',
			'dealerCode'=>'Дилерский код',
			'company'=>'Название компании',
			'city'=>'Город',
			'name'=>'Контактное лицо',
			//'subject'=>'Тема',
			'tel'=>'Телефон',
			'email'=>'Почта', 
			'verifyCode'=>'Антиспам',
			
			
		);
	}
	
	public function notListHtmlFields(){
		return array('verifyCode');
	}
	
	
	
}