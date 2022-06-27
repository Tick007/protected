<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
    
    const ERROR_ACCOUNT_DISABLED=3;
	/**
	 * Authenticates a user.
	 * The example implementation makes sure if the username and password
	 * are both 'demo'.
	 * In practical applications, this should be changed to authenticate
	 * against some persistent user identity storage (e.g. database).
	 * @return boolean whether authentication succeeds.
	 */
	 /*
	public function authenticate()
	{
		$users=array(
			// username => password
			'demo'=>'demo',
			'admin'=>'admin',
		);
		if(!isset($users[$this->username]))
			$this->errorCode=self::ERROR_USERNAME_INVALID;
		else if($users[$this->username]!==$this->password)
			$this->errorCode=self::ERROR_PASSWORD_INVALID;
		else
			$this->errorCode=self::ERROR_NONE;
		return !$this->errorCode;
	}
	*/
	private $_id;
    public function authenticate()
    {
        
        
        $record=User::model()->with('authassignment')->findByAttributes(array('login'=>$this->username));
        
       // print_r($record->getAttributes());
        
        //var_dump($this->password);
       // var_dump($record->client_password);
        
        //exit();
        
        if($record===null) {
            $this->errorCode=self::ERROR_USERNAME_INVALID;
        //else if($record->password!==md5($this->password))
        }
        else{
            if($record->client_password!=$this->password) {
                $this->errorCode=self::ERROR_PASSWORD_INVALID;
            }
            /*
            else if ($record->status==0){
                $this->errorCode=self::ERROR_ACCOUNT_DISABLED;
                }
                */
            else{
                    $this->_id=$record->id;
                    //$this->setState('title', $record->title);
        			$this->setState('title', $record->first_name.' '.$record->second_name);
        			$this->setState('approved', $record->status);
        			if(isset($record->enter_redirect) && $record->enter_redirect!=null){
        			    $this->setState('enterUrl', $record->enter_redirect);
        			}
        			if(isset($record->authassignment))$this->setState('role', $record->authassignment->itemname);////////////////////����������� Yii::app()->user �������� ���� ��������� ������������
        			$this->errorCode=self::ERROR_NONE;
                }
            }
            
            //print_r(!$this->errorCode);

        return !$this->errorCode;
    }
 
    public function getId()
    {
        return $this->_id;
    }
	
}