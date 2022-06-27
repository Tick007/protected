<?
class Stores extends CActiveRecord {/////////////////////////

public static function model($className=__CLASS__)
		{
			return parent::model($className);
		}


		public function tableName()
		{
			return 'stores';
		}
	
		public function relations()
		{
					return array(
				//	'authassignment' => array(self::HAS_MANY, 'Authassignment', 'itemname'),
				//	'child_items'=>array(self::MANY_MANY, 'Authitem', 'AuthItemChild(parent, child)'),
					//'currencies'=> array(self::BELONGS_TO, 'Currencies', 'currency'),
				//	'products' => array(self::HAS_MANY, 'Price_list_products_list', 'pricelist_id'),
					);
		}


		
		public function updatepricerules($rules){////////Сохранение новых правил ценообразовани я
			for($i=0; $i<count($rules); $i++) {
				if(isset($rules[$i]['delrule'])==false) $newrules[] = $rules[$i];
			}
			$this->pricerules = serialize($newrules);

			//$this->pricerules = NULL;
			$this->save();		
		}////////public function updatepricerules($rules){////////Сох
		
		
public function getnewprice($price_old){ /////////////Определяем новую цену для товара
	$pereocenka = unserialize($this->pricerules);
	
	//print_r($pereocenka);
	//echo $price_old.' - ';
	
	for($i=0; $i<count($pereocenka); $i++) {
		if($price_old>$pereocenka[$i]['price_from'] AND $price_old<=$pereocenka[$i]['price_to']) {
			$newprice = round($price_old*str_replace(',', '.', $pereocenka[$i]['koef']), 0);
			break;
		}
	}///////for($i=0; $i<count($pereocenka); $i++) {
	
	//echo $newprice.'/n/r';
	
	if(isset($newprice)) return $newprice;
	else return round($price_old, 0);
	
}//////////public function getnewprice($price_with_nds){
		
}//////////////////// class 
?>