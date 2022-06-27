<?php

/**
 * ContactForm class.
 * ContactForm is the data structure for keeping
 * contact form data. It is used by the 'contact' action of 'SiteController'.
 */
class ContactFormChemimart extends CFormModel
{
	public $name;
	public $company;
	public $email;
	public $body;
	public $verifyCode;
	public $tel;

	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		if(isset(Yii::app()->params['contactformrules'])) return Yii::app()->params['contactformrules'];
		else return array(
			// name, email, subject and body are required
			array('name, email,  body, tel, company, verifyCode', 'required'),
			// email has to be a valid email address
			array('email', 'email'),
			// verifyCode needs to be entered correctly
			//array('tel', 'safe'),
			array('verifyCode', 'captcha', 'allowEmpty'=>!extension_loaded('gd')),
				
		);
	}

	/**
	 * Declares customized attribute labels.
	 * If not declared here, an attribute would have a label that is
	 * the same as its name with the first letter in upper case.
	 */
	public function attributeLabels()
	{
		if(isset(Yii::app()->params['contactformattributes'])) return Yii::app()->params['contactformattributes'];
		else return array(
			'verifyCode'=>'Antispam',
			'name'=>'Your name',
			'company'=>'Company',
			'body'=>'Message',
		    'email'=>'Email adress',
			'tel'=>'Phone',
		);
	}
}