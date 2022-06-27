<?php
class Oneclick  extends CWidget {
	
	var $use_country;
	var $use_region;
	var $kladr_belongs;
	var $select_name;
	var $select_id;
	var $default_country_id = 3159;//////////////Россия
	var $countries_num;
	var $cities_list;
	var $update_metro;
	var $scriptfunc; ////////////Имя функции которую нужно выполнить при изменении города
	var $use_kladr;////////////использовать кладр а не id

	function __construct($view, $params = NULL){
			
				if(isset($params) AND isset($params['product_id'])) $product_id = $params['product_id'];
				else $product_id = NULL;
				if(isset($params) AND isset($params['url'])) $url = $params['url'];
				else $url=NULL;
				if(isset($params) AND isset($params['num'])) $num = $params['num'];
				else $num=NULL;
				if(isset($params) AND isset($params['name'])) $name = $params['name'];
				else $name=NULL;

			$params = array('product_id'=>$product_id, 'url'=>$url, 'num'=>$num, 'name'=>$name);
			//print_r($params);
		$this->render('oneclick/'.Yii::app()->theme->name.'/'.$view, $params);

	}//////////////function __construct($menu_id){

	
}///////////class Vitrina {
?>


