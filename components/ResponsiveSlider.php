<?php
class ResponsiveSlider extends CWidget 
{
	
	public $models;
	private $view;

	function __construct($view){
		$this->view = $view;		

	}

	public function run($params=array())
	{
		
		 $this->render(Yii::app()->theme->name.'/'.$this->view, $params );
	}
	


	
}