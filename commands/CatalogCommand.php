<?php

class CatalogCommand extends CConsoleCommand
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
		else{
			ini_set( 'date.timezone', 'Europe/Moscow' );
				ini_set("memory_limit","768M");
				//echo 'path = ';
				$docroot = str_replace('/protected', '', Yii::app()->basePath);
				$docroot = str_replace('\protected', '', $docroot);
				
				//echo $docroot;
				//exit();
				Yii::import('CatalogCache');
				echo '------------------------BEGIN OBHOD-------------------------------'.date('Y-M-d H:i:s', microtime(true))."\n";

				//$yml =  new MakeYandexxml($site, $docroot);
				//$yml->makefile();
				
				$Cache = new CatalogCache();
				$Cache->make_cache($site);
				
				echo '------------------------FINISHED OBHOD-------------------------------'.date('Y-M-d H:i:s', microtime(true))."\n";
				exit();
		}//////elseif($command=='makeyandexxml') {
			
	}
}