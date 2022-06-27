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
		'Управление правами',
        
    ),
));

?>
</div>
<div id="Right_column" style="background-color:#666E73; width:60px; margin-left:0px; float:left">
<?
$RC = new RightColumnAdmin;
?>
</div>

<div id="mainContent" style="padding-left:3px; margin-left:70px">
 <?

 ?>
 <?
 echo CHtml::beginForm($action='/roles/addauthitem/', $method='post', $htmlOptions=array('name'=>'item_creation', 'enctype'=>'multipart/form-data', 'id'=>'form1')); 
 ?>
<table width="auto" border="0" cellspacing="1" cellpadding="1">
  <tr>
    <td width="150" height="60" valign="bottom">Создание объекта&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td width="150" valign="bottom"><?
 $auth_types_list = array('4'=>'Создание объекта типа',
 										'2'=>'Роль',
										'1'=>'Задача',
										'0'=>'Операция'			);
	echo CHtml::dropDownList('auth_item_type', 4 , $auth_types_list,   array ('onChange' => '{select_auth_type(value)}') ); 
	?></td>
   <?
    $roles_list=array('0'=>'Выбор роли');
    for ($i=0; $i<count($roles); $i++) $roles_list[$roles[$i]->name]=$roles[$i]->name;
	?>	
    <td width="150" valign="bottom">&nbsp;&nbsp;&nbsp;&nbsp;<?
	 echo CHtml::dropDownList('role_select', 0 , $roles_list, array ('id'=>'role_select', 'style'=>'display:none', 'ajax' => array('type'=>'POST', 'url'=>CController::createUrl('/roles/gettasklist/') , 'success' => 'function(html){fill_tasks(html)}' ) ) ); ?>
      <div id="role_name" style="display:none">&nbsp;&nbsp;&nbsp;&nbsp;<?
    echo CHtml::textField('role_name', time());
	?></div>     </td>
    <td width="150" valign="bottom">&nbsp;&nbsp;&nbsp;&nbsp;<?
	 echo CHtml::dropDownList('task_select', 0 , array('0'=>'выбор'), array ('id'=>'task_select', 'style'=>'display:none', 'onChange' => '{fill_operation(value)}'  ) ); ?>
      <div id="task_name" style="display:none">&nbsp;&nbsp;&nbsp;&nbsp;<?
    echo CHtml::textField('task_name', time());
	?></div> </td>
    <td width="150" valign="bottom"> &nbsp;&nbsp;&nbsp;&nbsp;
      <div id="operation_name" style="display:none"><?
    echo CHtml::textField('operation_name', time());
	?></div></td>
    <td width="150" valign="bottom"><?
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
<br><br>
<?
 echo CHtml::beginForm($action='/roles/manageauthitems/', $method='post', $htmlOptions=array('name'=>'item_creation', 'enctype'=>'multipart/form-data', 'id'=>'form1')); 
 ?>
 <table width="auto" border="0" cellspacing="1" cellpadding="1">
  <tr>
    <td valign="top">
    <!--Таблица с ролями-->
     <table width="100%" border="0" cellspacing="1" cellpadding="1"  bgcolor="#666666">
  <thead>
  <tr>
    <th >Роли</th>
    <th >Задания/Операции</th>
    </tr></thead>
<?
for ($i=0; $i<count($roles); $i++) {
echo "  <tr bgcolor=\"#FFFFFF\">";
echo "<td>".$roles[$i]->name."</td><td><table border=0 width=\"100%\" bgcolor=\"#CCCCCC\" cellspacing=\"1\" cellpadding=\"1\"><thead><tr><th width=\"50%\" >Задачи</th><th>Операции</th></tr></thead>";
	for ($k=0; $k<count($roles[$i]->child_items); $k++)  {
			echo "<tr bgcolor=\"#FFFFFF\"><td valign=\"top\">".$roles[$i]->child_items[$k]->name.'</td><td>';
			if ($roles[$i]->child_items[$k]->child_items !=NULL ) {
			echo "<table border=\"0\">";
			for ($f=0; $f<count($roles[$i]->child_items[$k]->child_items); $f++ ) echo "<tr><td>".$roles[$i]->child_items[$k]->child_items[$f]->name."</td><td></tr>";
			echo "</table>";
			}
			else if ($roles[$i]->child_items[$k]->child_items ==NULL ) {
			 $operation_values_list = NULL;
							$operation_values_list=array(
							'0'=>'...выбрать',
							//'delete_link'=>'удалить связь',
							);
							for ($n=0; $n<count($tasks); $n++) $operation_values_list[$tasks[$n]->name]='в '.$tasks[$n]->name;
							for ($n=0; $n<count($roles); $n++) {
							if ($roles[$n]->name != $roles[$i]->name) $operation_values_list[$roles[$n]->name]='перенос в '.$roles[$n]->name;
							}
							echo CHtml::dropDownList('manage_operation['.$roles[$i]->name.']['.$roles[$i]->child_items[$k]->name.']',0, $operation_values_list);
			}////////if ($roles[$i]->child_items[$k]->child_items !=NULL) {
			
			//print_r($roles[$i]->child_items[$k]->child_items);
			echo "</td></tr>";
	}//////////for ($k=0; $k<count($roles[$i]->child_items); $k++)  {
	if (count($roles[$i]->child_items)==0) {///////////////Добавляем возможность привязать к роли задания
	echo "<tr><td colspan=\"2\">";
							$operation_values_list=array(
							'0'=>'...выбрать',
							'delete'=>'удалить',
							);
							for ($n=0; $n<count($tasks); $n++) {
								$operation_values_list[$tasks[$n]->name]='привязать к '.$tasks[$n]->name;
							}
							echo CHtml::dropDownList('manage_role['.$roles[$i]->name.']',0, $operation_values_list);		
	echo "</td></tr>";
	}
echo "</table></td>";
 echo "</tr>";
}
?>
</table>
    
    </td>
    <td valign="top">
<!--Таблица с taskами-->
  <table width="auto" border="0" cellspacing="1" cellpadding="1" bgcolor="#333333">
  <thead>
  <tr>
    <th width="200" rowspan="2">Роли</th>
    <th colspan="2">Задания</th>
    <th width="200" rowspan="2">Операции</th>
  </tr>
  <tr>
    <th width="100">Наименование</th>
    <th width="100">Добавить в роль</th>
    </tr>
  </thead>
  <?
  
  for ($i=0; $i<count($allroles); $i++) $allroleslist[]=$allroles[$i]->name;
  
  for($i=0;$i<count($tasks); $i++) {
  
?>
     <tr bgcolor="#FFFFFF">
    <td>
    
    <table width="100%" border="0" cellspacing="1" cellpadding="1">
  <thead><tr>
    <th width="55%">Роль</h>
    <th>Удалить из роли</th>
  </tr></thead>

<?
   // for($h=0; $h<0)
   //print_r(count($allroles));
   

   //print_r($tasks[$i]->roles);
   if (isset ($tasks[$i]->roles)) {
			for($g=0; $g<count($tasks[$i]->roles); $g++) {
					//echo $tasks[$i]->roles[$g]->name;
					$task_belongs_roles[$tasks[$i]->name][]=$tasks[$i]->roles[$g]->name;
					echo "<tr><td>".$tasks[$i]->roles[$g]->name.'</td>';
					echo "<td align=\"center\">";
					echo CHtml::checkBox('delete_from_role['.$tasks[$i]->roles[$g]->name.']['.$tasks[$i]->name.']');
					echo '</td></tr>';
					
			}///////////////////for($g=0; $g<count($tasks[$i]->roles); $g++) {
   }//////////////  if (isset ($tasks[$i]->roles)) {
 //  echo '<br>';
 // print_r($task_belongs_roles[$tasks[$i]->name]);
/* 
  for ($h=0; $h<count($allroles); $h++) {
 //echo $h;
 echo CHtml::checkBox('role['.$allroles[$h]->name.']', in_array($allroles[$h]->name, $task_belongs_roles[$tasks[$i]->name]) ? '1':'' ).$allroles[$h]->name.'<br>';
  //echo 'ewwer<br>';
  } ////////// for ($h=0; $h<count($allroleslist); $h++) {
	*/
	?></table>    </td>
    <td align="center" valign="middle"><?
    echo $tasks[$i]->name;
	?></td>
    <td align="center"><?
    		//print_r($task_belongs_roles[$tasks[$i]->name]);
			//echo '<br><br>';
			//print_r($allroleslist);
			//echo '<br><br>';
			$possible_roles = NULL;
			if (is_array($task_belongs_roles[$tasks[$i]->name])==true) $possible_roles = array_diff ($allroleslist, $task_belongs_roles[$tasks[$i]->name]);
			else $possible_roles = $allroleslist;
			//print_r($possible_roles);
			//echo count($possible_roles);
			//echo '<br><br>';
			$possible_roles_list=NULL;
			 $possible_roles_list['0']='Выбор';
	 		foreach($possible_roles as $key=> $value):
					 $possible_roles_list[$value]=$value;
					//echo $key.' - '.$value.';<br>';
			endforeach;
			if (count($possible_roles)>0) echo CHtml::dropDownList('add_task['.$tasks[$i]->name.']',0, $possible_roles_list);
			else echo '<strong>Данное задание <br>относится ко всем ролям</strong>';
			//print_r($possible_roles_list);
	?></td>
    <td><table width="100%" border="0" cellspacing="1" cellpadding="1">
  <thead><tr>
    <th width="35%">Операция</h>
    <th width="30%">Действия</th>
    </tr></thead>
<?
    		if (isset($tasks[$i]->operations)) {
					for ($k=0; $k<count($tasks[$i]->operations); $k++) {
							echo '<tr><td>'.$tasks[$i]->operations[$k]->name."</td><td align=\"center\">";
							//echo CHtml::checkBox('delete_operation['.$tasks[$i]->name.']['.$tasks[$i]->operations[$k]->name.']');
							$operation_values_list = NULL;
							$operation_values_list=array(
							'0'=>'...выбрать',
							'copy'=>'Копировать',
							'delete_link'=>'удалить связь',
							'delete'=>'удалить',
							);
							for ($n=0; $n<count($tasks); $n++) {
								if ($tasks[$n]->name != $tasks[$i]->name) $operation_values_list[$tasks[$n]->name]='в '.$tasks[$n]->name;
							}
							for ($n=0; $n<count($roles); $n++) {
							$operation_values_list[$roles[$n]->name]='перенос в '.$roles[$n]->name;
							}
							
							echo CHtml::dropDownList('manage_operation['.$tasks[$i]->name.']['.$tasks[$i]->operations[$k]->name.']',0, $operation_values_list);
							echo '</td></tr>';
					}/////////////for ($k=0; $k<count($tasks[$i]->operations); $k++) {
			}////////////////////////////if (isset($tasks[$i]->operations)) {
	?>
   </table></td>
  </tr>
     
  <?
    }/////////  for($i=0;$i<count($tasks); $i++) {
  ?>
  
</table></td>
  </tr>
  <tr>
       <td colspan="2" align="center"><?
        echo CHtml::submitButton('Сохранить', $htmlOptions=array ('name'=>'saveitems' ));
	   ?></td>
     </tr>
</table>

<?php echo CHtml::endForm();
?>
</div>