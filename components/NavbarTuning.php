<?
class NavbarTuning  extends CWidget{
//private $prod_char_id = 175;
function __construct(){
		
}



public function draw($cont, $action) {
		//$banners=array('3_1_big.png'=>'1', '3_2_big.png'=>'2', '3_3_big.png'=>'3');
		
		$criteria=new CDbCriteria;
		$criteria->condition="t.parent=:parent AND t.show_category=:show_category AND t.alias <> '' ";
		$criteria->order = 't.sort_category';
		$criteria->params=array(':parent'=>0, 'show_category'=>1);
		$models=Categories::model()->with('childs')->findAll($criteria);
		

		
		//$this->render(Yii::app()->theme->name.'/navbar/navbar', array( 'groups'=>$models ));
		$this->render(Yii::app()->theme->name.'/navbar/navbar', array( 'cont'=>$cont, 'action'=>$action, 'models'=>$models ));
		
}///////////////public function Draw() {


public function drawCart($cont, $action) {

	$this->render(Yii::app()->theme->name.'/navbar/cart', array( 'cont'=>$cont, 'action'=>$action ));
}

}///////////class Vitrina {
?>


