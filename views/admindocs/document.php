<?
$clientScript=Yii::app()->clientScript;
$clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/highslide/highslide-with-html.js', CClientScript::POS_HEAD);
$clientScript->registerCssFile('/js/highslide/highslide.css');
?>
<script type="text/javascript">
hs.graphicsDir = '/js/highslide/graphics/';
hs.outlineType = 'rounded-white';
hs.wrapperClassName = 'draggable-header';
</script>

<script>

function myfunc(kontragent_id){//////////Вызов обновления списка со складами
//alert('This is myfunc()');
 jQuery.ajax({'type':'POST','url':'/admindocs/storelist/','cache':false,'data':'kontragent_id='+kontragent_id,'success':function(html){jQuery("#store_doc_ca").html(html)}});
}

$(document).ready(function(){
//$("#faq-answer").animate({ opacity: "hide" }, "fast");
$("#kontragent_id").change(function(){
 // alert( $(this).text() );
 //myfunc();
 jQuery.ajax({'type':'POST','url':'/admindocs/storelist/','cache':false,'data':jQuery(this).parents("form").serialize(),'success':function(html){jQuery("#store_doc_ca").html(html)}})

 
}).change();

		$("#qqq").click(function(){
				//alert('1234');
				//jQuery.ajax({'type':'POST','url':'/admindocs/storelist/','cache':false,'data':jQuery(this).parents("form").serialize(),'success':function(html){jQuery("#store_doc_ca").html(html)}})
				myfunc(6);
		})

});

function displaypopup(url){
window.open (url,"mywindow","location=0,status=0,scrollbars=1,width=600,height=600");
}

</script>

<?
echo CHtml::beginForm(array('/admindocs/updatedocument/'.$doc_id),  $method='post',$htmlOptions=array('name'=>'doc_form', 'id'=>'doc_form'));  
?>
<div id="ribbon">&nbsp;
<?
 if (@$doc->doc_status==0 OR @$doc->doc_status==1) {
?>
<input name="add_product" type="hidden" id="add_product" >
<a href="#" onclick="{displaypopup('/nomenklatura?targetitem=add_product&targetform=doc_form')}">
Подбор номенклатуры</a>
<?
}
?>
</div>

<div id="Right_column" style="background-color:#666E73; width:60px; margin-left:0px; float:left">
<?
$RC = new RightColumnAdmin;
?>
</div>
<div id="mainContent" style="padding-left:3px; margin-left:70px ">
<?
echo $errors;
?>
<table width="600" border="0" cellspacing="2" cellpadding="1" background="/images/2x2.png">
  <tr  bgcolor="#fffbf0">
    <th align="left" scope="row">Номер</th>
    <td><?
    echo $doc->id;
	?></td>
    <td align="right">от</td>
    <td align="left"><?
    echo $doc->date_dt;
	?></td>
    <td>&nbsp;</td>
    </tr>
  <tr bgcolor="#fffbf0">
    <th align="left" scope="row">Организация</th>
    <td><?
    
	?></td>
    <td>&nbsp;</td>
    <td align="right">склад<br>
      организации</td>
    <td><?
    echo CHtml::dropDownList('store_id', $doc->store_id, $stores_list);
	?></td>
    </tr>
  <tr bgcolor="#fffbf0">
    <th align="left" scope="row">Контрагент</th>
    <td><?
    echo CHtml::dropDownList('kontragent_id', $doc->kontragent_id, $contr_agent_list, array ('ajax' => array('type'=>'POST', 'url'=>CController::createUrl('/admindocs/storelist/'), 'update'=>'#store_id_ca') ), $htmlOptions=array('encode'=>false) );
	?></td>
    <td><?
echo  CHtml::link('подбор',array('/nomenklatura/contragents/', 'targetitem'=>'kontragent_id', 'targetform'=>'doc_form') , array('onclick'=>"return hs.htmlExpand(this, { objectType: 'iframe' } )")); 
?></td>
    <td align="right">Склад контрагента</td>
    <td><!--<select name="store_doc_ca" id="store_doc_ca" style="width:150px" size="1">
    </select>-->
   <?
   //print_r($ca_stores_list);
    echo CHtml::dropDownList('store_id_ca', $doc->store_id_ca, (count($ca_stores_list)>0)? $ca_stores_list: array('0'=>'выбери контрагента'));
	?></td>
    </tr>
  
  <tr bgcolor="#fffbf0">
    <th align="left" scope="row">&nbsp;</th>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td align="right">&nbsp;</td>
    <td>&nbsp;</td>
    </tr>
  <tr bgcolor="#fffbf0">
    <th align="left" scope="row">Валюта</th>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td align="right">&nbsp;</td>
    <td>&nbsp;</td>
    </tr>
  <tr bgcolor="#fffbf0">
    <th align="left" scope="row">Статус</th>
    <td><?
    if (@$doc->doc_status==2) echo "<img src=\"/images/apply.png\">";
	else  echo "<img src=\"/images/stop.png\">";
	?></td>
    <td>&nbsp;</td>
    <td align="right">&nbsp;</td>
    <td>&nbsp;</td>
    </tr>
</table>
<?

//////////////////////////////////////////Рисуем табки с фотограйиями
$tab = new CTabView;
$tab->tabs=array(
    'tab1'=>array(
          'title'=>'Номенклатура',
          'view'=>'table_part',
          'data'=>array('models'=>$models),
    ),
	
);


//if(isset($_GET['activetab'])  ) $tab->activeTab = $_GET['activetab'];
$tab->run();
?>
<div align="right">
<?
     if (@$doc->doc_status==1 OR @$doc->doc_status==0) echo CHtml::submitButton('Сохранить', $htmlOptions=array ('name'=>'savepricelist' , 'alt'=>'Сохранить', 'title'=>'Сохранить'));

	?>
<?
    if (@$doc->doc_status==2)  echo CHtml::submitButton('Отмена проведения', $htmlOptions=array ('name'=>'abortapply' , 'alt'=>'Отмена проведения', 'title'=>'Отмена проведения'));
	else echo CHtml::submitButton('Провести', $htmlOptions=array ('name'=>'apply' , 'alt'=>'Провести', 'title'=>'Провести'));
	?>   
</div>
</div>
<!--<div id="mainContent" style="padding-left:3px; ">-->


<div style="height: 5px; clear:both">&nbsp;</div>
<?php echo CHtml::endForm(); ?>

<script>


function change_fields(html,id ){
//arr = transport.responseText.split('#');
//alert(html);
descr = 'descr_'+id;
gr='gr_'+id;

arr=html.split("@");
document.getElementById(descr).innerHTML=arr[0];
document.getElementById(gr).innerHTML=arr[1];
if (arr[0]=="<font color=#FF0000>Удаленно!!!</font>") {
el = 'edit_file_bt_'+id;
document.getElementById(el).disabled=true;
}
edit_my_file(id);
}

</script>