<?php
if($form->hasErrors()==true) {
	echo '<pre>';
	print_r($form->errors);
	echo '</pre>';
}
?>
<table  border="0" cellspacing="1" cellpadding="1" class="plain" bgcolor="#003366" width="auto">
<thead>
        <tr bgcolor="#EAE5D8"  class="fixed">
          <th colspan="4" align="left">Пользователь:&nbsp;<?=$user->login?></th> 
          </tr>
        </thead>
        <tr bgcolor="#CFC7AD">
    <td>Логин</td>
    <td colspan="3"><?
    echo CHtml::textField('main_user_params[login]',$user->login, $htmlOptions=array('encode'=>false, 'size'=>30) );
	?></td>
    </tr>
  <tr bgcolor="#E9E5D9">
    <td>Электронная почта</td>
    <td colspan="3"><?
    echo CHtml::textField('main_user_params[client_email]',$user->client_email, $htmlOptions=array('encode'=>false, 'size'=>30) );
	?></td>
    </tr>
  <tr bgcolor="#CFC7AD">
    <td>ФИО</td>
    <td><?
    echo CHtml::textField('main_user_params[second_name]',$user->second_name, $htmlOptions=array('encode'=>false, 'size'=>25) );
	?></td>
    <td><?
    echo CHtml::textField('main_user_params[first_name]',$user->first_name, $htmlOptions=array('encode'=>false, 'size'=>25) );
	?></td>
    <td><?
    echo CHtml::textField('main_user_params[last_name]',$user->last_name, $htmlOptions=array('encode'=>false, 'size'=>25) );
	?></td>
  </tr>
  <tr bgcolor="#E9E5D9">
    <td>Новый пароль</td>
    <td colspan="3"><?
    echo CHtml::textField('main_user_params[client_password]',$user->client_password, $htmlOptions=array('encode'=>false, 'size'=>30) );
	?></td>
  </tr>
 <tr bgcolor="#CFC7AD">
    <td>Роль</td>
    <td colspan="3"><?
    echo CHtml::dropDownList('user_role' ,$user->authassignment->itemname, $roles_list, array('width'=>'200px'))
	?></td>
    </tr>
   <tr bgcolor="#E9E5D9">
    <td>Телефоны</td>
    <td colspan="3"><?
    echo CHtml::textField('main_user_params[client_tels]',$user->client_tels, $htmlOptions=array('encode'=>false, 'size'=>30) );
	?></td>
    </tr>
    
    <tr bgcolor="#E9E5D9">
      <td bgcolor="#CFC7AD">Город</td>
      <td colspan="3" bgcolor="#CFC7AD"><?
    echo CHtml::textField('main_user_params[client_city]',$user->client_city, $htmlOptions=array('encode'=>false, 'size'=>30) );
	?></td>
  </tr>
    <tr bgcolor="#E9E5D9">
    <td>Почтовый индекс</td>
    <td colspan="3"><?
    echo CHtml::textField('main_user_params[client_post_index]',$user->client_post_index, $htmlOptions=array('encode'=>false, 'size'=>30) );
	?></td>
    </tr>
    
    <tr bgcolor="#CFC7AD">
     <td bgcolor="#CFC7AD">Юрлицо текст</td>
     <td colspan="3"><?
    echo CHtml::textField('main_user_params[urlico_txt]',$user->urlico_txt, $htmlOptions=array('encode'=>false, 'size'=>30) );
	?></td>
   </tr>
   <tr bgcolor="#E9E5D9">
     <td>&nbsp;</td>
     <td colspan="3">&nbsp;</td>
   </tr>
    <tr bgcolor="#CFC7AD">
     <td>&nbsp;</td>
     <td colspan="3">&nbsp;</td>
   </tr>
   <tr bgcolor="#E9E5D9">
     <td>&nbsp;</td>
     <td colspan="3">&nbsp;</td>
   </tr>
    <tr bgcolor="#CFC7AD">
     <td>Статус</td>
     <td colspan="3"><?
    echo CHtml::checkBox('main_user_params[status]', ($user->status == 1) ? $checked=true : $checked=false);
	?></td>
   </tr>
    </table>

