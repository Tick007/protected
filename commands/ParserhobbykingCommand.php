<?php

class ParserhobbykingCommand extends CConsoleCommand
{
	
	var $store_id=1;
	
	public function actionIndex($command, $url = null, $ignoreErros = false)
	{
		$parser = new HobbykingParser();
		$commandName = 'command' . ucfirst(strtolower(trim($command)));
		
		//echo $commandName."\n\r";
		
			//$parser->init();
			if($commandName == 'commandCatalog')
				$data = $parser->$commandName();
			elseif($commandName=='commandHobbyking')
			{
				
				//ini_set("memory_limit","512M");
				date_default_timezone_set ( 'Europe/Moscow' );
				
				$parser->init();
				
				$criteria=new CDbCriteria;
				$criteria->condition = " t.parse_url IS NOT NULL ";
				$models=Products::model()->findAll($criteria);
				
				for($i=0, $c=count($models); $i<$c; $i++)
				{
					
					$TRIGER = 	$this->find_ostatki_triger($models[$i]->id, $this->store_id);
					
					
					//echo $models[$i]->parse_url."\n\r";
					 //$data = $parser->commandItem($models[$i]->parse_url, false, false);
					 
					 $pat = '/__([0-9]{1,10})__/';
					preg_match_all($pat,$models[$i]-> parse_url,$matches);
					if(isset($matches) && isset($matches[1]) && isset($matches[1][0]))  $data = $parser->commandItemId($matches[1][0]);
					 
					
					 //print_r($data);
					 //echo "\n\r";
					 if(isset($data['result']['err']) && $data['result']['err']==true){
						 echo $url." ".$data['result']['message'];
						 echo "\n\r";
					}
					else{
					
					 if(isset($data['result']['price']))$TRIGER->store_price = $data['result']['price'];
								if(isset($data['result']['kolich']))$TRIGER->quantity = $data['result']['kolich'];
								if(isset($data['result']['cur'])){
									$currency = Currencies::model()->findByAttributes(array('currency_code'=>strtoupper($data['result']['cur'])));
									if(isset($currency) && $currency!=NULL) {
										$TRIGER->currency = $currency->currency_id;
									}
								}
							$TRIGER->save();
					 
					}
					 sleep(rand(1,100)/100);
				}
				
				//echo count($models);
				
			}
			elseif(!is_null($url))
				$data = $parser->$commandName($url);
			else
				echo "Не передан аргумент \"url\".\n";
			
			if(isset($data['error']) && $data['error'])
				echo $data['error'] . "\n";
/*
		else 
		{
			echo "Неверно задана команда.\n";
		}
		*/
	}
	
	public function find_ostatki_triger( $product_id, $store_id)
	{
		$TRIGER = 	Ostatki_trigers::model()->findByAttributes(array('tovar'=>$product_id, 'store'=>$store_id));
		if($TRIGER == NULL){
			$TRIGER = new Ostatki_trigers;
			$TRIGER->tovar = $product_id;
			$TRIGER->store = $store_id;
			$TRIGER->save();
		}
		return $TRIGER;
	
	}
	
}