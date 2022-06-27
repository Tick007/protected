<?php

/**
 * ContactForm class.
 * ContactForm is the data structure for keeping
 * contact form data. It is used by the 'contact' action of 'SiteController'.
 */
class VacancyForm extends CFormModel
{
	public $name;
	public $familiya;
	public $date;
	public $email;
	public $subject;
	public $body;
	public $verifyCode;
	public $education;
	public $educationlist=array(1=>'Высшее', 2=>'Среднее', 3=>'Средне-специальне');
	public $vuz;
	public $tel;

	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			// name, email, subject and body are required
			array('name, familiya, date, education, tel,  email,  body', 'required'),
			// email has to be a valid email address
			array('email', 'email'),
			// verifyCode needs to be entered correctly
			//array('verifyCode', 'captcha', 'allowEmpty'=>!extension_loaded('gd')),
			//array('title, h1, children_option_name, search_keywords, description, keywords', 'safe'),
			array('vuz', 'checkchars')
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
			//'verifyCode'=>'Антиспам',
			'name'=>'Имя',
			'familiya'=>'Фамилия',
			'date'=>'Дата рождения',
			//'subject'=>'Тема',
			'body'=>'Опыт работы',
			'education'=>'Образование',
			'vuz'=>'ВУЗ',
			'tel'=>'Контактный телефон',
			'email'=>'Электронный адрес', 
		);
	}
}