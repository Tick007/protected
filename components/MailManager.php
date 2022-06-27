<?php

class MailManager extends CComponent
{
	public function send($params)
	{
		$headers  = 'From: ' . $params['headers']['from'] . "\r\n" ;
		$headers .= 'Content-type: text/html; charset=windows-1251' . "\r\n";
		$body     = iconv( "UTF-8", "CP1251", $params['body']);
		$subject  = iconv( "UTF-8", "CP1251", $params['subject']);

		foreach($params['addresses'] as $address)
			mail($address, $subject, $body, $headers);
	}
	
	public function sendErrorAlarm($subject, $body)
	{
		$addresses = array
		(
			Yii::app()->params['adminEmail']
		);
		
		$this->send(array
		(
			'headers'   => array('from' => Yii::app()->params['supportEmail']),
			'subject'   => $subject,
			'body'      => $body,
			'addresses' => $addresses,
		));
	}
}