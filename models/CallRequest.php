<?php

class CallRequest extends CFormModel
{
    public $name;
    public $phone;
    public $timeToCall;
  
    public function rules()
    {
        return array(
            array('name, phone', 'required'),
            array('timeToCall', 'safe'),
			array('phone', 'match', 'pattern'=>'[\+]\d{1}[\(]\d{3}[\)]\d{3}[\-]\d{4}'),
			//В формате   +99(99)9999-9999:
        );
    }
  
    public function attributeLabels()
    {
        return array(
            'name'=> 'Ваше имя',
            'phone'=> 'Телефон',
            'timeToCall'=>'Время звонка',
        );
    }
}

?>