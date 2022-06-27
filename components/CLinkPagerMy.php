<?php
class CLinkPagerMy extends CLinkPager
{
	function createPageButton($label,$page,$class,$hidden,$selected)
	{
		if($hidden || $selected)
			$class.=' '.($hidden ? $this->hiddenPageCssClass : $this->selectedPageCssClass);
		return '<li class="'.$class.'">'.CHtml::link($label,urldecode($this->createPageUrl($page))).'</li>';
	}

}
?>