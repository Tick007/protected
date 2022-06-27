<?php
class DiapoSlider extends CWidget
{
	
	public $models;

	function __construct(){
		
		$criteria=new CDbCriteria;
		$criteria->select=array( 't.*',  'picture_product.picture AS icon' , 'picture_product.ext AS ext');
			//$criteria->together = true;
			$criteria->join ="
			LEFT JOIN ( SELECT product, picture, pictures.ext as ext FROM picture_product  JOIN pictures ON pictures.id= picture_product.picture  WHERE is_vitrina=1 ) picture_product ON picture_product.product = t.id  ";
		$criteria->order = 't.product_sellout_sort';
		$criteria->condition = " t.product_vitrina = 1 ";
		//$criteria->params = array(':SearchValue' => '%'.$search_field.'%'  );
		
		$this->models = Products::model()->findAll($criteria);//
	}

	public function run($params=array())
	{
		
		if(isset($this->models))  $this->render('diapo', $params );
	}
	
	public function run2($params=array())
	{
		
		if(isset($this->models))  $this->render('okodesign/diapo', $params );
	}

	
}