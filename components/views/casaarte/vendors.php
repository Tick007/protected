<ul class="brandlist">
<?
$k=0;
for($i=0, $c=count($models); $i<$c; $i++) if(trim($models[$i]->value)!=''){
echo '<li>'.CHtml::link($models[$i]->value, array('/product/vendor/', 'alias'=>urlencode(str_replace(' ', '_', $models[$i]->value)))).'</li>';
$k++;
if($k>=8) break;
}
?>
</ul>