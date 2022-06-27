<?php
Yii::import("zii.widgets.CListView");
class ListView extends CListView
{
	public function renderSizer()
	{
		$size = Yii::app()->request->getQuery('size', 5);
		$links = array();
		foreach (array(5, 20, 40, 50) as $count)
		{
			$params = array_replace($_GET, array('size'=>$count));
			if (isset($params['page']))
				unset($params['page']);

			$links[] = CHtml::link($count, Yii::app()->controller->createUrl('', $params));
		}
		
		$this->render(Yii::app()->theme->name.'/listview/sizer', array('links'=>$links, 'size'=>$size));
	}
	
	public function renderSwitcher(){
		
		$this->render(Yii::app()->theme->name.'/listview/switcher');
	}
	
	public function renderSorter()
	{
		if($this->dataProvider->getItemCount()<=0 || !$this->enableSorting || empty($this->sortableAttributes))
			return;
		$this->render(Yii::app()->theme->name.'/listview/sorter');
	}
}
?>