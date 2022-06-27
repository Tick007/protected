<script>

	function showregion(region_id, city_id){
		
		var data =  {

					'region':region_id , 
					'city': city_id
				}
		
		jQuery.ajax({
					'type':'POST',
					'url':'<?php echo Yii::app()->createUrl('nomenklatura/getregioncities')?>',
					'cache':false,
					'async': true,
					//'dataType':'json',
					'data':data,
					'success':function(response){
						
						//alert(response);
						
						
						
						$('#<?php echo $update_id?>').html(response);
						
						$('.deliveryprice').bind( "change", function() {
						 	savePrice(this, 'price');
						});
						$('.deliveryeprice').bind( "change", function() {
						 	savePrice(this, 'eprice');
						});
						
						$('.freelimitcash').bind( "change", function() {
						 	savefreelimits(this, 'freelimitcash');
						});
						
						$('.freelimitepay').bind( "change", function() {
						 	savefreelimits(this, 'freelimitepay');
						});
						
					
					},
					'error':function(response){
						
						alert(response);

					}
					});
	}

function savefreelimits(el, eltype){
	id = $(el).attr('id');
	ids = id.split('_');
	var data={
					'city':ids[1] ,
					'record':ids[2],
					'price':$(el).val(),
					'type':eltype
				}
				jQuery.ajax({
					'type':'POST',
					'url':'<?php echo Yii::app()->createUrl('nomenklatura/saveregprodlimits')?>',
					'cache':false,
					'async': true,
					//'dataType':'json',
					'data':data,
					'success':function(response){
					if(response.trim()!='') {	
						alert(response);
						$(el).val('');
					}
					
					},
					'error':function(response){
						
					alert(response);
					$(el).val('');
					}
					});
				
				
				
}

function savePrice(el, eltype){
	id = $(el).attr('id');
	ids = id.split('_');
	var data={
					'city':ids[2] ,
					'product':ids[3],
					'price':$(el).val(),
					'freelimitcash':$('#freelimitcash_'+ids[2]+'_0').val(),
					'type':eltype
				}
	
	jQuery.ajax({
					'type':'POST',
					'url':'<?php echo Yii::app()->createUrl('nomenklatura/saveregprodprice')?>',
					'cache':false,
					'async': true,
					//'dataType':'json',
					'data':data,
					'success':function(response){
					if(response.trim()!='') {	
						alert(response);
						$(el).val('');
					}
					
					},
					'error':function(response){
						
					alert(response);
					$(el).val('');
					}
					});
	
}///////savePrice(el){


$(document).ready(function(){

	
});

</script>

<?php


$this->widget(
			'CTreeView',
		array(
			//'url' => array('search/ajaxfillpricelistgroups', 'id'=>$id), //////Для аджакс подгрузки
			'collapsed'=>false,
			'data'=>$data,
		)
		);


?>

