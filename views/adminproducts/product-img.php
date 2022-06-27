<?php echo CHtml::beginForm(array('/adminproducts/product_update_img/'.$product->id.'/?group='.$group),  $method='post',$htmlOptions=array('name'=>'MainParams', 'enctype'=>'multipart/form-data'));  ?>
<table width="100%" border="0" cellspacing="1" cellpadding="1">
  <tr>
    <td width="125" align="left" valign="top">Главная картинка</td>
    <td valign="top"><?
    $filename_gif = $_SERVER['DOCUMENT_ROOT'].Yii::app()->request->baseUrl.'/pictures/img/'.$product->id.'.gif';
	$filename_jpg = $_SERVER['DOCUMENT_ROOT'].Yii::app()->request->baseUrl.'/pictures/img/'.$product->id.'.jpg';
	$filename_png = $_SERVER['DOCUMENT_ROOT'].Yii::app()->request->baseUrl.'/pictures/img/'.$product->id.'.png';
	$exist_gif = file_exists($filename_gif);
	$exist_jpg = file_exists($filename_jpg);
	$exist_png= file_exists($filename_png);
	if ($exist_gif==false AND $exist_jpg==false AND $exist_png==false) {/////////////Файл не существует, нужно рисовать элемент для закачки
			echo CHtml::fileField('fileimg');
	}//////////Файл не существует, нужно рисовать элемент для закачки
	else {////////////////////Иначе рисуем картинку
			if ($exist_png==true) {
				$filename = $filename_png;
				$filesrc = Yii::app()->request->baseUrl.'/pictures/img/'.$product->id.'.png?v='.rand();
			}
			elseif($exist_jpg==true) {
				$filename = $filename_jpg;
				$filesrc = Yii::app()->request->baseUrl.'/pictures/img/'.$product->id.'.jpg?v='.rand();
			}
			elseif($exist_gif==true) {
				$filename = $filename_gif;
				$filesrc = Yii::app()->request->baseUrl.'/pictures/img/'.$product->id.'.gif?v='.rand();
			}
			//echo $filename;
			//$filesize=filesize($filename);
			//print_r($filesize);
			echo "<img src=\"$filesrc\" style=\"max-width:500px\">";
	}//////////////////else {//////Иначе рисуем картинку
	?></td>
    <td align="center" valign="top"><img src="<?=Yii::app()->request->baseUrl?>/images/delete.gif" width="13" height="13"></td>
    <td align="center" valign="top"><?php echo CHtml::checkBox('del_img', 0)?></td>
  </tr>
  <tr>
    <td width="125" align="left" valign="top">Большая иконка (ширина 200)</td>
    <td valign="top"><?
    $filename_gif = $_SERVER['DOCUMENT_ROOT'].Yii::app()->request->baseUrl.'/pictures/img_med/'.$product->id.'.gif';
	$filename_jpg = $_SERVER['DOCUMENT_ROOT'].Yii::app()->request->baseUrl.'/pictures/img_med/'.$product->id.'.jpg';
	$filename_png = $_SERVER['DOCUMENT_ROOT'].Yii::app()->request->baseUrl.'/pictures/img_med/'.$product->id.'.png';
	$exist_gif = file_exists($filename_gif);
	$exist_jpg = file_exists($filename_jpg);
	$exist_png= file_exists($filename_png);
	if ($exist_gif==false AND $exist_jpg==false AND $exist_png==false) {/////////////Файл не существует, нужно рисовать элемент для закачки
			echo 'создать миниатюру - '.CHtml::checkBox('create_img_med', 0);
	}//////////Файл не существует, нужно рисовать элемент для закачки
	else {////////////////////Иначе рисуем картинку
			if ($exist_png==true) {
				$filename = $filename_png;
				$filesrc = Yii::app()->request->baseUrl.'/pictures/img_med/'.$product->id.'.png?v='.rand();
			}
			elseif($exist_jpg==true) {
				$filename = $filename_jpg;
				$filesrc = Yii::app()->request->baseUrl.'/pictures/img_med/'.$product->id.'.jpg?v='.rand();
			}
			elseif($exist_gif==true) {
				$filename = $filename_gif;
				$filesrc = Yii::app()->request->baseUrl.'/pictures/img_med/'.$product->id.'.gif?v='.rand();
			}
			//echo $filename;
			//$filesize=filesize($filename);
			//print_r($filesize);
			echo "<img src=\"$filesrc\" style=\"max-width:500px\">";
	}//////////////////else {//////Иначе рисуем картинку
	?></td>
    <td align="center" valign="top"><img src="<?=Yii::app()->request->baseUrl?>/images/delete.gif" width="13" height="13"></td>
    <td align="center" valign="top"><?php echo CHtml::checkBox('del_img_med', 0)?></td>
  </tr>
  <tr>
    <td width="125" align="left" valign="top">Маленькая иконка (высота 100)</td>
    <td valign="top"><?
    $filename_gif = $_SERVER['DOCUMENT_ROOT'].Yii::app()->request->baseUrl.'/pictures/img_small/'.$product->id.'.gif';
	$filename_jpg = $_SERVER['DOCUMENT_ROOT'].Yii::app()->request->baseUrl.'/pictures/img_small/'.$product->id.'.jpg';
	$filename_png = $_SERVER['DOCUMENT_ROOT'].Yii::app()->request->baseUrl.'/pictures/img_small/'.$product->id.'.png';
	$exist_gif = file_exists($filename_gif);
	$exist_jpg = file_exists($filename_jpg);
	$exist_png= file_exists($filename_png);
	if ($exist_gif==false AND $exist_jpg==false AND $exist_png==false) {/////////////Файл не существует, нужно рисовать элемент для закачки
			echo 'создать миниатюру - '.CHtml::checkBox('create_img_small', 0);
	}//////////Файл не существует, нужно рисовать элемент для закачки
	else {////////////////////Иначе рисуем картинку
			if ($exist_png==true) {
				$filename = $filename_png;
				$filesrc = Yii::app()->request->baseUrl.'/pictures/img_small/'.$product->id.'.png?v='.rand();
			}
			elseif($exist_jpg==true) {
				$filename = $filename_jpg;
				$filesrc = Yii::app()->request->baseUrl.'/pictures/img_small/'.$product->id.'.jpg?v='.rand();
			}
			elseif($exist_gif==true) {
				$filename = $filename_gif;
				$filesrc = Yii::app()->request->baseUrl.'/pictures/img_small/'.$product->id.'.gif?v='.rand();
			}
			//echo $filename;
			//$filesize=filesize($filename);
			//print_r($filesize);
			echo "<img src=\"$filesrc\" style=\"max-width:500px\">";
	}//////////////////else {//////Иначе рисуем картинку
	?></td>
    <td align="center" valign="top"><img src="<?=Yii::app()->request->baseUrl?>/images/delete.gif" width="13" height="13"></td>
    <td align="center" valign="top"><?php echo CHtml::checkBox('del_img_small', 0)?></td>
  </tr>
  <tr>
    <td width="125" align="left" valign="top">&nbsp;</td>
    <td align="center" valign="top">&nbsp;</td>
    <td align="center" valign="top">&nbsp;</td>
    <td align="center" valign="top">&nbsp;</td>
  </tr>
  <tr>
    <td width="125" align="left" valign="top">&nbsp;</td>
    <td align="center" valign="top">&nbsp;</td>
    <td align="center" valign="top">&nbsp;</td>
    <td align="center" valign="top">&nbsp;</td>
  </tr>
  <tr>
    <td width="125" align="left" valign="top">&nbsp;</td>
    <td align="center" valign="top">&nbsp;</td>
    <td align="center" valign="top">&nbsp;</td>
    <td align="center" valign="top">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="4" align="center" valign="top"><?
      echo CHtml::submitButton('Сохранить', $htmlOptions=array ('name'=>'process_images' , 'alt'=>'Сохранить', 'title'=>'Сохранить'));
	?></td>
  </tr>
</table>
<?php echo CHtml::endForm(); ?>