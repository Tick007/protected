<?php
//print_r($htmloptions);
?>
<?php
$clientScript=Yii::app()->clientScript;
$clientScript->registerCssFile('/css/region_selector.css');
?>
<style>
	
#world_select_heading
{
	float: left;
}
	
#world_adres_label
{
	background: url("/images/filters-minus.gif") no-repeat right center;
	padding-right: 12px;
}

#region_select_ierachical
{
	margin-left: 50px;
	padding: 10px 20px;
	margin-top: 3px;
}

#region_select_ierachical select
{
	width:250px;
	margin-right:5px;
	margin-top: 5px;
	padding: 3px 5px;
	background-color: #fff;
	border: 1px solid #ccc;
	color: #333;
	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
	border-radius: 5px;
}

#world_adres_label{
	display:inline-block;
<?php
if (isset($htmloptions)) {
	foreach($htmloptions as $style=>$value){
		echo $style.':'.$value.';';
	}
}
?>
}

#worldr_adres_selector
{
	text-decoration: none;
	border-bottom: 1px dashed #1766BF;
	color: #1766BF;
}

</style>

<span id="world_adres_label"><?php
if ($this->kladr_belongs!= NULL AND isset($this->cities_list[$this->kladr_belongs])) echo CHtml::link($this->cities_list[$this->kladr_belongs], NULL, array('id'=>'worldr_adres_selector'));
else echo CHtml::link('выбрать', NULL, array('id'=>'worldr_adres_selector'));
?></span><br>
<div id="region_select_ierachical">
<span id="world_select_heading">
	<strong>Выбор региона</strong>
</span>
<span id="world_select_closer"><?php
	echo CHtml::link('закрыть', NULL, array('onclick'=>'{$("#region_select_ierachical").toggle()}', 'id'=>'world_select_closer_link'));
?>
</span>
<?php
//echo $this->kladr_belongs.'<br>';
////////$countries, 'country_regions'=>$country_regions, 'region_cities'=>$region_cities
//echo $this->countries_num.'<br>';
//var_dump(Yii::app()->params['kladr_mode']['use_country']);
if (isset(Yii::app()->params['kladr_mode']['use_country']) AND Yii::app()->params['kladr_mode']['use_country']==true AND isset($countries)) echo $countries;
elseif(isset($countries) AND $this->countries_num>1) echo $countries;

if (isset($country_regions)) echo $country_regions;
echo $region_cities;
?>
</div>

<script>
$('#worldr_adres_selector').click(function() {
	$('#region_select_ierachical').toggle();
});

function updatemetrooption(el){//////////////функция обновления опции выьора метро метро. Ищем по классу option_metro_city

metro_city = $('.option_metro_city');
//console.log(metro_city);
console.log(metro_city.attr('id'));

city_select = $(el);
console.log(city_select.attr('id'));
city = city_select.val();
console.log(city);

if(city>0) {///////////////Делаем выборку метро по городу
	 jQuery.ajax({
								'type':'POST',
								'url':'/nomenklatura/getstationscity',
								'cache':false,
								'data': {'kladr_id':city},
								'success':function(response){
											if(response!='') {
												//console.log(response);
												$(".option_metro_city option").remove();
												$(".option_metro_city").html(response);
											}
											else $(".option_metro_city option").remove();
									},
								'error':function(){
									resp = 'error';
								}	
		  					});		
}///if(city>0) {/////////////


}////////////function updatemetrooption(el){///////////

</script>