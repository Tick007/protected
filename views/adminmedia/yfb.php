<link rel="stylesheet" type="text/css" href="/css/ui-lightness/jquery-ui-1.8.7.custom.css">
<script type="text/javascript" src="/js/jquery-ui-1.8.7.custom.min.js"></script>
<div style="background-color:#FFF">
<script>
$(document).ready(function(){

$('#dialog').dialog({
					autoOpen: false,
					width: 400,
					modal: true,
					resizable: false,
					buttons: {
						"Удалить": function() {
							//document.testconfirmJQ.submit();
							$('form').submit();
						},
						"Отмена": function() {
							$(this).dialog("close");
						}
					}
				});
				
$('#rename-dialog').dialog({ //////////////////переименование
					autoOpen: false,
					width: 400,
					modal: true,
					resizable: false,
					buttons: {
						"Переименовать": function() {
							//alert(document.getElementById('active_item').value);
							document.getElementById('active_item').value = document.getElementById('new_name').value ;
							old_name = document.getElementById('ren_file_name').value;
							new_name = document.getElementById('active_item').value;/////////////поле с новым значением
							if (old_name !== '' && new_name !== '' && old_name !== null && new_name !== null ) {
									if (old_name !== new_name ) {
											document.getElementById('ren_file').value=1;
											$('form').submit();
									}
									else alert ('одинаковые');
							}////////if (old_name
						},
						"Отмена": function() {
							$(this).dialog("close");
						}
					}
				});
				
$('#create-folder-dialog').dialog({ //////////////////переименование
					autoOpen: false,
					width: 400,
					modal: true,
					resizable: false,
					buttons: {
						"Создать": function() {
							//alert(document.getElementById('active_item').value);
							document.getElementById('create_folder').value = document.getElementById('new_folder').value ;
							$('form').submit();
			
						},
						"Отмена": function() {
							$(this).dialog("close");
						}
					}
				});				


$("#del_file").click(function(){////////////////////////////Удаление файла
				active_item = document.getElementById('active_item').value;
				if(active_item!=='' && active_item!==null) {
						document.getElementById('delet_active').value=1;
						//document.forms[0].submit();
						//alert('ewr');
						 $('#dialog').dialog('open');
				 }//////if(active_item!=='' && active_item!==null) {
		});
		
$("#zoom_button").click(function(){////////////////////Открытие в новом окне
	if(document.getElementById('active_item').value!=='' && document.getElementById('active_item').value!==null) {
		//alert('zoom');
		//popImage('http://yii-site/pictures/img/213.png', 'werewrwer');
		<?
		$full_path = $folder;
	$srctfile = str_replace($_SERVER['DOCUMENT_ROOT'], '', $full_path);
	echo "srctfile='",$srctfile."';";
		?>
		url =  srctfile+document.getElementById('active_item').value;
		window.open (url,"mywindow","location=0,status=0,scrollbars=1,width=500,height=400");
	}/////////////
	else alert ('Выбери файл!');
});

$("#ren_file_button").click(function(){
	document.getElementById('new_name').value = document.getElementById('active_item').value;
	
	//alert (old_name);
	 $('#rename-dialog').dialog('open');
});

	$("#new_folder_button").click(function(){
		$('#create-folder-dialog').dialog('open');
	});
});


function UpdateOpener(id) {
//parent.opener.document.getElementById('add_product').innerHTML = id;
targetitem = '<?=@$targetitem?>';
targetform = '<?=@$targetform?>';

////////////теперь нужно вызвать функцию для обновления второго списка
window.parent.myfunc(id);
window.parent.document.getElementById(targetitem).value = id;/////////////////работает на hislide
}

function set_active(item_id){

		//////////////Сначала гасим предыдущий
		old_item = document.getElementById('active_item').value;
		//alert (old_item);
		if (old_item!=='' && old_item!==item_id) {
				old_tbl='tbl_'+old_item;
				document.getElementById(old_tbl).bgColor="#ffffff";
		}
		
		//alert (item_id);
		active_tbl='tbl_'+item_id;
		
		$('#imgnamesel').html(item_id);
		//alert (active_tbl);
		
		currentcolor = document.getElementById(active_tbl).bgColor;
		//alert (currentcolor);
		if (currentcolor==="#ffffff" || currentcolor==="#FFFFFF") {
			document.getElementById('active_item').value= item_id;
			document.getElementById('ren_file_name').value= item_id;
			document.getElementById(active_tbl).bgColor="#cccccc";
			//alert('set color to #ff0000');
			//alert (currentcolor );
			
		}
		else {
			if (currentcolor==="#cccccc") {
				document.getElementById('active_item').value= '';
				document.getElementById('ren_file_name').value= '';
			document.getElementById(active_tbl).bgColor="#ffffff";
			}
		}

		//alert(document.getElementById(active_tbl).bgColor);
}//////////////function set_active(item_id){
</script>


<table width="100%" border="0" cellspacing="3" cellpadding="0" bgcolor="#CFC7AD">
<?php echo CHtml::beginForm(array('/adminmedia/addfile/'),  $method='post',$htmlOptions=array('name'=>'AddFiles', 'enctype'=>'multipart/form-data'));
?>
 <div id="dialog" title="Удаление файла"><p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 0 0;">Удаление файла</span> Вы собираетесь удалить файл:</p></div>
  <div id="rename-dialog" title="Переименование файла"><p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 0 0;">Переименование файла</span> введите новое имя для файла:</p>
  <?php echo CHtml::textfield('new_name', NULL,  $htmlOptions=array('encode'=>true, 'size'=>30, 'id'=>'new_name' )  ) ?>
  </div>
  
  <!--Блок для диалога создания новой папки-->
  <div id="create-folder-dialog" title="Создание папки"><p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 0 0;">Создание папки</span> введите имя для папки:</p>
  <?php echo CHtml::textfield('new_folder', NULL,  $htmlOptions=array('encode'=>true, 'size'=>30, 'id'=>'new_folder' )  ) ?>
  </div>
  
  <tr>
    <td class="plain"><font color="#000000"><strong>YFB. Yii file browser. Медиа-менеджер</strong></font></td>
  </tr>
  <tr>
    <td ><table width="100%" border="0" cellspacing="5" cellpadding="0" bgcolor="#FFFBF0">
      <tr>
        <td class="plain"></td>
        <td colspan="2" class="plain"><div align="left" style="float:left">
          <input alt="Переименовать" title="Переименовать" type="button" style="background-image:url(/images/rename.png); background-repeat:no-repeat; text-align:center; vertical-align:middle; width:60px; height:20px" id="ren_file_button"/>
        </div>
          <div style="float:left; width:5px">&nbsp;</div>
          <div style="float:left">
            <input alt="Удалить" title="Удалить" type="button" name="del_file" id="del_file" style="background-image:url(/images/delete.gif); background-repeat:no-repeat; text-align:center; vertical-align:middle; width:20px; height:20px" />
          </div>
          <div style="float:left; width:5px">&nbsp;</div>
          <div style="float:left" >
            <input alt="Просмотреть" title="Просмотреть" type="button" id="zoom_button" style="background-image:url(/images/zoom.png); background-repeat:no-repeat; text-align:center; vertical-align:middle; width:25px; height:20px" />
          </div> <div style="float:left; width:5px">&nbsp;</div>
          <div style="float:left" >
            <input alt="Создать папку" title="Создать папку" type="button" id="new_folder_button" style="background-image:url(/images/folder-new.png); background-repeat:no-repeat; text-align:center; vertical-align:middle; width:25px; height:20px" />
          </div></td>
      </tr>
      <tr>
        <td width="200" rowspan="2" valign="top"  bgcolor="#898477"><table width="100%" border="0" cellspacing="0" cellpadding="1">
          <tr>
            <td valign="top" width="200">
  <table border="0" width="100%" cellspacing="0" cellpadding="0" class="plain" bgcolor="#FFFFF0">
    
    <tr>
      <td width="100%" colspan="3" valign="top">
  <?
$tree = new FileSys(@$targetform, @$targetitem);
?>
  </table></td>
            </tr>
</table></td>
        <td valign="top" style="width:400px">	<?  
		echo CHtml::hiddenField('folder', $folder);
		echo CHtml::hiddenField('delet_active', NULL, array('id'=>'delet_active'));
		echo CHtml::hiddenField('ren_file_name', NULL, array('id'=>'ren_file_name'));/////////////Сохранение значения имени файла который нужно переименовывать
		echo CHtml::hiddenField('ren_file', NULL, array('id'=>'ren_file'));////////////////Признак переименования
		echo CHtml::hiddenField('active_item', NULL, array('id'=>'active_item'));////////////////Активный элемент
		echo CHtml::hiddenField('create_folder', NULL, array('id'=>'create_folder'));////////////////Активный элемент
		?><?
        echo CHtml::fileField('new_file', 'Загрузка файла');
		?>
        <?
      echo CHtml::submitButton('Сохранить', $htmlOptions=array ('name'=>'process_images' , 'alt'=>'Сохранить', 'title'=>'Сохранить'));
	?>
  </td>
        <td valign="top">  Картинка: 
    &lt;img src=&quot;/themes/<?php echo Yii::app()->theme->name;?><?php
    $folderone = str_replace($_SERVER['DOCUMENT_ROOT'].'/themes/'.Yii::app()->theme->name, '', $folder);
	echo $folderone;
	?><span id="imgnamesel"></span>&quot;&gt;</td>
      </tr>
      <tr>
        <td colspan="2" valign="top" style="max-width:500px">
          
          
  <?
$x = count($files);
if ($x>0){
for ($i=0; $i<$x; $i++) {

?>
          
          
          <table bgcolor="#FFFFFF" height="75px" border="0" cellspacing="0" cellpadding="0" style="float:left; padding:2px" id="tbl_<?=$files[$i]?>">
            <tr>
              <td height="20" style="font-family:Arial Narrow" align="center"><? echo $files[$i]?></td>
              </tr>
            <tr>
              <td height="50" align="center" valign="middle" style="min-height:50px">
                
                <?
	
	$size = getimagesize($folder.$files[$i]);
	//print_r($size);
	//echo $size[mime];
	$full_path = $folder.$files[$i];
	$srctfile = str_replace($_SERVER['DOCUMENT_ROOT'], '', $full_path);
	if ($size[mime]!='image/gif' AND  $size[mime]!='image/png' AND $size[mime]!='image/jpeg') {
			echo "<img border=\"0\" width=\"40px\" src=\"/images/unknown.png\" onclick=\"{set_active('$files[$i]')}\">";
	}//////////Файл не существует, нужно рисовать элемент для закачки
	else {////////////////////Иначе рисуем картинку
			$resize_to="height=60";
			$make_mini_path = "http://".$_SERVER['HTTP_HOST']."/pictures/make_mini.php?create=0&$resize_to&imgname=$srctfile";
			//echo $make_mini_path;
			if ($size[0]>60) echo "<img title=\"$files[$i]\"  border=\"0\" src=\"$make_mini_path\" style=\"max-height:60px\" id=\"$files[$i]\" onclick=\"{set_active('$files[$i]')}\">";
			else echo"<img title=\"$files[$i]\"  border=\"0\" src=\"$srctfile\" style=\"max-height:60px\" id=\"$files[$i]\" onclick=\"{set_active('$files[$i]')}\">";
	
	}////////if (!preg_match('.php', $files[$i])  {
?>    </td>
              </tr>
  </table>
  <?

 }/////for
		}////////////////if (!preg_match('/.php/', $files[$i]))  {////////для всех кроме php
		else echo $folder.': '.$x.' файл';			
	?></td>
      </tr>
      <tr>
        <td colspan="3" valign="top"  bgcolor="#898477">
	     </td>
        </tr>
    </table></td> 
  </tr>
<?php echo CHtml::endForm(); ?>  
</table>
<script>
//document.getElementById('mainmenu').innerHTML = '';
//document.getElementById('shapka').innerHTML = '';
$('#mainmenu').html('');
$('#shapka').html('');
</script>
</div>