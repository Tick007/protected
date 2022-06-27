<?php

/**
 * ContactForm class.
 * ContactForm is the data structure for keeping
 * contact form data. It is used by the 'contact' action of 'SiteController'.
 */
class CooperationForm extends CFormModel
{
	public $name;
	public $familiya;
	public $date;
	public $email;
	public $subject;
	public $body;
	public $expirience;
	public $verifyCode;
	public $education;
	public $educationlist=array(0=>'', 1=>'Менее 1 года', 2=>'От 1 до 3х лет', 3=>'От 3х до 5 лет', 4=>'Более 5 лет');
	public $tel;
	public $city;
	public $people;
	public $company;
	public $busines;
	public $uadres;
	public $fadres;
	public $propertyform;
	

	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			// name, email, subject and body are required
			array('name, familiya, date, education, tel,  email,   city, people, company, busines, propertyform, uadres, fadres', 'required'),
			// email has to be a valid email address
			array('email', 'email'),
			// verifyCode needs to be entered correctly
			//array('verifyCode', 'captcha', 'allowEmpty'=>!extension_loaded('gd')),
			//array('title, h1, children_option_name, search_keywords, description, keywords', 'safe'),
			array('education', 'compare', 'compareValue' => 0, 'operator' => '>',    'message' => 'Укажите опыт торговли '),
			array('body, expirience', 'checkchars')
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
			'name'=>'Контактное лицо для связи с представителем<br>&quot;Fortus&quot;',
			'familiya'=>'ФИО руководителя',
			'date'=>'Дата создания',
			//'subject'=>'Тема',
			'body'=>'Существующие сейчас в структуте организации<br>
			торговые точки и техцентры.',
			'education'=>'Опыт оптовой торговли',
					'tel'=>'Контактный телефон',
			'email'=>'Электронная почта', 
			'city'=>'Город',
			'people'=>'Численность населения',
			'company'=>'Название организации',
			'busines'=>'Направления деятельности организации',
			'expirience'=>'Имеет ли организация опыт работы с автомобильными<br>
противоугонными системами, если да, то какие торговые<br>
марки представлены в ассортименте компании:',
			'uadres'=>'Юридический адрес',
			'fadres'=>'Фактический адрес',
			'propertyform'=>'Форма собственности',
			
		);
	}
}