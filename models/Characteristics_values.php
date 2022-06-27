<?php

class Characteristics_values extends CActiveRecord ///////////
{	

	private $connection;
	public $head_images; /////////////////////Тут будет храниться массив с фоками модели и бренда

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}


	public function tableName()
	{
		//return 'User';
		return 'characteristics_values';
	}

	public function relations()
	{
		return array(
		'characteristics' => array(self::BELONGS_TO, 'Characteristics', 'id_caract'), 
		'products'    => array(self::BELONGS_TO, 'Products', 'id_product'),//////////////////////////только для типа 9 (товар)
		//'ma_soun' => array(self::BELONGS_TO, 'Ma_soun', 'value'),
		);
	}

	function init()
	{
		$this->connection = Yii::app()->db; 
		return parent::init();
	}
	

	public static function get_products_values($characteristics_categories){//////////Получение уникальных значения товаров для заданных групп

		 foreach ($characteristics_categories as $cat_id=>$cat) {
			 
			//echo '<br>$cat_id = '.$cat_id.'<br>'; 
			// TradeXCache::clear_products_cache($cat_id);
			 
			 $recasche = new CatalogCache();
			  $recasche->make_cache($_SERVER['HTTP_HOST'], $cat_id);
			 
			 
			//Получаем список id  товаров по категории
			$products1 = Products::model()->findAllByAttributes(array('category_belong'=>$cat_id, 'product_visible'=>1));
			$criteria=new CDbCriteria;
			$criteria->condition="categories_products.group = :cat_id AND t.product_visible = 1";
			$criteria->params = array('cat_id'=>$cat_id);
			$products2 = Products::model()->with('categories_products')->findAll($criteria);
			$products = @$products1+ @$products2;
			//$products = @$products1;
			
			
			if (isset($products) AND isset($cat) AND empty($cat)==false) {//////////Выбираем уникальные значения 
				//echo '<br>список товаров = ';
			
				$product_list=array_keys(CHtml::listdata($products, 'id', 'id'));
				$product_list_temp=CHtml::listdata($products, 'id', 'product_name');
				//echo '<pre>';
				//print_r($product_list_temp);
				//echo '</pre>';
				//echo '<br>';
				$criteria=new CDbCriteria;
				$criteria->distinct=true;
				$creteria->condition = "t.value <>";
				$criteria->select=array('id_caract', 'value');
				$criteria->order="t.value";
				//$criteria->condition="t.id_product IN (".implode(',', $product_list).")   AND t.id_caract IN (".implode(',', array_values(CHtml::listdata($cat, 'id', 'characteristics_id'))).") ";
				$criteria->condition="t.id_product IN (".implode(',', $product_list).") ";
				$values = Characteristics_values::model()->findAll($criteria);
				//echo  $cat_id.': '.count($cat).':'.count($values).'<br>';
				 $values_list[$cat_id][$values[$k]->id_caract][0]='все...';
				if(isset($values)) {
					for($k=0; $k<count($values); $k++) {
						if(isset($values_list[$cat_id][$values[$k]->id_caract][0])==false)$values_list[$cat_id][$values[$k]->id_caract][0]='все...';
					//echo $values[$k]->id_caract.': '.$values[$k]->value.'<br>';
						if(trim($values[$k]->value)) $values_list[$cat_id][$values[$k]->id_caract][]=$values[$k]->value;
					}
				}
			}
		}///////// foreach ($characteristics_categories as $cat_id->$cat) {
			
		if(isset($values_list)) return $values_list;	
			
	}//////////public static function get_products_values($characteristics_categories){//////////Получ
	
}
