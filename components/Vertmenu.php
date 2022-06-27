<?
class Vertmenu  extends CWidget{
private $prod_char_id = 175;
function __construct(){
		
}

function Draw() {

		$criteria=new CDbCriteria;
		$criteria->order = ' t.category_name, childs.category_name';
		//$criteria->select=array('value');
		//$criteria->distinct = true;
		if(isset(Yii::app()->params['main_tree_root'])) $criteria->condition = " t.parent= ".Yii::app()->params['main_tree_root']." AND t.show_category = 1 ";
		else $criteria->condition = " t.parent= 0 AND t.show_category = 1 ";
		$criteria->order = "t.sort_category, childs.sort_category";
		//s$criteria->having = " childs.show_category = 1 ";
		
		//$criteria->params=array(':active '=>1,':section'=>1);
		
		
		$models = Catalog::model()->with('childs')->findAll($criteria);//
		$this->render('vertmenu', array('models'=>$models));

}///////////////public function Draw() {



}///////////class Vitrina {
?>


