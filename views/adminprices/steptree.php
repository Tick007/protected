<?php
$this->pageTitle="Просмотр предварительный результатов обновления";
?>
<div id="Right_column" style="background-color:#666E73; width:60px; margin-left:0px; float:left">
<?
$RC = new RightColumnAdmin;
?>
</div>

<?php
$store = Stores::model()->findByPk($store_id);
?>

<div id="mainContent" style="padding-left:3px; margin-left:60px; background-color:#fffbf0  ">
<h2>Просмотр предварительных результатов загрузки на склад "<?php echo $store->name?>": шаг 3. Число строк в прайслисте: <?php echo $num_of_rows?></h2>


<div id="profress_div" style="width:500px; height:100px; background-color:#f8f8f8;; margin-top:100px; display:none; position:absolute; margin-left:300px; padding: 7px; border: 1px solid #e7e7e7; -moz-border-radius: 12px; border-radius: 12px; -moz-box-shadow: box-shadow: 3px 3px 3px #444;box-shadow: 3px 3px 3px #444; z-index:100">
     <div id="progress" style="height:100; width:0px; line-height:100px; text-align:center; background-color:#06F">
        <div id="progdess_num" style="color:#FFF; font-size:14px;line-height:100px;">
        </div>
    </div>
</div>

<?php
echo CHtml::beginForm();  
?>

<div class="private_to_right" style="top:106px; left:980px; margin-left:0px; width:180px">

    <div class="private_room_div_header" style="background-image:url(/images/db_commit5.png); background-repeat:no-repeat; background-position:center; background-position-x: 153px; width: 170px;">
    Загрузка
    </div>
    <div class="private_room_div_content">
	<div id="exrtainfo" class="errorSummary" style="display:none"></div>
    <div align="center">

	Загрузка ноывх цен и остатков
	<div id="loadprices"></div>
<br>

       </div>
    </div>
<br>
</div>



<div style=" width:300px; float:left; margin-right:10px;">
<strong>Список категорий в прайслисте:</strong><br>

<?php
$plh = Price_list_header::model()->findByPk($id);
$unic_cats = $plh->getPriceProductsGroups();


//echo 'cats step3 = '.count($unic_cats).'<br>';

if($num_of_rows<1000) {

$data =  $this->get_tree_children(0, $id, $unic_cats );
$this->widget(
			'CTreeView',
		array(
			//'url' => array('search/ajaxfillpricelistgroups', 'id'=>$id), //////Для аджакс подгрузки
			'collapsed'=>false,
			'data'=>$data,
		)
		);
}
else {
	
	$url = Yii::app()->createUrl('search/ajaxfillpricelistgroups', array( 'id'=>$id));
	//echo $url;
	
	//$cookie = new CHttpCookie('unic_cats', serialize($unic_cats));
	//$cookie->expire = time() + 3600;
	//Yii::app()->request->cookies['unic_cats'] = $cookie;
	
	Yii::app()->user->setState('unic_cats', serialize($unic_cats));
	
	//print_r( $cookie);
	
	//exit();
	
	$this->widget(
			'CTreeView',
			array(
				'url' => $url, //////Для аджакс подгрузки
			)
	);
	
}

//$plh = Price_list_header::model()->findByPk($id);
//$unic_cats = $plh->getPriceProductsGroups();
?>

</div>
<div style="float:left; width:600px" id="pricecontent"><br><br><br><br><br><- выберите категорию для просмотра загруженных цен</div>
<br style="clear:both" />

<?php
echo CHtml::endForm(); 
?>
</div><!--main content-->
<script>

$(document).ready(function(){

		jQuery('body').delegate('.adjustableprice','input',function(){
				//alert($(this).val());
				
				id = $(this).attr('id').replace('price_with_nds_', '');
				
				var data =  {
					'id':id,
					'price':$(this).val()
				}
				
				jQuery.ajax({
					'type':'POST',
					'url':'<?php echo Yii::app()->createUrl('adminprices/updateposition')?>',
					'cache':false,
					//'async': false,
					//'dataType':'json',
					'data':data,
					'success':function(html){
						
						//alert(step);
						

					
					}});
				
		});
		
		
		$('#loadprices').click(function() {////////////Эта функция должна вызывать аджакс оббработку файла xls по частям или вызов в функции вызванной аджаксом обращения к консольному скрипту
				//alert('фиг с маслом');
				
				$('#exrtainfo').css('display','none');
				
				var parts = <?php $parts =  round($num_of_rows/$this->sql_operations_insert_limit, 0);
				if($parts<($num_of_rows/$this->sql_operations_insert_limit)) $parts++;
				if($parts<1) $parts=1;
				echo $parts;
				?>;
		
				
				
				//$("#empty_span").append("<id>");			
				$('#profress_div').toggle();	
				make_request(1, parts);
				
				
				
		});
		
});

function showpriceproducts(cat){
	
	var data =  {
			'cat':cat,
			'store_id':<?php echo $store_id?>
		}
	
	jQuery.ajax({
			'type':'POST',
			'url':'<?php echo Yii::app()->createUrl('adminprices/getpricelistproducts', array('id'=>$id))?>',
			'cache':false,
			//'async': false,
			//'dataType':'json',
			'data':data,
			'success':function(html){
				
				//alert(step);
				$('#pricecontent').html(html);

			
			}});
	
}//////function showpriceproducts(cat){

function  make_request(step, parts){
		var responce ;
		var delay = <?php
		$delay = $this->sql_operations_insert_limit;
		if ($delay<1000) $delay = 1000;
		echo $delay;
		?>;
		var data =  {
					'pricelist':<?php echo $id;?>,
					'store':<?php echo $store_id?>,
					'tempfile':'<?php echo $tempfile?>',
					'step':step , 
					'parts':parts,
				}
	
		jQuery.ajax({
					'type':'POST',
					'url':'/adminprices/updatepricelistproducts',
					'cache':false,
					'async': false,
					'dataType':'json',
					'data':data,
					'success':function(html){
						
						//alert(step);
						responce = html;

					
					}});
					
					
					step++;
					if(step<=parts)  {
						draw_progress(parts, (step-1));
						setTimeout("make_request("+step+", "+parts+")", 100);
						//make_request(step,parts);
					}	
					else {
						draw_progress(parts, (step-1));
						
						
						
						setTimeout("finalize()", 1000);
						//alert(responce);
						$('#exrtainfo').html(responce);
						
					}
					
}

function finalize(){
	$('#profress_div').toggle();
	$('#exrtainfo').css('display','block');
	
}


function draw_progress( pecies, i){
		//console.log(i);
		width = (500/pecies)*(i);
		$('#progress').css('width', width);
		num = Math.round((100/pecies)*(i));
		$('#progdess_num').text(num+'%');
		//console.log(num);
}

</script>