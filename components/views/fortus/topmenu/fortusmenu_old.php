
<div  class="mainmenu" align="right">
<ul>
<?php


	$alias = Yii::app()->getRequest()->getParam('id');/////тут сидит alias в pagecontroleer
	
	
//	echo $controller.'<br>';
//	echo $action.'<br>';
	//echo  '$alias = ' .$alias.'<br>'; 
	
	$all = count($this->points);
	$i=1;
	$h=0;
	foreach ($this->points as $link_name => $link) {
		
		
		
		

		
		//print_r($link);
		if ($link['url']!="/#") {
			echo '<li';
			if (isset($link['alias'])) {
				//if (in_array($alias, $link['alias'])  OR in_array($action, $link['alias'])  ) {
				if (in_array($alias, $link['alias'])  OR in_array($action, $link['alias'])  OR  (isset($link['controller']) AND in_array($controller, $link['controller']) AND (isset($link['actions']) AND  in_array($action, $link['actions']) ))   ) { //////////изменил после добавления в сайт акшина cooperation
					echo " class=\"active";
					if(isset($link['childs']) ) {
						$submenu = $link['childs'];
						//$aliases = $link['alias'];
					}
				}
				else  echo " class=\"";
				if($i==$all) echo ' last';
				else if($i==1) echo ' first';
				//if (in_array('faq', $link['action']) ) echo '_faq';
				
				if (in_array($alias, $link['alias']) ) echo "\"";
				else  echo "\"";
			}
			elseif(isset($link['alias'])==false) {
				//print_r($link);
				
				if ( (isset($link['controller']) AND in_array($controller, $link['controller']) AND (isset($link['actions']) AND  in_array($action, $link['actions'])  )) OR ( isset($link['controller']) AND in_array($controller, $link['controller']) AND isset($link['actions'])==false  )  ) {
					// if (in_array('faq', $link['action']) )echo " class=\"active_faq\"";
					 //else
					  echo " class=\"active";
					  if($i==$all) echo ' last';
					  echo  '"';
					  if(isset($link['childs']) ) $submenu = $link['childs'];
					  
					}
					else if($i==1) echo ' class="first" ';
					else  if($i==$all) echo ' class="last"';
				// if (in_array('faq', $link['action']) ) echo ' class="_faq"';
				
			}
			
				
			
				echo ">";
				//$cond = ((($controller=='site' AND $action=='index') OR ($controller=='site' AND $action=='map') ) AND isset($link['childs']) AND empty($link['childs'])==false);
				$cond = (isset($link['childs']) AND empty($link['childs'])==false );
				
		//		if($controller=='site' AND $action=='index' AND isset($link['childs']) AND empty($link['childs'])==false) {
		//	$html_options = array('class'=>'submenu_activator', 'rel'=>$h);
		//	$submenu_mp = $link['childs'];
	//	}
				
				echo CHtml::link($link_name, $link['url'], (isset($cond) AND $cond==true)?array('class'=>'submenu_activator', 'rel'=>$h):NULL);
				//print_r($link['childs']);
				//echo $link['controller'];
				//echo $controller;
				//echo $action;
				//var_dump(isset($link['controller']));
				//var_dump( in_array($controller, $link['controller']));
				//var_dump(isset($link['actions']));
				//var_dump( in_array($action, $link['actions']) );
				//var_dump((isset($link['controller']) AND in_array($controller, $link['controller']) AND (isset($link['actions']) AND  in_array($action, $link['actions'])  )) OR ( isset($link['controller']) AND in_array($controller, $link['controller']) AND isset($link['actions'])==false  )  );
			
			
		if($cond==true) {//Последний заказ. Когда ещё не зашли в подменю, нужно сделать выпадающий второй уровень
	///////////Перебираем все подменю  
	?>
    <div class="submenu submenu_hidden" id="hid_subm_<?php echo $h?>"><ul>
	<?php
   // print_r($submenu_mp[$i]);
   foreach ($link['childs'] as $link_names => $links) {
   ?>
   <li><?php echo CHtml::link($link_names, $links['url']);?></li>
   <?php
   }
	?>
	
    </ul></div>
	<?
$h++;
}/////////////if(isset($submenu)==false) { /////////Последний заказ. Когда ещё не зашли в подменю, нужно сделать выпадающий второй уровень
			
				
			echo '</li>';
			
		}///////
		$i++;
	}
		?>
</ul>
</div>
<?php


//echo $this->current_cont.'<br>';
//echo  '$alias = ' .$alias.'<br>';
//echo $action.'<br>';

if(isset($submenu) AND  ($this->current_cont == 'page' OR $this->current_cont == 'vacancy' OR $this->current_cont == 'site' ) ) {
	
	?><div class="submenu">
    <?php
	//echo '<pre>';
   //print_r($submenu);
	//echo '</pre>';
	?><ul><?php
	foreach($submenu as $name=>$adr){
	if(($this->current_cont == 'page' AND  is_array($adr['alias'])==false AND  $adr['alias']==$alias) OR (in_array($action, $adr['actions']) AND $this->cur_action == $action  )  OR  (is_array($adr['alias'])==true AND  in_array($alias, $adr['alias']))     )	{//////////т.е. если детей нет, то смотрим, равен ли текущий алиас пришедшему в гет, если есть дети, то смотри есть ли в массиве алаисов совпадение с пришедшим в гет алаисом страницы
		$submenu2 = $adr['childs'];
	?><li class="active"><span><?php
	echo $name;
	//var_dump(in_array($action, $adr['actions']) AND $this->cur_action == $action );
	//var_dump(is_array($adr['alias'])==false AND  $adr['alias']==$alias);
	?></span><div align="center" style="width:100%; height:10px;">
     <div class="pointer"></div>
     </div>
    </li><?php
	}/////if
	else {
		?>
		<li><?php echo CHtml::link($name, $adr['url']);?></li>
		<?php
	}////else {
	
	}
	?>
	</ul></div>
	<?php
}
elseif( isset($submenu) AND $this->current_cont == 'constructcatalog') {
?>
 
<div class="submenu" <?php
if(isset($action) AND ($action=='adv' OR $action=='tools')) echo 'id="accessores"';
?>>
<ul>
<?php
$locktype = Yii::app()->getRequest()->getParam('locktype');
if(isset($locktype)) {
foreach($submenu as $name=>$adr){
?><li <?php
if($adr['locktype']==$locktype)  {
	$submenu2 = $adr['childs'];
	echo ' class="active"';
	}
?>>
<?php
if(isset($adr['url'])) echo CHtml::link($name, $adr['url'] );
 else echo CHtml::link($name, array('/constructcatalog/index', 'locktype'=>$adr['locktype']));?>

<?php
if($adr['locktype']==$locktype) {
?>
<div align="center" style="width:100%; height:10px;">
     <div class="pointer"></div>
     </div>
<?php
}
?>
</li>
<?php
}
}///////$locktype
else {//////////////Для пункта меню где инструмент
			
			foreach($submenu as $name=>$adr){////
	if(/*(is_array($adr['alias'])==false AND  $adr['alias']==$alias) OR */ (in_array($action, $adr['actions'])==true )  OR  (is_array($adr['alias'])==true AND  in_array($alias, $adr['alias']))     )	{//////////т.е. если детей нет, то смотрим, равен ли текущий алиас пришедшему в гет, если есть дети, то смотри есть ли в массиве алаисов совпадение с пришедшим в гет алаисом страницы
		$submenu2 = $adr['childs'];
	?><li class="active"><span><?php
	echo $name;
	?></span><div align="center" style="width:100%; height:10px;">
     <div class="pointer"></div>
     </div>
    </li><?php
	}/////if
	else {
		?>
		<li><?php echo CHtml::link($name, $adr['url']);?></li>
		<?php
	}////else {
	
	}
	
			
}////////else {//////////////Для пункта меню где и


?>

</ul>

</div><?php
}




if(isset($submenu2) AND $this->current_cont == 'constructcatalog') {////////////Меню 3го уровня
	?>
	<div class="submenu2">
<ul>
<?php

$krepltype  = Yii::app()->getRequest()->getParam('krepltype');

foreach($submenu2 as $name=>$adr){
		?>
		<li
        <?php
        if($krepltype==$adr['krepltype']) echo 'class="active"';
		?>><?php
        echo Chtml::link($name, array('constructcatalog/index', 'locktype'=>$locktype, 'krepltype'=>$adr['krepltype']));
		?>
        </li>
		<?php
}
?>
</ul></div>
	<?php
}////////if(isset($submenu2)) {///////////

if(isset($submenu2) AND $this->current_cont == 'page') {////
?>
<div class="submenu2"><ul>
<?php
foreach($submenu2 as $name=>$adr){
	?><li <?php
    if(in_array($alias, $adr['alias']) OR in_array($action, $adr['alias'] ))	echo ' class="active" ';
	?> 
    ><?php
	echo Chtml::link($name, $adr['url']);
?>
     
    </li>
<?php
}////////foreach($submenu2 as
?>
</ul></div>
<?php
}
?>