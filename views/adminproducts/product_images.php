<?php
$clientScript=Yii::app()->clientScript;
$clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/ajaxupload.3.5.js', CClientScript::POS_HEAD);
?>
<script>
$(document).ready(function() {



	
new AjaxUpload('#addfileimg', {
  // какому скрипту передавать файлы на загрузку? только на свой домен
  //action: '/nomenklatura/uploadimg',
  action: '/nomenklatura/ajaxupload',
  // имя файла
  name: 'addfileimg',
  // дополнительные данные для передачи
  data: {
    id : '<?php echo $product->id?>',
    example_key2 : 'example_value2'
  },
  // validation    
	// ex. ['jpg', 'jpeg', 'png', 'gif'] or []
	allowedExtensions:  ['jpg', 'jpeg', 'png', 'gif'],      
	sizeLimit: 5, // max size   
	//minSizeLimit: 0, // min size
  // авто submit
  autoSubmit: true,
  // формат в котором данные будет ответ от сервера .
  // HTML (text) и XML определяются автоматически .
  // Удобно при использовании  JSON , в таком случае устанавливаем параметр как "json" .
  // Также установите тип ответа (Content-Type) в text/html, иначе это не будет работать в IE6
  //responseType: false,
  responseType: false,
  // отправка файла сразу после выбора
  // удобно использовать если  autoSubmit отключен  
  onChange: function(file, extension){},
  // что произойдет при  начале отправки  файла 
  onSubmit: function(file, extension) {
	  $('#uploaded').html('<img src="/images/waitanim.gif">');
	  },
  // что выполнить при завершении отправки  файла
  onComplete: function(file, response) {
			//alert(response);
			//console.log(response);
			//$('#uploaded').text(response);
			//$('#uploaded').html(response);
			//
			var z = new Array();
			var z =response.split("#");
			$('#uploaded').text('');
			tr= '<tr><td valign="top">'+z[2]+'</td><td align="center" valign="top"><input value="'+z[2]+'" type="radio" name="main_icon" id="main_icon">	</td><td align="center" valign="top"><input value="'+z[2]+'" type="radio" name="vitrina_icon" id="vitrina_icon">	</td><td align="center" valign="top"><input value="'+z[2]+'" type="radio" name="sellout_icon" id="sellout_icon"></td><td align="center" valign="top"><input value="'+z[2]+'" type="radio" name="new_icon" id="new_icon"></td><td align="center" valign="top"><input value="'+z[2]+'" type="radio" name="vitrina_key_1" id="vitrina_key_1"></td><td align="center" valign="top">'+z[1]+'</td><td align="center" valign="top"><textarea rows="3" cols="30" style="font-family:Tahoma" name="comments['+z[0]+']" id="comments_'+z[1]+'"></textarea></td><td align="center" valign="top"><img src="/pictures/add/icons/'+z[0]+'.png" style="max-width:150px"></td><td align="center" valign="top"><input type="checkbox" value="0" name="delete_icon['+z[0]+']" id="delete_icon_'+z[0]+'">	</td><td align="center" valign="top"><input type="checkbox" value="0" name="create_icon['+z[0]+']" id="create_icon_'+z[0]+'"></td></tr>';
	$('#images_table tbody').append(tr);
	  }
});



});

</script>
<?php // echo CHtml::beginForm(array('/adminproducts/product_update_img/'.$product->id.'/?group='.$group),  $method='post',$htmlOptions=array('name'=>'MainParams', 'enctype'=>'multipart/form-data'));


echo CHtml::fileField('addfileimg', 'Загрузка', array('id'=>'addfileimg'));
echo '&nbsp;&nbsp;&nbsp;&nbsp;';
?>
<br>
<div id="uploaded">&nbsp;</div>
<br>

<?php echo CHtml::beginForm(array('/adminproducts/product_update_img/'.$product->id.'/?group='.$group),  $method='post',$htmlOptions=array('name'=>'MainParams', 'enctype'=>'multipart/form-data'));  
  echo CHtml::submitButton('Сохранить', $htmlOptions=array ('name'=>'process_additional_images' , 'alt'=>'Сохранить', 'title'=>'Сохранить'));
?>

<table width="100%" border="0" cellspacing="2" cellpadding="1" id="images_table">
<TBODY>
	<tr bgcolor="#999999">
		<td align="left" valign="top"><span class="стиль1">id</span></td>
		<td align="center" valign="top">Главная</td>
		<td align="center" valign="top">Витрина</td>
		<td align="center" valign="top">Распродажа</td>
		<td align="center" valign="top">Новинка</td>
		<td align="center" valign="top">Хит</td>
		<td align="center" valign="top"><span class="стиль1">Расширение</span>
		</td>
		<td align="center" valign="top">Описание</td>
		<td align="center" valign="top"><span class="стиль1">Миниатюра</span>
		</td>
		<td align="center" valign="top"><span class="стиль1">удаление</span>
		</td>
		<td align="center" valign="top"><span class="стиль1">Пересоздать</span>
		</td>
	</tr>
	<?



	for ($i=0; $i<count($linked_pictures); $i++) {
		?>
	<tr>
		<td align="left" valign="top"><?=$linked_pictures[$i]->id?></td>
		<td align="center" valign="top"><?php echo CHtml::radioButton('main_icon', ($linked_pictures[$i]->is_main==1), array('value'=>$linked_pictures[$i]->id))?>
		</td>
		<td align="center" valign="top"><?php echo CHtml::radioButton('vitrina_icon', ($linked_pictures[$i]->is_vitrina==1), array('value'=>$linked_pictures[$i]->id))?>&nbsp;</td>
		<td align="center" valign="top"><?php echo CHtml::radioButton('sellout_icon', ($linked_pictures[$i]->is_sellout==1), array('value'=>$linked_pictures[$i]->id))?>&nbsp;</td>
		<td align="center" valign="top"><?php echo CHtml::radioButton('new_icon', ($linked_pictures[$i]->is_new==1), array('value'=>$linked_pictures[$i]->id))?></td>
		<td align="center" valign="top"><?php echo CHtml::radioButton('vitrina_key_1', ($linked_pictures[$i]->vitrina_key_1==1), array('value'=>$linked_pictures[$i]->id))?></td>
		<td align="center" valign="top"><?=$linked_pictures[$i]->img->ext?>
		</td>
		<td align="center" valign="top"><?php echo CHtml::textarea('comments['.$linked_pictures[$i]->picture.']', $linked_pictures[$i]->img->comments,  $htmlOptions=array('encode'=>true, 'rows'=>3, 'cols'=>30, 'style'=>"font-family:Tahoma" )  ) ?>
		</td>
		<td align="center" valign="top"><?
		$filename = Yii::app()->request->baseUrl."/pictures/add/".$linked_pictures[$i]->picture.'.'.$linked_pictures[$i]->img->ext;
		//echo $_SERVER['DOCUMENT_ROOT'].$filename.'<br>';
		//echo file_exists($_SERVER['DOCUMENT_ROOT'].$filename).'<br>';
		if (!@file_exists($_SERVER['DOCUMENT_ROOT'].$filename) ) {
			//////////Проверка, есть ли сама картинка
			echo "Есть запись в базе, но файл не найден";
		}//////////if (!@file_exists($_SERVER['DOCUMENT_ROOT'].$filename)) {//////////Проверка, есть ли сама картинка
		else {///////////}//////////if (!@file_e
			$iconname = Yii::app()->request->baseUrl."/pictures/add/icons/".$linked_pictures[$i]->picture.'.png';
			//echo $_SERVER['DOCUMENT_ROOT'].$iconname;
			if (file_exists($_SERVER['DOCUMENT_ROOT'].$iconname)==1) echo "<img src=\"$iconname\"  style=\"max-width:150px\"/>";
			else echo "Не найдена миниатюра, нужно пересоздать. Отметте галочку <strong>\"пересоздать\"</strong> в последнем столбце и нажмите кнопку <strong>\"сохранить\"</strong>";
		}//////////////////else {///////////}//////////if (!@file_e
		?>
		</td>
		<td align="center" valign="top"><?php echo CHtml::checkBox('delete_icon['.$linked_pictures[$i]->picture.']', 0)?>
		</td>
		<td align="center" valign="top"><?php echo CHtml::checkBox('create_icon['.$linked_pictures[$i]->picture.']', !@file_exists($_SERVER['DOCUMENT_ROOT'].$iconname) ? 1 : 0)?>
		</td>
	</tr>
	<?
	}//////////////////////  for ($i=0; $i<count($linked_pictures); $i++) {
	?>
    </TBODY>
</table>

<?
      echo CHtml::submitButton('Сохранить', $htmlOptions=array ('name'=>'process_additional_images' , 'alt'=>'Сохранить', 'title'=>'Сохранить'));
	?>

<?php echo CHtml::endForm(); ?>