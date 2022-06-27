<?php
class FortusCatalogLeftPad extends CWidget {
var $brand_list;
public $krepl_prod_ids;
public $krepl_categ_ids;
var $device_types_list;

function __construct($krepl_prod_ids=NULL, $krepl_categ_ids=NULL){
	
			if($krepl_prod_ids !=NULL AND is_array($krepl_prod_ids)==true AND empty($krepl_prod_ids)==false) $this->krepl_prod_ids = $krepl_prod_ids;
			if($krepl_categ_ids!=NULL AND is_array($krepl_categ_ids)==true AND empty($krepl_categ_ids)==false) $this->krepl_categ_ids = $krepl_categ_ids;
	
	
			//print_r($krepl_prod_ids);
	//
			$criteria=new CDbCriteria;
			//$criteria->order = 't.sort_category';
			$criteria->order = 't.category_name';
			$criteria->condition = " t.parent =  :root AND t.show_category = 1  ";
			$criteria->params=array(':root'=>Yii::app()->params['main_tree_root']);
			if(empty($this->krepl_categ_ids)==false)  $criteria->addCondition('child_categories.category_id IN ('.implode(',', $this->krepl_categ_ids).')');
			$first_tree = Categories::model()->with('child_categories')->findAll($criteria);//
			if (isset($first_tree)) {
				$brand_list=CHtml::listData($first_tree,'category_id','category_name');
				//$this->brand_list = array('0'=>iconv( "CP1251", "UTF-8", "Марка автомобиля"))+$brand_list;
				//$this->brand_list  = $brand_list; 
				$this->brand_list = array('0'=>"Марка автомобиля")+$brand_list;
			}
			
			
			
			
}/////////////function __construct(){



function draw($target_file=NULL){
	
	//print_r($_GET);
	/////////////Для карточки товара нужно смотреть опции
	$id =  Yii::app()->getRequest()->getParam('id');
	
	if(isset($id)) {///////ид товара
		$criteria=new CDbCriteria;
		//$criteria->distinct=true;
		$criteria->condition = " t.id_product = :id_product  ";
		//$criteria->group="id_caract, value";
		$criteria->params=array(':id_product'=>$id);
		$char_vals = Characteristics_values::model()->findAll($criteria);
		if(isset($char_vals)) {
			$char_vals_list=CHtml::listdata($char_vals, 'id_caract', 'value');
			//print_r($char_vals_list);
			if(isset(Yii::app()->params['filters'])) {
				if(isset(Yii::app()->params['filters']['year']) AND isset($char_vals_list[Yii::app()->params['filters']['year']])) $year=$char_vals_list[Yii::app()->params['filters']['year']];
							
				if(isset(Yii::app()->params['filters']['kppfrontend']) AND isset($char_vals_list[Yii::app()->params['filters']['kppfrontend']])) $kpp=$char_vals_list[Yii::app()->params['filters']['kppfrontend']];
				

				
			}
		}
	}
	
	
	//////////////Список типов устройств
		$this->device_types_list=array('0'=>"Тип устройства")+Yii::app()->params['typs_krepleniya'];
	//print_r($this->device_types_list);
	//echo '<br>';
	//print_r(Yii::app()->params['typs_krepleniya']);
	
		$alias =  Yii::app()->getRequest()->getParam('alias');
		//echo $alias;
		if (isset($alias)) {
			
		$cat = Categories::model()->findByAttributes(array('alias'=>$alias));
			if (isset($cat) AND $cat->parent!=0 AND $cat->parent!=Yii::app()->params['main_tree_root'] ) $brand = $cat->parent;
			else $brand = $cat->category_id;
			//var_dump($brand);
			//$model = $cat->category_id;
			$this_array_path = array( 'path'=>FHtml::urlpath($cat->path), 'alias'=>$cat->alias);
			$this_url = urldecode(Yii::app()->createAbsoluteUrl('constructcatalog/group', $this_array_path));
			//$model = $this_url;
			if($brand != $cat->category_id)$model = $cat->category_id;
			
			$criteria=new CDbCriteria;
			//$criteria->order = 't.sort_category';
			$criteria->order = 't.category_name';
			$criteria->condition = " t.parent =  :root AND t.show_category = 1  ";
			$criteria->params=array(':root'=>$brand);
			if(empty($this->krepl_categ_ids)==false)  $criteria->addCondition('t.category_id IN ('.implode(',', $this->krepl_categ_ids).')');
			$models = Categories::model()->with('child_categories')->findAll($criteria);//
			if (isset($models)) {
				//$model_list=CHtml::listData($first_tree,'category_id','category_name');
				for($k=0; $k<count($models); $k++) {
					//$path_array=  array( 'path'=>FHtml::urlpath($models[$k]->path), 'alias'=>$models[$k]->alias);
					//$url= urldecode(Yii::app()->createAbsoluteUrl('constructcatalog/group', $path_array));
					//$model_list[$url]=$models[$k]->category_name;
					$model_list[$models[$k]->category_id]=$models[$k]->category_name;
				}
				//if(isset($model_list))$model_list = array('0'=>iconv( "CP1251", "UTF-8", "Выберете модель"))+$model_list;
				if(isset($model_list))$model_list = array('0'=>"выбор...")+$model_list;
				
			}
			
		
		}
		//else $model_list = array('0'=>iconv( "CP1251", "UTF-8", "Модель автомобиля"));
		else $model_list = array('0'=>"Модель");
		//$this->render('ajaxselector', array('brand_list'=>$this->brand_list, 'brand'=>@$brand, 'model'=>$model, 'model_list'=>@$model_list) );

if(isset($year)) {//////////Теперь нужно достать список всех значеинй дополнительный опций
					
					if (isset($model)) {
						//echo Yii::app()->params['filters']['kppfrontend'].'<br>';
						//echo '<br>'.$year."<br>";;
						//echo '<br>'.$model."<br>";;
					
						$criteria=new CDbCriteria;
						$criteria->condition = " products.category_belong  =  :model  AND products.product_visible = 1  ";
						
						$criteria->params=array(':model'=>$model);
						
						
						$kpp_list[]='...выбор';
						$cvmodels=Characteristics_values::model()->with('products')->findAll($criteria);
						if(isset($cvmodels)) for($k=0; $k<count($cvmodels); $k++) {
							//echo $cvmodels[$k]->products->id.' '.$cvmodels[$k]->id_caract.' '.$cvmodels[$k]->value.'<br>';
							if($cvmodels[$k]->id_caract ==Yii::app()->params['filters']['year']  ){
								 
								 $year_list[$cvmodels[$k]->value]=$cvmodels[$k]->value;
							}
							if(@$year==$cvmodels[$k]->value) $year_products[] = $cvmodels[$k]->id_product;
							//if($cvmodels[$k]->id_caract ==Yii::app()->params['filters']['kppfrontend']  /*AND $id==$cvmodels[$k]->id_product*/ AND isset() ) $kpp_list[$cvmodels[$k]->value]=$cvmodels[$k]->value;
							
						}///////if(isset($cvmodels)) for($k=0; $k<cou
						if(isset($year_products)) {
							
							//print_r($year_products);
							
							for($k=0; $k<count($cvmodels); $k++) {
								if($cvmodels[$k]->id_caract ==Yii::app()->params['filters']['kppfrontend']   AND in_array($cvmodels[$k]->id_product, $year_products) ) $kpp_list[$cvmodels[$k]->value]=$cvmodels[$k]->value;
							}
						}
						
						
						//print_r($kpp_list);
					}
					
				}/////if(isset($year)) {



if(isset($model)){ //////////Смотрим список годов
			//echo $model.'<br>';
			//print_r($this->krepl_prod_ids);
			
			$year_char_id = Yii::app()->params['filters']['year'];
			//echo $year_char_id;
			$criteria=new CDbCriteria;
			$criteria->distinct=true;
			$criteria->select=array('value AS value');
			$criteria->condition = " products.category_belong  =  :model  AND products.product_visible = 1 AND t.id_caract = :caract_id ";
			$criteria->params=array(':model'=>$model, ':caract_id'=>$year_char_id);
			if(empty($this->krepl_prod_ids)==false)  $criteria->addCondition('products.id IN ('.implode(',', $this->krepl_prod_ids).')');
			$criteria->group="value";
			$models=Characteristics_values::model()->with('products')->findAll($criteria);
			//print_r($models);
			////////////Это классика
			//$models_list=CHtml::tag('option', array('value'=>0), iconv( "UTF-8", "CP1251", "Модель"));
			if(isset($models))$year_list= CHtml::listdata($models, 'value', 'value');
			//print_r($year_list);



}/////if(isset($model)){ ////////

 
$params=array('brand_list'=>$this->brand_list,
		'brand'=>@$brand,
		'model'=>@$model,
		'model_list'=>@$model_list,
		'device_types_list'=>$this->device_types_list
) ;

if(isset($year) )$params['year'] = $year;
if(isset($kpp) )$params['kpp'] = $kpp;
if(isset($year_list) )$params['year_list'] = array('0'=>"выбор...")+$year_list;
if(isset( $kpp_list) )$params['kpp_list'] = $kpp_list;


if($target_file!=NULL) $this->render('fortus/leftpad/'.$target_file, $params);
else $this->render('fortus/leftpad/catalogleftpad', $params);
}/////function draw(){
}////class
?>