<?
class Vitrina   extends CWidget {
var $menu_levels;
private $show_group;
public $models;
//var $title;

function __construct($title=''){ 
		//$this->title = $title;
}

function Draw() {

$ProductList=new Product;
$ProductList->product_vitrina = 1;
$ProductList->creteria = " AND parent_categories.show_category = 1";
$ProductList->ExecuteObject(); ////////////перенес из констракта в отдельный метод
$ProductList->offset = 0;
$ProductList->limit = 10;
$models = $ProductList->run_query();		

$this->render('vitrina', array('models'=>$models));		
		


}///////////////public function Draw() {

function DrawSmall($view) {

$ProductList=new Product;
$ProductList->product_vitrina = 1;
$ProductList->creteria = " AND parent_categories.show_category = 1  ";
$ProductList->orderby = " product_vitrina_sort  ";
$ProductList->offset = 0;
$ProductList->limit = 50; 
$ProductList->ExecuteObject(); ////////////перенес из констракта в отдельный метод

$models = $ProductList->run_query();		///////////////Тут нет информации о группах для построения ссылки

/*
if(isset($models)) {
	foreach($models as $record){
		$product_ids[] = $record['id'];
	}
	if(isset($product_ids)){
		
	}
}
*/


$this->render($view, array('models'=>$models));		
}///////////////public function Draw() {


function DrawPsg($view=NULL) {

$ProductList=new Product;
$ProductList->product_sellout = 1; 
//$ProductList->creteria = " AND parent_categories.show_category = 1 ";
//echo $ProductList->query;
$ProductList->offset = 0;
$ProductList->limit = 10;
$ProductList->orderby = " RAND()";
$ProductList->ExecuteObject(); ////////////перенес из констракта в отдельный метод

$models = $ProductList->run_query();		
if(isset($view))$this->render($view, array('models'=>$models));		
else $this->render('vitrinapsg', array('models'=>$models));		
}///////////////public function Draw() {


function DrawCarusel($group=NULL, $view=NULL, $title=NULL){

$product_id = Yii::app()->params['fortus_carusel'];

$criteria=new CDbCriteria;

 $criteria->condition="picture_product.product = ".$product_id;
 // $criteria->order=" t.product_vitrina_sort ";

// $criteria->params=array(':id_caract'=>$this->year_char_id);
$pictures = Pictures::model()->with('picture_product')->findAll($criteria);

$this->render($view, array('title'=>$title, 'pictures'=>$pictures));	
}/////////function DrawCarusel($group=NUL

function DrawBogajniki($group=NULL, $view=NULL, $title=NULL){

$criteria=new CDbCriteria;
 $criteria->select=array( 't.*',  'CONCAT_WS(".", picture_product.picture, picture_product.ext) AS icon', 'picture_product.picture AS icon_id' , 'picture_product.comments AS attribute_value');
 $criteria->condition="t.product_vitrina =1 ";
  $criteria->order=" t.product_vitrina_sort ";
 $criteria->join =" LEFT JOIN ( SELECT picture_product.id, product, picture, ext, comments FROM picture_product JOIN pictures ON pictures.id=picture_product.picture WHERE is_vitrina=1) picture_product ON picture_product.product = t.id  ";
 $criteria->addCondition("t.product_visible = 1 AND picture_product.picture IS NOT NULL ");
 if ($group!=NULL) {
$criteria->addCondition ( "t. 	category_belong 	 = :category_belong  ");
$criteria->params =  array('category_belong'=>$group);
}
$criteria->limit = 10;
// $criteria->params=array(':id_caract'=>$this->year_char_id);
 $products = Products::model()->with('char_val', 'belong_category')->findAll($criteria);
//$products = Products::model()->findAll($criteria);
if (isset($products)) {
		

	
		for ($i=0; $i<count($products); $i++) $productslist[]=$products[$i]->id;
		if (isset($productslist)) {
		//print_r($productslist);
		$connection = Yii::app()->db;
		$query = "SELECT id_product, GROUP_CONCAT(CONCAT_WS( ';#;', value,id_caract ) ORDER BY characteristics.sort SEPARATOR '#;#') AS  attribute_value2 FROM characteristics_values JOIN characteristics ON characteristics.caract_id = characteristics_values.id_caract WHERE id_product IN (".implode(',', $productslist).")  GROUP BY id_product ORDER BY   characteristics_values.value_id";////////////
		//echo $query;
		$command=$connection->createCommand($query)	;
		$dataReader=$command->query();
		$records=$dataReader->readAll();////

					if (isset($records)) {
								for($i=0; $i<count($records); $i++) $products_attributes[$records[$i]['id_product']]=$records[$i]['attribute_value2'];
										}
										
										//echo '<br>';
										//print_r($products_attributes);
					 }
						
						
					
			
			/////////////////////////////////////////Вытаскиваем все характеристики, доступные для этой группы
			$criteria=new CDbCriteria;
			//$criteria->order = "characteristics_categories.sort, t.char_type , t.caract_name ";
			$characteristics1 = Characteristics::model()->findAll($criteria);
			for ($i=0; $i<count($characteristics1); $i++) {
					$characteristics_array[$characteristics1[$i]->caract_id]=array('char_type'=>4,  'caract_name'=>$characteristics1[$i]->caract_name, 'is_main'=>$characteristics1[$i]->is_main);
			}//////////for ($i=0; $i<count($characteristics); $i++) {

if(isset($view) AND $view!= NULL) $this->render($view, array('characteristics_array'=>@$characteristics_array, 'products_attributes'=>@$products_attributes, 'products'=>@$products, 'title'=>$title));	
else  $this->render($view, array('characteristics_array'=>@$characteristics_array, 'products_attributes'=>@$products_attributes, 'products'=>@$products, 'title'=>$title));	
}/////////if (isset($products)) {


}//////function DrawBogajniki(){


function DrawBogajnikiNew($group=NULL, $view=NULL, $title=NULL, $limit=NULL){
$criteria=new CDbCriteria;
 $criteria->select=array( 't.*',  'CONCAT_WS(".", picture_product.picture, picture_product.ext) AS icon', 'picture_product.picture AS icon_id' , 'picture_product.comments AS attribute_value');
 $criteria->condition="t.product_new =1 ";
  $criteria->order=" t.product_new_sort  ";
 $criteria->join =" LEFT JOIN ( SELECT picture_product.id, product, picture, ext, comments FROM picture_product JOIN pictures ON pictures.id=picture_product.picture WHERE is_new=1) picture_product ON picture_product.product = t.id  ";
 $criteria->addCondition("t.product_visible = 1 AND picture_product.picture IS NOT NULL " );
 if($limit!=NULL)  $criteria->limit = $limit;
 if ($group!=NULL) {
$criteria->condition = "t.caract_category 	 = :caract_category  ";
$criteria->params =  array('caract_category'=>$group);
}
// $criteria->params=array(':id_caract'=>$this->year_char_id);
 $products = Products::model()->with('char_val', 'belong_category')->findAll($criteria);

if (isset($products)) {
		

	
		for ($i=0; $i<count($products); $i++) $productslist[]=$products[$i]->id;
		if (isset($productslist) AND is_array($productslist)==true AND empty($productslist)==false) {
		//print_r($productslist);
		$connection = Yii::app()->db;
		$query = "SELECT id_product, GROUP_CONCAT(CONCAT_WS( ';#;', value,id_caract ) ORDER BY characteristics.sort  SEPARATOR '#;#') AS  attribute_value2 FROM characteristics_values JOIN characteristics ON characteristics.caract_id = characteristics_values.id_caract WHERE id_product IN (".implode(',', $productslist).")  GROUP BY id_product  ";////////////
		//echo $query;
		$command=$connection->createCommand($query)	;
		$dataReader=$command->query();
		$records=$dataReader->readAll();////

					if (isset($records)) {
								for($i=0; $i<count($records); $i++) $products_attributes[$records[$i]['id_product']]=$records[$i]['attribute_value2'];
										}
				
										//print_r($products_attributes);
					 }
						
						
					
			
			/////////////////////////////////////////Вытаскиваем все характеристики, доступные для этой группы
			$criteria=new CDbCriteria;
			//$criteria->order = "characteristics_categories.sort, t.char_type , t.caract_name ";
			$characteristics1 = Characteristics::model()->findAll($criteria);
			for ($i=0; $i<count($characteristics1); $i++) {
					$characteristics_array[$characteristics1[$i]->caract_id]=array('char_type'=>4,  'caract_name'=>$characteristics1[$i]->caract_name, 'is_main'=>$characteristics1[$i]->is_main);
			}//////////for ($i=0; $i<count($characteristics); $i++) {
			
			
if(isset($view) AND $view!= NULL) $this->render($view, array('characteristics_array'=>@$characteristics_array, 'products_attributes'=>@$products_attributes, 'products'=>@$products, 'title'=>$title));	
else  $this->render($view, array('characteristics_array'=>@$characteristics_array, 'products_attributes'=>@$products_attributes, 'products'=>@$products, 'title'=>$title));	
}/////////if (isset($products)) {


}//////function DrawBogajniki(){
	

/**
 * Сделал универсальную функцию для выборки соответствующих фотоко продуктов из БД
 * @param string $view Имя файла для рендерв в папке темы в компонентс/вьюс
 * @param string $title Заголовок блока
 * @param string $limit Ограничение, сколько выбирать из БД товаров
 * @param PictureType = тип извлекаемых для товара картинок
 * @return Ничего
 */
function DrawUniversal($view, $title=NULL, $limit=10, $pic_type){
	
	
	$criteria=new CDbCriteria;
	$criteria->select=array( 't.*',  'CONCAT_WS(".", picture_product.picture, picture_product.ext) AS icon', 'picture_product.picture AS icon_id' ,
	'picture_product.comments AS attribute_value');
	//$criteria->condition=" sellout_price>0 AND t.number_in_store>0";
	if($pic_type==PictureType::issellout) $criteria->condition=" sellout_price>0 ";
	if($pic_type==PictureType::isnew){
		$criteria->addCondition("t.product_new =1 ", 'AND');
		$criteria->order=" t.product_new_sort ";
		$picture_product_column = "picture_product.is_new";
	}
	if($pic_type==PictureType::ishit){
		$criteria->addCondition("t.vitrina_key_1 =1 ", 'AND');
		$criteria->order=" t.vitrina_key_1_sort ";
		$picture_product_column = "picture_product.vitrina_key_1";
	}
	if($pic_type==PictureType::isvitrina){
		$criteria->addCondition("t.product_vitrina =1 ", 'AND');
		$criteria->order=" t.product_vitrina_sort ";
		$picture_product_column = "picture_product.is_vitrina";
	}
	if($pic_type==PictureType::issellout){
		$criteria->addCondition("t.product_sellout =1 ", 'AND');
		$criteria->order=" t.product_sellout_sort ";
		$picture_product_column = "picture_product.is_sellout";
	}
	if($pic_type==PictureType::islastincome){
		//$criteria->addCondition("t.product_sellout =1 ", 'AND');
		$criteria->order=" t.id DESC ";
		$picture_product_column = "picture_product.is_main";
	}
	 $criteria->join =" LEFT JOIN ( SELECT picture_product.id, product, picture, ext,  pictures.comments FROM picture_product JOIN pictures ON 
			pictures.id=picture_product.picture WHERE ".$picture_product_column."=1) picture_product ON picture_product.product = t.id  ";
	$criteria->addCondition("t.product_visible = 1 AND picture_product.picture IS NOT NULL AND belong_category.alias <>''" );
	if($limit!=NULL)  $criteria->limit =$limit;
	/*
	if ($group!=NULL) {
		$criteria->condition = "t.caract_category 	 = :caract_category  ";
		$criteria->params =  array('caract_category'=>$group);
	
	}
	*/
	//$criteria->addCondition(" (t.sellout_active_till_int) > ". time()." ");
	
	// $criteria->params=array(':id_caract'=>$this->year_char_id);
	$products = Products::model()->with('belong_category')->findAll($criteria);
	/*
	echo '<pre>';
	print_r($products);
	echo '</pre>';
	*/
	
	if (isset($products) AND empty($products)==false) {
	
		foreach ($products as $product){
			$models[$product->id]=array(
					'name'=>$product->product_name,
					'price'=>$product->product_price,
					'price_old'=>$product->product_price_old,
					'category'=>$product->category_belong,
					'category_alias'=>$product->belong_category->alias,
					'icon'=>$product->icon,
					'icon_id'=>$product->icon_id,
					'product_sellout'=>$product->product_sellout,
					'sellout_price'=>$product->sellout_price,
					'new_product'=>$product->new_product,
					
			);
			
			////Дообрабатываем цены
			if($models[$product->id]['product_sellout']==1 && $models[$product->id]['sellout_price']>0) {
				$models[$product->id]['price_old'] =$models[$product->id]['price'];
				$models[$product->id]['price']=$models[$product->id]['sellout_price'];
					
			}
			
		}
		$products = null;
	}/////////if (isset($products)) {

	$render_params = array(
			'title'=>$title,
	);
	if(isset($models)) $render_params['products']=$models;

	$this->render(Yii::app()->theme->name.'/vitrina/'.$view, $render_params);
}
	
function DrawSellout($group=NULL, $view=NULL, $title=NULL, $limit=NULL){

$criteria=new CDbCriteria;
 $criteria->select=array( 't.*',  'CONCAT_WS(".", picture_product.picture, picture_product.ext) AS icon', 'picture_product.picture AS icon_id' , 'picture_product.comments AS attribute_value');
 $criteria->condition="t.product_sellout =1  AND sellout_price>0 AND t.number_in_store>0";
 $criteria->order=" t.product_sellout_sort ";
 $criteria->join =" LEFT JOIN ( SELECT picture_product.id, product, picture, ext,  pictures.comments FROM picture_product JOIN pictures ON pictures.id=picture_product.picture WHERE is_sellout=1) picture_product ON picture_product.product = t.id  ";
 $criteria->addCondition("t.product_visible = 1 AND picture_product.picture IS NOT NULL " );
 if($limit!=NULL)  $criteria->limit =$limit;
 if ($group!=NULL) {
$criteria->condition = "t.caract_category 	 = :caract_category  ";
$criteria->params =  array('caract_category'=>$group);

}

 $criteria->addCondition(" (t.sellout_active_till_int) > ". time()." ");

// $criteria->params=array(':id_caract'=>$this->year_char_id);
 $products = Products::model()->with('char_val', 'belong_category')->findAll($criteria);

if (isset($products) AND empty($products)==false) {
		

	
		for ($i=0; $i<count($products); $i++) $productslist[]=$products[$i]->id;
		if (isset($productslist) AND is_array($productslist)==true AND empty($productslist)==false) {
		//print_r($productslist);
		$connection = Yii::app()->db;
		$query = "SELECT id_product, GROUP_CONCAT(CONCAT_WS( ';#;', value,id_caract ) ORDER BY characteristics.sort  SEPARATOR '#;#') AS  attribute_value2 FROM characteristics_values JOIN characteristics ON characteristics.caract_id = characteristics_values.id_caract WHERE id_product IN (".implode(',', $productslist).")  GROUP BY id_product  ";////////////
		//echo $query;
		$command=$connection->createCommand($query)	;
		$dataReader=$command->query();
		$records=$dataReader->readAll();////

					if (isset($records)) {
								for($i=0; $i<count($records); $i++) $products_attributes[$records[$i]['id_product']]=$records[$i]['attribute_value2'];
										}
				
										//print_r($products_attributes);
					 }
						
						
					
			
			/////////////////////////////////////////Вытаскиваем все характеристики, доступные для этой группы
			$criteria=new CDbCriteria;
			//$criteria->order = "characteristics_categories.sort, t.char_type , t.caract_name ";
			$characteristics1 = Characteristics::model()->findAll($criteria);
			for ($i=0; $i<count($characteristics1); $i++) {
					$characteristics_array[$characteristics1[$i]->caract_id]=array('char_type'=>4,  'caract_name'=>$characteristics1[$i]->caract_name, 'is_main'=>$characteristics1[$i]->is_main);
			}//////////for ($i=0; $i<count($characteristics); $i++) {
			

			
if(isset($view) AND $view!= NULL) $this->render($view, array('characteristics_array'=>@$characteristics_array, 'products_attributes'=>@$products_attributes, 'products'=>@$products, 'title'=>$title));	
else  $this->render($view, array('characteristics_array'=>@$characteristics_array, 'products_attributes'=>@$products_attributes, 'products'=>@$products, 'title'=>$title));	
}/////////if (isset($products)) {


}//////function DrawSellout

function DrawHits($group=NULL, $view=NULL, $title=NULL, $limit=NULL){

$criteria=new CDbCriteria;
  $criteria->select=array( 't.*',  'CONCAT_WS(".", picture_product.picture, picture_product.ext) AS icon', 'picture_product.picture AS icon_id' , 'picture_product.comments AS attribute_value');
 $criteria->condition="t.vitrina_key_1 =1  AND t.number_in_store>0";
  $criteria->order=" t.vitrina_key_1_sort ";
 $criteria->join =" LEFT JOIN ( SELECT picture_product.id, product, picture, ext,  pictures.comments FROM picture_product JOIN pictures ON pictures.id=picture_product.picture WHERE vitrina_key_1) picture_product ON picture_product.product = t.id  ";
 $criteria->addCondition("t.product_visible = 1 AND picture_product.picture IS NOT NULL " );
 if($limit!=NULL)  $criteria->limit =$limit;
 if ($group!=NULL) {
$criteria->condition = "t.caract_category 	 = :caract_category  ";
$criteria->params =  array('caract_category'=>$group);

}

// $criteria->addCondition(" (t.sellout_active_till_int) > ". time()." ");

// $criteria->params=array(':id_caract'=>$this->year_char_id);
 $products = Products::model()->with('char_val', 'belong_category')->findAll($criteria);

if (isset($products) AND empty($products)==false) {
		

	
		for ($i=0; $i<count($products); $i++) $productslist[]=$products[$i]->id;
		if (isset($productslist) AND is_array($productslist)==true AND empty($productslist)==false) {
		//print_r($productslist);
		$connection = Yii::app()->db;
		$query = "SELECT id_product, GROUP_CONCAT(CONCAT_WS( ';#;', value,id_caract ) ORDER BY characteristics.sort  SEPARATOR '#;#') 
				AS  attribute_value2 FROM characteristics_values JOIN characteristics ON
				characteristics.caract_id = characteristics_values.id_caract WHERE id_product IN (".implode(',', $productslist).")  
						GROUP BY id_product  ";////////////
		//echo $query;
		$command=$connection->createCommand($query)	;
		$dataReader=$command->query();
		$records=$dataReader->readAll();////

					if (isset($records)) {
								for($i=0; $i<count($records); $i++) $products_attributes[$records[$i]['id_product']]=$records[$i]['attribute_value2'];
										}
				
										//print_r($products_attributes);
					 }
						
						
					
			
			/////////////////////////////////////////Вытаскиваем все характеристики, доступные для этой группы
			$criteria=new CDbCriteria;
			//$criteria->order = "characteristics_categories.sort, t.char_type , t.caract_name ";
			$characteristics1 = Characteristics::model()->findAll($criteria);
			for ($i=0; $i<count($characteristics1); $i++) {
					$characteristics_array[$characteristics1[$i]->caract_id]=array('char_type'=>4,  'caract_name'=>$characteristics1[$i]->caract_name, 'is_main'=>$characteristics1[$i]->is_main);
			}//////////for ($i=0; $i<count($characteristics); $i++) {
			

			
if(isset($view) AND $view!= NULL) $this->render($view, array('characteristics_array'=>@$characteristics_array, 'products_attributes'=>@$products_attributes, 'products'=>@$products, 'title'=>$title));	
else  $this->render($view, array('characteristics_array'=>@$characteristics_array, 'products_attributes'=>@$products_attributes, 'products'=>@$products, 'title'=>$title));	
}/////////if (isset($products)) {


}//////function DrawHits
	
	


}///////////class Vitrina {
?>


