<?
class Price_list_header extends CActiveRecord {/////////////////////////

public $categories;

public static function model($className=__CLASS__)
		{
			return parent::model($className);
		}


		public function tableName()
		{
			return 'price_list_header';
		}
	
		public function relations()
		{
					return array(
				//	'authassignment' => array(self::HAS_MANY, 'Authassignment', 'itemname'),
				//	'child_items'=>array(self::MANY_MANY, 'Authitem', 'AuthItemChild(parent, child)'),
					'currencies'=> array(self::BELONGS_TO, 'Currencies', 'currency'),
					'products' => array(self::HAS_MANY, 'Price_list_products_list', 'pricelist_id'),
					);
		}


public function appendNewCat($add_category){
	if(trim($this->catpricerules)!='') {
		$rules = unserialize($this->catpricerules);
		$rules[$add_category]=array($add_category=>array(0=>array('price_from'=>'', 'price_ro'=>'', 'koef'=>'')));
		$this->catpricerules = serialize($rules);
		$this->save();
	}
}////////////public function appendNewCat($add_category){


public function selectrules(){////////////Выборка категорий
	$rules = unserialize($this->catpricerules);
	$criteria=new CDbCriteria;
	if(is_array($rules) AND empty($rules)==false) {
		$criteria->condition = "t.category_id IN(".implode(',', array_keys($rules)).")";
		//$criteria->params = array(':cont_id'=>Yii::app()->params['self_contragent']);
		$models = Categories::model()->findAll($criteria);
		
		if(isset($models)) {/////////перебираем
			for($k=0; $k<count($models); $k++) {
				$this->categories[$models[$k]->category_id]=$models[$k]->category_name;
			}//////for($k=0; $k<count($mode
			//print_r($this->categories);
		}////////if(isset($models)) {//////
	}/////if(is_array($rules) AND empty($rules)==false) {
	
}/////public function selectrules(){////////////

public function deleteCatRules($delcatrules){/////////Удаление правил
	//print_r($delcatrules);
	if(trim($this->catpricerules)!='') {
		$rules = unserialize($this->catpricerules);
		foreach ($rules as $category_id => $category_rules) {
			if (isset($delcatrules[$category_id])==false) $new_rules[$category_id] = $category_rules;
		}
		if(isset($new_rules)) $this->catpricerules = serialize($new_rules);
		else $this->catpricerules =  NULL;
		$this->save();
	}
}//////public function deleteCatRules(){/////////Уда


public function updateCatRules($catrules){
	if(isset($catrules)) {
		/*
		echo '<pre>';
		print_r($catrules);
		echo '</pre>';
		*/
		foreach ($catrules as $category_id => $category_rules) {
			//print_r($category_rules);
			$new_cat_rules = NULL;
			for($k=0; $k<count($category_rules); $k++) {
				if(isset($category_rules[$k]['delrule'])==false) $new_cat_rules[]=$category_rules[$k];
			}
			if($new_cat_rules!=NULL) $newcatrules[$category_id] = $new_cat_rules;
		}
		//print_r($newcatrules);
		//if(isset($newcatrules)) $newcatrules[0]=array();
		//print_r($newcatrules);
		$this->catpricerules =  serialize($newcatrules);
		//echo '<br><br>';
		//print_r(unserialize($this->catpricerules));
	}
	else $this->catpricerules =  NULL;
	$this->save(); 
	
}/////////public function updateCatRules(){


public function clearpricelist($store_id){
	$connection=Yii::app()->db;
	$query = "DELETE FROM price_list_products_list WHERE pricelist_id = ".$this->id;
	$command=$connection->createCommand($query)	;
	$dataReader=$command->query(); /////////////////После таког
	
	/*						
	$query = "DELETE FROM ostatki_trigers WHERE store = ".$store_id;
	$command=$connection->createCommand($query)	;
	$dataReader=$command->query(); ////
	*/
}

public static function get_positions_num($id){
	$connection=Yii::app()->db;
	$query = "SELECT COUNT(id) as countrows FROM  price_list_products_list WHERE  pricelist_id = ".$id;
	$result=$connection->createCommand($query)	;
	$count=$result->queryRow();  
	return $count['countrows'];
}

public function getnewprice($price_old, $cat){ /////////////Определяем новую цену для товара
	$newcatrules = unserialize(trim($this->catpricerules));
	
	//print_r($newcatrules);
	
	$pereocenka = $newcatrules[$cat];
	
	//print_r($pereocenka);
	
	for($i=0; $i<count($pereocenka); $i++) {
		if($price_old>$pereocenka[$i]['price_from'] AND $price_old<=$pereocenka[$i]['price_to']) {
			$newprice = round($price_old*$pereocenka[$i]['koef'], 0);
			break;
		}
	}///////for($i=0; $i<count($pereocenka); $i++) {
	
	if(isset($newprice)) return $newprice;
	else return round($price_old, 0);
	
}//////////public function getnewprice($price_with_nds){
	
public function getPriceProductsGroups(){//////////Выборка идентификаторов групп всех товаров в прайслисте
	//echo $this->id;
	$criteria=new CDbCriteria;
	//$criteria->order = 't.sort_category';
	$criteria->condition = " t.pricelist_id = :pricelist_id ";
	$criteria->params=array(':pricelist_id'=>$this->id);
	$models= Price_list_products_list::model()->with('product')->findAll($criteria);
	if(isset($models)) for($i=0; $i<count($models);$i++) $groupslist[]=$models[$i]->product->category_belong;
	
	//print_r($groupslist);
	
	//$full_tree = Categories::getfulltree();
	//echo '<pre>';
	//print_r($full_tree['levels']);
	//echo '</pre>';
	$unic_cats = array();
	 for($i=0; $i<count($groupslist);$i++) {
		 $cat_id = $groupslist[$i];
		
		 do {
			 
			  if(in_array($cat_id, $unic_cats)==false) $unic_cats[]= $cat_id;
			 if(isset($found_cats[$cat_id])==false) 	{
				 $cat = Categories::model()->findByPk( $cat_id);
			 	$found_cats[$cat_id] =  $cat->parent;
				$cat_id = $cat->parent;
			 }
			 else $cat_id = $found_cats[$cat_id];
			
			 
			//echo $cat->category_id.' - '; 
		 } while ($cat->parent>0);
		// echo '<br><br>';
	}
	
	if(isset($unic_cats)) return ($unic_cats);
	
}///////public function getPriceProductsGroups(){/////

}//////////////////// class 
?>