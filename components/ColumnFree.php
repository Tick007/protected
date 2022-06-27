<?php

class ColumnFree extends CWidget
{
	
 	private $view;
	
	function __construct($view){
		$this->view = $view;
			
	}

	public function run()
	{
		$this->render(Yii::app()->theme->name.'/'.$this->view);
	}


	

		
}
?>