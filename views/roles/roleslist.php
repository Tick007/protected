<script language="javascript">
function select_auth_type(auth_type_field_value){  /////////Сюда попадаем кода выбираем тип создаваемого объекта, и если тип роль, то нужно 
//arr = transport.responseText.split('#');
//alert(auth_type_field_value);
//descr = 'descr_'+id;
//gr='gr_'+id;

//arr=html.split("@");
//document.getElementById(descr).innerHTML=arr[0];
//document.getElementById(gr).innerHTML=arr[1];
if (auth_type_field_value==2) {/////////роль
document.getElementById('role_name').style.display='';
document.getElementById('create_item_button').style.display='';
document.getElementById('role_select').style.display='none';
}
if (auth_type_field_value==1 || auth_type_field_value==0) {//////////задача
document.getElementById('create_item_button').style.display='none';
document.getElementById('role_name').style.display='none';
document.getElementById('role_select').style.display='';
}
document.getElementById('operation_name').style.display='none';
document.getElementById('task_name').style.display='none';
document.getElementById('task_select').style.display='none';
//if (auth_type_field_value==0) {/////////Операция

//}
//edit_my_file(id);
}////////////function select_auth_type(auth_type_field_value){  ////////

function fill_tasks(html) {////////////Получили список задач для выбранной роли
//alert(document.getElementById('role_select').value);
document.getElementById('task_select').style.display='none';
if (document.getElementById('auth_item_type').value==1) {
		document.getElementById('task_select').style.display='none';
		//document.getElementById('task_select').innerHTML=html;
		document.getElementById('task_name').style.display='';
		document.getElementById('create_item_button').style.display='';
		//document.getElementById('operation_name').style.display='none';
	}
if (document.getElementById('auth_item_type').value==0) {
		document.getElementById('task_select').style.display='';
		document.getElementById('task_select').innerHTML=html;
		document.getElementById('task_name').style.display='none';
		document.getElementById('create_item_button').style.display='none';
		//document.getElementById('operation_name').style.display='';
	}		
	
}//////////function fill_tasks(html) {/////////

function fill_operation(html) {
	document.getElementById('operation_name').style.display='';
	document.getElementById('create_item_button').style.display='';
}
</script>


<div id="ribbon" style="margin-left:71px">
<?

$this->widget('zii.widgets.CBreadcrumbs', array(
    'links'=>array(
        'Администрирование',
		'Управление правами доступа',
        
    ),
));

?>
</div>
<div id="Right_column" style="background-color:#666E73; width:60px; margin-left:0px; float:left">
<?
echo CHtml::link('Роли - таблица',array('/roles/list/')); ?><br><?
echo CHtml::link('Пользователи',array('/roles/index/')); 
echo CHtml::link('Роли - управление',array('/roles/roleslist/')); ?>
<?
$RC = new RightColumnAdmin;
?>
</div>

<div id="mainContent" style="padding-left:3px; margin-left:70px">

 <h2>Список объектов</h2>
 <div class="actionBar">
 <?
 echo CHtml::beginForm($action='/roles/addauthitem/', $method='post', $htmlOptions=array('name'=>'item_creation', 'enctype'=>'multipart/form-data', 'id'=>'form1')); 
 ?>
<table width="auto" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>Создание объекта&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td><?
 $auth_types_list = array('4'=>'Создание объекта типа',
 										'2'=>'Роль',
										'1'=>'Задача',
										'0'=>'Операция'			);
	echo CHtml::dropDownList('auth_item_type', 4 , $auth_types_list,   array ('onChange' => '{select_auth_type(value)}') ); 
	?></td>
    <td><?
    $roles_list=array('0'=>'Выбор роли');
    for ($i=0; $i<count($roles); $i++) $roles_list[$roles[$i]->name]=$roles[$i]->name;
	?>	</td>
    <td>&nbsp;&nbsp;&nbsp;&nbsp;<?
	 echo CHtml::dropDownList('role_select', 0 , $roles_list, array ('id'=>'role_select', 'style'=>'display:none', 'ajax' => array('type'=>'POST', 'url'=>CController::createUrl('/roles/gettasklist/') , 'success' => 'function(html){fill_tasks(html)}' ) ) ); ?>
      <div id="role_name" style="display:none">&nbsp;&nbsp;&nbsp;&nbsp;<?
    echo CHtml::textField('role_name', time());
	?></div>     </td>
    <td>&nbsp;&nbsp;&nbsp;&nbsp;<?
	 echo CHtml::dropDownList('task_select', 0 , array('0'=>'выбор'), array ('id'=>'task_select', 'style'=>'display:none', 'onChange' => '{fill_operation(value)}'  ) ); ?>
      <div id="task_name" style="display:none">&nbsp;&nbsp;&nbsp;&nbsp;<?
    echo CHtml::textField('task_name', time());
	?></div> </td>
    <td> &nbsp;&nbsp;&nbsp;&nbsp;<div id="operation_name" style="display:none"><?
    echo CHtml::textField('operation_name', time());
	?></div></td>
    <td><?
	echo "&nbsp;&nbsp;&nbsp;&nbsp;";
    echo CHtml::submitButton('Создать', $htmlOptions=array ('name'=>'create_item_button', 'id'=>'create_item_button', 'style'=>'display:none'));
	?></td>
  </tr>
  <tr>
    <td colspan="7">*&nbsp;выберете значение из списка что бы начать добавление нового объекта</td>
    </tr>
</table>





	
  
     
   
    
    
<?php echo CHtml::endForm();
?>


</div>
<?php echo CHtml::beginForm($action='/roles/update?page='.Yii::app()->getRequest()->getParam('page', NULL), $method='post', $htmlOptions=array('enctype'=>'multipart/form-data')); 
//echo CHtml::hiddenField('page', Yii::app()->getRequest()->getParam('page') );
?>
 <?php  $this->widget('CLinkPager',array('pages'=>$pages)); ?><br><br>
 <table width="100%" border="0" cellspacing="1" cellpadding="1"  bgcolor="#666666">
  <tr>
    <td bgcolor="#d5a73f">Роли</td>
    <td bgcolor="#d5a73f">&nbsp;</td>
    </tr>
<?
for ($i=0; $i<count($roles); $i++) {
echo "  <tr bgcolor=\"#FFFFFF\">";
echo "<td>".$roles[$i]->name."</td><td><table border=1 cellspacing=\"1\" cellpadding=\"1\"><tr><td bgcolor=\"#d5a73f\">Задачи</td><td bgcolor=\"#d5a73f\">Операции</td></tr>";
	for ($k=0; $k<count($roles[$i]->child_items); $k++)  {
			echo "<tr><td valign=\"top\">".$roles[$i]->child_items[$k]->name.'</td><td>';
			echo "<table>";
			for ($f=0; $f<count($roles[$i]->child_items[$k]->child_items); $f++ ) echo "<tr><td>".$roles[$i]->child_items[$k]->child_items[$f]->name."</td></tr>";
			echo "</table>";
			//print_r($roles[$i]->child_items[$k]->child_items);
			echo "</td></tr>";
	}//////////for ($k=0; $k<count($roles[$i]->child_items); $k++)  {
echo "</table></td>";
 echo "</tr>";
}
?>
</table>
<br> <br> 
<div align="right">
<?
 echo CHtml::submitButton('Сохранить', $htmlOptions=array ('name'=>'savegood' ));
 ?></div>
<?php echo CHtml::endForm();
?>
<br><br>
<?php  $this->widget('CLinkPager',array('pages'=>$pages)); ?>
</div>
