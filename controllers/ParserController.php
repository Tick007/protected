<?php

class ParserController extends Controller
{
	public $layout = 'admin';
	public $web_sites =  array(0=>'Выбери сайт',1=>'Positronica', 2=>'Armchairracer (слотовые машинки)');
	
	
	
	public function accessRules()
	{
		return array(
			array('allow',
				'actions'=>array('details'),
				'users'=>array('*'),
			),
			array('allow',
				'actions'=>array('index'),
				'users'=>array('@'),
			),
			array('deny',
				'users'=>array('*'),
			),
		);
	}
	
	public function actionIndex()
	{
		$parser_site = Yii::app()->getRequest()->getParam('parser_site');
		$make_record = Yii::app()->getRequest()->getParam('make_record', NULL);
		
		if($url = Yii::app()->request->getPost('url') AND isset($parser_site) AND $parser_site>0)
		{
			if($parser_site==1) $parser = new PositronicaParser();
			elseif($parser_site==2) $parser = new ArmchairracerParser();
			$parser->init();
			$data = $parser->commandItem($url, false, $make_record);
			
			if($parser->getParser())
				$info = $parser->getParser()->getInfo();
			
			$this->render('index', array(
				'result' => isset($data['result']) ? $data['result'] : array(),
				'error' => isset($data['error']) ? $data['error'] : null,
				'parsedUrl' => isset($info['url']) ? $info['url'] : Yii::app()->request->getPost('url'),
			));
		}
		else
		{
			$this->render('index');
		}
	}
}