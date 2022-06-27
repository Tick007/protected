<div style="display:table">
<!--<h3>Подчиненные группы</h3>-->
<?


if(isset($group_obj->title) AND trim($group_obj->title)!='') $this->pageTitle=$group_obj->title;
else $this->pageTitle= $group_obj->category_name;
if (isset($group_obj->description) )$this->pageDescription=$group_obj->description;
else $this->pageDescription=str_replace(' -> ', ', ', $title_path);
if (isset($group_obj->keywords)) $this->pageKeywords = $group_obj->keywords;

foreach($models as $n=>$next):

			
			/////////////////////////////
			//Рисуем иконки групп
					$group_icon_src='/pictures/group_ico/'.strtolower($next->category_id).'.png';
					$group_icon = $_SERVER['DOCUMENT_ROOT'].$group_icon_src;
					//echo $group_icon.;
					if(file_exists($group_icon) AND is_file($group_icon)) $gr_name = "<img src=\"$group_icon_src\" title=\"".$next->category_name."\" alt=\"".$next->category_name."\" style=\"max-width:140px\" border=\"0\">";
					//else  $gr_name = $models[$i]->value;
					else  {
					$group_icon_src=Yii::app()->theme->baseUrl.'/images/noicon.png';
					$group_icon = $_SERVER['DOCUMENT_ROOT'].$group_icon_src;
					$gr_name = "<img src=\"$group_icon_src\" border=\"0\" title=\"".$next->category_name."\" alt=\"".$next->category_name."\" style=\"max-width:140px\">";
					}
					if (@trim($gr_name)) {///////Выводим только если есть маленькая фотка
					?>
					<div style="float:left;  border:1px; width:142px; margin:5px; padding:5px; vertical-align:text-bottom">
					<div align="center" style="height:150px">
                    <div style="height:100px;  vertical-align:middle">
					<?
					//$gr_name = $models[$i]->category_name;
					//else echo 'нет картинки<br>';
					//echo $gr_name;
					if (trim($next->alias)<>'')  {
							echo CHtml::link($gr_name, array('/product/list', 'alias'=>$next->alias)).'</div><br>';
							echo CHtml::link($next->category_name, array('/product/list', 'alias'=>$next->alias)).'<br>'; 
					}
					else  {
							echo CHtml::link($gr_name, array('/product/'.$next->category_id)).'</div><br>';
							echo CHtml::link($next->category_name, array('/product/'.$next->category_id)).'<br>'; 
					}
					?>
					</div>
					</div>
					
					<?
                    }////////if (@trim($gr_name)) {///////Выводим только если есть маленькая фотка
			////////////////////////////////////
			
if (count($next->childs)) {
		for($k=0; $k<count($next->childs); $k++) {
			//	echo  '|'.CHtml::link($next->childs[$k]->category_name, array('/product/'.$next->childs[$k]->category_id) ).'|&nbsp;&nbsp;&nbsp;';
			
			
		}
//echo '<br><br>';
}
endforeach;


//}///////if (count($models)>0) {
?>
<br>
<?

//if(isset($group_obj->page) AND isset($group_obj->page->contents)) echo $group_obj->page->contents;
?>
</div>
