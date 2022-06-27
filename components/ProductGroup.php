<?
class ProductGroup  extends CWidget {
public $elementname;
public $elements;
public $value;
public $tree;
public $levels;
public $mode;
public $all_groups_list;
public $formtoupdate;
//public $all_groups_list;


function __construct($elements, $elementname, $value, $mode='full', $formtoupdate=NULL){
		$this->elementname = $elementname;
		//if(isset($elements) AND $elements!=NULL) $this->elements = $elements;
		//else 
		$this->select_categories();
		$this->value = $value;
		$this->mode = $mode;
		$this->formtoupdate = $formtoupdate;
	//	$this->all_groups_list = $all_groups_list;
}


function select_categories(){ /////////////выборка групп если они не пришли.
	if(isset(Yii::app()->params['main_tree_root']) ) {
		
		
		//echo Categories::getfulltree();
		//echo  Categories::getfulltree();
		$fulltree = Categories::getfulltree();
		$this->tree = $fulltree['tree'];
		$this->levels = $fulltree['levels'];
		
		//print_r($fulltree);
		//print_r($this->tree[Yii::app()->params['main_tree_root']]);
		//echo 'levels = ';
		//echo '<pre>';
		//print_r($this->levels);
		//echo '</pre>';
		//$this->elements=Categories::model()->findAllByAttributes(array('parent'=>Yii::app()->params['main_tree_root']));
		if(isset($this->levels[Yii::app()->params['main_tree_root']]) AND empty($this->levels[Yii::app()->params['main_tree_root']])==false) $this->elements = $this->levels[Yii::app()->params['main_tree_root']];
		elseif(Yii::app()->params['main_tree_root']==0) {
			$tmp_arr=Array();
			
			//echo '<pre>';
			//print_r($this->tree); 
			//echo '</pre>';
			
			
			
			
			foreach($this->tree  as $cat_id =>$cat) {
				$tmp_arr['children'][$cat_id] = $cat;
			}
			//  $this->elements = $this->levels;
			 $this->elements = $tmp_arr;
			
		}
		
		//print_r($this->elements);
		
	}
}///////function select_categories(){ ///////////

function Draw() {
	
	
	if($this->mode=='full') $this->render(Yii::app()->theme->name.'/productgroup/product_groups');
	elseif($this->mode=='simple') $this->render(Yii::app()->theme->name.'/productgroup/product_groups_simple');

}///////////////public function Draw() {


public function childs($parent_id){
	
	//print_r($this->levels[$parent_id]);
	
	
	$str = '<ul>';
	foreach ($this->levels[$parent_id]['children'] as $category_id=>$group) {
	$str.='<li rel="'.$category_id.'">';
	$str.=$group['category_name'];
	if(isset($this->levels[$category_id]) AND empty($this->levels[$category_id])==false) $str.=$this->childs($group['category_id']);
	$str.= '</li>';
	}
	$str.='</ul>';
	return $str;
}

public function childs_simple($parent_id, $prefix){
	
	//print_r($this->levels[$parent_id]);
	
	
	foreach ($this->levels[$parent_id]['children'] as $category_id=>$group) {
		$this->all_groups_list[$category_id] = 	$prefix.$group['category_name'];
		if(isset($this->levels[$category_id]) AND empty($this->levels[$category_id])==false) $this->childs_simple($group['category_id'], $prefix.'-');

	}
}


}///////////class Vitrina {
?>


