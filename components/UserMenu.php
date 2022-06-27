<?
//Yii::import('zii.widgets.CPortlet');
 
class UserMenu extends CWidget {

 
    protected function Draw()
    {
		
		$criteria=new CDbCriteria;
		$criteria->order = 't.sort_category';
		$criteria->condition = " t.parent =:root AND t.show_category =  1  ";
		$criteria->params=array(':root'=>Yii::app()->params['second_tree_root']);
		$models = Categories::model()->findAll($criteria);//
        $this->render('userMenu',  array('models'=>$models));
    }
	
	
	public function LeftFiles() {
			$this->render('leftfiles');
	}
	
	
}
?>