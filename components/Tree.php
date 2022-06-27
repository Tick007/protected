
<?
class Tree extends CWidget {

	var $targetform;
	var $targetitem;
	public $tree;
	public $levels;
	
	public function __construct($targetform, $targetitem){/////////
	/*
				$this->targetform = $targetform;
				$this->targetitem = $targetitem;
				//$connection =  Yii::app()->db;
				$criteria=new CDbCriteria;
				$criteria->order = 't.category_name';
				$criteria->condition = " t.parent = 0";
				$models= Categoriestradex::model()->with('child_categories')->findAll($criteria);
				
				
				*/
				
				
				$fulltree = Categories::getfulltree();
				
				$this->tree = $fulltree['tree'];
				$this->levels = $fulltree['levels'];
				
				
				
				foreach($this->tree AS $cat_id=>$cat) {
					$treee[]=array(
							//'text'=>"<a href=\"?r=products/details&cat=".$this->brand_ids[$i]."\">".$this->brand_names[$i].'</a>',
								'text'=>CHtml::link($cat['category_name'], array('/nomenklatura/cat/', 'id'=>$cat_id, 'targetform'=>$this->targetform, 'targetitem'=>$this->targetitem)),
								'id'=>$cat_id,
							// 'children'=>$this->print_models($this->levels[$cat_id]['children']),
								);			

				}/////////for ($i=0; $i<count($models); $i++) {
				
				//echo '<pre>';
				//print_r($levels);			
				//echo '</pre>';	
				
				

				



			$this->widget(
			'CTreeView',
			array(
			//'url' => array('ajaxFillTree'),//////////////////При использовании ажакса не запоминает открытые узлы
			'data'=>$treee, // передаем массив
   		    'animated'=>'fast', // скорость анимации свертывания/развертывания
   		     'collapsed'=>true, // если тру, то при генерации дерева, все его узлы будут свернуты
    		  'persist'=>'cookie',
			   'unique'=>true)
			);
			
			
	}//////////////public function __construct(){
		
	private function print_models($children) {
		
				/*
				echo '<pre>';
				print_r($children);			
				echo '</pre>';	
				*/
				
				foreach($children AS $cat_id=>$cat) {
			    		$treee[]=array(

								'text'=>CHtml::link($cat['category_name'], array('/nomenklatura/cat/', 'id'=>$cat_id, 'targetform'=>$this->targetform, 'targetitem'=>$this->targetitem)),
								'id'=>$cat_id,
							//'children'=>$this->print_models($this->levels[$cat_id]['children']),
								);			

						}/////////for ($i=0; $i<count($models); $i++) {
							
				if(empty($treee)==false)return $treee;
				else return NULL;
	}///////////private function print_models($models) {	
		
	private function extract_childs ($children) {
				$criteria=new CDbCriteria;
				$criteria->order = 't.category_name';
				$criteria->condition = " t.parent = ".$par_id;
				$models= Categoriestradex::model()->with('child_categories')->findAll($criteria);
				//echo $par_id.'<br>';
				//echo count($models);
				//exit();
				
				for ($i=0; $i<count($models); $i++) {
					$treee[]=array(
							//'text'=>"<a href=\"?r=products/details&cat=".$this->brand_ids[$i]."\">".$this->brand_names[$i].'</a>',
								'text'=>CHtml::link($models[$i]->category_name, array('/nomenklatura/cat/', 'id'=>$models[$i]->category_id, 'targetform'=>$this->targetform, 'targetitem'=>$this->targetitem)),
								'id'=>1,
							 'children'=>$this->print_models($models[$i]->child_categories),
								);			

				}/////////for ($i=0; $i<count($models); $i++) {
				if(empty($treee)==false)return $treee;
				else return NULL;
	}
		
}////////////////class Tree extends CWidget {
?>