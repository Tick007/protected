<?php 
$catcookie=Yii::app()->request->cookies['catDisplay'];
if($catcookie!=null){
	$view=$catcookie->value;
}
else $view = 'list';


?>
<div class="switcher">
                <div class="view-mode"> 
                
                <?php 
                if($view=='list'){
                ?>
               <a href  class="button button-active button-grid" onClick="{document.cookie = 'catDisplay=grid';  }" >Сеткой</a>
               <span title="Список" class="button button-active button-list">Списком</span>
                <?php 
                }
                elseif($view=='grid'){
                ?>
                <span title="Grid" class="button button-active button-grid">Сеткой</span>
                <a href title="Списком" class="button button-active button-list" onClick="{document.cookie = 'catDisplay=list'}">Списком</a>
                
                <?php 
				}
                ?>
 </div>
</div>
