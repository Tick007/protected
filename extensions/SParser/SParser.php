<?php

class SParser
{
	public $inputCharset = 'utf-8';
	public $outputCharset = 'utf-8';
	
	public $baseUrl = null;
	public $headers = array(
		'User-Agent' => 'SParser',
	);
	
	private $_isConvertedContents = false;
	private $_contents = null;
	private $_info = array();
	
	const PARSE_INNER = 1;
	const PARSE_OUTER = 2;
	
	public function init()
	{
		if(is_null($this->baseUrl))
			return false;
		
		return true;
	}
	
	public function sendGet($url = null, $params = array())
	{
		return $this->request(array_merge($params, array(
			'url' => $url,
			'method' => 'GET',
		)));
	}
	
	public function sendPost($url = null, $params = array())
	{
		return $this->request(array_merge($params, array(
			'url' => $url,
			'method' => 'POST',
		)));
	}

	public function request($params = array())
	{
		$params['url'] = $this->assignUrl($params['url']);
		$params['method'] = !isset($params['method']) ? 'GET' : strtoupper($params['method']);
		$params['header'] = !isset($params['header']) ? true : $params['header'];
		$params['data'] = !isset($params['data']) ? array() : $params['data'];
		
		$headers = array();
		foreach($this->headers as $key => $value)
			$headers[] = "$key: $value";
		
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
		
		$this->_info = curl_getinfo($ch);
		if(false === ($this->_contents = curl_exec($ch)))
		{
			//echo "Ошибка cURL: " . curl_error($ch);
			return false;
		}
		
		curl_close($ch);
		return true;
	}
	
	public function getContents()
	{
		if(!$this->_isConvertedContents && $this->inputCharset != $this->outputCharset)
		{
			$this->_contents = iconv($this->inputCharset, $this->outputCharset, $this->_contents);
			$this->_isConvertedContents = true;
		}

		return $this->_contents;
	}
	
	public function getInfo()
	{
		return $this->_info;
	}
	
	public function assignUrl($url = null)
	{
		if(is_null($url))
		{
			//return trim($this->baseUrl, '/');
			return $this->baseUrl;
		}
		elseif(strpos($url, 'http://') === 0)
		{
			return $url;
		}
		else
		{
			//return trim($this->baseUrl, '/') . '/' . trim($url, '/');
			return trim($this->baseUrl, '/') . '/' . $url;
		}
	}
	
	public function parseInnerTag($tag, $attribute = array(), $context = null, $pattern=NULL)
	{
		return $this->parseTag($tag, $attribute, $context, self::PARSE_INNER, $pattern);
	}
	
	public function parseOuterTag($tag, $attribute = array(), $context = null)
	{
		return $this->parseTag($tag, $attribute, $context, self::PARSE_OUTER);
	}
	
	public function parseTag($tag, $attribute, $context, $type, $pattern = NULL)
	{
		
		
		if(is_null($context))
			$context = $this->getContents();
		
		$attrStr = '([^\n^\r.]*?)';
		if(!empty($attribute))
			$attrStr = $attrStr . key($attribute) . '=[\'"]?' . current($attribute) . $attrStr;
		
		if($pattern==NULL) $pattern = '%<' . $tag . $attrStr . '>(.*?)</' . $tag . '>%is';
		
		//echo htmlspecialchars($pattern).'<br>';
		
		$matches = $this->parse($pattern, $context, $type);
		
		
		if($type == 4  && isset($matches[1]))
			return $matches;
		
		if($type == self::PARSE_INNER && isset($matches[3]))
			return $matches[3];
			
		
		
		if($type == self::PARSE_INNER && isset($matches[2]))
			return $matches[2];
		
		elseif($type == self::PARSE_OUTER && isset($matches[0]))
			return $matches[0];
		
		else return null;
	}
	
	public function parseTagAttribute($tag, $attribute, $condition = array(), $context = null)
	{
		if(is_null($context))
			$context = $this->getContents();
		
		$attrStr = '.*?';
		if(!empty($condition))
			$attrStr = $attrStr . key($condition) . '=[\'"]?' . current($condition) . $attrStr;
		
		$pattern1 = '%<' . $tag . $attrStr . '>%i';
		
		//echo $tag.' - '.$pattern1.'<br>';
		
		$matches1 = $this->parse($pattern1, $context);
		if(!isset($matches1[0]))
			return null;
		
		$pattern2 = '%<' . $tag . '.*?' . $attribute . '=[\'"](.*?)[\'"].*?>%i';
		$matches2 = $this->parse($pattern2, $matches1[0]);
		if(!isset($matches2[1]))
			return null;
		
		return $matches2[1];
	}
	
	public function parse($pattern, $context = null, $type = NULL)
	{
		
		//echo 'tag: '.$tag.'<br>';
		//echo 'pattern: '.htmlspecialchars($pattern).'<br>';
		//echo 'context: '.htmlspecialchars($context).'<br>';
		//echo 'Ищем а:';//
		//print_r($matches);
		//exit();
		
		if(is_null($context))
			$context = $this->getContents();
		
		if($type==4) preg_match_all($pattern, $context, $matches);
		else  preg_match($pattern, $context, $matches);
		return $matches;
	}
	
	public function parseAll($pattern, $context = null)
	{
		if(is_null($context))
			$context = $this->getContents();
		
		preg_match_all($pattern, $context, $matches);
		return $matches;
	}
}