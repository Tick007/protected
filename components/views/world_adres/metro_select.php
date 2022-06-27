<?php
$clientScript=Yii::app()->clientScript;
$clientScript->registerCssFile('/css/region_selector.css');
?>
<style>
#metro_select_ierachical select{
	width:250px;
	margin-right:5px;
}

#world_metro_label{
	display:inline-block;
<?php
if (isset($htmloptions)) {
	foreach($htmloptions as $style=>$value){
		echo $style.':'.$value.';';
	}
}
?>
}

</style>

<span id="world_metro_label"><?php
if ($this->station_id!= NULL AND isset($this->stations_list[$this->station_id])) echo CHtml::link($this->stations_list[$this->station_id], NULL, array('id'=>'worldr_metro_selector'));
else echo CHtml::link('выбрать', NULL, array('id'=>'worldr_metro_selector'));
?></span>
<span id="metro_select_ierachical">
<strong>Выбор ветки метро</strong>
<?php
if(isset($cities_select)) echo  $cities_select;
?><br>
<?php
if(isset($lines_select)) echo  $lines_select;
?><br>
<?php
if(isset($stations_select)) echo $stations_select;
?>

<span id="metro_select_closer"><?php
echo CHtml::link('закрыть', NULL, array('onclick'=>'{$("#metro_select_ierachical").toggle()}', 'id'=>'metro_select_closer_link'));
?></span>

</span>
<script>
$('#worldr_metro_selector').click(function() {
	$('#metro_select_ierachical').toggle();
});
</script>
<?php
//if(isset($cities_select)) echo  $cities_select;
?>&nbsp;
<?php
//if(isset($lines_select)) echo  $lines_select;
?>&nbsp;
<?php
//if(isset($stations_select)) echo $stations_select;
?>