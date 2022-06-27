<?
for($i=0; $i<count($models);$i++) {
?>
<div class="recepts">
 <div class="rimage">
 <?php
 echo $models[$i]->source;
 ?>
 </div>
	<div class="rname"><?php echo $models[$i]->name?></div>
    <div class="nanons"><?
 	echo $models[$i]->short_descr;
	echo Chtml::link('Подробнее  >> ',array('page/show', 'id'=>$models[$i]->id),  array('class'=>'receptlink'));

	?></div>
<?
}
?>
</div>