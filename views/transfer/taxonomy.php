<?php
echo CHtml::form(array('transfer/transfertaxonomy'));
//print_r($categories_list);
echo '<br>';
echo CHtml::submitButton('wrwerwer');
?>
<table width="500px" border="1">
<tr>
	<td>Имя в друпале</td>
	<td>Путь</td>
	<td>&nbsp;</td>
	<td>Подгруппа;</td>
	<td>&nbsp;</td>
	<td>Имя в YII</td>
	<td>Картинка</td>
	<td>Дочерняя в yii</td>
</tr>
<?php
for($i=0; $i<count($models); $i++) {
	//if ($models[$i]['parent_tid']!=9) {
	?>
	<tr>
    <td><?php echo $models[$i]['parent'] ?></td>
    <td><?php echo $models[$i]['filepath'] 
	?></td>
    <td><?php
	echo $file = 'C:/wwwroot/yii-construct/html/'.$models[$i]['filepath'];
    if (isset($models[$i]['filepath']) AND is_file($file ) AND file_exists($file)) echo "<img src=\"http://yii-construct/".$models[$i]['filepath'] ."\">";
	
	?></td>
    <td><?php echo $models[$i]['child'] ?></td>
    <td>&nbsp;</td>
    <td><?php
	//echo strtolower(trim($models[$i]['parent']));
	$qqq = strtolower(trim($models[$i]['parent']));
	//echo '- ' . $qqq;

	if(isset($categories_list[$qqq])) {
			
			 echo $categories_list[$qqq]['category_name'];
			 echo '<br>';
			 //echo "<img = src\"/".."\">";
			 //echo $categories_list[$qqq]['category_id'];
				$ddd = explode('.', basename($file));
			
			 $file_trade_x = $_SERVER['DOCUMENT_ROOT'].'/pictures/group_ico/'.$categories_list[$qqq]['category_id'];//.'.'.$ddd[1];
			 echo '<br>'.$file_trade_x;
			// @unlink($file_trade_x = $_SERVER['DOCUMENT_ROOT'].'/pictures/group_ico/'.$categories_list[$qqq]['category_id'].'.jpg');
			// @unlink($file_trade_x = $_SERVER['DOCUMENT_ROOT'].'/pictures/group_ico/'.$categories_list[$qqq]['category_id'].'.png');
			// @unlink($file_trade_x = $_SERVER['DOCUMENT_ROOT'].'/pictures/group_ico/'.$categories_list[$qqq]['category_id'].'.gif');
			 copy($file,  $file_trade_x.'.'.$ddd[1]);
			
	}
	else $this->create_category($models[$i]['parent']);
	?></td>
    <td>&nbsp;</td>
    <td><?php
    $qqq = strtolower(trim($models[$i]['child']));
	if(isset($categories_list[$qqq])) echo $categories_list[$qqq]['category_name'];
	else  $this->create_category_child($models[$i]['child'], $models[$i]['parent']);
	?></td>
    </tr>
	<?php
	//}
}/////////for($i=0; $i<count($models); $i++) {
?>
</table>
<?php
echo CHtml::endform();
?>