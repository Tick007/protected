<table width="100%" border="0" cellspacing="1" cellpadding="1">
<?
for($i=0; $i<count($models);$i++) {
$qqq = explode(' ', $models[$i]->creation_date);
$qqq1 = explode('-', $qqq[0]);

?>

 <tr>
    <td width="16" valign="top"><img src="/images/rightarrow.png" border="0"/></td>
    <td valign="top"><?=$qqq1[2].'/'.$qqq1[1]?></td>
    <td width="100%" valign="top"><?
	if (isset($models[$i]->alais))  echo Chtml::link($models[$i]->title, array('/news/'.$models[$i]->alais));
	else echo Chtml::link($models[$i]->title, array('/news/'.$models[$i]->id));
	?></td>
  </tr>
<?
}
?></table>
