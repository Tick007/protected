
<?
class TreeContr extends CWidget {

	var $targetform;
	var $targetitem;
	
	public function __construct($targetform, $targetitem){/////////
	
				$this->targetform = $targetform;
				$this->targetitem = $targetitem;
				//$connection =  Yii::app()->db;
				$criteria=new CDbCriteria;
				$criteria->order = 't.group_name';
				$criteria->condition = " t.parent = 0";
				$models= Contr_agents_groups::model()->with('child_categories')->findAll($criteria);
				for ($i=0; $i<count($models); $i++) {
					$treee[]=array(
							//'text'=>"<a href=\"?r=products/details&cat=".$this->brand_ids[$i]."\">".$this->brand_names[$i].'</a>',
								'text'=>CHtml::link($models[$i]->group_name, array('/nomenklatura/contragents/'.$models[$i]->group_id.'?targetform='.$targetform.'&targetitem='.$targetitem)),
								'id'=>1,
							 'children'=>$this->print_models($models[$i]->child_categories),
								);			

				}/////////for ($i=0; $i<count($models); $i++) {
				
				
				

			/*	
			$treee[]=array(
							//'text'=>"<a href=\"?r=products/details&cat=".$this->brand_ids[$i]."\">".$this->brand_names[$i].'</a>',
								'text'=>CHtml::link('qqqq', array('/products/details/','cat'=>5)),
								'id'=>1,
							//	'children'=>$this->find_models($this->brand_ids[$i]),
								);			
				*/
				if(isset($treee) && $treee!=null) {
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
				}

				
			
	}//////////////public function __construct(){
		
	private function print_models($models) {
				for ($i=0; $i<count($models); $i++) {
			    		$treee[]=array(
							//'text'=>"<a href=\"?r=products/details&cat=".$this->brand_ids[$i]."\">".$this->brand_names[$i].'</a>',
								'text'=>CHtml::link($models[$i]->group_name, array('/nomenklatura/contragents/', 'id'=>$models[$i]->group_id, 'targetform'=>$this->targetform, 'targetitem'=>$this->targetitem)),
								'id'=>1,
							'children'=>$this->extract_childs($models[$i]->group_id),
								);			

						}/////////for ($i=0; $i<count($models); $i++) {
				return $treee;
	}///////////private function print_models($models) {	
		
	private function extract_childs ($par_id) {
	$criteria=new CDbCriteria;
				$criteria->order = 't.category_name';
				$criteria->condition = " t.parent = ".$par_id;
				$models= Categoriestradex::model()->with('child_categories')->findAll($criteria);
				for ($i=0; $i<count($models); $i++) {
					$treee[]=array(
							//'text'=>"<a href=\"?r=products/details&cat=".$this->brand_ids[$i]."\">".$this->brand_names[$i].'</a>',
								'text'=>CHtml::link($models[$i]->category_name),
								'id'=>1,
							 'children'=>$this->print_models($models[$i]->child_categories),
								);			

				}/////////for ($i=0; $i<count($models); $i++) {
				return $treee;
	}
		
}////////////////class Tree extends CWidget {
?>