
<div class="top-cart-contain">

	<span id="topcartcontent"></span>

	<div id="ajaxconfig_info" style="display: none">
		<a href="#/"></a> <input value="" type="hidden"> <input
			id="enable_module" value="1" type="hidden"> <input
			class="effect_to_cart" value="1" type="hidden"> <input
			class="title_shopping_cart" value="Go to shopping cart" type="hidden">

	</div>

</div>

<?php 
$cookie=Yii::app()->request->cookies['YiiCart'];
if (isset($cookie) && $cookie->value!=''){
?><script>
getCart('topcartcontent');
</script>
<?php 
}
?>