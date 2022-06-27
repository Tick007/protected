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
		$models=array('airon','fiac', 'tnt', 'camozzi', 'senco', 'airpol',  'kawasaki', 'omer', 'bea', 'prebena', 'fasco');	
		
		//print_r($models);	
		
		
		$this->render('wood/vendorvertspictures', array('models'=>$models));
		
}///////////////public function Draw() {


}///////////class Vitrina {
?>


