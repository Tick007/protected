<?
class TreeServices extends CWidget {

	var $targetform;
	var $targetitem;
	var $tree;
	var $levels;
	var $all_cats;
	var $CAT;
	var $opened;/////////////////массив, в котором будут хранится открытые директории
	var $manual_open;

	public function __construct($targetform=NULL, $targetitem=NULL){
		/////////

		$this->targetform = $targetform;
		$this->targetitem = $targetitem;

	}//////////////public function __construct(){

	public function SetTree(&$tree, &$levels, &$all_cats ){

		$this->tree = &$tree;
		$this->levels = &$levels;
		$this->all_cats = &$all_cats;

	}

	public function Draw() {
		$connection = Yii::app()->db;
		$query = "SELECT t.category_id,
			t.category_name, 
			t.alias,
			t.parent
			FROM `categories` `t`  WHERE t.show_category = 1 ";
		$query.= "  GROUP BY t.category_id";
		$query.= " ORDER BY t.sort_category";
			
		$command=$connection->createCommand($query)	;
		$dataReader=$command->query();
		$records=$dataReader->readAll();////
		foreach ($records as $k=>$v) {
			$current['parent_id'] = $v['parent'];
			$current['name'] = $v['category_name'];
			$current['category_id'] = $v['category_id'];
			$current['alias'] = $v['alias'];
			if ( $v['parent'] == 0){
				$this->tree[ $v['category_id'] ] = $current;
			} else {
				$this->levels[$v['parent']]['children'][$v['category_id']] = $current;
			}
		}///////////foreach ($records as $k=>$v) {

		/*
		 echo '<pre>';
		print_r($this->tree);
		echo '</pre>';

		$sum = NULL;
		$arr = $this->levels[$parent_id];
		if (count($arr[children])>0) {
		$sum[1]= count($arr[children]);
		foreach ($arr[children] as $parent_id=>$tree) {
		$qqq=$this->show_vetv($parent_id);
		$sum[0] = $sum[0] + $qqq[0];
		$sum[0] = $sum[0] + $tree[products];
		}//////////
		}
		return $sum;
		*/
		$models  = $this->tree;
		foreach ($models as $category_id=>$cat) {
			$treee[]=array(
			//'text'=>"<a href=\"?r=products/details&cat=".$this->brand_ids[$i]."\">".$this->brand_names[$i].'</a>',
			//'text'=>CHtml::link($models[$i]->name.' '.$models[$i]->socr, array('/nomenklatura/contragents/'.$models[$i]->code.'?targetform='.$targetform.'&targetitem='.$targetitem)),
								'text'=>CHtml::link($cat['name'], array('site/category/', 'alias'=>$cat['alias'])),
			//'expanded' => false,
								'id'=>$category_id,
								'children'=>$this->children($category_id),
			);

		}/////////for ($i=0; $i<count($models); $i++) {


		echo "<div class=\"leftpanelblock\">";
		echo "<div class=\"blockheader\">Каталог</div>";
			
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

		echo "</div>";
	}//////////////draw

	public function returnonecategory($parent, $categody_id){
		/////////////Возвращаем категорию - нужно для определения макс и мин цены
		if(isset($this->levels[$parent]))return $this->levels[$parent]['children'][$categody_id];
		else {
			//echo $categody_id;
			//print_r($this->tree[$categody_id]);
			return $this->tree[$categody_id];
		}
		//return $categody_id;
	}/////////////public function returnonecategory($categody_id){/////////////Возвращаем ка

	private function children($parent_id) {
		//echo $parent_id.'<br>';
		if (@isset($this->levels[$parent_id])) {
			$models = $this->levels[$parent_id];
			//echo '<pre>';
			//print_r($models);
			//echo '</pre>';
			foreach ($models['children'] as $category_id=>$cat) {
				$treee[]=array(
				//'text'=>"<a href=\"?r=products/details&cat=".$this->brand_ids[$i]."\">".$this->brand_names[$i].'</a>',
				//'text'=>CHtml::link($models[$i]->name.' '.$models[$i]->socr, array('/nomenklatura/contragents/'.$models[$i]->code.'?targetform='.$targetform.'&targetitem='.$targetitem)),
								'text'=>CHtml::link($cat['name'], array('site/category/', 'alias'=>$cat['alias'])),
				//'expanded' => false,
								'id'=>$category_id,
								'children'=>$this->children($category_id),
				);
			}
			return $treee;
		}//////if (@isset($this->levels[$parent_id])) {
		else return NULL;
	}//////////////private function children1($kladr_id, $code) {


	private function children_admin_option($parent_id) {
		//echo $parent_id.'<br>';
		if (@isset($this->levels[$parent_id])) {
			$models = $this->levels[$parent_id];
			//echo '<pre>';
			//print_r($models);
			//echo '</pre>';
			foreach ($models['children'] as $category_id=>$cat) {
				$treee[]=array(
				//'text'=>"<a href=\"?r=products/details&cat=".$this->brand_ids[$i]."\">".$this->brand_names[$i].'</a>',
				//'text'=>CHtml::link($models[$i]->name.' '.$models[$i]->socr, array('/nomenklatura/contragents/'.$models[$i]->code.'?targetform='.$targetform.'&targetitem='.$targetitem)),
								//'text'=>CHtml::link($cat['name'], array('site/category/', 'alias'=>$cat['alias'])),
								'text'=>CHtml::link($cat['name'], '#chars', array('onClick'=>'get_options('.$category_id.')' )),
				//'expanded' => false,
								'id'=>$category_id,
								'children'=>$this->children_admin_option($category_id),
				);
			}
			return $treee;
		}/////////////if (@isset($this->levels[$parent_id])) {
		else return NULL;
	}//////////////private function children1($kladr_id, $code) {


public function DrawAdminOptionTree() { ///для отрисовки дерева для поска опций в адиминке
		$connection = Yii::app()->db;
		$query = "SELECT t.category_id,
			t.category_name, 
			t.alias,
			t.parent
			FROM `categories` `t`   ";
		$query.= "  GROUP BY t.category_id";
		$query.= " ORDER BY t.sort_category";
			
		$command=$connection->createCommand($query)	;
		$dataReader=$command->query();
		$records=$dataReader->readAll();////
		foreach ($records as $k=>$v) {
			$current['parent_id'] = $v['parent'];
			$current['name'] = $v['category_name'];
			$current['category_id'] = $v['category_id'];
			$current['alias'] = $v['alias'];
			if ( $v['parent'] == 0){
				$this->tree[ $v['category_id'] ] = $current;
			} else {
				$this->levels[$v['parent']]['children'][$v['category_id']] = $current;
			}
		}///////////foreach ($records as $k=>$v) {

		/*
		 echo '<pre>';
		print_r($this->tree);
		echo '</pre>';

		$sum = NULL;
		$arr = $this->levels[$parent_id];
		if (count($arr[children])>0) {
		$sum[1]= count($arr[children]);
		foreach ($arr[children] as $parent_id=>$tree) {
		$qqq=$this->show_vetv($parent_id);
		$sum[0] = $sum[0] + $qqq[0];
		$sum[0] = $sum[0] + $tree[products];
		}//////////
		}
		return $sum;
		*/
		$models  = $this->tree;
		foreach ($models as $category_id=>$cat) {
			$treee[]=array(
			//'text'=>"<a href=\"?r=products/details&cat=".$this->brand_ids[$i]."\">".$this->brand_names[$i].'</a>',
			//'text'=>CHtml::link($models[$i]->name.' '.$models[$i]->socr, array('/nomenklatura/contragents/'.$models[$i]->code.'?targetform='.$targetform.'&targetitem='.$targetitem)),
								//'text'=>CHtml::link($cat['name'], array('site/category/', 'alias'=>$cat['alias'])),
								'text'=>CHtml::link($cat['name'], '#chars', array('onClick'=>'get_options('.$category_id.')')),
			//'expanded' => false,
								'id'=>$category_id,
								'children'=>$this->children_admin_option($category_id),
			);

		}/////////for ($i=0; $i<count($models); $i++) {


		
		$this->render('treeview/adminoptions', array('treee'=>$treee));
	}//////////////draw



}////////////////class Tree extends CWidget {
?>