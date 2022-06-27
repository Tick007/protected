
<script>
function go_catalog(group, car_id, car_val){
//alert ('Добавлено в корзину');
str="/product/list?show_group="+group;
//alert (str);
document.getElementById("ListForm").action=str;
el="ListForm[cfid_arr]["+car_id+"]["+car_val+"]";
//alert (el);
document.getElementById("insert_here").innerHTML="<input type='hidden' value='1' name='"+el+"' id ='"+el+"' >";
//document.getElementById(el).value=car_val;
document.getElementById(el).checked=true;
//alert (document.getElementById(el).value);
document.forms.ListForm.submit();
}


</script>
<div id="Right_column">

<?

//echo $group_obj->title;

if(isset($group_obj->title)) $this->pageTitle=$group_obj->title;
else $this->pageTitle= $group_obj->category_name;
if (isset($group_obj->description) )$this->pageDescription=$group_obj->description;
else $this->pageDescription=str_replace(' -> ', ', ', $title_path);
if (isset($group_obj->keywords)) $this->pageKeywords = $group_obj->keywords;

$RC = new RightColumn(2,'L');

?>
</div>
<div id="mainContent">
<?
$path_text = $this->get_productiya_path($show_group);

if (@trim($path_text)) echo $path_text;
?>
<h1><?php
if(trim($group_obj->title)!='') echo $group_obj->title;
else echo $group_obj->category_name;
?></h1>
<?php

if (count($gruppa_files)>0)  {
CController::renderPartial( 'gruppa_files', array('gruppa_files'=>$gruppa_files));
//echo "<div style=\"clear:both\">&nbsp;</div>";
}

if (count($models)>0) CController::renderPartial( 'subs', array('models'=>$models, 'group_obj'=>$group_obj));
?><br>
<?php
if(isset($group_obj->page) AND isset($group_obj->page->contents)) echo $group_obj->page->contents;
?>
    </div>

