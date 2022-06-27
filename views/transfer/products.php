<h2>Товары</h2>
<?php
echo CHtml::form(array('transfer/transferproducts'));
//print_r($categories_list);
echo '<br>';
echo CHtml::submitButton('wrwerwer');
?>
<table width="500px" border="1">
<tr>
	<td>Имя в друпале</td>
	<td>Группа;</td>
	<td>&nbsp;</td>
	<td>Имя в YII</td>
	<td>Картинка</td>
	<td>Дочерняя в yii</td>
</tr>
<?php
for($i=0; $i<count($models); $i++) {
?>
<tr>
	<td><?php echo $models[$i]['model'] ?></td>
    <td><?php echo $models[$i]['cat_name'] ?></td>
    <td><?php echo $models[$i]['title'] ?></td>
    <td><?php
	//echo strtolower(trim($models[$i]['parent']));
	$qqq = strtolower(trim($models[$i]['cat_name']));
	//echo '- ' . $qqq;

	if(isset($categories_list[$qqq])) {
			
		//print_r($categories_list[$qqq]);
		if(isset($categories_list[$qqq]['category_id']) AND isset($this->sootnosh[$models[$i]['model']]) ) echo $this->createproduct($categories_list[$qqq]['category_id'],$this->sootnosh[$models[$i]['model']], $models[$i]['title'] );
			
	}

	?></td>
    <td></td>
    <td></td>
</tr>
<?php
}
?>
</table>