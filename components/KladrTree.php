
<?php
class KladrTree extends CWidget {////////////////////Рисует меню селекта

	private $regions;
	
	public function __construct(){
			
							$criteria=new CDbCriteria;
							$criteria->condition=" t.country_id = 3159";
							$criteria->order = "t.sort, t.name";
							$this->regions = World_adres_regions::model()->with('cities')->findAll($criteria);


	}////////////// __construct(){
	
	
	public function draw($update_id){
		
			$i=0;
			foreach($this->regions as $item)
			{
				//$link_text = iconv("UTF-8", "CP1251", $item->category_name);
				$link_text = $item->name;
				//$id = $item->category_id;
				$items[$i] =  array('id'=>$item->id, 'text'=>CHtml::link($link_text, '#', array('class'=>'lastchild','onClick'=>"{showregion(".$item->id.", 0)}") )  , 'expanded' => false, 'children'=>$this->get_cities($item->cities) );
				$items[$i]['hasChildren'] = ((count($item->cities)>0 ) ? TRUE : FALSE);
					
				$i++;
			}
		
		
		$this->render('world_adres/kladrtree', array('data'=>$items, 'update_id'=>$update_id));
	}
	
	
	public function get_cities($cities ){
		$i=0;
		foreach($cities as $item)
			{
				$link_text = $item->name;
				//$id = $item->category_id;
				$items[$i] = array('id'=>$item->id,  'text'=>CHtml::link($link_text, '#', array('class'=>'lastchild','onClick'=>"{showregion(0, ".$item->id.")}") ) );
				//$items[$i]['hasChildren'] =  FALSE);
					
				$i++;
			}
			
			if(isset($items))return $items;
		
	}
	
	
}////////////////class Tree extends CWidget {
?>