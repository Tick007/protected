<?php
/**
 * MainMenu is a widget displaying main menu items.
 *
 * The menu items are displayed as an HTML list. One of the items
 * may be set as active, which could add an "active" CSS class to the rendered item.
 *
 * To use this widget, specify the "items" property with an array of
 * the menu items to be displayed. Each item should be an array with
 * the following elements:
 * - visible: boolean, whether this item is visible;
 * - label: string, label of this menu item. Make sure you HTML-encode it if needed;
 * - url: string|array, the URL that this item leads to. Use a string to
 *   represent a static URL, while an array for constructing a dynamic one.
 * - pattern: array, optional. This is used to determine if the item is active.
 *   The first element refers to the route of the request, while the rest
 *   name-value pairs representing the GET parameters to be matched with.
 *   When the route does not contain the action part, it is treated
 *   as a controller ID and will match all actions of the controller.
 *   If pattern is not given, the url array will be used instead.
 */
class StmMenu extends CWidget
{
	
	var $tree;
	var $levels;
	
	function __construct(){
			//////////////////Выбираем группы
			$connection = Yii::app()->db;
		$query = "SELECT t.category_id,
			t.category_name, 
			t.alias,
			t.path, 
			t.parent
			FROM `categories` `t` WHERE t.show_category = 1 ";
		//if (isset($region) AND @$region != 0) $query.=" AND products.kladr_belongs IN (".implode(',', $region_list).") ";
		$query.= "  GROUP BY t.category_id";
		//$query.= " ORDER BY t.parent ASC";
		$query.= " ORDER BY t.sort_category";
			
		$command=$connection->createCommand($query)	;
		$dataReader=$command->query();
		$records=$dataReader->readAll();////
		foreach ($records as $k=>$v) {
			$current['parent_id'] = $v['parent'];
			$current['category_id'] = $v['category_id'];
			$current['alias'] = $v['alias'];
			$current['path'] = $v['path'];
			$current['name'] = $v['category_name'];
			if ( $v['parent'] == 0){
				$this->tree[ $v['category_id'] ] = $current;
			} else {
				$this->levels[$v['parent']]['children'][$v['category_id']] = $current;
			}
		}///////////foreach ($records as $k=>$v) {
			
	}

	public function run()
	{
		$this->render('stm/menu/dropdown');
	}

	protected function category_icon($category_id, $category_name){
					$group_icon_src='/pictures/group_ico/'.strtolower($category_id).'.png';
					$group_icon = $_SERVER['DOCUMENT_ROOT'].$group_icon_src;
					//echo $group_icon.;
					if(file_exists($group_icon) AND is_file($group_icon)) $gr_name = "<img src=\"$group_icon_src\" title=\"".$category_name."\" alt=\"".$category_name."\" style=\"max-height:20px\" border=\"0\">";
					//else  $gr_name = $models[$i]->value;
					else {///else 1
					
					$group_icon_src='/pictures/group_ico/'.strtolower($category_id).'.gif';
					$group_icon = $_SERVER['DOCUMENT_ROOT'].$group_icon_src;
					if (file_exists($group_icon) AND is_file($group_icon)) $gr_name = "<img src=\"$group_icon_src\" title=\"".$category_name."\" alt=\"".$category_name."\" style=\"max-height:20px\" border=\"0\">";
					 
					else  {////////////else 2
					$group_icon_src=Yii::app()->theme->baseUrl.'/images/noicon.png';
					$group_icon = $_SERVER['DOCUMENT_ROOT'].$group_icon_src;
					$gr_name = "<img src=\"$group_icon_src\" border=\"0\" title=\"".$next->category_name."\" alt=\"".$next->category_name."\" style=\"max-height:20px\">";
					}//////////else  {////////////else 2
					}////////else {///else 1
					if (@trim($gr_name)) return $gr_name;
					else return '';
	}
	
	protected function submenu_items($category, $level){
		//print_r($this->levels[$category_id]);

		foreach($this->levels[$category]['children'] as $category_id=>$category){
			$submunu.= '<div class="_b-header-nav-dropdown-item">
                                            <div class="_b-header-nav-dropdown-item-h">';
			if(isset($this->levels[$category_id])) $htmloptions=array(/*'style'=>'font-weight:bold'*/);
			else $htmloptions=array();
			   if(isset(Yii::app()->params['use_long_urls']) AND Yii::app()->params['use_long_urls']==true) $url=urldecode(Yii::app()->createUrl('product/list' ,array('alias'=>$category['alias'], 'path'=>FHtml::urlpath($category['path']) ) ) );
			   else  $url=urldecode(Yii::app()->createUrl('product/list' ,array( 'alias'=>$category['alias']) ) );
			//echo CHtml::link($next->category_name,$url, array('style'=>'color:#000000; text-decoration:none; line-height:21px'));
			//$submunu.= CHtml::link(/*$this->category_icon($category_id,$category['name'] ).*/$category['name'],  array('product/list', 'alias'=>$category['alias']), $htmloptions);
			$submunu.=  CHtml::link($category['name'],  $url, $htmloptions);
			/*
			if(isset($this->levels[$category_id]) AND $level<2) {
				$submunu.='<div class="subsubmenu">';
					$sub = $this->submenu_items($category_id, $level+1);
					$submunu.= $sub;
				$submunu.='</div>';
			}
			*/
			
			
			$submunu.= '</div></div>';	
		}

		return $submunu;
	}///////////private function submenu_items($category_id){

}