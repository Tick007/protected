<?php

class MakeXml extends CWidget
{

	public $products;
	public $groups;
	public $site;
	public $docroot;
	public $categories;
	public $pictures_list;
	public $params;
	public $not_allowed_directories_market;
	
	

	public function __construct($site=NULL, $docroot=NULL)
	{
				
				if($site!=NULL) $this->site = $site;
				if($docroot!=NULL) $this->docroot = $docroot;
				
				//echo 'start';
				return true;
}///////public function _init($site=NULL, $docroot=NULL, $not_allowed_directories=NULL)

public function make_query($not_allowed_directories=NULL, $only_allowed_directories=NULL, $quantity_limit=NULL) {
	
	
		$criteria=new CDbCriteria;
		$criteria->condition = " picture_product.is_main=1";
		$picture_models = Pictures::model()->with('picture_product')->findAll($criteria);
		if (isset($picture_models)) {
			for($i=0; $i<count($picture_models);$i++) $this->pictures_list[$picture_models[$i]->picture_product[0]->product]=array('pict_id'=>$picture_models[$i]->id, 'ext'=>$picture_models[$i]->ext);
			unset($picture_models);
		}
		
	
		$criteria=new CDbCriteria;
		//$criteria->select=array( 't.*',  'picture_product.picture AS icon' , 'picture_product.ext AS ext');
		$criteria->condition = " t.product_visible = 1 AND  product_price>300 AND number_in_store>0";
		if($quantity_limit!=NULL) $criteria->addCondition("number_in_store>".($quantity_limit-1));
		if(trim($not_allowed_directories) AND $not_allowed_directories!=NULL) $criteria->addCondition("t.category_belong NOT IN (".$not_allowed_directories.")");
		if(trim($only_allowed_directories) AND $only_allowed_directories!=NULL) $criteria->addCondition("t.category_belong  IN (".$only_allowed_directories.")");
	/*	$criteria->join ="
			LEFT JOIN ( SELECT product, picture, pictures.ext as ext FROM picture_product  JOIN pictures ON pictures.id= picture_product.picture  WHERE is_main=1 ) picture_product ON picture_product.product = t.id  "; 
			*/
		$this->products=Products::model()->findAll($criteria);
		

		
		
		$criteria=new CDbCriteria;
		$criteria->condition=" t.alias  IS NOT NULL AND t.alias <>'' AND t.path IS NOT NULL AND TRIM(t.path) <>'' AND t.show_category = 1  AND t.category_name<>'' ";
		if(trim($not_allowed_directories) AND $not_allowed_directories!=NULL) $criteria->addCondition("t.category_id NOT IN (".$not_allowed_directories.")");
		$criteria->order="t.category_id";
		$this->categories = Categories::model()->findAll($criteria);
		$this->groups =CHtml::listData($this->categories,'category_id','alias');
		


}


}/////////class MakeXml extends CWidget



