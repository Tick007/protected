<?
$clientScript=Yii::app()->clientScript;
$clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/highslide/highslide-with-gallery.js'
, CClientScript::POS_HEAD);
$clientScript->registerCSSFile(Yii::app()->request->baseUrl.'/js/highslide/highslide.css');
?>
<script type="text/javascript"> 
hs.graphicsDir = '/js/highslide/graphics/';
hs.align = 'center';
hs.transitions = ['expand', 'crossfade'];
hs.outlineType = 'rounded-white';
hs.fadeInOut = true;
hs.numberPosition = 'caption';
hs.dimmingOpacity = 0.75;
 
// Add the controlbar
if (hs.addSlideshow) hs.addSlideshow({
	//slideshowGroup: 'group1',
	interval: 5000,
	repeat: false,
	useControls: true,
	fixedControls: 'fit',
	overlayOptions: {
		opacity: .75,
		position: 'bottom center',
		hideOnMouseOut: true
	}
});
</script>
<?php echo CHtml::beginForm(array('/adminproducts/product_files/', 'id'=>$product->id, 'group'=>$group),  $method='post',$htmlOptions=array('name'=>'ProductFiles',  'enctype'=>'multipart/form-data'));  

$fotos=$product->files;
?>
<table width="100%" border="0">
<?
if (count($fotos)) {
?>
  <tr>
    <td>Удалить</td>
    <td>Загружен</td>
    <td width="200">Файл</td>
    <td width="200">Размеры</td>
    <td width="200">Тип</td>
    <td>Миниатюра</td>
  </tr>
  <?
  for ($i=0; $i<count($fotos);$i++) {
    $img_field_name = "imgfile[".$fotos[$i]->id."]";//////////////////название для поля файла
  //echo $img_field_name.'<br>';
  $img_field_del = "imgdel[".$fotos[$i]->id."]";/////////////////////название для чекбокса удаления
  //echo $img_field_del.'<br>';
  //$img = 'http://'.$_SERVER['HTTP_HOST'].'/'.Yii::app()->params->drupal_vars['file_path'].$fotos[$i]->filename;
  //$img_file = $_SERVER['DOCUMENT_ROOT'].'/'.Yii::app()->params->drupal_vars['file_path'].$fotos[$i]->filename;
  $img = 'http://'.$_SERVER['HTTP_HOST'].'/'.$fotos[$i]->filepath;
  $img_file = $_SERVER['DOCUMENT_ROOT'].'/'.$fotos[$i]->filepath;
// echo $img.'<br>';
  //echo $img_file.'<br>';
	
  ?>
  <tr>
    <td><?
    echo CHtml::checkBox($img_field_del, $checked=false);
	?></td>
	<td><?php 
	//echo $fotos[$i]->timestamp;
	echo date("d/m/Y, H:i:s", $fotos[$i]->timestamp);
	?></td>
    <td><?
	//echo $img_file;
	//echo $img_file;
    if (file_exists($img_file)){  
		echo "<a href=\"$img\"><font color=\"#0033CC\"><strong>".$fotos[$i]->filename."</font></strong></a>";
	}
	else {
		 //echo CHtml::fileField($img_field_name , 'Загрузка');
		 echo "Информация о файле присутствует, но файл не обнаружен";
	}
	?></td>
    <td><?
    if ($fotos[$i]->filemime=='image/jpeg') {
			//print_r(getimagesize($fotos[$i]->filepath));
			if (is_file($fotos[$i]->filepath) AND file_exists($fotos[$i]->filepath)) {
					$size=getimagesize($fotos[$i]->filepath);
					$w=(int)$size[0]; // ширина
					$h=(int)$size[1]; // высота
					echo "Ш:$w<br>В:$h";
			}//////////if (is_file($fotos[$i]->filepath) AND file_exists($fotos[$i]->filepath)) {
	}
	?></td>
    <td><?php
    echo CHtml::listBox("filetype1[".$fotos[$i]->id."]", $fotos[$i]->filetype1, $this->faletype1, array('size'=>'1')); 
	?></td>
    <td><?
	if ($fotos[$i]->filemime=='image/jpeg') {/////if3
	$img = '/'.$fotos[$i]->filepath;
	$img_file = $_SERVER['DOCUMENT_ROOT'].$fotos[$i]->filepath;
	$srctfile = $fotos[$i]->filepath;
	$resize_to="width=200";
	$make_mini_path = "http://".$_SERVER['HTTP_HOST']."/pictures/make_mini.php?create=0&$resize_to&imgname=$srctfile";
    echo "<div  align=\"center\"  ><a id=\"thumb1\" class=\"highslide\" onclick=\"return hs.expand(this, { slideshowGroup: 3 } )\" href=\"$img\" title=\"$model->name\"><img class=\"img_max_200\" title=\"$model->name\" alt=\"$model->name\" border=\"0\" src=\"$make_mini_path\"></a>
				</div>";
	}////////if ($fotos[$i]->filemime=='image/jpeg') {/////if3
	?>    </td>
  </tr>
<?
}////////////////////  for ($i=0; $i<count($fotos_array);$i++) {
}
//else  {////if (count($fotos)) {////Если нет файлов
?>
  <tr>
    <td colspan="5" align="center"><br>
    <hr>
    Добавить файл - 
      <?
       echo CHtml::fileField('create_new_file' , 'Загрузка');
	  ?></td>
  </tr>
  <tr>
    <td colspan="5" align="left" valign="top"><?
      echo CHtml::submitButton('Сохранить', $htmlOptions=array ('name'=>'save_files' , 'alt'=>'Сохранить', 'title'=>'Сохранить'));
	?></td>
  </tr>
</table>
<?php echo CHtml::endForm(); ?>
