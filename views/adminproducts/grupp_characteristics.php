
<?
$clientScript=Yii::app()->clientScript;
$clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/highslide/highslide-with-html.js', CClientScript::POS_HEAD);
$clientScript->registerCssFile(Yii::app()->request->baseUrl.'/js/highslide/highslide.css');
?>
<script>
hs.graphicsDir = '<?=Yii::app()->request->baseUrl?>/js/highslide/graphics/';
hs.outlineType = 'rounded-white';
hs.wrapperClassName = 'draggable-header';
hs.minWidth = 600;
hs.minHeight = 600;
hs.height =600;
hs.width= 600;
</script>


<?php echo CHtml::beginForm(Yii::app()->request->baseUrl.'/adminproducts/updategroup/'.Yii::app()->getRequest()->getParam('id'),  $method='post',$htmlOptions=array('name'=>'EditPage', 'enctype'=>'multipart/form-data'));  ?>
<?
//echo count($grupp_characteristics);
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <th width="20%" align="left" scope="row">Наименование группы</th>
    <td><?php echo CHtml::textfield('category_name', $gruppa->category_name,  $htmlOptions=array('encode'=>true, 'size'=>50, )  ) ?></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <th align="left" scope="row">
    <?php
    if (isset(Yii::app()->params['group_logo_x_limit'])) $limit_x = Yii::app()->params['group_logo_x_limit'];
											else $limit_x=140;
											if (isset(Yii::app()->params['group_logo_y_limit'])) $limit_y = Yii::app()->params['group_logo_y_limit'];
											else $limit_y=140;
	?>
    Лого группы ( до <?php echo  "$limit_x x $limit_y" ?>)</th>
    <td><?php
    $filename_png = Yii::app()->request->baseUrl."/pictures/group_ico/".Yii::app()->getRequest()->getParam('id').'.png';
	 $filename_jpg = Yii::app()->request->baseUrl."/pictures/group_ico/".Yii::app()->getRequest()->getParam('id').'.jpg';
	if (file_exists($_SERVER['DOCUMENT_ROOT'].$filename_png) AND is_file($_SERVER['DOCUMENT_ROOT'].$filename_png) )  $filename= $filename_png;
	else if (file_exists($_SERVER['DOCUMENT_ROOT'].$filename_jpg) AND is_file($_SERVER['DOCUMENT_ROOT'].$filename_jpg) )  $filename= $filename_jpg;
	
	if (!@file_exists($_SERVER['DOCUMENT_ROOT'].$filename ) ) {//////////Проверка, есть ли сама картинка
			 echo CHtml::fileField('logo', '',array('id'=>'not_logo'));
	}//////////if (!@file_exists($_SERVER['DOCUMENT_ROOT'].$filename)) {//////////Проверка, есть ли сама картинка
	else {///////////}//////////if (!@file_e
			if ( isset($filename) AND  file_exists($_SERVER['DOCUMENT_ROOT'].$filename) AND is_file($_SERVER['DOCUMENT_ROOT'].$filename) ) {
				$iconname = $filename;
			}
			//echo $_SERVER['DOCUMENT_ROOT'].$iconname;
			if ( isset ($iconname) AND  file_exists($_SERVER['DOCUMENT_ROOT'].$iconname)==1 AND is_file($_SERVER['DOCUMENT_ROOT'].$iconname) ) {
					echo "<img src=\"$iconname?v=".rand()."\" />";
					echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Удалить - '.CHtml::checkBox('del_logo', false);
					}
			else echo CHtml::fileField('logo');
	}//////////////////else {///////////}//////////if (!@file_e
	?></td>
    <td>&nbsp;</td>
  </tr>
  <tr bgcolor="#DAFEEF">
	  <th align="left" scope="row">Title, keywords, description</th>
	  <td valign="top"><?php echo CHtml::textfield('title', $gruppa->title,  $htmlOptions=array('encode'=>true, 'size'=>25, )  ) ?>
&nbsp;&nbsp;&nbsp;&nbsp;
<?php
echo CHtml::textarea('keywords',  htmlspecialchars_decode($gruppa->keywords),  $htmlOptions=array('encode'=>true, 'rows'=>3, 'cols'=>30)  );
?>      &nbsp;&nbsp;&nbsp;&nbsp;
<?php
echo CHtml::textarea('description',  htmlspecialchars_decode($gruppa->description),  $htmlOptions=array('encode'=>true, 'rows'=>3, 'cols'=>30)  );
?>      
           </td>
	  <td>&nbsp;</td>
  </tr>
  <tr>
		<th align="left" scope="row">Алиас</th>
		<td><?php echo CHtml::textfield('alias', $gruppa->alias,  $htmlOptions=array('encode'=>true, 'size'=>50, )  ) ?>
			&nbsp;<?php echo CHtml::checkBox('auto_alias', 0)?> -
			Сгенерить автоматом</td>
		<td>&nbsp;</td>
	</tr>
  <tr bgcolor="#DAFEEF">
    <th width="20%" align="left" scope="row">Родительская группа</th>
    <td>
	<?php
	
	$cat_bel =new ProductGroup($all_groups,  'category_parent', $gruppa->parent , 'simple', 'EditPage'); 
	$cat_bel->Draw();
	
	?></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <th align="left" scope="row">Связанная страница</th>
    <td><?php
   // print_r($linked_pages);
   if (isset($linked_pages)) echo CHtml::dropDownList('linked_page', $gruppa->linked_page, $linked_pages);
	?></td>
    <td>&nbsp;</td>
  </tr>
  <tr bgcolor="#DAFEEF">
		<th align="left" scope="row">Путь</th>
		<td><?php
			if($path = @unserialize($gruppa->path)) print_r($path);
			else echo 'Ошибка пути. Пересохраните группу !';
	 ?>
		</td>
		<td>&nbsp;</td>
	</tr>
  <tr>
    <th width="20%" align="left" scope="row">Вкл/выкл</th>
    <td><?php echo CHtml::checkBox('show_category', $gruppa->show_category)?></td>
    <td>&nbsp;</td>
  </tr>
    <tr>
    <th width="20%" align="left" scope="row">Архивная группа</th>
    <td><?php echo CHtml::checkBox('archive', $gruppa->archive)?></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <th width="20%" align="left" valign="top" bgcolor="#DAFEEF" scope="row">Параметры<br>
    <nobr><?php echo CHtml::checkBox('add_char', 0)?> - добавить параметр</nobr><br><br>
     <nobr><?php echo CHtml::checkBox('new_sub', 0)?> - Создать подгруппу</nobr><br><br>
    <nobr><?php echo CHtml::checkBox('new_good', 0)?> - Созать товар в группе</nobr>      </th>
    <td valign="top" bgcolor="#DAFEEF"><table width="100%" border="0" cellspacing="1" cellpadding="1">
  <tr bgcolor="#999999">
    <td>Наименование</td>
    <td>Сортировка</td>
    <td >Общий</td>
    <td>Фильр</td>
    <td align="center"><img src="/images/delete.gif" width="13" height="13" /></tdh>  </tr>
  <?
  for ($i=0; $i<count($grupp_characteristics); $i++) {
  ?>
  <tr>
    <td><?
    echo CHtml::textfield('caract_name['.$grupp_characteristics[$i]->caract_id.']', $grupp_characteristics[$i]->caract_name,  $htmlOptions=array('encode'=>true, 'size'=>40, )  )
	?></td>
    <td align="center"><?
    echo CHtml::textfield('sort['.$grupp_characteristics[$i]->caract_id.']', $grupp_characteristics[$i]->sort,  $htmlOptions=array('encode'=>true, 'size'=>10, )  )
	?></td>
    <td align="center"><?php echo CHtml::checkBox('is_common['.$grupp_characteristics[$i]->caract_id.']', $grupp_characteristics[$i]->is_common);
	?></td>
    <td align="center"><?php
	if (@!$grupp_characteristics[$i]->is_common) echo CHtml::checkBox('is_main['.$grupp_characteristics[$i]->caract_id.']', $grupp_characteristics[$i]->is_main);
	?></td>
    <td align="center"><?php echo CHtml::checkBox('del_car['.$grupp_characteristics[$i]->caract_id.']', 0);
	?></td>
  </tr>
  <?
  }//////////for ($i=0; $i<count($grupp_characteristics); $i++) {
  ?>
</table></td>
    <td valign="top" bgcolor="#DAFEEF">&nbsp;</td>
  </tr>
  <tr>
    <th align="left" valign="top" scope="row">Прайс листы
    <br>
    <?
    echo 'Новый файл: '.CHtml::fileField('addfile');
	?></th>
    <td valign="top"><?
    //echo count($gruppa_files);
	?>
    <!--Таблица с файлами-->
    <table width="100%" border="0" cellspacing="1" cellpadding="1">
  <tr bgcolor="#999999">
    <td align="left" valign="top"><span class="стиль1">id</span></td>
    <td align="center" valign="top"><span class="стиль1">Расширение</span></td>
    <td align="center" valign="top"><span class="стиль1">Наименовани</span></td>
    <td align="center" valign="top"><span class="стиль1">Удаление</span></td>
    <td align="center" valign="top"><span class="стиль1">Удаление связи</span></td>
  </tr>
  <?
  $linked_pictures = $gruppa_files;
  for ($i=0; $i<count($linked_pictures); $i++) {
  ?>
  <tr>
    <td align="left" valign="top"><?=$linked_pictures[$i]->id?></td>
    <td align="center" valign="top"><?=$linked_pictures[$i]->ext?></td>
    <td align="center" valign="top"><?
	echo $linked_pictures[$i]->description;   
	?></td>
    <td align="center" valign="top"><?php echo CHtml::checkBox('delete_file['.$linked_pictures[$i]->id.']', 0)?></td>
    <td align="center" valign="top"><?php echo CHtml::checkBox('delete_link['.$linked_pictures[$i]->id.']', 0)?></td>
  </tr>
  <?
  }//////////////////////  for ($i=0; $i<count($linked_pictures); $i++) {
  ?>
</table>
    <!--/Таблица с файлами-->    </td>
    <td valign="top">&nbsp;</td>
  </tr>
  
  <tr bgcolor="#DAFEEF">
    <th colspan="3" align="center" scope="row"><?
      echo CHtml::submitButton('Сохранить', $htmlOptions=array ('name'=>'Сохранить' , 'alt'=>'Сохранить', 'title'=>'Сохранить'));
	?></th>
  </tr>
</table>

<?php echo CHtml::endForm(); ?>