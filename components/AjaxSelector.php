<?
class AjaxSelector  extends CWidget{
var $brand_list;


function __construct(){
			
			$criteria=new CDbCriteria;
			$criteria->order = 't.sort_category';
			$criteria->condition = " t.parent =  :root AND t.show_category = 1  ";
			$criteria->params=array(':root'=>Yii::app()->params['main_tree_root']);
			$first_tree = Categories::model()->with('child_categories')->findAll($criteria);//
			if (isset($first_tree)) {
				$brand_list=CHtml::listData($first_tree,'category_id','category_name');
				$this->brand_list = array('0'=>iconv( "CP1251", "UTF-8", "Марка автомобиля"))+$brand_list;
				
			}
}////function __construct(){

function Draw() {
		
		$alias =  Yii::app()->getRequest()->getParam('alias');
		
		if (isset($alias)) {
			
		$cat = Categories::model()->findByAttributes(array('alias'=>$alias));
			if (isset($cat)) $brand = $cat->parent;
			//$model = $cat->category_id;
			$this_array_path = array( 'path'=>FHtml::urlpath($cat->path), 'alias'=>$cat->alias);
			$this_url = urldecode(Yii::app()->createAbsoluteUrl('constructcatalog/group', $this_array_path));
			$model = $this_url;
			
			$criteria=new CDbCriteria;
			$criteria->order = 't.sort_category';
			$criteria->condition = " t.parent =  :root AND t.show_category = 1  ";
			$criteria->params=array(':root'=>$brand);
			$models = Categories::model()->with('child_categories')->findAll($criteria);//
			if (isset($models)) {
				//$model_list=CHtml::listData($first_tree,'category_id','category_name');
				for($k=0; $k<count($models); $k++) {
					$path_array=  array( 'path'=>FHtml::urlpath($models[$k]->path), 'alias'=>$models[$k]->alias);
					$url= urldecode(Yii::app()->createAbsoluteUrl('constructcatalog/group', $path_array));
					$model_list[$url]=$models[$k]->category_name;
				}
				$model_list = array('0'=>iconv( "CP1251", "UTF-8", "Выберете модель"))+$model_list;
				
			}
			
		
		}
		else $model_list = array('0'=>iconv( "CP1251", "UTF-8", "Модель автомобиля"));
		$this->render('ajaxselector', array('brand_list'=>$this->brand_list, 'brand'=>@$brand, 'model'=>$model, 'model_list'=>@$model_list) );

}///////////////public function Draw() {



}///////////class Vitrina {
?>


