
<form action="/catalog/search/<?php if(isset($locktype)) echo $locktype?>/<?php if(isset($krepltype)) echo $krepltype;?>" method="get" name="lpform" id="lpform" >
<div class="podborpad">

<?php

echo CHtml::dropDownList('brand',   $brand, $brand_list, array('id'=>'brand_'.$str_id, 'class'=>'sel180 brand', 'tabindex'=>2));
?>
<br>
<?php 
echo CHtml::dropDownList('model',   @$model, $model_list, array('id'=>'model_'.$str_id, 'class'=>'sel180 model'));
?><br>
<?php 
echo CHtml::dropDownList('year',   @$year, (isset($year_list)?$year_list:array('0'=>'Год')), array('id'=>'year_'.$str_id,'class'=>'sel180 year'));
?><br><div class="butcont">
<?php
echo CHtml::Button('go', array('class'=>'gobut gobutrad', 'value'=>'Подобрать', 'id'=>'go_but_'.$str_id));
?></div>
</div>
</form>


<div class="overlay_popup"></div>
    <div class="popup" id="popup1">
    <div class="closepopup"><a href="" onclick="{ $('.overlay_popup, .popup').hide(); return false;}"> </a></div>
    <div class="popuplogo">&nbsp;</div>
    <hr class="popup_green_line">
    <br style="clear:both">

    <div class="popup_content">
      контент всплывающего окна <br>
      dadsadas
      qweqweqwe
      </div>
    </div>


<script>
jQuery(document).ready(function(){
	
/*
	$('searchp').keypress(function(event) {
	    if (event.keyCode == 13) {
	        event.preventDefault();
	    }
	});
	*/
	

	
	//$('.chzn-results').jScrollPane();
	
	<?php
	/*
	if(@$brand>0) echo "$('#brend_href').click();";
	?>
	<?php
	if(@$model>0) echo "$('#model_href').click();";
	?>
	<?php
	if(isset($year) AND trim($year) ) {
		echo "$('#year_href').click();
				$('#model_href').change();";
				if(isset($kpp)==false OR trim($kpp)=='') echo "$('#year').change();";
	}	
	?>
	<?php
	if((isset($kpp) AND trim($kpp)) OR (isset($kpp_list))) echo "$('#kpp_href').click();";
	*/
	?>
});



</script>
