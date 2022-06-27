<?php echo CHtml::beginForm(array('/adminproducts/product_update_img/'.$product->id.'/?group='.$group),  $method='post',$htmlOptions=array('name'=>'MainParams', 'enctype'=>'multipart/form-data'));  
?>
<style type="text/css">
<!--
.стиль1 {color: #FFFFFF}
-->
</style>


<?
echo 'Новый файл: '.CHtml::fileField('addfileimg');
echo '&nbsp;&nbsp;&nbsp;&nbsp;';
echo CHtml::submitButton('Сохранить', $htmlOptions=array ('name'=>'process_additional_images' , 'alt'=>'Сохранить', 'title'=>'Сохранить'));
?>
<br><br>
<table width="auto" border="0" cellspacing="2" cellpadding="1">
  <tr bgcolor="#999999">
    <td align="left" valign="top"><span class="стиль1">id</span></td>
    <td align="center" valign="top"><span class="стиль1">Расширение</span></td>
    <td align="center" valign="top"><span class="стиль1">Миниатюра</span></td>
    <td align="center" valign="top"><span class="стиль1">удаление</span></td>
    <td align="center" valign="top"><span class="стиль1">Пересоздать</span></td>
  </tr>
  <?
  for ($i=0; $i<count($linked_pictures); $i++) {
  ?>
  <tr>
    <td align="left" valign="top"><?=$linked_pictures[$i]->id?></td>
    <td align="center" valign="top"><?=$linked_pictures[$i]->ext?></td>
    <td align="center" valign="top"><?
    $filename = Yii::app()->request->baseUrl."/pictures/add/".$linked_pictures[$i]->id.'.'.$linked_pictures[$i]->ext;
	//echo $_SERVER['DOCUMENT_ROOT'].$filename.'<br>';
	//echo file_exists($_SERVER['DOCUMENT_ROOT'].$filename).'<br>';
	if (!@file_exists($_SERVER['DOCUMENT_ROOT'].$filename) ) {//////////Проверка, есть ли сама картинка
			echo "Есть запись в базе, но файл не найден";
	}//////////if (!@file_exists($_SERVER['DOCUMENT_ROOT'].$filename)) {//////////Проверка, есть ли сама картинка
	else {///////////}//////////if (!@file_e
			$iconname = Yii::app()->request->baseUrl."/pictures/add/icons/".$linked_pictures[$i]->id.'.png';
			//echo $_SERVER['DOCUMENT_ROOT'].$iconname;
			if (file_exists($_SERVER['DOCUMENT_ROOT'].$iconname)==1) echo "<img src=\"$iconname\" />";
			else echo "Не найдена миниатюра, нужно пересоздать";
	}//////////////////else {///////////}//////////if (!@file_e
	?></td>
    <td align="center" valign="top"><?php echo CHtml::checkBox('delete_icon['.$linked_pictures[$i]->id.']', 0)?></td>
    <td align="center" valign="top"><?php echo CHtml::checkBox('create_icon['.$linked_pictures[$i]->id.']', !@file_exists($_SERVER['DOCUMENT_ROOT'].$iconname) ? 1 : 0)?></td>
  </tr>
  <?
  }//////////////////////  for ($i=0; $i<count($linked_pictures); $i++) {
  ?>
  <tr>
    <td colspan="5" align="center" valign="top"><?
      echo CHtml::submitButton('Сохранить', $htmlOptions=array ('name'=>'process_additional_images' , 'alt'=>'Сохранить', 'title'=>'Сохранить'));
	?></td>
  </tr>
</table>
<?php echo CHtml::endForm(); ?>