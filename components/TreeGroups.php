<?php
class TreeGroups extends CWidget {

	var $targetform;
	var $targetitem;

	public function __construct($targetform, $targetitem){
		/////////



		$this->targetform = $targetform;
		$this->targetitem = $targetitem;
        
		
		$this->widget(
		    //'application.extensions.MTreeView.MTreeView',
			'CTreeView',
		array(
		    /*
		    'data'=>'[
    {
        "id": "655",
        "text": "<a onClick=\"{UpdateOpener(655)}\" href=\"#\">Вакансии</a>",
        "hasChildren": false
    },
    {
        "id": "727",
        "text": "<a onClick=\"{UpdateOpener(727)}\" href=\"#\">Мусор</a>",
        "hasChildren": false
    },
    {
        "id": "759",
        "text": "Дилеры",
        "hasChildren": true
    },
    {
        "id": "2",
        "text": "Марки",
        "hasChildren": true
    }
]',

{
  "id": "759",
  "text": "Дилеры <a onClick=\"{UpdateOpener(759)}\" href=\"#\">..select</a>",
  "hasChildren": true
}
		    */
		   /*
		    'data'=>array(
		        '0'=> array(
		              "id"=> "759",
		              "text"=> "<a onClick=\"{UpdateOpener(759)}\" href=\"#\">Дилеры</a>",
		              'hasChildren'=> true
		    ),
		        '1'=> array(
		            "id"=> "2",
		            "text"=> "<a onClick=\"{UpdateOpener(2)}\" href=\"#\">Марки</a>",
		            'hasChildren'=> true
		        ),
		    ),
		 */
		    
			'url' => array('search/ajaxfilltreegroups'),
			//'cssFile'=>(isset(Yii::app()->params['use_classic_tree']))? 'http://'.$_SERVER['HTTP_HOST'].'/css/treeviewdefault.css':'http://'.$_SERVER['HTTP_HOST'].'/themes/classic/css/GroupsTreeView.css',
		)
		);
			
	}//////////////public function __construct(){

	private function print_models($models) {
		for ($i=0; $i<count($models); $i++) {
			$treee[]=array(
			//'text'=>"<a href=\"?r=products/details&cat=".$this->brand_ids[$i]."\">".$this->brand_names[$i].'</a>',
								'text'=>CHtml::link($models[$i]->category_name, array('/nomenklatura/catgr/', 'id'=>$models[$i]->category_id, 'targetform'=>$this->targetform, 'targetitem'=>$this->targetitem)),
								'id'=>$models[$i]->category_id,
							'children'=>$this->extract_childs($models[$i]->category_id),
			);

		}/////////for ($i=0; $i<count($models); $i++) {
		return $treee;
	}///////////private function print_models($models) {


	private function extract_childs ($par_id) {
		$criteria=new CDbCriteria;
		$criteria->order = 't.category_name';
		$criteria->condition = " t.parent = ".$par_id;
		$models = Categories::model()->with('child_categories')->findAll($criteria);
		for ($i=0; $i<count($models); $i++) {
			$treee[]=array(
			//'text'=>"<a href=\"?r=products/details&cat=".$this->brand_ids[$i]."\">".$this->brand_names[$i].'</a>',
								'text'=>CHtml::link($models[$i]->category_name, array('/nomenklatura/catgr/', 'id'=>$models[$i]->category_id, 'targetform'=>$this->targetform, 'targetitem'=>$this->targetitem)),
								'id'=>$models[$i]->category_id,
			// 'children'=>$this->print_models($models[$i]->child_categories),
			);

		}/////////for ($i=0; $i<count($models); $i++) {
		return $treee;
	}


}////////////////class Tree extends CWidget {
?>