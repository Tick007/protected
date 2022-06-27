<?
echo "<br>";
//$auth=Yii::app()->authManager;


//print_r($models);
?>
<ul>
<li>2 - роль</li>
<li>0 - операция</li>
<li>1 - задача</li>
</ul>
<table width="100%" border="0" cellspacing="1" cellpadding="1" bgcolor="#CCCCCC">
  <tr>
    <td>Единица</td>
    <td>Тип</td>
    <td>Список задач(tasks)/операций(operations)</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <?
  for($i=0; $i<count($models); $i++) {
		echo "  <tr bgcolor=\"#FFFFFF\">
    <td>".$models[$i]->name."</td>
    <td>".$models[$i]->type."</td>
    <td>";
	//print_r($models[$i]->child_items);
	$num_of_includes = count($models[$i]->child_items);
	if ($num_of_includes>0) {//////////////Если есть вложенные файлы
			for ($k=0; $k<$num_of_includes; $k++) {
				    	echo $models[$i]->child_items[$k]->name.'; ';
			}////////////////for ($k=0; $models[$i]->files; $k++) {
	}////////////if ($num_of_includes>0) {
	echo "</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
	 <td>&nbsp;</td>
  </tr>
   <tr>
    <td colspan=\"6\" height=\"1\" bgcolor=\"#000000\"></td>
  </tr>
";
}/////////////////for($i=0; $i<count($models); $i++) {
?>
</table>
<br>
Username:<?=Yii::app()->user->name?><br>
Userrole:<?=Yii::app()->user->getState('role')?><br><br><br>

Выполняем проверки:
<?=Yii::app()->user->name?>(<?=Yii::app()->user->getState('role')?>) удаляет пост Yii::app()->user->checkAccess('Delete User')
<br>Результат: 
<?
$qq = Yii::app()->user->checkAccess('Delete User');
echo var_dump($qq);
?><br><br>
<?=Yii::app()->user->name?>(<?=Yii::app()->user->getState('role')?>)  смотрит пост Yii::app()->user->checkAccess('Edit Post')
<br>Результат: 
<?
$qq = Yii::app()->user->checkAccess('Edit Post');
echo var_dump($qq);
?><br><br>

<?=Yii::app()->user->name?>(<?=Yii::app()->user->getState('role')?>)  смотрит пост Yii::app()->user->checkAccess('Правка товаров')
<br>Результат: 
<?
$qq = Yii::app()->user->checkAccess('Правка товаров');
echo var_dump($qq);
?><br><br>

<?=Yii::app()->user->name?>(<?=Yii::app()->user->getState('role')?>)  смотрит пост Yii::app()->user->checkAccess('View Post')
<br>Результат: 
<?
$qq = Yii::app()->user->checkAccess('View Post');
echo var_dump($qq);
?><br><br><br>

?><br><br>
<?=Yii::app()->user->name?>(<?=Yii::app()->user->getState('role')?>)  смотрит пост Yii::app()->user->checkAccess('file_reader')
<br>Результат: 
<?
$qq = Yii::app()->user->checkAccess('file_reader');
echo var_dump($qq);
?><br><br><br>


<?
//echo Yii::app()->params->drupal_vars['taxonomy_catalog_level'];
//echo Yii::app()->user->getState("role");
/*
$email = Yii::app()->email;
$email->to = 'igor.ivanov@novline.com';
$email->subject = 'Hello';
$email->message = 'Hello brother';
$email->send();
*/
//print_r($auth->authItems);

//$USER_ROLE =  Yii::app()->user->getState('role');

//echo 'роль - '.$USER_ROLE;

//echo "<br><br><br>";
/*
foreach($auth->authItems as $key=>$val)
				{
							echo "$key - ";
							print_r($val->type);
							echo "<br><br>";
					}
			unset($key,$val);
*/


?>
<?php // $this->widget('application.extensions.email.debug'); ?>