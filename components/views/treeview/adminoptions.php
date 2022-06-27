<script>
//$('#show_tree').click(function() { 
jQuery('body').delegate('#show_tree','click',function(){
		//alert('Hello World');
		//$('#colapsed_tree').style.display='';
		//document.getElementById('colapsed_tree').style.display='';
		$("#colapsed_tree").toggle();
 });
 jQuery('body').delegate('#colapsed_tree','click',function(){
		//alert('Hello World');
		//$('#colapsed_tree').style.display='';
		//document.getElementById('colapsed_tree').style.display='';
		//$("#colapsed_tree").hide();$(this).val()
 });
 
 $(document).ready(function () {
		$('#search_option').keyup(function() {
		 // alert('Handler for .change() called.');
		 //console.log($(this).val());
		 jQuery.ajax({
						'type':'POST',
						'url':'<?php echo Yii::app()->createUrl('nomenklatura/getgroupsoptions', array())?>',
						'cache':false,
						'data': 'search_option='+$(this).val(),
						'success':function(html){
							if (html!='error') {
									
									document.getElementById('tree_options').innerHTML=html;
										
							}
						}
	});
		 
		});
 });
 
 function get_options(category_id){
	jQuery.ajax({
						'type':'POST',
						'url':'<?php echo Yii::app()->createUrl('nomenklatura/getgroupsoptions', array())?>',
						'cache':false,
						'data': 'category_id='+category_id,
						'success':function(html){
							if (html!='error') {
									
									document.getElementById('tree_options').innerHTML=html;
										
							}
						}
	});
} //////function get_options(c
 
 
 
</script>

<div style="border-bottom:1px solid #666; width:100%; background-color:#DAFEEF">
<form action="" method="post" name="form">
<label><strong><font size="+1">Найти</font></strong></label>
<input name="search_option" id="search_option" type="text" class="textfield" size="30" maxlength="20" placeholder="Поиск" style="padding-left: 2px; ">
</form></div>

<div class="blockheader">Каталог</div>
<?php

$this->widget(
			'CTreeView',
		array(
		//'url' => array('ajaxFillTree'),//////////////////При использовании ажакса не запоминает открытые узлы
			'data'=>$treee, // передаем массив
   		    'animated'=>'fast', // скорость анимации свертывания/развертывания
   		    'collapsed'=>true, // если тру, то при генерации дерева, все его узлы будут свернуты
    		 'persist'=>'cookie',
			   'unique'=>true)
		);

?>
