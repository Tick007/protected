<?php

class ParserCommand extends CConsoleCommand
{
	public function actionIndex($command, $url = null, $ignoreErros = false)
	{
		$parser = new PositronicaParser();
		$commandName = 'command' . ucfirst(strtolower(trim($command)));
		
		if(method_exists($parser, $commandName))
		{
			$parser->init();
			if($commandName == 'commandCatalog')
				$data = $parser->$commandName();
			elseif(!is_null($url))
				$data = $parser->$commandName($url);
			else
				echo "Не передан аргумент \"url\".\n";
			
			if(isset($data['error']) && $data['error'])
				echo $data['error'] . "\n";
		}
		else 
		{
			echo "Неверно задана команда.\n";
		}
	}
}