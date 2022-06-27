qweqwe
<?php
 $clientScript = Yii::app()->clientScript;
// $clientScript->registerScriptFile('/js/jquery.bgiframe.js', CClientScript::POS_HEAD);
  $clientScript->registerScriptFile('/js/jquery.mcdropdown.min.js', CClientScript::POS_HEAD);
  $clientScript->registerCssFile('/css/jquery.mcdropdown.min.css', CClientScript::POS_HEAD);
?>
<?php
/*
echo '<pre>';
print_r($this->elements);
echo '</pre>';
*/
?> 
<ul id="categorymenu" class="mcdropdown_menu"> 
<?php
if(isset($this->elements)) foreach($this->elements['children'] as $category_id=>$group) {
?>
<li rel="<?php echo $category_id?>">
<?php
echo $group['category_name'];
if(isset($this->levels[$category_id]) AND empty($this->levels[$category_id])==false) echo $this->childs($category_id);
?></li>
<?php
}
?>
</ul>
<div>
<?php
 //echo $this->value;
?>
<input type="text" name="<?php echo $this->elementname?>" id="<?php echo $this->elementname?>" value="<?php // echo $this->value?>" />
</div>

<script type="text/javascript">
	<!--//
	// define the location of the chili recipes
	//ChiliBook.recipeFolder = "./lib/chili/";

	// on DOM ready
	$(document).ready(function (){
		//$("#current_rev").html("v"+$.mcDropdown.version);
		//var dd = $("#<?php echo $this->elementname?>").mcDropdown("#categorymenu");
		//$("#<?php echo $this->elementname?>").mcDropdown("#categorymenu");
	//	//var dd = $("#<?php echo $this->elementname?>").mcDropdown(allowParentSelect: true);
		//dd = $("#<?php echo $this->elementname?>");
		//dd.mcDropdown("#categorymenu", {allowParentSelect:true, lineHeight: 30, hoverOutDelay: 1000 });
		//alert (dd.getValue());
		$("#<?php echo $this->elementname?>").mcDropdown("#categorymenu"); 
	});
	//-->
	</script>