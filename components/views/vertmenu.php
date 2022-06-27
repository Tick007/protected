<style>
ul#vertical_menu, #vertical_menu ul

{

    margin: 0;

    padding: 0;

    list-style: none;

    width:  205px;

    font-size:12px;

}



/*Submenu box*/

#vertical_menu li ul

{

    position:absolute;

    /*top:-999em;*/

    top:auto;

    display:none;

    z-index:500;

    height:auto;

    border:1px solid #C1C1C1;

    border-bottom:none;

    width:198px;

}



/* Submenu Items */

#vertical_menu li a

{

    display: block;

    text-decoration: none;

    color:#000000;

    font-weight:normal;

    padding: 5px 2px 5px 10px;

    background:#dcdadb;

    border-bottom:1px solid #C1C1C1;

}





/*Items Hover */

#vertical_menu li a:hover

{

    background-color: #000000;
	color:#FFFFFF;

}





/*Parent item*/

#vertical_menu a.parent

{


    padding-right:10px;

    padding-left:10px;

}





/* Holly Hack. IE Requirement \*/

* html ul#vertical_menu li { float: left; height: 1%; }

* html ul#vertical_menu li a { height: 1%; }

/* End */









#vertical_menu li ul

{

    margin:-30px 0 0 197px;

}



/*Submenu hide*/

#vertical_menu li:hover ul ul,

#vertical_menu li.jsvhover ul ul,

#vertical_menu li:hover ul ul ul,

#vertical_menu li.jsvhover ul ul ul,

#vertical_menu li:hover ul ul ul ul,

#vertical_menu li.jsvhover ul ul ul ul

{

    display:none;

}



/*Submenu show*/

#vertical_menu li:hover ul,

#vertical_menu li.jsvhover ul,

#vertical_menu li li:hover ul,

#vertical_menu li li.jsvhover ul,

#vertical_menu li li li:hover ul,

#vertical_menu li li li.jsvhover ul,

#vertical_menu li li li li:hover ul,

#vertical_menu li li li li.jsvhover ul

{

    display:block;

}
</style>
<ul id="vertical_menu">
<?
 for($i=0; $i<count($models); $i++) {
		echo '<li>';
		//echo CHtml::link($models[$i]->category_name, array('/product/'.$models[$i]->category_id), array('class'=>'parent'));
		if(isset($models[$i]->childs)) {
		if (count($models[$i]->childs)>0) {
		echo CHtml::link($models[$i]->category_name, array('/product/'.$models[$i]->category_id), array('class'=>'parent'));
			//echo count($models[$i]->childs);
			
			for($k=0; $k<count($models[$i]->childs); $k++) {
				if($models[$i]->childs[$k]->show_category==1) $childs[$models[$i]->childs[$k]->category_id] = $models[$i]->childs[$k]->category_name;
			}
			if(is_array($childs) && count($childs)>0) {
					
					//print_r($childs);
					echo '<ul>';
						//for($k=0; $k<count($models[$i]->childs); $k++) {/
						foreach($childs AS $key=>$val):
								echo '<li>';
								echo CHtml::link($val, array('/product/'.$key));
								////////Смотрим количество позиций 3го уровня//////////
								check_level_tree($key);
								echo '</li>';
						//}////////////$models[$i]->childs
						endforeach;
						echo '</ul>';
				}/////////if(count($childs)>0) {
			$childs=NULL;
		
		}////////////if (count($models[$i]->childs)>0) {
		else echo CHtml::link($models[$i]->category_name, array('/product/'.$models[$i]->category_id));
				
				
		}////////////if(isset($models[$i]->childs)) {
		echo '</li>';
}/////////////////for($i=0; $i<count($models); $i++) {
?>
</ul>
<?
function check_level_tree($id){////////////Проверка на уровне 3
		$criteria=new CDbCriteria;
		$criteria->order = ' t.category_name, childs.category_name';
		$criteria->condition = " t.parent= $id AND t.show_category = 1 ";
		$criteria->order = "t.sort_category, childs.sort_category";
		
		$models = Catalog::model()->with('childs')->findAll($criteria);//
		
		if (count($models)>0) {////////////Если найдено на 3м уровне
				echo "<ul>";
					for($i=0; $i<count($models); $i++) {
					echo '<li>';
					echo CHtml::link($models[$i]->category_name, array('/product/'.$models[$i]->category_id), array('class'=>'parent'));
					echo '</li>';
					}////////////for($i=0; $i<count($models); $i++) {							
				echo "</ul>";
		}/////////////////////if (count($models)>0) {////////////Если найдено на 3м уровне
		
}//////////////////////////////////////////function check_level_tree($id){
?>
