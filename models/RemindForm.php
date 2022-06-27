<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class RemindForm extends CFormModel 
{
	
	public $verifyCode;
	public $email;
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
			
			array('email', 'required'),
			array('verifyCode', 'captcha', 'allowEmpty'=>!extension_loaded('gd')),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'verifyCode'=>'Text from the picture',
			'email'=>'Login or email',
		);
	}

	/**
	 * Authenticates the password.
	 * This is the 'authenticate' validator as declared in rules().
	 */
	
	public function remindpassword($user){
			if (trim($user->client_password)!='') {
					//$new_pass = $user->client_password;
					$new_pass = FHtml::generate_password(6);
					$user->client_password = $new_pass;
					try {
					   $user->save();
        			} catch (Exception $e) {
        			    // echo 'Ошибка создание нового контагента из списка управления. ',  $e->getMessage(), "\n";
        			    $result = 'New password save error';
        			    return $result ;
        			    exit();
        			}/////
					
			}///////if (trim($user->client_password)!='') {
			else if (trim($user->client_password)=='' OR $user->client_password==NULL) {
					$new_pass = FHtml::generate_password(6);
					$user->client_password = $new_pass;
					try {
						  $user->save();
						} catch (Exception $e) {
						// echo 'Ошибка создание нового контагента из списка управления. ',  $e->getMessage(), "\n";
								$result = 'New password save error';
								return $result ;
								exit();
						}/////
			}/////////////else if (trim($user->client_password)=='' OR $user->client_password==NULL) {
			
			if(is_array(Yii::app()->params['adminEmail'])==false) $admin_mail ==(array)Yii::app()->params['adminEmail'];
			else $admin_mail = Yii::app()->params['adminEmail'];
			
			$msg_body = 	"Hello ".$user->first_name." ".$user->second_name.". Someone has requested password recovery for your account.
<br> Your login is: ".$user->login."<br>Your new password is: ".$new_pass ;
			
			foreach ($admin_mail as $amail) {
			
			}
			$headers = 'From: '.$amail. "\r\n" ;
			$headers.='Content-type: text/html; charset=UTF-8' . "\r\n";
			if (isset($user->client_email)==false OR trim($user->client_email)=='' OR $user->client_email == NULL) {
				return 2 ;
				exit();
			}
			elseif (mail($user->client_email,  'Password reminder '.$_SERVER['HTTP_HOST'], $msg_body, $headers)); 
			$result=1;
			Yii::app()->user->setFlash('remind','New password was sent, check your email box.');
			return 1;
	}///////////private function remindpassword($user){
	
}
