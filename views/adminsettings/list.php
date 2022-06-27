<script>
$(document).ready(function(){
//$("#faq-answer").animate({ opacity: "hide" }, "fast");
});

function collapse(el) {
//$(el).animate({ opacity: "hide" }, "slow");
$(el).hide();
}

function expand(el) {
$(el).show();
}

</script>
<style type="text/css">
<!--
.required {
	display:inline;
}
-->
</style>


<div id="ribbon" style="margin-left:71px">Общие настройки интернет магазина&nbsp;
</div>
<div id="Right_column" style="background-color:#666E73; width:60px; margin-left:0px">
<?
$RC = new RightColumnAdmin;
?>
</div>
<div id="mainContent" style="padding-left:3px; margin-left:70px ">

<?
//print_r($model->GetStructure1());
?>
<?
echo $form;
?>

</div><!--<div id="mainContent" style="padding-left:3px; ">-->


<div style="height: 5px; clear:both">&nbsp;</div>

