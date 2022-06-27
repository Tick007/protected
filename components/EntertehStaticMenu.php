<?php

class EntertehStaticMenu  extends CWidget {

	public $model;

	function __construct($title=''){
			//$this->title = $title;
			
	}
	
	function setCurrent($model){
		$this->model=$model;
	}
	
	function Draw() {
		
		
		/*
		////////////////////Выбираем статьи
		$criteria=new CDbCriteria;
		$criteria->order = " t.creation_date ASC ";
		$criteria->condition=" section=:section AND active=1 AND t.alais<>'' ";
		$criteria->params=array(':section'=>2);/////////////1-новости
		$models=Page::model()->findAll($criteria)
		*/
		
		$criteria=new CDbCriteria;
		$criteria->condition = " t.active =1 AND pages.active =1 ";
		$criteria->order=" t.sorting, pages.sort, pages.creation_date"; 
		$rubrics = Page_rubrics::model()->with('pages')->findAll($criteria);	
		
		
		if(isset($rubrics) AND empty($rubrics)==false) $this->render('enterteh/menu/static', array('rubrics'=>$rubrics));
	}

	public function checkactive( $a, $b) {
		if (trim(strtolower($a))==trim(strtolower($b))) echo ' class="active" ';
	}

}
?>