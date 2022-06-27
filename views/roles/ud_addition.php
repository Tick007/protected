<table width="auto" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td bgcolor="#d5a73f">Поле</td>
    <td bgcolor="#d5a73f">Значение</td>
  </tr>



<?

for ($i=0; $i<count($profile_values); $i++) $values[$profile_values[$i]->fid]=$profile_values[$i]->value;

for ($i=0; $i<count($FIELDS); $i++) {
  echo "<tr>
    <td>".$FIELDS[$i]->title."</td>
    <td>";
	echo CHtml::textField('value['.$FIELDS[$i]->fid.']', $values[$FIELDS[$i]->fid], $htmlOptions=array('encode'=>false, 'size'=>30) );
	echo "</td>
  </tr>";
}
?></table>