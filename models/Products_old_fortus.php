<?php

class Products extends CActiveRecord ///////////
{	

	public $attribute_value;
	public $product_count;
	private $connection;
	public $head_images; /////////////////////Тут будет храниться массив с фоками модели и бренда
	var $icon; ////////////////////идентификатор иконки
	var $icon_id; ////////////////////идентификатор иконки
	var $ext;

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}


	public function tableName()
	{
		return 'products';
	}

	
		public function relations()
		{
					return array(
					'categories_products'=> array(self::HAS_MANY, 'Categories_products', 'product'),
					//'child_items'=>array(self::MANY_MANY, 'Authitem', 'AuthItemChild(parent, child)'),
					//'authitemchild'=> array(self::BELONGS_TO, 'Authitemchild', 'name'),
					'belong_category'    => array(self::BELONGS_TO, 'Catalog', 'category_belong'),
					'char_val'			 =>array(self::HAS_MANY, 'Characteristics_values', 'id_product'),
					'files'=>array(self::HAS_MANY, 'Files', 'product'),
					'ostatki'=>array(self::HAS_MANY, 'Ostatki_trigers', 'tovar'),
					);
		}


		public static  function findProductsBywords($search, $expire){
		//////////////////Вытаскиваем id njdfhjd gj gjbcrjdjq ahfpt
		$search = trim($search);
		$search_words=explode(' ', $search);
		//print_r($search_words);
		//exit();
		//1. Поиск по названию товара,  характеристикам
		$connection=Yii::app()->db;
		$rows = NULL;
		for ($i=0; $i<count($search_words); $i++) {
			$query="SELECT id FROM products WHERE (product_name LIKE(:search_name) OR  product_full_descr LIKE(:search_descr) OR product_article LIKE(:search_name) ) AND product_visible = 1";
			$command=$connection->createCommand($query)	;

			$command->params=array(':search_name'=>'%'.trim($search_words[$i]).'%', ':search_descr'=>'% '.trim($search_words[$i]).'%');
			$dataReader=$command->query();

			/*
			 echo $query;
			echo '<pre>';
			print_r(count($dataReader));
			echo '</pre>';
			exit();
			*/
			for ($k=0; $k<$dataReader->count(); $k++) {
				$res = $dataReader->read();
				$rows['prname'][$i][]=$res['id'];
			}///////////	for ($k=0; $k<count($dataReader->count()); $k++) {
			if (isset($rows['prname'][$i])) $rows['prname'][$i]=array_unique($rows['prname'][$i]);

			$query="SELECT id_product as id FROM characteristics_values JOIN characteristics ON characteristics_values.id_caract = characteristics.caract_id JOIN products ON products.id=characteristics_values.id_product	 WHERE characteristics.caract_name  LIKE(:search)  	AND characteristics_values.value =1  AND products.product_visible = 1 AND products.category_belong>0";
			if (isset(Yii::app()->params['chernovik_cat_id'])) $query.= " AND products.category_belong <>".Yii::app()->params['chernovik_cat_id'];
			$command=$connection->createCommand($query)	;
			$command->params=array(':search'=>'%'.trim($search_words[$i]).'%');
			$dataReader=$command->query();


			for ($k=0; $k<$dataReader->count(); $k++) {
				$res = $dataReader->read();
				$rows['charname'][$i][]=$res['id'];
			}///////////	for ($k=0; $k<count($dataReader->count()); $k++) {
			if (isset($rows['charname'][$i])) $rows['charname'][$i]=array_unique($rows['charname'][$i]);


			////////////////////По названию групп товаров
			$query="SELECT DISTINCT products.id as id FROM categories JOIN products ON products.category_belong = categories.category_id WHERE categories.category_name  LIKE(:search)  	AND categories.show_category =1 AND categories.path IS NOT NULL  AND products.product_visible = 1 AND products.category_belong>0";
			if (isset(Yii::app()->params['chernovik_cat_id'])) $query.= " AND products.category_belong <>".Yii::app()->params['chernovik_cat_id'];
			$command=$connection->createCommand($query)	;
			$command->params=array(':search'=>'%'.trim($search_words[$i]).'%');
			$dataReader=$command->query();

			for ($k=0; $k<$dataReader->count(); $k++) {
				$res = $dataReader->read();
				$rows['catname'][$i][]=$res['id'];
			}///////////	for ($k=0; $k<count($dataReader->count()); $k++) {
			if(isset($rows['catname'][$i]))$rows['catname'][$i]=array_unique($rows['catname'][$i]);

		}////////////////for ($i=0; $i<count($search_words); $i++) {
		
		/*
		echo '<pre>';
		print_r($rows);
		echo '</pre>';
		echo '<br><br>';
		*/
		//exit();

		if (isset($rows)) {
			foreach ($rows as $seach_type=>$data_arr) :
					if (count($rows[$seach_type])==count($search_words) ){//////////Делаем далее только если количество найденный массивов по каждому слову по каждому типу равно количеству слов
					//echo 	"rows[$seach_type] = ".count($rows[$seach_type]).'<br>';
					//if (count($rows[$seach_type])>1 ){ //////Количество массивов по каждому из слов для типа $seach_type
						////////////Оставляем только присутствующие по каждому ключевому слову
						$nrows=$rows[$seach_type][0];
						for ($i=0; $i<count($search_words); $i++) {///////////////из найденных отдельно для каждого слова из запроса оставляем совпадаюшее по всем словам  товары
							if (isset($rows[$seach_type][$i])) $nrows=array_intersect($nrows, $rows[$seach_type][$i]);
							}
						$new_rows[$seach_type] = $nrows;
						//print_r($new_rows[$seach_type]);
						//echo '<br><br>';
					}//////////if (count($search_words)>1) {
					/*	
					elseif(count($rows[$seach_type])==1 AND count($search_words)==1 ){//////////Только 1 
						$qqq = array_keys($rows[$seach_type]);
						//print_r($qqq[0]);
						if(count($rows[$seach_type])==1) $rows[$seach_type]=$rows[$seach_type][$qqq[0]]; 
					}
					*/
				
				
					//echo 'nrows = <pre>';
					//print_r($new_rows[$seach_type]);
					//echo '</pre>';
					
					//echo "rows[$seach_type] = <pre>";
					//print_r($rows[$seach_type]);
					//echo '</pre>';
					//exit();
					//echo '<br>';
					
					if (isset($new_rows[$seach_type]) ) foreach($new_rows[$seach_type] as $key=>$val ) $nnrows[]=$val;
					//if (isset($nnrows)) print_r($nnrows);
					//echo '<br><br>';
			endforeach;
			
			/*
			echo 'nrows = <pre>';
			print_r($nnrows);
			echo '</pre>';
			exit();
			*/
			//echo 'qweqweqwe<br>';
			//if (isset($nnrows) == false) {
			//print_r($nnrows);
			//exit();
				
			if (isset($nnrows)) {
				$rows=array_unique($nnrows);
				Yii::app()->cache->set($search.'products', $rows, $expire);
				return $rows;
			}////////////////
		}////////if (isset($rows)) {
		else return NULL;
	}////////////////////private function findProductsBywords(){//////////////////В
	
	public static function get_products_by_filters($comp_attrs, $cat_id){
		
			//print_r($comp_attrs);
			//echo '<br>';
			
			$ProductList=new Product($cat_id);
			
			if(isset($comp_attrs[active]) AND $comp_attrs[active]==1) {
			
					if(isset($comp_attrs['filters'])) {
						$filters=unserialize($comp_attrs['filters']);
						if(is_array($filters) AND empty($filters)==false)  {
							foreach ($filters as $char_id=>$char_number) if($char_number>0) $ProductList->cfid_arr[$char_id] = array(($char_number-1)=>1);//////////////Задали фильтры в котором понимает класс Product
							
						}
					}
					if(isset($comp_attrs['minprice']) AND trim($comp_attrs['minprice'])) $ProductList->filters['price_from'] = $comp_attrs['minprice'];
					if(isset($comp_attrs['maxprice']) AND trim($comp_attrs['maxprice'])) $ProductList->filters['price_to'] = $comp_attrs['maxprice'];
					
					
					
					//$ProductList->
					$ProductList->ExecuteObject(); ////////////перенес из констракта в отдельный метод
					$ProductList->offset = 0;
					$ProductList->limit = 10000;
					$models = $ProductList->run_query();		
					
					//echo 'найдено: '.count($models);
					//echo '<br>';
					if(count($models)>0) {
						foreach($models as $n=>$next) $prod_list[] = $next['id'];
						return serialize($prod_list);
					}
			}////////////
			else return NULL;
			
	}////////////public static function get_products_by_filters(){
	
}