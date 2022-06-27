<?php

class SParserController
{
	public $isConsole = true;
	public $isWeb = false;
	
	public $inputCharset = 'utf-8';
	public $outputCharset = 'utf-8';
	public $baseUrl = null;
	public $headers = array();
	public $pauseSeconds = 1;
	
	private $_args = array();
	private $_output = null;
	
	public function init()
	{
		$this->initApplicationType();
		//$this->setArgs();
		$this->setHeader();
	}
	
	public function initApplicationType()
	{
		$this->isConsole = (php_sapi_name() == 'cli');
		$this->isWeb = !$this->isConsole;
	}
	
	public function setArgs()
	{
		if($this->isConsole) 
		{
			foreach($_SERVER['argv'] as $i => $arg)
			{
				if($i > 0)
				{
					$kv = explode('=', $arg);
					$this->setArg($kv[0], (isset($kv[1]) ? $kv[1] : null));
				}
			}
		}
		else
		{
			parse_str($_SERVER['QUERY_STRING'], $this->_args);
		}
	}
	
	public function getArgs()
	{
		return $this->_args;
	}
	
	public function setArg($index, $value)
	{
		$this->_args[$index] = $value;
	}
	
	public function getArg($index)
	{
		return isset($this->_args[$index]) ? $this->_args[$index] : null;
	}
	
	public function getParser()
	{
		require_once('SParser.php');
		$parser = new SParser();
		$parser->headers = array_merge($parser->headers, $this->headers);
		$parser->baseUrl = $this->baseUrl;
		$parser->inputCharset = strtolower($this->inputCharset);
		$parser->outputCharset = strtolower($this->outputCharset);
		
		if(!$parser->init())
			$this->error('Не задан базовый url.');
		
		return $parser;
	}
	
	public function getOutput()
	{
		return $this->_output;
	}
	
	public function setHeader()
	{
		header("Content-Type:text/html; charset={$this->outputCharset}");
	}
	
	public function convert($str)
	{
		return iconv($this->inputCharset, $this->outputCharset, $str);
	}
	
	public function pause()
	{
		sleep($this->pauseSeconds);
	}
	
	public function error($msg)
	{
		$this->show("Системная ошибка ::\t$msg");
		//exit();
	}
	
	public function show($str, $html = false)
	{
		if($this->isConsole) 
		{
			echo "$str\n";
		}
		else 
		{
			echo ($html ? $str : htmlspecialchars($str)) . "<br />";
		}
	}
	
	public function dump($array, $html = false)
	{
		if($this->isConsole) 
		{
			print_r($array);
			echo "\n";
		} 
		else
		{
			echo "<pre>";
			if($html)
			{
				print_r($array);
			}
			else
			{
				ob_start();
					print_r($array);
				echo htmlspecialchars(ob_get_clean());
			}
			echo "</pre>";
		}
	}
	
	public function clean($str)
	{
		return trim(strip_tags($str));
	}
	
	public function hardClean($str)
	{
		return preg_replace("%[\n\r\t\s]+%i", " ", trim(strip_tags($str)));
	}
	
	public function copyFile($from, $to)
	{
		$contents = file_get_contents($from);
		if($contents)
			return file_put_contents($to, $contents);
		else
			return false;
	}
}