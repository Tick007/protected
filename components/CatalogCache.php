<?php

class CatalogCache extends CWidget
{

	public $products;
	public $groups;
	public $site;
	public $docroot;
	public $categories;
	public $pictures_list;
	public $params;
	public $not_allowed_directories_market;
	
	public $inputCharset = 'utf-8';
	public $outputCharset = 'utf-8';
	public $baseUrl = 'http://gydz.ru/';
	public $headers = array(
		'User-Agent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.11 (KHTML, like Gecko) Chrome/23.0.1271.64 Safari/537.11',
		'Accept-Language' => 'ru-RU,ru;q=0.8,en-US;q=0.6,en;q=0.4',
	);
	public $pauseSeconds = 1;
	
	

	public function __construct($site=NULL, $docroot=NULL)
	{
				
				if($site!=NULL) $this->site = $site;
				if($docroot!=NULL) $this->docroot = $docroot;
				
				//echo 'start';
				return true;
}///////public function _init($site=NULL, $docroot=NULL, $not_allowed_directories=NULL)

public function make_cache($site, $cat_to_recache=NULL) {
	
	
	if($cat_to_recache==NULL){
		
		$connection=Yii::app()->db;
		$query="DELETE  FROM tradexcache ";/////////////////
		$command=$connection->createCommand($query)	;
		$dataReader=$command->query();
	}
	
	
	
	
		$criteria=new CDbCriteria;
		$criteria->condition=" t.alias  IS NOT NULL AND t.alias <>'' AND t.path IS NOT NULL AND TRIM(t.path) <>'' AND t.show_category = 1  AND t.category_name<>'' AND t.parent!=0 ";
		if(is_numeric($cat_to_recache)==true AND $cat_to_recache!=NULL)  $criteria->addCondition("t.category_id = ".$cat_to_recache);
		$criteria->order="t.category_id";
		$categories = Categories::model()->findAll($criteria);
		//$this->groups =CHtml::listData($this->categories,'category_id','alias');
		
		

		if(isset($categories)) {
			$connection =  Yii::app()->db;
			$k=0;
			for($i=0; $i<count($categories); $i++) {
				
				
				$query = "DROP TABLE IF EXISTS  products_".$categories[$i]->category_id;
				$command=$connection->createCommand($query)	;
				$dataReader=$command->query();
				
				
				$query = "DROP TABLE IF EXISTS  ostatki_trigers_".$categories[$i]->category_id;
				$command=$connection->createCommand($query)	;
				$dataReader=$command->query();
				
				
				$query = "DROP TABLE IF EXISTS  characteristics_values_".$categories[$i]->category_id;
				$command=$connection->createCommand($query)	;
				$dataReader=$command->query();
				
				
				if(trim($categories[$i]->path)) {
			
				
				
				//echo $categories[$i]->category_name.' : ';
				//if(trim($categories[$i]->path))print_r(unserialize($categories[$i]->path));
				/*
					if(trim($categories[$i]->path) AND @count(unserialize($categories[$i]->path))>2) $url=urldecode(Yii::app()->createUrl('product/list' ,array('alias'=>$categories[$i]->alias, 'path'=>FHtml::urlpath($categories[$i]->path) ) ) );
					else $url=urldecode(Yii::app()->createUrl('product/list' ,array('alias'=>$categories[$i]->alias ) ) );
					*/
					
					if(trim($categories[$i]->path) AND @count(unserialize($categories[$i]->path))>2)  $url=urldecode( '/catalog/'.FHtml::urlpath($categories[$i]->path).'/'.$categories[$i]->alias.'.html' ) ;
					else $url=urldecode( '/catalog/'.$categories[$i]->alias.'.html' ) ;
					
					
					//echo $categories[$i]->path.' - '.print_r(unserialize($categories[$i]->path));
					//echo  'gen_url = '.$url;
					//echo '
					//';
					
					
					//echo '<br>';
					$params['url']="http://".$site.$url;
					$params['method']='GET';
					$params['header']=$this->headers;
					
					$time1 = microtime(true);
					
					$res_url = $this->request($params);
					if($res_url==true) { 
						$k++;
						echo $params['url'].' - ok  					
						';
					}
					//else echo  $params['url'].'  - fail 
					//';
					$time2 = microtime(true);
					$period = $time2-$time1;
					echo 'Time spent: '.(abs($period)).' sec  
					
					';
					
					


					}
			}
			
			echo 'runned: '.$k.' urls
			';
			
		}

}//////public function make_cache($site) {


public function request($params = array())
	{
		
		echo 	$params['url'].'
		';
		
		//$params['url'] = $params['url'];
		$params['method'] = !isset($params['method']) ? 'GET' : strtoupper($params['method']);
		$params['header'] = !isset($params['header']) ? true : $params['header'];
		$params['data'] = !isset($params['data']) ? array() : $params['data'];
		
		$headers = $params['header'];

		$ch = curl_init();
		
		if($params['header'])
			curl_setopt($ch, CURLOPT_HEADER, 1);
		
		if($params['method'] == 'GET')
		{
			if(!empty($params['data']))
				$params['url'] .= '?' . http_build_query($params['data']);
			
			//curl_setopt($ch, CURLOPT_HTTPGET, 1);
		}
		elseif($params['method'] == 'POST')
		{
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $params['data']);
		}
		
		curl_setopt($ch, CURLOPT_URL, $params['url']);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		
		//print_r($ch);
		
		$info = curl_getinfo($ch);
		if(false === ($contents = curl_exec($ch)))
		{
			//echo "Ошибка cURL: " . curl_error($ch);
			return false;
		}
		
		curl_close($ch);
		return true;
	}


}/////////class MakeXml extends CWidget



