<?php

class SitemapController extends Controller
{
	/**
	 * Declares class-based actions.
	 */
	 private $CAT;
	 private $cat_child_ids;/////////////////Массив для хранения идентификаторов деток
	 private $PROD;
	 var $region; ////////////////////глобальный регион пользователя из куки
	 var $pageKeywords;
	 var $pageDescription;
	 var $PAGE_SIZE=15;
	 var $SEARCH_CACH_EXPIRE=3600; ////sec
	 var $levels; ///////////////Дерево групп особым алгоритмом
	  var $all_cats;
	  var $tree; ///////////////Дерево групп особым алгоритмом
	   var $levels_region; ///////////////Дерево групп особым алгоритмом
	  var $tree_region; ///////////////Дерево групп особым алгоритмом
	  
	// var $product_expire =  30;//////////////////////Время устаревания товара ///////////Объявил в конфиге
	 
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}


		public function filters()
	{
		return array(
			//'accessControl', // perform access control for CRUD operations
		//	'CheckGroupExist +category, compare ',//////////////////Проверка существования категории
		//	'CheckProductExist +product',//////////////////Проверка существования товара
		//	'CookieRegion +sitemap', //////////////////////Проверка региона, сохранения в куки

		);
	}

		public function accessRules()
	{
		return array(

			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('registertwo', 'registertree', 'setproductbkm'),
				'users'=>array('@'),
			),
		);
	}



public function actionSitemap(){
		
		//$klad = Ma_kladr::model()->findAll();
		//$kladr=CHtml::listData($klad,'kladr_id','name');
		
		
		$criteria=new CDbCriteria;
		$criteria->condition = " t.product_visible = 1 ";
		if (isset(Yii::app()->params['chernovik_cat_id']))  {
				$criteria->addCondition(" t.category_belong != :chernovik");
				$criteria->params=array(':chernovik'=>Yii::app()->params['chernovik_cat_id']);
		}
		$products=Products::model()->findAll($criteria);
		
		
		$criteria=new CDbCriteria;
		$criteria->addCondition("   t.alias  IS NOT NULL AND t.alias <>'' ");
		$merchants=Contr_agents::model()->findAll($criteria);
		if (isset($merchants)) for ($k=0; $k<count($merchants); $k++)  $merchant_list[$merchants[$k]->id]=array('name'=>$merchants[$k]->name, 'alias'=>$merchants[$k]->alias);
		
		$criteria=new CDbCriteria;
		$criteria->condition=" t.alias  IS NOT NULL AND t.alias <>'' AND t.path IS NOT NULL AND TRIM(t.path) <>'' AND t.show_category = 1   ";
		$criteria->order="t.category_id";
		$categories = Categories::model()->findAll($criteria);
		$groups =CHtml::listData($categories,'category_id','alias');
		/*
		$groups =CHtml::listData($categories,'category_name','path');
		echo '<pre>';
		print_r($groups);
		echo '</pre>';
		exit();
		*/
		
		
		$this->renderPartial('sitemap', array('products'=>@$products, 'merchants'=>@$merchants, 'merchant_list'=>@$merchant_list, 'groups'=>@$groups, 'kladr'=>@$kladr, 'categories'=>@$categories));
}//actionSitemap(){
	
}////////////class