<?
for($i=0; $i<count($models);$i++) {
$qqq = explode(' ', $models[$i]->creation_date);
$qqq1 = explode('-', $qqq[0]);

?>

 <div class="ndate"><?php echo $qqq1[1].'.'.$qqq1[2].'.'.$qqq1[0]?></div>

    <div class="nanons"><?
 	echo CHtml::link($models[$i]->short_descr, array('page/show', 'id'=>$models[$i]->id));

	?></div>
<?
}
?>
