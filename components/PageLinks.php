<table width="auto" border="0" cellspacing="0" cellpadding="1" class="plain">
<?
//mysql_query("SET NAMES cp1251",$cn);
    $query = "SELECT pages.id, pages.name, pages.keywords  FROM pages 
	WHERE pages.active =  1 AND pages.section=2 ORDER BY pages.sort";
	$res=mysql_query($query,$cn);
	if (!@$res) echo "ERROR IN $query ".mysql_error();
	while ($next=mysql_fetch_row($res)) {
	echo "<tr";
	//if (isset($sp)) if (intval($sp)==$next[0]) echo " bgcolor=\"#E9E5D9\"";
	echo"><td>&nbsp;</td>
	<td valign=\"top\"><li></td>
    <td valign=\"top\"><a title=\"$next[2]\" alt=\"$next[2]\" href=\"http://".$_SERVER['HTTP_HOST']."/pages/?sp=$next[0]\">$next[1]</a></td>
  </tr>";
	}
	
	?>
</table>

