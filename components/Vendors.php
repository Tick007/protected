<?
class Vendors  extends CWidget{
private $prod_char_id;
function __construct(){
		$this->prod_char_id = Yii::app()->params['vendor_char_id'];
}

function Draw() {

		$criteria=new CDbCriteria;
		$criteria->order = ' t.value';
		$criteria->select=array('value');
		$criteria->distinct = true;
		$criteria->condition = " t.id_caract = ". $this->prod_char_id;
		if($_SERVER['HTTP_HOST']=='pnevmoinstrument.ru') $criteria->condition.= " AND  t.value <> 'Kawasaki'
AND  t.value <> 'Ear-flap'
AND  t.value <> 'Ekamant'
AND  t.value <> 'Airpol'
AND  t.value <> 'Конаково'
AND  t.value <> 'JTC'
AND  t.value <> 'MAX'
AND  t.value <> 'PKG'
AND  t.value <> 'Pozitiv'
AND  t.value <> 'Temar'
AND  t.value <> 'UPT(URYU)'
AND  t.value <> 'Extend Great Intettrn Corp' 
AND  t.value <> 'Extend Great International Corp. ' ";
		//$criteria->params=array(':active '=>1,':section'=>1);
		
		
		$models = Characteristics_values::model()->findAll($criteria);//
		$this->render(Yii::app()->theme->name.'/vendors', array('models'=>$models));

}///////////////public function Draw() {


function DrawWithPictures() {

		$criteria=new CDbCriteria;
		$criteria->order = ' t.value';
		$criteria->select=array('value');
		$criteria->distinct = true;
		$criteria->condition = " t.id_caract = ".$this->prod_char_id;
		//$criteria->params=array(':active '=>1,':section'=>1);
		
		
		$models = Characteristics_values::model()->findAll($criteria);//
		$this->render('vendorspictures', array('models'=>$models));
		
}///////////////public function Draw() {


function DrawVertPictures() {

		/*
		$criteria=new CDbCriteria;
		$criteria->order = ' t.value';
		$criteria->select=array('value');
		$criteria->distinct = true;
		$criteria->condition = " t.id_caract = ".$this->prod_char_id;
		//$criteria->params=array(':active '=>1,':section'=>1);
		$models = Characteristics_values::model()->findAll($criteria);//
		*/
		$models=array('airon','fiac', 'tnt', 'camozzi', 'senco', 'airpol', 'omer', 'bea',  'fasco', 'bosch');	
		
		//print_r($models);	
		
		
		$this->render('wood/vendorvertspictures', array('models'=>$models));
		
}///////////////public function Draw() {


}///////////class Vitrina {
?>


