
<ul id="vertical_menu">
<?
for($i=0; $i<count($models); $i++) {
		echo '<li>';
		echo CHtml::link($models[$i]->title, array('/info/'.$models[$i]->alais), array('class'=>'parent'));
		
		echo '</li>';
}/////////////////for($i=0; $i<count($models); $i++) {
?>
</ul>


