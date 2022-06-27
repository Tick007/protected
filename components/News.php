<?
class News  extends CWidget{
var $menu_levels;
private $show_group;
public $models;

function __construct(){
		
}

function Draw($view, $section=1) {

		$criteria=new CDbCriteria;
		$criteria->order = ' t.creation_date DESC LIMIT 0, 10';
		$criteria->condition = " t.active= 1 AND t.section= ".$section;
		//$criteria->params=array(':active '=>1,':section'=>1);
		
		
		$models = Page::model()->findAll($criteria);//
		$this->render($view, array('models'=>$models));

}///////////////public function Draw() {



}///////////class Vitrina {
?>


