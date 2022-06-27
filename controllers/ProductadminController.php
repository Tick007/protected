<?php

class ProductadminController extends Controller
{
	const PAGE_SIZE=10;

	/**
	 * @var string specifies the default action to be 'list'.
	 */
	public $defaultAction='index';

	/**
	 * @var CActiveRecord the currently loaded data model instance.
	 */
	private $_model;

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'list' and 'show' actions
				'actions'=>array('list','show', 'details',  'index', 'Transfer'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
	
	public function actionTransfer(){
	$connection = Yii::app()->db;
	
	$query = "TRUNCATE TABLE  characteristics_values";
	$command=$connection->createCommand($query);
	$dataReader=$command->query();
	$query = "TRUNCATE TABLE picture_product"; 
	$command=$connection->createCommand($query);
	$dataReader=$command->query();
	$query = "TRUNCATE TABLE  pictures"; 
	$command=$connection->createCommand($query);
	$dataReader=$command->query();
	
	$query = "TRUNCATE TABLE  categories_products"; 
	$command=$connection->createCommand($query);
	$dataReader=$command->query();
	
	$query = "DELETE FROM  products WHERE id > 445"; 
	$command=$connection->createCommand($query);
	$dataReader=$command->query();
	
	
	$allowable_productions=array(9,10,20,36,11,37,38,19);
	$criteria=new CDbCriteria;
	$criteria->order=' t.name';
	//$criteria->condition='t.parent=0 AND  t.id = 370';
	$criteria->condition='t.parent=0 ';
	$models=Categories::model()->with('child_categories', 'goods')->findAll($criteria);
	
	for  ($i=0; $i<count($models);$i++) {
	$atr=array('category_name'=>$models[$i]->name);
	$LEV_ONE_GR = Categoriestradex::model()->findByAttributes($atr);//
	//$P = Term_hierarchy::model()->findByAttributes($atr);//
	if (!isset($LEV_ONE_GR)) {///////////Если не найдено - значит создаем
		$LEV_ONE_GR	= new Categoriestradex;
		$LEV_ONE_GR->category_name = $models[$i]->name;
		$LEV_ONE_GR->parent=0;
		$LEV_ONE_GR->show_category=1;
				try {
						$LEV_ONE_GR->save();	//////////////
						} catch (Exception $e) {
						 echo 'Caught exception: ',  $e->getMessage(), "\n";
						}////
		}
		
		$parent_gr = $LEV_ONE_GR->category_id;
		
	

		
		for ($k=0; $k<count($models[$i]->child_categories); $k++) {
		
		$goods_products=NULL;
		for ($u=0; $u<count($models[$i]->child_categories[$k]->goods[$u]); $u++) 
		{
		$goods_products=($goods_products || in_array($models[$i]->child_categories[$k]->goods[$u]->production,$allowable_productions) );
		} 
		//print_r($goods_products);
		if($goods_products){
		
			//echo "---".$models[$i]->child_categories[$k]->name."<br>";
			$atr=array('category_name'=>$models[$i]->child_categories[$k]->name);
			$LEV_TWO_GR = Categoriestradex::model()->findByAttributes($atr);// 
			if (!isset($LEV_TWO_GR)) {///////////Если не найдено - значит создаем
				$LEV_TWO_GR	= new Categoriestradex;
				$LEV_TWO_GR->category_name = $models[$i]->child_categories[$k]->name;
				$LEV_TWO_GR->parent=$parent_gr;
				$LEV_TWO_GR->show_category=1;
						try {
								$LEV_TWO_GR->save();	//////////////
								} catch (Exception $e) {
								 echo 'Caught exception: ',  $e->getMessage(), "\n";
								}////
				}
				$gr_id = $LEV_TWO_GR->category_id;
				
			for ($t=0; $t<count($models[$i]->child_categories[$k]->goods); $t++) {
			
				//echo "---------".	$models[$i]->child_categories[$k]->goods[$t]->name.' - '.$models[$i]->child_categories[$k]->goods[$t]->production."<br>";
				if (in_array($models[$i]->child_categories[$k]->goods[$t]->production, $allowable_productions)) {///////Дальше, только если какой либо вид ковриков
						$atr=array('product_name'=>$models[$i]->child_categories[$k]->goods[$t]->name, 'product_article'=>$models[$i]->child_categories[$k]->goods[$t]->artikul);
						//echo $models[$i]->child_categories[$k]->goods[$t]->name.'<br>';
						$Product = Products::model()->findByAttributes($atr);// 
						if(isset($Product)) {
							if($Product->category_belong!=$gr_id) {////////////Если category_belong отличается от текущего значит добавляем записи в таблицу свизи, если их там нет				
									$atr=array('group'=>$gr_id, 'product'=>$Product->id);
									$categories_products  = Categories_products ::model()->findByAttributes($atr);// 
									if (!isset($categories_products)) {
											$categories_products = new Categories_products;
											$categories_products->group = $gr_id;
											$categories_products->product = $Product->id;
											try {
											$categories_products->save();	//////////////
											} catch (Exception $e) {
											 echo 'Caught exception: ',  $e->getMessage(), "\n";
											}////
									}///////////if (!isset($categories_products)) {
									//$categories_products	
							}///////////////////if($Product->category_belong!=$gr_id) {///
						}////////////if(isset($Product)) {
						
						if (!isset($Product)) {///////////
								echo $models[$i]->child_categories[$k]->goods[$t]->name.'<br>';
								$Product = new Products;
								$Product->product_name = $models[$i]->child_categories[$k]->goods[$t]->name;
								$Product->product_article = $models[$i]->child_categories[$k]->goods[$t]->artikul;
								$Product->product_price = $models[$i]->child_categories[$k]->goods[$t]->price;
								$Product->category_belong = $gr_id;
								//$Product->product_price = $models[$i]->child_categories[$k]->goods[$t]->price;
								try {
								$Product->save();	//////////////
								} catch (Exception $e) {
								 echo 'Caught exception: ',  $e->getMessage(), "\n";
								}////
							
						}/////////if (!isset($Product)) {///////////
								///////////////////////Разбираем картинкиfotoTextRus
								if(isset($models[$i]->child_categories[$k]->goods[$t]->foto)) 	$this->grabimages($models[$i]->child_categories[$k]->goods[$t]->foto, $models[$i]->child_categories[$k]->goods[$t]->fotoTextRus,  $Product->id);
								if(isset($models[$i]->child_categories[$k]->goods[$t]->images)) 	$this->grabimages($models[$i]->child_categories[$k]->goods[$t]->images, $models[$i]->child_categories[$k]->goods[$t]->imgTextRus,  $Product->id);
								//echo $Product->product_name.'<br>';
								$product_id=$Product->id;
								$Product=NULL;
								//echo 'product_id = '.$product_id.'<br>';
								///////////////////////////////////////Вставляем в характеристики
								$this->savecharval(175, $product_id, 'Новлайн');								
								$this->savecharval(176, $product_id, 'Россия');
								
								if(strstr(strtolower($models[$i]->child_categories[$k]->goods[$t]->name), 'серы')) $color='серый';
								else if(strstr(strtolower($models[$i]->child_categories[$k]->goods[$t]->name), 'черн') OR strstr(strtolower($models[$i]->child_categories[$k]->goods[$t]->name), 'чёрн')) $color='чёрный';
								else if(strstr(strtolower($models[$i]->child_categories[$k]->goods[$t]->name), 'бежев')) $color='бежевый';
								else $color='непонятный';
								$this->savecharval(558, $product_id, $color);
										
								if(strstr($models[$i]->child_categories[$k]->goods[$t]->name, 'полиуретан')) $material='полиуретан';
								else if(strstr($models[$i]->child_categories[$k]->goods[$t]->name, 'резинов')) $material ='резина';
								else if(strstr($models[$i]->child_categories[$k]->goods[$t]->name, 'текстиль')) $material ='текстиль на резине';
								else $material='непонятный';
								$this->savecharval(557, $product_id, $material);
								
								if (in_array($models[$i]->child_categories[$k]->goods[$t]->production, array(9,10,20,36)) ) $type = "в салон";	
								elseif(in_array($models[$i]->child_categories[$k]->goods[$t]->production, array(11,37,38,19)) ) $type = "в багажник";	
								$this->savecharval(559, $product_id, $type);						
						
						
				}
			}
		}//////if($goods_products){
		}/////////for ($k=0; $k<count($models[$i]->
		
	}///////////for  ($i=0; $i<count($models);$i++) {
	
	}

	public function actionIndex(){
	$criteria=new CDbCriteria;
	$criteria->order=' t.name';
	$criteria->condition='t.parent=0 AND  t.id = 370';
	$models=Categories::model()->with('child_categories', 'goods')->findAll($criteria);
	$this->render('index', array('models'=>$models));
	}
	
	
	private function savecharval($id_caract, $product_id, $value){
	//echo "werwerwe<br>";
						$connection = Yii::app()->db;
						/*
						$CHV = new Characteristics_values;
						$CHV->id_caract=$id_caract;
						$CHV->id_product = $product_id;
						$CHV->value=$value;
						try {
								$CHV->save();	//////////////
								} catch (Exception $e) {
								 echo 'Caught exception: ',  $e->getMessage(), "\n";
								}////
								
						*/		
						$query = "INSERT INTO  `yii`.`characteristics_values` (
						`value_id` ,
						`id_caract` ,
						`id_product` ,
						`value`
						)
						VALUES (
						NULL ,  '".$id_caract."',  '".$product_id."',  '".$value."'
						);";
						////////GROUP_CONCAT(  //////////меняет порядок выборки
						//echo $query;
						$command=$connection->createCommand($query);
						$dataReader=$command->query();
	}//////////private function savecharv
	
	private function grabimages($img_arr, $descr_arr, $product_id){
			$images=  explode("^", $img_arr);
			for ($i=0;$i<count($images); $i++ ) {
					if (trim($images[$i]) AND trim($images[$i])!='0') {
					$Picture = new Pictures();
					$qqq =  explode(".",$images[$i]);
					$Picture->ext=$qqq[1];
								try {
								$Picture->save();	//////////////
								} catch (Exception $e) {
								 echo 'Caught exception: ',  $e->getMessage(), "\n";
								}////
					$picture_id=$Picture->id;
					
					@rename($_SERVER['DOCUMENT_ROOT'].'/pictures/add/'.$images[$i], $_SERVER['DOCUMENT_ROOT'].'/pictures/add/'.$picture_id.'.'.$qqq[1]);
					if($i==0) {////////Копируем первую картинку как основную
					@copy($_SERVER['DOCUMENT_ROOT'].'/pictures/add/'.$picture_id.'.'.$qqq[1], $_SERVER['DOCUMENT_ROOT'].'/pictures/img/'.$product_id.'.'.$qqq[1]);
					}
					
					$temp="pictures/add/".$picture_id.'.'.$qqq[1];
					echo "<img src=\"http://".$_SERVER['HTTP_HOST']."/workflow/make_mini.php?create=1&height=100&imgname=$temp\" border=0>";
					
					$picture_product = new Picture_product;
					$picture_product->product = $product_id;
					$picture_product->picture = $picture_id;
								try {
								$picture_product->save();	//////////////
								} catch (Exception $e) {
								 echo 'Caught exception: ',  $e->getMessage(), "\n";
								}////
					}///////if (trim($images[$i]) AND trim($images[$i])!='0')
			}//////////////////////for ($i=0;$i<count($images); $i++ ) {
	}//////////private function grabimages($images, $descriptions, $product_id){
	
}
