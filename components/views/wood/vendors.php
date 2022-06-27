<ul id="vertical_menu">
<?
for($i=0; $i<count($models); $i++) {
echo '<li>'.CHtml::link($models[$i]->value, array('/product/vendor/'.$models[$i]->value)).'</li>';
}
?>
</ul>