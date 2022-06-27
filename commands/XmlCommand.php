<?php

class XmlCommand extends CConsoleCommand
{
	public function actionIndex($command, $site)
	{
		
		if($command=='phpinfo') {
			echo phpinfo();
			
		}
		if($command=='testmem') {
			echo ini_get("memory_limit")."\n";
		ini_set("memory_limit","256M");
		echo ini_get("memory_limit")."\n";
		}
		elseif($command=='makeyandexxml') {
			ini_set( 'date.timezone', 'Europe/Moscow' );
				ini_set("memory_limit","768M");
				//echo 'path = ';
				$docroot = str_replace('/protected', '', Yii::app()->basePath);
				$docroot = str_replace('\protected', '', $docroot);
				
				//echo $docroot;
				//exit();
				Yii::import('MakeXml');
				Yii::import('MakeYandexxml');
				echo '------------------------BEGIN-------------------------------'."\n";
				$yml =  new MakeYandexxml($site, $docroot);
				$yml->makefile();
				echo '------------------------FINISHED-------------------------------'."\n";
				exit();
		}//////elseif($command=='makeyandexxml') {
			elseif($command=='makeyandexxml_vm') {
			ini_set( 'date.timezone', 'Europe/Moscow' );
				ini_set("memory_limit","768M");
				//echo 'path = ';
				$docroot = str_replace('/protected', '', Yii::app()->basePath);
				$docroot = str_replace('\protected', '', $docroot);
				
				//echo $docroot;
				//exit();
				Yii::import('MakeXml');
				Yii::import('MakeYandexxml_vm');
				echo '------------------------BEGIN VM-------------------------------'."\n";
				$yml =  new MakeYandexxml_vm($site, $docroot);
				$yml->makefile();
				echo '------------------------FINISHED VM-------------------------------'."\n";
				exit();
		}//////elseif($command=='makeyandexxml') {
	}
}