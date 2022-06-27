<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	//public $layout='//layouts/column1';
	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu=array();
	var $pageDescription;
	var $pageKeywords;
	var $pageTitle;
	var $leftpanel;
	var $rightpanel;
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs=array();
	
	public function filterCheckPathSuffix($filterChain){/////////////
				//echo '<br>ewrwer';
					$request = Yii::app()->getRequest();
					$url = $request->getUrl();
					//echo $url.'<br>';
					$last= mb_substr($url, -1, 1);
					if ($last=='/') {
							$this->redirect(mb_substr($url, 0, mb_strlen($url)-1), true, 301);
							exit();
					}
			$filterChain->run();
	}/////////public function filterCheckPathSuffix($filterChain){/////////////
	
	public function filterUserLastUrl($filterChain) { ////////////Сохраняем ссылку на последнюю страницу где был пользователь
			$request = Yii::app()->getRequest();
			$url = $request->getUrl();
			
			//print_r(Yii::app()->user);
			//exit();
			Yii::app()->user->setReturnUrl($url);
			$filterChain->run();
	}/////////ublic function filterUserLastUrl($filterChain) { ////////
	
	public function filterReadKladr($filterChain){ ////////////Просто смотрим текущий кладр
			$cookie=Yii::app()->request->cookies['region'];
				//print_r($cookie);	
					if (isset($cookie) AND trim($cookie->value)<>'') {
							$this->region=$cookie->value;
							$this->global_kladr = Ma_kladr::model()->findByPk($this->region);		
							//echo $this->region_word;
					}///////if (isset($cookie) AND trim($cookie->value)<>'') {
					else 	if(isset(Yii::app()->params['default_kladr_id'])) {
						$this->global_kladr = Ma_kladr::model()->findByPk(Yii::app()->params['default_kladr_id']);	
					}/////////////
			$filterChain->run();		
	}
	
	 public function Add_To_Cart() {
		 
		 //print_r($_GET);
		 //print_r($_POST);
		// exit();
		 
		 
		$add_to_basket = Yii::app()->getRequest()->getParam('add_to_basket');////сам фильтр
		$num_to_basket = Yii::app()->getRequest()->getParam('num_to_basket', 1);////сам фильтр 
		 
		 
	 		if (isset($add_to_basket) AND is_numeric($add_to_basket)) {//////////////////Добавление в корзину
			
					$tovar_id=intval(trim($add_to_basket));
					$MyBasket = new MyShoppingCart($tovar_id, $num_to_basket  );
			}
			
			$cookie=Yii::app()->request->cookies['YiiCart'];
			if (isset($cookie)){
			 $value=$cookie->value;
				//echo "Сейчас установленные куки ".$value."/<br>";
	 		}
			//else echo "Нет куки<br>";
		}
		
		public function actions()
		{
			return array(
				// captcha action renders the CAPTCHA image displayed on the contact page
				'captcha'=>array(
					'class'=>'CCaptchaAction',
					'backColor'=>0xFFFFFF, //цвет фона капчи
					'testLimit'=>2, //сколько раз капча не меняется
					'transparent'=>false,
					'foreColor'=>0x7a7a7a, //цвет символов
				//	'width'=>'150px',
				 	'height'=>'50px',
				),
			);
		}
		
		
		public function CalculateCartContents(){ //////Считаем содержимое корзины
				 $cook=Yii::app()->request->cookies['YiiCart'];
				if (isset($cook)) {
				$cookie = $cook->value;
					if (isset($cookie)) {/////////////////////////////
					//print_r($cookie);
							$goods=explode('#', $cookie);
							foreach($goods as $product) {
								$sum_num=explode(":", $product);
								if(isset($sum_num[0]) AND isset($sum_num[1])) {
									$products_arr['ids'][]=$sum_num[0];
									$products_arr['num'][$sum_num[0]]=$sum_num[1];
								}
								
								//print_r($sum_num);
							}
					//print_r($products_arr);	
					
					if (isset($products_arr['ids'])) {
							$criteria=new CDbCriteria;
							//$criteria->order = 't.sort_category';
							//print_r($products_arr['ids']);
							$criteria->condition = " t.id IN (".implode(',', $products_arr['ids']).")";
							//$criteria->params=array(':root'=>Yii::app()->params['main_tree_root']);
							$models = Products::model()->findAll($criteria);//
							if (isset($models)) {
									$sum=0;
									$num_of_products=0;
									for($k=0; $k<count($models); $k++) {
										//echo $models[$k]->id;
										$sum=$sum+round($models[$k]->product_price*$products_arr['num'][$models[$k]->id], 2);
										$num_of_products = $num_of_products+$products_arr['num'][$models[$k]->id];
									}
									//echo $sum;
									return array($sum, $num_of_products);
							}/////if (isset($models)) {
					}//////if (isset($products_arr['ids'])) {
							
					}/////if (isset($cookie)) {/////////////////
					
					
					
				}/////////if (isset($cook)) {
		}//////public function CalculateCartContents(){
	
	
		public function filterEmptyFilter($filterChain){
			$filterChain->run();
		}
	
}