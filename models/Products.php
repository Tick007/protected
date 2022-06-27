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
					'belong_category'    => array(self::BELONGS_TO, 'Categories', 'category_belong'),
					'char_val'			 =>array(self::HAS_MANY, 'Characteristics_values', 'id_product'),
					'files'=>array(self::HAS_MANY, 'Files', 'product'),
					'prices'=>array(self::HAS_MANY, 'PriceVariations', 'product'),
					'ostatki'=>array(self::HAS_MANY, 'Ostatki_trigers', 'tovar'),
					'card_prices'=>array(self::HAS_MANY, 'ProductCardPrices', 'product_id'),
					'measurelink'=>array(self::BELONGS_TO, 'Measures', 'measure'),
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

			////////////////////По характеристикам
			/*
			$query="SELECT id_product as id FROM characteristics_values JOIN characteristics ON characteristics_values.id_caract = characteristics.caract_id JOIN 
            products ON products.id=characteristics_values.id_product	 WHERE characteristics.caract_name  LIKE(:search)  	AND characteristics_values.value =1  AND products.product_visible = 1 AND products.category_belong>0";
			if (isset(Yii::app()->params['chernovik_cat_id'])) $query.= " AND products.category_belong <>".Yii::app()->params['chernovik_cat_id'];*/
			$query="SELECT id_product as id FROM characteristics_values JOIN characteristics ON characteristics_values.id_caract = characteristics.caract_id JOIN
            products ON products.id=characteristics_values.id_product	 WHERE characteristics_values.value LIKE(:search)  AND products.product_visible = 1 AND products.category_belong>0";
			if (isset(Yii::app()->params['chernovik_cat_id'])) $query.= " AND products.category_belong <>".Yii::app()->params['chernovik_cat_id'];
			$command=$connection->createCommand($query)	;
			$command->params=array(':search'=>'%'.trim($search_words[$i]).'%');
			//echo $query;
			$dataReader=$command->query();


			for ($k=0; $k<$dataReader->count(); $k++) {
				$res = $dataReader->read();
				$rows['charname'][$i][]=$res['id'];
			}///////////	for ($k=0; $k<count($dataReader->count()); $k++) {
			if (isset($rows['charname'][$i])) $rows['charname'][$i]=array_unique($rows['charname'][$i]);

			////////////////////По полю code в  price variations
			$query="SELECT DISTINCT product FROM price_variations WHERE code LIKE(:search)";
			$command=$connection->createCommand($query)	;
			$command->params=array(':search'=>'%'.trim($search_words[$i]).'%');
			$dataReader=$command->query();
			for ($k=0; $k<$dataReader->count(); $k++) {
			    $res = $dataReader->read();
			    $rows['pricevar'][$i][]=$res['product'];
			}///////////	for ($k=0; $k<count($dataReader->count()); $k++) {
			
			
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

					if (isset($new_rows[$seach_type]) ) foreach($new_rows[$seach_type] as $key=>$val ) $nnrows[]=$val;
					//if (isset($nnrows)) print_r($nnrows);
					//echo '<br><br>';
			endforeach;
			

				
			if (isset($nnrows)) {
				$rows=array_unique($nnrows);
				if(isset(Yii::app()->cache))Yii::app()->cache->set($search.'products', $rows, $expire);
				return $rows;
			}////////////////
		}////////if (isset($rows)) {
		else return NULL;
	}////////////////////private function findProductsBywords(){//////////////////В
	
	public static function get_products_by_filters($comp_attrs, $cat_id){
		
			//print_r($comp_attrs);
			//echo '<br>';
			
			$ProductList=new Product($cat_id);
			
			if(isset($comp_attrs['active']) AND $comp_attrs['active']==1) {
			
				//print_r($comp_attrs);
			
					if(isset($comp_attrs['filters'])) {
						$filters=unserialize($comp_attrs['filters']);
						if(is_array($filters) AND empty($filters)==false)  {
							foreach ($filters as $char_id=>$char_number) if($char_number>0) $ProductList->cfid_arr[$char_id] = array(($char_number-1)=>1);//////////////Задали фильтры в котором понимает класс Product
							
						}
					}
					if(isset($comp_attrs['minprice']) AND trim($comp_attrs['minprice'])) $ProductList->filters['price_from'] = $comp_attrs['minprice'];
					if(isset($comp_attrs['maxprice']) AND trim($comp_attrs['maxprice'])) $ProductList->filters['price_to'] = $comp_attrs['maxprice'];
					
					

					
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
	
	
	/*
	 * Не помню для чего эта функция была сделана
	 * */
	public static function get_product_params($id, $market_params=true){
			$connection=Yii::app()->db;
		
			$chars = implode(',', array_values(Yii::app()->params['market_params']));			
			$query="SELECT * FROM  characteristics_values JOIN characteristics ON characteristics.caract_id = characteristics_values.id_caract  WHERE id_caract IN (".$chars.") AND id_product = $id   ORDER BY FIELD(characteristics.caract_id,  ".$chars.")";//
			//echo $query;
			$command=$connection->createCommand($query)	;
			$dataReader=$command->query();
			$params = $dataReader->readAll();
			
			//print_r($params);
			
			if(isset($params)) {
					$has_attribute = array();
					$return_str='';
					for($p=0; $p<count($params); $p++) {
						
						foreach(Yii::app()->params['market_params'] as $param_name=>$param_id) {
							if($params[$p]['id_caract'] == $param_id AND trim($params[$p]['value'])) {
								
								///////////// Для формирования прайса xml типа vendor.model нужно что бы присутствовало все 3 поля: typePrefix  Vendor  Model
								if($param_name=='vendor' OR $param_name=='model' OR $param_name=='typePrefix') {
										$has_attribute[$param_name] = 1;
										 $return_str.='<'.$param_name.'>'.htmlspecialchars($params[$p]['value']).'</'.$param_name.'>';////////Простой вариант
								}
								else {////////посложнее типа  <param name="Размер оперативной памяти" unit="Мб">4096</param> 
									 $return_str.='<param name="'.htmlspecialchars($params[$p]['caract_name']).'" unit="'.htmlspecialchars($params[$p]['caract_mesuare']).'">'.htmlspecialchars($params[$p]['value']).'</param>';
								}
							}
						}
					}
					
					
					//print_r($has_attribute);
					if(sizeof($has_attribute)==3) $vender_model = true; //////////////3 - это количество главных параметров typePrefix  Vendor  Model
					else $vender_model = false;
					
					if( isset($return_str) AND trim($return_str)) return array('vender_model'=>$vender_model, 'attr'=>$return_str);
					else return array('vender_model'=>$vender_model, 'attr'=>'');
			}
		
	}/////////////////////
	
	/* 14.10.2015
	 * Новый метод для выбора опций товара. Вызывается в новом CatalogController
	 * */
	public static function getProductOptions($id){
		$criteria=new CDbCriteria;
		$criteria->distinct = true;
		$criteria->condition = " t.id_product = :product ";
		$criteria->params = array(':product'=>$id);
		$criteria->order = "t.id_caract";
		$characteristics_values = Characteristics_values::model()->with('characteristics')->findAll($criteria);
		if($characteristics_values!=null){
			foreach ($characteristics_values as $char_val){
				$options[$char_val->characteristics->caract_name]=	$char_val->attributes['value']	;
			}
		}
		if(isset($options) && $options!=null) return $options;
		else return null;
	}
	
	
	/**
	 * Вытаскиваем совместимые товары
	 * @param Integet $pd ИД товара
	 * выходной массив делаем таким же как у виджета витрина, метод DrawUniversal
	 * Алгоритм переписан относительно своего оригинала в продакт контроллере
	 */
	public static function getCompatibleProducts($pd){
		$criteria=new CDbCriteria;
		$criteria->order = ' t.product ';
		$criteria->condition = "compprod.product_visible=1  AND t.product = ".$pd;
		//if($_SERVER['HTTP_HOST']!='pnevmoinstrument.ru') $criteria->addCondition("compprod.product_price>0");
		$compabile= Products_compability::model()->with('compprod')->findAll($criteria);//
		if(isset($compabile)) {////////То смотрим их картинки
		
			$comp_list_ids = array_keys(CHtml::listdata($compabile, 'compatible', 'compatible'));
			if(isset($comp_list_ids) AND empty($comp_list_ids)==false AND is_array($comp_list_ids)  ) {
				$criteria=new CDbCriteria;
				//print_r($comp_list_ids);
				$criteria->condition = " t.product IN (".implode(',',  $comp_list_ids).") AND t.is_main = 1";
				$picture_products = Picture_product::model()->findAll($criteria);
				if(isset($picture_products)) $pictures_list = CHtml::listdata($picture_products, 'picture', 'product');
				//print_r( $pictures_list );
				if(isset($pictures_list) AND empty($pictures_list)==false) {
					$criteria=new CDbCriteria;
					$criteria->condition = " t.id IN (".implode(',',  array_keys($pictures_list)).") ";
					$pictures = Pictures::model()->findAll($criteria);
					if(isset($pictures) AND empty($pictures)==false) {
						for ($i=0; $i<count($pictures); $i++) {
							//print_r($pictures[$i]->attributes);
							if(isset($pictures_list[$pictures[$i]->id])) {
								$pict_ext[$pictures_list[$pictures[$i]->id]] = array('icon'=>$pictures[$i]->id, 'ext'=>$pictures[$i]->ext);
							}
						}
							if(isset($pict_ext))  {
									//print_r($pict_ext);
								for($i=0; $i<count($compabile); $i++) {
									if(isset($pict_ext[$compabile[$i]->compatible])) {
										$compabile_products[$compabile[$i]->compprod->id]['name']=$compabile[$i]->compprod->product_name;
										$compabile_products[$compabile[$i]->compprod->id]['price']=$compabile[$i]->compprod->product_price;
										$compabile_products[$compabile[$i]->compprod->id]['price_old']=$compabile[$i]->compprod->product_price_old;
										$compabile_products[$compabile[$i]->compprod->id]['category']=$compabile[$i]->compprod->category_belong;
										$compabile_products[$compabile[$i]->compprod->id]['category_alias']=$compabile[$i]->compprod->belong_category->alias;
										$compabile_products[$compabile[$i]->compprod->id]['product_sellout']=$compabile[$i]->compprod->product_sellout;
										$compabile_products[$compabile[$i]->compprod->id]['icon_id'] =  $pict_ext[$compabile[$i]->compatible]['icon'];
										$compabile_products[$compabile[$i]->compprod->id]['ext'] =  $pict_ext[$compabile[$i]->compatible]['ext'];
									}
								}
							}///////if(isset($pict_ext))  {

					}///if(isset($pictures) AND empty($pictures)==false) {
				}///////if(isset($pictures_list) AND empty($p
			}//////if(isset($comp_list_ids) AND empty($comp_list_ids)==false AND
		
	}
	if(isset($compabile_products)){
		$picture_products=null;
		$compabile = null;
		$pictures=null;
		return $compabile_products;
	}
	else return null;
	
	}
	
	
	public static  function DeleteProduct($product_to_del) {
		//////////Процедура удаления товара

		$PRODUCT = Products::model()->findbyPk($product_to_del);
		if ($PRODUCT!=NULL) {

			//1. фотки
			$criteria=new CDbCriteria;
			//$criteria->order = ' t.sort_category ';
			$criteria->condition = " 	t.product = :product";
			$criteria->params = array(':product'=>$product_to_del);
			$picture_products = Picture_product::model()->with('img')->findAll($criteria);//
			
			
			for ($i=0; $i<count($picture_products);$i++) {
				
				if (isset($picture_products[$i]->img)==true) {
				//	echo '<pre>';
				//	print_r($picture_products[$i]);
				//	echo '</pre>';
				//	exit();
				//}
				
					$srctfile =  'pictures/add/'.$picture_products[$i]->img->id.'.'.$picture_products[$i]->img->ext;		
					$iconfile =  'pictures/add/icons/'.$picture_products[$i]->img->id.'.png';		
					$iconsmall =  'pictures/add/icons_small/'.$picture_products[$i]->img->id.'.png';	
						
					$mainpicture_jpg = 'pictures/img/'.$product_to_del.'.jpg';
					$mainpicture_png = 'pictures/img/'.$product_to_del.'.png';		
					$mainpicture_med_jpg = 'pictures/img_med/'.$product_to_del.'.jpg';
					$mainpicture_med_png = 'pictures/img_med/'.$product_to_del.'.png';
					$mainpicture_small_jpg = 'pictures/img_small/'.$product_to_del.'.jpg';
					$mainpicture_small_png = 'pictures/img_small/'.$product_to_del.'.png';				
					
					@unlink($_SERVER['DOCUMENT_ROOT'].Yii::app()->request->baseUrl.'/'.$srctfile);
					@unlink($_SERVER['DOCUMENT_ROOT'].Yii::app()->request->baseUrl.'/'.$iconfile);
					@unlink($_SERVER['DOCUMENT_ROOT'].Yii::app()->request->baseUrl.'/'.$iconsmall);
					
					@unlink($_SERVER['DOCUMENT_ROOT'].Yii::app()->request->baseUrl.'/'.$mainpicture_jpg);
					@unlink($_SERVER['DOCUMENT_ROOT'].Yii::app()->request->baseUrl.'/'.$mainpicture_png);
					@unlink($_SERVER['DOCUMENT_ROOT'].Yii::app()->request->baseUrl.'/'.$mainpicture_med_jpg);
					@unlink($_SERVER['DOCUMENT_ROOT'].Yii::app()->request->baseUrl.'/'.$mainpicture_med_png);
					@unlink($_SERVER['DOCUMENT_ROOT'].Yii::app()->request->baseUrl.'/'.$mainpicture_small_jpg);
					@unlink($_SERVER['DOCUMENT_ROOT'].Yii::app()->request->baseUrl.'/'.$mainpicture_small_png);
					
				}////////////////
					
				try {
					$picture_products[$i]->delete();
				} catch (Exception $e) {
					echo 'Ошибка удаления иконки. ',  $e->getMessage(), "\n";
				}/////
				try {
					if (isset($picture_products[$i]->img)) $picture_products[$i]->img->delete();
				} catch (Exception $e) {
					echo 'Ошибка удаления картинки. ',  $e->getMessage(), "\n";
				}/////
					
			}/////////////for ($i=0; $<count($picture_products);$i++) {
			
			//1.5 

			//2. опции
			$criteria=new CDbCriteria;
			$criteria->condition = " t.id_product = ".$product_to_del ; //////////is_common = признак что пользователь имеет доступ к этому параметру
			$parametrs = Characteristics_values::model()->findAll($criteria);//
			for ($i=0; $i<count($parametrs);$i++) {
				try {
					$parametrs[$i]->delete();
				} catch (Exception $e) {
					echo 'Ошибка удаления параметра. ',  $e->getMessage(), "\n";
				}/////
			}////////////////for ($i=0; $i<count($parametrs_product);$i++) {
			
			//3. Дочерние товары
			$criteria=new CDbCriteria;
			$criteria->condition="t.product_parent_id = :id";
			$criteria->params=array(':id'=>$PRODUCT->id);
			$childs = Products::model()->findAll($criteria);
			if (isset($childs)) for($k=0; $k<count($childs); $k++) Products::DeleteProduct($childs[$k]->id);
			
			
			//4. Связанные группы
			$criteria=new CDbCriteria;
			$criteria->condition="t.product = :product";
			$criteria->params=array(':product'=>$PRODUCT->id);
			$categories_products = Categories_products::model()->findAll($criteria);
			if (isset($categories_products)) for($k=0; $k<count($categories_products); $k++)$categories_products[$k]->delete();
			
			//5. товар
			try {
				$PRODUCT->delete();
			} catch (Exception $e) {
				echo 'Ошибка удаления товара. ',  $e->getMessage(), "\n";
			}/////

		}/////////////////if ($PRODUCT!=NULL)
			
	}////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
	
	
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.
	
		$criteria=new CDbCriteria;
	
		$criteria->compare('id',$this->id);
		$criteria->compare('product_name',$this->product_name,true);
		$criteria->compare('product_article',$this->product_article,true);
		$criteria->compare('product_short_descr',$this->product_short_descr,true);
		$criteria->compare('product_full_descr',$this->product_full_descr,true);
		$criteria->compare('category_belong',$this->category_belong);
		$criteria->compare('product_visible',$this->product_visible);
		$criteria->compare('new_product',$this->new_product);
		$criteria->compare('product_html_title',$this->product_html_title,true);
		$criteria->compare('product_html_keywords',$this->product_html_keywords,true);
		$criteria->compare('product_html_description',$this->product_html_description,true);
		$criteria->compare('product_vitrina',$this->product_vitrina);
		$criteria->compare('product_sellout',$this->product_sellout);
		$criteria->compare('product_price',$this->product_price);
		$criteria->compare('product_price_old',$this->product_price_old);
		$criteria->compare('product_price_vip',$this->product_price_vip);
		$criteria->compare('product_price_recomended',$this->product_price_recomended);
		$criteria->compare('number_in_store',$this->number_in_store);
		$criteria->compare('product_visible_for_xml',$this->product_visible_for_xml);
		$criteria->compare('product_made_in',$this->product_made_in,true);
		$criteria->compare('product_dlina',$this->product_dlina);
		$criteria->compare('product_shirina',$this->product_shirina);
		$criteria->compare('product_visota',$this->product_visota);
		$criteria->compare('product_ves',$this->product_ves);
		$criteria->compare('product_size',$this->product_size,true);
		$criteria->compare('product_color',$this->product_color,true);
		$criteria->compare('product_sort',$this->product_sort);
		$criteria->compare('product_manufacture',$this->product_manufacture);
		$criteria->compare('product_warranty',$this->product_warranty);
		$criteria->compare('measure',$this->measure);
		$criteria->compare('nds_out',$this->nds_out);
		$criteria->compare('guid1',$this->guid1,true);
		$criteria->compare('product_parent_id',$this->product_parent_id);
		$criteria->compare('product_vitrina_sort',$this->product_vitrina_sort);
		$criteria->compare('product_sellout_sort',$this->product_sellout_sort);
		$criteria->compare('product_new',$this->product_new);
		$criteria->compare('product_new_sort',$this->product_new_sort);
		$criteria->compare('contragent_id',$this->contragent_id);
		$criteria->compare('sellout_price',$this->sellout_price);
		$criteria->compare('sellout_active_till_int',$this->sellout_active_till_int);
		$criteria->compare('sort',$this->sort);
		$criteria->compare('product_price_no_auto_update',$this->product_price_no_auto_update);
		$criteria->compare('parse_url',$this->parse_url,true);
	
		return new CActiveDataProvider($this, array(
				'criteria'=>$criteria,
		));
	}
	
}