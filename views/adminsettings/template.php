<div id="ribbon" style="margin-left:71px">Общие настройки интернет магазина&nbsp;
</div>
<div id="Right_column" style="background-color:#666E73; width:60px; margin-left:0px">
<?
$RC = new RightColumnAdmin;
?>
</div>
<div id="mainContent" style="padding-left:3px; margin-left:70px ">
<?
$onof=array(
0=>'выкл',
1=>'вкл',
);
echo CHtml::form('/adminsettings/updatetemplate/', 'post');
?>
<table width="100%" border="1" cellspacing="1" cellpadding="1">
  <tr><td>&nbsp;</td>
  <?
  for ($ch=0; $ch<count($chapters); $ch++) {////////////Переборка разделов
  ?>
    <td><?
    echo $chapters[$ch]->chapter_description;
	?></td>
  <?
  }////////////  for ($ch=0; $ch<count($chapters); $ch++) {////////////Переборка разделов
  ?>
  </tr>
  <?
  for ($f=0; $f<count($files); $f++) {//////////////Перебираем строки, т.е. файлы
  		echo "<tr><td>".$files[$f]->name."(".$files[$f]->file.")".CHtml::Checkbox('addfile['.$files[$f]->id.']', false)."</td>";
				for ($ch=0; $ch<count($chapters); $ch++) {////////////Переборка разделов
				  ?>
					<td align="center"><?
					echo CHtml::listBox('switchof['.$block_rec[$chapters[$ch]->chapter_id][$files[$f]->id].']',   $block_status[$chapters[$ch]->chapter_id][$files[$f]->id], $onof, array('size'=>1));
					echo '|';
					echo CHtml::textField('sort['.$block_rec[$chapters[$ch]->chapter_id][$files[$f]->id].']', $block_sort[$chapters[$ch]->chapter_id][$files[$f]->id], array('size'=>5));
					echo '<br>';
					//echo CHtml::HiddenField('chapter['.$block_rec[$chapters[$ch]->chapter_id][$files[$f]->id].']', $chapters[$ch]->chapter_id);
					//echo CHtml::HiddenField('file['.$block_rec[$chapters[$ch]->chapter_id][$files[$f]->id].']', $files[$f]->id);
					?></td>
				  <?
				  }////////////  for ($ch=0; $ch<count($chapters); $ch++) {////////////Переборка разделов
		echo "</tr>";
  }///////////// for ($f=0; $f<count($files); $f++) {//////////////Перебираем строки, т.е. файлы
  ?>
</table>
<br><div align="right">
<?
echo CHtml::submitButton('сохранить');
?>
</div>
</form>
</div><!--<div id="mainContent" style="padding-left:3px; ">-->


<div style="height: 5px; clear:both">&nbsp;</div>

