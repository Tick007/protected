<?php

class SearchController extends Controller
{
	/**
	 * Declares class-based actions.
	 */
	 private $CAT;
	 private $cat_child_ids;/////////////////Массив для хранения идентификаторов деток
	 private $PROD;
	 var $pageKeywords;
	 var $pageDescription;
	 const PAGE_SIZE=40;
	 private $SEARCH_CACH_EXPIRE=3600; ////sec
	 var $levels; ///////////////Дерево групп особым алгоритмом
	 var $tree; ///////////////Дерево групп особым алгоритмом
	 var $levels_region; ///////////////Дерево групп особым алгоритмом
	 var $tree_region; ///////////////Дерево групп особым алгоритмом
	 
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
			'accessControl', // perform access control for CRUD operations
			'CheckGroupExist +services, compare, product',//////////////////Проверка существования категории
			'CheckProductExist +product',//////////////////Проверка существования товара

		);
	}


	public function  filterCheckGroupExist($filterChain)	{///////Если передан идентификатор контрагента, то проверяем пользователя ли это контрагент
	
			$cat_alias = Yii::app()->getRequest()->getParam('alias', NULL);	
			$cat_alias = trim(htmlspecialchars($cat_alias));
			//echo 'ca = '.$cat_alias.'<br>';
			$criteria=new CDbCriteria;
			$criteria->condition = "  (child_categories.show_category = 1 OR child_categories.category_id IS NULL)";
			//$criteria->params = array(':alias'=>$cat_alias);
			//$CAT = Categories::model()->with('child_categories')->findByAttributes($criteria);
			$CAT = Categories::model()->with('child_categories')->findByAttributes(array('alias'=>$cat_alias), $criteria);
			if ($CAT == NULL)  throw new CHttpException(404,'Категоря не найдена');
			else {	
							$this->CAT = $CAT;
							for($k=0; $k<count($CAT->child_categories); $k++) $this->cat_child_ids[]=$CAT->child_categories[$k]->category_id;/////////////////Получили список идшников детей данной группы 
							$filterChain->run();
					}

					//
		}
		
		public function  filterCheckProductExist($filterChain)	{////	
		
			$product_id = Yii::app()->getRequest()->getParam('id', NULL);	
			if (is_numeric($product_id)==false) CHttpException(404,'Карточка не существует');
			else {/////////else1
				//$PRODUCT = Products::model()->with('contr_agent', 'kladr', 'char_val')->findByPk($product_id);
				$PRODUCT = Products::model()->with('contr_agent', 'kladr')->findByPk($product_id);
								if ($PRODUCT==NULL) throw new CHttpException(404,'Карточка не существует');
								else {///////////if3
												$this->PROD = $PRODUCT;
												$filterChain->run();
										}////////////else {///////////if3
					}///////////////////else1
					//
		}///////////////public function  filterCheckProductExist($filterChain)	{///////Если пе
		
	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	 
	
	 
	public function actionIndex()////////////////////Глобальный поиск
	{
		$sort = Yii::app()->getRequest()->getParam('sort', NULL);
		$search = Yii::app()->getRequest()->getParam('search', NULL);	
		
		if ($search==NULL) {
			$this->redirect(Yii::app()->request->baseUrl."/");
			exit;
			}	
		
		$search_words=explode(' ', $search);
		//Yii::app()->cache->flush();
		$rows =Yii::app()->cache->get(trim($search.'products'));
		if($rows===false) {
						
						$rows=Products::findProductsBywords($search, $this->SEARCH_CACH_EXPIRE );
							
					}///////if($products===false) {
					else {
							//$pages = Yii::app()->cache->get(trim($search.'pages'));
					}
					
								
				if ($rows!=NULL) {
						////////////////////Теперь делаем выборка по товарам
						$criteria=new CDbCriteria;
						$criteria->condition = " products.id IN (".implode(',',$rows).") AND parent = 0 ";
						$models=Categories::model()->with( 'products' )->findAll($criteria);
						
						//Yii::app()->cache->set($search.'products', $products, $this->SEARCH_CACH_EXPIRE);
						//Yii::app()->cache->set($search.'pages',     $pages,     $this->SEARCH_CACH_EXPIRE);
				
						//echo 'qweqwe = ';
						//echo count($models).'<br>';;
						
				}////////////if ($rows!=NULL) {
								
				
								//////////////////Пробегаемся по наименованию групп, и если совпало со строкой поиска - редиректим
								/*
								if (isset($models)) {
										for($i=0; $i<count($models); $i++) {
												$cat_name = $models[$i]->category_name;
												//echo trim(mb_strtolower($cat_name, "UTF-8")).' '. mb_strtolower($search, "UTF-8").'<br>';
										
												if (trim(mb_strtolower($cat_name, "UTF-8")) == mb_strtolower($search, "UTF-8")) {
														$this->redirect(array("/site/category/", 'alias'=>$models[$i]->alias), true, 302);
														exit;
												}/////////////////////////
											
										}//////////////////for($i=0; $i<count($models); $i++) {
								}////////////if (isset($models)) {
								*/
		/*
		//////////////////////////////////////////////Смотрим названия фирм
		$criteria=new CDbCriteria;
		$criteria->condition = " t.name LIKE :search_words  AND alias<>'' ";
		$criteria->params=array(':search_words'=>'%'.implode(',', $search_words).'%');
		$contr_agents= Contr_agents::model()->findAll($criteria);	
		*/
		
		if (isset($rows)) {	
		/////////////////////////////////////////////////////////////Выбираем дерево групп одним запросом и пост обработкой для нахождения всех ветвейv без привязки к региону
			$connection = Yii::app()->db;
			$query = "SELECT t.category_id, 
			t.category_name, 
			t.alias,
			t.parent,
			COUNT(products.id) AS number_of_products, 
			MAX(products.product_price) AS max_price, 
			MIN(products.product_price) AS min_price 
			FROM `categories` `t` LEFT JOIN products ON products.category_belong = t.category_id WHERE t.show_category = 1 ";
			//if (isset($region) AND @$region != 0) $query.=" AND products.kladr_belongs IN (".implode(',', $region_list).") ";
			$query.= "  GROUP BY t.category_id";
			$query.= " ORDER BY t.parent ASC";
			
			$command=$connection->createCommand($query)	;
			$dataReader=$command->query();
			$records=$dataReader->readAll();////
			foreach ($records as $k=>$v) {
		        $current['parent_id'] = $v['parent'];
				$current['category_id'] = $v['category_id'];
			    $current['products'] = $v['number_of_products'];
				$current['alias'] = $v['alias'];
                $current['name'] = $v['category_name'];
				if ( $v['parent'] == 0){
               		$this->tree[$v['category_id'] ] = $current;
              	} else {
         			$this->levels[$v['parent']]['children'][$v['category_id']] = $current;
            	}
        	}///////////foreach ($records as $k=>$v) {
		
		
			/////////////////////////////////////////////////////////////Выбираем дерево групп одним запросом и пост обработкой для нахождения количеств товаров c заданным фильтром региона
			$connection = Yii::app()->db;
			$query = "SELECT t.category_id, 
			t.category_name, 
			t.alias,
			t.parent,
			COUNT(products.id) AS number_of_products, 
			MAX(products.product_price) AS max_price, 
			MIN(products.product_price) AS min_price 
			FROM `categories` `t` LEFT JOIN products ON products.category_belong = t.category_id WHERE t.show_category = 1 ";
			//echo '<pre>';
			//					print_r($rows);
			//					echo '</pre>';
			//print_r(implode(',', $rows));
			$query.=" AND products.id IN (".implode(',', $rows).") ";////////////////////Связь с товараи по региону делает LEFT JOIN жестким JOIN 
			/////////////////////////////////////////////////////////т.к. промежуточные группы не имеют связи с товарами, они при наличии IN не выбираются вообще
			$query.= "  GROUP BY t.category_id";
			$query.= " ORDER BY t.parent ASC";
			//echo $query;
			
			$command=$connection->createCommand($query)	;
			$dataReader=$command->query();
			$records=$dataReader->readAll();////
			foreach ($records as $k=>$v) {
				$this->levels_region[$v['category_id']]=array('name'=>$v['category_name'], 'products'=>$v['number_of_products']);	
        	}///////////foreach ($records as $k=>$v) {				
			}///////////if (isset($rows)) {	
			
		$this->render('pages/search', array('rows'=>$rows, 'models'=>@$models,   'pages'=>@$pages, 'search'=>@$search, 'contr_agents'=>@$contr_agents));
	}

public function show_vetv($parent_id){
		//echo $parent_id.'<br>';
		$search = Yii::app()->getRequest()->getParam('search', NULL);	
		$sum = NULL;
		if(@isset($this->levels[$parent_id])){
		
				$arr = $this->levels[$parent_id];
				if (count($arr['children'])>0) {
				//$sum[1]= count($this->levels[$parent_id][children]);
						$txt = '<ul>';
						foreach ($arr['children'] as $parent_id=>$tree) {
								if(isset($this->levels_region[$tree['category_id']]['products'])) $sum[0] = $sum[0] +  $this->levels_region[$tree['category_id']]['products'];/////////////////////Это сумма товаров
								$qqq=$this->show_vetv($parent_id);
								$sum[0] = $sum[0] + $qqq[0];
								
								//if ( $qqq[0]>0) echo '--------'.$tree['name'].'<br>';
								if(isset($this->levels_region[$tree['category_id']]['products'])) if ($this->levels_region[$tree['category_id']]['products'] > 0 ) $sum[1] = @$sum[1]+1;
								if(isset($qqq[1])) if ($qqq[1]>0) $sum[1] = @$sum[1]+1;
								if ($qqq[0]>0 OR  @$this->levels_region[$tree['category_id']]['products'] ) {////////
										$txt .="<li>";
										if (@$this->levels_region[$tree['category_id']]['products']>0) $txt .=CHtml::link($tree['name'], array('product/list', 'id'=>$tree['category_id'], 'search'=>$search))."(".@$this->levels_region[$tree['category_id']]['products'].")"; 
										else $txt .=$tree['name'];
										if ($qqq[0]>0   )$txt .=@$qqq[2];
										$txt .="</li>";
								}/////////if ($sum[0]>0 )
						}//////////foreach ($arr['children'] as $parent_id=>$tree) {
						$txt .='</ul>';
						if ($sum[0]>0) $sum[2]=$txt;
				}//////////////if (count($arr['children'])>0) {
				//if ($sum[0]>0) echo $txt;
		}///////////$this->levels[$parent_id]
	//if ( $sum>0) echo $txt;
	return $sum;
}///////////////function show_vetv($parent_id){

public function actionAdvanced () {//////////////////////Расширенный поиск
		$filter_region = Yii::app()->getRequest()->getParam('filter_region', NULL);
		$unsetregion = Yii::app()->getRequest()->getParam('unsetregion', NULL);
		$filter_razdel = Yii::app()->getRequest()->getParam('filter_razdel', NULL);
		$unsetrazdel = Yii::app()->getRequest()->getParam('unsetrazdel', NULL);
		$expirience = Yii::app()->getRequest()->getParam('expirience', NULL);	
		$sort = Yii::app()->getRequest()->getParam('sort', NULL);	
		
		$date_from_value  = Yii::app()->getRequest()->getParam('date_from_value', NULL);
		$date_to_value  = Yii::app()->getRequest()->getParam('date_to_value', NULL);
		if (trim($date_from_value )<>'') {
				$date_from_arr = split("-", $date_from_value );
				$date_from_sql = $date_from_arr[2].'-'.$date_from_arr[1].'-'. $date_from_arr[0];
		}
		else $date_from_sql = NULL;
		
		//echo '<br>'.$date_from_sql.'<br>';
		if (trim($date_to_value )<>'') {
				$date_to_arr = split("-", $date_to_value );
				$date_to_sql = $date_to_arr[2].'-'.$date_to_arr[1].'-'. $date_to_arr[0];
		}
		else $date_to_sql = NULL;
		
		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////Обработка фильтра регионов
		$cookie = Yii::app()->request->cookies['search_region_list'];
		if (isset($cookie)) $search_region_list =  unserialize($cookie->value);
		else  $search_region_list = NULL;

		
		if ($unsetregion != NULL AND $cookie !=NULL ) {////////////////////////////////////////////////////////Удаляем регион если передан его идентификатор
				$search_region_list = NULL;
				$old_search_region_list =unserialize($cookie->value);
				//print_r($old_search_region_list);
				for ($i=0; $i<count($old_search_region_list); $i++) {
						if ($old_search_region_list[$i]<>'' AND $old_search_region_list[$i] !=$unsetregion ) $search_region_list[] = $old_search_region_list[$i];
				}//////////////for ($i=0; $i<count($old_search_region_list); $i++) {
				//if ($search_region_list != NULL) {
						unset(Yii::app()->request->cookies['search_region_list']);
						$cookie =new CHttpCookie('search_region_list', serialize($search_region_list) ); // 
						$cookie->expire= time()+60*60; ///////////24 часа
						Yii::app()->request->cookies['search_region_list']=$cookie;
				//}//////////if ($search_region_list != NULL) {
		} ///////////////////		
				
		if ($filter_region != NULL) {		/////////////////////////Если передан регион для выбора, то пишем в куки
				if ($cookie->value==NULL) {
						//echo '1<br>';
						unset(Yii::app()->request->cookies['search_region_list']);
						$cookie =new CHttpCookie('search_region_list', serialize(array($filter_region)) ); // sends a cookie
						$search_region_list[]=$filter_region;
						$cookie->expire= time()+60*60; ///////////24 часа
						Yii::app()->request->cookies['search_region_list']=$cookie;
				}
				else {
					
					//echo '2<br>';
					$search_region_list =  unserialize($cookie->value);
					if (in_array($filter_region, $search_region_list )==false) $search_region_list[]=$filter_region;
					
					unset(Yii::app()->request->cookies['search_region_list']);
					$cookie =new CHttpCookie('search_region_list', serialize($search_region_list) ); // 
					$cookie->expire= time()+60*60; ///////////24 часа
					Yii::app()->request->cookies['search_region_list']=$cookie;
				}
		}/////////////////	if ($filter_region != NULL) {
		
		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////Обработка фильра разделов
		$cookie = Yii::app()->request->cookies['search_razdel_list'];
		if (isset($cookie))  $search_razdel_list =  unserialize($cookie->value);
		else $search_razdel_list = NULL;
		
		if ($unsetrazdel != NULL AND $cookie !=NULL ) {/////////////////////////////////////////////////////////////Удаляем регион если передан его идентификатор
				$search_razdel_list = NULL;
				$old_search_razdel_list =unserialize($cookie->value);
				//print_r($old_search_region_list);
				for ($i=0; $i<count($old_search_razdel_list); $i++) {
						if ($old_search_razdel_list[$i]<>'' AND $old_search_razdel_list[$i] !=$unsetrazdel ) $search_razdel_list[] = $old_search_razdel_list[$i];
				}//////////////for ($i=0; $i<count($old_search_region_list); $i++) {
				//if ($search_region_list != NULL) {
						unset(Yii::app()->request->cookies['search_razdel_list']);
						$cookie =new CHttpCookie('search_razdel_list', serialize($search_razdel_list) ); // 
						$cookie->expire= time()+60*60; ///////////24 часа
						Yii::app()->request->cookies['search_razdel_list']=$cookie;
				//}//////////if ($search_region_list != NULL) {
		} ///////////////////		
		
		
		if ($filter_razdel != NULL) {		/////////////////////////Если передан регион для выбора, то пишем в куки
				if ($cookie->value==NULL) {
						//echo '21<br>';
						unset(Yii::app()->request->cookies['search_razdel_list']);
						$cookie =new CHttpCookie('search_razdel_list', serialize(array($filter_razdel)) ); // sends a cookie
						$search_razdel_list[]=$filter_razdel;
						$cookie->expire= time()+60*60; ///////////24 часа
						Yii::app()->request->cookies['search_razdel_list']=$cookie;
				}
				else {
					
					//echo '22<br>';
					$search_razdel_list =  unserialize($cookie->value);
					if (in_array($filter_razdel, $search_razdel_list )==false) $search_razdel_list[]=$filter_razdel;
					
					unset(Yii::app()->request->cookies['search_razdel_list']);
					$cookie =new CHttpCookie('search_razdel_list', serialize($search_razdel_list) ); // 
					$cookie->expire= time()+60*60; ///////////24 часа
					Yii::app()->request->cookies['search_razdel_list']=$cookie;
				}
		}/////////////////	if ($filter_region != NULL) {
		
		
		if (is_array($search_razdel_list)) {
				$criteria=new CDbCriteria;
				$criteria->condition ="t.category_id 	IN (".implode(',', $search_razdel_list).") ";
				$criteria->order = " t.sort_category ";
				$groups_filters = Categories::model()->findAll($criteria);
		}///////if (count($search_region_list)>0) {
		
		if (is_array($search_region_list)) {
				$criteria=new CDbCriteria;
				$criteria->condition ="t.kladr_id 	IN (".implode(',', $search_region_list).") ";
				$criteria->order = " t.name";
				$kladr_filters = Ma_kladr::model()->findAll($criteria);
		}///////if (count($search_region_list)>0) {
		//print_r($session);
		
		
		//var_dump($search_region_list);
		
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////Сам поиск (по товарам)
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		/*$filter_region = Yii::app()->getRequest()->getParam('filter_region', NULL);
		$unsetregion = Yii::app()->getRequest()->getParam('unsetregion', NULL);
		$filter_razdel = Yii::app()->getRequest()->getParam('filter_razdel', NULL);
		$unsetrazdel = Yii::app()->getRequest()->getParam('unsetrazdel', NULL);
		$expirience = Yii::app()->getRequest()->getParam('expirience', NULL);	
		
		
		*/
		$criteria=new CDbCriteria;
		$params=NULL;
		//$criteria->select=array( 't.*',  'picture_product.id AS icon' );
		$criteria->select = array('ROUND((DATEDIFF(NOW(), contr_agent.create_time)/365), 2) AS expirience' , 'picture_product.id AS icon', 't.*' );
			//$criteria->together = true;
			$criteria->join =" LEFT JOIN ( SELECT id, product FROM picture_product WHERE is_main=1) picture_product ON picture_product.product = t.id ";
		//$criteria->params=array(':frase'=>mb_strtolower($search, "UTF-8") );
		//$criteria->join ="
		//print_r($search_razdel_list);
		if (is_array($search_razdel_list)) {/////////////Фильтруем по разделу
				$criteria->addCondition("t.category_belong IN (".implode(',', $search_razdel_list).") ");
		}/////////////if (count($search_razdel_list)>0) {/////////////Фильтруем по разделу
		//var_dump($search_region_list);
		if (is_array($search_region_list)) {///////////////////Фильтр по региону
				$criteria->addCondition("t.kladr_belongs IN (".implode(',', $search_region_list).") ");
		}
		if ($expirience > 0) {
				$criteria->addCondition(" ROUND((DATEDIFF(NOW(), contr_agent.create_time)/365), 2)  > :expirience ");
				$params[':expirience'] = $expirience;
		}
		if ($date_to_sql != NULL) {
				$criteria->addCondition(" t.created  <= :date_to_sql");
				$params[':date_to_sql'] = $date_to_sql;
		}
		if ($date_from_sql != NULL) {
				//echo  $date_from_sql;
				$criteria->addCondition(" t.created >= :date_from_sql");
				$params[':date_from_sql'] = $date_from_sql;
		}
		if ($params != NULL ) $criteria->params=$params;
		
		switch ($sort) {
				case '1':
					$sort_order = 't.product_name';
					break;
				case '1d':
					$sort_order = 't.product_name DESC';
					break;
				case '2':
					$sort_order = 'belong_category.category_name';
					break;
				case '2d':
					$sort_order = 'belong_category.category_name DESC';
					break;
				case '3':
					$sort_order = 'kladr.name';
					break;
				case '3d':
					$sort_order = 'kladr.name DESC ';
					break;
				case '6':
					$sort_order = 't.created';
					break;
				case '6d':
					$sort_order = 't.created DESC ';
					break;
				case '7':
					$sort_order = 't.product_price';
					break;
				case '7d':
					$sort_order = 't.product_price DESC ';
					break;

				default: 
					$sort_order='t.id DESC';
				}
		$criteria->order=" $sort_order ";
		
		
		
		$pages=new CPagination(Products::model()->with('belong_category', 'kladr' , 'contr_agent' )->count($criteria)); //////////////// 6 мс
		//$pages->params=array('sort'=>$sort);
		$pages->pageSize=self::PAGE_SIZE;
		$pages->applyLimit($criteria);
		
			
		$products=Products::model()->with('belong_category', 'kladr' , 'contr_agent' )->findAll($criteria);
		
		
		$this->render('advanced', array('kladr_filters'=>@$kladr_filters, 'groups_filters'=>@$groups_filters, 'products'=>$products, 'pages'=>$pages, 'sort'=>$sort));
}////////////////////public function actionAdvanced () {//////////////////////Расширенный поиск


public function actionIndex_old()
	{
		$sort = Yii::app()->getRequest()->getParam('sort', NULL);
		$search = Yii::app()->getRequest()->getParam('search', NULL);	
		
		$search_words=explode(' ', $search);
		//Yii::app()->cache->flush();
		$rows =Yii::app()->cache->get(trim($search.'products'));
		if($rows===false) {
			
							if ($search==NULL) $this->redirect(Yii::app()->request->baseUrl."/");
							
							
							
							//1. Поиск по названию товара,  характеристикам
							$connection=Yii::app()->db;
								$rows = NULL;
								for ($i=0; $i<count($search_words); $i++) {
								$query="SELECT id FROM products WHERE (product_name LIKE(:search_name) OR  product_full_descr LIKE(:search_descr) ) AND product_visible = 1";
								$command=$connection->createCommand($query)	;
								
								$command->params=array(':search_name'=>'%'.trim($search_words[$i]).'%', ':search_descr'=>trim($search_words[$i]).'%');
								$dataReader=$command->query();
								//echo $query;
								for ($k=0; $k<$dataReader->count(); $k++) {
									$res = $dataReader->read();
									 $rows[prname][$i][]=$res[id];
								}///////////	for ($k=0; $k<count($dataReader->count()); $k++) {
								$rows[prname][$i]=array_unique($rows[prname][$i]);
								 
								$query="SELECT id_product as id FROM characteristics_values JOIN characteristics ON characteristics_values.id_caract = characteristics.caract_id JOIN products ON products.id=characteristics_values.id_product	 WHERE characteristics.caract_name  LIKE(:search)  	AND characteristics_values.value =1  AND products.product_visible = 1";
								$command=$connection->createCommand($query)	;
								$command->params=array(':search'=>'%'.trim($search_words[$i]).'%');
								$dataReader=$command->query();
								
								
								for ($k=0; $k<$dataReader->count(); $k++) {
									$res = $dataReader->read();
									 $rows[charname][$i][]=$res[id];
								}///////////	for ($k=0; $k<count($dataReader->count()); $k++) {
								$rows[charname][$i]=array_unique($rows[charname][$i]);
								
								}////////////////for ($i=0; $i<count($search_words); $i++) {
								
								foreach ($rows as $seach_type=>$data_arr) :
										if (count($rows[$seach_type])>1) {////////////Оставляем только присутствующие по каждому ключевому слову
												$nrows=$rows[$seach_type][0];
												//print_r($rows[$seach_type][0]);
												//echo '<br>';
												for ($i=1; $i<count($search_words); $i++) {
													//print_r($rows[$seach_type][$i]);
													//echo '<br>';
													$nrows=array_intersect($nrows, $rows[$seach_type][$i]);
												}
												//print_r($nrows);
										$rows[$seach_type] = $nrows;		
										}//////////if (count($search_words)>1) {
										else $rows[$seach_type]=$rows[$seach_type][0];
										//echo "<hr> по $seach_type: ";
										//print_r($rows[$seach_type]);
										foreach($rows[$seach_type] as $key=>$val ) $nnrows[]=$val;
								endforeach;
							/*	
								if (count($rows[prname])>1) {////////////Оставляем только присутствующие по каждому ключевому слову
										$nrows=$rows[prname][0];
										print_r($rows[prname][0]);
										echo '<br>';
										for ($i=1; $i<count($search_words); $i++) {
											print_r($rows[prname][$i]);
											echo '<br>';
											$nrows=array_intersect($nrows, $rows[prname][$i]);
										}
										//print_r($nrows);
								$rows[prname] = $nrows;		
								}//////////if (count($search_words)>1) {
								else $rows[prname]=$rows[prname][0];
								echo '<hr> по имени: ';
								print_r($rows[prname]);
								foreach($rows[prname] as $key=>$val ) $nnrows[]=$val;
								echo '<br>';
								print_r($nnrows);
								echo '<br><br>';
								
								if (count($rows[charname])>1) {///////////////////////Оставляем только присутствующие по каждому ключевому слову
										$nrows=$rows[charname][0];
										print_r($rows[charname][0]);
											echo '<br>';
										for ($i=1; $i<count($search_words); $i++) {
											print_r($rows[charname][$i]);
											echo '<br>';
											$nrows=array_intersect($nrows, $rows[charname][$i]);
										}
										//print_r($nrows);
										//echo '###########';
								$rows[charname] = $nrows;		
								}//////////if (count($search_words)>1) {
								else $rows[charname]=$rows[charname][0];
								echo '<hr> по свойствам:';
								print_r($rows[charname]);
								foreach($rows[charname] as $key=>$val )  $nnrows[]=$val;
								echo '<br>';
								print_r($nnrows);
								echo '<br><br>';
					*/
												
								$rows=array_unique($nnrows);
								Yii::app()->cache->set($search.'products', $rows, $this->SEARCH_CACH_EXPIRE);
					}///////if($products===false) {
					else {
							//$pages = Yii::app()->cache->get(trim($search.'pages'));
					}
					
								
								if ($rows!=NULL) {
										////////////////////Теперь делаем выборка по товарам
										$criteria=new CDbCriteria;
										$criteria->select=array( 't.*',  'attribute_value AS attribute_value',  'relev.relevant AS relevantnost');
										$criteria->condition = " t.id IN (".implode(',',$rows).") ";
										$criteria->params=array(':frase'=>mb_strtolower($search, "UTF-8") );
										$criteria->join ="
										LEFT JOIN (
													SELECT id_product, GROUP_CONCAT( characteristics.caract_name) AS attribute_value
													FROM characteristics JOIN`characteristics_values`  ON characteristics.caract_id = characteristics_values.id_caract
													JOIN characteristics_categories ON characteristics_categories.characteristics_id = characteristics.caract_id 
													GROUP BY id_product
													)product_attribute ON product_attribute.id_product = t.id 
										 LEFT JOIN ( SELECT product_id, relevant FROM products_search_relevant WHERE word=:frase )  relev		ON t.id = relev.product_id	
													";
										//$criteria->addInCondition('t.id', $product_arr, 'AND');
										
										switch ($sort) {
											case 1:
												$sort_order = 't.product_price';
												break;
											case 2:
												$sort_order = 't.created DESC';
												break;
							/*
											case 3:
												$sort_order = 't.login';
												break;
							*/					
											case 6:
												$sort_order = 't.product_name';
												break;
							
											default: 
												$sort_order=' relev.relevant DESC  , t.product_name';
											}
										$criteria->order = $sort_order;
										
										$pages=new CPagination(Products::model()->with('contr_agent', 'kladr')->count($criteria));
										$pages->pageSize=self::PAGE_SIZE;
										$pages->applyLimit($criteria);
										
										$products=Products::model()->with( 'contr_agent', 'kladr', 'belong_category' )->findAll($criteria);
										
										//Yii::app()->cache->set($search.'products', $products, $this->SEARCH_CACH_EXPIRE);
										//Yii::app()->cache->set($search.'pages',     $pages,     $this->SEARCH_CACH_EXPIRE);
								
										
										
								}////////////if ($rows!=NULL) {
								
				
								//делаем обработку для получения результатов релевантности в будущем
										for ($i=0; $i<count($products); $i++) {
												//if (strstr($products[$i]->product_name, $search_words[0])) {iconv("CP1251", "UTF-8", 
												$pat_one_name = "/^". mb_strtolower($search_words[0], "UTF-8")."/";/////////////////Совппдение только 1го слова
												$frase = implode(' ', $search_words);
												$pat_all_name = "/^". mb_strtolower($frase, "UTF-8")."/";/////////////////Совпадение всех слов
												
												//echo $pat.' '.mb_strtolower($products[$i]->product_name, "UTF-8").'<br>';
												//echo preg_match($pat, strtolower($products[$i]->product_name)).'<br>';
												//echo $pat_all_name;
												if (preg_match($pat_all_name, mb_strtolower($products[$i]->product_name, "UTF-8")) AND count($search_words)>1)	{
														//echo 'полностью: '.$products[$i]->product_name.'<br>';
														//if ($products[$i]->relevantnost!=2) {/////////Если не такая релевантность как должна быть, то сохраняем
																$nr =  Products_search_relevant::model()->findByAttributes(array('word'=>$frase, 'product_id'=>$products[$i]->id));
																if ($nr==NULL) {
																		$nr= new Products_search_relevant;
																}///////////////if ($relevantity==NULL) {
																$nr->word=$frase;
																$nr->product_id=$products[$i]->id;
																$nr->relevant=2;
																try {
																	$nr->save();
																	} catch (Exception $e) {
																	 echo 'Ошибка сохранения релевантности. ',  $e->getMessage(), "\n";
																	}/////try
														//}////////////////if ($products[$i]->relevantnost!=2) {/////////Если
												}//////////////if (strstr($products[$i]->product_name, $products)) {
												elseif (preg_match($pat_one_name, mb_strtolower($products[$i]->product_name, "UTF-8")) )	{
														//echo 'частично: '.$products[$i]->product_name.'<br>';
														$nr =  Products_search_relevant::model()->findByAttributes(array('word'=>$search_words[0], 'product_id'=>$products[$i]->id));
																if ($nr==NULL) {
																		$nr= new Products_search_relevant;
																}///////////////if ($relevantity==NULL) {
																$nr->word=$search_words[0];
																$nr->product_id=$products[$i]->id;
																$nr->relevant=1;
																try {
																	$nr->save();
																	} catch (Exception $e) {
																	 echo 'Ошибка сохранения релевантности. ',  $e->getMessage(), "\n";
																	}/////try
												}//////////////if (strstr($products[$i]->product_name, $products)) {
												
												
										}//////////////////
		
		
		
							
			
		$this->render('pages/search', array('rows'=>$rows, 'products'=>$products,   'pages'=>$pages));
	}//////////////function



public function actionServices() {

		//print_r($_GET);

					foreach($_GET as $gr_id=>$val):
						if(is_numeric($gr_id)) {
							$filter_arr[]=strval(trim($gr_id));/////////////////Массив с переданными значениями фильтров (опций)
							$income_get_filters[] = strval(trim($gr_id));/////////////////////Нужен для checked или нет чекбоксов в интерфейсе
						}
					endforeach;
					if(count($filter_arr)==0  ) {//////////////Значит ищемпо всем входящим в данную группу подкатегориям
							 $filter_arr = $this->cat_child_ids;
					}//if(count($filter_arr)>0) {


		$region = Yii::app()->getRequest()->getParam('region', NULL);	//////////Отбор по региону
		$ul = Yii::app()->getRequest()->getParam('ul', NULL);	
		$fl = Yii::app()->getRequest()->getParam('fl', NULL);	
		
			if (isset($region) AND @$region != 0)  {////////////// Нужно смотреть и тех кто входит в выбранный регион
					$kladr_id=Ma_kladr::model()->findByPk($region);
					$kladr_code = str_replace('0', '%', $kladr_id->code);
					$criteria=new CDbCriteria;
					$criteria->condition="t.code LIKE '$kladr_code%'  ";
					$regions_to_look_in = Ma_kladr::model()->findAll($criteria);
					for ($i=0; $i<count($regions_to_look_in); $i++) $region_list[]=$regions_to_look_in[$i]->kladr_id;
					//print_r($region_list);
			}//////////////if (isset($region) AND @$region != 0)  {
			
			
			if(isset($_GET[fchar])){
			foreach ($_GET[fchar] as $key=>$val):
					if(is_numeric($key)) $fchar[]=$key;
			endforeach;
			}///////////if(isset($_GET[fchar]){

				$criteria=new CDbCriteria;
				$criteria->select=array( 't.*',  'attribute_value AS attribute_value');
			//$criteria->together = true;
			$criteria->join ="
			LEFT JOIN (
SELECT id_product, GROUP_CONCAT( characteristics.caract_name) AS attribute_value
FROM `characteristics_values` JOIN characteristics ON characteristics.caract_id = characteristics_values.id_caract
WHERE characteristics.caract_category IN (";
				/////////////TO DO: через -params(':id'>array) получается IN('123,345,5654,35345') и автоматически преодразовывается к  123 - т.е. первому члену
				if (count($filter_arr)>0) $criteria->join .= implode(',',$filter_arr);
				else $criteria->join .=":cat_id ";
$criteria->join .=") AND characteristics_values.value=1 
GROUP BY id_product ORDER BY characteristics.caract_name
)product_attribute ON product_attribute.id_product = t.id";
if (count($fchar)>0) {///////////////т.е. если были переданы характеристики
				for ($k=0; $k<count($fchar); $k++) {
					$qqq = $fchar[$k];
					$criteria->join .=" JOIN (SELECT  characteristics_values.id_product AS id_product,   characteristics_values.value AS param_val FROM characteristics_values WHERE id_caract = $qqq AND  characteristics_values.value=1 ) filter_value_$qqq  ON filter_value_$qqq.id_product = t.id";
				}////////////////$fchar
			}//////////////////if (count($fchar)>0) {///////////////т.е. если были


				$criteria->order = " t.product_name ";
				$criteria->condition = " t.product_visible = 1";
				if (count($filter_arr)>0) $criteria->condition  .=" AND t.category_belong IN (".implode(',',$filter_arr).") ";
				else $criteria->condition  .=" AND t.category_belong = :cat_id ";
				//if (isset($ul) AND isset($fl)) ;
				if(isset($ul) AND !isset($fl))$criteria->condition .=" AND contr_agent.type = 1";
				if(isset($fl) AND !isset($ul)) $criteria->condition .=" AND contr_agent.type = 2";
				
				if (isset($region) AND @$region != 0) $criteria->condition .=" AND t.kladr_belongs IN (".implode(',', $region_list).")";
				//echo $query ;
				
				//if (count($filter_arr)==0) $criteria->params=array(':cat_id'=>$this->CAT->category_id);
				//else $criteria->params=array(':cat_id'=>implode(',',$filter_arr) );
				
				$pages=new CPagination(Products::model()->with('contr_agent')->count($criteria));
				$pages->pageSize=self::PAGE_SIZE;
				$pages->applyLimit($criteria);
				
				$products=Products::model()->with('contr_agent')->findAll($criteria);
				
				
				/////////////////////////Вытаскиваем характеристики для группы
				$criteria=new CDbCriteria;
				//$criteria->condition = "characteristics_categories.categories_id = :caract_category";
				//if (count($filter_arr)>0) $criteria->condition  .=" characteristics_categories.categories_id IN (".implode(',',$filter_arr).") ";//////////////Фильры выводятся только котносящиеся к категории заданной фильтром по группе, нужно что бы имплод был исходя из того кто входит в данную группу, или только общие
				if (count($filter_arr)>0) $criteria->condition  .=" characteristics_categories.categories_id IN (".implode(',', $this->cat_child_ids).") AND is_main=1 ";////////////////Фильтр с учетом того кто входит в категорию
				$criteria->order = "t.caract_name ";
				$criteria->params =  array('caract_category'=>$this->CAT->category_id);
				$characteristics = Characteristics::model()->with('characteristics_categories')->findAll($criteria);
				

				$this->render('category_products', array('products'=>$products, 'CAT'=>$this->CAT, 'characteristics'=>$characteristics, 'filter_arr'=>$filter_arr, 'fchar'=>$fchar, 'income_get_filters'=>$income_get_filters, 'pages'=>$pages) );
}//////public function actionCategory {
	

	
	public function actionAjaxfillkladr (){///////////дерево регионов
			
		
		if (isset($_GET['root']) AND $_GET['root'] !== 'source') {
				$code = Yii::app()->getRequest()->getParam('root', NULL);	
				$code1=substr($code, 0, 2);
				//echo $code1.'<br>';
				$criteria=new CDbCriteria;
				$criteria->order = 't.name, products.id IS NOT NULL';
				//$criteria->condition = " t.code RLIKE '".$code1."0[1-9]00%%' AND socr = 'г' ";
				$regexp = " t.code REGEXP '^".$code1."0[0-9]{2}0[0-9]{2}' AND socr = 'г' ";
				$criteria->condition = $regexp;
				//echo 'regexp = '.$regexp.'<br>';
				//$criteria->params=array(':code'=>$code1.'%');
				$tree= Ma_kladr::model()->with('products')->findAll($criteria);
		}///////if (isset($_GET['root']) {
		else {
				$criteria=new CDbCriteria;
				$criteria->order = 't.name';
				//$criteria->condition = " t.code LIKE '%%%0000000000'";
				//$criteria->condition = " t.code='5000000000000' OR t.code='7700000000000' OR  t.code='4700000000000' ";
				$criteria->condition = "  t.code LIKE '%%00000000000' ";
				$tree = Ma_kladr::model()->findAll($criteria);
		}////////else {
        $items = array();
       $i = 0;
        foreach($tree as $item)
        {
            if (isset($_GET['root']) AND $_GET['root'] !== 'source') {
					$link_text = iconv("UTF-8", "CP1251", $item->name.' '.$item->socr);
					$id = $item->kladr_id;
					$items[$i] = array('id'=>$item->code, 'text'=>CHtml::link($link_text, array('#'), array('onClick'=>"{UpdateOpener($id)}") ) );
					$items[$i]['hasChildren'] =  FALSE ;
			}
			else {
					//<a style="cursor:pointer" class="narrow" onClick="{UpdateOpener(<?=$models[$i]->kladr_id
					$items[$i] = array('id'=>$item->code, 'text'=>iconv("UTF-8", "CP1251", $item->name.' '.$item->socr) );
					$items[$i]['hasChildren'] = TRUE;
			}
           $i++;
        }
		echo CTreeView::saveDataAsJson($items);
		exit();
	}////////////////public function actionAjaxfillklad (){
	
	public function actionAjaxfilltreegroups (){/////////////////////Выбор раздела в дереве групп для поиска
			if (isset($_GET['root']) AND $_GET['root'] !== 'source') {
				$par_id = Yii::app()->getRequest()->getParam('root', NULL);	
				$criteria=new CDbCriteria;
				$criteria->order = 't.category_name';
				$criteria->condition = " t.parent = ".$par_id;
				$tree = Categories::model()->with('child_categories')->findAll($criteria);
		}///////if (isset($_GET['root']) {
		else {
				$criteria=new CDbCriteria;
				$criteria->order = 't.category_name';
				$criteria->condition = " t.parent = 0 AND t.category_id<>156";
				$tree= Categories::model()->with('child_categories')->findAll($criteria);
		}////////else {
		
		
		$items = array();
		$i = 0;
        foreach($tree as $item)
        {
			$link_text = iconv("UTF-8", "CP1251", $item->category_name);
			$id = $item->category_id;
           
					//<a style="cursor:pointer" class="narrow" onClick="{UpdateOpener(<?=$models[$i]->kladr_id
					$items[$i] = ((count($item->child_categories)>0 ) ?$items[$i] = array('id'=>$item->category_id, 'text'=>iconv("UTF-8", "CP1251", $item->category_name) )  : array('id'=>$item->category_id, 'text'=>CHtml::link($link_text, array('#'), array('onClick'=>"{UpdateOpener($id)}") ) ) );
					$items[$i]['hasChildren'] = ((count($item->child_categories)>0 ) ? TRUE : FALSE);
			
			$i++;
        }
		echo CTreeView::saveDataAsJson($items);
		exit();
	}//////////////////////public function actionAjaxfilltreegroups (){
	
}///////////////////class