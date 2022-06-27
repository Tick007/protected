<?
class SiteMenu  extends CWidget {

//class MainMenu extends CWidget

function Draw() {

		$criteria=new CDbCriteria;
		//$criteria->order = ' ';
		//$criteria->select=array('value');
		//$criteria->distinct = true;
		$criteria->condition = " t.section = 2 AND t.active = 1 AND t.front = 0 AND alais<>''  AND t.id<>6 ";
		$criteria->order = "t.sort";
		//s$criteria->having = " childs.show_category = 1 ";
		
		//$criteria->params=array(':active '=>1,':section'=>1);
		
		
		$models = Page::model()->findAll($criteria);//
		$this->render('sitemenu', array('models'=>$models));

}///////////////public function Draw() {
}
?>