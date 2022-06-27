<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script>
jQuery(document).ready(function(){
	

	
	jQuery('body').delegate('#search_item','input',function(){
	//alert('werwer');
	jQuery.ajax({'type':'POST','url':'/nomenklatura/searchgoods/','cache':false,'data':jQuery(this).parents("form").serialize(),'success':function(html){
	//jQuery("#table_content").html(html)
	//alert (html);
	if (html!='n/a') $('#pricecontent').html(html);
	//alert('ewwer');
	},
	'error':function(){
			$('#pricecontent').html('');
	}
	});return false;});
	
	///////////////////////////////////////////////////////////////////////////////////////////////////
	jQuery('body').delegate('.lastlevel','click',function(){
		cat_id=$(this).attr('id').replace('cat_', '');
		//alert(cat_id);
		jQuery.ajax({'type':'POST','url':'/nomenklatura/searchgoods/','cache':false,'data':{'id':cat_id},'success':function(html){
		if (html!='n/a') $('#pricecontent').html(html);
	
		},
		'error':function(){
			$('#pricecontent').html('');
	}
	});return false;
	
	});
	
	
	
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		jQuery('body').delegate('.adjustableprice','input',function(){
				//alert($(this).val());
				
				id = $(this).attr('id').replace('price_with_nds_', '');
				
				var data =  {
					'id':id,
					'price':$(this).val()
				}
				
				jQuery.ajax({
					'type':'POST',
					'url':'<?php echo Yii::app()->createUrl('adminprices/updateproductprice')?>',
					'cache':false,
					//'async': false,
					//'dataType':'json',
					'data':data,
					'success':function(html){
						
						//alert(step);
				}});
				
		});
		
		
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	jQuery('body').delegate('.adjustablerrpprice','input',function(){
				//alert($(this).val());
				
				id = $(this).attr('id').replace('price_rrp_', '');
				
				var data =  {
					'id':id,
					'pricerrp':$(this).val()
				}
				
				jQuery.ajax({
					'type':'POST',
					'url':'<?php echo Yii::app()->createUrl('adminprices/updateproductprice')?>',
					'cache':false,
					//'async': false,
					//'dataType':'json',
					'data':data,
					'success':function(html){
						
						//alert(step);
				}});
				
		});
		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	jQuery('body').delegate('.adjustableselloutprice','input',function(){
				//alert($(this).val());
				
				id = $(this).attr('id').replace('sellout_price_', '');
				
				var data =  {
					'id':id,
					'sellout_price':$(this).val()
				}
				
				jQuery.ajax({
					'type':'POST',
					'url':'<?php echo Yii::app()->createUrl('adminprices/updateproductprice')?>',
					'cache':false,
					//'async': false,
					//'dataType':'json',
					'data':data,
					'success':function(html){
						
						//alert(step);
				}});
				
		});
		
		
		///////////////////////////////////////////////////////////////////////////////
		jQuery('body').delegate('.adjustablestatus','click',function(){
			id = $(this).attr('id').replace('product_visible_', '');
			var data =  {
				'id':id,
			}
			if($(this).is(':checked')) data['checked']=1;
			else  data['checked']=0;
			jQuery.ajax({
					'type':'POST',
					'url':'<?php echo Yii::app()->createUrl('adminprices/updateproductprice')?>',
					'cache':false,
					//'async': false,
					//'dataType':'json',
					'data':data,
					'success':function(html){	
						//alert(step);
			}});
			
		});
		
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////
		jQuery('body').delegate('.autoupdatelock','click',function(){
			id = $(this).attr('id').replace('product_price_no_auto_update_', '');
			var data =  {
				'id':id,
			}
			if($(this).is(':checked')) data['product_price_no_auto_update']=1;
			else  data['product_price_no_auto_update']=0;
			jQuery.ajax({
					'type':'POST',
					'url':'<?php echo Yii::app()->createUrl('adminprices/updateproductprice')?>',
					'cache':false,
					//'async': false,
					//'dataType':'json',
					'data':data,
					'success':function(html){	
						//alert(step);
			}});
			
		});
		
		
		
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
});

function UpdateOpener(id) {
//parent.opener.document.getElementById('add_product').innerHTML = id;
targetitem = '<?=$targetitem?>';
targetform = '<?=$targetform?>';
parent.opener.document.getElementById(targetitem).value = id;
//parent.document.forms.price_form.submit();
//fname='price_form';
//document.getElementById('price_form').submit();
//parent.opener.document.forms[parent_action_form].submit();
parent.opener.document.forms[targetform].submit();
//parent.opener.submit();
}

function sortable(sortfield){
	
	

	var data =  {
	'sortfield':sortfield,	
	} ;
	if( $('#category_id')!='undefined' ) data['id']=$('#category_id').val();
	
	if($.trim($('#search_item').val())!='') data['search_item']=$('#search_item').val();
	
	
	
		jQuery.ajax({'type':'POST','url':'/nomenklatura/searchgoods/','cache':false,'data':data,'success':function(html){
		if (html!='n/a') $('#pricecontent').html(html);
	
		},
		'error':function(){
			$('#pricecontent').html('');
	}
	});
	

}


</script>
<form action="" method="post" name="form">
<table width="auto" border="0" cellspacing="3" cellpadding="0" bgcolor="#CFC7AD">
  <tr>
    <td class="plain"><font color="#000000"><strong>Номенклатура</strong></font></td>
  </tr>
  <tr>
    <td ><table width="100%" border="0" cellspacing="5" cellpadding="0" bgcolor="#FFFBF0">
      <tr>
        <td class="plain">&nbsp;</td>
        <td class="plain">Артикул или часть наименования</td>
        <td class="plain"><input name="search_item"  id="search_item" type="text" class="textfield" size="60" maxlength="20"></td>
      </tr>
      <tr>
        <td  bgcolor="#898477" width="200" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="1">
  <tr>
    <td valign="top" width="200">
<table border="0" width="100%" cellspacing="0" cellpadding="0" class="plain" bgcolor="#FFFFF0">

  <tr>
    <td width="100%" colspan="3" valign="top">
<?php
//$tree = new Tree($targetform, $targetitem);
$url = Yii::app()->createUrl('search/ajaxfilltreegroupsnew', array( 'id'=>isset($id)?$id:0));
$this->widget(
			'CTreeView',
			array(
				'url' => $url, //////Для аджакс подгрузки
			)
	);

?>

</table></td>
  </tr>
</table></td>
        <td width="auto" colspan="2" valign="top" bgcolor="#898477" >

        <div  id="pricecontent"><br><br><br><br><br><- выберите категорию для просмотра загруженных цен</div>


</table>

</td>
      </tr>
      <tr>
        <td  bgcolor="#898477" valign="top">&nbsp;</td>
        <td colspan="2" valign="top" bgcolor="#898477" id="ostatki">&nbsp;</td>
      </tr>
    </table></td> 
  </tr>
</table>
</form>